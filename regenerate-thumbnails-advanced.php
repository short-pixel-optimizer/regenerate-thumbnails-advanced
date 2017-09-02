<?php
/*
  Plugin Name: reGenerate Thumbnails - advanced
  Plugin URI: http://ciprianturcu.com
  Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.
  Version: 1.5.1.1
  Author: turcuciprian
  Author URI: http://ciprianturcu.com
  License: GPLv2 or later
  Text Domain: rta
 */

//Global variables for arguments
require_once 'inc/rest.php';
require_once 'mediaRows.php';

class cc
{
    //    create basic page in the admin panel, with menu settings too
    public function start()
    {
        //create admin menu page and content
        add_action('admin_menu', array($this, 'create_menu'));
        add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'add_settings_link'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'));
        //ajax callback for button click
        add_action('wp_ajax_rta_ajax', array($this, 'ajax_callback'));
    }

    public function ajax_callback()
    {
    }

//    Admin menu calback
    public function create_menu()
    {
        global $cc_args;
        $args = $cc_args;
//         Add a new submenu under Tools:
        add_options_page(__('reGenerate Thumbnails Advanced', 'rta'), __('Regenerate Thumbnails', 'rta'), 'administrator', 'regenerate_thumbnails_advanced', array($this, 'create_page_callback'));

        return true;
    }

    public function enqueue_admin($hook)
    {
        if (isset($_GET['page']) && isset($hook)) {
            if ($_GET['page'] !== 'regenerate_thumbnails_advanced' && $hook != 'options-general.php ') {
                return;
            }
        }
        wp_enqueue_script('jquery-ui-progressbar');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('rta-jquery-ui', plugin_dir_url(__FILE__).'jquery-ui.min.css');
        wp_enqueue_style('rta', plugin_dir_url(__FILE__).'style.css');
        wp_enqueue_script('rta', plugin_dir_url(__FILE__).'script.js');
        //
        wp_add_inline_script('jquery-migrate', 'var rtaRestURL = \''.site_url().'/wp-json/rta/regenerate\';');
    }

//    Callback for the admin_init hook - this is where the page is created.... text, form fields and all
    public function create_page_callback()
    {
        $total = 1;
        $offset = 0;
        ?>
        <!--GTA wrap START -->
        <div id="rta">
            <div id="no-js">
                <h1><?php echo __('Javascript is not enabled or it has a error!', 'rta');
        ?></h1>
                <p><?php echo __('If there is a error in the page (most likely caused by another plugin or even the theme, the regenerate thumbnails advanced plugin will not work properly. Please fix this issue and come back here. YOU WILL NOT SEE THIS WARNING IF EVERYTHING IS WORKING FINE', 'rta');
        ?></p>
            </div>
            <div id="js-works" class="hidden">
              <h3>Help out.  <span style="color:green;">DONATE how much you want!</span></h3>


 <!-- Paypal donate button START-->
 <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="7EP625Y355BNS">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

<!-- Paypal donate button END-->

<hr>


                <h2><?php echo __('reGenerate Thumbnails Advanced', 'rta');
        ?></h2>
                <!--Progress bar-->
                <div id="progressbar">
                    <div class="progress-label">0&#37;</div>
                </div>
                <!--Information section-->
                <div class="info">
                    <?php echo __('Total number of images:', 'rta');
        ?> <span class="total">0</span><br/>
                    <?php echo __('Images processed:', 'rta');
        ?><span class="processed">0</span><br/>
                                   <!--Could not process: <span class="errors">0</span> Images<br/>-->
                </div>
                <!--Dropdown-->
                <h3>Select a period</h3>
                <select name="period" id="rta_period">
                    <!--get all the images in the database-->
                    <option value="0"><?php echo __('All', 'rta');
        ?></option>
                    <option value="1"><?php echo __('Past Day', 'rta');
        ?></option>
                    <option value="2"><?php echo __('Past Week', 'rta');
        ?></option>
                    <option value="3"><?php echo __('Past Month', 'rta');
        ?></option>
                    <option value="4"><?php echo __('Between Dates', 'rta');
        ?></option>
                </select>
                <div class="fromTo hidden">
                    <p><span><?php echo __('Start Date(including):', 'rta');
        ?><br/><input type="text" class="datepicker start" readonly /></span></p>
                    <p><span><?php echo __('End Date(including):', 'rta');
        ?><br/><input type="text" class="datepicker end"  readonly /></span></p>
                </div>
                <p class="submit">
                    <button class="button button-primary RTA"><?php echo __('Regenerate Thumbnails', 'rta');
        ?></button>
                <div class="wrap">
                    <h3><?php echo __('Progress', 'rta');
        ?></h3>
                    <div class="logstatus ui-widget-content">
                        <?php echo __('Nothing processed yet', 'r ta');
        ?>
                    </div>
                </div><!--where the errors show -->
                <div class="wrap">
                    <h3><?php echo __('Errors', 'rta');
        ?></h3>
                    <div class="errors ui-widget-content">
                        <?php echo __('No errors to display yet', 'rta');
        ?>
                    </div><!-- where the errors show -->
                    </p>
                </div>
                <div class="tutorial">
                  <h2><a href="https://youtu.be/a5F5OsWZC28" target="_blank"><?php echo __('- Tutorial -', 'rta');
        ?></a></h2>
                </div>

            </div>
        </div>


        <!-- Js Works End -->
        <!--GTA wrap END -->
        <?php

    }

    public function add_settings_link($links)
    {
        $mylinks = array(
            '<a href="'.admin_url('options-general.php?page=regenerate_thumbnails_advanced').'">'.__('Settings', 'rta').'</a>',
        );

        return array_merge($links, $mylinks);
    }
}

/* var @cc cc */
$cc = new cc();
$cc->start();

add_action("after_switch_theme", "mytheme_do_something");

function mytheme_do_something () {

add_action( 'admin_notices', 'rta_admin_notice__success' );
}

function rta_admin_notice__success() {
  ?>
  <div class="notice notice-success is-dismissible">
      <p><?php _e( 'You switched themes! Would you like to regenerate thumbnails so that all your thumbnails work on your theme? <a href="options-general.php?page=regenerate_thumbnails_advanced">YES</a>', 'sample-text-domain' ); ?></p>
  </div>
  <?php
}
