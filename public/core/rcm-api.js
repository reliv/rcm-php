/**
 * General service to wrap standard API JSON returns from RCM
 *  - Deals with failed codes (code 0 = success)
 *  - Creates standard return on error if no standard API JSON object received
 *  - Deals with loading state
 *    See: ApiParams.loading
 *  - Formats error messages (from rcm input filter) into single strings (optional)
 *    See: ApiParams.prepareErrors
 */
angular.module('rcmApi', [])
    .factory(
    'rcmApiService',
    [
        '$http', '$log',
        function ($http, $log) {

            var self = this;

            /**
             * cache
             * @type {{}}
             */
            self.cache = {};

            /**
             * ApiParams
             * @constructor
             */
            self.ApiParams = function () {
                /**
                 * URL of request (can contain parsable params in format {myParam})
                 * @type {string}
                 */
                this.url = '';
                /**
                 * URL Params that will replace parsable params in url
                 * @type {object}
                 */
                this.urlParams = null;
                /**
                 * POST PUT DELETE data
                 * @type {object}
                 */
                this.data = null;
                /**
                 * GET query params
                 * @type {object}
                 */
                this.params = null;
                /**
                 * Prepare errors from input filters if set to true
                 * @type {boolean}
                 */
                this.prepareErrors = false;
                /**
                 * Loading callback, used to track loading state
                 * @param {boolean} loading
                 */
                this.loading = function (loading) {
                };
                /**
                 * Success callback, called if http and API is successful (error code == 0)
                 * @param {object} data
                 */
                this.success = function (data) {
                };
                /**
                 * Error callback, called if http or API is fails (error code > 0)
                 * @param data
                 */
                this.error = function (data) {
                };
            };

            /**
             * ApiData - Format expected from server
             * @constructor
             */
            self.ApiData = function () {
                this.code = null;
                this.message = null;
                this.data = [];
                this.errors = [];
            };

            /**
             * GET
             * @param apiParams
             * @param {bool} cache - if you ask for cache it will try to get it from and set it to the cache
             * @returns {*}
             */
            self.get = function (apiParams, cache) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                if (cache) {

                    if (self.cache[apiParams.url]) {

                        self.apiSuccess(
                            self.cache[apiParams.url],
                            apiParams,
                            'CACHE',
                            null,
                            null
                        );
                        return;
                    }

                    apiParams.cacheId = apiParams.url;
                }

                apiParams.loading(true);

                $http(
                    {
                        method: 'GET',
                        url: apiParams.url,
                        params: apiParams.params // @todo Validate this works for GET query
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * POST
             * @param apiParams
             */
            self.post = function (apiParams) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'POST',
                        url: apiParams.url,
                        data: apiParams.data
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * PATCH
             * @param apiParams
             */
            self.patch = function (apiParams) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'PATCH',
                        url: apiParams.url,
                        data: apiParams.data // angular.toJson(data)
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * PUT
             * @param apiParams
             */
            self.put = function (apiParams) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'PUT',
                        url: apiParams.url,
                        data: apiParams.data
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * DELETE
             * @param apiParams
             */
            self.del = function (apiParams) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'DELETE',
                        url: apiParams.url,
                        data: apiParams.data
                    }
                )
                    .success(
                    function (data, status, headers, config) {
                        self.apiSuccess(data, apiParams, status, headers, config)
                    }
                )
                    .error(
                    function (data, status, headers, config) {
                        self.apiError(data, apiParams, status, headers, config)
                    }
                );
            };

            /**
             * Parse URL string and replace {#} with param value by key
             * @param {string} str
             * @param {array} urlParams
             * @returns {string}
             */
            self.formatUrl = function (str, urlParams) {

                if (typeof urlParams !== 'object' || urlParams === null) {
                    return str;
                }

                for (var arg in urlParams) {
                    str = str.replace(
                        RegExp("\\{" + arg + "\\}", "gi"),
                        urlParams[arg]
                    );
                }

                return str;
            };

            /**
             *
             * @param data
             * @param apiParams
             */
            self.apiError = function (data, apiParams, status, headers, config) {
                $log.error(
                    'An API error occured, status: ' + status + ' returned: ',
                    data
                );

                self.prepareErrorData(
                    data,
                    apiParams,
                    function (data) {
                        apiParams.loading(false);
                        apiParams.error(data);
                    }
                );
            };

            /**
             * apiSuccess
             * @param data
             * @param apiParams
             * @param cacheId
             */
            self.apiSuccess = function (data, apiParams, status, headers, config) {
                // $log.info('An API success: ', data);

                if (data.code > 0 || typeof data !== 'object') {

                    self.prepareErrorData(
                        data,
                        apiParams,
                        function (data) {
                            apiParams.loading(false);
                            apiParams.error(data);
                        }
                    )
                } else {

                    self.prepareData(
                        data,
                        apiParams,
                        function (data) {
                            if (apiParams.cacheId) {
                                self.cache[apiParams.cacheId] = angular.copy(data);
                            }
                            apiParams.loading(false);
                            apiParams.success(data);
                        }
                    );
                }
            };

            /**
             * prepareErrorData
             * @param data
             * @returns {ApiData} data
             */
            self.prepareErrorData = function (data, apiParams, callback) {

                if (typeof data !== 'object' || data === null) {
                    data = new self.ApiData();
                }

                if (!data.code) {
                    data.code = 1;
                }

                if (!data.message) {
                    data.message = 'An unknown error occured while making request.';
                }

                return self.prepareData(data, apiParams, callback);
            };

            /**
             * prepareData
             * @param data
             * @param {boolean} prepareErrors
             * @returns {ApiData} data
             */
            self.prepareData = function (data, apiParams, callback) {

                if (data.errors && apiParams.prepareErrors) {
                    self.prepareErrors(data, callback);
                    return;
                }

                callback(data);
            };

            /**
             * prepareErrors
             * @param data
             * @returns {ApiData} data
             */
            self.prepareErrors = function (data, callback) {

                angular.forEach(
                    data.errors,
                    function (value, key) {
                        if (typeof value === 'object' && value !== null) {
                            angular.forEach(
                                value,
                                function (evalue, ekey) {
                                    data.errors[key] = evalue + ' ';
                                }
                            );
                        }
                    }
                );
                callback(data);
            };

            return self;
        }
    ]
);


/**
 * Exposes Angular service to global scope for use by other libraries
 * - This is to support jQuery and native JavaScript modules and code
 */
var rcmApi = {
    rcmApiService: null // defined in angular
};

/**
 * Angular injector to get rcmApi Module services
 */
angular.injector(['ng', 'rcmApi']).invoke(
    [
        'rcmApiService',
        function (rcmApiService) {
            rcmApi.rcmApiService = rcmApiService;
        }
    ]
);
