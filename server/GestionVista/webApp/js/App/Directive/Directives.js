(function () {
    var $ang = appAng;

    var $app = new app();
    var $sys = $app.System;
    var $tool = $app.Tool;
    var $format = $app.Format
    var $sysCrud = $sys.Crud;
    var $sysEnum = $sys.Enum.CrudState;


    /*Pagination*/
    function GetAppPaginacion(modelName) {
        var ModelName = modelName || 'AppPaginacion';

        var appPaginacionTemplate = '<div style="float:left" class="app-paginacion-searcherWrap" ng-show="' + ModelName + '.ShowSearcher">\
                <div class="app-paginacion-searcher form-search search-only" style="width:300px">\
                    <i class="search-icon glyphicon glyphicon-search" ng-click="' + ModelName + '.$List()"></i>\
                    <input type="text" class="form-control search-query" ng-model="' + ModelName + '.Filtro">\
                </div>\
            </div>\
            <div class="app-paginacion-paginatorWrap" ng-init=" ' + ModelName + '.LoadInit? ' + ModelName + '.$List(null, true) : false">\
                <ul class="app-paginacion-paginator pagin text-center" style="float:right">\
                    <li><a ng-click="' + ModelName + '.RangoFirst()  "><strong>&lsaquo;</strong></a></li>\
                    <li><a ng-click="' + ModelName + '.RangoBack() "><strong>&lsaquo;&lsaquo;</strong></a></li>\
                    <li ng-repeat="next in ' + ModelName + '.ArrayPag">\
                        <a ng-click="' + ModelName + '.ActivePag($index);' + ModelName + '.$List(next*' + ModelName + '.Max)" ng-class="' + ModelName + '.ActivePagClass($index)" ng-show="' + ModelName + '.RangoIsvisible($index)">\
                            <strong>{{next}}</strong>\
                        </a>\
                    </li>\
                    <li><a ng-click="' + ModelName + '.RangoNext()"><strong>&rsaquo;&rsaquo;</strong></a></li>\
                    <li><a ng-click="' + ModelName + '.RangoLast()"><strong>&rsaquo;</strong></a></li>\
                </ul>\
            </div>';

        return appPaginacionTemplate;
    };

    $ang.directive('appPaginacion', function () {

        return {
            restrict: 'A', /* restrict this directive to elements */
            template: GetAppPaginacion()
        };
    });

    $ang.directive('appPaginacion', function ($compile, $timeout) {
        return {
            restrict: 'A', /* restrict this directive to elements */
            scope: {
                option: "="
            },
            link: function (scope, element, attrs) {
                scope.$watch('option', function (model) {
                    if (model) {
                        element.html(GetAppPaginacion('option'));
                        $compile(element.contents())(scope);        
                    }
                })
            }
        };
    });

    
    /*Ng-Repeat ready*/
    $ang.directive('appRepeatEndevent', function ($timeout) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                if (scope.$last === true) {
                    $timeout(function () {
                        scope.$emit("appRepeatEndcallback");

                        /*
                            $scope.on("appRepeatEndcallback", function(){ });
                        */
                    });
                }
            }
        }
    });


    /*File model*/
    $ang.directive('fileModel', [
            '$parse', function ($parse) {
                return {
                    restrict: 'A',
                    link: function (scope, element, attrs) {
                        var model = $parse(attrs.fileModel);
                        var modelSetter = model.assign;
                        element.bind('change', function () {
                            scope.$apply(function () {
                                modelSetter(scope, element[0].files[0]);
                            });
                        });
                    }
                };
            }
    ]);


    /* checklist model */
    $ang.directive('checklistModel', ['$parse', '$compile', function ($parse, $compile) {
        // contains
        function contains(arr, item, comparator) {
            if (angular.isArray(arr)) {
                for (var i = arr.length; i--;) {
                    if (comparator(arr[i], item)) {
                        return true;
                    }
                }
            }
            return false;
        }

        // add
        function add(arr, item, comparator) {
            arr = angular.isArray(arr) ? arr : [];
            if (!contains(arr, item, comparator)) {
                arr.push(item);
            }
            return arr;
        }

        // remove
        function remove(arr, item, comparator) {
            if (angular.isArray(arr)) {
                for (var i = arr.length; i--;) {
                    if (comparator(arr[i], item)) {
                        arr.splice(i, 1);
                        break;
                    }
                }
            }
            return arr;
        }

        // http://stackoverflow.com/a/19228302/1458162
        function postLinkFn(scope, elem, attrs) {
            // compile with `ng-model` pointing to `checked`
            $compile(elem)(scope);

            // getter / setter for original model
            var getter = $parse(attrs.checklistModel);
            var setter = getter.assign;
            var checklistChange = $parse(attrs.checklistChange);

            // value added to list
            var value = $parse(attrs.checklistValue)(scope.$parent);


            var comparator = angular.equals;

            if (attrs.hasOwnProperty('checklistComparator')) {
                comparator = $parse(attrs.checklistComparator)(scope.$parent);
            }

            // watch UI checked change
            scope.$watch('checked', function (newValue, oldValue) {
                if (newValue === oldValue) {
                    return;
                }
                var current = getter(scope.$parent);
                if (newValue === true) {
                    setter(scope.$parent, add(current, value, comparator));
                } else {
                    setter(scope.$parent, remove(current, value, comparator));
                }

                if (checklistChange) {
                    checklistChange(scope);
                }
            });

            // declare one function to be used for both $watch functions
            function setChecked(newArr, oldArr) {
                scope.checked = contains(newArr, value, comparator);
            }

            // watch original model change
            // use the faster $watchCollection method if it's available
            if (angular.isFunction(scope.$parent.$watchCollection)) {
                scope.$parent.$watchCollection(attrs.checklistModel, setChecked);
            } else {
                scope.$parent.$watch(attrs.checklistModel, setChecked, true);
            }
        }

        return {
            restrict: 'A',
            priority: 1000,
            terminal: true,
            scope: true,
            compile: function (tElement, tAttrs) {
                if (tElement[0].tagName !== 'INPUT' || tAttrs.type !== 'checkbox') {
                    throw 'checklist-model should be applied to `input[type="checkbox"]`.';
                }

                if (!tAttrs.checklistValue) {
                    throw 'You should provide `checklist-value`.';
                }

                // exclude recursion
                tElement.removeAttr('checklist-model');

                // local scope var storing individual checkbox model
                tElement.attr('ng-model', 'checked');

                return postLinkFn;
            }
        };
    }]);


})();

