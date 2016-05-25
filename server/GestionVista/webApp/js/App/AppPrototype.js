var test;

(function () {
    /*String*/
    var str = String.prototype;

    /*****************************************************
    $str.prototype.repeat

    Parameters:
        times : 0

    ******************************************************/
    str.repeat = function (times) {
        return new Array(times + 1).join(this)
    }

    str.replaceAll = function (str1, str2, ignore) {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, "\\$&"), (ignore ? "gi" : "g")), (typeof (str2) == "string") ? str2.replace(/\$/g, "$$$$") : str2);
    }




    /*Object*/
    var objp = Object.prototype;

    /*****************************************************
    $Object.prototype.where

    Parameters:
        filtro : "
                  x.variable == valor 
                  && x.variable == valor
                  || x.variable == valor
                  
                  ",

    ******************************************************/
    Object.where = function (filtro) {
        var obj = this;

        if (Array.isArray(obj))
            return obj.filter(function (x) { return eval(filtro) });

    }




    /*Jquery*/
    var Jqy = $.prototype

    /*****************************************************
     $$.prototype.where

     Parameters:
         truncateLenght : 0

     ******************************************************/
    Jqy.truncate = function (truncateLenght) {
        var obj = $(this);

        obj.each(function () {
            var obj = $(this);
            var text = obj.text();
            var apply = (text.length <= truncateLenght) ? false : true;

            if (apply)
                obj.prop("title", text);

            obj.text(
					text.substring(0, apply ? truncateLenght : text.length) + (apply ? "..." : "")
					);
        })
    }

})()




/*

    appFormat.ObjectExist  = function (obj, objFilter) {
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

    appFormat.ObjectUpdate = function (obj, objNew, whereFilter) {
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
                        this.ObjectUpdate(obj[key],objNew[key]);
                    }
                    else {
                        obj[key] = objNew[key];
                    }
                }
            }
        }
    }

    appFormat.ObjectDelete = function (obj, whereFilter) {
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

    appFormat.ObjectcleanValue = function (objToClean) {
        var obj = objToClean;
        var objsToRemove = [];

        if (Array.isArray(obj)) {
            for (var index in obj) {
                if (typeof (obj[index]) == 'object')
                    this.ObjectcleanValue(obj[index]);
                else
                    objsToRemove.push(obj[index]);
            }

            for (var index in objsToRemove)
            {
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

                        };
                    }
                }
            }
        }
    }


*/