/**
 * rcmuserAdminUsersApp
 */
'use strict';

angular.module('rcmuserAdminUsersApp', ['ui.bootstrap', 'rcmuserCore']);

if (typeof rcm != 'undefined') {
    rcm.addAngularModule('rcmuserAdminUsersApp');
}
