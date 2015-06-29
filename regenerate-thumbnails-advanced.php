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
        //ajax callback
        add_action('wp_ajax_rta_rt', array($this, 'ajax_callback'));
    }

    public function ajax_callback() {
        $offset = 0;
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
                echo $the_query->post->ID;
            }
        } else {
            // no posts found
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
        add_options_page(__('Generate Thumbnails Advanced', 'rta_id'), __('GT Adv', 'rta_id'), 'administrator', 'generate_thumbnails_advanced', array($this, 'create_page_callback'));
        //call register settings function
        add_action('admin_init', array($this, 'rapc'));
        return true;
    }

    function enqueue_admin($hook) {
        if (isset($_GET['page']) && isset($hook)) {
            if ($_GET['page'] != 'generate_thumbnails_advanced' && $hook != 'options-general.php ') {
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
        $content .= sprintf("<h2>%s</h2>", 'Generate Thumbnails Advanced');
//        Progress bar
        $content .= sprintf('<div id="progressbar">'
                . '<div class="progress-label">0&#37;</div>'
                . '</div>');
//        Dropdown
        $content .= sprintf('<h3>Select a period</h3>');
        $content .= sprintf('<select name="period" id="rta_period">');
        $content .= sprintf('<option value="0">All</option>');
        $content .= sprintf('<option value="1">past day</option>');
        $content .= sprintf('<option value="2">past week</option>');
        $content .= sprintf('<option value="2">past Month</option>');
        $content .= sprintf('</select>');
        $content .= sprintf('<input type="hidden" name="total" value="%s"/>', $total);
        $content .= sprintf('<input type="hidden" name="offset" value="%s"/>', $offset);
        $content .= sprintf('<p class="submit">'
                . '<button class="button button-primary RTA">Regenerate Thumbnails</button>'
                . '</p>');

        $content .= sprintf('</div>'
                . '<!--GTA wrap END -->');

        echo $content;
    }

//    callback function for the add_menu_page - this is where the settings are registered
    public function rapc() {
        // 
    }

}

/* var @cc cc */
$cc = new cc();
$cc->start();
