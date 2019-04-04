/**
 * RcmHtmlEditorEventManager - Can be replaced with RCM event manager
 * @type {{on: Function, trigger: Function}}
 */
var RcmHtmlEditorEventManager = function () {

    var self = this;

    var events = {};

    self.on = function (event, method) {

        if (!events[event]) {
            events[event] = [];
        }

        events[event].push(method);
    };

    self.trigger = function (event, args) {

        if (events[event]) {
            jQuery.each(
                events[event],
                function (index, value) {
                    value(args);
                }
            );
        }
    };
};
