alert('master.js loaded');

var widgetAPI = new Common.API.Widget();
var tvKey = new Common.API.TVKeyValue();
var pluginAPI = new Common.API.Plugin();
var fileSystemObj = new FileSystem();

var copy = {}; 
var fileObj = {};  
var instancia = null;
var configFiles = ["serverWSUrl.data", "version.data", "allsource.data", "serverRequest.data" ]; //{ 0 = serverURL, 1 = version, 2 = all source }
var serverUpdatePath = null; 
var macTV;

var mTimer = {
	cIndexC: 0, // Contenido.
	cIndexS: 0, // Slider.
	TransicionFin: ""
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

		alert("Initt"); 
			  widgetAPI.sendReadyEvent();
			 document.getElementById("anchor_main").focus();

			 /*

			 $.get("http://10.234.51.99:8079/GestionVista/webApp/img/esqm_TresxTres.png", function(dt){
			 	log(dt); 
			 }).done(function(dt) {
    alert( "second success" );
    log("Exito:" + dt); 
  })
  .fail(function(dt) {
  	log("Fallo:" + dt); 

    alert( "error" );
  });
return 0; 
*/
			 Master.setSmartTemplate(function(){			 	

			 	alert("Initt: Set LOcal Html"); 

			 	Master.setLocalHtml("inicio", "inicio_01"); 	

			 	if(instancia == null || instancia === undefined ){			    	
		    	  instancia = new MasterTV();
		    	 // // console.log(instancia);
		     	}
			 });

		     
		     
		     //Master.showWelcomePages();
// 		     Master.inittVideo(); 		     
	},

	KeyDown: function(){	
			
		var keyCode = event.keyCode;		
			    if(instancia == null || instancia === undefined ){			    	
			    	instancia = new MasterTV();			    	
			    	instancia.handleKeyDown(keyCode);
			    }else{			    	
			    	instancia.handleKeyDown(keyCode);			        
			    }
		},

		receptorWs: function(data){	
			// console.log("Aqui Llego. Receptor");
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
						// console.log("Revisara Si es Necesario la Actualizacion.");
						var prog = localStorage.getItem("programaTV");
						if(prog == null || typeof prog == "undefined" || typeof prog == "string" ){
								prog = {}; 
						}

						for (var i in data ) {
							// console.log(i + "===> " + data[i]); 
						}

					// console.log(data.fechaActual); 
					// console.log("Fecha Actualizada:-::: ^^"); 

					if(!(data.fechaActual in prog) ){
						localStorage.setItem("programaTV", null); 	

						alert("Obtener Programa.... "); 

						Master.ObtenerPrograma(data.fechaActual, data.server, data.fecha); 
							log(data.Msg); 

						return true; 

					} else {

						alert("Ya he Configurado Incorrectamente el programaTV...."); 
					}

					// console.log("El contenido ya ha sido actualizado.");
					log(data.Msg); 
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
		//	url = url.replace("10.234.133.76", "localhost");

			getRqst(url, function(res){
				if(res.IsOk){
					// Estuvo OK el asunto
					alt[fecha] = {programa: res.programa, contenido: res.contenido}; 
					localStorage.setItem("programaTV", JSON.stringify(alt) );
					log("Configurando programa."); 

					Master.cambiarSimpleImgPantallaFull("template/img/actualizando.png", {}); 
					// Master.setTimerPerPrograma(fechaServidor);
					Master.setFormatTimerPerPrograma(fechaServidor);
				} else {
				//	alert("Sin Asignacion"); 
					log(res.Msg);					
					Master.recorrerProgramaSinAsingacion(); 

				}
	}, "json");	
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
	$.get("template/esquemas.html", function( data ) {
		// Carga del Html puro; 		
		Master.html = data; 
		// $("#applicationWrapper").html(data);				
		callBack(); 
	});

}, 

setLocalCss: function(css){	
	$.get("template/styles/" + css +".css", function( data ) {		
			$("#cssApplicationWrapper").html("<style>" + data + "</style>");		
	});
}, 

setLocalHtml: function(html, css){
	if(typeof css !== "undefined" ){
		// EL template de Espera Pantalla de Bloqueo.
		$.get("template/styles/" + css + ".css", function( data ) {
			$("#cssApplicationWrapper").html("<style>" + data + "</style>");					
		});
	}			

	$.get("template/" + html + ".html", function( data ) {
			$("#applicationWrapper").html(data);					
	});
}, 

recorrerProgramaSinAsingacion: function(){	
	// Paso 1. setTheme
	/**
	$.get("template/styles/smart.css", function( data ) {		
		$("#cssApplicationWrapper").html("<style>" + data + "</style>");		
	});
	**/	

	/*

	var defaultPropertities = {
		"BloqueID":"0",
	"DuracionBloque":"00:03:30",
	"DuracionBloqueSec":"210",
	"listaContenido":
	{"8D054484-6682-984F-D906-BD051334641E":
	{"Guid":"8D054484-6682-984F-D906-BD051334641E",
		"Duracion":"00:17:10",
		"Descripcion":"Recorrido Sin Asignar",
		"Orden":"1",
		"slides": {
		 "1":
			 {"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"1",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"6",
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

*/


	var defaultPropertities = {
	"BloqueID":"0",
	"DuracionBloque":"00:03:30",
	"DuracionBloqueSec":"210",
	"listaContenido":
	{"TemporalContenido":
		{"Guid":"8D054484-6682-984F-D906-BD051334641E",
		"Duracion":"00:17:10",
		"Descripcion":"Recorrido Sin Asignar",
		"Orden":"1",
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
				"DuracionPageSec":"6",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"1",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"template/img/blueBack.png" // blueBack
					}
				]
				}, 
			"1":
				{"EsquemaTipo":"2",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"2",
				"Posicion":"1",
				"DuracionPage":"00:00:06",
				"DuracionPageSec":"15",
				"secciones":[					 
					{"Encabezado":"Segundo",
					"Posicion":"1",
					"FuenteTipo":"1",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"http://10.234.51.99:8079/GestionVista/webApp/img/esqm_TresxTres.png"
					},

					{"Encabezado":"Primero",
					"Posicion":"2",
					"FuenteTipo":"3",
					"RepresentacionTipo":"1",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"http://cnddosdobis:8090/WebServices/api.asmx/ObtenerDocumentoJSON?docCode=530b62e4-19d3-4e34-8f0c-9d007f27f55a&queryParametroValores="
					},					

					{"Encabezado":"Segundo",
					"Posicion":"3",
					"FuenteTipo":"1",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"template/img/ConfiHora01.png"
					}, 
					{"Encabezado":"Segundo",
					"Posicion":"4",
					"FuenteTipo":"1",
					"RepresentacionTipo":"1",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"template/img/ConfiHora02.png"
					},

					{"Encabezado":"Segundo",
					"Posicion":"4",
					"FuenteTipo":"1",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"template/img/ConfiHora02.png"
					}
				]
				},

				"2":
			{"EsquemaTipo":"1",
				"bgColor":"#000",
				"TransicionTipoIni":"0",
				"TransicionTipoFin":"1",
				"MostrarHeader":"1",
				"Posicion":"2",
				"DuracionPage":"00:00:30",
				"DuracionPageSec":"5",
				"secciones":[
					{"Encabezado":"Primero",
					"Posicion":"1",
					"FuenteTipo":"4",
					"RepresentacionTipo":"3",
					"FuenteID":"0",
					"EsManual":"0",
					"Url":"http://cnddosdobis:8090/WebServices/api.asmx/ObtenerDocumentoHTML?docCode=df980dea-5ec3-4849-bdb9-54e2d0f2adab" // blueBack
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

Master.generateContentByFuenteTipoSimple(config); 


	mTimer.cIndexS++; 	
	mTimer.TransicionFin = config.TransicionTipoFin; 
	mTimer.contenido = setTimeout(function(){
		Master.renderBloque(); 
	}, parseInt(config.DuracionPageSec) * 1000 ); 
	// 



}, 

generateContentByFuenteTipoSimple: function(config){
	// Si Fuente tipo es 
		var hasRequest = false; 
		var requestList = []; 		

	config.secciones.forEach(function(item, indx) {		
		switch(parseInt(item.FuenteTipo)){
		case 1: // Imagen 
		$("#sc-" + item.Posicion).html('<img src="'+ item.Url + '" class="">'); 
		break; 
		case 2: // Texto.
		$("#sc-" + item.Posicion).html("<p>" +  item.Url  +"</p>");
		break; 
		case 3: // Bischart.

		if(parseInt(item.EsManual) == 0) {

			hasRequest= true; 
			requestList.push({Url: item.Url, Type:"JSON", methodCallRequest: function(res){

				alert("SOLO YEGARA AQUI SIIIIIIIIII.."); 

					var responseJSON = res; 
                    Master.dTransAuto[item.Posicion] ={dt: datatransformer.new( responseJSON.data, responseJSON.config), visuals: responseJSON.config.visuals};
                	Master.fn_onShow.push({name: "Bischart", type: "auto"});
				

                    
                } }); 
				
		} else {

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

		//	Master.setLocalCss("white"); 
		

			if(item.Url in Master.excelcollection){
				$("#sc-" + item.Posicion).html(Master.excelcollection[item.Url]); 
				
			} else {

				requestList.push({Url: item.Url, Type:"get", methodCallRequest: function(data){					
					Master.excelcollection[item.Url] =  data
					$("#sc-" + item.Posicion ).html(data); 
				} }); 
			}			

						
		break; 
		case 5: // OfficeVIewPowerPoint.				

		break; 

		case 6: // video.
		// 	return "htmlVIdeo"; 
		break; 

		case 7: // Simple HTML Renderizado.
		requestList.push({Url: item.Url, Type:"get", methodCallRequest: function(data){					
			

				$("#sc-" + item.Posicion ).html(data); 
			

				} 
			}); 
			
					

		// 	return "htmlVIdeo"; 
		break;

		default:		
		break; 
	}
	}); 


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
		getRqst( arreglo[i].Url, function(dta) {
		 arreglo[i].methodCallRequest(dta); 
		 i++; 
		 // log("Ha regresado el Get" + dta ); 

		 Master.nextRequest(arreglo, i, len); 
		}, "html" ); 

	} 

	if(arreglo[index].Type == "JSON"){
		alert("Siguiente Paso Get del JSON"); 

		alert(arreglo[i].Url); 
		log(arreglo[i].Url); 

		getRqst( arreglo[i].Url, function(dta) { 

			alert("Aqui Quiere LLegar Pero"); 
			alert(dta); 
		//	log("HTTP: " + JSON.stringify(dta) ); 
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

					alert( JSON.stringify(Master.dTransAuto[it].visuals[0].visualType) ); 


										alert("/*********************/"); 
										alert(math.eval("b * a", {a: 2, b:4})); 
										alert("/*******************/"); 

										alert(typeof Master.dTransAuto[it].dt.generateVisual); 

										
Master.dTransAuto[it].dt.generateVisual(Master.dTransAuto[it].visuals[0].visualType, Master.dTransAuto[it].visuals[0].visualOptions,
					'sc-' + it ).render();
										


					



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

cambiarBloque: function(){
	// Consular al Servidor cual es el Bloque
	alert("Consulta para Cambiar el Bloque. "); 

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
// 	widgetAPI.sendReadyEvent();
	
};

MasterTV.prototype.setFileConfig = function(){	
	
	// Yes Exists File. OF COMPUTER.		
	// var firstData = ['192.168.183.1:9300'];

	// var firstData = ['10.234.133.76:9300'];
	// For Producction SERVER

	var firstData = ['10.234.51.99:9300'];
	var firstServer = 'localhost:7777/GestionVista/'; 
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

	log( "Server : " + cArr[cIndex].toString().trim() ); 

	//Let the user know we're connected
	this.Server.bind('open', function() {
	log( "Connected." );	

//	try{

		var networkPlugin = document.getElementById('pluginNetwork');
	 var mac = networkPlugin.GetMAC(0) || networkPlugin.GetMAC(1);
	 macTV = mac;  

	// Provicional
	// macTV = '222-222-2222'; 
	// mac = macTV; 
	
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

/*	} catch(err){
		log(err); 
		alert("Error en la Conexion del Documento."); 
		alert(err); 
	}
	*/
	
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

function getRqst(url, callback, typeS){

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