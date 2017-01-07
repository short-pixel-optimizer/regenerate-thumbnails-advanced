<?php
/*
  Plugin Name: reGenerate Thumbnails - advanced
  Plugin URI: http://ciprianturcu.com/Regenerate-thumbnails-advanced
  Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.
  Version: 2.0
  Author: turcuciprian
  Author URI: http://ciprianturcu.com
  License: GPLv2 or later
  Text Domain: RTA
 */

add_action('admin_enqueue_scripts', 'enqueue_admin');

function enqueue_admin($hook)
{
    if (isset($_GET['page']) && isset($hook)) {
        if ($_GET['page'] !== 'rta_page' && $hook != 'options-general.php ') {
            return;
        }
    }
    $plugin_root = plugin_dir_url(__FILE__);

 wp_enqueue_script('jquery');
    wp_enqueue_style('jqueryUI', $plugin_root.'css/jquery-ui.css');
    wp_enqueue_style('rta', $plugin_root.'css/style.css');

    wp_register_script('jquery-ui-custom', $plugin_root.'js/jquery-ui.js','jquery');
    wp_enqueue_script('jquery-ui-custom');
    wp_register_script('mainScript', $plugin_root.'js/script.js');
    wp_enqueue_script('mainScript');
}


//admin page declaration
add_action( 'admin_menu', 'admin_menu_callback_rta_page' );
function admin_menu_callback_rta_page(){
  add_menu_page('rta_page', 'RTA', 'manage_options', 'rta_page', 'rta_page_callback', '', null);
}
function rta_page_callback(){
  ?>
  <div class="container-fluid ab">
  <form name="abForm" class="abForm" method="post" action="">
  <?php
  if ($_GET['page'] === 'rta_page') {
    ?>
    <h2>Regenerate Thumbnails Advanced Settings</h2>
    <p class="pageDescription">The main settings page where you get to configure all the options of the plugin.</p>
    <div id="progressbar"></div>
    <?php
  }
  ?>
  </form>
</div>
  <?php
}
