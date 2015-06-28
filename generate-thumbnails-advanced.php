<?php

/*
  Plugin Name: Generate Thumbnails - advanced
  Plugin URI: http://turcuciprian.com
  Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.
  Version: 0.1
  Author: turcuciprian
  Author URI: http://turcuciprian.com
  License: GPLv2 or later
  Text Domain: gta
 */

//Global variables for arguments

class cc {

//    create basic page in the admin panel, with menu settings too
    public function start() {
        //create admin menu page and content
        add_action('admin_menu', array($this, 'create_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'));
    }

//    Admin menu calback
    public function create_menu() {
        global $cc_args;
        $args = $cc_args;
//         Add a new submenu under Tools:
        add_options_page(__('Generate Thumbnails Advanced', 'gta_id'), __('GT Adv', 'gta_id'), 'administrator', 'generate_thumbnails_advanced', array($this, 'create_page_callback'));
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
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_style('gta-jquery-ui', plugin_dir_url(__FILE__) . 'jquery-ui.css');
        wp_enqueue_script('gta', plugin_dir_url(__FILE__) . 'script.js');
    }

//    Callback for the admin_init hook - this is where the page is created.... text, form fields and all
    public function create_page_callback() {
        $content .= sprintf("<h2>%s</h2>", 'Generate Thumbnails Advanced');
        echo $content;
    }

//    callback function for the add_menu_page - this is where the settings are registered
    public function rapc() {
        
    }

}

/* var @cc cc */
$cc = new cc();
$cc->start();
