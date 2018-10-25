angular.module('rcmInput').directive(
    'rcmInputImage',
    [
        '$timeout',
        'rcmFileChooserService',
        function ($timeout, rcmFileChooserService) {

            var link = function ($scope, element, attributes, ngModelCtrl) {

                $timeout(
                    function () {

                        $scope.viewValue = ngModelCtrl.$viewValue;

                        $scope.filter = {};

                        if (typeof attributes.filter !== 'undefined') {
                            $scope.filter = $scope.$eval(attributes.filter);
                        }

                        $scope.uid = jQuery.fn.generateUUID();

                        if (typeof attributes.uid !== 'undefined') {
                            $scope.uid = $scope.$eval(attributes.uid);
                        }

                        $scope.loading = false;

                        $scope.onChange = function() {
                            ngModelCtrl.$setViewValue($scope.viewValue);
                        };

                        var onUrlSelected = function (url) {
                            $scope.viewValue = url;
                            ngModelCtrl.$setViewValue($scope.viewValue);
                            $scope.loading = false;
                            $scope.$apply();
                        };

                        $scope.browse = function () {
                            $scope.loading = true;
                            rcmFileChooserService.chooseFile(
                                onUrlSelected,
                                $scope.viewValue,
                                $scope.filter
                            );
                        };
                    },
                    0
                );

            };

            return {
                link: link,
                scope: {},
                require: 'ngModel',
                templateUrl: '/modules/rcm-admin/rcm-input/rcm-input-image.html'
            };
        }
    ]
);
