// noinspection JSUnusedGlobalSymbols
/**
 *  Message interface
 *
 * @type {{load, disableTime}}
 */
var Message = (function () {

    "use strict";

    var window = document.getElementById('media-list');
    var disableTime = 0;
    var lastId = 0;

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
            '                                <div class="'+authorClassName+' ">' + name + ' ('+location+')</div>\n' +
            '                                <p class="bubble '+bubbleClassName+'">' + message +'<br>'+
            '                                   <small class="text-muted">' + bottomText + ' </small>\n' +
            '                                </p>' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '</li>';
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

        var path = url + '/' + lastId;

        // noinspection Annotator
        jQuery.get(path) // jshint ignore:line
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

        /**
         * @param m.message
         * @param m.username
         * @param m.publication
         * @param m.ip
         * @param m.location
         */
        data.forEach(function (m) {
            container.push(
                appendMessage(m.username, m.message, m.publication, m.ip, m.location)
            );
        });
        return container.join('');
    };

    // noinspection JSUnusedGlobalSymbols
    return {
        load: getListRequest,
        disableTime: disableTime
    };
})();
