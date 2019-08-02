/**
 * Global Event Manager
 * @type {{events: {}, on: Function, trigger: Function}}
 */
rcmUser.eventManager = {

    events: {},

    on: function (event, method) {

        if (!this.events[event]) {
            this.events[event] = [];
        }

        this.events[event].push(method);
    },

    trigger: function (event, args) {

        if (this.events[event]) {
            jQuery.each(
                this.events[event],
                function (index, value) {
                    value(args);
                }
            );
        }
    },
    hasEvents: function (event) {

        if (!this.events[event]) {
            return false;
        }

        if (this.events[event].length > 0) {
            return true;
        }

        return false;
    }
};

angular.module('rcmuserCore').factory(
    'rcmUserEventManager',
    function () {
        return rcmUser.eventManager;
    }
);
