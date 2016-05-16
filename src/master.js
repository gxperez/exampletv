/**  here is the class extension for master.js*/
alert('master.js loaded');

var widgetAPI = new Common.API.Widget();
var tvKey = new Common.API.TVKeyValue();
var pluginAPI = new Common.API.Plugin();
var fileSystemObj = new FileSystem();
var fileObj = {};  
var instancia = null;
var gobalThemeChart = {};
var configFiles = ["serverUrl.data", "version.data", "allsource.data", "log.data" ]; 
//{ 0 = serverURL, 1 = version, 2 = all source } 

require.config({
    paths: {
        echarts: 'src/eChart'
    }
});

require(['echarts/theme/shine'], function (tarTheme) {
    gobalTheme = tarTheme;
});

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
		     
		     alert("Finalizo la COnfiguaracion");
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
		                console.log(gobalTheme);             
		                    var myChart = ec.init(document.getElementById("applicationWrapper"));           
		                    myChart.setTheme(gobalTheme);
		                    myChart.setOption(option);		                    
		                    ggg = myChart;
		            	 */
		            }
		            );		    
		    // Instancia del Web Socket.
		    
		    alert("Estableciendo conexion con el webSocket");
			Server = new FancyWebSocket('ws://10.234.133.76:9300'); 
			//Let the user know we're connected
			Server.bind('open', function() {
				log( "Connected." );
			});
			//OH NOES! Disconnection occurred.
			Server.bind('close', function( data ) {
				log( "Disconnected." );
			});
			//Log any messages sent from server
			Server.bind('message', function( payload ) {
				log( payload );
			});
			
	//		alert("Se va a conectar");
			// Gananar un Hanckathon, no es parte de la Propuesta.
			// 
			
			Server.connect();			
			alert("LLego Aqui");			
			// Ajuses Smart TV comentaro de Exhibicion.
		}		
};


MasterTV = function() {	
	this.page_config = {};	
	this.app_info = {server: "", version: ""};	
	
	var index;
	var content_txt = [];
	var content_img = [];
	var title_txt = [];	
	
	
	var sessionesPAG = ["applicationWrapper"];	
	
	if(this.setFileConfig()){
		alert("Se configuro correctamente...."); 
	}	
	widgetAPI.sendReadyEvent();
};

MasterTV.prototype.setFileConfig = function(){	
	alert( "Ayunen: SetFileConfig()" ); 
	// Yes Exists File. OF COMPUTER.	
	var firstData = ['10.234.133.76:9300'];
	var app_info = {}; 
	var page_config = {}; 	
	
	configFiles.forEach(function (item, index, array) {		
		fileObjFirst = fileSystemObj.openCommonFile(curWidget.id + '/' + item , 'r');		
		if(!fileObjFirst){
			alert("=== Se Escribe el Archivo en commun: " + item);
			 var textA = ""; 
			switch (index) {
			case 0:  //   
				alert("Configurando Lista de Serviodr"); 
				textA = firstData.toString();				
				app_info.server = firstData;
				
				alert("===================== Jesus LLoro ===============");
				break;				
			case 1:  // { 0 = serverURL, 1 = version, 2 = all source }
				textA = "1.0.0,2016-05-16";
				textArr = textA.split(","); 
				app_info.version = textArr[0];
				app_info.fecha_modificacion = textArr[1];								
				  
				break;			
			case 2:  //
				textA = "{ initt: function(option){  } }";				
				page_config.source = textA;  
				break;			
			default:
			   textA = ""; 			
			break; 
			}
			
			var fileObjTemp = fileSystemObj.openCommonFile(curWidget.id + '/' + item, 'w');
			fileObjTemp.writeAll(textA);
			fileSystemObj.closeCommonFile(fileObjTemp);			
		} else {	
			
			alert("------------------------------------------ Ya existe el Documento Please no lo sobreescribas-----------"); 
			
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
					
					versionArr = allStr.split(","); 
					app_info.version = textArr[0];
					app_info.fecha_modificacion = textArr[1];					
					break;
					
				case 2:  // Codigo de Programamacion
					eval("page_config.source = " + allStr );					
					alert(page_config.source);
					
					alert("La version del Codigo es: ");
					alert(allStr);
					
					
					break;					
				default:
					console.log("Has descuidado en tu vida" ); 
				break; 
			}
			
			
			
		}
	});
	
		this.app_info = app_info;
		this.app_config = page_config;
		return true;
}; 

MasterTV.prototype.setPagesConfig = function(){	
	// Las tablas de diferencias entre los elementos.
	
	fileObj = fileSystemObj.openCommonFile(curWidget.id + '/testFile.data', 'w');
	fileObj.writeAll('var js = "La sinfonica de Dios"; for(var i in js){ console.log(i); console.log(js[i]);  }');
	fileSystemObj.closeCommonFile(fileObj);
	
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
			alert("Tecla Enter: Obtener Mac del TV:");
			 var networkPlugin = document.getElementById('pluginNetwork');
			 var mac = networkPlugin.GetMAC(0) || networkPlugin.GetMAC(1);						
			alert("Envio de la Mack Adress al Servidor"); 
				log( 'You: ' + mac );
				send(mac);
				/*
				 * 
				 * LA APLICACIóN SOPOPRTA MUY BIEN EL LOCALSTOGE.
				 
			if(typeof(Storage) !== "undefined") {				
			    // Code for localStorage/sessionStorage.
				alert("Este soporta y eso esta OK SI ");
			//	localStorage.setItem("name", "Mi SUPER");				
			} else { 
			    // Sorry! No Web Storage support..
				alert("Sorry! No Web Storage support..");				
			}
			*/				
				// COMUN FILE CONTROLLER.
				
			break;
		default:
			alert("SIII Probando"); 
			alert(localStorage.getItem("name"));		
			alert("handle default key event, key code(" + keyCode + ")");
			
			alert("Aplicacion de Lectura");
			var fileObj2 = fileSystemObj.openCommonFile(curWidget.id + '/testFile.data', 'r');
			var strLine = '';
			var arrResult = new Array();

			while (strLine = fileObj2.readLine()) {
			    arrResult.push(strLine);
			    alert(strLine); 
			}
			
			break;
	}
};
 
/* EL websocket en el TV
 * **/
var Server;
var content = $("<div id='log'></div>");


function log( text ) {
	//Add text to log
	 content.html((content.html()?"\n":'')+text);
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
	            backgroundColor: '#000',
	            '-webkit-border-radius': '10px',
	            '-moz-border-radius': '10px',
	            opacity: 1,
	            color: '#fff'
	        }
	    });	
}

function send( text ) {
	Server.send( 'message', text );	
}

$( document ).ready(function() {
	Master.initt();
	alert("=================================");
	
  });