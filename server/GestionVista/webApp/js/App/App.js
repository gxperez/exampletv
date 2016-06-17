var app = function () {
    var instance;

    app = function app() {
        return instance;
    };
    app.prototype = this;

    instance = new app();

    instance.constructor = app;

    return instance;
};

appAngularDependecies.push('ngSanitize', 'jcs-autoValidate', 'ui.sortable');

var appAng = angular.module("App", appAngularDependecies, function ($compileProvider) {
    $compileProvider.directive('compile', function ($compile) {
        return function (scope, element, attrs) {
            scope.$watch(
                function (scope) {
                    return scope.$eval(attrs.compile);
                },
                function (value) {
                    element.html(value);
                    $compile(element.contents())(scope);
                }
            );
        };
    });
})
.run([ /*Module:  jcs-autoValidate*/ 
    'defaultErrorMessageResolver',    
    'bootstrap3ElementModifier',
    function (defaultErrorMessageResolver,  bootstrap3ElementModifier) {
        // To change the root resource file path
        defaultErrorMessageResolver.setI18nFileRootPath( base_url + 'webApp/js/Jcs-auto-validate/lang');
        defaultErrorMessageResolver.setCulture('es-es');
         bootstrap3ElementModifier.enableValidationStateIcons(true);

    }
]);


//1 - Validation
//2 - Format
//3 - Tool
//4 - Prototype
//5 - System

