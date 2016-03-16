angular.module('rcmInput').directive(
    'rcmInputLinkUrl',
    [
        '$timeout',
        '$http',
        function ($timeout, $http) {

            var link = function ($scope, element, attributes, ngModelCtrl) {

                $scope.viewValue = '';

                $timeout(
                    function () {
                        $scope.viewValue = ngModelCtrl.$viewValue;
                        $scope.$apply();
                    },
                    0
                );

                var pageUrls = [];

                var sourceUrl = '/rcm-page-search/title';

                $scope.uid = jQuery.fn.generateUUID();

                if (typeof attributes.uid !== 'undefined') {
                    $scope.uid = $scope.$eval(attributes.uid);
                }

                $scope.loading = false;

                var buildAutoComplete = function (response) {

                    for (var key in response.data) {
                        pageUrls.push(key)
                    }

                    // Utilizes jQuery UI autocomplete
                    element.find('input').autocomplete(
                        {
                            source: pageUrls,
                            select: function (value) {

                                $timeout(
                                    function () {
                                        element.find('input').trigger('input');
                                    },
                                    0
                                );
                            }
                        }
                    );

                    $scope.loading = false;
                };

                var getAutoComplete = function () {
                    // prevent duplicate calls
                    if (pageUrls.length > 0) {
                        return;
                    }
                    $scope.loading = true;
                    $http.get(sourceUrl).then(
                        buildAutoComplete
                    );
                };

                var init = function () {
                    getAutoComplete();
                };

                init()
            };

            return {
                link: link,
                scope: {},
                require: 'ngModel',
                templateUrl: '/modules/rcm-admin/rcm-input/rcm-input-link-url.html'
            };
        }
    ]
);
