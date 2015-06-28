jQuery(document).ready(function ($) {
    var pbar = $("#gta #progressbar");
//    if the progressbar id exists
    if (pbar[0]) {
//        set the initial value to 0
                pbar.progressbar({
                    value: 60
                });
    }
});