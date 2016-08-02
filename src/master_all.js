alert("Loaded master_all.js"); 
/** Mensajes y cintas de informacion **/

// EL aplicativo Completo. MAster controlador
var widgetAPI = {};
var tvKey = {};
var pluginAPI = {};
var fileSystemObj = {};


alert("Cargaron COnfiguraciones Loaded master_all.js"); 

ConfigSetting = {
	ws: ['10.234.133.76:9300'], // ['10.234.51.99:9300'], // 10.234.133.76
	configFiles: ["serverWSUrl.data", "version.data", "allsource.data", "serverRequest.data" ], //{ 0 = serverURL, 1 = version, 2 = all source }
	serverApp: 'localhost:7777/GestionVista/'
}; 

// EL aplicativo Completo. MAster controlador
var copy = {}; 
var fileObj = {};  
var instancia = null;
var configFiles = ConfigSetting.configFiles; 
var serverUpdatePath = null; 
var macTV;

var mTimer = {
	hasLider: true, 
	hasRuntime: false,
	cIndexC: 0, // Contenido.
	cIndexS: 0, // Slider.
	TransicionFin: "", 
	contenido: 0, 
	BloqueID: 0
};

var pptMaster = {
	cuerpo: [], 
	slide: 0, 
	totalSlide: 0, 
	hasFinish: false,
	tiempo: 0, 
	hasKeyControlActive: false, 
	getPPT: function($index){

		if($index in pptMaster.cuerpo){
			pptMaster.cuerpo[$index];
			$("#sc-" + 1).html( "<div class='img'>" + pptMaster.cuerpo[$index].outerHTML + "</div>");
		}
	}, 

	next: function() {
		pptMaster.slide++; 

		if((pptMaster.slide) > pptMaster.totalSlide){
			pptMaster.slide = pptMaster.totalSlide; 			
			Msg.warning("Fin Slide", "Final de la Presentación"); 			
		}
		pptMaster.getPPT((pptMaster.slide-1));
	}, 

	back: function(){
		pptMaster.slide--; 

		if((pptMaster.slide) > pptMaster.totalSlide){
			pptMaster.slide = pptMaster.totalSlide; 			
			Msg.warning("Fin Slide", "Final de la Presentación"); 			
		}

		if(pptMaster.slide < 1){
			pptMaster.slide = 1; 	
			Msg.warning("Primer Slide", "Inicio de la presentación"); 
		}
		pptMaster.getPPT((pptMaster.slide-1));
	}, 

	keyControlPPT: function(keyCode){

		switch(keyCode)
			{
				case tvKey.KEY_RETURN:
				case tvKey.KEY_PANEL_RETURN:
					alert("RETURN");
					 widgetAPI.sendReturnEvent();
					break;
				case tvKey.KEY_LEFT:
					alert("LEFT");					
					pptMaster.back(); 
					break;

				case tvKey.KEY_RIGHT:
					alert("RIGHT");
					pptMaster.next(); 					
					break;
				case tvKey.KEY_UP:
					alert("UP");
					break;
				case tvKey.KEY_DOWN:
					alert("DOWN");
					break;
				case tvKey.KEY_ENTER:
				case tvKey.KEY_PANEL_ENTER:

					alert("ENTER");
					break;
				default:
				alert("Unhandled key");
			break;
			}
	}
}; 


Master = {
	html: {},	
	curContent: [],
	excelcollection: {}, 	
	pptCollection: {},
	dTransAuto: {},
	dTransManual: {}, 
	fn_onShow: [], 
	initt: function(){
		alert("exec Master.innit()"); 
		Fondo.setInicioPage(function(){	

			alert("Set de Home Inicio"); 

			widgetAPI = new Common.API.Widget();
			tvKey = new Common.API.TVKeyValue();
			pluginAPI = new Common.API.Plugin();
			fileSystemObj = new FileSystem();		

			alert("Set vars Inicio"); 			

			widgetAPI.sendReadyEvent();
			alert("Finish Ready. "); 

			Master.setSmartTemplate(function(){

				alert("setSmart TV Templates for Userrr ************** "); 
				alert("Antes de Anchor"); 				
			//	Msg.log("Espere un Momento"); 
				alert("Despues de Msg"); 

				alert("Antes de Anchor"); 
				if(instancia == null || instancia === undefined ){			    	
					alert("Pregntando por la INstancia"); 
			    	  instancia = new MasterTV();		    	 
			    }
			}); 
		}); 		
	},

	reconectar : function(){	


		     
		},

	KeyDown: function(){				
		var keyCode = event.keyCode;	
		// Lenctor de Codigo desde Dispositivo. Condicionantes
		if(pptMaster.active){
			pptMaster.keyControlPPT(keyCode);

			if(mTimer.hasLider){
			// Es un Dispositivo Lider de Grupo 
				var obj = {
					macAdrees: macTV,			
					Tipo: "TV",						
					accion: "CONTROLLIDER",
					"keyCode": keyCode, 
					"BloqueID": mTimer.BloqueID, 
					"cIndexC": mTimer.cIndexC, 
					"cIndexS": mTimer.cIndexS, 
					"pptKey": pptMaster.slide
				};

				alert("Aqui Estas: "); 
				alert( typeof instancia); 
				alert("CUBO RUBY"); 
				alert( typeof instancia.conn); 

			alert("type Servidor"); 

				alert (typeof instancia.conn.Server); 
				 instancia.conn.Server.send("message", JSON.stringify(obj) );		
			}			
		}


alert("El Codigo Mostrado o pulsado ES:"); 
		alert(keyCode); 		

		
			    if(instancia == null || instancia === undefined ){			    	
			    	instancia = new MasterTV();			    	
			    	instancia.handleKeyDown(keyCode);
			    }else{			    	
			    	instancia.handleKeyDown(keyCode);			        
			    }
		},

		receptorWs: function(data){				
				// ["ACTUALIZAR-APP", "ACTIVAR", "TWEETS", "CAST", "PROGRAMA" "CONTROL" ]
					localStorage.setItem("programaTV", null); 

				switch (data.accion) {
					case "ACTUALIZAR-APP":
						// SOLICITUD DE ACTUALIZACION DE APP DESDE EL SERVIDOR.	Cambio de IP cambio de Servidor a las Apliaciones.						
						if("server" in data){
								serverUpdatePath = data.server; 
								instancia.setFileConfig(); 
						}
						break;
					case "ACTUALIZAR-PROG":

						localStorage.setItem("programaTV", null);
						mTimer.hasRuntime = false;
						Master.cambiarBloque();
					break; 
					case "NOTIFICAR":	

					alert("NOtificar desde la Respuesta del Servidor en el Servicio SIPP"); 							

						var prog = JSON.parse(localStorage.getItem("programaTV"));

						alert(prog); 
						alert("**************** Progrmacion ***********************");  // Hay que Hacerle Parser.
						if(prog == null || typeof prog == "undefined" || typeof prog == "string" ){
								prog = {}; 
						}

						localStorage.setItem("base_url", data.base_url); 
						localStorage.setItem("getFullBloque", data.server); 
						localStorage.setItem("fechaActual_APP", data.fechaActual); 
						localStorage.setItem("fecha_APP", data.fecha); 					



					if(!(data.fechaActual in prog) ){
						localStorage.setItem("programaTV", null); 	
						alert("Obtener Programa.... "); 
						Msg.log(data.Msg); 

						Master.ObtenerPrograma(data.fechaActual, data.server, data.fecha); 
						return true; 
					} else {

						Msg.log("La progrmacion esta actualizada."); 

						hasProBloq = false; 

Msg.log("Analizando la Apps en la Programacion del Contenido en el localStoreg**************"); 

						for (var i in prog[data.fechaActual].programa) {
							if(prog[data.fechaActual].programa.hasOwnProperty(i)){
								prog[data.fechaActual].programa
							alert(i + "==> " + prog[data.fechaActual].programa[i])
							hasProBloq = true; 
							}
							
						}

					//	alert("Despues del blucle"); 
				//		alert(hasProBloq); 

						alert("Lo detendre Jusnto Aqui */***********************"); 
			//			return true;


						if( prog[data.fechaActual].programa )

						mTimer.hasRuntime = true; 
						Fondo.setBodyPage();						
						Master.getBloqueIDAndTimer(); 


					}

					// console.log("El contenido ya ha sido actualizado.");					
					// Master.IrVideoTimeOut();

					break;

					case "ACTIVAR":
						// Devolver la Hora en que se debe configurar.												
					//	if(data.fecha != null || typeof data.fecha !== 'undefined' )
						//	Master.confirmDateTime(data.fecha.toString()); 
					//	Master.IrVideoTimeOut();						
						
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
					alert(data.accion); 			
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
		    											data: " <div class='img'><img src='"+ url + "' style='max-height: 715px;  align-items: center;'> </div>"	    	    						
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
		//	url = url.replace("10.234.133.76", "localhost");

			http.get(url, function(res){

				if(res.IsOk){
					// Estuvo OK el asunto
					alt[fecha] = {programa: res.programa, contenido: [] }; 
					localStorage.setItem("programaTV", JSON.stringify(alt) );
						Msg.log("Configurando programa."); 

						mTimer.hasRuntime = true; 
						Fondo.setBodyPage();
						alert("Envio de la Aplicacion"); 
						Master.getBloqueIDAndTimer(); 
						alert("Despues del BLoqueIDAndTImer"); 

					// Master.cambiarSimpleImgPantallaFull("template/img/actualizando.png", {}); 
					// Master.setTimerPerPrograma(fechaServidor);
					// Master.setFormatTimerPerPrograma(fechaServidor);
					alert("Aqui Iran Los BLoques"); 
				} else {
				//	alert("Sin Asignacion"); 
				Fondo.setBodyPage(function(){
					alert("Set Body Pages off recorrer programa"); 
					Master.recorrerProgramaSinAsingacion(); 

				}); 
					Msg.log(res.Msg);					
					

				}
	}, "JSON");	
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


setSmartTemplate: function(callBack){	
	Master.html = {};
	http.get("template/esquemas.html", function( data ) {
		// Carga del Html puro; 		
		Master.html = data; 
		// $("#applicationWrapper").html(data);				
		callBack(); 
	});

}, 

setLocalCss: function(css){	
	http.get("template/styles/" + css +".css", function( data ) {		
			$("#cssApplicationWrapper").html("<style>" + data + "</style>");		
	});
}, 

setLocalHtml: function(html, css){
	if(typeof css !== "undefined" ){
		// EL template de Espera Pantalla de Bloqueo.
		http.get("template/styles/" + css + ".css", function( data ) {
			$("#cssApplicationWrapper").html("<style>" + data + "</style>");					
		});
	}			

	http.get("template/" + html + ".html", function( data ) {
			$("#applicationWrapper").html(data);					
	});
}, 

recorrerProgramaOff: function(){

	var defaultPropertities = {
	"BloqueID":"0",
	"DuracionBloque":"00:03:30",
	"DuracionBloqueSec":"182",
	"listaContenido":
	{"TemporalContenido":
		{"Guid":"8D054484-6682-984F-D906-BD051334641E",
		"Duracion":"00:17:10",
		"Descripcion":"Recorrido Sin Asignar",
		"Orden":"1",
		"SincroGrupo": false,
		"slides":
		{
		"0":
			{"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"0",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"30",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"1",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"template/img/block01.png" // blueBack
					}
				]
				}, 

			"2": {"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"2",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"10",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"6",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"resource/intro_finalCND.mp4" // blueBack
					}
				]
				}, 

				"4": {"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"4",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"10",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"6",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"resource/Preludios.mp4" // blueBack
					}
				]
				}, 

				"3":
			{"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"3",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"60",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"1",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"template/img/block01.png" // blueBack
					}
				]
				}
			}
		}
	}
};
	Master.setPantallaPropiedad(defaultPropertities); 
	Master.renderBloque(); 


}, 

recorrerProgramaSinAsingacion: function(){	
	// Paso 1. setTheme	
	var defaultPropertities = {
	"BloqueID":"0",
	"DuracionBloque":"00:03:30",
	"DuracionBloqueSec":"99",
	"listaContenido":
	{"TemporalContenido":
		{"Guid":"8D054484-6682-984F-D906-BD051334641E",
		"Duracion":"00:17:10",
		"Descripcion":"Recorrido Sin Asignar",
		"Orden":"1",
		"SincroGrupo": false,
		"slides":
		{
		"0":
			{"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"0",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"20",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"1",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"template/img/contactar.png" // blueBack
					}
				]
				}, 

			"2": {"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"2",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"10",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"6",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"resource/intro_finalCND.mp4" // blueBack
					}
				]
				}, 

				"4": {"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"4",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"10",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"6",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"resource/Preludios.mp4" // blueBack
					}
				]
				},

			"3":
			{"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"3",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"10",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"1",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"template/img/block01.png" // blueBack
					}
				]
				} 
			}
		}
	}
};
	Master.setPantallaPropiedad(defaultPropertities); 
	Master.renderBloque(); 
},

renderBloque: function(){
	var totalC = Master.curContent.length; 	
	if( (mTimer.cIndexC+1) > totalC){
		mTimer.cIndexC = 0; 
	}
	var totalS = Master.curContent[mTimer.cIndexC].slides.length
	if( (mTimer.cIndexS+1) > totalS ){
		mTimer.cIndexS = 0; 
	}

	alert("Antes del Css de la aplicacion.   renderBloque"); 
	$("#cssApplicationWrapper").html(""); 

	Master.renderSliderPage(Master.curContent[mTimer.cIndexC].slides[mTimer.cIndexS], totalS); 
}, 

renderSliderPage: function(config, totalS){
	// TotalS es la cantidad Maxima de Slider
	if(totalS == mTimer.cIndexS){
		mTimer.cIndexC++; 
	}

	if(mTimer.TransicionFin !== ""){		
		Master.applyTransicion(mTimer.TransicionFin, "out"); 
	} else {
		Master.applyTransicion("", "out"); 
	}

	var esquemaOculto = Master.getOptionEsquema(config.EsquemaTipo); 
	esquemaOculto.show(); 	

	$("#applicationWrapper").html( esquemaOculto.html() ); 

	// Insertamos los contenidos en cada cuadro.
	mTimer.TransicionTipoIni = config.TransicionTipoIni; 

	Master.fn_onShow = []; 
	Master.dTransAuto = {}; 

//	Master.setLocalCss("white"); 

	hasNormal = Master.generateContentByFuenteTipoSimple(config); 



	mTimer.cIndexS++; 	
	mTimer.TransicionFin = config.TransicionTipoFin; 

	if(hasNormal !== false){
		mTimer.contenido = setTimeout(function(){
			Master.renderBloque(); 
		}, parseInt(config.DuracionPageSec) * 1000 ); 		
	}
	// 


}, 

generateContentByFuenteTipoSimple: function(config){
	// Si Fuente tipo es 
		var hasRequest = false; 
		var requestList = []; 	
		var hasVideo = false; 

		pptMaster.active = false; 	
		$("#footer-gvapp").hide(); 

	config.secciones.forEach(function(item, indx) {		
		switch(parseInt(item.FuenteTipo)){
		case 1: // Imagen 		
		$("#sc-" + item.Posicion).html('<div class="img"> <img src="'+ item.Url + '" class=""> </div>'); 
		break; 
		case 2: // Texto.
		$("#sc-" + item.Posicion).html(""); 
		$("#sc-" + item.Posicion).html("<p>" +  item.Url  +"</p>");
		break; 
		case 3: // Bischart.

		// Fondo.setTremeBg("paper"); 
		Master.setLocalCss("white"); 	

		if(parseInt(item.EsManual) == 0) {

			hasRequest= true; 
			requestList.push({Url: item.Url, Type:"JSON", methodCallRequest: function(res){

				alert("SOLO YEGARA AQUI SIIIIIIIIII.."); 

					var responseJSON = res; 					
                    Master.dTransAuto[item.Posicion] ={dt: datatransformer.new( responseJSON.data, responseJSON.config), visuals: responseJSON.config.visuals};
                	Master.fn_onShow.push({name: "Bischart", type: "auto"});
				

                    
                } }); 
				
		} else {

			alert("Es Manual*****");



			if(item.Url in Master.dTransManual){				
				Master.fn_onShow.push({name: "Bischart", type: "manual", Posicion: item.Posicion, url: item.Url});				
			} else {

				requestList.push({Url: item.Url, Type:"JSON",  methodCallRequest: function(res){

                    var responseJSON = res; 
                    Master.dTransManual[item.Url] = {dt: datatransformer.new( responseJSON.data, responseJSON.config), visuals: responseJSON.config.visuals};                    
                	Master.fn_onShow.push({name: "Bischart", type: "manual", Posicion: item.Posicion, Url: item.Url});                	
                } }); 
			}
		}
		break; 
		case 4: // OfficeVIewExcel 
			Master.setLocalCss("white"); 		

			
			alert("Este es la Office EXcel"); 
			alert("URL: "+ item.Url); 

			if(item.Url in Master.excelcollection){
				$("#sc-" + item.Posicion).html("<div class='excel-wrapp'>" + Master.excelcollection[item.Url] + "</div>"); 
				
			} else {
				requestList.push({Url: item.Url, Type:"get", methodCallRequest: function(data){					
					Master.excelcollection[item.Url] =  data
					$("#sc-" + item.Posicion ).html("<div class='excel-wrapp'>" + data + "</div>"); 
				} }); 
			}

		break; 
		case 5: // OfficeVIewPowerPoint.				
		// Falta la Manipulacion del Power point.
		// pptCollection.

			pptMaster.active = true; 

			Master.setLocalCss("black"); 

		$("#footer-gvapp").show(); 


		if(item.Url in Master.pptCollection){

		// pptCollection.
			pptMaster.cuerpo = 	$(Master.pptCollection[item.Url]).find("img"); 
			pptMaster.totalSlide = pptMaster.cuerpo.length; 

			alert("**************************** PPT MASTER*************************"); 

		// Este es el total de Slider de la aplicacion Ahora.
alert("IMGES ---------------------------------------- IMAGES"); 
		alert( pptMaster.cuerpo[0] ); 
alert("IMGES ---------------------------------------- IMAGES"); 

			$("#sc-" + item.Posicion).html( "<div class='img'>" + pptMaster.cuerpo[0].outerHTML + "</div>");		
			Msg.warning("presentacion", "PPT sliderShow. Tome el control remoto para Navegar los " + pptMaster.cuerpo.length + " Sliders"); 
				
		} else {
				requestList.push({Url: item.Url, Type:"get", methodCallRequest: function(data){					
					Master.pptCollection[item.Url] = data;					

					pptMaster.cuerpo = $(Master.pptCollection[item.Url]).find("img"); 
					pptMaster.totalSlide = pptMaster.cuerpo.length;

					$("#sc-" + item.Posicion).html( "<div class='img'>" + pptMaster.cuerpo[0].outerHTML + "</div>");
					Msg.warning("presentacion", "PPT sliderShow. Tome el control remoto para Navegar los " + pptMaster.cuerpo.length + " Sliders."); 
					pptMaster.slide = 1; 
				} }); 
			}


		// Inicializamos 
		 


		break; 

		case 6: // video.
	 	 Fondo.setFullMp4Video(item.Url, function(){ Master.renderBloque(); }); 		
		

		hasVideo = true;  
		 
		break; 
		case 7: // Simple HTML Renderizado.
		requestList.push({Url: item.Url, Type:"get", methodCallRequest: function(data){					
			

				$("#sc-" + item.Posicion ).html("<div class='excel-wrapp'>" + data + "</div>"); 
			

				} 
			}); 
			
					

		// 	return "htmlVIdeo"; 
		break;

		default:		
		break; 
	}

	}); 

if(hasVideo){	
	return false; 
}


	if(requestList.length == 0){
		Master.applyTransicion(mTimer.TransicionTipoIni, "in"); 
			return 1; 		
	} else {
		Master.nextRequest(requestList, 0, requestList.length); 
	}
}, 

nextRequest: function(arreglo, index, len){	

	var i = index; 

	if(index >= len){
	

		Master.applyTransicion(mTimer.TransicionTipoIni, "in"); 
		alert("Entrada Apply"); 
		return true; 
	}
	alert("Siguiente Paso renderizar"); 
	if(arreglo[i].Type == "get"){
		http.get( arreglo[i].Url, function(dta) {
		 arreglo[i].methodCallRequest(dta); 
		 i++; 
		 // log("Ha regresado el Get" + dta ); 

		 Master.nextRequest(arreglo, i, len); 
		}, "html" ); 

	} 

	if(arreglo[index].Type == "JSON"){
		alert("Siguiente Paso Get del JSON"); 
		alert(arreglo[i].Url); 
	//	Msg.log(arreglo[i].Url); 
		http.get( arreglo[i].Url, function(dta) { 

			alert("Aqui Quiere LLegar Pero"); 
			alert(dta); 		
			alert("****************************"); 			
			arreglo[i].methodCallRequest(dta);
			i++;
			Master.nextRequest(arreglo, i, len);  
			// console.log("Escenario #1"); 			
		}, "json"); 
	} 







}, 

generateContentByFuenteTipoRequest: function(){
	// Si Fuente tipo es 
	

	config.secciones.forEach(function(item, indx) {

								
	}); 

	

	if((parseInt(itx)+1) == numRow){
		callBack(); 
	}

	return ""; 
}, 

applyTransicion: function(transicionTipo, modo ){

	switch(modo){
		case "in":

		$("#applicationWrapper").show("fold");


		// Recorrido Al momento de Visualizar.

	var arrBichar = Master.fn_onShow.filter(function(d){			
				return d.name === "Bischart"; 
		});

		arrOtr = Master.fn_onShow.filter(function(d){			
				return d.name !== "Bischart"; 
		});



var hastAutoMatic = false; 
		arrBichar.forEach(function(it, k){
			if (it.type == "manual"){				
				Master.dTransAuto[it].visuals[0].visualOptions["renderAsImage"] = true;
				Master.dTransManual[it.Url].dt.generateVisual(Master.dTransManual[it.Url].visuals[0].visualType, Master.dTransAuto[it].visuals[0].visualOptions,
					'sc-' + it.Posicion ).render();
			} else {	
			hastAutoMatic = true;				
			}
		});



		if(hastAutoMatic){
			for(var it in Master.dTransAuto){
				if(Master.dTransAuto.hasOwnProperty(it)){					
				//	// console.log(Master.dTransAuto[it]); 
				alert("@#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@"); 
					alert("NO Existe fold"); 
					alert(Master.dTransAuto[it].dt);
					alert( JSON.stringify(Master.dTransAuto[it].visuals[0].visualType)); 


										alert("/*********************/"); 
										alert(math.eval("b * a", {a: 2, b:4})); 
										alert("/*******************/"); 

										alert(typeof Master.dTransAuto[it].dt.generateVisual); 

						Master.dTransAuto[it].visuals[0].visualOptions["renderAsImage"] = true;
						var hh = Master.dTransAuto[it].dt.generateVisual(Master.dTransAuto[it].visuals[0].visualType, Master.dTransAuto[it].visuals[0].visualOptions,
					'sc-' + it );  // .render();
						hh.render(); 			

					alert("Paso el Renderizado. "); 
				}
			}

			

		}

		return 0; 

		switch(Master.getTransicionTipo(transicionTipo)){
			case "":

			$("#applicationWrapper").fadeIn(1500);
			break;
			case "slide":

			$("#applicationWrapper").show("fold");

			break;

			case "drop":

			$("#applicationWrapper").fadeIn(3000);
			break;
		}

			break;
		case "out":

		switch(Master.getTransicionTipo(transicionTipo)){
			case  "":
				$("#applicationWrapper").hide(); 
			break;

			case  "scale":
        	options = { percent: 0 }; 
        	$("#applicationWrapper").hide("scale", options, 2000, function () { });  
        	break; 

        	default:
        		options = { percent: 0 }; 
        		$("#applicationWrapper").hide();  
        	break; 
		}

		return 0;		
    
			break;
	}

	return 0; 
}, 

getTransicionTipo: function(transicionTipo){
// # Ninguna, slide, drop, blind, scale
// '0', '1', '2', '3', '4'

	switch(parseInt(transicionTipo) ){
		case  0:
		return "";		
		break; 
		case  1:
		return "slide";
		break;
		case  2:
		return "drop";
		break;
		case  3:
		return "blind";
		break;
		case  4:
		return "scale";
		break;
	}

	return "fold"; 
	// var arregloEffect = ['slide', 'drop', 'drop', 'clip', 'slide', 'blind', 'drop', 'drop', 'scale', 'slide', 'slide'];
	// ("fold", 1000);
}, 

setPantallaPropiedad: function(propiedades, timer){
	// Propiedades del Slider show.	
	Master.curContent = [];
	var tiempo = parseInt(propiedades.DuracionBloqueSec); 
	if(typeof timer !== "undefined"){
		tiempo = timer; 	
	}

	Master.setCambioBloque(tiempo);

		for(var p in propiedades.listaContenido){
		 if(propiedades.listaContenido.hasOwnProperty(p) ){
		 	var currentProperty = {Guid: propiedades.listaContenido[p].Guid, Orden: propiedades.listaContenido[p].Orden, slides: []}; 			
			for(var q in propiedades.listaContenido[p].slides){
				if(propiedades.listaContenido[p].slides.hasOwnProperty(q)){
					currentProperty.slides.push(propiedades.listaContenido[p].slides[q]); 
				}
			}
			currentProperty.slides.sort(function(a, b){ if (parseInt(a.Posicion) > parseInt(b.Posicion) ) return 1; if ( parseInt(a.Posicion) < parseInt(b.Posicion) ) return -1; return 0; }); 
			Master.curContent.push(currentProperty);
		 }
		}
		Master.curContent.sort(function(a, b){ if (parseInt(a.Orden) > parseInt(b.Orden) ) return 1; if ( parseInt(a.Orden) < parseInt(b.Orden) ) return -1; return 0; });
}, 

setCambioBloque: function(tmr){  // tmr: tiempo en segundos
	// Consular al Servidor cual es el Bloque
	// console.log("El bloque cambiara en: " +  tmr); 

	tmr = parseInt(tmr)*1000;
	mTimer.bloque = setTimeout(function(){
		Master.cambiarBloque(); 
	}, tmr );
}, 


getBloqueIDAndTimer: function(callback){

	alert("Los Locales son: " + macTV); 

	alert(mTimer.contenido);

	var base_urlT = localStorage.getItem("base_url"); 
	var getFullBloque = localStorage.getItem("getFullBloque"); 
	var fechaActual_APP = localStorage.getItem("fechaActual_APP"); 
	var fecha_APP = localStorage.getItem("fecha_APP"); 

	alert("Buscando el Contenido:: " +base_urlT +"_" + macTV); 

		http.get(base_urlT + "Contenido/httpObtenerIDBloqueNow?Mac=" + macTV, function(res){
			// La Progranacion Que nos corresponde es.
			alert(res.IsOk); 
			if(res.IsOk){
				var dt = res.data; 
				if(dt !== false){					

					var rt = Master.getSelectedBloque(dt.data.BloqueID, fechaActual_APP); 
					mTimer.BloqueID = dt.data.BloqueID; 


					if(rt !== false){

						alert("El bloque cambiara en "+ dt.data.TiempoRestante + "Segundos SIp"); 
						if(typeof dt.data.TiempoRestante !== "undefined" ){
							rt.DuracionBloqueSec = dt.data.TiempoRestante; 	
						}						

						Master.setPantallaPropiedad(rt); 						
						Master.renderBloque(); 

						if(typeof callback == "function"){
							callback(); 
						}
						return true; 
					}
				}
				// En el caso de que no encuentre Ningun bloque para recorrer.
				Master.recorrerProgramaOff(); 	

			} else {
				Fondo.setBodyPage(function(){
						alert("Set Body Pages off recorrer programa Desde el Runtime"); 
						Master.recorrerProgramaOff(); 
					}); 				

			}
			
		}, "JSON"); 

}, 

cambiarBloque: function(){
	// Consular al Servidor cual es el Bloque
	// alert("Consulta para Cambiar el Bloque. "); 

	alert("************************** CAMBIO BLOQUES ********************"); 	
	alert("************************** CAMBIO BLOQUES ********************"); 	
	alert("************************** CAMBIO BLOQUES ********************");

	alert("************************** CAMBIO BLOQUES ********************"); 	
	alert("************************** CAMBIO BLOQUES ********************"); 	
	alert("************************** CAMBIO BLOQUES ********************");

	var base_urlT = localStorage.getItem("base_url");
	var getFullBloque = localStorage.getItem("getFullBloque");
	var fechaActual_APP = localStorage.getItem("fechaActual_APP");
	var fecha_APP = localStorage.getItem("fecha_APP");

	 clearTimeout(mTimer.contenido);
	 // Fondo.setFullMp4Video("resource/Preludios.mp4", function(){
	 // }); 
	if(!mTimer.hasRuntime){
		// NO ha corrido.
		// Solicitar al Serviodor la Consultar la fecha y Hora del Servidor.
		Master.ObtenerPrograma(fechaActual_APP, getFullBloque, fecha_APP); 			
	} else {
		// Obtener La EL Bloque Correspondiente.
		Master.getBloqueIDAndTimer(); 
	}
}, 

getSelectedBloque: function($idBloque, dt){

	alert("Estoamos Aqui Para ver Que pasa"); 
	alert($idBloque); 
	alert(dt); 

	alert("------yyyyy------------------****"); 

	var a_obj = JSON.parse(localStorage.getItem("programaTV")); 

	if(dt in a_obj){
		var arregloPrograma = {}; 
		if($idBloque in a_obj[dt].programa){
			return a_obj[dt].programa[$idBloque]; 
			// alert("Encontro el Asunto sii"); 
		} 
	}
		return false; 
}, 

getOptionEsquema: function (esquema){
var retorno = {};

	switch (parseInt(esquema)){	
	case -1:		
		break;				
	case 1:  

			id = "#aplication-01";
			retorno = $(Master.html).find(id);
			retorno.hide();

		break; 			
		
		case 2:
			id = "#aplication-02";
			retorno = $(Master.html).find(id);
			retorno.hide();
			
		break;

		case 3:
			id = "#aplication-03";
			retorno = $(Master.html).find(id);
			retorno.hide();
		break;
	case 4:
	// <div class='cs-div_V1x2'> </div>
		id = "#aplication-04";
		retorno = $(Master.html).find(id);
		retorno.hide();		
		break;
		
	case 5:
		// 
		id = "#aplication-05";
		retorno = $(Master.html).find(id);
		retorno.hide();
		 
		break;

		case 6:
			id = "#aplication-06";
			retorno = $(Master.html).find(id);
			retorno.hide();
		break;

		case 7:
		// baseHtml = "<div class='cs-div_H1x2'>";
			id = "#aplication-07";
			retorno = $(Master.html).find(id);
			retorno.hide();
		break;

		case 8:
			id = "#aplication-08";
			retorno = $(Master.html).find(id);
			retorno.hide();
		break;

		case 9:
			id = "#aplication-09";
			retorno = $(Master.html).find(id);
			retorno.hide();
		break;
		default:			
			option.Url = "template/inicio.html"; 
			retorno = $(Master.html).find(id);
			retorno.hide();
		break;
	}
	return retorno;
},

renderStruct: function(option, callfunctionBack){	
	Master.setTheme(option.css);
	Master.setPage(option, callfunctionBack); 	
}
};
//*********************************** Final del Master controlador. **********************/

var Msg = {	
	log: function( text, titulo) {
			alert(text); 	 
		 	var til = ""; 		 	
		 	if(typeof titulo === "string"){
		 		til = titulo; 
		 	}
		 	var dtnim = new Date();
		 	var id = (dtnim.getTime()- 9539934202);

		 	var htmlgw = '<div id="divvGrowls'+ id + '"> <div class="growl growl-default growl-medium"><div class="growl-close"></div><div class="growl-title">' + til + ' </div><div class="growl-message">' + text + '</div></div> </div>'; 
		 	$("#growls").append(htmlgw); 		 			 		 

		 	setTimeout(function(){		 		
		 		$("#divvGrowls" + id ).remove(); 		 		
		 	}, 4500); 
	},

	notice: function( text, titulo) {
			alert(text); 	 
		 	var til = ""; 		 	
		 	if(typeof titulo === "string"){
		 		til = titulo; 
		 	}
		 	var dtnim = new Date();
		 	var id = (dtnim.getTime()- 9539934202);

		 	var htmlgw = '<div id="divGrowls'+ id + '"> <div class="growl growl-notice growl-medium"><div class="growl-close"></div><div class="growl-title">' + til + ' </div><div class="growl-message">' + text + '</div></div> </div>'; 
		 	$("#growls").append(htmlgw); 		 			 		 

		 	setTimeout(function(){		 		
		 		$("#divGrowls" + id ).remove(); 		 		
		 	}, 4500); 
	},

	error: function( text, titulo) {
			alert(text); 	 
		 	var til = ""; 		 	
		 	if(typeof titulo === "string"){
		 		til = titulo; 
		 	}
		 	var dtnim = new Date();
		 	var id = (dtnim.getTime()- 9539934202);

		 	var htmlgw = '<div id="divGrowls'+ id + '"> <div class="growl growl-error growl-medium"><div class="growl-close"></div><div class="growl-title">' + til + ' </div><div class="growl-message">' + text + '</div></div> </div>'; 
		 	$("#growls").append(htmlgw); 		 			 		 

		 	setTimeout(function(){		 		
		 		$("#divGrowls" + id ).remove(); 		 		
		 	}, 4500); 
	},

	warning: function( text, titulo) {
			alert(text); 	 
		 	var til = ""; 		 	
		 	if(typeof titulo === "string"){
		 		til = titulo; 
		 	}
		 	var dtnim = new Date();
		 	var id = (dtnim.getTime()- 9539934202);

		 	var htmlgw = '<div id="divGrowls'+ id + '"> <div class="growl growl-warning growl-medium"><div class="growl-close"></div><div class="growl-title">' + til + ' </div><div class="growl-message">' + text + '</div></div> </div>'; 
		 	$("#growls").append(htmlgw); 		 			 		 

		 	setTimeout(function(){		 		
		 		$("#divGrowls" + id ).remove(); 		 		
		 	}, 4500); 
	},

	textBlock: function(htmlcontent){
        $.blockUI({ 
            message: $(htmlcontent), 
            css: { top: '20%' } 
        });
        setTimeout($.unblockUI, 2000);         
	}, 

	hideMarqueeBar: function(){
		$("#marqueBar").html(""); 
	}, 

	showMaqueeFlashInfo: function(opton){
		// How to Used.	
		/*
				Msg.showMaqueeFlashInfo({modo:"flash", showCategory: false,
		 	 categoryText: "Ejemplo de Categoria",
		 	  styleCat: "background: green; color: white;",
		 	  items: ["Paso del primer mensaje Enviado desde el Servidor", 'Solo una prueba de calidad', "Tercer Mensaje de Coordinacion"]
 });  
		*/
		var categoryText = ""; 
		var htmlText = ""; 
		var fullHtmlBar = "";		
		var left = ""; 
		var right = ""; 		
		opton.items.forEach(function(itm, ind){
			if(opton.modo == "flash"){
				htmlText += "<li>" + itm + "</li>";
			} else {
				htmlText += "   |   " + itm + ""; 
			}			
		});
		if(opton.modo == "flash"){
			htmlText = "<ul class='newsticker'> " + htmlText  +  "</ul>"; 
		} else {
			alert("Paso 2"); 
			htmlText = "<marquee>" + htmlText  +  "</marquee>";
		}
		if(opton.showCategory !== false){
			categoryText = opton.categoryText; 
			var stylC = ""; 
			var styleMsg = ""; 
			if("styleCat" in opton){

				stylC = opton.styleCat; 
			}
			left = '<div style="float: left; width: 17%; height: 27px; text-align: center; padding-top: 5px; border-top: black solid 1px; border-right: black solid 2px; '+ stylC +'">' + categoryText +' </div>'; 

			if("styleMsg" in opton){
				styleMsg = opton.styleMsg; 
			}
			right = '<div style="float: left; width: 82%; height: 27px; padding-top: 5px; border-top: black solid 1px; '+ styleMsg +'">' + htmlText +' </div>'; 
		} else {
			if("styleMsg" in opton){
				styleMsg = opton.styleMsg; 
			}
			left = '<div style="float: left; width: 2%; height: 27px; text-align: center; padding-top: 5px; border-top: black solid 1px;"> </div>'; 
			right = '<div style="float: left; width: 97%; height: 27px; padding-top: 5px; border-top: black solid 1px; '+ styleMsg +'">' + htmlText +' </div>';
		}		
		fullHtmlBar = "<div class='sf-ui-keyhelp sf-ui-keyhelp-black'>" + left + right + "</div>"; 
		// Recorrido del los Itmes de Noticias
		// Combinacion de Colores.
		$("#marqueBar").html(fullHtmlBar);
		if(opton.modo == "flash"){
			$(".newsticker").newsTicker({
				row_height: 35,
				max_rows: 1,
			});
		}
	}, 
	hideMarqueFlashInfo: function(){
		$("#marqueBar").html(""); 
	}
}; 

var Fondo = {
	path: "template/img/background/",

	listaBg: ["background_texture.png", "blue_shop_thumb.jpg", 
	"Golf-Shirt-Grey.jpg", "gray38.gif", "ladrillo.jpg", "paper00.jpg",
	"parchment-background.jpg", "tile.png", "pluma.png", "ladrilloblue.jpg",
	 "blackGreen.gif", "imagesgreen.jpg", "bgtela.jpg"],

	 setBgImg: function(num){
	 	if(num >= Fondo.length){
	 		 num = 0; 		
	 	}
	 	bodyStyle = " body { background: url('" + Fondo.path + Fondo.listaBg[num] + "') repeat;	background-color: #1f1f20; }";
	 	document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 	
	 }, 

	 setTremeBg: function(theme){
	 	switch(theme){ 		 		
	 		// Dark
	 		case "tvtexture":
	 		bodyStyle = " body { background: url('" + Fondo.path + "background_texture.png') repeat;	background-color: #1f1f20; color: fff; }";
	 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 	
	 		break; 

	 		case "bluetexture":
		 		bodyStyle = " body { background: url('" + Fondo.path + "blue_shop_thumb.jpg') repeat;	background-color: #1f1f20; color: fff; }";
		 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 	

	 		break;

	 		case "tile":
		 		bodyStyle = " body { background: url('" + Fondo.path + "tile.png') repeat;	background-color: #1f1f20; color: fff; }";
		 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 	
	 		break; 

	 		case "backgreen":	 		
	 			bodyStyle = " body { background: url('" + Fondo.path + "backgreen.jpg') repeat;	background-color: #1f1f20; color: fff; }";
		 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 		
	 		break;
	 		case "green":
	 		bodyStyle = " body { background: url('" + Fondo.path + "imagesgreen.jpg') repeat;	background-color: #1f1f20; color: fff; }";
		 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 		
	 		break;

	 		case "gray":
	 			bodyStyle = " body { background: url('" + Fondo.path + "gray38.gif') repeat;	background-color: #1f1f20; } ";
		 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 
	 		
	 		break;

	 		case "ladrilloazul":
		 	bodyStyle = " body { background: url('" + Fondo.path + "ladrilloblue.jpg') repeat;	background-color: #1f1f20; color: white; }";
		 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 		
		 		break;
// White ligth theme
		case "telagreen": 
			bodyStyle = " body { background: url('" + Fondo.path + "bgtela.jpg') repeat;	background-color: #E3E3E3; }";
		 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 		
		 		break;

		 case "ladrilloblanco":
		 bodyStyle = " body { background: url('" + Fondo.path + "ladrillo.jpg') repeat;	background-color: #E3E3E3; }";
		 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 		
		 		break;

		 		case "golf":
// Golf-Shirt-Grey.jpg
						bodyStyle = " body { background: url('" + Fondo.path + "Golf-Shirt-Grey.jpg') repeat;	background-color: #E3E3E3; }";
				 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 		
		 		break;

		 		case "paper":
			 		bodyStyle = " body { background: url('" + Fondo.path + "paper00.jpg') repeat;	background-color: #E3E3E3; }";
				 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 		
		 		break;

		 	case "paper-y":
			 		bodyStyle = " body { background: url('" + Fondo.path + "parchment-background.jpg') repeat;	background-color: #E3E3E3; }";
				 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 		
		 		break;

		 	case "pluma":
			 		bodyStyle = " body { background: url('" + Fondo.path + "pluma.png') repeat;	background-color: #E3E3E3; }";
				 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 		
		 	break;

		 	case "alert-green":
		 		bodyStyle = " body { background: url('" + Fondo.path + "blackGreen.gif') repeat;	background-color: #E3E3E3; }";
				document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 				 	
		 	break; 
		 	case "home01":
		 	bodyStyle = " body { background: #525FB8; background: -webkit-linear-gradient(right, #525FB8, #6FC2B6); background: -moz-linear-gradient(right, #76b852, #8DC26F);background: -o-linear-gradient(right, #76b852, #8DC26F); background: linear-gradient(to left, #0D1236, #07231E); font-family: 'Roboto', sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }  "; 
		 		document.getElementById("cssBg").innerHTML = '<style type="text/css"> '+ bodyStyle +' </style>';	 			 			 				 	
		 	break; 

		 	case "home02":
		 	bodyStyle = " body { background: #525FB8; background: -webkit-linear-gradient(right, #525FB8, #6FC2B6); background: -moz-linear-gradient(right, #76b852, #8DC26F); background: -o-linear-gradient(right, #76b852, #8DC26F); background: linear-gradient(to left, #131F78, #1E7669); font-family: 'Roboto', sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }"
	 	}
	 }, 

	 setFullScreenVideo: function(){
	 	var htm = '<video controls="" autoplay="" name="media"><source src="resource/intro_finalCND.mp4" type="video/mp4"></video>'; 
	 	$("#mybody").html(htm); 
	 },

	 setFullMp4Video: function(url, callback){
	 	Fondo.EndVideo = function(){ }; 
	 		if(typeof callback !== "undefined"){
	 			Fondo.EndVideo = function(){		 				
	 				document.getElementById("mybody").innerHTML = ""; 
	 				Fondo.setBodyPage(function(){
	 					callback();
	 				}); 
	 			}
	 		}

	 	var htm = '<video id="mp4-video-source" controls="" onended="Fondo.EndVideo()" name="media" ><source src="'+ url+ '" type="video/mp4"></video>'; 	 	
	 	var bodys = document.getElementById("mybody");
	 	bodys.innerHTML = htm; 

		$(document).ready(function(){
			var video = document.getElementById("mp4-video-source");	 		 				
	 		video.load(); 	 		
	 		video.play(); 
		}); 
	 	

	 }, 

	 setInicioPage: function(callback){
	 	http.get("template/inicio.html", function(res){
	 		$("#mybody").html(res);
	 		if(typeof callback === "function") {
	 			callback(); 
	 		}
	 	}); 
	 }, 

	 setParkingPage: function(callback){

	 	http.get("template/parking.html", function(res){

	 		$("#mybody").html(res);

	 		if(typeof callback === "function") {
	 			callback(); 
	 		}	 		
	 	}); 
	 }, 

	 setBodyPage: function(callback){

	 	http.get("body.html", function(res){
	 		$("#mybody").html(res); 
	 		if(typeof callback === "function") {
	 			callback(); 
	 		}
	 	}); 
	 }, 

	 EndVideo: function(){
	 }
}; 

var http = {
	get: function(url, callback, type){               	
          caph.xhr(url, {
                method : 'get', // omission possible.               
                type : type // omission possible.
            }).then(function(response) {
            	// “XML”, “HTML”, “TEXT”, “JSON” and “JSONP”, default is “XML”
            	if(response.isSuccess()){
            		var dt = ""; 
            		switch(type){           			
            			case "JSON":            			
            			case "json":  
            			 dt = response.getJSON();
            			 break;
            			case "XML":
            			dt = response.getXML();
            			break;
            			case "HTML":
            			case "TEXT":
            			case "text":
            			default:
            			 dt = response.getText();
            			 break; 
            		}

            		callback(dt); 

            	} else { 
            		callback(response); 
            	}
            });
	}, 
	getAjax: function (url, callback, typeS){
	var tipo = typeS; 
	if(typeof typeS == "undefined" ){
		tipo = "text"; 
	}
	$.ajax({
			  url: url,
			  dataType: tipo, 
			  async: true,
			  success: function(dt){				  
				 callback(dt); 									  
			  }
			});
	}	
};

/** END Mensajes E informacion **/


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

	alert("EN la Conexion ***************"); 

	var cLen = this.ListIp.length;
	var cArr = this.ListIp;
	var cIndex = this.cIndex;	
	var fechaJson = this.fechaJson.toJSON();

alert("Buscando el Conector")
	if(cNext){
		cIndex++;		
		if(cIndex >= cLen ){
			cIndex = 0; 
		}
	}	

	alert("Despues de Next"); 

	// Conxion con el seridor para la INstalacion y envio de Informacion	
	var ServerTEMP = new FancyWebSocket('ws://' + cArr[cIndex].toString().trim());	
	this.Server = ServerTEMP;
	Msg.log("Buscando la conexion del servicio.");

	this.Server.bind('open', function() {
		Msg.notice( "Conectado correctamente.!" );	
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

	var miInstancia = this; 
	
	//OH NOES! Disconnection occurred.
	this.Server.bind('close', function( data ) {
		Msg.error( "Disconnected.");
		var myVarReconect = setTimeout(function(){

			alert(miInstancia); 	     			     
		    Msg.warning("Reintentar", "Espere un momento... <br> Intentando reconectar con el servidor."); 	
		    alert("Buscando Reconectar"); 
			miInstancia.conectar(true);			
			alert("Buscando Reconectar"); 
			
		}, 40000);
	});

	//Log any messages sent from server
	this.Server.bind('message', function( payload ) {
		if(payload.trim() != ""){
			var infor = JSON.parse(payload); 
			Master.receptorWs(infor);			
		}
	});		
	this.Server.connect();
	this.cIndex = cIndex;
};

//************************************* Master TV Computer ********************************
// Master TV
MasterTV = function() {	
	this.page_config = {};	
	this.app_info = {server: "", version: "", serverRequest: ""};
	this.ManagerPages = {
			showInfoBar: false,
			EventRemote: {ENTER: function(){  }, GREEN: function(){ } },
			infoBarLeyend: [],
			content: {}
	};  

	alert("Estamos en la Istancia"); 
	
	var index;
	var content_txt = [];
	var content_img = [];
	var title_txt = [];	
	var sessionesPAG = ["applicationWrapper"];
	if(this.setFileConfig()){
		this.conn = new ConexionTV(this.app_info.server);
	} 	
};

MasterTV.prototype.setFileConfig = function(){	
	
	// Yes Exists File. OF COMPUTER.		
	// var firstData = ['192.168.183.1:9300'];

	// var firstData = ['10.234.133.76:9300'];
	// For Producction SERVER

	var firstData = ConfigSetting.ws;
	var firstServer = ConfigSetting.serverApp;
	var app_info = {}; 
	var page_config = {}; 	

this.app_info.server = firstData; 
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


// Msg.log("Ejemplo Caso 1", "Titulo", 0); 

// Msg.showMarqueeBar("Este es el mejor de los Escenarios para pasar informacion Importante");  





