<?php
$pathToSource = realpath(dirname(__FILE__));
   if (strpos($pathToSource, 'themes') !== false) {
       //theme
   $rootURL = get_template_directory_uri().'/admin-builder-wordpress/';
       $rootDIR = get_template_directory().'/admin-builder-wordpress/';
   } else {
       //plugin
     $rootURL = plugin_dir_url(__FILE__).'admin-builder-wordpress/';
       $rootDIR = plugin_dir_path(__FILE__).'admin-builder-wordpress/';
   }

$exportFile = $rootDIR.'admin_builder.php';

if (is_file($exportFile)) {
    require_once $exportFile;
}
if (class_exists('loadFromPlugin')) {

$theJson = '{"menus":[{"label":"Posts","type":"post","name":"posts","unique":true,"children":[],"$$hashKey":"object:66"},{"label":"Pages","type":"page","name":"pages","unique":true,"children":[],"$$hashKey":"object:67"},{"label":"Reg. Thumbnails","type":"cPage","name":"regenerateThumbnailsAdvanced","unique":false,"children":[{"label":"General Settings","name":"tab1","context":"normal","priority":"default","fields":[{"name":"rtaOTF","type":"checkbox","label":"Auto Regenerate","description":"Check this if you want missing thumbnails to be created on the fly, when needed.","extraText":"Activate","$$hashKey":"object:293"}],"$$hashKey":"object:92"}],"capability":"manage_options","handler":"ab_regenerateThumbnailsAdvanced","pageTitle":"regenerate Thumbnails Settings","$$hashKey":"object:89","pageDescription":"Configure if you want to regenerate thumbnails on the fly or not. Just make sure to check the checkbox if you do want everything to be taken care of regarding unexisting plugins."}]}';
$lfp = new loadFromPlugin();
$lfp->load($theJson);
 }
