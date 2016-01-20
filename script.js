jQuery(document).ready(function ($) {
    //no js error
    $('#no-js').addClass('hidden');
    $('#js-works').removeClass('hidden');

    //the main script
    var rtaOtf = $('.rtaOtf');
    if(rtaOtf[0]){
     rtaOtf.click(function(){
       otfAjaxRequest($(this).attr('checked'));
     });
    }


        //OTF ajax call
        function otfAjaxRequest(tempVal) {
            //tha ajax data
            var data = {
                'action': 'rtaOtfAjax',
                'tempValue': tempVal,
                'otfVal': 1
            };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post(ajaxurl, data, function (response) {
                console.log(response);

            });
        }
        // Append only unique array values
        function unique_arr_append(val) {
            var unique = true;
            var i = 0;
            var y = 0;
            while (val[i]) {
                unique = true;
                y = 0;
                while (err_arr[y]) {
                    if (err_arr[y] == val[i]) {
//                        console.log(err_arr[i]);
                        unique = false;
                        break;
                    }
                    y++;
                }
                if (unique) {
                    err_arr.push(val[i]);
                }
                i++;
            }

        }
});
