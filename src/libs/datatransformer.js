/**
 * datatransformer
 *
 * a library that transforme json data to information.
 * let you perfome grouping, filtering, measuring and
 * render on different type of visual that extends the 
 * library.
 *
 * @author  vigor situ lou
 * @version 0.1.0
 * @date    15/abr/2016
 *
 */

'use strict';
var prueba;

(function (window, $, math, callback) { 
    // Contants
    var MEASURE_COLUMN_PREFIX = "_",
        MEASURE_COLUMN_REGEXP = /_\w+_/ig;


    // Namespace
    var _datatransformer = function () { };

    // Visual Interface
    function visual() {
        this.renderOptions = {};
        this.render = function () { };
        this.refreshRender = function () { };
    }

    // Private variables
    // Object that contains every visual.
    var _obj = {
        visuals: {},
        visualNames: [],
    },
        _eventName = {
            onVisualAdded: 'onVisualAdded'
        },
        _events = {};

    // Excepcion Objects
    function _getCustomErrorBase(name, code, message) {
        return function (objErrorKey) {
            this.prototype = new Error();
            this.prototype.constructor = name;
            this.message = message + ', REFERENCE: ' + objErrorKey;
            this.code = code;
        }
    }

    var ERROR = {
        visualAlreadyExists: _getCustomErrorBase('visualException', '01', 'VISUAL ALREADY EXISTS'),
        visualNotExists: _getCustomErrorBase('visualException', '02', 'VISUAL NOT EXISTS'),
    }

    // Events Handle
    _events[_eventName.onVisualAdded] = [];

    _datatransformer.prototype[_eventName.onVisualAdded] = function (eventFunction) {
        if (typeof eventFunction === "function")
            _events[_eventName.onVisualAdded].push(eventFunction);
    }
    // Function that execute a event
    function _executeEvent(eventName) {
        (_events[eventName] || []).forEach(function (e) { e(); });
    }

    // Private functions
    // Function that checks if two objects are equivalent
    function _isEquivalent(a, b) {
        var _aProps = Object.getOwnPropertyNames(a),
            _bProps = Object.getOwnPropertyNames(b);

        if (_aProps.length != _bProps.length) {
            return false;
        }

        for (var i = 0; i < _aProps.length; i++) {
            var _propName = _aProps[i];

            if (a[_propName] !== b[_propName]) {
                return false;
            }
        }

        return true;
    }

    // Function that give a random number
    function _getRandomNumber(numberStart, numberEnd) {
        if (!numberStart || !numberEnd)
            return Math.random();
        else
            return Math.floor((Math.random() * numberEnd) + numberStart);
    }


    /**
     *  _round
     *
     *  Function that round a number.
     * 
     *  @param      Number  a number
     *  @param      Number  the number of digits to appear after the decimal point.
     *  @since      0.1.0
     */
    function _round(number, digits) {
        if (typeof digits === 'undefined' || +digits === 0)
            return Math.round(number);

        number = +number;
        digits = +digits;

        if (isNaN(number) || !(typeof digits === 'number' && digits % 1 === 0))
            return NaN;

        // Shift
        number = number.toString().split('e');
        number = Math.round(+(number[0] + 'e' + (number[1] ? (+number[1] + digits) : digits)));

        // Shift back
        number = number.toString().split('e');
        return +(number[0] + 'e' + (number[1] ? (+number[1] - digits) : -digits));
    }


    // Function that return the columns of a json
    function _getColumns(data) {
        return Object.keys(data[0] || {});
    }

    /**
     *  _getDistinct
     *
     *  Function that return the distinct rows of a column
     * 
     *  @param      JSON    data in json format
     *  @param      string  column name of the data 
     *  @since      0.1.0
     */
    function _getDistinct(data, column) {
        var _distinct = [];

        for (var d in data) {
            var _data = data[d][column];

            if (!_distinct.some(function (od) { return od == _data }))
                _distinct.push(_data);
        }

        return _distinct;
    }

    /**
     *  _getGroup
     *
     *  Function that return a array of the distinct rows of some groups
     * 
     *  @param      array   data in a array
     *  @param      array   array of column names
     *  @since      0.1.0
     */
    function _getGroups(data, groups) {
        var _groups = [];

        for (var d in data) {
            var _group = {};

            for (var group in groups) {
                var _groupName = groups[group];
                _group[_groupName] = data[d][_groupName];
            }

            if (!_groups.some(function (g) { return _isEquivalent(g, _group) }))
                _groups.push(_group);
        }

        return _groups;
    }

    /**
     *  _filterData
     *
     *  Function that return a data with filter applied
     * 
     *  @param      array   data in a array
     *  @param      object  filter option.
     *                      interface:
     *                          { filterColumn: [ filters ], filterColumn: [ filters ]...  }
     *  @since      0.1.0
     */
    function _filterData(data, optionFilter) {
        var _data = JSON.parse(JSON.stringify(data));;

        function _formatFilter(filter) {
            return ((typeof filter != "undefined" && filter != null) ? filter.toString().toLowerCase().replace(/\s/g, '') : '');
        }

        for (var filter in optionFilter) {
            var _filterString = optionFilter[filter];

            if (typeof _filterString['length'] != 'undefined' && _filterString['length'] > 0) {
                var _filterStringFormat = _filterString.map(function (x) { return _formatFilter(x); });

                _data = _data.filter(function (d) {
                    return _filterStringFormat.indexOf(_formatFilter(d[filter])) > -1;
                });
            }
        }
        return _data;
    }


    /**
     *  _generateDataTransformed
     *
     *  Function that return a new data with the differents options (filter, group and measure)
     *  applied.
     * 
     *  @param      array   data in a array
     *  @param      object  options for applying to data.
     *              interface:
     *                  {
     *                      groups :    [ groupColumns ]
     *                      measures:   { measureName: 'measureExpresion ( sum(_groupColumn_) )'} // measureExpresion variable has to be 
     *                                                                                            relate to a column of the data and 
     *                                                                                            format with underscore "_" on the start
     *                                                                                            and end, example: "id" = "_id_"
     *                  }   
     *  @since      0.1.0
     */
    function _generateDataTransformed(data, options, measureRoundDigits) {
        var _dataGenerated = [],
            _options = options,
            _data = JSON.parse(JSON.stringify(data));

        // If options.groups dont have at least a group dont go to next step
        if (!_options.groups.length)
            return _data;

        // Get groups
        var _groups = _getGroups(_data, _options.groups);
        var _hasGroup = (_groups.length == data.length) ? false : true;

        // Calculate measures
        var _measures = [];

        for (var group in _groups) {
            var _group = _groups[group];

            // Filter group row
            var _dataFilter = _data.filter(function (d) {
                var r = true;
                for (var g in _options.groups) {
                    var _groupName = _options.groups[g];

                    if (_group[_groupName] != d[_groupName]) {
                        r = false;
                        break;
                    }
                };
                return r;
            });

            var _variables = {};
            var _isSimpleMeasure = false;

            for (var measure in _options.measures) {
                var _measureVariables = _options.measures[measure];

                if (_measureVariables.indexOf("+") >= 0 || _measureVariables.indexOf("-") >= 0 || _measureVariables.indexOf("*") >= 0 || _measureVariables.indexOf("/") >= 0) {
                    _isSimpleMeasure = false;
                    break;
                }
            }

            // If dont exist group and measure is simple (dont have "+ - / *" operations)
            if (!_hasGroup && _isSimpleMeasure) {
                // Add every measure to the group
                for (var measure in _options.measures) {
                    // Get first variable of measure expression
                    var _measureVariable = _options.measures[measure].match(MEASURE_COLUMN_REGEXP)[0];
                    var _value = 0;

                    if (_measureVariable != undefined && _measureVariable != null) {
                        // Get data of variable
                        var _value = _dataFilter[0][_measureVariable.substr(1, _measureVariable.length - 2)]; // _variable_
                    }

                    // Add measure
                    _group[measure] = _value;
                }
            }
            else {
                // Add every measure to the group
                for (var measure in _options.measures) {
                    // Get array of variable of measure expression
                    var _measureVariables = _options.measures[measure].match(MEASURE_COLUMN_REGEXP);

                    // Get every data row of the variable of the measure expression
                    for (var variable in _measureVariables) {
                        var _variable = _measureVariables[variable];

                        if (!_variables[_variable])
                            _variables[_variable] = _dataFilter.map(function (df) { return df[_variable.substr(1, _variable.length - 2)]; });
                    }

                    // Evaluate measure expression with the data
                    var _value = measureRoundDigits === undefined || measureRoundDigits === null?
                                        _getExpressionValue(_options.measures[measure], _variables) : 
                                        _round(_getExpressionValue(_options.measures[measure], _variables), measureRoundDigits);

                    // Add measure
                    _group[measure] = _value;
                }
            }

            _measures.push(_group);
        }

        return JSON.parse(JSON.stringify(_measures));
    };

    /**
     *  _generateDataOfExpression
     *
     *  Function that return a object with a data example of an expression.
     *  It expression mush have the variable with the datatranformer syntax:
     *  variable1 => _variable1_
     * 
     *  @param      string  string expression with variables related to the data,
     *                      for example: sum(_a_) + sum(_b_)
     *  @since      0.1.0
     */
    var _generateDataOfExpression = _datatransformer.prototype.generateDataOfExpression = function (expression) {
        var _variableData = {},
            _variableOfExpression = expression.match(MEASURE_COLUMN_REGEXP);

        for (var variable in _variableOfExpression) {
            var _variable = _variableOfExpression[variable];

            if (!_variableData[_variable])
                _variableData[_variable] = [_getRandomNumber(1, 100), _getRandomNumber(1, 100)];
        }

        return _variableData;
    }

    /**
     *  _getExpressionValue
     *
     *  Function that return the value of an expression evaluated
     * 
     *  @param      string  string expression with variables related to the data,
     *                      for example: sum(a) + sum(b)
     *  @param      object  object with the data to be evaluated, 
     *                      for example: {a:1, b:2}
     *  @since      0.1.0
     */
    var _getExpressionValue = _datatransformer.prototype.getExpressionValue = function (expression, data) {
        return math.eval(expression, data) || 0;
    }

    // Generate a Measure Column Valid Attribute For Expression
    var _generateMeasureColumn = _datatransformer.prototype.generateMeasureColumn = function (column) {
        return MEASURE_COLUMN_PREFIX + column + MEASURE_COLUMN_PREFIX;
    }

    /**
     *  addVisual 
     *
     *  Add or extentend a new visual to the library
     *
     *  @param      string      new visual name
     *  @param      Object      new visual configuration object, attributes that the visual needed.
     *                          interface:
     *                              { attributeName: { label: 'attributeLabel', type: attributeType (String,Number,..) } }
     *  @param      function    new visual function, all the methods to performance the visual.
     *                          interface:
     *                              function(){
     *                                  this.data   // internal object that contains data information
     *                                  this.config // internal object that contains the configuration attributes for the visual
     *                                  this.util   // internal object that contains 
     *                                  this.render // implement function that inject the visual in a element
     *                              }
     *  @since      0.1.0
     *
     */
    _datatransformer.prototype.addVisual = function (visualName, visualConfig, visualFunction) {
        try {
            var _visual = _obj.visuals[visualName];

            if (!(typeof _visual == 'undefined'))
                throw new ERROR.visualAlreadyExists;

            _obj.visualNames.push(visualName);
            _obj.visuals[visualName] = { config: visualConfig, func: visualFunction };
            _executeEvent(_eventName.onVisualAdded);
        }
        catch (e) {
            console.error(e);
        }
    }

    // Get all visual Name
    _datatransformer.prototype.getVisualNames = function () {
        return JSON.parse(JSON.stringify(_obj.visualNames));
    }

    // Get a visual name with its configuration
    _datatransformer.prototype.getVisualConfigByName = function (visualName) {
        try {
            var _visual = _obj.visuals[visualName];

            if (typeof _visual == 'undefined')
                throw new ERROR.visualNotExists;

            return _visual.config;
        }
        catch (e) {
            error(e);
        }
    }

    // Type Interfaces
    _datatransformer.prototype.typeEnum = function () { }

    _datatransformer.prototype.typeColumn = function () { }

    _datatransformer.prototype.typeMultipleColumns = function () { }

    _datatransformer.prototype.typeGroup = function () { }

    _datatransformer.prototype.typeMultipleGroups = function () { }

    _datatransformer.prototype.typeMeasure = function () { }

    _datatransformer.prototype.typeMultipleMeasures = function () { }

    /**
     *  _new 
     *
     *  Create a new dataTransformer and generate differents visuals
     *
     *  @param      JSON    data in json format
     *  @param      object  options for applying to data.
     *              interface:
     *                  {
     *                      filters: { filterColumn: [ filters ]   },
     *                      groups : [ groupColumns ]
     *                      measures: { measureName: 'measureExpresion ( sum(groupColumn) )'}
     *                  }   
     *  @since      0.1.0
     */
    var _new = function (data, options) {
        this.data = {
            data: [],
            columns: [],
            transformed: [],
            options: {
                filters: {},
                groups: [],
                measures: {}
            }
        };

        this.visuals = []

        this.data._data = data || [];
        this.data._transformed = [];

        this.data.options.filters = ('filters' in (options || {})) ? options.filters : {};
        this.data.options.groups = ('groups' in (options || [])) ? options.groups : [];
        this.data.options.measures = ('measures' in (options || {})) ? options.measures : {};
        this.data.columns = _getColumns(this.data._data);
        this.data.data = _filterData(this.data._data, this.data.options.filters);

        // get data transformed
        this.data.getTransformed = function (measureRoundDigits) {
            if (typeof this['_transformed'] == 'undefined' || this._transformed.length <= 0) {
                this._transformed = _generateDataTransformed(this.data, this.options, measureRoundDigits);
            }
            return this._transformed;
        }
    };

    // Refresh all visuals that have the datatransfome instance
    _new.prototype.refreshVisuals = function () {
        this.data.columns = _getColumns(this.data._data);
        this.data.data = _filterData(this.data._data, this.data.options.filters);
        this.data._transformed = [];

        for (var vl in this.visuals) {
            var _visualObj = this.visuals[vl];
            _visualObj.data = this.data;
            _visualObj.render();
        }
    }

    /**
     *  generateVisual 
     *
     *  Generate a visual for the datatransformer instance
     *
     *  @param      string      visual name
     *  @param      Object      visual configuration object, attributes that the visual needed.
     *  @param      string      element id 
     *  @since      0.1.0
     */
    _new.prototype.generateVisual = function (visualName, visualConfig, elemId) {
        try {
            var _visualObj = new _obj.visuals[visualName].func();

            _visualObj.data = this.data;
            _visualObj.config = visualConfig;
            _visualObj.config.elemId = elemId;

            _visualObj.util = {
                filterData: _filterData,
                getColumns: _getColumns,
                getGroups: _getGroups,
                getDistinct: _getDistinct,
                generateDataTransformed: _generateDataTransformed,
                generateDataOfExpression: _generateDataOfExpression,
                generateMeasureColumn: _generateMeasureColumn,
                getExpressionValue: _getExpressionValue,
                noDataTemplateHTML: '<p class="nodata-label" style="margin: o0px auto;text-align: center;padding-top: 28%;">No Data Available</p>'
            };

            delete this.visuals[elemId]
            this.visuals[elemId] = _visualObj;

            return _visualObj;
        } catch (e) {
            console.error(e);
        }

        return new visual();
    }

    // Public _new contructor
    _datatransformer.prototype.new = function (data, options) {
        return new _new(data, options);
    }

    // Global datatransformer object
    window.datatransformer = new _datatransformer();
    callback(window, $);

})(window, $, math, function (window, $) {
    // Private variables
    var _tootip = {
        tableHeaderColor: "It can be configurated in one of the following 2 ways: -> color=red  -> category=red,categoryName|yellow,categoryName",
        measureColor: "It can be configurated in one of the following 2 ways: -> color=red,measureName|green,measureName -> range= measureName+red,1,2+green,3,4| measureName+yellow,1,2,+blue,3,4",
        measureSymbol: "It can be configurated: symbol=$,measureName|#,measureName|%,measureName ",
        css: "Table classes: h3.table-title,   div.table-item,   table.table,  tr.table-header,  tr.table-header-category,   th.table-header-category-item,   tr.table-row ,   td.table-row-data,   td.table-row-data-group,    tr.table-totalRow ,   td.table-totalRow-data,    td.table-row-data-group. Apply CSS only to this visual using {visualId} that is the id of the element where the visual is rendered."
    };

    // Private function
    // generate html table string
    function _generateTableHTML(dataColumns, data, totalColumns, totalData,symbol, headerColor, measureColor, isMeasureColorRange, totalText) {
        var _tableHeaderColor = headerColor? "style='background-color:" + headerColor+ ";'" : "",
            _tableHeader = "<table border='1' class='table'><thead><tr  class='table-header' " + _tableHeaderColor + ">",
            _tableBody = "<tbody>";

        function _getColor(column, dataValor) {
            var _color = null;

            if (measureColor && totalColumns.indexOf(column) >= 0) {
                if (isMeasureColorRange) {
                    var _colorArray = Array.isArray(measureColor[column]) ? measureColor[column].filter(function (mc) { return dataValor >= mc["start"] && dataValor <= mc["end"] }) : [];

                    if (_colorArray.length > 0)
                        _color = _colorArray[0]["color"];
                }
                else {
                    _color = measureColor[column];
                }

                return _color ? "style='color:" + _color + ";'" : "";
            }
        }

        // Table header
        for (var dc in dataColumns) {
            _tableHeader += "<th class='table-header-item'>" + dataColumns[dc] + "</th>";
        }

        _tableHeader += "<tr></thead>";

        // Table body
        for (var d in data) {
            var _tableRow = "<tr class='table-row'>";

            for (var dc in dataColumns) {
                var _dc = dataColumns[dc];
                var _data = data[d][dataColumns[dc]];

                _tableRow += "<td class='table-row-data' " + _getColor(_dc, _data) + " >" + String(_data) + "</td>";
            }

            _tableRow += "</tr>";
            _tableBody += _tableRow;
        }

        for (var d in totalData) {
            var _tableRow = "",
                _tableTdColSpanNumber = 0;

            for (var dc in dataColumns) {

                if (totalColumns.indexOf(dataColumns[dc]) >= 0) {
                    var _dc = dataColumns[dc];
                    var _data = totalData[d][dataColumns[dc]];

                    _tableRow += "<td  class='table-totalRow-data' " + _getColor(_dc, _data) + ">" + String(_data) + "</td>";
                }
                else
                    _tableTdColSpanNumber++;
            }

            _tableRow = "<tr class='table-totalRow'  style='background-color: #f7f7f7;' ><td class='table-totalRow-data' colspan='" + _tableTdColSpanNumber + "'> " + (totalText || 'Total') + "  </td>" + _tableRow + "</tr>";
            _tableBody += _tableRow;
        }

        _tableBody += "</tbody></table>";

        return _tableHeader + _tableBody;
    }

    // generate multiple tables in one 
    function _generateUnifiedTableHTML(dataColumns, data, measureColumns, totalData, group, groups, category, categories, symbol, colorsHeader, colorHeader, measureColor, isMeasureColorRange, totalText) {
        var _tableHeader = "<table border='1' class='table'><thead>",
            _tableBody = "<tbody>";

        function _getColor(column, dataValor) {
            var _color = null;

            if (measureColor && measureColumns.indexOf(column) >= 0) {
                if (isMeasureColorRange) {
                    var _colorArray = Array.isArray(measureColor[column]) ? measureColor[column].filter(function (mc) { return dataValor >= mc["start"] && dataValor <= mc["end"] }) : [];

                    if (_colorArray.length > 0)
                        _color = _colorArray[0]["color"];
                }
                else {
                    _color = measureColor[column];
                }

                return _color ? "style='color:" + _color + ";'" : "";
            }
        }

        _tableHeader += "<tr  class='table-header table-header-category'>";
        _tableHeader += "<th class='table-header-item table-header-category-group' rowspan='2'>" + group + "</th>";
        var _headerCategoryColSpan = measureColumns.length || 0;

        // Table header category
        for (var dc in categories) {
            var _cHeader = colorsHeader[categories[dc]] || colorHeader;
            var _tableHeaderColor = _cHeader? "style='background-color:" + _cHeader+ ";'" : "";

            _tableHeader += "<th class='table-header-item table-header-category-item' "+_tableHeaderColor+" colspan='" + _headerCategoryColSpan + "'>" + categories[dc] + "</th>";
        }

        _tableHeader += "</tr>";

        _tableHeader += "<tr  class='table-header'>";

        // Table header
        for (var dc in categories) {
            for (var dc in dataColumns) {
                if (measureColumns.indexOf(dataColumns[dc]) >= 0) {
                    _tableHeader += "<th class='table-header-item'>" + dataColumns[dc] + "</th>";
                }
            }
        }

        _tableHeader += "</tr></thead>";

        // Table body
        for (var g in groups) {
            var _tableRow = "<tr class='table-row'>";
            _tableRow += "<td class='table-row-data table-row-data-group'>" + groups[g] + "</td>";

            for (var dc in categories) {
                var _rowData = data.filter(function (d) { return d[group] == groups[g] && d[category] == categories[dc] })[0];

                for (var dc in dataColumns) {
                    if (measureColumns.indexOf(dataColumns[dc]) >= 0) {
                        if (_rowData) {
                            var _dc = dataColumns[dc];
                            var _data = _rowData[dataColumns[dc]];
                            var _symbol = symbol[dataColumns[dc]] || "";
                            _tableRow += "<td class='table-row-data' " + _getColor(_dc, _data) + " >" + _data + _symbol + "</td>";
                        }
                        else
                            _tableRow += "<td class='table-row-data'></td>";
                    }
                }
            }

            _tableRow += "</tr>";
            _tableBody += _tableRow;
        }

        var _tableRowTotal = "<tr class='table-totalRow' style='background-color: #f7f7f7;'>";
        _tableRowTotal += "<td class='table-totalRow-data table-row-data-group'>" + (totalText || 'Total') + "</td>";

        for (var dc in categories) {
            var _rowDataTotal = totalData.filter(function (d) { return d[category] == categories[dc] })[0];

            for (var dc in dataColumns) {
                if (measureColumns.indexOf(dataColumns[dc]) >= 0) {
                    if (_rowDataTotal) {
                        var _dc = dataColumns[dc];
                        var _data = _rowDataTotal[dataColumns[dc]];
                        var _symbol = symbol[dataColumns[dc]] || "";
                        _tableRowTotal += "<td class='table-totalRow-data' " + _getColor(_dc, _data) + " >" + _data + _symbol + "</td>";
                    }
                    else
                        _tableRowTotal += "<td class='table-totalRow-data'></td>";
                }
            }
        }

        _tableRowTotal += "</tr>";
        _tableBody += _tableRowTotal;
        _tableBody += "</tbody></table>";

        return _tableHeader + _tableBody;
    }

    /**
     *  simpleTable
     *
     *  A visual obj that create a table with the data
     */
    datatransformer.addVisual("simpleTable",
        {
            title: { label: "title", type: String, required: true, order: 1 },
            measureRound: { label: "measure round", type: Number, order: 2 }
        },
        function () {
            this.renderOptions = {
                tableHTML: null
            }

            this.render = function () {
                var _data = this.data.getTransformed(),
                    _dataColumns = Object.keys(_data[0]),
                    _title = "<h3 class='table-title'>" + this.config.title + "</h3>",
                    _mearureRound = this.config.measureRound,
                    _table = this.renderOptions.tableHTML = _title +  (_data.length > 0? _generateTableHTML(_dataColumns, _data,_mearureRound) : this.util.noDataTemplateHTML);
                    
                $("#" + this.config.elemId).html(_table);
            }

            this.refreshRender = function () {
                $("#" + this.config.elemId).html(this.renderOptions.tableHTML);
            }
        });

    /**
     *  advancedTable
     *
     *  A visual obj that create a table with the data and advanced features.
     *
     *  CSS styles have to add {visualId} to point the container of the visual table.
     *
     *  Unified table has the follow structure ((1) the group column && (*) the multiple measures columns):
     *
     *      h3.table-title
     *
     *      div.table-item
     *       
     *       table.table
     *            thead
     *              tr.table-header.table-header-category
     *                  th.table-header-item.table-header-category-group (1)
     *                  th.table-header-item.table-header-category-item (*) 
     *               
     *              tr.table-header
     *                  th.table-header-item
     *
     *
     *            tbody
     *              tr.table-row        
     *                  td.table-row-data.table-row-data-group (1)
     *                  td.table-row-data (*)
     *               
     *              tr.table-totalRow
     *                  td.table-totalRow-data.table-row-data-group (1)
     *                  td.table-totalRow-data (*)
     *
     *
     *  Category table Structure:
     *
     *      h3.table-title
     *
     *      div.table-item
     *
     *          h3.table-title-category
     *       
     *          table.table
     *              thead
     *                  tr.table-header
     *                  th.table-header-item
     *
     *              tbody
     *                  tr.table-row
     *                  td.table-row-data
     *
     *                  tr.table-totalRow
     *                  td.table-totalRow-data
     *
     */
    datatransformer.addVisual("advancedTable",
        {
            title: { label: "title", type: String, order: 1 },
            category: { label: "category", type: datatransformer.typeGroup, required: true, order: 2 },
            tableHeaderColor: { label: "table header color", type: String, tooltip: _tootip.tableHeaderColor, order: 3 , largeText: true},
            group: { label: "group", type: datatransformer.typeGroup, required: true, order: 4 },
            measures: { label: "measures categories", type: datatransformer.typeMultipleMeasures, required: true, order: 5 },
            measureColor: { label: "measure color", type: String, tooltip: _tootip.measureColor, order: 6 , largeText: true  },
            measureSymbol: { label: "measure symbol", type: String, tooltip: _tootip.measureSymbol, order: 7  , largeText: true},
            measureRound: { label: "measure round", type: Number, order: 8 },
            unifiedTable: { label: "unified table", type: Boolean, order: 9 },
            totalText: { label: "total text", type: String, order: 10 },
            css: { label: "css", type: String, tooltip: _tootip.css, order: 11, largeText: true }
        },
        function () {
            this.renderOptions = {
                tableHTML: null
            }

            this.render = function () {
                var _data = this.data.data,
                    _category = this.config.category,
                    _group = this.config.group,
                    _measuresObj = {},
                    _dataOptionsObj = {},
                    _generateData = [],
                    _generateDataTotal = [],
                    _categories = [],
                    _dataColumnsTable = [],
                    _thc = this.config.tableHeaderColor,
                    _mc = this.config.measureColor,
                    _ms = this.config.measureSymbol,
                    _mearureRound = this.config.measureRound;


                var _titleHtml = this.config.title ? "<h3 class='table-title'>" + this.config.title + "</h3>" : "";

                if(_data.length <= 0)
                {

                    this.renderOptions.tableHTML = _titleHtml +  this.util.noDataTemplateHTML;
                    $("#" + this.config.elemId).html(this.renderOptions.tableHTML);

                    return;
                }

                _dataColumnsTable.push(_group);
                _categories = this.util.getDistinct(_data, _category);

                for (var m in this.config.measures) {
                    _measuresObj[this.config.measures[m]] = this.data.options.measures[this.config.measures[m]];
                    _dataColumnsTable.push(this.config.measures[m]);
                }

                _dataOptionsObj = {
                    groups: [_category, _group],
                    measures: _measuresObj
                };

                _generateData = this.util.generateDataTransformed(_data, _dataOptionsObj, _mearureRound);

                _dataOptionsObj = {
                    groups: [_category],
                    measures: _measuresObj
                };

                _generateDataTotal = this.util.generateDataTransformed(_data, _dataOptionsObj, _mearureRound);

                var _html = "<div class='table-container'>";

                //-> color=red  -> category=red,categoryName|yellow,categoryName",
                var _colorHeader = null, _colorsHeader = {};

                if (typeof _thc != 'undefined' && _thc.indexOf('color=') >= 0) {
                    _colorHeader = _thc.replace('color=', '');
                }
                else if (typeof _thc != 'undefined' && _thc.indexOf('category=') >= 0) {
                    var _categoriesSyntax = _thc.replace('category=', '');
                    var _categoriesArray = _categoriesSyntax.split('|');

                    for (var ca in _categoriesArray) {
                        var _ca = _categoriesArray[ca].split(",");

                        if (_ca && _ca.length == 2) {
                            var _caColor = _ca[0]
                            var _caCategory = _ca[1] || '';
                            _colorsHeader[_caCategory] = _caColor;
                        }
                    }
                }

                //-> color=red,measureName -> range= measureName+red,1,2+blue,1,3| measureName+red,1,2,+blue,1,3"
                var _colorMeasure = null, _colorsMeasure = null, _isColorMeasureRange = false;

                if (typeof _mc != 'undefined' && _mc.indexOf('color=') >= 0) {
                    _colorMeasure = {};
                    var _colorSyntax = _mc.replace('color=', '');
                    var _colorArray = _colorSyntax.split('|');

                    for (var c in _colorArray) {
                        var _c = _colorArray[c].split(",");

                        if (_c && _c.length == 2) {
                            var _caColor = _c[0]
                            var _caCategory = _c[1] || '';
                            _colorMeasure[_caCategory] = _caColor;
                        }
                    }
                }
                else if (typeof _mc != 'undefined' && _mc.indexOf('range=') >= 0) {
                    _colorsMeasure = {};
                    _isColorMeasureRange = true;
                    var _measureColorSyntax = _mc.replace('range=', '');
                    var _measureColorArray = _measureColorSyntax.split('|');

                    for (var mc in _measureColorArray) {
                        var _mc = _measureColorArray[mc].split("+");

                        if (_mc && _mc.length > 0) {
                            var _counter = 0,
                                _measureName = "";

                            for (var _mcd in _mc) {
                                if (_counter == 0) {
                                    _measureName = _mc[_mcd];
                                    _colorsMeasure[_measureName] = [];
                                }
                                else {
                                    var _mcds = _mc[_mcd].split(",");

                                    if (_mcds && _mcds.length == 3) {
                                        _colorsMeasure[_measureName].push({ color: _mcds[0], start: Number(_mcds[1]), end: Number(_mcds[2]) });
                                    }
                                }
                                _counter++;
                            }
                        }
                    }
                }

                // -> symbol=$,measureName|#,measureName|%,measureName

                var _measureSymbol = {};

                if (typeof _ms != 'undefined' && _ms.indexOf('symbol=') >= 0) {
                    var _symbolSyntax = _ms.replace('symbol=', '');
                    var _symbolArray = _symbolSyntax.split('|');

                    for (var c in _symbolArray) {
                        var _c = _symbolArray[c].split(",");

                        if (_c && _c.length == 2) {
                            var _caSymbol = _c[0]
                            var _caCategory = _c[1] || '';
                            _measureSymbol[_caCategory] = _caSymbol;
                        }
                    }
                }

                if (this.config.unifiedTable) {

                    var _tableHtml = _titleHtml + "<div class='table-item' style='margin: 0px;padding: 0px;'>";
                    var _groups = this.util.getDistinct(_data, _group);
                    _tableHtml += _generateUnifiedTableHTML(
                                                            _dataColumnsTable,
                                                            _generateData,
                                                            this.config.measures,
                                                            _generateDataTotal,
                                                            _group,
                                                            _groups,
                                                            _category,
                                                            _categories,
                                                            _measureSymbol,
                                                            _colorsHeader,
                                                            _colorHeader,
                                                             _isColorMeasureRange ? _colorsMeasure : _colorMeasure,
                                                             _isColorMeasureRange,
                                                            this.config.totalText);

                    _html += _tableHtml;
                    this.renderOptions.tableHTML = _html += "</div>";
                    this.renderOptions.tableHTML = "<style> " + this.config.css.replace(/{visualId}/g, "#" + this.config.elemId) + " </style>" + this.renderOptions.tableHTML;
                }
                else {
                    for (var c in _categories) {
                        var _tableHtml ="<div class='table-item' style='margin: 0px;padding: 0px;'>",
                            _dataInCategory = _generateData.filter(function (d) { return d[_category] == _categories[c] }),
                            _totalDataInCategory = _generateDataTotal.filter(function (d) { return d[_category] == _categories[c] });

                        _tableHtml += "<h3 class='table-title-category'>" +
                                      _categories[c] +
                                      "</h3>" +
                                      _generateTableHTML(_dataColumnsTable, _dataInCategory, this.config.measures, _totalDataInCategory,_measureSymbol, (_colorsHeader[_categories[c]] || _colorHeader), _isColorMeasureRange ? _colorsMeasure : _colorMeasure, _isColorMeasureRange, this.config.totalText);

                        _tableHtml += "</div>";
                        _html += _tableHtml;
                    }

                    this.renderOptions.tableHTML = _titleHtml + (_html += "</div>");
                    this.renderOptions.tableHTML = "<style> " + this.config.css.replace(/{visualId}/g, "#" + this.config.elemId) + " </style>" + this.renderOptions.tableHTML;
                }

                $("#" + this.config.elemId).html(this.renderOptions.tableHTML);
            }

            this.refreshRender = function () {
                $("#" + this.config.elemId).html(this.renderOptions.tableHTML);
            }
        });
});




