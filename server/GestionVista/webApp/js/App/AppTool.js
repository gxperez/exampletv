(function (window, $) {
    var $app = window.app;
    
    var appTool = $app.prototype.Tool = {};


    /*****************************************************
        $CLEANFORM

        Parameters:
                 config: "#FormaName"
 
    ******************************************************/
    appTool.CleanForm = function (formElem) {
        $(formElem)
            .find("input[type=text],input[type=file],input[type=email],input[type=hidden],input[type=color],input[type=number],input[type=password],textarea")
                .val("");

        $(formElem)
            .find("input[type=checkbox]")
                .attr('checked', false);

        $(formElem)
            .find("select")
                .prop('selectedIndex', 0);

    };

    appTool.ObjectExist = function (obj, objFilter) {
        var obj = obj;
        var result = false;

        if (Array.isArray(obj)) {
            //for (var index in obj) {
            //    if (obj[index].isEqual(objFilter))
            //        result = true;
            //}
        }
        else {
            var objFilterKeys = Object.keys(objFilter ? objFilter : {});
            var objFound = 0;

            for (var key in obj) {
                if (obj.hasOwnProperty(key) && objFilterKeys.filter(function (k) { return k == key }).length) {
                    if (typeof (obj[key]) == 'object' && typeof (objFilter[key]) == 'object' && this.ObjectExist(obj[key], objFilter[key])) {
                        objFound += 1;
                    }
                    else if (obj[key] === objFilter[key]) {
                        objFound += 1;
                    }
                }
            }

            result = objFound == objFilterKeys.length;
        }

        return result;
    }

    appTool.ObjectUpdate = function (obj, objNew, whereFilter) {
        var obj = obj;

        if (Array.isArray(obj)) {
            for (var index in obj) {
                this.ObjectUpdate(obj[index], objNew, whereFilter);
            }
        }
        else {
            if (whereFilter && !this.ObjectExist(obj, whereFilter))
                return;

            var objNewKeys = Object.keys(objNew ? objNew : {});

            for (var key in obj) {
                if (obj.hasOwnProperty(key) && objNewKeys.filter(function (k) { return k == key }).length) {
                    if (typeof (obj[key]) == 'object' && typeof (objNew[key]) == 'object') {
                        this.ObjectUpdate(obj[key], objNew[key]);
                    }
                    else {
                        obj[key] = objNew[key];
                    }
                }
            }
        }
    }

    appTool.ObjectDelete = function (obj, whereFilter) {
        var obj = obj;

        if (Array.isArray(obj)) {
            for (var index in obj) {
                console.log(index);
                console.log(this.ObjectExist(obj[index], whereFilter));

                if (this.ObjectExist(obj[index], whereFilter))
                    obj.shift(index)
            }
        }
    }

    appTool.ObjectcleanValue = function (objToClean) {
        var obj = objToClean;
        var objsToRemove = [];

        if (Array.isArray(obj)) {
            for (var index in obj) {
                if (typeof (obj[index]) == 'object')
                    this.ObjectcleanValue(obj[index]);
                else
                    objsToRemove.push(obj[index]);
            }

            for (var index in objsToRemove) {
                obj.pop(objsToRemove[index]);
            }
        }
        else {
            for (var key in obj) {
                if (obj.hasOwnProperty(key)) {
                    if (typeof (obj[key]) == 'object') {
                        this.ObjectcleanValue(obj[key]);
                    }
                    else {
                        switch (typeof (obj[key])) {
                            case "number":
                                obj[key] = 0;
                                break;
                            case "string":
                                obj[key] = "";
                                break;
                            case "boolean":
                                obj[key] = false;
                                break;
                        };
                    }
                }
            }
        }
    }




    /*****************************************************
        $MODAL

        Parameters:
                config: {
                            title: "",
                            content: "",
                            closeBtnEnable: true,
                            closeCallBack: function(),
                            btn: {btnName: btnFunction(),
                            angularBtn: {btnName: btnAttr="ng-click='function()'"}  || {btnName: btnFunction()} ,
                            angularScope: {},
                            angularCompile: {}
                        }

        Note: 
            Close Modal From Btn : add to the function the statement "this.CloseModal()"

            Close Modal From AngularBtn : only work with the {btnName, btnFunction()} parameter,
                add to the function the statement  "this.CloseModal()" and to track the changes 
                of the model add  $scope.$apply()

    ******************************************************/
    appTool.GetModal = function (config) {
        if (!config)
            return;

        var title = "Title";
        var content;
        var btns = {};
        var angularBtns = {};
        var angularScope = null;
        var angularCompile = null;
        var closeCallBack = function () { };
        var closeBtnEnable = true;
       
        if (config.hasOwnProperty("title"))
            title = config.title;

        if (config.hasOwnProperty("content"))
            content = config.content;

        if (config.hasOwnProperty("btn") && typeof(config.btn) == "object")
            btns = config.btn;

        if (config.hasOwnProperty("angularBtn") && typeof (config.angularBtn) == "object")
            angularBtns = config.angularBtn;

        if (config.hasOwnProperty("angularScope") && typeof (config.angularScope) == "object")
            angularScope = config.angularScope;

        if (config.hasOwnProperty("angularCompile") && typeof (config.angularCompile) == "function")
            angularCompile = config.angularCompile;

        if (config.hasOwnProperty("closeBtnEnable"))
            closeBtnEnable = config.closeBtnEnable;

        if (config.hasOwnProperty("closeCallBack"))
            closeCallBack = config.closeCallBack;



        var closeBtn = closeBtnEnable ? "<button class='app-modal-close'>x</button>" : "";

        var modal = $("	<div class='app-modal'></div>").html("<div class='app-modal-content'>\
                                                                    <div class='app-modal-header'>\
                                                                        <h4 class='app-modal-title'>\
                                                                        <span class='glyphicon glyphicon-cog' style='color: rgb(255, 175, 2);vertical-align: middle;  margin-right: 5px;font-size: 20px;'></span>" + title + "</h4>"
                                                                        + closeBtn +
                                                                        "<div class='app-modal-clear'></div>\
                                                                    </div>\
                                                                    <div class='app-modal-body'></div>\
                                                                    <div class='app-modal-footer'></div>\
                                                                </div>\
                                                            </div>");

        var closeFuntion = function () {
            $(modal).remove();
            closeCallBack();
        };

        for (var btn in btns) {
            var btnElement = $("<button class='app-modal-control' >" + btn + "</button> ");

            if (typeof (btns[btn]) == "function")
                btnElement.on("click", btns[btn].bind({ CloseModal: closeFuntion }));

            modal.find(".app-modal-footer").append(btnElement);
        }

        if (closeBtnEnable)
            modal.find(".app-modal-close").on("click", closeFuntion);

        modal.find(".app-modal-body").html(content);


        /*Angular Compile*/
        if (angularCompile && angularScope)
        {
            for (var btn in angularBtns) {

                if (typeof (angularBtns[btn]) == 'string')
                    var btnElement = $("<button class='app-modal-control' " + angularBtns[btn].replaceAll("()", "(this)") + " >" + btn + "</button> ");
                else if (typeof (angularBtns[btn]) == 'function')
                {
                    var btnElement = $("<button class='app-modal-control'>" + btn + "</button> ");
                    btnElement.on("click", angularBtns[btn].bind({ CloseModal: closeFuntion }));
                }

                modal.find(".app-modal-footer").append(btnElement);
            }

            angularCompile(modal)(angularScope);
        }
        return modal;
    };



    /*****************************************************
        $OVERLAY

        Parameters:
                htmlToInject:   ""
                css         :   {
                                    attr1: value1,
                                    attr2: value2
                                    ....
                                    attrN: valueN 
                                }
    
    ******************************************************/
    appTool.GetOverlay = function (htmlToInject, css) {
        var overlay = $("<div class='app-overlay'></div>");
        var zIndexMinimo = 100;

        overlay.css({
            "width": "100%",
            "height": "100%",
            "background-color": "rgba(132, 145, 163, 0.54)",
            "position": "fixed",
            "top": "0",
            "left": "0",
            "z-index": "100",
            'overflow': 'auto'
        });

        if (css)
            overlay.css(css);
        
        overlay.html(htmlToInject);

        return overlay;
    };

})(window, $)