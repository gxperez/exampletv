
(function () {
    var $ang = appAng;

    var $app = new app();
    var $tool = $app.Tool;
    var $format = $app.Format;

    var $sys = $app.System;
    var $sysCrud = $sys.Crud;
    var $sysEnum = $sys.Enum.CrudState;
    var $sysMsg = $sys.Msg.MsgType;

    var $sysUtil = $sys.Utility;
    var $smt = $sys.Enum.MessageType;
    var appPath = $sys.Config.ApplicationPath;

   
    /*AppController*/
    $ang.controller('AppController', ['$scope', '$http', 'AppHttp','AppMenuEvent', '$compile', function ($scope, $http, appHttp,appMenuEvent, $compile) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback)
        }

        $scope.MenuItemName = "";
        $scope.Notificaciones = [];

        $scope.NotificacionesTotalNoLeido = 0;

        $scope.PortalConfigs = [];

        $scope.AppRoot = appPath;

        $scope.AppHtml = "";

        appMenuEvent.menuScopeObj = $scope;

         $scope.Arreglo = [];
         $scope.texto = "Buenos dias";
        

        $scope.SetMain = function (link) {

        var link = base_url + link;         

        appHttp.Get(link, null, (function (res, status) {
                $scope.AppHtml = res;

            }));        
        }

        $scope.SetMenuItemName = function (menuItemName) {
            $scope.MenuItemName = menuItemName;
        }

        $scope.ChangeConfig = function (configID) {
            if ($sysCrud.GetState() != null) {
                $sysUtil.ShowInfoMessage($sysMsg.FinalizaCrud);
                return;
            }
            appHttp.Post('Home/ChangeConfig', { configID: configID }, function (res) {
                if (res.IsOk) {
                    appHttp.Reload();
                }
                else {
                    console.log("Error: ChangeConfig");
                }
            });
        }

        $scope.Redirect = function (link) {
            if ($sysCrud.GetState() != null) {
                $sysUtil.ShowInfoMessage($sysMsg.FinalizaCrud);
                return;
            }
            appHttp.Redirect(link);
        }

        $scope.AppInit = function () {  
            http("Notificacion/Obtener", null, function (res) {
                $scope.Notificaciones = res.Notificaciones;
                $scope.NotificacionesTotalNoLeido = res.CantidadTotalNoLeido;
            });
        }


        $scope.EliminarNotificacion = function (index) {
            http("Notificacion/Eliminar", { notificacionID: $scope.Notificaciones[index].NotificacionID }, function (res) {
                if (res.IsOk) {
                    $scope.Notificaciones.splice(index, 1);
                    $scope.NotificacionesTotalNoLeido--;
                }
                else {
                    $sysUtil.ShowDangerMessage(res.Msg);
                }
            });
        };
    }]);

 $ang.controller('SimpleFormController', ['$scope', '$http', 'AppHttp','AppMenuEvent', '$compile', function ($scope, $http, appHttp,appMenuEvent, $compile) {

        $scope.currentObj = {
            id: 0,
            descripcion: ""
        };
        $scope.CLUBCrud = CLUBCrud; 
        $scope.CLUBCrud.initt(); 
        
        $scope.listaMantenimiento =[]; 
        $scope.pantallaNombre = "La Pantalla";


        function http(url, data, callback) {
            appHttp.Get(url, data, callback)
        }        

        $scope.initt = function () {

            $scope.Pantalla = {nombre: "Mantenimientos"};


            $scope.listaMantenimiento = [
            {id:"aa", descripcion:"SI es"},
            {id:"ca", descripcion:"Creo Yop"},
            {id:"a1", descripcion:"LALA KASA"},
            {id:"a23", descripcion:"EN Espiritu y en Verdad KASA"},
            {id:"a3", descripcion:"EN Espiritu y en Verdad KASA"}
            ]; 

        };

        $scope.buscarLista = ""; 
        $scope.Buscar = function(){
            console.log("Buscar para enviar o recibior"); 
        }; 

        $scope.Llenar = function(obj){
            var copiObj = JSON.parse(JSON.stringify(obj));
            $scope.currentObj = copiObj;
        }; 

        $scope.Eliminar = function(item, indice){

            console.log("Eliminar");
            console.log(indice);
        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){            

            console.log("GUardar A ver si es Actualizar o Modificar"); 
            console.log($scope.CLUBCrud.modo); 


        }
}]);

$ang.controller('DispositivoController', ['$scope', '$http',  'AppCrud', 'AppHttp','AppMenuEvent', '$compile', 'AppSession' function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {

        function http(url, data, callback) {
            appHttp.Get(url, data, callback)
        }        

        var form = {                 
            Nombre: "",               
            Descripcion: "", 
        };


        $scope.listaDispositivo =[]; 
        $scope.pantallaNombre = "Registro Dispositivo";
        $scope.buscarLista = "";
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + "Dispositivo/Obtener", 
            "callback": function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            "searchUrl":  base_url + "Dispositivo/Buscar"
        });        
        

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaDispositivo = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log("Uno un Error");
                }
        }

        $scope.initt = function () {

            $scope.Pantalla = {nombre: "Dispositivo"};            
             http(base_url + "Dispositivo/Obtener", {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != "") {

             switch(cEvent.type ){
                case "keypress":
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + "Dispositivo/Buscar/" + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                                                                                         
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case "click":

                $scope.vCrud.$Search.send = true;
                http(base_url + "Dispositivo/Buscar/" + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                                                                                           
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }

        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + "Dispositivo/Obtener", {}, function (dt) {

                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        }  
        }; 


        $scope.Llenar = function(obj, index){            
            var copiObj = JSON.parse(JSON.stringify(obj));   
            $scope.vCrud.setForm(copiObj);            
            $scope.vCrud.selectedIndex = index;             
        }; 

        $scope.Eliminar = function(item, indice){
            console.log("Eliminar: ");
            console.log(indice);

            // Metodos para la Eliminacion de Elementos.


        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){

            console.log("Quien ha hecho algo"); 

            if(!$scope.vCrud.validate()){
                return false; 
            }
                      

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + "Dispositivo/Crear", $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(dt);                                         


                if (res.IsOk){
                    $scope.listaDispositivo.push(res.data);
                    $scope.vCrud.reset();                    
                } else {
                    // Reasignacion de Tokens.
                    alert(res.Msg);                     
                }
                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }
            }, 'json');            


            break;
            case 1: // Actualizar Existe 

            $.post(base_url + "Dispositivo/Actualizar", $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(dt);                                         
                // Ajustes del Json. Respuesta del Formulario
                if (res.IsOk){
                    $scope.listaDispositivo[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {
                    // Reasignacion de Tokens. Mensaje
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert("Este es la culpa tuy ay mia hermano"); 
            });            
            break;
        }

        }
}]);

  

})();

