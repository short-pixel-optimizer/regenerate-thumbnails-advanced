<?php
if (!function_exists('rTARoutesInit')) {
    add_action('rest_api_init', 'rTARoutesInit');
    function rTARoutesInit($generalArr)
    {
        register_rest_route('rta', '/regenerate', array('methods' => 'GET', 'callback' => 'rtaProcess', 'args' => array()));
    }
}
function rtaProcess(){
  
}
