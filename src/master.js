/**  here is the class extension for master.js*/
alert('master.js loaded');

var widgetAPI = new Common.API.Widget();
var tvKey = new Common.API.TVKeyValue();
var pluginAPI = new Common.API.Plugin();
var fileSystemObj = new FileSystem();
var fileObj = {};  
var instancia = null;

var configFiles = ["serverWSUrl.data", "version.data", "allsource.data", "serverRequest.data" ];   //{ 0 = serverURL, 1 = version, 2 = all source }
var serverUpdatePath = null; 
var macTV;
var rr = 0;

Master = {	
		ec: {},
		sIndex: {seccionActiva: 1,  sccns: {} }, 
		cuPage: "",
		cuTheme: "",
		$cuPage: {},
		lastUpdate: new Date(),
		cTime: new Date(),
		relojId: 0, 
		programaTV: {}, 

		KeyDown: function(){	
			
		var keyCode = event.keyCode;		
			    if(instancia == null || instancia === undefined ){			    	
			    	instancia = new MasterTV();			    	
			    	instancia.handleKeyDown(keyCode);
			    }else{			    	
			    	instancia.handleKeyDown(keyCode);			        
			    }
		},
		
		CorrerRelojLocal: function(){
			Master.cTime.setSeconds(Master.cTime.getSeconds() + 1);			
			Master.relojId = setTimeout("Master.CorrerRelojLocal()", 1000);			
			alert(Master.cTime);
			
		},
		
		getCantidadSlidePorEsquema: function(esquemaTipo){			
			switch(esquemaTipo){
			case 1: 
				return 1; 
				break;
			case 2: 
				return 4; 
				break;
			case 3: 
				return 6; 
			break;
			case 4: 
				return 3;
				break;
			case 5: 
				return 4;
				break;
			case 6: 
				return 2;
				break;
			case 7:
				return 3;  
				break;
			case 8: 
				return 4; 
				break;
			case 9: 
				return 2; 
				break;
			default:
				return 1;
				break;
			}
		}, 
		
		Next: function(){
			// Saber la seccion Active
			// Saber el index Active
			alert("Esta Tecleando el Next=> Enter");			
			
			if(Master.sIndex.sccns[Master.sIndex.seccionActiva].modulo == "jquery"){				
				$("#sld-" + Master.sIndex.sccns[Master.sIndex.seccionActiva].c).hide();
				
				Master.sIndex.sccns[Master.sIndex.seccionActiva].c++;
				if( (Master.sIndex.sccns[Master.sIndex.seccionActiva].c+ 1) > Master.sIndex.sccns[Master.sIndex.seccionActiva].max ){					
					Master.sIndex.sccns[Master.sIndex.seccionActiva].c = 0; 	
				}
				
				$("#sld-" + Master.sIndex.sccns[Master.sIndex.seccionActiva].c).show();
				
				alert("Llego Hasta Aqui"); 
				
				return true;
			}
		}, 
		renderPage: function(option){
			var DivId = "#sc-full"; 
			if("EventRemote" in option){
				instancia.ManagerPages.EventRemote = option.EventRemote; 
			}
			
			if(option.showInfoBar){
				// Muestra Barra de Ayuda. con los Iconos.				
			}
			
			
			for(var it in option.slide.seccion){
				alert("Entro en el Bucle #1"); 
				if(option.slide.seccion.hasOwnProperty(it)){
					alert("Entro en el Bucle #1.hasOwnProperty");
					switch (option.slide.esquemaTipo) {
					case 1:
						
						if(option.slide.seccion[it].posicion == 1){
							
							alert("Entro en el Bucle #1.hasOwnProperty case 1");
							
							var moduloAct = "jquery"; 
							option.slide.seccion[it];							
							var innerHtml = $("<div style='background-color: " + option.slide.seccion[it].bgColor  + "; width: 1280px;  height: 100%; margin: auto; '> </div>");														
							for(var iy in option.slide.seccion[it].contenido){								
								if (option.slide.seccion[it].contenido.hasOwnProperty(iy)){
								//	option.slide.seccion[it].contenido[iy].representacionTipo;									
									alert("Entro en el Bucle #1.hasOwnProperty case: 1=> Contenido Representante 1");
									
									// PAra IMAGEBES = html
									innerHtml.append("<div id='sld-"+ iy+ "' > " + option.slide.seccion[it].contenido[iy].data + " </div>");
									moduloAct = option.slide.seccion[it].modulo;  
									if (option.slide.seccion[it].modulo == "jquery"){
										innerHtml.find("#sld-"+ iy ).hide(); 
									} else {
										// Otros Modulos y configuraciones para esta seccion.
									}
								}
							}							
							
							// Hablamos Ahora.
							if(moduloAct == "jquery"){
								innerHtml.find("#sld-0").show();
							}
							
							Master.sIndex.sccns[option.slide.seccion[it].posicion] = {c: 0, max: option.slide.seccion[it].contenido.length, modulo: "jquery" };
							 
							$(DivId).html(innerHtml.html());						
							
						}
						
						
						
						break;
					default:
						
						$(DivId).html(""); 					
						
						break;
					}  				
				}
			}
				
			option.slide.seccion.length;
			 
		}, 

		IrVideoTimeOut: function(fc){				
	        	
		}, 

		inittVideo: function(){

		 	// alert("##FF## Player PAST"); 			

			/*
				sf.service.VideoPlayer.setPosition({
    left: 100,
    top: 100,
    width: 500,
    height: 400
});
// The bottom of the area is occupied by the Controller UI. Its height for 540p is 73pixel. So the controller's height should be added to "height" value.

// Starts playback
sf.service.VideoPlayer.play({
    url: 'http://10.234.133.76:7777/GestionVista/Docs/Cielo_Boolper.mp4',
    fullScreen: false    // Sets Player to partial mode
});

			try{
			var playerInstance = webapis.avplay;
				webapis.avplay.getAVPlay(Player.onAVPlayObtained, Player.onGetAVPlayError);		
    		}catch(e){
				alert('######getAVplay Exception :[' +e.code + '] ' + e.message);
    		}

    		*/		

		}, 

		cambiarSimpleImgPantallaFull: function(url, eventos){

			var op = Master.setOptionEsquema(1);

				Master.renderStruct(op, function(){
					
					var view = [{
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
		    	        	}];		        	
		        	Master.renderPage(view);
				});


		}, 
		
		IrTutorialHora: function(fc){				
			Master.lastUpdate = fc;
			
			setTimeout(function(){
				
				var op = Master.setOptionEsquema(1);
				Master.renderStruct(op, function(){
					
					var view = {
		    	        	showInfoBar: false, 
		    	        	EventRemote: {
		    	        				"ENTER" : function(){
		    	        					// instancia
		    	        					Master.Next();
		    	        				},
		    	        				"DOWN": function(){		    	        					
		    	        					alert($("body").html());		    	        					
		    	        				}
		    	        	},	    				
		    				slide: {
		    	        		esquemaTipo: 1,
		    	        		"autochange": false,	    	        		
		    	        		duracion: 0,
		    	        		seccion: [
		    	    						{ 
		    	    						posicion: 1,
		    	    						encabezado: "Ecabezado",
		    	    						contenidoTipo: 1,
		    	    						bgColor: "#000",
		    	    						modulo: "jquery",
		    	    						contenido: [ {
		    											representacionTipo: 3,
		    											data: "<img src='template/img/ConfiHora01.png' style='max-height: 715px;  align-items: center;'>"	    	    						
		    	    								}, 
		    	    								{
		    											representacionTipo: 3,
		    											data: "<img src='template/img/ConfiHora02.png' style='max-height: 715px;  align-items: center;'>"	    	    						
		    	    								}		    	    								
		    	    								]				
		    	    						}				
		    	    					]
		    	    				}	        			
		    	        	};
		        	alert("LLego Aqui sin Error. SIP");
		        	Master.renderPage(view);
				});
	        	
				
			}, 3000);
			
				
        // 	var div = '<div style="background-color: #000; width: 100%; height: 100%; color: white;"><img>  </div>';
	        	
		},
		
		confirmDateTime: function(informat){
			// Preguntar via ws la hora al Servidor.
			// TODO: EL margen de holgura de Diferencia de hora entre el servidor y el cliente es de 2 minutos.
			// Formaro en Date JS.
			
			var arrF = informat.split(",");
			alert("Divicion de la Fecha para istancia Servidor");
			
			
			var ServerFechaHm = new Date(parseInt(arrF[0]), (parseInt(arrF[1])-1), parseInt(arrF[2]), parseInt(arrF[3]), parseInt(arrF[4]), parseInt(arrF[5]) );
			var LocalFechaHm = new Date(); 
			
			// diferencia en Segundos. 			
			var diff = (ServerFechaHm-LocalFechaHm)/1000;
			
			Master.cTime = ServerFechaHm;						 
			
			if( parseInt(diff) > 120 || parseInt(diff) < -120 ){
				
			//	Master.CorrerRelojLocal();
				
				
				alert("============================="); 
				alert("Tiene Horarios diferentes:  " + LocalFechaHm.toDateString() );
				
				Master.IrTutorialHora(ServerFechaHm); 
				
				 

				
			} else {				
				alert("EL horario Cliente-servidor esta Sincronizado OK");
				
			}
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
		}, 
		
		descargarImagenes: function(){
			// Descarga todas las imagenes a Utilizar este dia
		},
		
		descargarHtml: function(){
			
		}, 
		
		initt: function(){
			 widgetAPI.sendReadyEvent();			
		     document.getElementById("anchor_main").focus();
		     if(instancia == null || instancia === undefined ){			    	
		    	 instancia = new MasterTV();		    	  
		     }

		     Master.showWelcomePages();
		     Master.inittVideo(); 
		     alert("Cargado pugin de video"); 

		     
		     
		},
		
		reconectar : function(){			
			if(instancia == null || instancia === undefined ){			    	
		    	 instancia = new MasterTV();		    	  
		     }			     
		    log("Intentando reconectar con el servidor.", "Reintentar", 0); 	
			instancia.conectar(true);			
		},
		
		actualizarProgramacion: function(prog){			
			instancia.ObtenerPrograma();			
		},
		
		slideNow: function(){
			
			var programa = localStorage.getItem("programaTV");			
			// programa.Bloques[0].data[0].slides.esquemaTipo
			opt = Master.setOptionEsquema(programa.Bloques[0].data[0].slide.esquemaTipo);			
			setTimeout(function(){				
				Master.renderStruct(opt, function(){
					var listt = localStorage.getItem("programaTV");					
					list = listt.Bloques[0].data[0].slide.seccion[0].contenido;
					
	            	$("#sc-full").html(lista[rr]);            	
	            	rr++;            	
	            	if(rr >= lista.length){
	            		rr = 0; 
	            	}
	        	});
        	}, 4000);
			// forEach(function(item, index, array){			// Cuando te llegue hasta el Alma});
			
			},	
		
		ConvertRemoteServerControl: function(btn){			
			var code = 0;			
			switch (btn) {
			case "LEFT":
				code = sf.key.LEFT;				 
				break;
			case "ENTER":			
				code =  sf.key.ENTER;			
			case "RIGTH":			
				code = sf.key.RIGHT;
				break;
			case "UP":
				code = sf.key.UP;
			break;
			case "DOWN":
				code = sf.key.DOWN;
				break;		
			case "MENU":								
				code = 262;				
			case "EXIT":
				code = sf.key.EXIT;				
			default:
				break;
			}
			return code;
		},
		

		setFormatTimerPerPrograma: function(serverDatetime){
			// set timer 
			var arrF = serverDatetime.split(",");			
			var fechaHoy = new Date(parseInt(arrF[0]), (parseInt(arrF[1])-1), parseInt(arrF[2]), parseInt(arrF[3]), parseInt(arrF[4]), parseInt(arrF[5]) );			
			var timeNow = parseInt(arrF[3])+ ":" +  parseInt(arrF[4])":"parseInt(arrF[5]); 


			var a_obj = JSON.parse(localStorage.getItem("programaTV")); 

			// Aqui Es Armar el Objeto en el Arreglo de la Programacion.
			var arregloPrograma = {}; 

			$


/*				
			var op = Master.setOptionEsquema(1);
				Master.renderStruct(op, function(){	

				
					var view = {
		    	        	showInfoBar: false, 
		    	        	EventRemote: {
		    	        				"ENTER" : function(){
		    	        					// instancia
		    	        					Master.Next();
		    	        				},
		    	        				"DOWN": function(){		    	        					
		    	        					alert($("body").html());		    	        					
		    	        				}
		    	        	},	    				
		    				slide: {
		    	        		esquemaTipo: 1,
		    	        		"autochange": false,	    	        		
		    	        		duracion: 0,
		    	        		seccion: [
		    	    						{ 
		    	    						posicion: 1,
		    	    						encabezado: "Ecabezado",
		    	    						contenidoTipo: 1,
		    	    						bgColor: "#000",
		    	    						modulo: "jquery",
		    	    						contenido: [ {
		    											representacionTipo: 3,
		    											data: "<img src='template/img/ConfiHora01.png' style='max-height: 715px;  align-items: center;'>"	    	    						
		    	    								}, 
		    	    								{
		    											representacionTipo: 3,
		    											data: "<img src='template/img/ConfiHora02.png' style='max-height: 715px;  align-items: center;'>"	    	    						
		    	    								}		    	    								
		    	    								]				
		    	    						}				
		    	    					]
		    	    				}	        			
		    	        	};

		    	        	{"26":{"BloqueID":"26", "HoraInicioBloque":"07:00:40", "HoraFinBloque":"08:00:50", "DuracionBloque":"01:00:10",
"DuracionBloqueSegundos":"3610", "Contenidos" */ 


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


			alert("Configuracion de Tiempo"); 
			alert(serverDatetime); 
			// alert(typeof aa);

			// alert(aa);  

// 			Master.setFormatTimerPerPrograma(serverDatetime);


			// Recorrer para encontrar 

		}, 


   	compararHoras: function(sHora1, sHora2) {
    
    var arHora1 = sHora1.split(":");
    var arHora2 = sHora2.split(":");
    
    // Obtener horas y minutos (hora 1)
    var hh1 = parseInt(arHora1[0],10);
    var mm1 = parseInt(arHora1[1],10);

    // Obtener horas y minutos (hora 2)
    var hh2 = parseInt(arHora2[0],10);
    var mm2 = parseInt(arHora2[1],10);

    // Comparar
    if (hh1<hh2 || (hh1==hh2 && mm1<mm2))
        return true; //  "sHora1 MENOR sHora2";
    else if (hh1>hh2 || (hh1==hh2 && mm1>mm2))
         return false; // "sHora1 MAYOR sHora2";
    else 
        return true; //"sHora1 IGUAL sHora2";
	}, 
		
		receptorWs: function(data){			

			alert("Aqui Llego. Receptor");
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
						alert("Revisara Si es Necesario la Actualizacion.");
						var prog = localStorage.getItem("programaTV");

						alert("Esto es ==" + typeof prog ); 

						alert("Program dice = " + prog ); 

						if(prog == null || typeof prog == "undefined" || typeof prog == "string" ){
								prog = {}; 
						}


					for (var i in data ) {
						alert(i + "===> " + data[i]); 
					}

					alert(data.fechaActual); 
					alert("Fecha Actualizada:-::: ^^"); 

					if(!(data.fechaActual in prog) ){
						localStorage.setItem("programaTV", null); 
						// Enviamos el Post para cargar la Informacion 
						instancia.ObtenerPrograma(data.fechaActual, data.server, data.fecha); 

							log(data.Msg); 
						alert("En espera de la Programacion"); 

						return true; 

					} else {

						alert("Ya he Configurado Incorrectamente el programaTV...."); 
					}

					alert("El contenido ya ha sido actualizado.");
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

				/*
			} else {
				alert("Se conecto");							
				// ACTIVAR LA OPCION DEBE SIEMPRE SER REVISADA.
				if(data.accion == "ACTIVAR"){
					alert(JSON.stringify(data));					
					Master.confirmDateTime(data.fecha);					
				}
				*/			
					
					/*
			}	 else {
				alert("La pregunta del Mac Adrres esta Incorrecta"); 
				alert(data.macAdrees + ": " + data.accion);
			}		
			*/

		}		
};



Master.setOptionEsquema = function (esquema){
	 var id = "#";
	 var option = {css: "bas.css", url: "template/base.html"};	 
	 
	switch(esquema){	
	case -1:		
		option.url = "template/inicio.html"; 
		option.divId = undefined;
		option.css = "inicio_01.css";
		break;		
	
	case 0:		
		option.url = "template/inicio.html"; 
		option.divId = undefined;
		option.css = "inicio_02.css";
		break;		
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


Master.renderStruct = function(option, callfunctionBack){
	//  { c s s : "", u r l: "", divId: ""}	
	Master.setTheme(option.css);
	Master.setPage(option, callfunctionBack); 
	// 
};

Master.actualizarPrograma = function(){
	//	Busca la Configuraciones de la programacion del Dia de Hoy 
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
	this.app_info = {server: "", version: "", serverRequest: ""};	
	this.ManagerPages = {
			showInfoBar: false,
			EventRemote: {ENTER: function(){ alert("Enter");  alert($("body").html() );  }, GREEN: function(){ alert("Verde"); } },
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


// return true; 

	
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

MasterTV.prototype.ObtenerPrograma= function(fecha, servicio, fechaServidor){
	alert("Actualizar"); 
	// this.app_info.serverRequest = servicio; 
	alert("ejecute: ObtenerPrograma");
	localStorage.setItem("server", servicio); 

	var alt = {};
	var url = servicio + "&Mac=" + macTV;	

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
	var mac = networkPlugin.GetMAC(0) || networkPlugin.GetMAC(1);
	macTV = mac;  
	
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

	//Add text to log
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