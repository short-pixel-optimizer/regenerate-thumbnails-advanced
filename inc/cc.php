<?php

global $cc_args;

class cc {

//    create basic page in the admin panel, with menu settings too
    public function create_admin_page($args) {
        global $cc_args;
        add_action('admin_menu', array($this, 'admin_menu_callback'));
        $cc_args = $args;
    }

    public function admin_menu_callback() {
        global $cc_args;
        $args = $cc_args;
        //create new top-level menu
        add_menu_page($args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], array($this, 'create_page_callback'), plugins_url($args['icon_url'], __FILE__), $args['position']);
        //call register settings function
        add_action('admin_init', array($this, 'register_admin_page_callback'));
        return true;
    }

//    Callback for the admin_init hook - this is where the page is created.... text, form fields and all
    public function create_page_callback() {
        global $cc_args;
        $args = $cc_args;
    }

//    callback function for the add_menu_page - this is where the settings are registered
    public function register_admin_page_callback() {
        global $cc_args;
        $args = $cc_args;
        foreach ($args['fields'] as $field) {
            register_setting($field['group'], $field['field_name']);
        }
    }

//    Create form fields and other options
    private function create_field($args) {
        switch ($args['type']) {
            case 'textbox':
                $content .=sprintf('<input type="text" name="%s" id="%s" value="%s" />', $args['name'], $args['name'], $args['value']);
                break;
        }
        return $content;
    }

}
