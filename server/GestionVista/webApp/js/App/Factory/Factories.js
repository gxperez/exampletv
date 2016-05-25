var prueba;

(function () {
    var $ang = appAng;

    var $app = new app();
    var $sys = $app.System;
    var $tool = $app.Tool; //ObjectcleanValue
    var $format = $app.Format; 
    var $sysCrud = $sys.Crud;
    var $sysEnum = $sys.Enum.CrudState;
    var $sysconfig = $sys.Config;
    var $sysUtil = $sys.Utility;
    var $smt = $sys.Enum.MessageType;

    /*CRUD*/
    $ang.factory('AppCrud', function () {
        var Crud = function () {
            var _crud = {
                ListShow: true,
                SaveShow: false,
                AddShow: true,
                EditShow: true,
                DelShow: true,
                CancelShow: true,
                ShowTab: function (showForm) {
                    var Formulario = $('.panel #Formulario');
                    var Lista = $('.panel #Lista');

                    if (!Formulario || !Lista)
                        return null;

                    if (showForm) {
                        Formulario.removeClass("disabled");
                        Formulario.find('a').attr("data-toggle", "tab");

                        Lista.removeClass("active").addClass("disabled");
                        Lista.find('a').attr("data-toggle", "");

                        Formulario.find('a').click();
                    }
                    else {
                        Lista.removeClass("disabled");
                        Lista.find('a').attr("data-toggle", "tab");

                        Formulario.removeClass("active").addClass("disabled");
                        Formulario.find('a').attr("data-toggle", "");

                        Lista.find('a').click();
                    }
                },
                ResetCrud: function () {
                    this.ListShow = true;
                    this.SaveShow = false;
                    this.AddShow = true;
                    this.EditShow = true;
                    this.DelShow = true;
                    this.CancelShow = true;
                    this.ShowTab(false);
                    this.SelectedRowIndex = null;

                    $tool.ObjectcleanValue(this.$Data);
                    $tool.CleanForm("#FormularioContainer");

                    for (var form in this.$Form) {
                        //AngularJs: Reset Input Validation
                        if (this.$Form[form].hasOwnProperty("$setPristine"))
                        {
                            this.$Form[form].$setPristine();
                        }
                    }

                    $sysCrud.Reset();

                    this.$ResetCrudCallBack();
                },
                SelectedRowIndex: null,
                SelectRow: function (index) {
                    var status = $sysCrud.GetState();

                    //AppCrud: If we aren't editing, adding or deleting
                    if (!status) {
                        this.SelectedRowIndex = index;
                    }
                },
                SelectRowClass: function (index) {
                    return this.SelectedRowIndex == index ? 'selected' : "";
                },
                $List: function () {
                    this.ResetCrud();
                },
                $Save: function () {
                    if (!this.$Validate()) {
                        return false;
                    }

                    var status = $sysCrud.GetState();

                    if (status == $sysEnum.Create) {
                        setTimeout(function () {
                            $("#AddClick").click();
                        }, 0);
                    }
                    else if (status == $sysEnum.Update) {
                        setTimeout(function () {
                            $("#EditClick").click();
                        }, 0);
                    }
                },
                $Validate: function () {
                    //AngularJs: Check Input Validation (Errors)
                    for (var form in this.$Form)
                    {
                        if (this.$Form[form].hasOwnProperty("$invalid") && this.$Form[form].$invalid) {
                            //if (this.$Form.hasOwnProperty("$error")) {
                            //}
                    
                            $sysUtil.ShowMessage($smt.info, "Favor de completar los registros correctamente.");
                            return false;
                        }
                    }
                    
                    
                    return true;
                },
                $Add: function () {
                    this.ResetCrud();

                    this.ListShow = true;
                    this.SaveShow = true;
                    this.AddShow = false;
                    this.EditShow = false;
                    this.DelShow = false;
                    this.CancelShow = true;

                    this.ShowTab(true);
                    $sysCrud.SetCreate();

                    this.$AddCallBack();
                },
                $Edit: function () {
                    if (this.SelectedRowIndex != null && this.SelectedRowIndex >= 0) {
                        this.ListShow = true;
                        this.SaveShow = true;
                        this.AddShow = false;
                        this.EditShow = false;
                        this.DelShow = false;
                        this.CancelShow = true;
                        this.ShowTab(true);

                        $sysCrud.SetUpdate();

                        this.$EditCallBack();
                    }
                    else {

                        $sysUtil.ShowMessage($smt.info, "Favor de seleccionar un registro.");
                    }

                },
                $Del: function () {
                    if (this.SelectedRowIndex != null && this.SelectedRowIndex >= 0) {
                        $sysCrud.SetDelete();

                        setTimeout(function () {
                            $("#ElimClick").click();
                        }, 0);
                    }
                    else {
                        $sysUtil.ShowMessage($smt.info, "Favor de seleccionar un registro.");
                    }
                },
                $Cancel: function () {
                    this.ResetCrud();
                },
                $Form: {},
                $ResetCrudCallBack: function(){},
                $AddCallBack: function () { },
                $EditCallBack: function () { },
                $Data: {}
            };

            return _crud;
        }

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