"use strict";

/**
 * Session storage interface
 *
 * @type {{set: Storage.set, get: Storage.get, remove: Storage.remove, destroy: Storage.destroy}}
 */
var Storage = {

    /**
     * Set key to session storage
     *
     * @param key
     * @param value
     * @param secret
     * @returns {*}
     */
    set: function (key, value, secret) {

        if (typeof value === 'object') {
            //noinspection AssignmentToFunctionParameterJS
            value = JSON.stringify(value);
        }

        setTimeout(function() {
            sessionStorage.setItem(key, value);
        }, 10);
    },

    /**
     * Get key from session storage
     *
     * @uses store
     * @param key
     * @returns {*}
     */
    get: function (key) {

        var data = sessionStorage.getItem(key), value;
        try {
            value = JSON.parse(data);
        }
        catch(e) {
            value = data;
        }

        return value;
    },

    /**
     * Remove key from session storage
     *
     * @param key
     */
    remove: function (key) {

        sessionStorage.removeItem(key);

        return  this;
    },

    /**
     * Destroy all keys
     */
    destroy: function () {
        sessionStorage.clear();
    }
};