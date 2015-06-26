<?php

global $cc_args;

class chipcore {

//    create basic page in the admin panel, with menu settings too
    public function create_admin_page($args) {
        global $cc_args;
        $cc_args = $args;
        //create new top-level menu
        add_menu_page($args['page_title'], $args['menu_title'], $args['capability'], __FILE__, array($shis, 'create_page_callback'), plugins_url($args['icon_url'], __FILE__), $args['position']);
        //call register settings function
        add_action('admin_init', array($this, 'register_admin_page_callback'));
        return true;
    }

//    Callback for the admin_init hook - this is where the page is created.... text, form fields and all
    private function create_page_callback() {
        global $cc_args;
    }

//    callback function for the add_menu_page - this is where the settings are registered
    private function register_admin_page_callback() {
        global $cc_args;
        $args = $cc_args;
        foreach ($args['fields'] as $field) {
            register_setting($field['group'], $field['field_name']);
        }
    }

}
