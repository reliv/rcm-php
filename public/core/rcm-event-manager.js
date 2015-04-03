/**
 * RcmEventManager
 * @constructor
 */
var RcmEventManager = function() {

    var self = this;

    /**
     * events
     * @type {{}}
     */
    self.events = {};

    /**
     * promises
     * @type {{}}
     */
    self.promises = {};

    /**
     * on - register listener
     * @param event
     * @param method
     * @param checkPromise - For checking results of event after it fired
     * @param id - Can specify an even id so only on listener will be registered
     */
    self.on = function (event, method, checkPromise, id) {

        if (!self.events[event]) {
            self.events[event] = [];
        }

        if(typeof id  === 'undefined' || id === null) {
            self.events[event].push(method);

            id = self.events[event].indexOf(method);
        } else {
            self.events[event][id] = method;
        }

        if (checkPromise) {
            self.honorPromise(event, method);
        }

        return id;
    };

    /**
     * clear event and related promises
     * @param event
     */
    //self.clear = function(event, id){
    //    self.events[event] = [];
    //    self.promises[event] = [];
    //};

    /**
     * trigger listener
     * @param event
     * @param args
     */
    self.trigger = function (event, args) {

        if (self.events[event]) {
            jQuery.each(
                self.events[event],
                function (index, value) {
                    value(args);
                }
            );

            self.makePromise(event, args);
        }
    };

    /**
     * makePromise
     * @param event
     * @param args
     */
    self.makePromise = function(event, args){

        self.promises[event] = args;
    };

    /**
     * honorPromise
     * @param event
     * @param method
     */
    self.honorPromise = function(event, method){

        if (self.promises[event]) {
            method(self.promises[event]);
        }
    };

    /**
     *
     * @param event
     * @returns {boolean}
     */
    self.hasEvents = function (event) {

        if (!self.events[event]) {
            return false;
        }

        return (self.events[event].length > 0);
    }
};
