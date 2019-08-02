/**
 * Exposes Angular service to global scope for use by other libraries
 * - This is to support jQuery and native JavaScript modules and code
 * Angular injector to get Module services
 */
angular.injector(['ng', 'rcmUserRolesService']).invoke(
    [
        'rcmUserRolesService',
        function (rcmUserRolesService) {

            rcmUser.rcmUserRolesService.service = rcmUserRolesService;
        }
    ]
);
