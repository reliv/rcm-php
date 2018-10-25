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
                $scope.loading = false;

                $scope.saveOk = false;
                $scope.saveFail = false;
                $scope.message = '';

                $scope.isEditable = rcmAdminService.model.RcmPageModel.isEditable();

                if (!$scope.isEditable) {
                    $scope.message = 'This page can not be edited';
                    return;
                }

                //getting title, description and keywords from dom to our form
                var pageData = rcmAdminService.model.RcmPageModel.getData();

                /**
                 *
                 * @param data
                 */
                var syncPageDataToScope = function (data) {
                    $scope.title = data.title;
                    $scope.description = data.description;
                    $scope.keywords = data.keywords;
                    $scope.name = data.name;
                };

                /**
                 *
                 * @param data
                 */
                var syncDataFromApi = function (data) {
                    $scope.title = data.pageTitle;
                    $scope.description = data.description;
                    $scope.keywords = data.keywords;
                    $scope.name = data.name;
                    pageData.page.title = data.pageTitle;
                    pageData.page.description = data.description;
                    pageData.page.keywords = data.keywords;
                    pageData.page.name = data.name;
                };

                /**
                 *
                 * @param loading
                 */
                var loading = function (loading) {
                    $scope.loading = loading;
                    var loadingInt = Number(!loading);
                    rcmLoading.setLoading(
                        'rcmAdminPage.PageProperties.loading',
                        loadingInt
                    );
                };

                /**
                 * updatePageData
                 * @param data
                 */
                var updatePageData = function (data) {

                    if (data.name !== pageData.page.name) {
                        $window.alert(
                            'Page name has been changed, you will be redirect to the new URL.'
                        );
                        loading(true);
                        $window.location = '/' + data.name;
                        return;
                    }

                    syncDataFromApi(data);

                    rcmAdminService.model.RcmPageModel.setData(pageData);
                };

                //save function
                $scope.save = function () {
                    loading(true);

                    $scope.saveOk = false;
                    $scope.saveFail = false;
                    $scope.message = '';

                    var requestData = {
                        pageTitle: $scope.title,
                        description: $scope.description,
                        keywords: $scope.keywords,
                        name: $scope.name
                    };

                    var confirmNameChange = true;

                    if (requestData.name !== pageData.page.name) {
                        confirmNameChange = $window.confirm(
                            'Changing the name of the page will break any existing links ' +
                            'and any unsaved changes will be lost. ' +
                            'are you sure you wish to continue?'
                        );
                    }

                    if (!confirmNameChange) {
                        loading(false);
                        return;
                    }

                    var apiParams = {
                        url: rcmAdminApiUrlService.sitePage,
                        urlParams: {
                            siteId: pageData.page.siteId,
                            pageId: pageData.page.id
                        },
                        data: requestData,
                        loading: loading,
                        success: function (data) {
                            updatePageData(data.data);
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

                var init = function () {
                    syncPageDataToScope(pageData.page);
                };

                init();
            }
        ]
    );
