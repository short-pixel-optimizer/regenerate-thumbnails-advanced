jQuery(document).ready(function ($) {
    var pbar = $("#gta #progressbar");
//    if the progressbar id exists
    if (pbar[0]) {
//        set the initial value to 0
        pbar.progressbar({
            value: 0
        });
        //tha ajax data
        var data = {
            'action': 'gta_rt',
            'whatever': 1234
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(ajaxurl, data, function (response) {
            alert('Got this from the server: ' + response);
        });
    }
});