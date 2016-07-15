var copy = {}; 

/**  here is the class extension for master.js*/
alert('master.js loaded');



var fileObj = {};  
var instancia = null;

var configFiles = ["serverWSUrl.data", "version.data", "allsource.data", "serverRequest.data" ];   //{ 0 = serverURL, 1 = version, 2 = all source }
var serverUpdatePath = null; 
var macTV;
var rr = 0;


Master = {
	initt: function(){

			 // widgetAPI.sendReadyEvent();			
			 console.log("Se Ejecuta Aquiiii=====> "); 
		     document.getElementById("anchor_main").focus();

		     if(instancia == null || instancia === undefined ){			    	
		    	 instancia = new MasterTV();

		    	 console.log("@@@@@@@@@@@@ Instancia"); 		    	  
		    	 console.log(instancia); 		    	  

		     }
		     Master.showWelcomePages();
// 		     Master.inittVideo(); 		     
		},

		receptorWs: function(data){	
			console.log("Aqui Llego. Receptor");
				// ["ACTUALIZAR-APP", "ACTIVAR", "TWEETS", "CAST", "PROGRAMA" "CONTROL" ]
				switch (data.accion) {
					case "ACTUALIZAR-APP":
						// SOLICITUD DE ACTUALIZACION DE APP DESDE EL SERVIDOR.	Cambio de IP cambio de Servidor a las Apliaciones.
						// Respuesta por parte de la APlicacion y el Servidor
						if("server" in data){
								serverUpdatePath = data.server; 
								instancia.setFileConfig(); 
						}						

						break;
					case "NOTIFICAR":		
						console.log("Revisara Si es Necesario la Actualizacion.");
						var prog = localStorage.getItem("programaTV");
						if(prog == null || typeof prog == "undefined" || typeof prog == "string" ){
								prog = {}; 
						}

						for (var i in data ) {
							console.log(i + "===> " + data[i]); 
						}

					console.log(data.fechaActual); 
					console.log("Fecha Actualizada:-::: ^^"); 

					if(!(data.fechaActual in prog) ){
						localStorage.setItem("programaTV", null); 	

						Master.ObtenerPrograma(data.fechaActual, data.server, data.fecha); 

							log(data.Msg); 
						alert("En espera de la Programacion");
						return true; 

					} else {

						alert("Ya he Configurado Incorrectamente el programaTV...."); 
					}

					console.log("El contenido ya ha sido actualizado.");
					log(data.Msg); 
					// Master.IrVideoTimeOut();

					break;

					case "ACTIVAR":
						// Devolver la Hora en que se debe configurar.
						alert("Entro a Activar");
						
					//	if(data.fecha != null || typeof data.fecha !== 'undefined' )
						//	Master.confirmDateTime(data.fecha.toString()); 
						Master.IrVideoTimeOut();						
						
						break;
					case "TWEETS":
						// REcepcion de Mesajes Instantaneos desde Un serviddor.					
						if(typeof data.mensaje !== 'undefined'){
							log(data.mensaje); 
						}						
						break;
						
					case "BROADCAST":  // Boletin. Difusion de Informacion.
						
						break;
					case "PROGRAMA":  // LA INFORMACION COMPLETA PARA EL PROGRAMA DEL DIA Y SU HORARIO.						
						log(data.mensaje);
						Master.actualizarProgramacion(data.data);
											
						alert("Va Ha actualizar"); 
						Master.slideNow(); 
						
						break;						
					case "CONTROL":  // CONTROL REMOTO DESDE EL SERVER.
						// Permite enviar y recibir funcionalidades del control remoto en el servici
												
						break;						
					default:
					alert("La Accion no APlicca"); 					
					alert("---*--*-----*-*-*-*-*- "); 
						break;
				}
		}, 

			cambiarSimpleImgPantallaFull: function(url, eventos){
			var op = Master.setOptionEsquema(1);
				Master.renderStruct(op, function(){

					var view = {
		    	        	showInfoBar: false, 
		    	        	EventRemote: eventos,		    	          				
		    				slide: {
		    	        		esquemaTipo: 1,
		    	        		"autochange": false,	    	        		
		    	        		duracion: 0,
		    	        		seccion: [
		    	    						{ 
		    	    						posicion: 1,
		    	    						encabezado: "Encabezado",
		    	    						contenidoTipo: 1,
		    	    						bgColor: "#000",
		    	    						modulo: "jquery",
		    	    						contenido: [ {
		    											representacionTipo: 3,
		    											data: "<img src='"+ url + "' style='max-height: 715px;  align-items: center;'>"	    	    						
		    	    								}	    	    								
		    	    								]				
		    	    						}				
		    	    					]
		    	    				}	        			
		    	        	};		        	
		        	Master.renderPage(view);
				});
		}, 

		ObtenerPrograma: function(fecha, servicio, fechaServidor){	
		
			localStorage.setItem("server", servicio); 
			var alt = {};
			var url = servicio + "&Mac=" + macTV;	

			url = url.replace("10.234.133.76", "localhost");

			alert(servicio); 	
			alert("Aqui Hara un Envio."); 

			$.getJSON(url, function(res){
				if(res.IsOk){
					// Estuvo OK el asunto
					alt[fecha] = {programa: res.programa, contenido: res.contenido}; 
					localStorage.setItem("programaTV", JSON.stringify(alt) );
					log("Configurando programa."); 

					Master.cambiarSimpleImgPantallaFull("template/img/actualizando.png", {}); 
					// Master.setTimerPerPrograma(fechaServidor);
					Master.setFormatTimerPerPrograma(fechaServidor);
				}
	});	
}, 

setFormatTimerPerPrograma: function(serverDatetime){
			// set timer 
			var arrF = serverDatetime.split(",");			
			var fechaHoy = new Date(parseInt(arrF[0]), (parseInt(arrF[1])-1), parseInt(arrF[2]), parseInt(arrF[3]), parseInt(arrF[4]), parseInt(arrF[5]) );
			var timeNow = parseInt(arrF[3])+ ":" +  parseInt(arrF[4]) + ":" + parseInt(arrF[5]); 

			var a_obj = JSON.parse(localStorage.getItem("programaTV")); 
			var arregloPrograma = {}; 
			for (var i in a_obj) {				
				// EL dia de Hoy. Recorrido del Programa para almacenarlo.
				var programa = a_obj[i].programa; 
				for(var ti in programa){
					// Incluir en el Programa solo la Programacion que falta del Dia.
					if( programa.hasOwnProperty(ti) ){			

								var pp = {
								inicio: programa[ti].HoraInicioBloque, 
								duracion: programa[ti].DuracionBloque
									}; 
					}
				}
			}
			alert(serverDatetime); 
		}, 

		showWelcomePages: function(indx ){			
			if(typeof indx === 'undefined'){
				indx = 0; 
			}
			if(indx > 0){
				indx = -1;
			}
			var op = Master.setOptionEsquema(indx);		            	
        	Master.renderStruct(op);        	
		} 

};


// Master TV
MasterTV = function() {	
	this.page_config = {};	
	this.app_info = {server: "", version: "", serverRequest: ""};	

	this.ManagerPages = {
			showInfoBar: false,
			EventRemote: {ENTER: function(){ alert("Enter");  }, GREEN: function(){ alert("Verde"); } },
			infoBarLeyend: [],
			content: {}
	};  
	
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
		
	// var firstData = ['192.168.183.1:9300'];
	var firstData = ['10.234.133.76:9300'];
	var firstServer = 'localhost:7777/GestionVista/'; 
	var app_info = {}; 
	var page_config = {}; 	

this.app_info.server = firstData; 
 return true; 

	
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
				textA = firstServer.toString();				
				app_info.serverRequest = firstData;				
				
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
						
			switch (index) { // { 0 = serverURL, 1 = version, 2 = all source }
				case 0:
				alert("Validar que el socket es nuevo"); 
				var validate = false;
				var currentUpdatePath = allStr.split(",");//  firstData;

				var tempSr = allStr.split(",")
				for(var t in firstData){

					if( tempSr.indexOf(firstData[t]) === -1 ){
						validate = true;
						currentUpdatePath = firstData;
					}
				}

				if(serverUpdatePath != null){
					validate = true; 
					currentUpdatePath = firstData.split(",");				
				}

				if(validate){
					alert("Actualizara el Documento para las IPS o por Actualicion del Servicio"); 

					var fileObjTemp = fileSystemObj.openCommonFile(curWidget.id + '/' + configFiles[0], 'w');
					fileObjTemp.writeAll(currentUpdatePath.toString() );
					fileSystemObj.closeCommonFile(fileObjTemp);
				}

					app_info.server = currentUpdatePath;
					break;				
				case 1:  					

					eval("versionArr = " + allStr);					
					app_info.version = versionArr.version;
					app_info.fecha_modificacion = versionArr.fecha_modificacion;

					break;
					
				case 2:  // Codigo de Programamacion
					eval("page_config.source = " + allStr );
					alert("La version del Codigo es: ");
					alert(allStr);					
					break;
					
				case 3:					
					app_info.serverRequest = allStr.trim();					
					break;
					
				default:
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


var indeces = 1;
var it = 0; 

MasterTV.prototype.handleKeyDown = function (keyCode) {
	alert("SceneMainPage.handleKeyDown(" + keyCode + ")");
	// TODO : write an key event handler when this scene get focused
	
	for(var ki in this.ManagerPages.EventRemote){
		if (this.ManagerPages.EventRemote.hasOwnProperty(ki)){
			if(ki in sf.key){				
				if(sf.key[ki] == keyCode){					
					this.ManagerPages.EventRemote[ki](); 
				}
			}
		}
	}
};

MasterTV.prototype.ValidarPrograma= function(dt){

}; 


var ConexionTV = function(listIp){	
	alert("Instancia de la Conxion del Servidor");
	this.fechaJson = new Date();	
	this.Server = {};
	this.current_TV = {
			clienteSessionID: 0, 
			macAdrees: "", 
			Tipo: 'TV', 	
			hash: "",
			fecha: "",
			accion: "ACTIVAR",
			data: {}
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
	log("Buscando la conexion del servicio.");
	//Let the user know we're connected
	this.Server.bind('open', function() {
	log( "Connected." );	
	var networkPlugin = document.getElementById('pluginNetwork');
	// var mac = networkPlugin.GetMAC(0) || networkPlugin.GetMAC(1);
	// macTV = mac;  

	// Provicional
	macTV = '222-222-2222'; 
	mac = macTV; 

	
	current_TV = {
			clienteSessionID: 0,
			macAdrees: mac,			
			Tipo: "TV",
			hash: "",
			fecha: fechaJson,
			accion: "ACTIVAR",
			codigo: 0,
			mensaje: "",
			data: {}
		};

		ServerTEMP.send("message", JSON.stringify(current_TV) );
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
		if(payload.trim() != ""){
			var infor = JSON.parse(payload); 
			Master.receptorWs(infor);			
			// payload.

		}
		
	});	
	this.Server.connect();
	this.cIndex = cIndex;
};


var content = $("<div id='log'></div>");
function log( text, titulo, forma ) {	
	 content.html( text );
	 if(forma = 0){
	 	$.blockUI({
	        message: content.html(),
	       centerY: 0, 
            css: { top: '10px', left: '', right: '10px' } ,
            timeout: 2000 
	    });	
	 } else {
	 	var til = ""; 
	 	if(typeof titulo !== "undefined"){
	 		til = titulo; 
	 	}
	 	  $.growlUI(til, text ); 
	 }
}



$( document ).ready(function() {
	Master.initt();
  });