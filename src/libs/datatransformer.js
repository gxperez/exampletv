/**
 * datatransformer
 *
 * a library that transforme json data to information.
 * let you perfome grouping, filtering, measuring and
 * render on different type of visual that extends the 
 * library.
 *
 * @author	vigor situ lou
 * @version 0.1.0
 * @date 	15/abr/2016
 *
 */

'use strict';

(function(window, $, math, callback){
	// Contants
	var MEASURE_COLUMN_PREFIX = "_",
		MEASURE_COLUMN_REGEXP  = /_\w+_/ig;


	// Namespace
	var _datatransformer = function(){};

	// Private variables
	// Object that contains every visual.
	var _obj =	{
			visuals : {},
			visualNames : [],
		},
		_eventName = {
			onVisualAdded : 'onVisualAdded'
		},
		_events = {};

	// Events Handle
	_events[_eventName.onVisualAdded] = [];

	_datatransformer.prototype[_eventName.onVisualAdded] = function(eventFunction){
			if(typeof eventFunction === "function")
				_events[_eventName.onVisualAdded].push(eventFunction);
	}

	// Function that execute a event
	function _executeEvent(eventName){
		(_events[eventName] || []).forEach(function(e){ e(); });
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
	function _getRandomNumber(numberStart, numberEnd)
	{
		if(!numberStart || !numberEnd)
			return Math.random();
		else 
			return Math.floor((Math.random() * numberEnd) + numberStart); 
	}

	// Function that give the closest number of a array of number
	function _getClosestNumber(number, arrayOfNumbers){
		 return arrayOfNumbers.reduce(function (prev, curr) {
		  	return (Math.abs(curr - number) < Math.abs(prev - number) ? curr : prev);
		});
	}

	/**
	 *  _round
	 *
	 *	Function that round a number.
	 * 
	 *	@param 		Number 	a number
	 *	@param		Number 	the number of digits to appear after the decimal point.
	 *  @since 		0.1.0
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
	function _getColumns(data){
		return Object.keys(data[0] || {});
	}

	/**
	 *  _getDistinct
	 *
	 *	Function that return the distinct rows of a column
	 * 
	 *	@param 		JSON 	data in json format
	 *	@param		string 	column name of the data 
	 *  @since 		0.1.0
	 */
	function _getDistinct(data, column){
		var _distinct = [];

		for(var d in data){
			var _data = data[d][column];

			if(!_distinct.some(function(od){ return od == _data}))
					_distinct.push(_data);
		}

		return _distinct;
	}

	/**
	 *  _getGroup
	 *
	 *	Function that return a array of the distinct rows of some groups
	 * 
	 *	@param 		JSON 	data in json format
	 *	@param		array 	array of column names
	 *  @since 		0.1.0
	 */
	function _getGroups(data, groups){
		var _groups = [];

		for(var d in data){
			var _group = {};

			for(var group in groups){
					var _groupName = groups[group];
					_group[_groupName] = data[d][_groupName];
			}

			if(!_groups.some(function(g){ return _isEquivalent(g,_group)}))
					_groups.push(_group);
		}

		return _groups;
	}

	/**
	 *  _generateDataTransformed
	 *
	 *	Function that return a new data with the differents options (filter, group and measure)
	 *	applied.
	 * 
	 *	@param 		JSON 	data in json format
	 *	@param		object 	options for applying to data.
	 *				interface:
	 *					{
	 *						filters: 	{ filterColumn: [ filters ]   },
	 *						groups : 	[ groupColumns ]
	 *						measures: 	{ measureName: 'measureExpresion ( sum(_groupColumn_) )'} // measureExpresion variable has to be 
	 *																							  relate to a column of the data and 
	 *																							  format with underscore "_" on the start
	 *																							  and end, example: "id" = "_id_"
	 *					}	
	 *  @since 		0.1.0
	 */
	function _generateDataTransformed(data, options){
		var _dataGenerated = [],
			_options = options,
			_data = JSON.parse(JSON.stringify(data));

		// Filter data
		for(var filter in _options.filters){
			_data = _data.filter(function(d){
				return  _options.filters[filter].indexOf(d[filter]) > -1; 
			})
		}

		// If options.groups dont have at least a group dont go to next step
		if(!_options.groups.length)
			return _data;

		// Get groups
		var _groups = _getGroups(_data,_options.groups);

		// Calculate measures
		var _measures = [];

		for(var group in _groups){
			var _group = _groups[group];

			// Filter group row
			var _dataFilter = _data.filter(function(d){
				var r = true;  
				for(var g in _options.groups){ 
					var _groupName = _options.groups[g];  

					if (_group[_groupName] != d[_groupName])
					{
						r = false;
						break;
					}
				}; 
				return r;
			});

			var _variables = {};

			// Add every measure to the group
			for(var measure in _options.measures)
			{
				// Get array of variable of measure expression
				var _measureVariables = _options.measures[measure].match(MEASURE_COLUMN_REGEXP);

				// Get every data row of the variable of the measure expression
				for(var variable in _measureVariables){
					var _variable = _measureVariables[variable];

					if(!_variables[_variable])
						_variables[_variable] = _dataFilter.map(function(df){ return df[_variable.substr(1,_variable.length-2)]; });
				}

				// Evaluate measure expression with the data
				var _value = _getExpressionValue(_options.measures[measure], _variables);

				// Add measure
				_group[measure] = _value;
			}

			_measures.push(_group);
		}

		return  JSON.parse(JSON.stringify(_measures));
	};

	/**
	 *  _generateDataOfExpressionn
	 *
	 *	Function that return a object with a data example of an expression.
	 *	It expression mush have the variable with the datatranformer syntax:
	 *	variable1 => _variable1_
	 * 
	 *	@param 		string 	string expression with variables related to the data,
	 *						for example: sum(_a_) + sum(_b_)
	 *  @since 		0.1.0
	 */
	function _generateDataOfExpressionn(expression){
		var _variableData = {},
			_variableOfExpression = expression.match(MEASURE_COLUMN_REGEXP);

		for(var variable in _variableOfExpression){
			var _variable = _variableOfExpression[variable];

			if(!_variableData[_variable])
				_variableData[_variable] = [_getRandomNumber(1, 100), _getRandomNumber(1, 100)];
		}

		return _variableData;
	}

	/**
	 *  _getExpressionValue
	 *
	 *	Function that return the value of an expression evaluated
	 * 
	 *	@param 		string 	string expression with variables related to the data,
	 *						for example: sum(a) + sum(b)
	 *	@param		object 	object with the data to be evaluated, 
	 *						for example: {a:1, b:2}
	 *  @since 		0.1.0
	 */
	function _getExpressionValue(expression, data){
		return math.eval(expression, data) || 0;
	}

	// Generate a Measure Column Valid Attribute For Expression
	function _generateMeasureColumn(column){
		return MEASURE_COLUMN_PREFIX+column+MEASURE_COLUMN_PREFIX; 
	}


	// Visual Interface
	function visual(){
		this.render = function(){}
	}

	/**
	 *  addVisual 
	 *
	 *  Add or extentend a new visual to the library
	 *
	 *	@param		string 		new visual name
	 * 	@param		Object 		new visual configuration object, attributes that the visual needed.
	 *							interface:
	 *								{ attributeName: { label: 'attributeLabel', type: attributeType (String,Number,..) } }
	 *  @param		function 	new visual function, all the methods to performance the visual.
	 *							interface:
	 *								function(){
	 *									this.data   // internal object that contains data information
	 *									this.config // internal object that contains the configuration attributes for the visual
	 *									this.util   // internal object that contains 
	 *									this.render // implement function that inject the visual in a element
	 *								}
	 *  @since 		0.1.0
	 *
	 */
	_datatransformer.prototype.addVisual = function(visualName, visualConfig, visualFunction){
		_obj.visualNames.push(visualName);
		_obj.visuals[visualName] = {config: visualConfig , func : visualFunction };
		_executeEvent(_eventName.onVisualAdded);
	}

	// Get all visual Name
	_datatransformer.prototype.getVisualNames = function(){
		return JSON.parse(JSON.stringify(_obj.visualNames));
	}

	// Get a visual name with its configuration
	_datatransformer.prototype.getVisualConfigByName = function(visualName){
		return _obj.visuals[visualName].config;
	}

	// Type Interfaces
	_datatransformer.prototype.typeEnum = function(){}

	_datatransformer.prototype.typeColumn = function(){}

	_datatransformer.prototype.typeMultipleColumns = function(){}

	_datatransformer.prototype.typeGroup = function(){}

	_datatransformer.prototype.typeMultipleGroups = function(){}

	_datatransformer.prototype.typeMeasure = function(){}

	_datatransformer.prototype.typeMultipleMeasures = function(){}

	/**
	 *  _new 
	 *
	 *  Create a new dataTransformer and generate differents visuals
	 *
	 *	@param 		JSON 	data in json format
	 *	@param		object 	options for applying to data.
	 *				interface:
	 *					{
	 *						filters: { filterColumn: [ filters ]   },
	 *						groups : [ groupColumns ]
	 *						measures: { measureName: 'measureExpresion ( sum(groupColumn) )'}
	 *					}	
	 *  @since 		0.1.0
	 */
	var _new = function(data, options){
		this.data = {
			data: [],
			columns: [],
			transformed : [],
			options: {
				filters: {},
				groups: [],
				measures: {}
			}
		};

		this.visuals = []

		this.data.data = data || [];

		this.data.options.filters 	= 	('filters'	in (options || {})) ? options.filters 	: {};
		this.data.options.groups 	= 	('groups' 	in (options || [])) ? options.groups 	: [];
		this.data.options.measures 	= 	('measures' in (options || {})) ? options.measures 	: {};
		this.data.columns =  _getColumns(this.data.data);
		this.data.transformed = _generateDataTransformed(this.data.data, this.data.options);
	};

	// Refresh all visuals that have the datatransfome instance
	_new.prototype.refreshVisuals = function(){
		this.data.columns =  _getColumns(this.data.data);
		this.data.transformed = _generateDataTransformed(this.data.data, this.data.options);

		for(var vl in this.visuals){
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
	 *	@param		string 		visual name
	 * 	@param		Object 		visual configuration object, attributes that the visual needed.
	 *  @param		string 		element id 
	 *  @since 		0.1.0
	 */
	_new.prototype.generateVisual = function(visualName, visualConfig, elemId){
		try{
			var _visualObj = new _obj.visuals[visualName].func();

			_visualObj.data = this.data;
			_visualObj.config = visualConfig;
			_visualObj.config.elemId = elemId;
			
			_visualObj.util  = {
				round: _round,
				isEquivalent: _isEquivalent,
				getRandomNumber : _getRandomNumber,
				getClosestNumber: _getClosestNumber,
				getColumns: _getColumns,
				getGroups : _getGroups,
				getDistinct : _getDistinct,
				generateDataTransformed:  _generateDataTransformed,
				generateDataOfExpressionn: _generateDataOfExpressionn,
				generateMeasureColumn:  _generateMeasureColumn,
				getExpressionValue: _getExpressionValue
			}

			this.visuals[elemId] =_visualObj;

			return _visualObj;
		} catch(e){
			console.error(e);
		}

		return new visual();
	}

	// get data transformed
	_new.prototype.getDataTransformed = function(){
		return this.data.transformed;
	}

	// Public _new contructor
	_datatransformer.prototype.new = function(data, options){
		return new _new(data, options);
	}

	// Global datatransformer object
	window.datatransformer =  new _datatransformer();
	callback(window, $);

})(window, $, math, function(window, $){
	/**
	 *  simpleTable
	 *
	 *  A visual obj that create a table with the data
	 */
	datatransformer.addVisual("simpleTable", 
		{
			title: {label: "title",  type: String,  required: true}
		},
		function(){
			this.render = function(){
					var _html = "",
						_dataColumns = Object.keys(this.data.transformed[0]),
						_title = "<h3>"+this.config.title+"</h3>",
						_tableHeader = _title +  "<table border='1' class='table'><thead>",
						_tableBody = "<tbody>";

					// Table header
					for(var dc in _dataColumns){
						_tableHeader += "<th>"+_dataColumns[dc]+"</th>";
					}
					
					_tableHeader += "</thead>";
					
					// Table body
					for(var d in this.data.transformed){
						var _tableRow = "<tr>";

						for(var dc in _dataColumns){
							_tableRow += "<td>"+this.data.transformed[d][_dataColumns[dc]]+"</td>";
						}

						_tableRow += "</tr>";

						_tableBody += _tableRow;
					}

					_tableBody += "</tbody></table>";

					$("#"+this.config.elemId).html(_tableHeader+_tableBody);
			};

	});
});




