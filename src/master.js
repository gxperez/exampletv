/**  Aqui esta la clase con controlara todas las extenciones. */
alert('master.js loaded');


var widgetAPI = new Common.API.Widget();
var tvKey = new Common.API.TVKeyValue();
var pluginAPI = new Common.API.Plugin();

var instancia = null;


option = {
	    title : {
	        text: '某站点用户访问来源',
	        subtext: '纯属虚构',
	        x:'center'
	    },
	    tooltip : {
	        trigger: 'item',
	        formatter: "{a} <br/>{b} : {c} ({d}%)"
	    },
	    legend: {
	        orient : 'vertical',
	        x : 'left',
	        data:['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
	    },
	    toolbox: {
	        show : true,
	        feature : {
	            mark : {show: true},
	            dataView : {show: true, readOnly: false},
	            magicType : {
	                show: true, 
	                type: ['pie', 'funnel'],
	                option: {
	                    funnel: {
	                        x: '25%',
	                        width: '50%',
	                        funnelAlign: 'left',
	                        max: 1548
	                    }
	                }
	            },
	            restore : {show: true},
	            saveAsImage : {show: true}
	        }
	    },
	    calculable : true,
	    series : [
	        {
	            name:'访问来源',
	            type:'pie',
	            radius : '55%',
	            center: ['50%', '60%'],
	            data:[
	                {value:335, name:'直接访问'},
	                {value:310, name:'邮件营销'},
	                {value:234, name:'联盟广告'},
	                {value:135, name:'视频广告'},
	                {value:1548, name:'搜索引擎'}
	            ]
	        }
	    ]
};


require.config({
    paths: {
        echarts: 'src/eChart'
    }
});

gobalTheme = {}; 
require(['echarts/theme/shine'], function (tarTheme) {
    gobalTheme = tarTheme;
});



Master = {
		KeyDown: function(){
		var keyCode = event.keyCode;
		console.log( " El codigo precionao es: =>" + keyCode);
			    if(instancia == null || instancia === undefined ){
			    	alert("Se intancio el Asunto Mejor de lo Mejor");
			    	instancia = new MasterTV();			    	
			    	instancia.handleKeyDown(keyCode);
			    }else{			    	
			    	instancia.handleKeyDown(keyCode);			        
			    }
		}, 		
		initt: function(){
			 widgetAPI.sendReadyEvent();			
		     document.getElementById("anchor_main").focus();
		     
		    var ggg = {};		    
		    require(
		            [
		                'echarts',
		                'echarts/chart/bar', // require the specific chart type
		                'echarts/chart/line',
		                'echarts/chart/pie'
		            ],
		            function (ec) {
		                // Initialize after dom ready
		                console.log(gobalTheme);             
		                    var myChart = ec.init(document.getElementById("applicationWrapper"));           
		                    myChart.setTheme(gobalTheme);
		                    myChart.setOption(option);
		                    
		                    ggg = myChart;
		                
		            }
		            );
		    
		    // Instancia del Web Socket.
		    
		    alert("Estableciendo conexion con el webSocket");
			Server = new FancyWebSocket('ws://10.234.130.55:9300'); 
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

			alert("Se va a conectar");
			
			Server.connect();
			
			alert("LLego Aqui"); 
		    
		}		
};



function MasterTV() {
	var index;
	var content_txt = [];
	var content_img = [];
	var title_txt = [];		
	var pageConfig = {};
	
	var sessionesPAG = ["applicationWrapper"];	
	
	
	widgetAPI.sendReadyEvent();
	
};

MasterTV.prototype.setPagesConfig = function(){	
	// Las tablas de diferencias entre los elementos. 
	
	
	
	
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
				send( mac );
			
			if(typeof(Storage) !== "undefined") {
			    // Code for localStorage/sessionStorage.
				alert("Este soporta y eso esta OK SI ");
			//	localStorage.setItem("name", "Mi SUPER");				
			} else {
			    // Sorry! No Web Storage support..
				alert("Sorry! No Web Storage support..");				
			}
			
			break;
		default:
			alert("SIII Probando"); 
			alert(localStorage.getItem("name"));
		
			alert("handle default key event, key code(" + keyCode + ")");
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