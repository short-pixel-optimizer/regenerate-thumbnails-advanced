<?php
/*
  Plugin Name: reGenerate Thumbnails - advanced
  Plugin URI: http://ciprianturcu.com
  Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.
  Version: 1.6.2.2
  Author: turcuciprian
  Author URI: http://ciprianturcu.com
  License: GPLv2 or later
  Text Domain: rta
 */

//Global variables for arguments
require_once 'inc/rest.php';
require_once 'mediaRows.php';
require_once 'inc/function_for_media.php';


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
            if($_GET['page'] == 'regenerate_thumbnails_advanced') {
                wp_enqueue_script('rtaReact', plugin_dir_url(__FILE__).'bundle.js',[],null, true );
                wp_add_inline_script('rtaReact', 'let RTArestUrl = \''.site_url().'/wp-json/rta/regenerate\';');
            }
    }

//    Callback for the admin_init hook - this is where the page is created.... text, form fields and all
    public function create_page_callback()
    {
        ?>
            <div id="rtaContent">
            </div>
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
