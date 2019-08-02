'use strict';
/**
 * Global cache
 * @type {{empty: Function, getCache: Function, roles: {}, selectedRoles: {}}}
 */
rcmUser.cache = {
    empty: function (cacheKey) {
        var key;
        for (key in rcmUser.cache[cacheKey]) {
            if (rcmUser.cache[cacheKey].hasOwnProperty(key)) {
                return false;
            }
        }
        return true;
    },
    getCache: function (cacheKey) {

        if (rcmUser.cache[cacheKey]) {
            return rcmUser.cache[cacheKey];
        }

        return {};
    },
    rolesState: 'Initial',
    roles: null,
    rolesIndex: null,
    selectedRoles: {}
};
