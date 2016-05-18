/**  here is the class extension for master.js*/
alert('master.js loaded');
var widgetAPI = new Common.API.Widget();
var tvKey = new Common.API.TVKeyValue();
var pluginAPI = new Common.API.Plugin();
var fileSystemObj = new FileSystem();
var fileObj = {};  
var instancia = null;
var gobalThemeChart = {};
var configFiles = ["serverUrl.data", "version.data", "allsource.data", "log.data" ];   //{ 0 = serverURL, 1 = version, 2 = all source } 

/*** ECHART REQUIRED LIB  ***/
require.config({
    paths: {
        echarts: 'src/eChart'
    }
});

require(['echarts/theme/shine'], function (tarTheme) {
	gobalThemeChart = tarTheme;
});

/***** FIN ECHARTS LIB ******/
Master = {	
		cuPage: "",
		cuTheme: "",
		$cuPage: {},
		KeyDown: function(){
		var keyCode = event.keyCode;		
			    if(instancia == null || instancia === undefined ){			    	
			    	instancia = new MasterTV();			    	
			    	instancia.handleKeyDown(keyCode);
			    }else{			    	
			    	instancia.handleKeyDown(keyCode);			        
			    }
		},
		
		initt: function(){
			 widgetAPI.sendReadyEvent();			
		     document.getElementById("anchor_main").focus();
		     if(instancia == null || instancia === undefined ){			    	
		    	 instancia = new MasterTV();		    	  
		     }
		    require(
		            [
		                'echarts',
		                'echarts/chart/bar', // require the specific chart type
		                'echarts/chart/line',
		                'echarts/chart/pie'
		            ],
		            function (ec) {
		                // Initialize after dom ready
		            	/*             
		                    var myChart = ec.init(document.getElementById("applicationWrapper"));           
		                    myChart.setTheme(gobalTheme);
		                    myChart.setOption(option);	                    
		            	 */
		            	
		            	// Prueba Renderizando el Html		            	
		            	var op = Master.setOptionEsquema(3); 
		            	/* {css:"inicio_02.css",
		            			url: "template/inicio.html",
		            			divID: undefined,
		            			isAdd: null
		            			};		            			
		            	*/
		            	
		            	
		            	
		            	Master.renderPage(op);
		            	
		            }
		            );		    
		},
		reconectar : function(){			
			if(instancia == null || instancia === undefined ){			    	
		    	 instancia = new MasterTV();		    	  
		     }		
			instancia.conectar(true);			
		},		
		recetor: function(data){
		}		
};

Master.setOptionEsquema = function (esquema){
	 var id = "#";
	 var option = {css: "base.css", url: "template/base.html"};
	 
	switch(esquema){ 			
		case 1:  //
			id = "#aplication-Full";
			option.divId = id; 
		break; 			
		
		case 2:
			id = "#aplication-02";
			option.divId = id; 
		break;

		case 3:
			id = "#aplication-03";
			option.divId = id;
		 			
		break;

	case 4:
	// <div class='cs-div_V1x2'> </div>
		id = "#aplication-04";
		option.divId = id;		
		break;
		
	case 5:
		// 
		id = "#aplication-05";
		option.divId = id;
		 
		break;

		case 6:
			id = "#aplication-06";
			option.divId = id;
		break;

		case 7:
		// baseHtml = "<div class='cs-div_H1x2'>";
			id = "#aplication-07";
			option.divId = id;		 
		break;

		case 8:
			id = "#aplication-08";
			option.divId = id;
		break;

		case 9:
			id = "#aplication-09";
			option.divId = id;			 			 				
		break;

		default:			
			option.url = "template/inicio.html"; 
			option.divId = undefined;
		break;
	}
	
	return option; 
	
}; 


Master.renderPage = function(option, callfunctionBack){
	//  { c s s : "", u r l: "", divId: ""}	
	Master.setTheme(option.css);
	Master.setPage(option, callfunctionBack); 
	// 
};

Master.setPage = function(option, callfunctionBack){
	var isAdd = option.isAdd;	
	var html = "";
	
	if(typeof callfunctionBack === 'undefined' ){
		callfunctionBack = function(){}; 
	}
	
	$.get(option.url, function( data ) {
		Master.cuPage = data;	
		Master.$cuPage = $(data);		
		html = data;
		 
		if( typeof(option.divId) !== 'undefined'){
			html = Master.$cuPage.find(option.divId).html();
		}
		
		if(typeof isAdd === 'undefined' ||isAdd === null){
			alert("#html DIV");
			alert(html);
			alert("FINAL SIP"); 
			$("#applicationWrapper").html(html);
			
			callfunctionBack();
			
			return true; 
		}	
		if(isAdd == true){
			alert("Apennd DIV");
			$("#applicationWrapper").append(html);
			callfunctionBack();
			return true; 
		}
		
		$("#applicationWrapper").html(html);
		callfunctionBack();
		return true; 		
	});
};

Master.setTheme = function(file){	
	$.get("template/styles/" + file, function( data ) {
		Master.cuTheme = data;
		$("#cssApplicationWrapper").html("<style>" + Master.cuTheme + "</style>");
	});	
};

MasterTV = function() {	
	this.page_config = {};	
	this.app_info = {server: "", version: ""};	
	this.ManagerPages = {}; 
	var index;
	var content_txt = [];
	var content_img = [];
	var title_txt = [];	
	var sessionesPAG = ["applicationWrapper"];
	
	if(this.setFileConfig()){
		this.conn = new ConexionTV(this.app_info.server);
	}	
	
	widgetAPI.sendReadyEvent();	
	
};

MasterTV.prototype.setFileConfig = function(){	
	
	// Yes Exists File. OF COMPUTER.	
	var firstData = ['10.234.133.76:9300'];
	var app_info = {}; 
	var page_config = {}; 	
	
	configFiles.forEach(function (item, index, array) {		
		fileObjFirst = fileSystemObj.openCommonFile(curWidget.id + '/' + item , 'r');
		if(!fileObjFirst){
			alert("=== Primer Uso de la Aplicacion. Se Escribe el Archivo en commun: " + item);
			 var textA = ""; 
			switch (index) {
			case 0:  //
				textA = firstData.toString();				
				app_info.server = firstData;
				break;				
			case 1:  // { 0 = serverURL, 1 = version, 2 = all source }
				textA = "{version: '1.0.0', fecha_modificacion: '2016-05-16'}";
				eval("textArr = " +  textA);
				app_info.version = textArr.version;
				app_info.fecha_modificacion = textArr.fecha_modificacion;
				
				alert(textA);
				alert("======= La Version ==================="); 
				break;			
			case 2:  //
				textA = "{ initt: function(option){  } }";				
				page_config.source = textA;  
				break;				
			case 3:
				textA = "/** Log.data **/";
				break;				
			default:
			   textA = ""; 			
			break; 
			}
			var fileObjTemp = fileSystemObj.openCommonFile(curWidget.id + '/' + item, 'w');
			fileObjTemp.writeAll(textA);
			fileSystemObj.closeCommonFile(fileObjTemp);
			
		} else {
			alert("/**************** Existen Documentos. (Lectura de los Ficheros) **********/"); 
			
			var strLine = '';
			var arrResult = new Array();
			var allStr = "";
			
			while (strLine = fileObjFirst.readLine()) {
			    arrResult.push(strLine);
			    allStr += " "+ strLine; 
			}
						
			switch (index) {
				case 0:  //		array de IPs			
					app_info.server = allStr.split(",");
					break;				
				case 1:  // { 0 = serverURL, 1 = version, 2 = all source }					

					eval("versionArr = " + allStr);					
					app_info.version = versionArr.version;
					app_info.fecha_modificacion = versionArr.fecha_modificacion;					
					break;
					
				case 2:  // Codigo de Programamacion
					eval("page_config.source = " + allStr );					
					
					alert("La version del Codigo es: ");
					alert(allStr);
					break;					
				default:					
					console.log("Nungun Archivo en Particular" ); 
				break; 
			}
		}
	});	
		this.app_info = app_info;
		this.app_config = page_config;
		return true;
};

MasterTV.prototype.GetPages = function(url){
	
};

MasterTV.prototype.UpdateConfig = function(){	
}; 


MasterTV.prototype.setPagesConfig = function () {
};

MasterTV.prototype.renderPage = function (tipoID) {
};

var indeces = 1;
var it = 0; 

MasterTV.prototype.handleKeyDown = function (keyCode) {
	alert("SceneMainPage.handleKeyDown(" + keyCode + ")");
	// TODO : write an key event handler when this scene get focused	
	switch (keyCode) {
		case sf.key.LEFT:
			
			var arry = ['<img src="template/img/samsungsetup.jpg" style="max-width: 1280px; max-height: 700px;">', 
			            '<img src="template/img/indice.jpg" style="max-width: 1280px; max-height: 700px;">',
			            '<img src="template/img/karthic.c.d.jpg" style="max-width: 1280px; max-height: 700px;">']; 
			
			var full = Master.setOptionEsquema(1);			
        	Master.renderPage(full, function(){
        		
        		alert("Antes de********* "); 
            	alert(arry[it]);
            	$("#sc-full").html(arry[it]);        	
            	it++;
            	if(it > 2){
            		it =0; 
            	}
            	
        	});
        	
        		 
			
			break;
		case sf.key.RIGHT:
			break;
		case sf.key.UP:
			--index;
			if(index<0)index = index + 4;
			break;
		case sf.key.DOWN:			
			if (indeces >= 10){
				indeces = 0; 				
			}			
			
			var op = Master.setOptionEsquema(indeces);
        	Master.renderPage(op);
        	
        	indeces++; 
			index++;
			
			if(index>3)index = index - 4;			
			
			break;
		case sf.key.ENTER:
			break;
		default:
			
			alert(localStorage.getItem("name"));
		
			break;
	}
};


var ConexionTV = function(listIp){	
	alert("Instancia de la Conxion del Servidor");
	this.fechaJson = new Date();	
	this.Server = {};
	this.current_TV = {
			clienteSessionID: 0, 
			macAdreesTV: "", 
			Tipo: 'TV', 	
			hash: "",
			fecha: "",
			accion: "REP"
		};
	this.ListIp = listIp; 
	this.cIndex = 0;	
	this.conectar(false);
	// REcorrido de listas de IPs
		// Ajuses Smart TV comentaro de Exhibicion.
};

ConexionTV.prototype.conectar = function(cNext){
	var cLen = this.ListIp.length;
	var cArr = this.ListIp;
	var cIndex = this.cIndex;
	
	var fechaJson = this.fechaJson.toJSON();
	
	if(cNext){
		cIndex++;		
		if(cIndex >= cLen ){
			cIndex = 0; 
		}
	}	
	
	var ServerTEMP = new FancyWebSocket('ws://' + cArr[cIndex].toString().trim());	
	this.Server = ServerTEMP;
	//Let the user know we're connected
	this.Server.bind('open', function() {
	log( "Connected." );	
	var networkPlugin = document.getElementById('pluginNetwork');
	var mac = networkPlugin.GetMAC(0) || networkPlugin.GetMAC(1);
	current_TV = {
			clienteSessionID: 0,
			macAdreesTV: mac,
			Tipo: 'TV',
			hash: "",
			fecha: fechaJson,
			accion: "ACTIVA"
		};
	ServerTEMP.send("message", current_TV);
	});
	
	//OH NOES! Disconnection occurred.
	this.Server.bind('close', function( data ) {
		log( "Disconnected." );
		var myVarReconect = setTimeout(function(){
			Master.reconectar();						
		}, 40000);			
		
				
	});
	//Log any messages sent from server
	this.Server.bind('message', function( payload ) {		
		Master.recetor(payload);
		log( "Mensaje Recibido" );
	});	
	this.Server.connect();
	this.cIndex = cIndex;
}; 

var content = $("<div id='log'></div>");
function log( text ) {
	//Add text to log
	 content.html((content.html()?"\n":'')+ text );
	 $.blockUI({
	        message: content.html(),
	        fadeIn: 700,
	        fadeOut: 700,
	        timeout: 2000,
	        showOverlay: false,
	        centerY: false,
	        css: {
	            width: '760px',
	            top:  110 + 'px',
	            left: 240 + 'px',
	            border: 'none',
	            padding: '15px',
	            backgroundColor: '#865',
	            '-webkit-border-radius': '10px',
	            '-moz-border-radius': '10px',
	            opacity: 1,
	            color: '#fff'
	        }
	    });	
}

$( document ).ready(function() {
	Master.initt();
  });