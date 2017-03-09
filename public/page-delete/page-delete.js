/**
 * page-delete
 */
rcm.addAngularModule('rcmAdminPageDelete');
/**
 * rcmAdminPage.PageProperties
 */
angular.module('rcmAdminPageDelete', ['rcmApi', 'rcmAdminApi'])
    .controller(
        'AdminPageDelete',
        [
            '$scope',
            '$timeout',
            '$window',
            'rcmApiService',
            'rcmAdminApiUrlService',
            function (
                $scope,
                $timeout,
                $window,
                rcmApiService,
                rcmAdminApiUrlService
            ) {
                var data = null;

                $scope.loading = true;
                $scope.saveOk = false;
                $scope.saveFail = false;
                $scope.message = '';
                $scope.canDelete = true;

                /**
                 * deletePage
                 */
                $scope.deletePage = function () {

                    $scope.saveOk = false;
                    $scope.saveFail = false;
                    $scope.message = '';

                    var apiParams = {
                        url: rcmAdminApiUrlService.sitePage,
                        urlParams: {
                            siteId: data.page.siteId,
                            pageId: data.page.id
                        },
                        // data: {},
                        loading: function (loading) {
                            $scope.loading = loading;
                        },
                        success: function (data) {
                            $scope.saveOk = true
                            $timeout(
                                function () {
                                    $window.location.href = '/';
                                },
                                2000
                            );
                        },
                        error: function (data) {
                            $scope.saveFail = true;
                            $scope.message = 'An error occurred while deleting page: ' + data.message
                        }
                    };
                    // this service the put acts as a patch
                    rcmApiService.del(apiParams);
                };

                /**
                 * init
                 */
                var init = function () {

                    data = rcmAdminService.model.RcmPageModel.getData();

                    if (!data.page.id) {
                        $scope.canDelete = false;
                        $scope.message = 'Not a CMS page';
                        $scope.loading = false;
                        return;
                    }

                    // Prevent us from deleting a not-found page
                    if (data.page.name != data.requestedPage.name) {
                        $scope.canDelete = false;
                        $scope.message = 'The page displayed is not the page requested'
                    }

                    $scope.loading = false;
                };

                init();
            }
        ]
    );
