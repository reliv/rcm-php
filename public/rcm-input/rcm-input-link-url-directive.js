angular.module('rcmInput').directive(
    'rcmInputLinkUrl',
    [
        '$timeout',
        '$http',
        function ($timeout, $http) {

            var link = function ($scope, element, attributes) {

                var pageUrls = [];

                var sourceUrl = '/rcm-page-search/title';

                if (typeof attributes.sourceUrl !== 'undefined') {
                    sourceUrl = $scope.$eval(attributes.sourceUrl);
                }


                $scope.label = 'Link URL';

                if (typeof attributes.label !== 'undefined') {
                    $scope.label = $scope.$eval(attributes.label);
                }

                $scope.value = '';

                if (typeof attributes.value !== 'undefined') {
                    $scope.value = $scope.$eval(attributes.value);
                }

                $scope.uid = jQuery.fn.generateUUID();

                if (typeof attributes.uid !== 'undefined') {
                    $scope.uid = $scope.$eval(attributes.uid);
                }

                $scope.loading = false;

                var buildAutoComplete = function (urlList) {

                    for (var key in urlList) {
                        pageUrls.push(urlList[key])
                    }

                    element.autocomplete(
                        {
                            source: pageUrls,
                            select: function () {
                                $timeout(
                                    function () {
                                        element.trigger('input');
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
                templateUrl: '/modules/rcm-admin/rcm-input/rcm-input-link-url.html'
            };
        }
    ]
);
