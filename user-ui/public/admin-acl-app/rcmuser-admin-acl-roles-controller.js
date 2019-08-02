/**
 * rcmuserAdminAclApp.rcmuserAdminAclRoles
 */
angular.module('rcmuserAdminAclApp').controller(
    'rcmuserAdminAclRoles',
    [
        '$window',
        '$scope',
        'RcmUserHttp',
        'getNamespaceRepeatString',
        'rcmUserSelectedDataService',
        'rcmUserAclRoleService',
        'rcmUserAclResourceService',
        function (
            $window,
            $scope,
            RcmUserHttp,
            getNamespaceRepeatString,
            rcmUserSelectedDataService,
            rcmUserAclRoleService,
            rcmUserAclResourceService
        ) {
            var roleServiceEventManager = rcmUserAclRoleService.getEventManager();
            var rcmUserHttpEventManager = RcmUserHttp.getEventManager();

            $scope.selectedRoleData = null;

            $scope.oneAtATime = true;

            $scope.resourceCount = 0;

            $scope.levelRepeat = getNamespaceRepeatString;

            $scope.loading = true;
            rcmUserHttpEventManager.on(
                'RcmUserHttp.loading',
                function (loading) {
                    $scope.loading = loading;
                }
            );

            var onError = function (data) {
                $window.alert('An error occurred');
                console.error(data);
            };

            /**
             * Open add rule modal
             *
             * @param roleData
             */
            $scope.openAddRule = function (roleData) {
                rcmUserSelectedDataService.setData('addRuleRoleData', roleData);
                jQuery('#addRule').modal('show');
            };

            /* <REMOVE_RULE> */
            $scope.openRemoveRule = function (ruleData, resourceData) {
                rcmUserSelectedDataService.setData('removeRuleRuleData', ruleData);
                rcmUserSelectedDataService.setData('removeRuleResourceData', resourceData);
                jQuery('#removeRule').modal('show');
            };
            /* </REMOVE_RULE> */

            /* <ADD_ROLE> */
            $scope.openAddRole = function (roles) {
                rcmUserSelectedDataService.setData('addRoleRoles', roles);
                jQuery('#addRole').modal('show');
            };
            /* </ADD_ROLE> */

            /* <REMOVE_ROLE> */
            $scope.openRemoveRole = function (roleData) {
                rcmUserSelectedDataService.setData('removeRoleRoleData', roleData);
                jQuery('#removeRole').modal('show');
            };
            /* </REMOVE_ROLE> */

            /* <ROLES> */
            var onGetRolesSuccess = function (data) {
                $scope.roles = data.data;
            };

            roleServiceEventManager.on(
                'rcmUserAclRoleService.getRulesByRoles.success',
                onGetRolesSuccess
            );

            roleServiceEventManager.on(
                'rcmUserAclRoleService.getRulesByRoles.error',
                onError
            );

            rcmUserAclRoleService.getRulesByRoles();
            /* </ROLES> */

            /* <RESOURCES> */
            var onGetResourcesSuccess = function (data, status) {
                $scope.resources = data.data;
                rcmUserSelectedDataService.setData('resources', $scope.resources);
                $scope.resourceCount = $scope.resources.length;
            };

            rcmUserAclResourceService.getResources(
                onGetResourcesSuccess
            );
            /* </RESOURCES> */
        }
    ]
);
