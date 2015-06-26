<?php
global $cc_args;
class chipcore {
    
    public function create_admin_page($args) {
        global $cc_args;
        $cc_args = $args;
        //create new top-level menu
        add_menu_page($args['page_title'], $args['menu_title'], $args['capability'], __FILE__, array($shis, 'create_page_callback'), plugins_url($args['icon_url'], __FILE__), $args['position']);
        //call register settings function
        add_action('admin_init', array($this, 'register_admin_page_callback'));
        return true;
    }
    private function create_page_callback(){
        global $cc_args;
        
    }
    private function register_admin_page_callback(){
        global $cc_args;
        
    }

}
