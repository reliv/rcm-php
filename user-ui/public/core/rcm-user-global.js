/**
 * Exposes Angular service to global scope for use by other libraries
 * - This is to support jQuery and native JavaScript modules and code
 * Angular injector to get Module services
 */
rcmUser.core = {};
angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'rcmUserConfig',
        function (rcmUserConfig) {
            rcmUser.core.rcmUserConfig = rcmUserConfig;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'RcmUserHttp',
        function (RcmUserHttp) {
            rcmUser.core.RcmUserHttp = RcmUserHttp;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'RcmUserResult',
        function (RcmUserResult) {
            rcmUser.core.RcmUserResult = RcmUserResult;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'RcmUser',
        function (RcmUser) {
            rcmUser.core.RcmUser = RcmUser;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'RcmResults',
        function (RcmResults) {
            rcmUser.core.RcmResults = RcmResults;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'getNamespaceRepeatString',
        function (getNamespaceRepeatString) {
            rcmUser.core.getNamespaceRepeatString = getNamespaceRepeatString;
        }
    ]
);
