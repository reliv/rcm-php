/**
 * rcmuserCore.getNamespaceRepeatString
 */
angular.module('rcmuserCore').factory(
    'getNamespaceRepeatString', function () {
        return function (namespace, repeatStr, namespaceDelimiter) {
            namespace = '' + namespace;

            if (!namespaceDelimiter) {
                namespaceDelimiter = ".";
            }

            var n = (namespace.split(namespaceDelimiter).length - 1);
            var a = [];
            while (a.length < n) {
                a.push(repeatStr);
            }
            return a.join('');
        }
    }
);
