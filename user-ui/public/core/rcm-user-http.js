/**
 * rcmuserCore.RcmUserHttp
 */
angular.module('rcmuserCore').factory(
    'RcmUserHttp',
    [
        '$log',
        '$http',
        'RcmUserResult',
        'RcmResults',
        'rcmUserEventManager',
        function (
            $log,
            $http,
            RcmUserResult,
            RcmResults,
            rcmUserEventManager
        ) {
            var RcmUserHttp = function () {

                var self = this;

                self.getEventManager = function () {
                    return rcmUserEventManager;
                };

                self.http = $http;
                self.comErrorMessage = 'There was an error talking to the server: ';
                self.includeSuccessAlerts = false;
                self.alerts = new RcmResults();

                var loadingCount = 0;

                /**
                 * onLoading
                 * @param namespace
                 * @param loading
                 */
                var loading = function (namespace, loading) {
                    if (loading) {
                        loadingCount++;
                    } else {
                        if (loadingCount > 0) {
                            loadingCount--;
                        }
                    }

                    if (namespace) {
                        rcmUserEventManager.trigger(
                            namespace + '.loading',
                            loading
                        );
                    }

                    rcmUserEventManager.trigger(
                        'RcmUserHttp.loading',
                        (loadingCount > 0)
                    );
                };

                /**
                 * onApiSuccess
                 * @param namespace
                 * @param onSuccess
                 * @param data
                 * @param status
                 * @param headers
                 * @param config
                 */
                var onApiSuccess = function (namespace, onSuccess, data, status, headers, config) {

                    if (self.includeSuccessAlerts) {

                        if (data.messages.length == 0) {
                            //$log.log('default-success-alert');
                            data.messages.push("Success!")
                        }

                        self.alerts.add(data);
                    }

                    if (namespace) {
                        rcmUserEventManager.trigger(namespace + '.success', data);
                    }

                    if (typeof(onSuccess) === 'function') {
                        onSuccess(data, status, headers, config);
                    }

                    loading(namespace, false);
                };

                /**
                 * onApiError
                 * @param namespace
                 * @param onError
                 * @param data
                 * @param status
                 * @param headers
                 * @param config
                 */
                var onApiError = function (namespace, onError, data, status, headers, config) {

                    self.alerts.add(data);

                    if (namespace) {
                        rcmUserEventManager.trigger(namespace + '.error', data);
                    }

                    if (typeof(onError) === 'function') {
                        onError(data, status, headers, config);
                    }

                    loading(namespace, false);
                };

                var beforeExecute = function (eventNamespace) {
                    loading(eventNamespace, true);
                    self.alerts.clear();
                };

                /**
                 * execute
                 * @param config
                 * @param onSuccess
                 * @param onError
                 * @param eventNamespace
                 */
                self.execute = function (config, onSuccess, onError, eventNamespace) {
                    beforeExecute(eventNamespace);
                    self.http(config)
                        .success(
                            function (data, status, headers, config) {
                                // !if is result object
                                if (typeof(data.code) === 'undefined' || typeof(data.messages) === 'undefined') {
                                    $log.error('Result object not returned: ', data);
                                    var failResult = new RcmUserResult(
                                        0,
                                        ['Error: Invalid result returned from server.'],
                                        data
                                    );

                                    onApiError(eventNamespace, onError, failResult, status, headers, config);
                                    return;
                                }

                                if (data.code < 1) {
                                    onApiError(eventNamespace, onError, data, status, headers, config);
                                    return;
                                }

                                onApiSuccess(eventNamespace, onSuccess, data, status, headers, config);
                            }
                        )
                        .error(
                            function (data, status, headers, config) {

                                var failResult = new RcmUserResult(
                                    0,
                                    [self.comErrorMessage + status],
                                    data
                                );

                                $log.error(failResult);

                                onApiError(eventNamespace, onError, failResult, status, headers, config);
                            }
                        );
                }
            };

            return new RcmUserHttp();
        }
    ]
);
