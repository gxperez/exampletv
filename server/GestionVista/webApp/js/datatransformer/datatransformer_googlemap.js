/**
 * datatransformer_googlemap
 *
 * visuals extension of datatransformer that uses the 
 * google map library.
 *
 * google map api: 	http://maps.googleapis.com/maps/api/js?v=3.9&sensor=false&language=es
 * 
 *
 * @author	vigor situ lou
 * @version 0.1.0
 * @date 	27/abr/2016
 *
 */

'use strict';

// datatransformerGooglemap public object
var datatransformerGooglemap = (function ($, datatransformer) {
    //Contants
    var MEASURE_COLUMN_REGEXP = /_\w+_/ig,
		LATITUDE_DEFAULT = 25.766541,
		LONGITUD_DEFAULT = -40.965776;

    // Namespace
    var _datatransformerGooglemap = function () { };

    // Private variables
    var _templateList = [],
		_templates = {},
		_tootip = {
		    markerTitle: "Example: <h1> _columnName_ </h1>",
		    iconTarget: "It only works with icon type: group or range",
		    icon: "It can be configurated in one of the following 4 ways: &#013; > color=red  &#013; > img=path  &#013; > group=red,groupName|yellow,groupName &#013; > range= red,1,2 | yellow,5,6",
		    latLng: "Example: 18.7058698,-70.9039233"
		};

    // Private functions
    function _setTemplate(templateName, map) {
        if (templateName in _templates)
            _templates[templateName].func(map);
    }

    // Function that generates a guidid
    function _newGuid() {
        function S4() {
            return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        }

        return (S4() + S4() + "-" + S4() + "-4" + S4().substr(0, 3) + "-" + S4() + "-" + S4() + S4() + S4()).toLowerCase();
    }

    function _colourNameToHex(colour) {
        var colours = {
            "aliceblue": "#f0f8ff", "antiquewhite": "#faebd7", "aqua": "#00ffff", "aquamarine": "#7fffd4", "azure": "#f0ffff",
            "beige": "#f5f5dc", "bisque": "#ffe4c4", "black": "#000000", "blanchedalmond": "#ffebcd", "blue": "#0000ff", "blueviolet": "#8a2be2", "brown": "#a52a2a", "burlywood": "#deb887",
            "cadetblue": "#5f9ea0", "chartreuse": "#7fff00", "chocolate": "#d2691e", "coral": "#ff7f50", "cornflowerblue": "#6495ed", "cornsilk": "#fff8dc", "crimson": "#dc143c", "cyan": "#00ffff",
            "darkblue": "#00008b", "darkcyan": "#008b8b", "darkgoldenrod": "#b8860b", "darkgray": "#a9a9a9", "darkgreen": "#006400", "darkkhaki": "#bdb76b", "darkmagenta": "#8b008b", "darkolivegreen": "#556b2f",
            "darkorange": "#ff8c00", "darkorchid": "#9932cc", "darkred": "#8b0000", "darksalmon": "#e9967a", "darkseagreen": "#8fbc8f", "darkslateblue": "#483d8b", "darkslategray": "#2f4f4f", "darkturquoise": "#00ced1",
            "darkviolet": "#9400d3", "deeppink": "#ff1493", "deepskyblue": "#00bfff", "dimgray": "#696969", "dodgerblue": "#1e90ff",
            "firebrick": "#b22222", "floralwhite": "#fffaf0", "forestgreen": "#228b22", "fuchsia": "#ff00ff",
            "gainsboro": "#dcdcdc", "ghostwhite": "#f8f8ff", "gold": "#ffd700", "goldenrod": "#daa520", "gray": "#808080", "green": "#008000", "greenyellow": "#adff2f",
            "honeydew": "#f0fff0", "hotpink": "#ff69b4",
            "indianred ": "#cd5c5c", "indigo": "#4b0082", "ivory": "#fffff0", "khaki": "#f0e68c",
            "lavender": "#e6e6fa", "lavenderblush": "#fff0f5", "lawngreen": "#7cfc00", "lemonchiffon": "#fffacd", "lightblue": "#add8e6", "lightcoral": "#f08080", "lightcyan": "#e0ffff", "lightgoldenrodyellow": "#fafad2",
            "lightgrey": "#d3d3d3", "lightgreen": "#90ee90", "lightpink": "#ffb6c1", "lightsalmon": "#ffa07a", "lightseagreen": "#20b2aa", "lightskyblue": "#87cefa", "lightslategray": "#778899", "lightsteelblue": "#b0c4de",
            "lightyellow": "#ffffe0", "lime": "#00ff00", "limegreen": "#32cd32", "linen": "#faf0e6",
            "magenta": "#ff00ff", "maroon": "#800000", "mediumaquamarine": "#66cdaa", "mediumblue": "#0000cd", "mediumorchid": "#ba55d3", "mediumpurple": "#9370d8", "mediumseagreen": "#3cb371", "mediumslateblue": "#7b68ee",
            "mediumspringgreen": "#00fa9a", "mediumturquoise": "#48d1cc", "mediumvioletred": "#c71585", "midnightblue": "#191970", "mintcream": "#f5fffa", "mistyrose": "#ffe4e1", "moccasin": "#ffe4b5",
            "navajowhite": "#ffdead", "navy": "#000080",
            "oldlace": "#fdf5e6", "olive": "#808000", "olivedrab": "#6b8e23", "orange": "#ffa500", "orangered": "#ff4500", "orchid": "#da70d6",
            "palegoldenrod": "#eee8aa", "palegreen": "#98fb98", "paleturquoise": "#afeeee", "palevioletred": "#d87093", "papayawhip": "#ffefd5", "peachpuff": "#ffdab9", "peru": "#cd853f", "pink": "#ffc0cb", "plum": "#dda0dd", "powderblue": "#b0e0e6", "purple": "#800080",
            "red": "#ff0000", "rosybrown": "#bc8f8f", "royalblue": "#4169e1",
            "saddlebrown": "#8b4513", "salmon": "#fa8072", "sandybrown": "#f4a460", "seagreen": "#2e8b57", "seashell": "#fff5ee", "sienna": "#a0522d", "silver": "#c0c0c0", "skyblue": "#87ceeb", "slateblue": "#6a5acd", "slategray": "#708090", "snow": "#fffafa", "springgreen": "#00ff7f", "steelblue": "#4682b4",
            "tan": "#d2b48c", "teal": "#008080", "thistle": "#d8bfd8", "tomato": "#ff6347", "turquoise": "#40e0d0",
            "violet": "#ee82ee",
            "wheat": "#f5deb3", "white": "#ffffff", "whitesmoke": "#f5f5f5",
            "yellow": "#ffff00", "yellowgreen": "#9acd32"
        };

        if (typeof colours[colour.toLowerCase()] != 'undefined')
            return colours[colour.toLowerCase()];

        return colour;
    }

    function _encode_triplet(e1, e2, e3) {
        var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var enc1 = e1 >> 2;
        var enc2 = ((e1 & 3) << 4) | (e2 >> 4);
        var enc3 = ((e2 & 15) << 2) | (e3 >> 6);
        var enc4 = e3 & 63;
        return keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4);
    }

    function _encodeRGB(r, g, b) {
        return _encode_triplet(0, r, g) + _encode_triplet(b, 255, 255);
    }

    // Generate a pixel by a encode color.
    function _generatePixel(color) {
        return "data:image/gif;base64,R0lGODlhAQABAPAA" + color + "/yH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==";
    }

    // Encode a hexadecimal color.
    function _encodeHex(s) {
        s = _colourNameToHex(s);
        s = s.substring(1, 7);
        if (s.length < 6) {
            s = s[0] + s[0] + s[1] + s[1] + s[2] + s[2];
        }
        return _encodeRGB(
        parseInt(s[0] + s[1], 16), parseInt(s[2] + s[3], 16), parseInt(s[4] + s[5], 16));
    }

    /**
	*  addTemplate 
	*
	*  Add template to google map visual.
	*
	*	@param 		String 		template name.
	*	@param		Function 	template function, it will receive a google map object on the first argument.
	*
	*  @since 		0.1.0
	*/
    _datatransformerGooglemap.prototype.addTemplate = function (templateName, templateFunction) {
        _templateList.push(templateName);
        _templates[templateName] = { func: templateFunction };
    }

    /**
	*  changeLatLng 
	*
	*  Change default latitude and longitude, for centering google map.
	*
	*	@param 		Number 		latitude.
	*	@param		Number 		longitude.
	*
	*  @since 		0.1.0
	*/
    _datatransformerGooglemap.prototype.changeLatLng = function (latitude, longitude) {
        LATITUDE_DEFAULT = latitude,
		LONGITUD_DEFAULT = longitude;
    }

    /**
	*  initialize 
	*
	*  initialize to extend datatransformer visuals.
	*
	*  @since 		0.1.0
	*/
    _datatransformerGooglemap.prototype.initialize = function () {
        /**
        *  googlemap-basic
        *
        *	Visual that shows marker on google map
        * 
        *  @since 		0.1.0
        */
        datatransformer.addVisual("googlemap-basic",
        {
            title: { label: "title", type: String, required: true, order: 1 },
            subtitle: { label: "subtitle", type: String, required: false, order: 2 },
            latitude: { label: "latitude", type: datatransformer.typeColumn, required: true, order: 3 },
            longitude: { label: "longitude", type: datatransformer.typeColumn, required: true, order: 4 },
            markerTitle: { label: "marker Title", type: String, tooltip: _tootip.markerTitle, order: 5 },
            markerShow: { label: "show marker", type: Boolean, order: 6 },
            iconTarget: { label: "icon target", type: datatransformer.typeColumn, tooltip: _tootip.iconTarget, order: 7 },
            icon: { label: "icon", type: String, tooltip: _tootip.icon, order: 8 },
            iconSize: { label: "icon Size", type: Number, order: 9 },
            latLng: { label: "lat & lng center", type: String, tooltip: _tootip.latLng, order: 10 },
            zoom: { label: "zoom", type: Number, order: 11 },
            template: { label: "template", type: datatransformer.typeEnum, values: _templateList, order: 12 }
        },
        function () {
            // Generate HTML datatransforme_googlemap_control
            function _generateDTgooglemapControl(containerID, title, subTitle, leyends, map, markers) {
                var _markers = markers,
                    _map = map,
                    _checks = [],
                    _display = true;

                var _templatePrimary = $('<div id="datatransformer_googlemap_control_' + _newGuid() + '" style="\
											   background-color: white;\
											   width: 97px;\
											   height: 200px;\
											   margin-top: 48px;    z-index: 0;    position: absolute;    cursor: pointer;    left: 0px;    top: 0px;\
											   margin-left: 10px;\
											   overflow: auto;\
											   z-index: 2;\
											   box-sizing: border-box;\
											   text-align: left;\
											   -webkit-box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px;    box-shadow: rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px;">');

                // Append title & subtitle
                _templatePrimary.append('<div class="datatransformer_googlemap_control_title" style="\
											 color: rgb(44, 44, 44);    font-family: Roboto, Arial, sans-serif;    -webkit-user-select: none;\
											 font-size: 12px;\
											 font-weight: bold;\
											 padding-left: 8px;\
											 padding-top: 6px;\
											"> '+ title + '</div>');

                _templatePrimary.append('<div class="datatransformer_googlemap_control_subtitle" style="\
												color: rgb(86, 86, 86);    font-family: Roboto, Arial, sans-serif;    -webkit-user-select: none;    font-size: 11px;\
												padding-left: 8px;\
												padding-top: -10px;"> '+ subTitle + '</div>');


                var _minMaxButton = $('<div style="\
											   position: absolute;\
											   overflow: hidden;\
											   top: -2px;\
											   right: 8px;\
											   color: rgb(90, 90, 90);\
											   font-family:  Roboto, Arial, sans-serif;\
											   font-size: 19px;\
											   font-weight: bold;"\
											   > - </div>');

                _minMaxButton.on('click', function () {
                    _display = !_display;

                    if (_display) {
                        _templatePrimary.css('height', '73%');
                        _templateOptions.css('display', 'block');
                    }
                    else {
                        _templatePrimary.css('height', '50px');
                        _templateOptions.css('display', 'none');
                    }
                });

                _templatePrimary.append(_minMaxButton);

                // Append separator
                _templatePrimary.append('<div style="position: relative; overflow: hidden; width: 87%; height: 1px; left: 7%; top: 2px; background-color: rgb(230, 230, 230);"></div>');

                var _templateOptions = $('<div class="datatransformer_googlemap_control_options" style="color: rgb(86, 86, 86);    font-family: Roboto, Arial, sans-serif;    -webkit-user-select: none; font-size: 9px; padding: 6px;">  </div>');
                var _tempalteOption = $('<div style="border: 1px solid rgb(240, 240, 240);padding: 3px;margin-bottom: 3px;  text-align: left;"></div>');

                for (var l in leyends) {
                    var _leyend = leyends[l];
                    var _label = $('<label style="color: ' + _leyend['color'] + ';display: block;"></label>');
                    var _check = $('<input type="checkbox" style="margin-right: 5px;" data-dtgmcbx="' + _leyend['color'] + '" checked>');

                    _check.on('click', function () {
                        var _c = _checks.map(function (c) {
                            return { color: $(c).attr('data-dtgmcbx'), checked: c[0].checked };
                        });
                        var _counter = 0;

                        _markers.forEach(function (m) {
                            if (_c.some(function (v) { return v.color == m.color && v.checked })) {
                                m.setMap(_map);
                                _counter++;
                            }
                            else {
                                m.setMap(null);
                            }
                        });

                        _total.html(_counter);
                    });

                    _label.append(_check);
                    _label.append(_leyend['label']);

                    _tempalteOption.append(_label);
                    _checks.push(_check);
                }

                var _tempalteOptionTwo = $('<div style="border: 1px solid rgb(240, 240, 240);padding: 3px;margin-bottom: 3px;  text-align: center;"></div>');
                var _total = $('<h4>' + _markers.length + '</h4>');
                _tempalteOptionTwo.append(_total);
                _tempalteOptionTwo.append('total');

                _templateOptions.append(_tempalteOption);
                _templateOptions.append(_tempalteOptionTwo);
                _templatePrimary.append(_templateOptions);

                // Append to container
                $(containerID).append(_templatePrimary);
            }

            this.render = function () {
                var latLng = this.config.latLng ? this.config.latLng.split(",") : [];


                var _map = new google.maps.Map(document.getElementById(this.config.elemId), {
                    center: { lat: (Number(latLng[0]) || LATITUDE_DEFAULT), lng: (Number(latLng[1]) || LONGITUD_DEFAULT) },
                    zoom: (Number(this.config.zoom) || 8),
                    panControl: false,
                    streetViewControl: false
                });

                var _ranges = [];  // {colorObj: redObj, start: 1, end: 2 , color : 'red'}
                var _group = [];   // {colorObj: redObj, groupName: 'group name' , color: 'red'}
                var _leyends = []; // {label:'', color: ''}
                var _markers = [];

                var _measureVariables = this.config.markerTitle ? this.config.markerTitle.match(MEASURE_COLUMN_REGEXP) : [];

                var _icon = this.config.icon;
                var _iconFormat = null;
                var _iconSize = this.config.iconSize || 10;
                var _colorDefault = new google.maps.MarkerImage('data:image/gif;base64,R0lGODlhAQABAPAAAAAAAP///yH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==', null, null, null, new google.maps.Size(_iconSize, _iconSize));

                if (typeof _icon != 'undefined' && _icon.indexOf('color=') >= 0) {
                    var _color = _generatePixel(_encodeHex(_icon.replace('color=', '')));
                    _iconFormat = new google.maps.MarkerImage(_color, null, null, null, new google.maps.Size(_iconSize, _iconSize));
                }

                if (typeof _icon != 'undefined' && _icon.indexOf('img=') >= 0) {
                    _iconFormat = new google.maps.MarkerImage(_icon.replace('img=', ''), null, null, null, new google.maps.Size(_iconSize, _iconSize));
                }

                if (typeof _icon != 'undefined' && _icon.indexOf('range=') >= 0) {
                    _ranges = [];
                    var _rangeSyntax = _icon.replace('range=', '');
                    var _rangesArray = _rangeSyntax.split('|');

                    for (var x in _rangesArray) {
                        var _xx = _rangesArray[x].split(",");

                        if (_xx && _xx.length == 3) {
                            var _color = _xx[0]
                            var _colorPix = _generatePixel(_encodeHex(_color));
                            var _varOne = Number(_xx[1]) || 0;
                            var _varTwo = Number(_xx[2]) || 0;
                            _ranges.push({ colorObj: new google.maps.MarkerImage(_colorPix, null, null, null, new google.maps.Size(_iconSize, _iconSize)), start: _varOne, end: _varTwo, color: _color });
                            _leyends.push({ label: _varOne + ' - ' + _varTwo, color: _color });
                        }
                    }
                }
                else if (typeof _icon != 'undefined' && _icon.indexOf('group=') >= 0) {
                    var _rangeSyntax = _icon.replace('group=', '');
                    var _rangesArray = _rangeSyntax.split('|');

                    for (var x in _rangesArray) {
                        var _xx = _rangesArray[x].split(",");

                        if (_xx && _xx.length == 2) {
                            var _color = _xx[0]
                            var _colorPix = _generatePixel(_encodeHex(_color));
                            var _varOne = _xx[1] || '';
                            _group.push({ colorObj: new google.maps.MarkerImage(_colorPix, null, null, null, new google.maps.Size(_iconSize, _iconSize)), groupName: _varOne, color: _color });
                            _leyends.push({ label: _varOne, color: _color });
                        }
                    }
                }

                for (var x in this.data.data) {
                    var _data = this.data.data[x]
                    var _myLatLng = { lat: Number(_data[this.config.latitude]), lng: Number(_data[this.config.longitude]) };
                    var _content = this.config.markerTitle;
                    var _color = null;

                    for (var x in _measureVariables) {
                        var _vv = _measureVariables[x];
                        var re = new RegExp(_vv, 'g');
                        _content = _content.replace(re, _data[_vv.substr(1, _vv.length - 2)]);
                    }

                    if (_ranges.length > 0) {
                        var _mm = _data[this.config.iconTarget];
                        var _jj = _ranges.filter(function (x) {
                            return _mm >= x.start && _mm <= x.end;
                        });

                        _iconFormat = _jj.length ? _jj[0]['colorObj'] : _colorDefault;
                        _color = _jj.length ? _jj[0]['color'] : null;
                    }
                    else if (_group.length > 0) {
                        var _mm = _data[this.config.iconTarget];
                        var _jj = _group.filter(function (x) {
                            return _mm.toLowerCase() == x.groupName.toLowerCase();
                        });

                        _iconFormat = _jj.length ? _jj[0]['colorObj'] : _colorDefault;
                        _color = _jj.length ? _jj[0]['color'] : null;
                    }

                    var marker = new google.maps.Marker({
                        position: _myLatLng,
                        map: _map,
                        icon: _iconFormat,
                        title: _content
                    });

                    marker.color = _color;

                    var infowindow = new google.maps.InfoWindow();
                    var content = _content;

                    google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
                        return function () {
                            infowindow.setContent(content);
                            infowindow.open(_map, marker);
                        };
                    })(marker, content, infowindow));

                    if (this.config.markerShow) {
                        infowindow.setContent(content);
                        infowindow.open(_map, marker);
                    }

                    _markers.push(marker);
                }

                _setTemplate(this.config.template, _map);
                _generateDTgooglemapControl("#" + this.config.elemId, this.config.title, this.config.subtitle, _leyends, _map, _markers);
            }
        });

        // Destroy initialize
        _datatransformerGooglemap.prototype.initialize = null;
    }

    // Return instance
    return new _datatransformerGooglemap();

})($, datatransformer)

// Set default template.
datatransformerGooglemap.addTemplate('default', function () { });

// Loading google map
window.google || document.write('<script src="http://maps.googleapis.com/maps/api/js?v=3.9&sensor=false&language=es&callback=datatransformerGooglemap.initialize" async defer><\/script>');