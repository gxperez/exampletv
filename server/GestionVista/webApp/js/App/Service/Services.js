var b;

(function () {
    var $ang = appAng;

    var $app = new app();
    var $sys = $app.System;
    var $tool = $app.Tool;
    var $format = $app.Format; //ObjectcleanValue
    var $sysCrud = $sys.Crud;
    var $sysEnum = $sys.Enum.CrudState;
    var $sysconfig = $sys.Config;
    var $sysUtil = $sys.Utility;
    var $smt = $sys.Enum.MessageType;

    var dependsFilter = function (collection, filtros) {
        console.log("El Filtro");
        console.log(filtros);
        var output = [],
        keys = [];
        temp = [];
        temp = collection;
        inicital = {};
        if (filtros.length == 0) {
            output = [];
            output = temp;
            inicital = { Categoria: "TODOS", CodigoOriginal: "TODOS", Descripcion: "TODOS", Empaque: "TODOS", Envase: "TODOS", Estatus: "Activo", FechaInicio: "2004-12-29T00:00:00", Marca: "TODOS", Presentacion: "TODOS", ProductoID: 0, SKU: "TODOS", Tipo: "TODOS", Vendible: "Si" };
            inicital["val"] = "TODOS";
            inicital["Label"] = "TODOS";
            //            if(inicital.CodigoOriginal)
            output.unshift(inicital)
            return output;
        }
        for (var j in filtros) {
            output = [];
            inicital = { Categoria: "TODOS", CodigoOriginal: "TODOS", Descripcion: "TODOS", Empaque: "TODOS", Envase: "TODOS", Estatus: "Activo", FechaInicio: "2004-12-29T00:00:00", Marca: "TODOS", Presentacion: "TODOS", ProductoID: 0, SKU: "TODOS", Tipo: "TODOS", Vendible: "Si" };
            inicital[filtros[j].key] = "TODOS";
            inicital["val"] = "TODOS";
            inicital["Label"] = "TODOS";
            output.push(inicital);
            for (var i in temp) {
                if (typeof filtros[j].val == 'object') {

                    if (temp[i][filtros[j].key] == filtros[j].val[filtros[j].key]) {
                        output.push(temp[i]);
                    }
                } else {
                    if (temp[i][filtros[j].key] == filtros[j].val) {
                        output.push(temp[i]);
                    }
                }
            }
            temp = output;
        }
        return temp;
    };

    /*File Uploud*/
    $ang.service('AppFileUpload', ['$http', '$log', function ($http, $log) {
        this.uploadFileToUrl = function (file, data, uploadUrl, successCallback, erroCallback) {
            var formData = new FormData();
            //Add our file
            formData.append('file', file);

            //Add our data
            formData.append('data', angular.toJson(data));

            $http.post($sysconfig.ApplicationPath + uploadUrl, formData, {
                transformRequest: angular.identity,
                headers: { 'Content-Type': undefined }
            })
            .success(function (data) {
                successCallback(data);
            }).error(function () {
                erroCallback();
            });
        };
    }
    ]);

    $ang.service('AppMenuEvent', ['$http', function ($http) {
        this.menuScopeObj = null;

        this.onClick = function () {

        }

        this.activateExpandScreen = function () {
            if (this.menuScopeObj) {
                this.menuScopeObj.IsExpandScreen = true;
                this.menuScopeObj.$apply();
            }
        }

        this.desactivateExpandScreen = function () {
            if (this.menuScopeObj) {
                this.menuScopeObj.IsExpandScreen = false;
                this.menuScopeObj.$apply();
            }
        }

        this.cleanEvents = function () {
            this.onClick = function () { };
        }
    }
    ]);

    /*Producto Form*/
    $ang.service('AppProductoForm', ['$http', '$log', function ($http, $log) {

        this.IpDetalle = {
            IpDetalleID: 0,
            IpSubEncabezadoID: 0,
            ProdCodigo: '',
            ProdCategoria: '',
            ProdMarca: '',
            ProdTipo: '0',
            ProdEmpaque: '',
            ProdPresentacion: '',
            ProdDescripcion: '',
            ProdEnvase: '',
            Sku: '',
            ProdLabel: '',
            CantidadMinima: 1,
            ProdInformacionAdicional: '',
            Estado: true,
            Guid: '',
            FechaModificacion: ''
        };

        var link = 'IpSubEncabezado/frmIpDetalle';
        var productos = [];
        var dropDownLists = { marcas: [], presentaciones: [], descripciones: [], sku: [], empaques: [], envases: [] };
        var form = "";
        var FormDialog = {};
        var actionCallback = { guardar: function () { }, cancelar: function () { } };

        this.GetList = function () {
            return dropDownLists; 
        }

        this.Accion = function (accion) {
            actionCallback = accion;
        };

        this.GetProductos = function () {
            return productos; 
        };

        this.Agregar = function () {
            actionCallback.guardar();
        };

        this.httpForm = function (divID, successCallback) {
            var result = false; 
            $http.get($sysconfig.ApplicationPath + link).success(function (data) {
                form = data;
                successCallback(data);
                FormDialog = $(divID).dialog({ title: "Ip Detalle", width: 460 });

                result = true;

            }).error(function () {
                result = false;
            });

            return result;
        }
        this.GetForm = function (divID, successCallback ) {
            this.limpiarTempData();
            this.httpForm(divID, successCallback);
            // return form; 
        }

        this.cerrarForm = function () {
            FormDialog.dialog("close");
        }

        this.setProductos = function (lista) {
            productos = lista; 
        }


        this.isReprocesar = function (modo) {

            var categoria = this.IpDetalle.ProdCategoria;
            var marca = typeof (this.IpDetalle.ProdMarca) == "object" ? this.IpDetalle.ProdMarca.Marca : this.IpDetalle.ProdMarca;
            var tipo = typeof (this.IpDetalle.ProdTipo) == "object" ? this.IpDetalle.ProdTipo.Tipo : this.IpDetalle.ProdTipo;
            var presentacion = typeof (this.IpDetalle.ProdPresentacion) == "object" ? this.IpDetalle.ProdPresentacion.Presentacion : this.IpDetalle.ProdPresentacion;
            var empaque = typeof (this.IpDetalle.ProdEmpaque) == "object" ? this.IpDetalle.ProdEmpaque.Empaque : this.IpDetalle.ProdEmpaque;
            var envase = typeof (this.IpDetalle.ProdEnvase) == "object" ? this.IpDetalle.ProdEnvase.Envase : this.IpDetalle.ProdEnvase;

            switch (modo) {
                case 2, "Marca":
                    if (categoria == "TODOS" && marca == "TODOS") {
                        return false;
                    }
                    break;
                case 3, "Tipo":

                    if (categoria == "TODOS" && marca == "TODOS" && tipo == "TODOS") {
                        return false;
                    }

                    break;
                case "Presentacion":
                    if (categoria == "TODOS" && marca == "TODOS" && tipo == "TODOS" && presentacion == "TODOS") {
                        return false;
                    }
                    break;
                case "Empaque":
                    if (categoria == "TODOS" && marca == "TODOS" && tipo == "TODOS" && presentacion == "TODOS" && empaque == "TODOS") {
                        return false;
                    }
                    break;

                case "Envase":
                    if (categoria == "TODOS" && marca == "TODOS" && tipo == "TODOS" && presentacion == "TODOS" && empaque == "TODOS") {
                        return false;
                    }
                case "Sku":
                    if (categoria == "TODOS" && marca == "TODOS" && tipo == "TODOS" && presentacion == "TODOS" && empaque == "TODOS") {
                        return false;
                    }
                    break;
                default:
                    return true;
                    break;
            }
            return true;
        };


        this.obtenerMarcas = function (labelKey, temp) {
            switch (labelKey) {
                case "Categoria":
                    for (var c in this.IpDetalle) {
                        if (c != "ProdCategoria" && c != "Guid" && c != "ProdInformacionAdicional" && c != "FechaModificacion" && c != "CantidadMinima") {
                            this.IpDetalle[c] = '';
                        }
                    }

                    break;
                case "Marca":

                 //   console.log(this.IpDetalle.ProdCategoria);


                    this.IpDetalle.ProdPresentacion = '';
                    this.IpDetalle.ProdTipo = '0';
                    this.IpDetalle.ProdEmpaque = '';
                    this.IpDetalle.Sku = ''
                    this.IpDetalle.ProdDescripcion = '';
                    break;

                case "Tipo":

                    this.IpDetalle.ProdPresentacion = '';
                    this.IpDetalle.ProdEmpaque = '';
                    this.IpDetalle.Sku = ''
                    this.IpDetalle.ProdDescripcion = '';

                    break;
                case "Presentacion":
                    this.IpDetalle.ProdEmpaque = '';
                    this.IpDetalle.Sku = ''
                    this.IpDetalle.ProdDescripcion = '';

                    break;
                case "Empaque":

                    this.IpDetalle.Sku = ''
                    this.IpDetalle.ProdDescripcion = '';
                    break;

                case "Envase":

                    this.IpDetalle.Sku = ''
                    this.IpDetalle.ProdDescripcion = '';
                    break;

                case "Sku":
                    this.IpDetalle.ProdDescripcion = '';

                    break;
                case "Descripcion":
                    this.IpDetalle.ProdCodigo = this.IpDetalle.ProdDescripcion.CodigoOriginal;
                    break;
            }

            var objeto = null;
            var retorno = {};
            var filtro = [];
            for (var c in this.IpDetalle) {
                if (this.IpDetalle[c] != "TODOS" && this.IpDetalle[c] != "" && this.IpDetalle[c] != "0" && c != "CantidadMinima" && c != "ProdInformacionAdicional" && c != "FechaModificacion" ) {

                    var param = { key: c.replace("Prod", "").trim(), val: this.IpDetalle[c] };

                    if (param.key != "Estado") {
                        if (typeof (param.val) == "object") {
                            param.val = param.val[param.key];
                        }

                        if (param.val != "TODOS") {
                            filtro.push(param);
                        }
                    }

                }
            }

            if (this.isReprocesar(labelKey)) {
                retorno = dependsFilter(productos, filtro);
            } else {
                return false;
            }

          //  console.log("Procesara"); 

            switch (labelKey) {
                case "Categoria":
                    dropDownLists.marcas = retorno;
                    dropDownLists.presentaciones = retorno;
                    dropDownLists.empaques = retorno;
                    dropDownLists.tipos = retorno;
                    dropDownLists.envases = retorno;
                    dropDownLists.sku = retorno;
                    dropDownLists.descripciones = retorno;
                    this.IpDetalle.ProdCodigo = '';

                    break;
                case "Marca":
                    dropDownLists.presentaciones = retorno;
                    dropDownLists.empaques = retorno;
                    dropDownLists.envases = retorno;
                    dropDownLists.tipos = retorno;
                    dropDownLists.sku = retorno;

                    break;

                case "Tipo":
                    dropDownLists.presentaciones = retorno;
                    dropDownLists.sku = retorno;
                    dropDownLists.descripciones = retorno;
                    break;
                case "Presentacion":
                    dropDownLists.sku = retorno;
                    dropDownLists.descripciones = retorno;

                    break;
                case "Empaque":
                    dropDownLists.sku = retorno;
                    dropDownLists.descripciones = retorno;
                    dropDownLists.envases = retorno;
                    this.IpDetalle.ProdCodigo = '';
                    break;

                case "Envase":
                    dropDownLists.sku = retorno;
                    dropDownLists.descripciones = retorno;
                    this.IpDetalle.ProdCodigo = '';
                    break;

                case "Sku":
                    this.IpDetalle.ProdCodigo = this.IpDetalle.Sku.CodigoOriginal;
                    this.IpDetalle.ProdDescripcion = this.IpDetalle.Sku.Descripcion;
                    break;
                case "Descripcion":
                    this.IpDetalle.ProdCodigo = this.IpDetalle.ProdDescripcion.CodigoOriginal;
                    break;
            }
            return false;
        };

        this.limpiarTempData = function () {
            this.IpDetalle = {
                IpDetalleID: 0,
                IpSubEncabezadoID: 0,
                ProdCodigo: '',
                ProdCategoria: '',
                ProdMarca: '',
                ProdTipo: '0',
                ProdEmpaque: '',
                ProdPresentacion: '',
                ProdDescripcion: '',
                ProdEnvase: '',
                Sku: '',
                ProdLabel: '',
                ProdInformacionAdicional: '',
                Estado: true,
                Guid: '',
                FechaModificacion: '',
                CantidadMinima: 1
            };
        };

        this.GetData = function () {
            return this.IpDetalle; 
        };    
    }
    ]);



})();