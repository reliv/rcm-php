/**
 * rcmuserCore.RcmUserResults
 */
angular.module('rcmuserCore').factory(
    'RcmResults', function () {

        var RcmResults = function () {

            var self = this;

            self.results = [];

            self.add = function (result) {
                self.results.push(result);
            };

            self.remove = function (index) {
                self.results.splice(index, 1);
            };

            self.clear = function () {
                self.results = [];
            }
        };

        return RcmResults;
    }
);
