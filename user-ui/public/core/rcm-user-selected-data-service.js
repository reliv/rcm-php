/**
 * rcmuserCore.rcmUserSelectedDataService
 */
angular.module('rcmuserCore').factory(
    'rcmUserSelectedDataService',
    [
        'rcmUserEventManager',
        function (rcmUserEventManager) {

            var SelectedData = function () {

                var self = this;
                self.data = {};
                
                self.getEventManager = function () {
                    return rcmUserEventManager;
                };

                /**
                 *
                 * @param {string} name
                 * @param {*}      value
                 */
                self.setData = function (name, value) {
                    self.data[name] = value;
                    rcmUserEventManager.trigger(
                        'rcmUserSelectedDataServiceChange.' + name,
                        value
                    );
                }
            };

            return new SelectedData();
        }
    ]
);
