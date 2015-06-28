<?php
$args = array(
    'page_title' => 'Generate Thumbnaiks Advanced',//the name of the page
    'menu_title' => 'GT Adv',//the name of the menu
    'capability' => 'administrator',//who has access to this
    'menu_slug' => 'generate_thumbnails_advanced',//who has access to this
    'icon_url' => '../images/icon.png',//the icon of the menu
    'position' => 81, //settings
    'fields' => array(
        array(
            
        )
    )
);
/* var @cc cc */
$cc = new cc();
$cc->create_admin_page($args);
