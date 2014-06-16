<?php

class AGATT_Admin {

    function __construct(){
        add_action( 'admin_menu', array( $this, 'register_agatt_custom_menu_pages' ) );
        add_action( 'admin_init', array($this, 'agatt_settings_page_init'));
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

    public function agatt_settings_page_init(){

        register_setting( 'agatt-group', 'agatt-settings' );

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
            'agatt-goog-analytics',
            array(

              'parent-element'   =>  'scrolldepth', 
              'element'          =>  'scroll_tracking_check',
              'type'             =>  'checkbox'

            )
        );
        # http://code.tutsplus.com/tutorials/create-a-settings-page-for-your-wordpress-theme--wp-20091
        add_settings_field(
            'agatt-goog-analytics-events',
            __('List elements for click tracking', 'agatt'),
            array($this, 'agatt_elements_to_track'),
            AGATT_MENU_SLUG,
            'agatt-goog-analytics',
            array()
        );

    }

    public function agatt_options_page() {
      # Methodology: http://kovshenin.com/2012/the-wordpress-settings-api/
      ?>
      <div class="wrap">
          <h2>Advanced Google Analytics Tracking</h2>
          <form action="options.php" method="POST">
              <?php settings_fields( 'agatt-group' ); ?>
              <?php do_settings_sections( 'agatt-settings' ); ?>
              <?php submit_button(); ?>
          </form>
      </div>
      <?php
    }

    public function agatt_goog_analytics_section(){
      echo 'Set up options for advanced Google Analytics tracking.'
    }

    public function agatt_scroll_tracking(){
      $settings = get_option( 'agatt-settings' );
      echo "<input type='text' name='agatt-settings[]' value='".esc_attr($settings[])."' />";
    }

    public function agatt_user_set_js_variables(){

        ?>
        <script type="text/javascript">
            var agatt_scrolledElements = [];
            <?php
                if (!empty($user_set_scrolledElements)){
                    ?>
                    agatt_scrolledElements = [<?php echo $user_set_scrolledElements; ?>];
                    <?php
                }
            ?>
            var agatt_sd_minHeight = 0;
            var agatt_sd_percentage = true;
            var agatt_sd_userTiming = true;
            var agatt_sd_pixel_Depth = true;
        </script>
        <?php
    }

}
