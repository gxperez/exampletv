
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

        var link = base_url + "webApp/vistas/sm_"+ link +".html"; 
        console.log(link);

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
            console.log(":Llenar");

            var copiObj = JSON.parse(JSON.stringify(obj));
            $scope.currentObj = copiObj;
            $scope.CLUBCrud.obj = $scope.currentObj; 


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

$ang.controller('DispositivoController', ['$scope', '$http', 'AppHttp','AppMenuEvent', '$compile', function ($scope, $http, appHttp,appMenuEvent, $compile) {

        function http(url, data, callback) {
            appHttp.Get(url, data, callback)
        }        

        $scope.currentObj = {            
            Descripcion: ""
        };

        $scope.CLUBCrud = CLUBCrud; 
        $scope.CLUBCrud.initt(); 
        
        $scope.listaDispositivo =[]; 
        $scope.pantallaNombre = "Registro Dispositivo";
        $scope.buscarLista = ""; 


        $scope.initt = function () {
            $scope.Pantalla = {nombre: "Dispositivo"};            

             http("Dispositivo/Obtener", {}, function (res) {
                if(res.IsOk){
                    $scope.listaDispositivo = res.data; 
                } else {
                    console.log("Uno un Error"); 

                }
                

             }); 

            
        };        

        $scope.Buscar = function(){                    
            console.log("Buscar para enviar o recibior"); 

            $.post(base_url + "Dispositivo/buscar",  function(res){
                // Ajustes del Json. Respuesta del Formulario

            }, 'json');


        }; 

        $scope.Llenar = function(obj, index){            

            var copiObj = JSON.parse(JSON.stringify(obj));
            $scope.currentObj = copiObj;
            $scope.CLUBCrud.obj = $scope.currentObj; 
            $scope.CLUBCrud.selectedIndex = index; 

        }; 

        $scope.Eliminar = function(item, indice){

            console.log("Eliminar");
            console.log(indice);
        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            var form = $scope.currentObj; 

        console.log("Guardar A ver si es Actualizar o Modificar"); 
        switch($scope.CLUBCrud.modo){
            case 0: // Nuevo Crear
            console.log("Envio para la creacion de listaMantenimiento"); 

            $.post(base_url + "Dispositivo/Crear", {objeto: form }, function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                if (res.IsOk){

                    $scope.listaDispositivo.push(res.data);                                                              
                    $scope.CLUBCrud.reset();
                    


                } else {
                    console.log("El registro no pudo ser completado"); 
                }


            }, 'json');            


            break;
            case 1: // Actualizar Existe 

            $.post(base_url + "Dispositivo/Actualizar", form, function(res){
                // Ajustes del Json. Respuesta del Formulario

            }, 'json');            

            break;
        }
         

          
            console.log($scope.CLUBCrud.modo); 


        }
}]);

  

})();

