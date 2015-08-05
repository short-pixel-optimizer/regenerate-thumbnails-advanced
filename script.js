jQuery(document).ready(function ($) {
    var err_arr = [];
    var errors_obj = $('#rta .errors');
    var pbar = $("#rta #progressbar");

//    if the progressbar id exists
    if (pbar[0]) {
//        set the initial value to 0
        pbar.progressbar({
            value: 0
        });
    }
    var rta_butt = $('.button.RTA');
    if (rta_butt[0]) {
        var logstatus = $('#rta .logstatus');
        
        rta_butt.click(submit_ajax_call);
        //
        //LOOP REQUEST ... ajax request to call when the button is pressed
        //
        function submit_ajax_call() {
            logstatus.html('Processing...');
            err_arr=[];
            errors_obj.html('');
            var period = $('#rta_period');
            //    First Time Request
            loop_ajax_request('general', 0, -1, period.val());
            
            
        }
        //
        //
        // Main ajax call
        //
        //
        function loop_ajax_request(type, offset, tCount, period) {

            //tha ajax data
            var data = {
                'action': 'rta_ajax',
                'type': type,
                'period': period,
                'offset': offset
            };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post(ajaxurl, data, function (response) {
                //var err_arr = new Array();
                //json response
                var json = JSON.parse(response);
                var offset = 0;
                var tCount = 0;
                var rta_total = $('#rta .info .total');
                
                // console.log(response);
                switch (type) {
                    case 'general':
                        //console.log(response);
                        var period = $('#rta_period');
                        var rta_total = $('#rta .info .total');
                        var rta_processed = $('#rta .info .processed');
                        if (rta_total[0]) {
                            var json = JSON.parse(response);
                            rta_total.html(json.pCount);
                            rta_processed.html("0");
                        }
                        
                        if (rta_total[0]) {
                            tCount = rta_total.html();
                        }
                        loop_ajax_request('submit', offset, tCount, period.val(), false);

                        break;
                    case 'submit':
//                        console.log(response);
                        if (rta_total[0]) {
                            tCount = rta_total.html();
                        }
                        var processed = $('#rta .info .processed');
                        
                        var progressbar_percentage = $('#progressbar .progress-label');
                        if (processed[0] && rta_total.html()!=0) {
                            processed.html(json.offset);
                        }
                        tCount = parseInt(tCount);
                        response = parseInt(json.offset);
                        if (tCount >= response) {
                            offset = response;

                            var lPercentage = offset / tCount * 100;
                            if (pbar[0]) {
                                if (progressbar_percentage[0]) {
                                }
//                                set the initial value to 0
                                pbar.progressbar({
                                    value: lPercentage
                                });
                                lPercentage = Math.floor(lPercentage) + '%';
                                progressbar_percentage.html(lPercentage);
                            }
                            //Add to log
                            logstatus.html(logstatus.html()+json.logstatus);
                            //call function again
                            if (tCount > response) {
                                //append unique errors
                                unique_arr_append(json.error);
                                //make a new request to the ajax call
                                loop_ajax_request(type, offset, tCount, period);
                            }else{
                                //the loop ended show errors and messages
                                $.each(err_arr,function( index, value ){
                                    var final_val = '<div class="ui-state-error">'+value+'</div>';
                                errors_obj.html(errors_obj.html()+final_val);    
                                });
                                
                            }
                        }else{
                            unique_arr_append(json.error);
                            //the loop ended show errors and messages
                                $.each(err_arr,function( index, value ){
                                    var final_val = '<div class="ui-state-error">'+value+'</div>';
                                errors_obj.html(errors_obj.html()+final_val);    
                                });
                        }
                        break;
                }
            });
        }
        // Append only unique array values
        function unique_arr_append(val){
            var unique = true;
            var i = 0;
            var y = 0;
            while(val[i]){
                unique = true;
                y=0;
                while(err_arr[y]){
                    if(err_arr[y]==val[i]){
                        console.log(err_arr[i]);
                        unique = false;
                        break;
                    }
                    y++;
                }
                if(unique){
                err_arr.push(val[i]);
            }
                i++;
            }
            
        }
    }
});