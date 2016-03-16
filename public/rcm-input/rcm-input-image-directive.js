angular.module('rcmInput').directive(
    'rcmInputImage',
    [
        'rcmFileChooserService',
        function (rcmFileChooserService) {

            var link = function ($scope, element, attributes) {

                $scope.label = 'Image';

                if (typeof attributes.label !== 'undefined') {
                    $scope.label = $scope.$eval(attributes.label);
                }

                $scope.value = '';

                if (typeof attributes.value !== 'undefined') {
                    $scope.value = $scope.$eval(attributes.value);
                }

                $scope.filter = {};

                if (typeof attributes.filter !== 'undefined') {
                    $scope.filter = $scope.$eval(attributes.filter);
                }

                $scope.uid = jQuery.fn.generateUUID();

                if (typeof attributes.uid !== 'undefined') {
                    $scope.uid = $scope.$eval(attributes.uid);
                }

                $scope.loading = false;

                var onUrlSelected = function (url) {
                    $scope.value = url;
                    $scope.loading = false;
                };

                $scope.browse = function () {
                    $scope.loading = true;
                    rcmFileChooserService.chooseFile(
                        onUrlSelected,
                        $scope.value,
                        $scope.filter
                    );
                };
            };

            return {
                link: link,
                templateUrl: '/modules/rcm-admin/rcm-input/rcm-input-image.html'
            };
        }
    ]
);
