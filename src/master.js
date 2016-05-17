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
	alert( "SetFileConfig()" ); 
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

MasterTV.prototype.UpdateConfig = function(){	
}; 


MasterTV.prototype.setPagesConfig = function () {
};

MasterTV.prototype.renderPage = function (tipoID) {
	
};

MasterTV.prototype.handleKeyDown = function (keyCode) {
	alert("SceneMainPage.handleKeyDown(" + keyCode + ")");
	// TODO : write an key event handler when this scene get focused	
	switch (keyCode) {
		case sf.key.LEFT:
			break;
		case sf.key.RIGHT:
			break;
		case sf.key.UP:
			--index;
			if(index<0)index = index + 4;
			break;
		case sf.key.DOWN:
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