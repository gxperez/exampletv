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
        this.renderOptions= {};
        this.render = function () { };
        this.refreshRender = function(){ };
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
            return ((typeof filter != "undefined" && filter['toString'] > 0)? filter.toString().toLowerCase().replace(/\s/g, '') : '');
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
    function _generateDataTransformed(data, options) {
        var _dataGenerated = [],
            _options = options,
            _data = JSON.parse(JSON.stringify(data));

        // If options.groups dont have at least a group dont go to next step
        if (!_options.groups.length)
            return _data;

        // Get groups
        var _groups = _getGroups(_data, _options.groups);
        var _hasGroup = (_groups.length == data.length)? false: true;

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

                if(_measureVariables.indexOf("+") >= 0 || _measureVariables.indexOf("-") >= 0 || _measureVariables.indexOf("*") >= 0 || _measureVariables.indexOf("/") >= 0)
                {
                    _isSimpleMeasure = false;
                    break;
                }
            }

            // If dont exist group and measure is simple (dont have "+ - / *" operations)
            if(!_hasGroup && _isSimpleMeasure){
                 // Add every measure to the group
                for (var measure in _options.measures) {
                    // Get first variable of measure expression
                    var _measureVariable = _options.measures[measure].match(MEASURE_COLUMN_REGEXP)[0];
                    var _value  = 0;

                    if(typeof _measureVariable != "undefined")
                    {
                        // Get data of variable
                        var _value = _dataFilter[0][_measureVariable.substr(1, _measureVariable.length - 2)]; // _variable_
                    }
                    
                    // Add measure
                    _group[measure] = _value;
                }
            }
            else{
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
                    var _value = _getExpressionValue(_options.measures[measure], _variables);

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
            console.error(e);
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
        this.data.getTransformed = function () {
            if (typeof this['_transformed'] == 'undefined' || this._transformed.length <= 0) {
                this._transformed = _generateDataTransformed(this.data, this.options);
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
                getExpressionValue: _getExpressionValue
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
    /**
     *  simpleTable
     *
     *  A visual obj that create a table with the data
     */
    datatransformer.addVisual("simpleTable",
        {
            title: { label: "title", type: String, required: true }
        },
        function () {
            this.render = function () {
                var _html = "",
                    _data = this.data.getTransformed(),
                    _dataColumns = Object.keys(_data[0]),
                    _title = "<h3>" + this.config.title + "</h3>",
                    _tableHeader = _title + "<table border='1' class='table'><thead>",
                    _tableBody = "<tbody>";

                // Table header
                for (var dc in _dataColumns) {
                    _tableHeader += "<th>" + _dataColumns[dc] + "</th>";
                }

                _tableHeader += "</thead>";

                // Table body
                for (var d in _data) {
                    var _tableRow = "<tr>";

                    for (var dc in _dataColumns) {
                        _tableRow += "<td>" + String(_data[d][_dataColumns[dc]]) + "</td>";
                    }

                    _tableRow += "</tr>";
                    _tableBody += _tableRow;
                }

                _tableBody += "</tbody></table>";


                $("#" + this.config.elemId).html(_tableHeader + _tableBody);
            };
        });
});




