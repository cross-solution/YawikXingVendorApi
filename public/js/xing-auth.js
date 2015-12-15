/**
 * YAWIK
 *
 * License: MIT
 * (c) 2013 - 2015 CROSS Solution <http://cross-solution.de>
 */

/**
 *
 * Author: Mathias Gelhausen <gelhausen@cross-solution.de>
 */
;
(function ($, window) {

    var popup;

    function onLinkClick(event)
    {
        if (popup) {
            return false;
        }

        var url = $(event.currentTarget).attr('href');
        popup = window.open(url, 'fetch xing auth', 'width=480,height=550');

        return false;
    }

    function fetchCompleted()
    {
        popup.close();

        var href = window.location.href;

        if (href.match(/\?/)) {
            window.location.href = href.replace(/\?.*$/, '');
        } else {
            window.location.reload();
        }
    }

    $(function() {
        $('#xing-auth-enable-link').click(onLinkClick);
        $(document).on('fetch_complete.xing-auth.xing-vendor-api', fetchCompleted)
        console.debug(window.location.href);
    });

})(jQuery, window);
 
