/**
 * rcmuserCore
 */
'use strict';

var rcmUser = {};

angular.module('rcmuserCore', []);

if (typeof rcm != 'undefined') {
    rcm.addAngularModule('rcmuserCore');
}
