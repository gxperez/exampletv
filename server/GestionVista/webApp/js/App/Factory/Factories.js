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
            obj: {},       
            modo: 0,
            accion: 0,
            selectedIndex: 0,
            esValido: false,
            vldt: null,
            $Form: {},
            $Pagination: {},
            $Search: {},

        initt: function(options){
          $("#formulario").hide();          
          $("#ListMantenimiento").show();
          Crud.renderPaginate(); 


        },

        renderPaginate: function(opt){

            // configuracion de la cantidad de registros, cantidad de Paginas 

            Crud.$Pagination = $('#page-selection-APP').bootpag({
                   total: 0,                   
                   maxVisible: 10
                }).on('page', function(event, num){                                    
                    // Angular Ajax or whiting search.
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
               console.log("Este es el Crud en el Cliente."); 

        },
        reset: function(){  
          $("#formulario").hide();          
          $("#ListMantenimiento").show();

          for(var i in Crud.obj){
            if(Crud.obj.hasOwnProperty(i) ){
              Crud.obj[i] = ""; 
            }
          } 
        },

        validate: function(){
            for (var form in Crud.$Form) {
                if (Crud.$Form[form].hasOwnProperty("$invalid") && Crud.$Form[form].$invalid) {
                    alert("Favor llenar el Formulario Sip");
                 //$sysUtil.ShowMessage($smt.info, "Favor de completar los registros correctamente.");
                return false;
            }
        }
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