angular.module('rcmuserAdminUsersApp').filter('userFilter', function () {

    var compareStr = function (stra, strb) {
        stra = ("" + stra).toLowerCase();
        strb = ("" + strb).toLowerCase();

        return stra.indexOf(strb) !== -1;
    };

    return function (input, query) {
        if (!query) {
            return input
        }
        var result = [];

        angular.forEach(input, function (user) {
            if (compareStr(user.id, query)
                || compareStr(user.username, query)
                || compareStr(user.state, query)
                || compareStr(user.email, query)
                || compareStr(user.name, query)
            ) {
                result.push(user);
            }
        });

        return result;
    };
});
