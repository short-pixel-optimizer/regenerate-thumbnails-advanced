jQuery(document).ready(function ($) {
    var pbar = $("#rta #progressbar");
//    When the page first loads
    first_load_ajax();
//    if the progressbar id exists
    if (pbar[0]) {
//        set the initial value to 0
        pbar.progressbar({
            value: 0
        });
    }
    var rta_butt = $('.button.RTA');
    if (rta_butt[0]) {
        rta_butt.click(submit_ajax_call);
        //
        //ajax request to call when the button is pressed
        //
        function submit_ajax_call() {
            //tha ajax data
            var data = {
                'action': 'rta_ajax',
                'type': 'general',
                'offset': offset
            };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post(ajaxurl, data, function (response) {
                alert('Got this from the server: ' + response);
            });
        }
        //
        //ajax request to call to return the total and offset values ----- when some options are changed, when page is first loaded
        //
        function first_load_ajax() {
//            set the dropdown box as a object variable
            var rta_period = $('#rta_period');
            //get the value from the dropdown box
            var period = rta_period.val();
            if (rta_period[0]) {
                //tha ajax data
                var data = {
                    'action': 'rta_ajax',
                    'type': 'general'
                };
                var rta_total = $('#rta .info .total');

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                $.post(ajaxurl, data, function (response) {
                    var json = JSON.parse(response);
//                    Store the data as a cookie
                    var rta_total = $('#rta .info .total');
                    if (rta_total[0]) {
                        rta_total.html(json.pCount);
                    }
                });

            }
        }
    }
});