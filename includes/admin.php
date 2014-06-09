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
    
    public function agatt_settings_page(){
        
        add_settings_section(
            'agatt-goog-analytics',
            __('Advanced Google Analytics Options', 'agatt'),
            array($this, 'agatt_goog_analytics_section'),
            AGATT_MENU_SLUG
        );
        
        add_settings_field(
            'agatt-goog-analytics-scroll',
            __('Activate scroll tracking', 'agatt'),
            array($this, 'agatt_scroll_tracking'),
            AGATT_MENU_SLUG,
            'agatt-goog-analytics'
        );
        
        
        submit_button(); 
    
}