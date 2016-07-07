var prueba;

(function () {
    var $ang = appAng;

    var $app = new app();
    var $sys = $app.System;    
    var $format = $app.Format; 
    var $sysCrud = $sys.Crud;
    var $sysEnum = $sys.Enum.CrudState;
    var $sysconfig = $sys.Config;
    var $sysUtil = $sys.Utility;
    var $smt = $sys.Enum.MessageType;

    /*CRUD*/
    $ang.factory('AppCrud', function () {

         var Crud = {
            form: {},            
            modo: 0,
            hash: {},
            selectedIndex: 0,
            esValido: false,
            vldt: null,
            $Form: {},
            $Pagination: {},
            $configPagination: { maxRowsPage: 15, maxVisiblePage: 8},
            totalPages: 0,
            pageCallBack : function(){},
            $Search: {send: false, url: "", w: ""},

        initt: function(options){
          $("#formulario").hide();          
          $("#ListMantenimiento").show();

        if("searchUrl" in options){
          Crud.$Search.url = options.searchUrl; 
        }

          Crud.renderPaginate(options);

        },

        setPages: function(opt){

            Crud.$configPagination.maxRowsPage = opt.maxRowsPage;
            Crud.pageCalculate(opt); 
            
        },

        pageCalculate: function(opt){  
        // Calculador a de variavbles          
            if("totalResult" in opt){      
                var valPag =opt.totalResult/Crud.$configPagination.maxRowsPage          
                if(valPag < 1 && valPag > 0){
                    Crud.totalPages = parseInt(1);  
                } else {                    
                    Crud.totalPages = parseInt(valPag + 1);  
                }
                
                
                Crud.$Pagination.bootpag( {total: (Crud.totalPages), maxVisible: 10 } ); 
            }
        }, 

        renderPaginate: function(option){
            // configuracion de la cantidad de registros, cantidad de Paginas 
            Crud.pageCallBack = option.callback;
            Crud.$Pagination = $('#page-selection-APP').bootpag({
                   total: 0,                   
                   maxVisible: 10
                }).on('page', function(event, num){

                    var innerUrl = option.url;
                if(Crud.$Search.send === true){
                    innerUrl = Crud.$Search.url + "/" + Crud.$Search.w; 
                }                
                    $.getJSON( innerUrl + "?vNumPage=" + (num-1), function(res){
                        Crud.pageCallBack(res, num);
                    } );
                });
        },

        Cancelar: function(){
          $("#formulario").hide();          
          $("#ListMantenimiento").show();          
        },

        Editar: function(modo){
          $("#formulario").show();          
          $("#ListMantenimiento").hide();     
               Crud.modo = modo;              
        },
        reset: function(){  
          $("#formulario").hide();          
          $("#ListMantenimiento").show();

          for(var i in Crud.form){
            if(Crud.form.hasOwnProperty(i) ){
                if(!(i in Crud.hash)){
                    Crud.form[i] =  "";                     
                }              
            }
          } 
        },

        setForm: function( obj ){
            Crud.form = obj; 

            for(var i in Crud.form){
                if(Crud.form.hasOwnProperty(i)){
                    if($.isNumeric(Crud.form[i]) ){
                        Crud.form[i] = parseFloat(Crud.form[i]); 
                    }
                }
            }
        },

        formatObjForm: function(item, nameVar){
             var rest = {}; 
             if (typeof nameVar === 'undefined' || nameVar === null ){
                rest['objeto'] = item;                 
             // return {objeto: form }
            } else {
                rest[nameVar] = item;                
            }

            for(var i in Crud.hash) {
                if(Crud.hash.hasOwnProperty(i)){
                    rest[i] = Crud.hash[i]; 
                }
            }

            return rest; 

        }, 

        getForm: function( nameVar ){
            // Sus manos martille.
            var rest = {}; 
            if (typeof nameVar === 'undefined' || nameVar === null ){
                rest['objeto'] = Crud.form;                 
             // return {objeto: form }
            } else {
                rest[nameVar] = Crud.form;                
            }


            for(var i in Crud.hash) {
                if(Crud.hash.hasOwnProperty(i)){
                    rest[i] = Crud.hash[i]; 
                }
            }
            return rest; 
        }, 

        setHash: function(name, val){
            Crud.hash[name] = val;
             // Crud.form[name.trim()] = val.trim();
        },



        validate: function(){

            for (var form in Crud.$Form) {
                if (Crud.$Form[form].hasOwnProperty("$invalid") && Crud.$Form[form].$invalid) {
                       $.blockUI({ 
            message: $('<div style="size: font-size: 20px;"> <span>Campos incompletos </span>  <p>hay campos obligatorios que debes completar </p></div>'),
            fadeIn: 700, 
            fadeOut: 700, 
            timeout: 2000, 
            showOverlay: false, 
            centerY: false, 
            css: { 
                width: '350px', 
                top: '60px', 
                left: '', 
                right: '10px', 
                border: 'none', 
                padding: '5px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .6, 
                color: '#fff' 
            } 
        });                     
                 //$sysUtil.ShowMessage($smt.info, "Favor de completar los registros correctamente.");
                return false;
            }
        }

        return true; 
    }
}; 

return Crud; 
       
    });


$ang.factory('AppSession', function () {

         var miSession = {
            strict: true,
            form: {},                       
            pageCallBack : function(){},
            $Search: {send: false, url: "", w: ""},

        initt: function(options){
        },       

        IsSession: function(res){            
            if("IsSession" in res){
                if(res.IsSession == false){
                    miSession.refrescar();
                    return false;
                }
            } else {
                if(miSession.strict === true){
                    miSession.refrescar(); 
                    return false;
                }
            }            
        },
        refrescar: function(){
            // Windows Reload     
             document.location.reload(true);
        }
}; 
return miSession;
       
    });
 

    /*Http*/
    $ang.factory('AppHttp', function ($http) {
        var appHttp = {};

        appHttp.Get = function (url, data, callback) {
            $http({
                method: 'GET',
                url:  url,
                params: data
            }).success(callback);
        };

        appHttp.Post = function (url, data, callback) {
            $http.post( url, data).success(callback);
        };

        appHttp.Redirect = function (url) {
            window.location.href = url;
        }

        appHttp.Reload = function () {
            window.location.reload();
        }

        return appHttp;

    })

})();