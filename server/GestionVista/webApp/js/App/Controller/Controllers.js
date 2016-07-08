var Glbquery ={}; 

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
                    $scope.$apply();                  
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
                    $scope.$apply();                
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
        $scope.listaTemplatePages = [];
        $scope.pantallaNombre = 'Registro Contenido';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        $scope.vCrud.setForm(form); 

        $scope.esquemaHtml = "";         

// seccionTemp.listSeccion
        $scope.seccionTemp = {

            modo: 0, 
            form: {},
            $Form: {},  
            listSeccion: {pos_1: {}, pos_2: {}, pos_3: {}, pos_4: {}, pos_5: {}, pos_6: {}, pos_7: {}
            }, 


            limpiar: function(){               

                for(var i in $scope.seccionTemp.form){      
                    if($scope.seccionTemp.form.hasOwnProperty(i) ){                    
                            $scope.seccionTemp.form[i] =  "";
                    }
                }

                  if ($scope.seccionTemp.$Form["Second"].hasOwnProperty("$setPristine"))
                   {
                            $scope.seccionTemp.$Form["Second"].$setPristine();
                   }

            }, 

            agregar: function(num, obj){
                // Aqui va el contenido.
                if (typeof obj === 'undefined' || obj === null){
                    $scope.seccionTemp.limpiar(); 
                    // console.log(obj);                                     
                    $scope.seccionTemp.modo= 0;
                    $scope.seccionTemp.form.Posicion = num; 

                } else {
                    
                    $scope.seccionTemp.modo = 1;
                    $scope.seccionTemp.form = JSON.parse(JSON.stringify(obj));                                        
                    $scope.seccionTemp.form.Posicion = num; 
                }

                $("#myFormSeccionTemp").modal('toggle');
            }, 

            guardar: function(){

                if($scope.seccionTemp.modo == 0){
                    // Registrar
                    $scope.seccionTemp.form.TemplatePagesID = $scope.wizard.selectedTemplatePagesID;                    
                    var sendO = $scope.vCrud.formatObjForm($scope.seccionTemp.form);
                    $.post(base_url + 'SeccionTemplate/Crear', sendO, function(res){                
                        $appSession.IsSession(res);

                        if (res.IsOk){    
                             $('#myFormSeccionTemp').modal('hide');
                             $scope.seccionTemp.limpiar(); 

                             $scope.wizard.obtenerSeccionFuentes($scope.wizard.selectedTemplatePagesID);
                             $scope.$apply();


                        } else {                    
                            alert(res.Msg);                     
                        }

                        if('csrf' in res){
                                $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                                
                        }


                    }, 'json').fail(function() {
                        alert('Erro en el Servicio 500'); 
                    });

                } 

                if($scope.seccionTemp.modo == 1){
                    // Modific
                    var sendO = $scope.vCrud.formatObjForm($scope.seccionTemp.form);

                    $.post(base_url + 'SeccionTemplate/Actualizar', sendO, function(res){                
                        $appSession.IsSession(res);

                        if (res.IsOk){                            
                            
                            // Aplicando los Ajustes.                            
                            $('#myFormSeccionTemp').modal('hide');
                             $scope.seccionTemp.limpiar(); 

                            $scope.wizard.obtenerSeccionFuentes($scope.wizard.selectedTemplatePagesID);

                            $scope.$apply();       
                            

                        } else {                    
                            alert(res.Msg);                     
                        }

                        if('csrf' in res){
                                $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                        }

                    }, 'json').fail(function() {
                        alert('Erro en el Servicio 500'); 
                    });                    
                } 
                
                 

            }
        };

        $scope.generarBosetoEsquema= function(descripcion){

                http(base_url + 'TemplatePages/obtenerTablaEsquemaID', {EsquemaTipo: descripcion}, function (dt) {                    
                    $scope.esquemaHtml = dt;                    
                 });
            
        }; 

        $scope.wizard = {
            form: {},
            $Fom: {},  
            modo: 0,
            validado: false,
            posicion: 1, 
            selectedIndex: 0,
            selectedTemplatePagesID: 0,

            reset: function(){
                
                for(var i in $scope.wizard.form){      
                    if($scope.wizard.form.hasOwnProperty(i) ){                    
                            $scope.wizard.form[i] =  "";
                    }
                }
            }, 

            unsetjQueryPosition: function(){
                var arr = ["pos_1", "pos_2", "pos_3", "pos_4", "pos_5", "pos_6", "pos_7"]; 
                for (var i = arr.length - 1; i >= 0; i--) {
                    $("#" + arr[i]).html(""); 
                }
                 
            }, 

            renderjQueryPosicion:function(){

                for(var i in $scope.seccionTemp.listSeccion){
                    if($scope.seccionTemp.listSeccion.hasOwnProperty(i)){

                        var FuenteDecTXT = ($scope.seccionTemp.listSeccion[i].FuenteID in vw_listFuentes)? vw_listFuentes[$scope.seccionTemp.listSeccion[i].FuenteID].Descripcion: "N/A"; 

                        var title = '<div class="darkblue-panel"> <div class="darkblue-header"><h5>'+  $scope.seccionTemp.listSeccion[i].Encabezado +  '</h5></div> <p class="mt"><b>' + FuenteDecTXT  + '</b><br>Consultar Fuente</p></div>'; 
                        $("#" + i).html(title); 
                    }
                }
            },

            obtenerSeccionFuentes: function(TemplatePagesID){

                 // Buscar objetso en Seccion.
                 http(base_url + 'SeccionTemplate/obtenerSecionTempatePorTempateID', {TemplatePagesID: TemplatePagesID}, function (dt) {                    
                    $appSession.IsSession(dt);

                    if (dt.IsOk){
                        $scope.seccionTemp.listSeccion = dt.data; 
                        $scope.wizard.renderjQueryPosicion(); 
                    }                    
                 });
            }, 

            CargarSeccionesFuentes: function(objeto){

                $scope.wizard.unsetjQueryPosition(); 
                $scope.generarBosetoEsquema($scope.wizard.ObtenerEsquemaNombre(objeto.EsquemaTipo));

                $scope.wizard.selectedTemplatePagesID = objeto.TemplatePagesID;
                $scope.wizard.obtenerSeccionFuentes(objeto.TemplatePagesID); 

            }, 

            setPosicion: function(id){
                $scope.wizard.posicion = id;

                if(id > 1){
                    $("#btn-next").hide();     
                } else {
                    $("#btn-next").show();                         
                }
            }, 

            validar: function(){
                // Validar.
                    for (var form in $scope.wizard.$Form) {
                if ($scope.wizard.$Form[form].hasOwnProperty("$invalid") && $scope.wizard.$Form[form].$invalid) {

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
                //
            },

            mostrarEsquema: function(){

                for(var i in vw_listEsquemaTipo){

                    if(vw_listEsquemaTipo.hasOwnProperty(i)){
                        if(parseInt(vw_listEsquemaTipo[i]) == parseInt($scope.wizard.form.EsquemaTipo)){
                            return '<img height="138" src="../webApp/img/esqm_' + i.toString().trim() + '.png" style="margin-left: 82px; margin-top: 10px;" >';
                        }
                    }
                }
                return ""; 
            },

            llenar: function(obj, index){
                     
                var copiObj = JSON.parse(JSON.stringify(obj));   
                $scope.wizard.form = copiObj; 
                $scope.wizard.selectedIndex = index; 
                $scope.wizard.modo = 1;                
            },

            ObtenerEsquemaNombre: function(id){

                  for(var i in vw_listEsquemaTipo){
                    if(vw_listEsquemaTipo.hasOwnProperty(i)){
                        if(parseInt(vw_listEsquemaTipo[i]) == parseInt( id.toString() )){
                            return i.toString().trim();
                        }
                    }
                } 

            },

            ObtenerEsquemaPorID: function(obj, indx){
                                
                if(obj.Posicion == null){
                    obj.Posicion = (parseInt(indx) + 1); 
                }

                for(var i in vw_listEsquemaTipo){
                    if(vw_listEsquemaTipo.hasOwnProperty(i)){
                        if(parseInt(vw_listEsquemaTipo[i]) == parseInt(obj.EsquemaTipo.toString() )){
                            return i.toString().trim();
                        }
                    }
                } 

                return "N/A"; 
            }, 

            guardarTemplatePages: function(){

                if($scope.wizard.modo == 0){
                    // Crear.
                    $scope.wizard.form.SliderMaestroID = $scope.vCrud.form.SliderMaestroID; 
                    $scope.wizard.form.Posicion = (parseInt($scope.listaTemplatePages.length) + 1); 
                    var sendO = $scope.vCrud.formatObjForm($scope.wizard.form);
                    
                    if(!$scope.wizard.validar()){
                        return false; 
                    }


                    $.post(base_url + 'TemplatePages/Crear', sendO, function(res){                
                        $appSession.IsSession(res);

                        if (res.IsOk){
                            $scope.listaTemplatePages.push(res.data);                                        
                            $scope.$apply();       
                            // Aplicando los Ajustes.
                            $('#myModal').modal('hide');
                            $scope.wizard.reset(); 

                        } else {                    
                            alert(res.Msg);                     
                        }

                        if('csrf' in res){
                                $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                        }

                    }, 'json').fail(function() {
                        alert('Erro en el Servicio 500'); 
                    });   

                } else {
                    // Modificar.

                    $scope.wizard.form.SliderMaestroID = $scope.vCrud.form.SliderMaestroID;  

                    $scope.wizard.form.Posicion = (parseInt($scope.wizard.selectedIndex) + 1); 

                    var sendO = $scope.vCrud.formatObjForm($scope.wizard.form);



                       $.post(base_url + 'TemplatePages/Actualizar', sendO, function(res){                
                        $appSession.IsSession(res);

                        if (res.IsOk){
                            $scope.listaTemplatePages[$scope.wizard.selectedIndex] = res.data;                                        
                            $scope.$apply();       
                            // Aplicando los Ajustes.
                            $('#myModal').modal('hide');
                        } else {                    
                            alert(res.Msg);                     
                        }

                        if('csrf' in res){
                                $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                        }

                    }, 'json').fail(function() {
                        alert('Erro en el Servicio 500'); 
                    });   



                }
            }
        }; 




$scope.AgregarTemplate = function(){    // 
     $scope.wizard.modo = 0;
     // Reset.
     $scope.wizard.reset(); 
}


$scope.anteriorCursor = function(){

    if ($scope.wizard.posicion > 1){
            $scope.wizard.posicion--;   

    }    

    if($scope.wizard.posicion < 2){
         $("#btn-next").show();
    }
}; 
        // Primer formulario
        $scope.nextForm= function(){

            if($scope.wizard.posicion == 1){
                    switch($scope.vCrud.modo) {
                        case 0:  // Esto es Guardar.
                        if(!$scope.vCrud.validate()){
                            $scope.wizard.validado = false;
                            return false; 
                        }
                        $scope.wizard.validado = true;
                        // Guardar
                        $scope.guardarContenido(); 
                        break;
                        case 1:  // Esto es Modificacion.

                         $scope.wizard.validado = true;                        
                        $scope.actualizarContenido(); 
                        break;
                    }
                    $scope.wizard.posicion = 2; 
                    $("#btn-next").hide();
            }

            
        };
        // 

$scope.actualizarContenido = function(){    
    var sendForm = $scope.vCrud.getForm();

      $.post(base_url + 'Contenido/Actualizar', sendForm, function(res){
                $appSession.IsSession(res);                
                if (res.IsOk){

                    $scope.listaContenido[$scope.vCrud.selectedIndex]=  JSON.parse(JSON.stringify($scope.vCrud.form)); // res.data; 
                    $scope.vCrud.setForm(res.data);                   
                    $scope.$apply();                                       
                } else {                    
                    alert(res.Msg);                     
                }

                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }

            }, 'json').fail(function() {
                alert('Erro en el Servicio 500'); 
            });      
};


$scope.guardarContenido = function(){    
    var sendForm = $scope.vCrud.getForm();
    $.post(base_url + 'Contenido/Crear', sendForm, function(res){        
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                    
                if (res.IsOk){

                    $scope.listaContenido.push(res.data);                    
                    $scope.vCrud.setForm(res.data);
                    $scope.vCrud.modo = 1;                     
                    $scope.$apply();

                    // YO vivo por Lei.

                            
                } else {
                    // Reasignacion de Tokens.
                    alert(res.Msg);                     
                }
                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }
            }, 'json');

}; 


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

            console.log(copiObj); 

            $scope.vCrud.setForm(copiObj);            
            $scope.vCrud.selectedIndex = index;  

            // LLenado de las conspiraciones.
            // PAra los templates. 
            $scope.listaTemplatePages = []; 

            http(base_url + 'TemplatePages/ObtenerPorIDSliderMaestro', {id: copiObj.SliderMaestroID}, function (dt) { 
                    $appSession.IsSession(dt); 
                    $scope.listaTemplatePages = dt.data; 
                    if(dt.data == null){
                        $scope.listaTemplatePages = []; 
                    }
             });  
            


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
                    $scope.$apply();
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
                    console
                    $scope.listaContenido[$scope.vCrud.selectedIndex]= JSON.parse(JSON.stringify($scope.vCrud.form));

                    console.log(res.data);

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



        $scope.temp_Prvw = ""; 

        $scope.htmls = '<img src="../webApp/img/ppt.png" class="img-circle-op" width="65%" height="126">'; 

        $scope.PreviewHTMLElement = function (itm){            

            switch(parseInt(itm.FuenteTipo)){
                case 1:
                return ""; 
                break;

                case 4:

                return '<img src="../webApp/img/excel.png" class="img-circle-op" width="65%" height="126">';
                break; 

                case 5:

                if("ContenidoTexto" in itm){
                    var jqText = $(itm.ContenidoTexto); 
                       var listOfImg = jqText.find("img"); 

                       if(listOfImg.length > 0 ){
                            var txtImg = $(listOfImg[0]).html(); 
                            var objTxtImg= $(txtImg);
                            return '<img src="' + listOfImg[0].src +'" class="" width="65%" height="126">';
                       }
                }    

                return '<img src="../webApp/img/ppt.png" class="img-circle-op" width="65%" height="126">';
                break;



            }



        }; 

        $scope.PreviewOfficeHTML = function(){

            // Preliminar de conversion del Html y los Excel. 
            $("#url_preview").html(""); 
            url = $scope.vCrud.form.Url;
            if($scope.vCrud.form.FuenteTipo == 3){
                $.getJSON(url, function(res){
                    var responseJSON = res; 
                    var dt = datatransformer.new( responseJSON.data, responseJSON.config),
                    visuals = responseJSON.config.visuals;
                    dt.generateVisual(visuals[0].visualType,visuals[0].visualOptions,'url_preview' ).render();

                });
                // El BISChar
                


                return false; 
                
            }



            $.get(url, function(rHtml){
                // Espera Mensaje @!                
                $scope.temp_Prvw = rHtml;

            if($scope.vCrud.form.FuenteTipo == 5){

                Glbquery = $($scope.temp_Prvw); 
                var listImg = Glbquery.find("img");
                var txtAppend = ""; 
                
                for (var it = 0; it < listImg.length; it++) {
                     if(listImg.hasOwnProperty(it)){
                            if(typeof listImg[it] !== "undefined" && ("src" in listImg[it]) ){
                                    $("#url_preview").append('<img src="' + listImg[it].src +'" style = "height: 145px; margin: 8px;">');
                            }                            
                       //     var temp =  $(listImg[it]).attr("style", "height: 145px; margin: 8px;"); 
                        //    $("#url_preview").append(temp);
                        }
                }

                                    

            }

            if($scope.vCrud.form.FuenteTipo == 4) {
                    $("#url_preview").html("<div id='excel-vw_vw' style='background-color: white;'>" + $scope.temp_Prvw + "</div>"); 
            }               

            }).fail(function() {
                alert('Erro en el Servicio, no pudo encontrar el Documento'); 
            });       
                            
        }

        $scope.Guardar = function(){            

        switch($scope.vCrud.modo) {
            case 0: // Nuevo Crear

            if(!$scope.vCrud.validate()){
                return false; 
            }

            $.post(base_url + 'Fuentes/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaFuentes.push(res.data);
                    $scope.vCrud.reset(); 
                    $scope.$apply();                   
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
                    $scope.$apply();                   
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
                    $scope.$apply();                   
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

        $scope.listaContenido = vw_contenidos; // []; // vw_contenidos
        $scope.first = true;

        $scope.dropzone = {};

        $scope.isLabelActive = function(i){

            if($scope.first){
                if(i == 1){
                   return  "active";
                   $scope.first = false;
                }
                return "";     
            }

            return ""; 

            
            

        }



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

        $scope.verCreer = function(a){
            console.log(a);

        };

        // Este es el caso.        
        $scope.masterGrupo = {
            listgrupos: vw_listaGrupos, 
            form: {},
            data: {},
            resumen: {},
            selectedGrupoID: 0, 
            selectedBloqueID: 0,
            selectedBloque: {}, 
            hasChanges: false, 
            AgregarGrupo: function(id){
                $scope.masterGrupo.selectedGrupoID = id;
                var divht = $("#grupoform"); 
                divht.dialog(
                {
                 width: 615,
                 heigth: 485,
                  modal: true
                 });
            },

            isContenidoInBloque: function(obj){

                if($scope.masterGrupo.selectedGrupoID != 0){

                    if(typeof $scope.masterGrupo.data[$scope.masterGrupo.selectedGrupoID] === 'undefined'){
                        $scope.masterGrupo.data[$scope.masterGrupo.selectedGrupoID] = []; 
                    }


                    $restCont =  $scope.masterGrupo.data[$scope.masterGrupo.selectedGrupoID].filter(
                    function(d){                    
                        return d.ContenidoID.toString() === obj.ContenidoID.toString(); 
                    } );

                    if($restCont.length > 0 ){
                        return false;
                    }
                return true;
                } 

                return true;

            },

            validateHasChanges: function(idGrupo){

                var hasChanges = false; 

                for(var index in $scope.masterGrupo.data[idGrupo] ){                    
                    if($scope.masterGrupo.data[idGrupo].hasOwnProperty(index)){                        
                        var ii = parseInt(index) + 1; 

                        var orden = parseInt($scope.masterGrupo.data[idGrupo][index].Orden); 
                        if(ii != orden) {
                            hasChanges = true; 
                        }
                    }
                }

                return hasChanges; 
            },

            guardarCambioOrden: function(idGrupo){
                // Como hacer que se guarde el orden.
                $listCambios = []; 
                 for(var index in $scope.masterGrupo.data[idGrupo] ){                    
                    if($scope.masterGrupo.data[idGrupo].hasOwnProperty(index)){                        
                        var ii = parseInt(index) + 1; 
                        var orden = parseInt($scope.masterGrupo.data[idGrupo][index].Orden); 
                        if(ii != orden) {                            
                            $listCambios.push( {BloqueContenidoID: $scope.masterGrupo.data[idGrupo][index].BloqueContenidoID,
                             Orden: ii, 
                             ProgramacionID: $scope.masterGrupo.selectedBloque.ProgramacionID, 
                             BloqueID: $scope.masterGrupo.selectedBloqueID});
                        }
                    }
                }

                sendObj=  $scope.vCrud.formatObjForm({lista: $listCambios}); 

                // Ajustar cambios en el servidor.
                 $.post(base_url + 'BloqueContenido/CambiarOrden', sendObj, function(res){                    
                        if (res.IsOk) {
                          if('csrf' in res){
                             $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                           }                           

                          $scope.masterGrupo.data = res.data;
                          $('[data-toggle="tooltip"]').tooltip();

                          alert("Exitoso");
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
            

            AgregarGuardarContenido: function(obj){    

                if(typeof $scope.masterGrupo.data[$scope.masterGrupo.selectedGrupoID] === 'undefined'){
                        $scope.masterGrupo.data[$scope.masterGrupo.selectedGrupoID] = []; 
                }            
                
               var sndcont = {BloqueID: $scope.masterGrupo.selectedBloqueID,
                              ContenidoID:  obj.ContenidoID, 
                              GrupoID: $scope.masterGrupo.selectedGrupoID, 
                              Orden: ($scope.masterGrupo.data[$scope.masterGrupo.selectedGrupoID].length + 1),
                              Estado: 1,
                            ProgramacionID: $scope.masterGrupo.selectedBloque.ProgramacionID
                               }; 
                
                sendObj=  $scope.vCrud.formatObjForm(sndcont); 
                 


                // Post del formulario.
                 $.post(base_url + 'BloqueContenido/Crear', sendObj, function(res){
                    
                    console.log(res);
                        if (res.IsOk) { 

                          if('csrf' in res){
                             $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);                        
                           }                           

                            $scope.masterGrupo.data = res.data; 
                            $scope.masterGrupo.resumen = res.resumen;                      
                          $('[data-toggle="tooltip"]').tooltip();    

                          alert("Exitoso"); 
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

            removeContent: function(itm, index, grupoID){
                // Remover contenido.
                console.log(itm); 
                console.log(index); 
                console.log("Remover"); 

                

                 http(base_url + 'Bloques/EliminarBloqueContenido/' , {BloqueContenidoID: itm.BloqueContenidoID, BloqueID: itm.BloqueID, ProgramacionID: $scope.frmBloque.selectedID },
                  function (res) {
                    $appSession.IsSession(res); 
                    if(res.IsOk){

                        $scope.masterGrupo.data[grupoID].splice(index, 1);                         
                           $scope.masterGrupo.resumen = res.data; 
                        alert("Se eliminó correctamente.");                        
                      

                    } else {
                        alert("Error: Este Bloque no existe en el SERVER"); 
                    }
                });


            },

            SubirOrden: function(itm, index){
                console.log("Subir"); 

            },

            BajarOrden: function(itm, index){
                console.log("Bajar"); 
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

                $scope.masterGrupo.listgrupos = result; 
            },

            obtenerBloquesContenido: function(itm){

                var sendObj = {ProgramacionID: itm.ProgramacionID, BloqueID: itm.BloqueID }; 

                $scope.masterGrupo.selectedBloqueID = itm.BloqueID;
                $scope.masterGrupo.selectedBloque = JSON.parse(JSON.stringify(itm)); 

                http(base_url + 'Bloques/obtenerBloquesContenidoPorIDs/' , sendObj , function (res) {
                    $appSession.IsSession(res); 
                    if(res.IsOk){

                        $scope.masterGrupo.data = res.data; 
                        $scope.masterGrupo.resumen = res.resumen;

                        console.log($scope.masterGrupo.resumen.length); 
                      //  $scope.masterGrupo.setListGrupos(res.resumen);
                          $('[data-toggle="tooltip"]').tooltip();


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
                    $scope.$apply();                   
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
                    $scope.$apply();                   
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
                    $scope.$apply();                   
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
                    $scope.$apply();                    
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
                    $scope.$apply();                   
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

 $ang.controller("GrupoTVController", ["$scope", "$http",  "AppCrud", "AppHttp","AppMenuEvent", "$compile", "AppSession", function ($scope, $http, appCrud, appHttp,appMenuEvent, $compile, $appSession) {
        function http(url, data, callback) {
            appHttp.Get(url, data, callback); 
        }



        var form = {            
        };

        $scope.masterGroup = {grupoID: 0 }; 

        $scope.listaGrupoTv = {}; 
        $scope.listaGrupos = []; 
        $scope.pantallaNombre = 'Registro GrupoTv';
        $scope.buscarLista = '';
        $scope.vCrud = appCrud;
        // $scope.vCrud.setForm(form);     



        $scope.hasTV = function(itm){

            if(itm.DispositivoID == null){ 
            $('[data-toggle="tooltip"]').tooltip();                                
                return false; 
            }
            return true; 
        };

        $scope.tempLista = []; 

        $scope.notChecked = function(itm){

            if($scope.masterGroup.GrupoID in $scope.listaGrupoTv ){
                var array = $scope.listaGrupoTv[$scope.masterGroup.GrupoID]; 

                if(array.indexOf(itm) !== -1){
                    return false;
                }   
                
                
               var fnElmnt =  array.filter(function(a){
                    return a.GUID_FV === itm.GUID_FV; 
                 }); 

               if(fnElmnt.length > 0){
                return false; 
               }
                

            }
            return true; 
        }

        $scope.addToList = function(itm){
            // Envio del la Formula al controlador. Uno po Uno.                      
                    console.log(itm);  
                    var sendOb = {DispositivoID: itm.DispositivoID, GrupoID: $scope.masterGroup.GrupoID};
                    http(base_url + 'GrupoTv/AgregarGrupoTV', sendOb, function (dt) {  

                    $appSession.IsSession(dt);          

                    if(dt.IsOk){                                                      
                        $scope.listaGrupoTv = dt.data;                          
                    }                                  
                    });
        };

        $scope.EliminarToList = function(itm){
            // Envio del la Formula al controlador. Uno po Uno.
                     
                    var sendOb = {DispositivoID: itm.DispositivoID, GrupoID: itm.GrupoID};
                    http(base_url + 'GrupoTv/EliminarGrupoTV', sendOb, function (dt) {  

                    $appSession.IsSession(dt);  
                    if(dt.IsOk){
                        $scope.listaGrupoTv = dt.data; 
                    }                                       
                    
                    });
        };

        $scope.guardarLista = function(){

        }; 

        $scope.selectAll = function(){

        }; 





        $scope.ObtenerPaginacionRes = function(res, num){            
            if(res.IsOk){

                    $scope.listaGrupoTv = res.data; 
                    $scope.vCrud.setPages({totalResult: res.totalResult, count: res.count, maxRowsPage: res.rowsPerPages}); 

                } else {
                // Mensaje de noticicaicon de erroes, normalizado y limpio.                                                    
                    console.log('Uno un Error');
                }
        }

        $scope.getCantidadSelected = function(){

            if($scope.masterGroup.GrupoID in  $scope.listaGrupoTv){
                return $scope.listaGrupoTv[$scope.masterGroup.GrupoID].length;
            } 
                return 0; 
        }; 

        

        $scope.initt = function () {

            $scope.Pantalla = {nombre: 'GrupoTv'};  
            $scope.listaFuerzaVentaCopy = JSON.parse(JSON.stringify(JFData));  
            $scope.listaGrupoTv = vw_listaGrupoTv;
                   

             http(base_url + 'GrupoTv/ObtenerDatos', {}, function (dt) {                
                    $appSession.IsSession(dt);                                         

                    $scope.listaGrupos = dt.listaGrupos; 
                    // $scope.ObtenerPaginacionRes(dt); 

             });
        };        

        $scope.Buscar = function(cEvent){ 
        if($scope.buscarLista != '') {

             switch(cEvent.type ){
                case 'keypress':
                 if(cEvent.keyCode == 13){
                        $scope.vCrud.$Search.send = true; 
                        $scope.vCrud.$Search.w = $scope.buscarLista; 
                    http(base_url + 'GrupoTv/Buscar/' + $scope.buscarLista , {}, function (dt) {   
                            $appSession.IsSession(dt);                            
                           $scope.ObtenerPaginacionRes(dt);                                
                    });    
                 }
                break;
                case 'click':

                $scope.vCrud.$Search.send = true;
                http(base_url + 'GrupoTv/Buscar/' + $scope.buscarLista, {},
                 function (dt) { 
                        $appSession.IsSession(dt);                          
                           $scope.ObtenerPaginacionRes(dt, null);                                
                });    
                break;
             }
        } else {
                $scope.vCrud.$Search.send = false;                                                        
                $scope.vCrud.$Search.w= ""; 
                http(base_url + 'GrupoTv/Obtener', {}, function (dt) {
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
             $.post(base_url + 'GrupoTv/Eliminar', iObj, function(res){
                // Validacion de Sessiones.
                $appSession.IsSession(res);                                                                         
                if (res.IsOk){
                    $scope.listaGrupoTv.splice(indice, 1); 
                    $scope.$apply();
                    // TODO: @MensajeEliminacion;
                } else {
                    // TODO: @MensajeEliminacion;
                    // Notifiacion de mensaje de Error.
                    // alert(res.Msg);
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
            $.post(base_url + 'GrupoTv/Crear', $scope.vCrud.getForm(), function(res){
                    // Ajustes del Json. Respuesta del Formulario.
                    $appSession.IsSession(res);                                      


                if (res.IsOk){
                    $scope.listaGrupoTv.push(res.data);
                    $scope.vCrud.reset();  
                    $scope.$apply();                 
                } else {
                    // Reasignacion de Tokens.
                  //  alert(res.Msg);                     
                }
                if('csrf' in res){
                        $scope.vCrud.setHash(res.csrf.name, res.csrf.hash);
                }
            }, 'json');

            break;
            case 1: // Actualizar Existe 

            $.post(base_url + 'GrupoTv/Actualizar', $scope.vCrud.getForm(), function(res){
                $appSession.IsSession(res); 

                if (res.IsOk){
                    $scope.listaGrupoTv[$scope.vCrud.selectedIndex]= res.data;
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

