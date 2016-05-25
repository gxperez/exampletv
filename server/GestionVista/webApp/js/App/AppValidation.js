(function (window, $) {
    var $app = window.app;

    var appVal = $app.prototype.Validation = {};

    appVal.IsNumber = function (n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

})(window, $)