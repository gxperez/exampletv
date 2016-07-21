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

	showMarqueeBar: function(text){
		$("#marqueBar").html("<div class='sf-ui-keyhelp sf-ui-keyhelp-black'><marquee> <p class=''> " + text.toString()  + " </p> </marquee> </div>"); 
	}, 

	hideMarqueeBar: function(){
		$("#marqueBar").html(""); 
	}

}; 
/** END Mensajes E informacion **/

Msg.log("Ejemplo Caso 1", "Titulo", 0); 

Msg.showMarqueeBar("Este es el mejor de los Escenarios para pasar informacion Importante"); 



