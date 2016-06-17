
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

$ang.controller('DispositivoController', ['$scope', '$http',  'AppCrud', 'AppHttp','AppMenuEvent', '$compile', 'AppSession', function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {

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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + "Dispositivo/Eliminar", iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaDispositivo.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert("Error en el POST SERVER");

            });  



            // Metodos para la Eliminacion de Elementos.


        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + "Dispositivo/Crear", $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


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
                $appSession.IsSession(res);                                                         
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

// TODO: BLOQUE
$ang.controller("BloquesController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };
        $scope.listaBloques =[]; 
        $scope.pantallaNombre = 'Registro Bloques';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + 'Bloques/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'Bloques/Buscar'
        });

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaBloques = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'Bloques'};            
             http(base_url + 'Bloques/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'Bloques/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'Bloques/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'Bloques/Obtener', {}, function (dt) {
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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'Bloques/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaBloques.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert('Error en el POST SERVER');
            });  

        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'Bloques/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaBloques.push(res.data);
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

            $.post(base_url + 'Bloques/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaBloques[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });            
            break;
        }
        }
}]);


// TODO: CONTENIDO

$ang.controller("ContenidoController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };
        $scope.listaContenido =[]; 
        $scope.pantallaNombre = 'Registro Contenido';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + 'Contenido/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'Contenido/Buscar'
        });

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaContenido = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'Contenido'};            
             http(base_url + 'Contenido/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'Contenido/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'Contenido/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'Contenido/Obtener', {}, function (dt) {
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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'Contenido/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaContenido.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert('Error en el POST SERVER');
            });  

        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'Contenido/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaContenido.push(res.data);
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

            $.post(base_url + 'Contenido/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaContenido[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });            
            break;
        }
        }
}]);


$ang.controller("FuentesController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };
        $scope.listaFuentes =[]; 
        $scope.pantallaNombre = 'Registro Fuentes';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + 'Fuentes/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'Fuentes/Buscar'
        });

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaFuentes = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'Fuentes'};            
             http(base_url + 'Fuentes/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'Fuentes/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'Fuentes/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'Fuentes/Obtener', {}, function (dt) {
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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'Fuentes/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaFuentes.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert('Error en el POST SERVER');
            });  

        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'Fuentes/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaFuentes.push(res.data);
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

            $.post(base_url + 'Fuentes/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaFuentes[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });            
            break;
        }
        }
}]);


// TODO: FUERZA VENTA

 $ang.controller("FuerzaVentaController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };
        $scope.listaFuerzaVenta =[]; 
        $scope.pantallaNombre = 'Registro FuerzaVenta';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 


        $scope.inittCrud = function(){

            $scope.vCrud.initt({url: base_url + 'FuerzaVenta/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'FuerzaVenta/Buscar'
            });
    
            http(base_url + 'FuerzaVenta/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
            });

            $('#fvOculto').show(); 


        }; 

$scope.ActualizarHoja = function(){

    // confirmacion 
    if(confirm("Seguro Desea Actualizar") ){
        var sendObj = $scope.vCrud.getForm(); 
        sendObj.sincronizar = true;                 
        
         $.post(base_url + 'FuerzaVenta/SincronizarWebServices', sendObj, function(res){
                // Validacion de Sessiones.

                $appSession.IsSession(res);      

                 if(res.IsOk){
                        $scope.resumenFuerzaVenta = res.data; 
                        $scope.$apply();

                    } else {
                        alert("Error en la Data"); 
                    }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }
            }, 'json').fail(function() {                

                alert('Error en el POST SERVER');

            }); 

    }


}


        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaFuerzaVenta = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.master = function(){

            $scope.Pantalla = {nombre: 'FuerzaVenta'}; 

            http(base_url + 'FuerzaVenta/ObtenerMaestro', {}, function (dt) {                                                    
                    $appSession.IsSession(dt);                               
                    if(dt.IsOk){
                        $scope.resumenFuerzaVenta = dt.data; 
                    } else {
                        alert("Error en la Data"); 
                    }
                    
             });


        }


        $scope.initt = function () {

            $scope.inittCrud(); 

            $scope.Pantalla = {nombre: 'FuerzaVenta'};            
             http(base_url + 'FuerzaVenta/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'FuerzaVenta/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'FuerzaVenta/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'FuerzaVenta/Obtener', {}, function (dt) {
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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'FuerzaVenta/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaFuerzaVenta.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert('Error en el POST SERVER');
            });  

        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'FuerzaVenta/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaFuerzaVenta.push(res.data);
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

            $.post(base_url + 'FuerzaVenta/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaFuerzaVenta[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });            
            break;
        }
        }
}]);


// Grupo Controlador.
 $ang.controller("GrupoController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };
        $scope.listaGrupo =[]; 
        $scope.pantallaNombre = 'Registro Grupo';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + 'Grupo/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'Grupo/Buscar'
        });

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaGrupo = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'Grupo'};            
             http(base_url + 'Grupo/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'Grupo/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'Grupo/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'Grupo/Obtener', {}, function (dt) {
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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'Grupo/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaGrupo.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert('Error en el POST SERVER');
            });  

        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'Grupo/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaGrupo.push(res.data);
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

            $.post(base_url + 'Grupo/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaGrupo[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });            
            break;
        }
        }
}]);

// -- TODO: Progrmacion -----------------

 $ang.controller("ProgramacionController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };
        $scope.listaProgramacion =[]; 
        $scope.pantallaNombre = 'Registro Programacion';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + 'Programacion/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'Programacion/Buscar'
        });

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaProgramacion = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'Programacion'};            
             http(base_url + 'Programacion/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'Programacion/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'Programacion/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'Programacion/Obtener', {}, function (dt) {
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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'Programacion/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaProgramacion.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert('Error en el POST SERVER');
            });  

        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'Programacion/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaProgramacion.push(res.data);
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

            $.post(base_url + 'Programacion/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaProgramacion[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });            
            break;
        }
        }
}]);


 $ang.controller("SeccionTemplateController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };
        $scope.listaSeccionTemplate =[]; 
        $scope.pantallaNombre = 'Registro SeccionTemplate';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + 'SeccionTemplate/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'SeccionTemplate/Buscar'
        });

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaSeccionTemplate = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'SeccionTemplate'};            
             http(base_url + 'SeccionTemplate/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'SeccionTemplate/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'SeccionTemplate/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'SeccionTemplate/Obtener', {}, function (dt) {
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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'SeccionTemplate/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaSeccionTemplate.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert('Error en el POST SERVER');
            });  

        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'SeccionTemplate/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaSeccionTemplate.push(res.data);
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

            $.post(base_url + 'SeccionTemplate/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaSeccionTemplate[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });            
            break;
        }
        }
}]);

 $ang.controller("UsuarioLogSesionController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };
        $scope.listaUsuarioLogSesion =[]; 
        $scope.pantallaNombre = 'Registro UsuarioLogSesion';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + 'UsuarioLogSesion/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'UsuarioLogSesion/Buscar'
        });

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaUsuarioLogSesion = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'UsuarioLogSesion'};            
             http(base_url + 'UsuarioLogSesion/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'UsuarioLogSesion/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'UsuarioLogSesion/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'UsuarioLogSesion/Obtener', {}, function (dt) {
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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'UsuarioLogSesion/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaUsuarioLogSesion.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert('Error en el POST SERVER');
            });  

        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'UsuarioLogSesion/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaUsuarioLogSesion.push(res.data);
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

            $.post(base_url + 'UsuarioLogSesion/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaUsuarioLogSesion[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });            
            break;
        }
        }
}]);


$ang.controller("FuerzaVentaDispositivoController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };

         $scope.dropzone = {};

         $scope.dropzoneFields = []; 


        $scope.sorti ={1: [], 2: [], 3: [], 4: [], 5: [], 6: [], 7: []}; 

         $scope.sortableOptions =
          {
    connectWith: ".sortable1-cont",
    start: function (e, ui) {  

  //    $('.sortable1-cont').sortable('refresh');
      
    },
    
    update: function (e, ui) {

    console.log("Movio Correcto");     
    console.log(ui.item.sortable.droptarget[0].classList[0]); 

      if (ui.item.sortable.droptarget[0].classList[0] !== "sortable1-cont")
        ui.item.sortable.cancel();


    },
    stop: function (e, ui) {

      if (ui.item.sortable.droptarget == undefined) {
        //$scope.$apply($scope.dragging = false);
        return;
      }else if (ui.item.sortable.droptarget[0].classList[0] == "sortable1-cont") {
        // run code when item is dropped in the dropzone
        // $scope.$apply($scope.dragging = false);
      }else{
        // $scope.$apply($scope.dragging = false);
      }
    }
  };
  /*
   {
            placeholder: "app",
            
            update: function(event, ui) {      
                    if (event.target.id !== 'screen-1' && ui.item.sortable.droptarget.attr('id') === 'screen-1' && $scope.rawScreens[1].length >= 10) {
                        // Ajustes en General
                        ui.item.sortable.cancel();
                    }
                }
            };
*/
        $scope.listaFuerzaVentaDispositivo =[]; 
        $scope.copyFuerzaVentaDispositivo =[]; 
        $scope.pantallaNombre = 'Registro FuerzaVentaDispositivo';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + 'FuerzaVentaDispositivo/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'FuerzaVentaDispositivo/Buscar'
        });

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaFuerzaVentaDispositivo = res.data; 
                    $scope.copyFuerzaVentaDispositivo = res.data;
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.selectedClassNivel = function(obj){



            if(obj.DispositivoID !== null){

                return "label-success"; 
            }

            return "label-default"; 


        }

        $scope.listaDispositivo = {}; 

        $scope.panel = function(){

            $(function(){                

                $scope.listaDispositivo = JSONData; 
                $scope.listaFuerzaVenta = JFData;
                 $scope.listaFuerzaVentaCopy = JSON.parse(JSON.stringify(JFData));

                


                
            }); 
            


        }


        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'FuerzaVentaDispositivo'};            
             http(base_url + 'FuerzaVentaDispositivo/Obtener', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         
                    $scope.ObtenerPaginacionRes(dt); 
             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'FuerzaVentaDispositivo/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'FuerzaVentaDispositivo/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'FuerzaVentaDispositivo/Obtener', {}, function (dt) {
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

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'FuerzaVentaDispositivo/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaFuerzaVentaDispositivo.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    alert(res.Msg);
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                }

            }, 'json').fail(function() {                
                alert('Error en el POST SERVER');
            });  

        }; 

        $scope.ListAll = function(){
        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'FuerzaVentaDispositivo/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaFuerzaVentaDispositivo.push(res.data);
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

            $.post(base_url + 'FuerzaVentaDispositivo/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaFuerzaVentaDispositivo[$scope.vCrud.selectedIndex]= res.data;
                    $scope.$apply();
                    $scope.vCrud.reset();                    
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });            
            break;
        }
        }
}]);


})();

