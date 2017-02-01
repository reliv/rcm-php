/**
 * page-properties
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
            '$window',
            '$scope',
            'rcmApiService',
            'rcmAdminApiUrlService',
            function (
                $window,
                $scope,
                rcmApiService,
                rcmAdminApiUrlService
            ) {

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

                    var requestData = {
                        pageTitle: data.page.title,
                        description: data.page.description,
                        keywords: data.page.keywords,
                        name: $scope.name
                    };

                    RcmAdminService.model.RcmPageModel.setData(data);

                    var apiParams = {
                        url: rcmAdminApiUrlService.sitePage,
                        urlParams: {
                            siteId: data.page.siteId,
                            pageId: data.page.id
                        },
                        data: requestData,
                        loading: function (loading) {
                            $scope.loading = loading;
                            var loadingInt = Number(!loading);
                            rcmLoading.setLoading(
                                'rcmAdminPage.PageProperties.loading',
                                loadingInt
                            );
                        },
                        success: function (data) {
                            if(data.data.name !== requestData.name) {

                            }
                            $scope.saveOk = true
                        },
                        error: function (data) {
                            $scope.saveFail = true;
                            $scope.message = 'An error occurred while saving data: ' + data.message
                        }
                    };
                    // this service the put acts as a patch
                    rcmApiService.put(apiParams);
                };

                var getPage = function () {
                    var apiParams = {
                        url: rcmAdminApiUrlService.sitePage,
                        urlParams: {
                            siteId: data.page.siteId,
                            pageId: data.page.id
                        },
                        loading: function (loading) {
                            $scope.loading = loading;
                            var loadingInt = Number(!loading);
                            rcmLoading.setLoading(
                                'rcmAdminPage.PageProperties.loading',
                                loadingInt
                            );
                        },
                        success: function (data) {
                            console.log(data);
                        },
                        error: function (data) {
                            $scope.message = 'An error occurred while loading data: ' + data.message
                        }
                    };
                    // this service the put acts as a patch
                    rcmApiService.put(apiParams);
                };

                var init = function () {

                };

                init();
            }
        ]
    );
