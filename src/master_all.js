alert("Loaded master_all.js"); 
/** Mensajes y cintas de informacion **/
var Msg = {
	content: $("<div id='log'></div>"), 			
	log: function( text, titulo, forma ) {	
		 Msg.content.html( text );
		 if(forma == 0){
		 	$.blockUI({
		        message: Msg.content.html(),
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
	 	}
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
	}	
};



/** END Mensajes E informacion **/

// Msg.log("Ejemplo Caso 1", "Titulo", 0); 

// Msg.showMarqueeBar("Este es el mejor de los Escenarios para pasar informacion Importante");  





