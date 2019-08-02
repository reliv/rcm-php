/**
 * rcmUserRoleSelector.filter
 */
angular.module('rcmUserRoleSelector').filter(
    'rcmUserRoleFilter',
    function () {

        var compareStr = function (stra, strb) {
            stra = ("" + stra).toLowerCase();
            strb = ("" + strb).toLowerCase();

            return stra.indexOf(strb) !== -1;
        };

        return function (input, query) {
            if (!query) {
                return input
            }
            var result = {};

            angular.forEach(
                input, function (role, key) {
                    if (compareStr(role.roleId, query)) {
                        result[key] = role;
                    }
                }
            );

            return result;
        };
    }
);
