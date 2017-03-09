angular.module('rcmAdmin').controller(
    'rcmAdminManageSitesController',
    [
        '$scope', '$http', 'rcmApiService', 'rcmAdminApiUrlService', 'rcmLoading',
        function ($scope, $http, rcmApiService, rcmAdminApiUrlService, rcmLoading) {

            var pageData = rcmAdminService.model.RcmPageModel.getData();

            $scope.currentSiteId = pageData.page.siteId;

            var self = this;
            var namespace = 'rcmAdminManageSites';

            $scope.sites = [];
            $scope.languages = {};
            $scope.countries = {};

            $scope.resultsPerPage = 25; // this should match however many results your API puts on one page
            $scope.keywords = '';
            $scope.totalItems = 0;

            $scope.loading = false;
            $scope.loadings = {
                languages: false,
                countries: false
            };
            
            $scope.tempSites = {};

            $scope.message = '';

            $scope.pagination = {
                current: 1
            };

            $scope.disableSite = function (site) {
                $scope.loadings[site.siteId] = true;
                var siteNamespace = namespace + '-' + site.siteId;
                $().confirm(
                    'Disable this site?<br><br>' +
                    '<ul>' +
                    '<li>Site Id: ' + site.siteId + '</li>' +
                    '<li>Domain: ' + site.domainName + '</li>' +
                    '</ul>',
                    function () {
                        rcmLoading.setLoading(siteNamespace, 0);

                        if (site.status == 'A') {
                            site.status = 'D';
                        } else {
                            site.status = 'A';
                        }

                        rcmApiService.put(
                            {
                                url: rcmAdminApiUrlService.site,
                                urlParams: {siteId: site.siteId},
                                data: site,
                                loading: function (loading) {
                                    $scope.loadings[site.siteId] = loading;
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        siteNamespace,
                                        loadingInt
                                    );
                                },
                                success: function (data) {
                                    //Refresh site list
                                    $scope.getCurrentResultsPage();
                                },
                                error: function (data) {
                                    $scope.message = data.message;
                                }
                            }
                        );
                    }
                )
            };

            $scope.showClone = function (site) {

                $scope.tempSites[site.siteId] = angular.copy(site, {});
            };

            $scope.hideClone = function (site) {

                $scope.tempSites[site.siteId] = null;
            };

            $scope.hideCloneComplete = function (site) {

                $scope.tempSites[site.siteId] = null;
                $scope.getCurrentResultsPage();
            };

            $scope.cloneSite = function (site) {
                $scope.loadings[site.siteId] = true;
                var siteNamespace = namespace + '-' + site.siteId;

                $().confirm(
                    '<div class="confirm">' +
                    '<h2>Duplicate site ' + site.siteId + '?</h2>' +
                    '<div><span>New Domain: </span>' + $scope.tempSites[site.siteId].domainName + '</div>' +
                    '</div>',
                    function () {
                        rcmLoading.setLoading(siteNamespace, 0);
                        rcmApiService.post(
                            {
                                url: rcmAdminApiUrlService.siteCopy,
                                data: $scope.tempSites[site.siteId],
                                loading: function (loading) {
                                    $scope.loadings[site.siteId] = loading;
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        siteNamespace,
                                        loadingInt
                                    );
                                },
                                success: function (data) {
                                    $scope.tempSites[site.siteId] = data.data;
                                    $scope.tempSites[site.siteId]['code'] = data.code;
                                    $scope.tempSites[site.siteId]['message'] = data.message;
                                },
                                error: function (data) {
                                    $scope.tempSites[site.siteId] = data.data;
                                    $scope.tempSites[site.siteId]['code'] = data.code;
                                    $scope.tempSites[site.siteId]['message'] = data.message;
                                }
                            }
                        );
                    }
                )
            };

            rcmApiService.get(
                {
                    url: rcmAdminApiUrlService.languages,
                    loading: function (loading) {
                        $scope.loadings.languages = loading;
                        var loadingInt = Number(!loading);
                        rcmLoading.setLoading(
                            namespace + '-languages',
                            loadingInt
                        );
                    },
                    success: function (data) {
                        $scope.languages = data.data;
                    },
                    error: function (data) {
                        $scope.message = data.message;
                    }
                },
                true
            );

            rcmApiService.get(
                {
                    url: rcmAdminApiUrlService.countries,
                    loading: function (loading) {
                        $scope.loadings.countries = loading;
                        var loadingInt = Number(!loading);
                        rcmLoading.setLoading(
                            namespace + '-countries',
                            loadingInt
                        );
                    },
                    success: function (data) {
                        $scope.countries = data.data;
                    },
                    error: function (data) {
                        $scope.message = data.message;
                    }
                },
                true
            );

            $scope.search = function () {
                getResultsPage(1);
            };

            $scope.pageChanged = function (newPage) {
                getResultsPage(newPage);
            };

            $scope.getCurrentResultsPage = function () {
                getResultsPage($scope.pagination.current);
            };

            var getResultsPage = function(pageNumber) {
                var pageParam = 'page=' + pageNumber;
                var pageSizeParam = 'page_size=' + $scope.resultsPerPage;
                var queryParam = '';

                if ($scope.keywords.length > 0) {
                    queryParam = 'q=' + encodeURIComponent($scope.keywords);
                }

                var url = rcmAdminApiUrlService.sites;

                var sitePageNamespace = namespace + 'site-page';

                rcmLoading.setLoading(sitePageNamespace, 0);

                $http.get(url + '?' + pageParam + '&' + pageSizeParam + '&' + queryParam)
                    .then(
                        function (result) {
                            $scope.sites = result.data.data.items;
                            $scope.totalItems = result.data.data.itemCount;
                            rcmLoading.setLoading(sitePageNamespace, 1);
                        }
                    );
            };

            getResultsPage(1);
        }
    ]
);
