<?php

      global $abNoticesCalled;
      $abNoticesCalled = (isset($abNoticesCalled)?$abNoticesCalled:false);
     include_once ABSPATH.'wp-admin/includes/plugin.php';
     if (!function_exists('ab_admin_notices')) {
         function ab_admin_notices()
         {
             $pluginInstalled = false;
                 include_once ABSPATH.'wp-admin/includes/plugin.php';
             $pluginActive = is_plugin_active('admin-builder/admin_builder.php');

             if(isset($_GET['action']) && $_GET['action']==='install-plugin' && isset($_GET['plugin']) && $_GET['plugin']==='admin-builder'){return;}
             if (!function_exists('get_plugins')) {
                 require_once ABSPATH.'wp-admin/includes/plugin.php';
             }
             $allPlugins = get_plugins();
             foreach ($allPlugins as $key => $value) {
                 if ($key === 'admin-builder/admin_builder.php') {
                     $pluginInstalled = true;
                 }
                     # code...
             }
             if (!$pluginInstalled) {
                 echo '<div class="notice notice-error is-dismissible">';
                 echo '<h3>Step 2: Install Admin Builder</h3>';
                 echo '<p>';
                 echo 'To get the full functionality , install Admin Builder.';
                 echo '</p>';
                 echo '<p>';
                 $install_link = '<a href="'.esc_url(network_admin_url('plugin-install.php?tab=plugin-information&amp;plugin=admin-builder&amp;TB_iframe=true&amp;width=600&amp;height=550')).'" class="thickbox" title="More info about Admin Builder"><span class="button-primary">Install Admin Builder</span></a>';
                 echo $install_link;
                 echo '</p>';
                 echo '</div>';
             } else {
                 if (!$pluginActive) {
                     $url = admin_url();

                     echo '<div class="notice notice-error is-dismissible">';
                     echo '<h3>Step 3(final): Activate Admin Builder!</h3>';
                     echo '<p>';
                       echo 'To get the full functionality , activate Admin Builder <br> <br> <a href="'.$url.'plugins.php"><span class="button-primary">Go to Plugins page</span></a> <br><br> AND click <b>Activate</b> Under Admin builder.';
                     echo '</p>';
                     echo '</div>';
                 }
             }
         }
         if(!$abNoticesCalled){
           add_action('admin_notices', 'ab_admin_notices');
           $abNoticesCalled = true;
         }
     }
            if (class_exists('loadFromPlugin')) {

$theJson = '{"menus":[{"label":"Posts","type":"post","name":"posts","unique":true,"children":[],"$$hashKey":"object:66"},{"label":"Pages","type":"page","name":"pages","unique":true,"children":[],"$$hashKey":"object:67"},{"label":"Reg. Thumbnails","type":"cPage","name":"regenerateThumbnailsAdvanced","unique":false,"children":[{"label":"General Settings","name":"tab1","context":"normal","priority":"default","fields":[{"name":"rtaOTF","type":"checkbox","label":"Auto Regenerate","description":"Check this if you want missing thumbnails to be created on the fly, when needed.","extraText":"Activate","$$hashKey":"object:293"}],"$$hashKey":"object:92"}],"capability":"manage_options","handler":"ab_regenerateThumbnailsAdvanced","pageTitle":"regenerate Thumbnails Settings","$$hashKey":"object:89","pageDescription":"Configure if you want to regenerate thumbnails on the fly or not. Just make sure to check the checkbox if you do want everything to be taken care of regarding unexisting plugins."}]}';
$lfp = new loadFromPlugin();
$lfp->load($theJson);
 }
