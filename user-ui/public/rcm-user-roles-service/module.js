/**
 * rcmUserRolesService
 */
'use strict';
/** rcmUser from core js **/
rcmUser.rcmUserRolesService = {};

angular.module('rcmUserRolesService', ['rcmuserCore']);

if (typeof rcm != 'undefined') {
    rcm.addAngularModule('rcmUserRolesService');
}
