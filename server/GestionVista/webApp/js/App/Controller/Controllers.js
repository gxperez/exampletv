
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

        for (var i = gbl_Master_setInvervalLog.length - 1; i >= 0; i--) {
            console.log("Se eliminaron los Set time out # " + gbl_Master_setInvervalLog[i] ); 
                    clearInterval(gbl_Master_setInvervalLog[i]); 
                   
               }       

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

$ang.controller("MasterBloquesController", ["$scope", "$http", "AppCrud",  "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }

        // Ajustes en General de contenidos.
        $scope.listaProgramacion = {}; // vw_listaProgramas; 

        $scope.bloque = {
            ProgramacionID: 0,
            FrecuenciaTipo: 0,
            Estado: 0,
            HoraInicio: "",
            HoraFin: "",
            BloqueID: 0
        }; 

        $scope.vCrud = appCrud;

        $scope.pantallaNombre = 'Administrador de Bloques';
        $scope.listaBloques = []; 
        $scope.bBloque = ""; 
        $scope.bloques = []; 
        $scope.semanal = {}; 

        $scope.myValue = true; 

        $scope.masterTabs = {
            selected: 1,            
            setSelected: function(id){
                $scope.masterTabs.selected = id;
            }

        }; 

        // Este es el caso.        
        $scope.masterGrupo = {
            listgrupos: [],            
            data: {},
            resumen: {},
            selectedBloqueID: 0,

            AgregarGrupo: function(){

                var divht = $("<div title='Agregar Bloque'> </div>"); 

                divht.dialog(); 


            },

            setListGrupos: function(list){

                var result = [];                 

                for(var k in list){
                    if(list.hasOwnProperty(k)){
                        if(list[k].GrupoID != 1){
                        result.push(list[k]); 
                        }
                    }                    
                }

                $scope.masterGrupo.listgrupos = resultl; 
            },

            obtenerBloquesContenido: function(itm){

                var sendObj = {ProgramacionID: itm.ProgramacionID, BloqueID: itm.BloqueID }; 

                $scope.masterGrupo.selectedBloqueID = itm.BloqueID;

                http(base_url + 'Bloques/obtenerBloquesContenidoPorIDs/' , sendObj , function (res) {
                    $appSession.IsSession(res); 
                    if(res.IsOk){

                        $scope.masterGrupo.listgrupos = res.data; 
                        $scope.masterGrupo.resumen = res.resumen; 
                        $scope.masterGrupo.setListGrupos(res.resumen);
                        

                    } else {
                        alert("Error: Este Bloque no existe en el SERVER"); 

                    }

                }); 
                // El ajuste de todas la cosas.                
                console.log(itm); 

            }

        };

// $scope.frmBloque.dialog
        $scope.frmBloque = {
            dialog: null,
            selectedID: 0,
            form: {},
            modo: "C",
            Filtrar: function(BloqueID, obj) { 
              // Que han ganado el Duelo.
              $scope.filtroArr.push(BloqueID);
            },

            EditarItem: function(item){ 
                $scope.frmBloque.modo = "UPDATE";
                $scope.frmBloque.form = JSON.parse(JSON.stringify(item)); 
                $scope.AbrirDialog();
            },

            validarChoqueBloqueUpdate: function(){
                
                sendObj=  $scope.vCrud.formatObjForm($scope.frmBloque.form); 

                $.post(base_url + 'Bloques/ActualizarValidar', sendObj, function(res){

                    // Actualizacion del Boque. 
                    console.log(res);
                        if (res.IsOk) {                            
                          if('csrf' in res){
                             $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                           }

                           alert("Se actualizo Correctamente"); 

                            $scope.frmBloque.cancel();                           
                            $scope.AbrirPrograma($scope.frmBloque.selectedID);                          
                          // $("#bloqueform").dialog("close");  
                          $scope.frmBloque.dialog.dialog("close");                         
                        }
                        else {
                           if('csrf' in res){
                             $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                           }
                            alert(res.Msg); 
                        }

                        $scope.$apply();


                }, "json"); 



            }, 
            validarChoqueBloque: function(){ 

                console.log("Cpmtr"); 

                var sendObj = {ProgramacionID: $scope.frmBloque.selectedID, FrecuenciaTipo: $scope.frmBloque.form.FrecuenciaTipo, HoraInicio: $scope.frmBloque.form.HoraInicio, HoraFin: $scope.frmBloque.form.HoraFin, Estado: $scope.frmBloque.form.Estado};
                 http(base_url + 'Bloques/validarChoqueBloque/' , sendObj , function (res) {                                    

                    $appSession.IsSession(res); 
                    if(res.IsOk){
                        if(res.data){
                        // Enviar a Guardar el Formulario.                        
                          sendObj=  $scope.vCrud.formatObjForm(sendObj); 

                    $.post(base_url + 'Bloques/Crear', sendObj, function(res){
                       // overlayLoading.remove();
                       console.log(res);
                        if (res.IsOk) {
                            //  $scope.memoriacalculos.push(res.Data);
                            // $sysUtil.ShowSuccessMessage(res.Msg);
                          //  $scope.EnviarFiltro();
                          // setHash
                          if('csrf' in res){
                             $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                           }

                            $scope.frmBloque.cancel();                           
                            $scope.AbrirPrograma($scope.frmBloque.selectedID);                          

                            $scope.frmBloque.dialog.dialog("close");


                          //$("#bloqueform").dialog("close"); 
                          $scope.$apply();

                        } else {
                           if('csrf' in res){
                             $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                           }
                            console.log("Error en el Envio del Formulario.");                            
                        }
                    }, "json");

                    $scope.frmBloque.dialog.dialog("close"); 
                          // $("#bloqueform").dialog("close"); 
                        } else {
                            console.log("Esto no pudo ser"); 
                            console.log(res.msg);
                            alert(res.msg); 
                        }
                        
                //        $scope.listaBloques = res.data; 
                 //       $scope.bloques = res.bloques;                         
                    } else {

                    }                    
             });
            }, 

            guardar: function(){                

                if($scope.frmBloque.modo == "C"){

                    $scope.frmBloque.form.BloqueID = 0;
                    $scope.frmBloque.form.ProgramacionID = $scope.frmBloque.selectedID;
                }


                var val = true; 
                for(var i in $scope.frmBloque.form ){
                if($scope.frmBloque.form.hasOwnProperty(i)){
                    if(i in $scope.bloque){
                        if($scope.frmBloque.form[i] === ""){
                            val = false;  
                            console.log(i);    
                        }                         
                    }                    
                }
              }
              //  Si esta valiado.
              if($scope.frmBloque.modo == "C"){
                if(val){
                    console.log("Se creara una"); 
                    $scope.frmBloque.validarChoqueBloque(); 
                } else {
                    alert("Hay campos incompletos"); 
                }                          
              } else {
                    //Update Mode. Validacion not in 
                console.log("Este es el modo de aprendizaje.");
                $scope.frmBloque.validarChoqueBloqueUpdate();
              }              
            }, 
            cancel: function(){    
            $scope.frmBloque.dialog.dialog("close");                           
            }
        }

        $scope.panelHeader = [];   

        $scope.AbrirDialog = function() {

            if($scope.frmBloque.dialog == null ){

                $scope.frmBloque.dialog = $("#bloqueform");

                $scope.frmBloque.dialog.dialog({
                width: 605,
                 heigth: 450                  
             }); 

           $scope.frmBloque.dialog.on('dialogclose', function(event) {            

            for(var i in $scope.frmBloque.form ){
                if($scope.frmBloque.form.hasOwnProperty(i)){
                    $scope.frmBloque.form[i] = ""; 
                }
              }

            });
            } else {
// Open dialog
                $scope.frmBloque.dialog.dialog();

            }

        };

        $scope.AgregarBloque = function(){
            $scope.frmBloque.modo = "C"; 
            $scope.AbrirDialog(); 

        }; 

        $scope.filtroArr = []; 

        $scope.clearFilter = function(){
        $scope.filtroArr = [];             
        }

        $scope.inFiltro = function(ob){



            if($scope.filtroArr.length == 0){
                return true;
            }

            
            if($scope.filtroArr.length > 0) {               
                if(typeof ob !== "undefined"){
                                if("BloqueID" in ob){
                                    if($scope.filtroArr.indexOf(ob.BloqueID) !== -1){
                                       return true;                     
                                    }
                                }              
                            }

            }


            return false; 


        }


        $scope.master = function(){
            $scope.Pantalla = {nombre: 'Administrador de Bloques'};  
            $("#CanalBloque").hide();             
            $("#bloqueform").hide();             
            
        }; 

        $scope.bloquesFormat = {};


        $scope.formatBloque = function(list){
            var nformat= {}; 

            // Ordenar la Lista Por FrecuenciaTipoID
            list.sort(function (a, b) { if ( parseFloat(a.FrecuenciaTipo) > parseFloat(b.FrecuenciaTipo) ) return 1; if ( parseFloat(a.FrecuenciaTipo) < parseFloat( b.FrecuenciaTipo) ) return -1; return 0; }); 


            for(var t in list){               
                if(list.hasOwnProperty(t) ){
                    if(!(list[t].FrecuenciaTipoDesc in  nformat)){
                        nformat[list[t].FrecuenciaTipoDesc] = [];
                    }                    
                    nformat[list[t].FrecuenciaTipoDesc].push(list[t]); 

                    nformat[list[t].FrecuenciaTipoDesc].sort(function (a, b) { if (a.HoraInicio > b.HoraInicio) return 1; if (a.HoraInicio < b.HoraInicio) return -1; return 0; }); 
                }
            }

            $scope.bloquesFormat = nformat; 
        }


        $scope.AbrirPrograma = function( id ){
            // Paso Uno Desaparecer el Dialog            
             $("#myModal").hide(); 
             $("#CanalBloque").show(); 
             $scope.frmBloque.selectedID = id; 

            // Paso #2 Cargar los Bloques en Orden y Generar el contenido segun configurado
            http(base_url + 'Bloques/ObtenerBloquesGenerados/' , {ProgramacionID: id}, function (res) {                
                    $appSession.IsSession(res); 
                    if(res.IsOk){
                        $scope.listaBloques = res.data; 
                        $scope.bloques = res.bloques;  
                        $scope.formatBloque(res.bloques); 
                    }                    
             });
        };
        
}]);



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

            if("EsRegular" in copiObj){
                if(copiObj.EsRegular == 1){
                    copiObj.EsRegular = true;
                } else {
                    copiObj.EsRegular = false;
                }
            }


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
         $scope.dropzoneFields = {}; 

         $scope.liveDisp = JResentDis; 

         $scope.isOnline = function(mac){
            if(mac in $scope.liveDisp){
                return true; 
            }
            return false;
         }; 

         $scope.validateOnline = function(mac){
            if($scope.isOnline(mac)){
                return "til-online"; 
            }
            return "til-offline"; 
         };

         $scope.SendDobleTocken = function(obj){
             if($scope.isOnline(obj.Mac)){
                // 
                console.log("Enviar Mensaje al Web-Socket");                 

             }
         }


         $scope.sortableOptions =
          {
            connectWith: ".sortable1-cont",
            start: function (e, ui) {  
                $('.sortable1-cont').sortable('refresh');
            },
    
            update: function (e, ui) {
                  if (ui.item.sortable.droptarget[0].classList[0] !== "sortable1-cont"){
                    ui.item.sortable.cancel();
                    return false; 
                  }                    

                var contID = ui.item.sortable.droptarget[0].id.split("-"); 
                if(contID.length > 0){
                    if($scope.dropzoneFields[contID[1]].length == 1){
                    ui.item.sortable.cancel();                        
                    return false;
                    } 
                }

                // Envio de los Datos para Registrar Vinculo.
              http(base_url + 'FuerzaVentaDispositivo/registraRelacion', {"dispositivoID": contID, "GUID_FV": ui.item.sortable.model.GUID_FV }, function (dt) {                
                     res = dt; 

                  if(res.IsOk){
                   // console.log("Todo Ok"); 
                  } else {
                   // console.log("Entrada de Pantajo. No Ok"); 
                  }                  
             });
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


        $scope.ordenarDispositivoPorEstatusLine = function(){
            //  $scope.liveDisp.            
            for(var mc in $scope.liveDisp){
                if( $scope.liveDisp.hasOwnProperty(mc)){

                   var $disp = $scope.listaDispositivo.filter(function(d){                    
                        return d.Mac.toString() === mc; 
                    });

                    var $ind =  $scope.listaDispositivo.indexOf($disp[0]);
                    // Indice en General.                     
                    if($ind !== -1){
                        $scope.listaDispositivo.splice($ind, 1); 
                        $scope.listaDispositivo.unshift($disp[0]);                         
                    } else {
                            console.log("Aqui hay que Agregar un Nuevo Dispositivo que no Exite.");
                            console.log($scope.liveDisp[mc]);                                        
                            $scope.listaDispositivo.unshift({DispositivoID: $scope.liveDisp[mc].DispositivoID, Mac: mc, Nombre: "Nuevo Dispositivo #" + $scope.listaDispositivo.length , Descripcion: "Dispositivo sin Registrar", Estado: "1"});
                            $scope.dropzoneFields[$scope.liveDisp[mc].DispositivoID] = []; 
                    }
                }
            }
        }; 

        $scope.consultarDispositivoOnline = function(){
            http(base_url + 'FuerzaVentaDispositivo/obtenerDispositivoOnline', {}, function (res) {                                    
                  if(res.IsOk){
                  //  console.log("Se elimino OK"); 
                  $scope.liveDisp = res.data; 
                  $scope.ordenarDispositivoPorEstatusLine(); 
                  } else {                    
                  }                  
             });

        }; 


        $scope.formatZoneField = function(){
            // Set Key  (DispositivoID) asignate val(obj(FuerzaVenta)) 
            for(ky in JSONData){
                if(JSONData.hasOwnProperty(ky)){
                    if(!(JSONData[ky].DispositivoID in $scope.dropzoneFields)){
                        $scope.dropzoneFields[JSONData[ky].DispositivoID] = [];                         

                    }                    
                }
            }
                 

             for(var k in JFData){
                if(JFData.hasOwnProperty(k)){
                   var tmpArr = JFData[k]; 

                    for (var i = tmpArr.length - 1; i >= 0; i--) {

                         if(tmpArr[i].DispositivoID !== null){

                        if(!(tmpArr[i].DispositivoID in $scope.dropzoneFields )){
                            $scope.dropzoneFields[tmpArr[i].Nivel] = []; 
                        }

                        $scope.dropzoneFields[tmpArr[i].DispositivoID].push(tmpArr[i]); 
                        tmpArr.splice( i, 1 );                         
                     } 
                    }
                }
             }   

        }; 



        $scope.formatZoneField(); 

        $scope.eliminarVinculoFV = function(dispositivo, FV){

            // Eliminar los dispositivo.
            

            $scope.dropzoneFields[dispositivo.DispositivoID] = []; 
            $scope.listaFuerzaVentaCopy[FV.Nivel].push(FV);           

             http(base_url + 'FuerzaVentaDispositivo/eliminarRelacion', {"dispositivoID": dispositivo.DispositivoID, "GUID_FV": FV.GUID_FV }, function (dt) {                

                    res = dt; 
                  if(res.IsOk){
                  //  console.log("Se elimino OK"); 
                  } else {
                    console.log("Entrada de Pantajo No Ok"); 
                  }                  
             });
        }

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

        $scope.validarFvSelected = function(obj){

            for (var i = obj.length - 1; i >= 0; i--) {
                if(obj[i].Nivel == $scope.vCrud.form.Nivel){
                    return true; 
                }                
            }
            return false; 
        }; 


        $scope.clickAutoSearch = function(dispositivoID){
            $aaa = JSONData.filter(function(x){ return x.DispositivoID == dispositivoID})[0];

            if("Nombre" in $aaa){
                $scope.buscarLista = $aaa.Nombre.toString();              
            }

        }; 


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
                 var refreshIntervalId = setInterval(function(){$scope.consultarDispositivoOnline(); }, 90000);
                 $scope.consultarDispositivoOnline();
                 gbl_Master_setInvervalLog.push(refreshIntervalId); 
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


$ang.controller("PlanConfigController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }
        var form = {            
        };
        $scope.listaPlanConfig =[]; 
        $scope.pantallaNombre = 'Registro PlanConfig';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.vCrud.initt({url: base_url + 'PlanConfig/Obtener', 
            'callback': function(res, num){
                $scope.ObtenerPaginacionRes(res, num);     
                $scope.$apply();
            }, 
            'searchUrl':  base_url + 'PlanConfig/Buscar'
        });

        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaPlanConfig = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'PlanConfig'};            
             http(base_url + 'PlanConfig/Obtener', {}, function (dt) {                
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
                    http(base_url + 'PlanConfig/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'PlanConfig/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'PlanConfig/Obtener', {}, function (dt) {
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

        $scope.objetivos = {}; 

        $scope.BuscarPendiente = function(){
            // El cambio de los objetivos.
            switch($scope.objetivos.Tipo){
                case "1": 
                // General.
                $("#content-objs").hide();                 

                break; 

                case "2": 
                //  Especiicos.
                $("#content-objs").show(); 
                
                break; 

            }

        }; 

        $scope.Eliminar = function(item, indice){            

            var iObj =  $scope.vCrud.formatObjForm(item); 
            //------------------------------------------
             $.post(base_url + 'PlanConfig/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaPlanConfig.splice(indice, 1); 
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

        $scope.guardarObjetivo = function(){
            // Envio del Objetivo           

        }; 

        $scope.cerrarDialog = function(){
            $('#objetivoFormulario').dialog('close');

        }

        $scope.agregarobjetvo = function(){

            // Colocarn en DIalog BOX. (el evento)
            $("#objetivoFormulario").dialog({
                width: "716", 
                title: "Agregar Objetivo"
            }); 




        }

        $scope.Guardar = function(){
            if(!$scope.vCrud.validate()){
                return false; 
            }

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear
            $.post(base_url + 'PlanConfig/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaPlanConfig.push(res.data);
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

            $.post(base_url + 'PlanConfig/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaPlanConfig[$scope.vCrud.selectedIndex]= res.data;
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

