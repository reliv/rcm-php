/**
 * Created by idavis on 9/10/14.
 */

(function () {

    var sessionKeepAlive = null;
    RcmAdminService.rcmEventManager.on(
        'editingStateChange',
        function (page) {
            // Set interval if edit mode and no current interval
            if (page.editMode === true && !sessionKeepAlive) {

                sessionKeepAlive = setInterval(
                    function () {
                        var timestamp = Math.floor(new Date().getTime() / 1000);
                        $.post(
                            '/api/rpc/rcm-admin/keep-alive',
                            {'requestTime': timestamp},
                            function(data){
                                // console.log('keep-alive',data);
                            }
                        );
                    }, 300000
                );
            }
            // just in case
            if (page.editMode === false && sessionKeepAlive) {
                clearInterval(sessionKeepAlive);
            }
        }
    );
})();