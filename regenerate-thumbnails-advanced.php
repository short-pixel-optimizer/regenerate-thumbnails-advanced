<?php
/*
  Plugin Name: reGenerate Thumbnails - advanced
  Plugin URI: http://turcuciprian.com
  Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.
  Version: 1.3.4
  Author: turcuciprian
  Author URI: http://turcuciprian.com
  License: GPLv2 or later
  Text Domain: rta
 */

//Global variables for arguments
require_once("rest.php");
require_once("mediaRows.php");
class cc {

//    create basic page in the admin panel, with menu settings too
    public function start() {
        //create admin menu page and content
        add_action('admin_menu', array($this, 'create_menu'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'));
        //ajax callback for button click
        add_action('wp_ajax_rta_ajax', array($this, 'ajax_callback'));
    }

    public function ajax_callback() {

    }

//    Admin menu calback
    public function create_menu() {
        global $cc_args;
        $args = $cc_args;
//         Add a new submenu under Tools:
        add_options_page(__('reGenerate Thumbnails Advanced', 'rta_id'), __('Regenerate Thumbnails', 'rta_id'), 'administrator', 'regenerate_thumbnails_advanced', array($this, 'create_page_callback'));
        return true;
    }

    function enqueue_admin($hook) {
        if (isset($_GET['page']) && isset($hook)) {
            if ($_GET['page'] !== 'regenerate_thumbnails_advanced' && $hook != 'options-general.php ') {
                return;
            }
        }
        wp_enqueue_script('jquery-ui-progressbar');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('rta-jquery-ui', plugin_dir_url(__FILE__) . 'jquery-ui.min.css');
        wp_enqueue_style('rta', plugin_dir_url(__FILE__) . 'style.css');
        wp_enqueue_script('rta', plugin_dir_url(__FILE__) . 'script.js');
        //
        wp_add_inline_script( 'jquery-migrate', 'var rtaRestURL = \''.site_url().'/wp-json/rta/regenerate\';' );
    }

//    Callback for the admin_init hook - this is where the page is created.... text, form fields and all
    public function create_page_callback() {
        $total = 1;
        $offset = 0;
        ?>
        <!--GTA wrap START -->
        <div id="rta">
            <div id="no-js">s
                <h1>Javascript is not enabled or it has a error!</h1>
                <p>If there is a error in the page (most likely caused by another plugin or even the theme, the regenerate thumbnails advanced plugin will not work properly. Please fix this issue and come back here. YOU WILL NOT SEE THIS WARNING IF EVERYTHING IS WORKING FINE</p>
            </div>
            <div id="js-works" class="hidden">
                <h2>reGenerate Thumbnails Advanced</h2>
                <!--Progress bar-->
                <div id="progressbar">
                    <div class="progress-label">0&#37;</div>
                </div>
                <!--Information section-->
                <div class="info">
                    Total number of images: <span class="total">0</span><br/>
                    Images processed: <span class="processed">0</span><br/>
                                   <!--Could not process: <span class="errors">0</span> Images<br/>-->
                </div>
                <!--Dropdown-->
                <h3>Select a period</h3>
                <select name="period" id="rta_period">
                    <!--get all the images in the database-->
                    <option value="0">All</option>
                    <option value="1">Past Day</option>
                    <option value="2">Past Week</option>
                    <option value="3">Past Month</option>
                    <option value="4">Between Dates</option>
                </select>
                <div class="fromTo hidden">
                    <p><span>Start Date(including):<br/><input type="text" class="datepicker start" readonly /></span></p>
                    <p><span>End Date(including):<br/><input type="text" class="datepicker end"  readonly /></span></p>
                </div>
                <p class="submit">
                    <button class="button button-primary RTA">Regenerate Thumbnails</button>
                <div class="wrap">
                    <h3>Progress</h3>
                    <div class="logstatus ui-widget-content">
                        Nothing processed yet
                    </div>
                </div><!--where the errors show -->
                <div class="wrap">
                    <h3> Errors</h3>
                    <div class="errors ui-widget-content">
                        No errors to display yet
                    </div><!-- where the errors show -->
                    </p>
                </div>
            </div>
        </div>
        <!-- Js Works End -->
        <!--GTA wrap END -->
        <?php
    }

    public function add_settings_link($links) {
        $mylinks = array(
            '<a href="' . admin_url('options-general.php?page=regenerate_thumbnails_advanced') . '">Settings</a>',
        );
        return array_merge($links, $mylinks);
    }

}

/* var @cc cc */
$cc = new cc();
$cc->start();
