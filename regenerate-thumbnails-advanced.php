<?php
/*
  Plugin Name: reGenerate Thumbnails - advanced
  Plugin URI: http://turcuciprian.com
  Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.
  Version: 0.8
  Author: turcuciprian
  Author URI: http://turcuciprian.com
  License: GPLv2 or later
  Text Domain: RTA
 */

//Global variables for arguments

class cc { 

//    create basic page in the admin panel, with menu settings too
    public function start() {
        //create admin menu page and content
        add_action('admin_menu', array($this, 'create_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'));
        //ajax callback for button click
        add_action('wp_ajax_rta_ajax', array($this, 'ajax_callback'));
    }

    public function ajax_callback() {
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
        }
        $offset = 0;
        switch ($type) {
            case 'general':
                $args = array(
                    'post_type' => 'attachment',
                    'posts_per_page' => -1,
                    'post_status' => 'any',
                    'offset' => 0
                );

                if (isset($_POST['period'])) {
                    $period = $_POST['period'];

                    switch ($period) {
                        case '0':
                            break;
                        case '1':
                            $date = '-1 day';
                            break;
                        case '2':
                            $date = '-1 week';
//                            echo $date;
                            break;
                        case '3':
                            $date = '-1 month';
                            break;
                    }
                    if ($period !== 0 && isset($date)) {
                        $period_arr = array(
                            'date_query' => array(
                                array(
                                    'after' => $date,
                                )
                        ));
//                        print_r($date_arr);
                        $args = array_merge($args, $period_arr);
//                        print_r($args);
                    }
                }
                $the_query = new WP_Query($args);
                $post_count = 0;
                if ($the_query->have_posts()) {
                    $post_count = $the_query->post_count;
                }
                $return_arr = array('pCount' => $post_count);
//                return the total number of results
                echo json_encode($return_arr);
                break;
            case 'submit':
                $error = array();
                if (isset($_POST['offset'])) {
                    $offset = $_POST['offset'];
                }
                if (isset($_POST['period'])) {
                    $period = $_POST['period'];

                    $args = array(
                        'post_type' => 'attachment',
                        'post_status' => 'any',
                        'posts_per_page' => 1,
                        'offset' => $offset,
                        'orderby' => 'ID',
                        'order' => 'DESC'
                    );
                    switch ($period) {
                        case '0':
                            break;
                        case '1':
                            $date = '-1 day';

                            break;
                        case '2':
                            $date = '-1 week';

                            break;
                        case '3':
                            $date = '-1 month';

                            break;
                    }
                    if ($period !== 0 && isset($date)) {
                        $period_arr = array(
                            'date_query' => array(
                                array(
                                    'after' => $date,
                                )
                        ));
                        $args = array_merge($args, $period_arr);
                    }
                }

                $args = array(
                    'post_type' => 'attachment',
                    'post_status' => 'any',
                    'posts_per_page' => 1,
                    'offset' => $offset,
                    'orderby' => 'ID',
                    'order' => 'DESC'
                );
                $the_query = new WP_Query($args);
                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {

                        $the_query->the_post();
                        $image_id = $the_query->post->ID;
                        $is_image = true;
                        $fullsizepath = get_attached_file($image_id);
                        //is image:
                        if(!is_array(getimagesize($fullsizepath))){
                            $is_image = false;
                            
                        }
                        if($is_image){
                            if (false === $fullsizepath || !file_exists($fullsizepath))
                                $error[] = '<code>' . esc_html($fullsizepath) . '</code>'; 
    
                            @set_time_limit(900);
                            $metadata = wp_generate_attachment_metadata($image_id, $fullsizepath);
                            //get the attachment name
                            $filename_only = basename( get_attached_file( $image_id ) );
                            if (is_wp_error($metadata)) {
                                $error[] = sprint_f("%s Image ID:%d",$metadata->get_error_message(),$image_id);
                            }
                            if (empty($metadata)) {
                                //$this->die_json_error_msg($image_id, __('Unknown failure reason.', 'regenerate-thumbnails'));
                            $error[] = sprint_f('Unknown failure reason. regenerate-thumbnails %d', $image_id);
                            
                            }else{
                                wp_update_attachment_metadata($image_id, $metadata);
                            }
                        }else{
                            $filename_only = basename( get_attached_file( $image_id ) );
                            
                            $error[]=sprintf('Attachment (<b>%s</b> - ID:%d) is not an image. Skipping',$filename_only,$image_id);
                        }
                    }
                    
                } else {
                    $error[] = "No pictures uploaded";
                }
                
                
                //
                if (!extension_loaded('gd') && !function_exists('gd_info')) {
                   $error[]= "<b>PHP GD library is not installed</b> on your web server. Please install in order to have the ability to resize and crop images";
                }
                //increment offset
                $result = $offset + 1;
                echo json_encode(array('offset'=>($offset+1),'error'=>$error));
                break;
        }
        /* Restore original Post Data */
        wp_reset_postdata();

        wp_die();
    }
//    Admin menu calback
    public function create_menu() {
        global $cc_args;
        $args = $cc_args;
//         Add a new submenu under Tools:
        add_options_page(__('reGenerate Thumbnails Advanced', 'rta_id'), __('rGT Adv', 'rta_id'), 'administrator', 'regenerate_thumbnails_advanced', array($this, 'create_page_callback'));
        return true;
    }

    function enqueue_admin($hook) {
        if (!isset($_GET['page']) && isset($hook)) {
            if ($_GET['page'] !== 'regenerate_thumbnails_advanced' && $hook != 'options-general.php ') {
                return;
            }
        }
        wp_enqueue_script('jquery-ui-progressbar');
        wp_enqueue_style('rta-jquery-ui', plugin_dir_url(__FILE__) . 'jquery-ui.min.css');
        wp_enqueue_style('rta', plugin_dir_url(__FILE__) . 'style.css');
        wp_enqueue_script('rta', plugin_dir_url(__FILE__) . 'script.js');
    }

//    Callback for the admin_init hook - this is where the page is created.... text, form fields and all
    public function create_page_callback() {
        $total = 1;
        $offset = 0;

        $content .= sprintf('<!--GTA wrap START -->'
                . '<div id="rta">');
        $content .= sprintf("<h2>%s</h2>", 'reGenerate Thumbnails Advanced');
//        Progress bar
        $content .= sprintf('<div id="progressbar">'
                . '<div class="progress-label">0&#37;</div>'
                . '</div>');
//        Information section
        $content .= sprintf('<div class="info">'
                . 'Total number of images: <span class="total">0</span><br/>'
                . 'Images processed: <span class="processed">0</span><br/>'
//                . 'Could not process: <span class="errors">0</span> Images<br/>'
                . '</div>');
//        Dropdown
        $content .= sprintf('<h3>Select a period</h3>');
        $content .= sprintf('<select name="period" id="rta_period">');
        //get all the images in the database
        $content .= sprintf('<option value="0">All</option>');
        //
        $content .= sprintf('<option value="1">past day</option>');
        $content .= sprintf('<option value="2">past week</option>');
        $content .= sprintf('<option value="3">past Month</option>');
        $content .= sprintf('</select>');
        $content .= sprintf('<p class="submit">'
                . '<button class="button button-primary RTA">Regenerate Thumbnails</button>'
                . '<h3>Errors</h3>'
                . '<div class="errors ui-widget-content">'
                . 'No errors to display yet.'
                . '</div>'//where the errors show
                . '</p>');

        $content .= sprintf('</div>'
                . '<!--GTA wrap END -->');

        echo $content;
    }

}

/* var @cc cc */
$cc = new cc();
$cc->start();
