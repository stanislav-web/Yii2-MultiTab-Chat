"use strict";

/**
 * Message interface
 *
 * @type {{load}}
 */
var Message = (function () {

    var window = document.getElementById('media-list');
    var disableTime = 0;
    var lastId;

    /**
     * Append message
     *
     * @param name
     * @param message
     * @param date
     * @param ip
     * @param location
     * @returns {string}
     */
    var appendMessage = function (name, message, date, ip, location) {

        var username = Storage.get('username');
        var isAuth = username && name.trim() === username.trim();

        var bubbleClassName = (isAuth) ? 'you' : 'me';
        var authorClassName = (isAuth) ? 'pull-right auth' : 'pull-left user';
        var bottomText = (isAuth) ? ip + ' | ' + date : date;

        return '<li class="media">\n' +
            '                        <div class="media-body">\n' +
            '                            <div class="media">\n' +
            '                                <div class="'+authorClassName+' " href="#">' + name + ' ('+location+')</div>\n' +
            '                                <p class="bubble '+bubbleClassName+'">' + message +'<br>'+
            '                                   <small class="text-muted">' + bottomText + ' </small>\n' +
            '                                </p>' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '</li>';
    };

    /**
     * Convert ip int
     *
     * @param ip
     * @returns {*}
     */
    var ip2int = function ip2int(ip) {

        return ip.split('.').reduce(function(ipInt, octet) {
            return (ipInt<<8) + parseInt(octet, 10)
        }, 0) >>> 0;
    };

    /**
     * Scroll to bottom
     */
    var scrollToBottom = function () {
        var shouldScroll = window.scrollTop + window.clientHeight === window.scrollHeight;
        if (!shouldScroll) {
            window.scrollTop = window.scrollHeight;
        }
    };

    /**
     * Request
     *
     * @param url
     */
    var getListRequest = function (url) {

        jQuery.getJSON(url, {lastId: lastId})
            .done(function (data) {

                var row = data.slice(-1).pop();
                if (row) {
                    lastId = row.id;
                }
                var response = getListResponse(data);
                var windowLength = window.innerHTML.trim().length;
                if (!windowLength) {
                    window.innerHTML = response;
                } else {
                    window.insertAdjacentHTML('beforeend', response);
                }
                scrollToBottom();
            });
    };

    /**
     * Response
     *
     * @param data
     */
    var getListResponse = function (data) {

        var container = [];

        data.forEach(function (m) {
            container.push(
                appendMessage(m.username, m.message, m.publication, m.ip, m.location)
            )
        });
        return container.join('');
    };

    return {
        load: getListRequest,
        disableTime: disableTime
    }
})();
