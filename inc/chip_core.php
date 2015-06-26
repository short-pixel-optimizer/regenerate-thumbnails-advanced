<?php

class chipcore {

    function create_admin_page($args) {
        //create new top-level menu
        add_menu_page($args['page_title'], $args['menu_title'], $args['capability'], __FILE__, array($shis, 'create_page_callback'), plugins_url($args['icon_url'], __FILE__), $args['position']);
        //call register settings function
        add_action('admin_init', array($this, 'register_admin_page_callback'));
        return true;
    }

}
