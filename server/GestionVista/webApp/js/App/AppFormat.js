(function (window, $) {
    var $app = window.app;
    var appValidation = new $app().Validation

    var appFormat = $app.prototype.Format = {};

    appFormat.Number = function (n) {
        return appValidation.IsNumber(n);
    };

})(window, $)