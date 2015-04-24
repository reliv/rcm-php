/**
 * RcmEventManager
 * REQUIRES: rcmGuid
 * @constructor
 */
var RcmEventManager = function() {

    var self = this;

    /**
     * events
     * @type {{}}
     */
    var events = {};

    /**
     * promises
     * @type {{}}
     */
    var promises = {};

    /**
     * guid
     * @type {{generate: Function}}
     */
    var guid = rcmGuid;

    /**
     * on - register listener
     * @param event
     * @param method
     * @param [id] - Can specify an even id so only on listener will be registered
     * @param [checkPromise] - For getting results of event on register of listener, in case it already fired
     * @returns {event id}
     */
    self.on = function (event, method, id, checkPromise) {

        if (!events[event]) {
            events[event] = {};
        }

        if(typeof id  === 'undefined' || id === null || id === '') {

            id = guid.generate();
        }

        events[event][id] = method;

        if (checkPromise) {
            honorPromise(event, method);
        }

        return id;
    };

    /**
     * remove listener
     * @param event
     * @param id
     */
    self.remove = function(event, id){

        if (!events[event]) {
            return;
        }

        events[event][id] = undefined;

        try {
            delete events[event][id];
        } catch (e) {

        }
    };

    /**
     * trigger listener
     * @param event
     * @param args
     */
    self.trigger = function (event, args) {

        if (events[event]) {
            jQuery.each(
                events[event],
                function (index, value) {
                    if(typeof value === 'function') {
                        value(args);
                    }
                }
            );

            makePromise(event, args);
        }
    };

    /**
     * makePromise
     * @param event
     * @param args
     */
    var makePromise = function(event, args){

        promises[event] = args;
    };

    /**
     * honorPromise
     * @param event
     * @param method
     */
    var honorPromise = function(event, method){

        if (promises[event]) {
            method(promises[event]);
        }
    };

    /**
     * hasEvent
     * @param event
     * @param id
     * @returns {boolean}
     */
    self.hasEvent = function (event, id) {

        if (!events[event]) {
            return false;
        }

        if (!events[event][id]) {
            return false;
        }

        return (typeof events[event][id] === 'function');
    };

    /**
     *
     * @param event
     * @returns {boolean}
     */
    self.hasEvents = function (event) {

        if (!events[event]) {
            return false;
        }

        jQuery.each(
            events[event],
            function (index, value) {
                if(typeof value === 'function') {
                    return true;
                }
            }
        );

        return false;
    };
};
