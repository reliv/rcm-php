/** RcmSwitch User Module **/
if (typeof rcm != 'undefined') {
    // RCM is undefined in unit tests
    rcm.addAngularModule('rcmSwitchUser');
}
angular.module('rcmSwitchUser', ['RcmLoading', 'RcmJsLib', 'rcmApiLib']);
