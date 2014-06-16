<?php

class AGATT_Admin {

    var $option_name;

    function __construct(){

        $this->option_name = 'agatt_settings';
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

        register_setting( 'agatt-group', $this->option_name );

        add_settings_section(
            'agatt-goog-analytics',
            __('Advanced Google Analytics Options', 'agatt'),
            array($this, 'agatt_goog_analytics_section'),
            AGATT_MENU_SLUG
        );

        add_settings_field(
            'agatt-goog-analytics-scroll',
            __('Activate scroll tracking', 'agatt'),
            array($this, 'agatt_option_generator'),
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
            array($this, 'agatt_option_generator'),
            AGATT_MENU_SLUG,
            'agatt-goog-analytics',
            array(
              'parent-element'  =>  'click_tracker',
              'element'         =>  'track_these_elements',
              'type'            =>  'repeating_text'
            )
        );

    }

    public function agatt_options_page() {
      # Methodology: http://kovshenin.com/2012/the-wordpress-settings-api/
      ?>
      <div class="wrap">
          <h2>Advanced Google Analytics Tracking</h2>
          <form action="options.php" method="POST">
              <?php settings_fields( 'agatt-group' ); ?>
              <?php $settings = get_option( $this->option_name, array() ); ?>
              <?php do_settings_sections( $this->option_name ); ?>
              <?php submit_button(); ?>
          </form>
      </div>
      <?php
    }

    public function agatt_goog_analytics_section(){
      echo 'Set up options for advanced Google Analytics tracking.'
    }

    public function agatt_option_generator($args){

      # Once we're sure that we've enforced singleton, we'll take care of it that way.
      if (empty($settings))
        $settings = get_option( $this->option_name, array() );

      $parent_element = $args['parent_element'];
      $element = $args['element'];
      $type = $args['type'];

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
