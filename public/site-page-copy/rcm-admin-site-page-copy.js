angular.module('rcmAdmin')
    .controller(
    'rcmAdminSitePageCopyController',
    [
        '$scope', '$http', 'rcmApiService', 'rcmAdminApiUrlService',
        function ($scope, $http, rcmApiService, rcmAdminApiUrlService) {

            var self = this;

            $scope.errorMessage = null;

            $scope.loadings = {
                sourceSite: false,
                destinationSites: false,
                sourcePages: false,
                pageTypes: false,
                copyPage: false
            };

            $scope.step = 1;

            $scope.sourceSite = {};
            $scope.destinationSites = [];
            $scope.sourcePages = [];
            $scope.pageTypes = {};

            $scope.destinationSite = null;

            $scope.selectedPages = [];
            $scope.selectedPageType = null;
            $scope.filteredPages = [];

            /**
             * toggleSelectPage
             * @param page
             */
            $scope.toggleSelectPage = function (page) {

                var index = $scope.selectedPages.indexOf(page);
                if (index < 0) {
                    $scope.selectedPages.push(page);
                } else {
                    $scope.selectedPages.splice(
                        index,
                        1
                    );
                }
            };

            /**
             * clearSelectedPages
             */
            $scope.clearCopyMessages = function () {
                $scope.copyMessages = {
                    count: 0,
                    error: [],
                    success: []
                };

                $scope.showCopyErrors = false;
                $scope.showCopySuccesses = false;
            };
            $scope.clearCopyMessages();

            /**
             * clearSelectedPages
             */
            $scope.clearSelectedPages = function () {
                $scope.selectedPages = [];
            };

            /**
             * selectFilteredPages - Select all of the filtered pages
             */
            $scope.selectFilteredPages = function () {

                if ($scope.filteredPages) {
                    $scope.selectedPages = $scope.filteredPages;
                }
            };

            /**
             * copySelectedPages - Go thur the selected and do the copy
             */
            $scope.copySelectedPages = function () {
                $scope.loadings.copyPage = true;
                $scope.clearCopyMessages();

                angular.forEach(
                    $scope.selectedPages,
                    self.copyPage
                );

                $scope.loadings.copyPage = false;
            };

            /**
             * Copy Page
             * @param page
             */
            self.copyPage = function (page) {

                page.destinationSiteId = $scope.destinationSite.siteId;

                rcmApiService.post(
                    {
                        url: rcmAdminApiUrlService.sitePageCopy,
                        urlParams: {
                            siteId: $scope.sourceSite.siteId,
                            pageId: page.pageId
                        },
                        data: page,
                        success: function (data) {

                            data.page = page;
                            $scope.copyMessages.count++;
                            $scope.copyMessages.success.push(
                                data
                            );
                        },
                        error: function (data) {

                            data.page = page;
                            $scope.copyMessages.count++;
                            $scope.copyMessages.error.push(
                                data
                            );
                        }
                    }
                );
            };

            /**
             * parseMessage - Parse ans set standard error
             * @param result
             */
            self.parseMessage = function (result) {

                $scope.errorMessage = $scope.errorMessage + ' ' + result.message;
            };

            /**
             *
             */
            self.getSourceSite = function () {
                rcmApiService.get(
                    {
                        url: rcmAdminApiUrlService.siteCurrent,

                        loading: function (loading) {
                            $scope.loadings.sourceSite = loading;
                        },
                        success: function (data) {
                            $scope.sourceSite = data.data;
                            self.getSourcePages();
                        },
                        error: function (data) {
                            self.parseMessage(data);
                        }
                    },
                    true
                );
            };

            /**
             * getSourcePages
             */
            self.getSourcePages = function () {
                rcmApiService.get(
                    {
                        url: rcmAdminApiUrlService.sitePages,
                        urlParams: {siteId: $scope.sourceSite.siteId},
                        loading: function (loading) {
                            $scope.loadings.sourcePages = loading;
                        },
                        success: function (data) {
                            self.setSourcePages(data.data);
                        },
                        error: function (data) {
                            self.parseMessage(data);
                        }
                    }
                );
            };

            /**
             * Prepare Source Pages as array so they can be filtered
             * @param sourcePages
             */
            self.setSourcePages = function (sourcePages) {

                $scope.sourcePages = [];

                angular.forEach(
                    sourcePages,
                    function (page) {
                        $scope.sourcePages.push(page);
                    }
                );
            };

            /**
             * getDestinationSites
             */
            self.getDestinationSites = function () {
                rcmApiService.get(
                    {
                        url: rcmAdminApiUrlService.sites,
                        urlParams: {siteId: $scope.sourceSite.siteId},
                        loading: function (loading) {
                            $scope.loadings.destinationSites = loading;
                        },
                        success: function (data) {
                            $scope.destinationSites = data.data;
                        },
                        error: function (data) {
                            self.parseMessage(data);
                        }
                    }
                );
            };

            /**
             * getPageTypes
             */
            self.getPageTypes = function () {
                rcmApiService.get(
                    {
                        url: rcmAdminApiUrlService.pageTypes,
                        loading: function (loading) {
                            $scope.loadings.pageTypes = loading;
                        },
                        success: function (data) {
                            $scope.pageTypes = data.data;
                        },
                        error: function (data) {
                            self.parseMessage(data);
                        }
                    },
                    true
                );
            };

            /**
             * init
             */
            self.init = function () {

                self.getSourceSite();

                self.getPageTypes();

                self.getDestinationSites();
            };

            self.init();
        }
    ]
)

    .filter(
    'rcmAdminPageTypeFilter',
    function () {

        var compareStrDirect = function (stra, strb) {
            stra = ("" + stra).toLowerCase();
            strb = ("" + strb).toLowerCase();

            return (stra == strb);
        };

        return function (input, query) {
            if (!query) {
                return input
            }
            var result = [];

            angular.forEach(
                input, function (page) {
                    if (compareStrDirect(page.pageType, query)) {
                        result.push(page);
                    }
                }
            );

            return result;
        };
    }
)
    .filter(
    'rcmAdminPageNameFilter',
    function () {

        var compareStr = function (stra, strb) {
            stra = ("" + stra).toLowerCase();
            strb = ("" + strb).toLowerCase();

            return stra.indexOf(strb) !== -1;
        };

        return function (input, query) {
            if (!query) {
                return input
            }
            var result = [];

            angular.forEach(
                input, function (page) {
                    if (compareStr(page.name, query) || compareStr(
                            page.pageTitle,
                            query
                        )) {
                        result.push(page);
                    }
                }
            );

            return result;
        };
    }
);