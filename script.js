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
        rta_butt.click(submit_ajax_call);
        //ajax request to call when the button is pressed
        function submit_ajax_call() {
            //tha ajax data
            var data = {
                'action': 'rta_rt',
                'whatever': 1234
            };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post(ajaxurl, data, function (response) {
                alert('Got this from the server: ' + response);
            });
        }
        //ajax request to call to return the total and offset values ----- when some options are changed, when page is first loaded
        
        function change_ajax_call() {
            //tha ajax data
            var data = {
                'action': 'rta_rt_options',
                'whatever': 1234
            };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post(ajaxurl, data, function (response) {
                alert('Got this from the server: ' + response);
            });
        }        
    }
});