(function (window, $) {
    var CrudState = 0

    var $app = window.app
    var appTool = new $app().Tool;

    var appSystem = $app.prototype.System = {};

    appSystem.Enum = {
        CrudState: {
            Create: 1,
            Read: 2,
            Update: 3,
            Delete: 4
        }
		, MessageType: {
		    warning: 1,
		    info: 2,
		    success: 3,
		    danger: 4
		}
    };

    appSystem.Utility = {
        /*Message Methods*/
        MessageType: new $app().System.Enum.MessageType,
        GetMessageBox: function (messageType, messageTitle, messageContent, isMessageHtml) {
            var messageBox = $('<div class="body-main-popover">\
                                      <div class="arrow"></div>\
                                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="padding: 5px;">x</button>\
                                      <h3 class="popover-title"> </h3>\
                                      <div class="popover-content">\
                                          <p></p>\
                                      </div>\
                                 </div>');


            switch (messageType) {
                case this.MessageType.success:
                    messageBox.addClass("popover-success");
                    messageBox.find(".popover-title").text(messageTitle || "Exito");
                    break;
                case this.MessageType.danger:
                    messageBox.addClass("popover-danger");
                    messageBox.find(".popover-title").text(messageTitle || "Advertencia");
                    break;
                case this.MessageType.warning:
                    messageBox.addClass("popover-warning");
                    messageBox.find(".popover-title").text(messageTitle || "Error");
                    break;
                case this.MessageType.info:
                    messageBox.addClass("popover-info");
                    messageBox.find(".popover-title").text(messageTitle || "Informacion");
                    break;
                default:
            }

            if (isMessageHtml)
                messageBox.find(".popover-content").html(messageContent);
            else
                messageBox.find(".popover-content > p").text(messageContent);

            return messageBox;
        },
        ShowMessage: function (messageType, messageContent,isMessageHtml, removeCallBack) {
            var messageBox = this.GetMessageBox(messageType, null, messageContent, isMessageHtml);

            $(".body-main-popover").each(function (index) {
                var buttomValue = parseInt($(this).css("bottom"));
                var zindexValue = parseInt($(this).css("z-index"));
                $(this).css("bottom", (buttomValue + 5).toString() + "px");
                $(this).css("z-index", zindexValue + 1);
            });

            $("#main").append(messageBox);

            if (!removeCallBack) {
                setInterval(function () {
                    messageBox.remove();
                }, messageContent == null ? 0 :  messageContent.length * messageContent.length > 10? 20000: 100000);

            }

        },
        ShowSuccessMessage: function (messageContent, removeCallBack) {
            this.ShowMessage(this.MessageType.success, messageContent,false, removeCallBack);
        },
        ShowDangerMessage: function (messageContent, removeCallBack) {
            this.ShowMessage(this.MessageType.danger, messageContent, false, removeCallBack);
        },
        ShowInfoMessage: function (messageContent, removeCallBack) {
            this.ShowMessage(this.MessageType.info, messageContent, false, removeCallBack);
        },
        ShowDangerMessageHtml: function (messageContent, removeCallBack) {
            this.ShowMessage(this.MessageType.danger, messageContent, true ,removeCallBack);
        },
        ShowInfoMessageHtml: function (messageContent, removeCallBack) {
            this.ShowMessage(this.MessageType.info, messageContent, true, removeCallBack);
        },

        /*Overlay Methods*/
        GetOverlay: function (htmlContent) {
            var zIndex = parseInt($(".app-overlay").css("z-index")) + parseInt($(".app-overlay").length) || 100;
            return appTool.GetOverlay(htmlContent, { "z-index": zIndex });
        },
        ShowOverlayLoading: function (message) {
            var messageToAdd = message || "Cargando...";

            var overlayContent = $(" <div class='app-overlay-img' style='\
                                margin: auto;\
                                margin-top: 17%;\
                                width: 90px;\
                                background-color: white;\
                                border-radius: 54px;\
                                height: 90px;\
                                padding: 3px;\
                                box-shadow: 0px 5px 8px -2px rgba(123, 123, 123, 0.65);'></div> \
                                \
                                <div class='app-overlay-text' style='\
                                margin: auto;\
                                width: 15%;\
                                word-wrap: break-word;'> </div>");

            overlayContent.filter(".app-overlay-img").html("<img style='width: 85px;height: 85px;' src='Images/LoadingOrange.gif'>");

            overlayContent.filter(".app-overlay-text").html("<p style='text-align: center;\
                                                      font-size: 1.05em;\
                                                      font-weight: bold;\
                                                      color: rgb(122, 122, 117);\
                                                      margin-top: 15px;\
                                                      background-color: white;\
                                                      border-radius: 10px;\
                                                      box-shadow: 0px 5px 8px -2px rgba(123, 123, 123, 0.65);\'>" + messageToAdd + "</p>");

            var overlay = this.GetOverlay(overlayContent);

            $("#main").append(overlay);

            return overlay;
        },

        /*Modal Methods*/
        ShowModal: function (config) {
            var modal = this.GetOverlay();

            config.closeCallBack = function () { modal.remove();}

            modal.html(appTool.GetModal(config));
            $("#main").append(modal);

            return modal;
        },
        ShowModalByUrl: function (title, url, angularBtn, angularScope, angularCompile) {
            var showModal = this.ShowModal;

            $.get(url, function (data) {
                var config = {
                    'title': title,
                    'content': data,
                    'angularBtn': angularBtn,
                    'angularScope': angularScope,
                    'angularCompile': angularCompile,
                }

                showModal.bind(appSystem.Utility)(config);
            });
        },
        ShowModalByContent: function (title, content, angularBtn, angularScope, angularCompile) {
            var config = {
                'title': title,
                'content': content,
                'angularBtn': angularBtn,
                'angularScope': angularScope,
                'angularCompile': angularCompile,
            }

            return this.ShowModal(config);
        },
        ShowModalYesNo: function (title, question, yesAngularFunction, noAngularFunction, angularScope, angularCompile) {
            console.log("pruebaa");

            var config = {
                'title': title,
                'content': "<p>" + question + "</p>",
                'angularBtn': {
                    'Si': yesAngularFunction, //? "ng-click='" + yesAngularFunction + "'" : "",
                    'No': noAngularFunction //? "ng-click='" + noAngularFunction + "'" : ""
                },
                'angularScope':angularScope,
                'angularCompile': angularCompile,
            }

            console.log(config);

            return this.ShowModal(config);
        }
    };

    appSystem.Crud = {
        CrudState: function () { return new $app().System.Enum.CrudState },
        State: null,
        SetCreate: function () {
            this.State = this.CrudState().Create;
        },
        SetDelete: function () {
            this.State = this.CrudState().Delete;
        },
        SetUpdate: function () {
            this.State = this.CrudState().Update;
        },
        SetRead: function () {
            this.State = this.CrudState().Read;
        },
        Reset: function () {
            this.State = null;
        },
        GetState: function () {
            return this.State;
        }
    };


    var baseUrl = $("base").first().attr("href");

    appSystem.Config = {
        ApplicationPath: baseUrl
    }

    appSystem.Msg = {
        MsgType: {
            FinalizaCrud: "Favor de finalizar las operaciones en el mantenimiento."
        }
    }

})(window, $)