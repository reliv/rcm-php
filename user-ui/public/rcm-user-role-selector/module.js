/**
 * rcmUserRoleSelector
 */
angular.module('rcmUserRoleSelector', ['rcmUserRolesService']);

if (typeof rcm != 'undefined') {
    rcm.addAngularModule('rcmUserRoleSelector');
}
