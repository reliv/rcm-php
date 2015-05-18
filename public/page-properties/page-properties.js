/**
 * Created by idavis on 7/15/14.
 */

rcm.addAngularModule('rcmAdminPage');
/**
 * rcmAdminPage.PageProperties
 */
angular.module('rcmAdminPage', ['rcmApi', 'rcmAdminApi'])
    .controller(
    'PageProperties',
    [
        '$scope',
        'rcmApiService',
        'rcmAdminApiUrlService',
        function ($scope, rcmApiService, rcmAdminApiUrlService) {

            var data = RcmAdminService.model.RcmPageModel.getData();

            $scope.loading = false;

            $scope.saveOk = false;
            $scope.saveFail = false;
            $scope.message = '';

            //getting title, description and keywords from dom to our form
            $scope.title = data.page.title;
            $scope.description = data.page.description;
            $scope.keywords = data.page.keywords;

            //save function
            $scope.save = function () {

                $scope.saveOk = false;
                $scope.saveFail = false;
                $scope.message = '';

                data.page.title = $scope.title;
                data.page.description = $scope.description;
                data.page.keywords = $scope.keywords;

                RcmAdminService.model.RcmPageModel.setData(data);

                var apiParams = {
                    url: rcmAdminApiUrlService.sitePage,
                    urlParams: {
                        siteId: data.page.siteId,
                        pageId: data.page.id
                    },
                    data: {
                        pageTitle: data.page.title,
                        description: data.page.description,
                        keywords: data.page.keywords
                    },
                    loading: function (loading) {
                        $scope.loading = loading;
                    },
                    success: function (data) {
                        $scope.saveOk = true
                    },
                    error: function (data) {
                        $scope.saveFail = true;
                        $scope.message = 'An error occurred while saving data: ' + data.message
                    }
                };
                // this service the put acts as a patch
                rcmApiService.put(apiParams);
            }
        }
    ]
);