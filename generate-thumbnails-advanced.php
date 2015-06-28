<?php

/*
  Plugin Name: Generate Thumbnails - advanced
  Plugin URI: http://turcuciprian.com
  Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.
  Version: 0.1
  Author: turcuciprian
  Author URI: http://turcuciprian.com
  License: GPLv2 or later
  Text Domain: akismet
 */
//Global variables for arguments
global $cc_args;

class cc {

//    create basic page in the admin panel, with menu settings too
    public function create_admin_page() {
        global $cc_args;
        add_action('admin_menu', array($this, 'amc'));
        $cc_args = $args;
    }

//    Admin menu calback
    public function amc() {
        global $cc_args;
        $args = $cc_args;
//         Add a new submenu under Tools:
        add_options_page(__('Generate Thumbnails Advanced', 'gta_id'), __('GT Adv', 'gta_id'), 'administrator', 'generate_thumbnails_advanced', array($this, 'create_page_callback'));
        //call register settings function
        add_action('admin_init', array($this, 'rapc'));
        return true;
    }

//    Callback for the admin_init hook - this is where the page is created.... text, form fields and all
    public function create_page_callback() {
        global $cc_args;
        $args = $cc_args;
        $content .= sprintf("<h2>%s</h2>",'Generate Thumbnails Advanced');
        echo $content;
    }

//    callback function for the add_menu_page - this is where the settings are registered
    public function rapc() {
        global $cc_args;
        $args = $cc_args;
    }
}

/* var @cc cc */
$cc = new cc();
$cc->create_admin_page();
