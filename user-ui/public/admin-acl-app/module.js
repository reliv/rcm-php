/**
 * rcmuserAdminAclApp
 */
'use strict';

angular.module('rcmuserAdminAclApp', ['ui.bootstrap', 'rcmuserCore']);

if (typeof rcm != 'undefined') {
    rcm.addAngularModule('rcmuserAdminAclApp');
}
