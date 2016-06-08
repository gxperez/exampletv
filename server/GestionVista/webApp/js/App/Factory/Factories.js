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

            $Search: {},

        initt: function(options){
          $("#formulario").hide();          
          $("#ListMantenimiento").show();
          Crud.renderPaginate(options);

        },

        setPages: function(opt){

            Crud.$configPagination.maxRowsPage = opt.maxRowsPage;
            Crud.pageCalculate(opt); 
            
        },

        pageCalculate: function(opt){  
        // Calculador a de variavbles          
            if("totalResult" in opt){                
                Crud.totalPages = parseInt(opt.totalResult/Crud.$configPagination.maxRowsPage);                                
                
                Crud.$Pagination.bootpag( {total: (Crud.totalPages+ 1), maxVisible: 10 } ); 
            }
        }, 

        renderPaginate: function(option){
            // configuracion de la cantidad de registros, cantidad de Paginas 
            Crud.pageCallBack = option.callback;
            Crud.$Pagination = $('#page-selection-APP').bootpag({
                   total: 0,                   
                   maxVisible: 10
                }).on('page', function(event, num){

                    $.getJSON( option.url + "?vNumPage=" + (num-1), function(res){
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
        },

        getForm: function( nameVar ){

            // Sus manos martille.
            console.log(nameVar);
            console.log("Esperes Hasta el Final");

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
            Crud.hash[name.trim()] = val.trim();
             Crud.form[name.trim()] = val.trim();
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

    /*Pagination*/
    $ang.factory('AppPagination', function () {
        var paginacion = function () {
            var _paginacion = {
                ArrayPag: [],
                TotalAmount: 0,
                Max: 0,
                Filtro: "",
                Init: function () {
                    var array = [];
                    var value = Math.round(this.TotalAmount / this.Max) - 1;

                    for (var i = 0 ; i <= value; i++) {
                        array[i] = i
                    }

                    this.ArrayPag = array;
                },
                ShowSearcher: true,
                LoadInit: true,
                ActivedPagIndex: 0,
                ActivePag: function (index) {
                    this.ActivedPagIndex = index;
                },
                ActivePagClass: function (index) {
                    return this.ActivedPagIndex == index ? 'active' : "";
                },
                RangoVisible: [0, 10],
                RangoIsvisible: function (index) {
                    return (index >= this.RangoVisible[0] && index <= this.RangoVisible[1]) ? true : false;
                },
                RangoBack: function () {
                    if (this.RangoVisible[0] > 0)
                    {
                        this.RangoVisible[0] = this.RangoVisible[0] - 1;
                        this.RangoVisible[1] = this.RangoVisible[1] - 1;
                    }
                },
                RangoNext: function () {
                    var arr = this.ArrayPag.length - 1;

                    if (this.RangoVisible[1] < arr)
                    {
                        this.RangoVisible[0] = this.RangoVisible[0] + 1;
                        this.RangoVisible[1] = this.RangoVisible[1] + 1;
                    }
                },
                RangoFirst: function () { this.RangoVisible = [0, 10] },
                RangoLast: function () { var arr = this.ArrayPag.length - 1; this.RangoVisible = [(arr - 10) < 0 ? 0 : arr - 11, arr] },
                $List: function () { }
            };

            prueba = _paginacion;

            return _paginacion;
        }

        return paginacion;
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