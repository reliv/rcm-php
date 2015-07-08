angular.module('rcmAdmin').controller(
    'rcmAdminManageSitesController',
    [
        '$scope', '$http', 'rcmApiService', 'rcmAdminApiUrlService',
        function ($scope, $http, rcmApiService, rcmAdminApiUrlService) {

            var pageData = RcmAdminService.model.RcmPageModel.getData();

            $scope.currentSiteId = pageData.page.siteId;

            var self = this;

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

            getResultsPage(1);

            $scope.pagination = {
                current: 1
            };

            $scope.disableSite = function (site) {
                $scope.loadings[site.siteId] = true;
                $().confirm(
                    'Disable this site?<br><br>' +
                    '<ul>' +
                    '<li>Site Id: ' + site.siteId + '</li>' +
                    '<li>Domain: ' + site.domain + '</li>' +
                    '</ul>',
                    function () {
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
                $().confirm(
                    '<div class="confirm">' +
                    '<h2>Duplicate site ' + site.siteId + '?</h2>' +
                    '<div><span>New Domain: </span>' + $scope.tempSites[site.siteId].domain + '</div>' +
                    '</div>',
                    function () {

                        rcmApiService.post(
                            {
                                url: rcmAdminApiUrlService.siteCopy,
                                data: $scope.tempSites[site.siteId],
                                loading: function (loading) {
                                    $scope.loadings[site.siteId] = loading;
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

            $scope.search = function(){
                getResultsPage(1);
            };

            $scope.pageChanged = function(newPage) {
                getResultsPage(newPage);
            };

            $scope.getCurrentResultsPage = function() {
                getResultsPage($scope.pagination.current);
            };


            function getResultsPage(pageNumber) {
                var pageParam = 'page=' + pageNumber;
                var pageSizeParam = 'page_size=' + $scope.resultsPerPage;
                var queryParam = '';

                if ($scope.keywords.length > 0) {
                    queryParam = 'q=' + $scope.keywords;
                }

                url = rcmAdminApiUrlService.sites;


                $http.get(url + '?' + pageParam + '&' + pageSizeParam + '&' + queryParam)
                    .then(function(result) {
                        $scope.sites = result.data.data.items;
                        $scope.totalItems = result.data.data.itemCount;
                    });
            }
        }
    ]
);
