jQuery(document).ready(function ($) {
    var pbar = $("#rta #progressbar");
//    if the progressbar id exists
    if (pbar[0]) {
//        set the initial value to 0
        pbar.progressbar({
            value: 50
        });
    }
    var rta_butt = $('.button.RTA');
    if (rta_butt[0]) {
        rta_butt.click(function () {
            //tha ajax data
            var data = {
                'action': 'rta_rt',
                'whatever': 1234
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post(ajaxurl, data, function (response) {
                alert('Got this from the server: ' + response);
            });
        });
    }
});