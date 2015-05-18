angular.module('rcmAdmin')
    .controller(
    'rcmAdminCreateSiteController',
    [
        '$scope', '$http', 'rcmApiService', 'rcmAdminApiUrlService',
        function ($scope, $http, rcmApiService, rcmAdminApiUrlService) {

            var self = this;

            $scope.loadings = {
                defaultSite: false,
                themes: false,
                languages: false,
                countries: false,
                createSite: false
            };

            $scope.site = {};
            $scope.themes = {};
            $scope.languages = {};
            $scope.countries = {};

            $scope.done = false;

            $scope.code = 0;
            $scope.message = '';
            $scope.errorMessage = '';

            self.parseMessage = function (result) {
                $scope.errorMessage = $scope.errorMessage + ' ' + result.message;
            };

            self.resetMessage = function (result) {

                $scope.code = 0;
                $scope.message = '';
                $scope.errorMessage = '';
            };

            $scope.reset = function () {

                self.resetMessage();
                $scope.done = false;
            };

            $scope.createSite = function () {
                $scope.loadings.createSite = true;
                self.resetMessage();

                var siteData = self.prepareData($scope.site);

                rcmApiService.post(
                    {
                        url: rcmAdminApiUrlService.sites,
                        data: siteData,
                        prepareErrors: true,
                        loading: function (loading) {
                            $scope.loadings.createSite = loading;
                        },
                        success: function (data) {
                            $scope.site = data.data;
                            $scope.message = data.message;
                            $scope.done = true;
                            $scope.createResult = data;
                            $scope.site.siteId = null;
                        },
                        error: function (data) {
                            $scope.createResult = data;
                            $scope.site.siteId = null;
                            self.parseMessage(data);
                        }
                    }
                );
            };

            self.prepareData = function (site) {

                // make sure we don't sent and Id
                site.siteId = null;
                // force default site layout
                site.siteLayout = 'default';
                // force empty favicon
                site.favIcon = null;

                return site;
            };

            rcmApiService.get(
                {
                    url: rcmAdminApiUrlService.siteDefault,

                    loading: function (loading) {
                        $scope.loadings.defaultSite = loading;
                    },
                    success: function (data) {
                        $scope.site = data.data;
                    },
                    error: function (data) {
                        self.parseMessage(data);
                    }
                },
                true
            );

            rcmApiService.get(
                {
                    url: rcmAdminApiUrlService.themes,

                    loading: function (loading) {
                        $scope.loadings.themes = loading;
                    },
                    success: function (data) {
                        $scope.themes = data.data;
                    },
                    error: function (data) {
                        self.parseMessage(data);
                    }
                },
                true
            );

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
                        self.parseMessage(data);
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
                        self.parseMessage(data);
                    }
                },
                true
            );
        }
    ]
);
