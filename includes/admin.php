<?php

class AGATT_Admin {

    function __construct(){
        add_action( 'admin_menu', array( $this, 'register_agatt_custom_menu_pages' ) );
    }
    
    /**
	 * Register menu pages
	 */
	function register_agatt_custom_menu_pages() {
        
        add_submenu_page(
            'tools.php',
            __('Advanced Google Analytics', 'agatt'),
            __('Advanced Google Analytics', 'agatt'),
            'manage_options'
            AGATT_MENU_SLUG,
            array($this, 'agatt_settings_page')
        );
        
    }
    
}