<?php

/*
  Plugin Name: reGenerate Thumbnails - advanced
  Plugin URI: http://turcuciprian.com
  Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.
  Version: 0.1
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
        //ajax callback for returning general data (total)
        add_action('wp_ajax_rta_rt_options', array($this, 'ajax_options_callback'));
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
                    'offset' => $offset
                );
                $the_query = new WP_Query($args);
                if ($the_query->have_posts()) {
                    $post_count = $the_query->post_count;
                }
                $return_arr = array('pCount' => $post_count);
                echo json_encode($return_arr);
                break;
            case 'submit':
                if (isset($_POST['offset'])) {
                    $offset = $_POST['offset'];
                }
                $args = array(
                    'post_type' => 'attachment',
                    'post_status' => 'any',
                    'posts_per_page' => 1,
                    'offset' => $offset
                );
                $the_query = new WP_Query($args);
                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {

                        $the_query->the_post();
                        $image_id = $the_query->post->ID;
                        $fullsizepath = get_attached_file($image_id);
                        if (false === $fullsizepath || !file_exists($fullsizepath))
                            $this->die_json_error_msg($image_id, sprintf(__('The originally uploaded image file cannot be found at %s', 'regenerate-thumbnails'), '<code>' . esc_html($fullsizepath) . '</code>'));

                        @set_time_limit(1200);
                        $metadata = wp_generate_attachment_metadata($image_id, $fullsizepath);
                        if (is_wp_error($metadata))
                            $this->die_json_error_msg($image_id, $metadata->get_error_message());
                        if (empty($metadata))
                            $this->die_json_error_msg($image_id, __('Unknown failure reason.', 'regenerate-thumbnails'));
                        wp_update_attachment_metadata($image_id, $metadata);
                        echo "ok";
                    }
                } else {
                    echo "empty?";
                }
                break;
        }
        $offset = 0;

        /* Restore original Post Data */
        wp_reset_postdata();
        wp_die();
    }

    //ajax request to return total nr of images for the main script to use when button is clicked
    public function ajax_options_callback() {
        echo "???";
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
        wp_enqueue_style('rta-jquery-ui', plugin_dir_url(__FILE__) . 'jquery-ui.css');
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
                . 'Could not process: <span class="errors">0</span> Images<br/>'
                . '</div>');
//        Dropdown
        $content .= sprintf('<h3>Select a period</h3>');
        $content .= sprintf('<select name="period" id="rta_period">');
        //get all the images in the database
        $content .= sprintf('<option value="0">All</option>');
        //
        $content .= sprintf('<option value="1">past day</option>');
        $content .= sprintf('<option value="2">past week</option>');
        $content .= sprintf('<option value="2">past Month</option>');
        $content .= sprintf('</select>');
        $content .= sprintf('<p class="submit">'
                . '<button class="button button-primary RTA">Regenerate Thumbnails</button>'
                . '</p>');

        $content .= sprintf('</div>'
                . '<!--GTA wrap END -->');

        echo $content;
    }

}

/* var @cc cc */
$cc = new cc();
$cc->start();
