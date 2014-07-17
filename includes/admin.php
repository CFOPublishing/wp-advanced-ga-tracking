<?php

class AGATT_Admin {

    var $option_name;

    function __construct(){

        $this->option_name = 'agatt_settings';
        add_action( 'admin_menu', array( $this, 'register_agatt_custom_menu_pages' ) );
        add_action( 'admin_init', array($this, 'agatt_settings_page_init'));
        add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ) );
    }

    #Notes http://wordpress.stackexchange.com/questions/100023/settings-api-with-arrays-example

    /**
	 * Register menu pages
	 */
	function register_agatt_custom_menu_pages() {

        add_management_page(
            __('Advanced Google Analytics', 'agatt'),
            __('Advanced Google Analytics', 'agatt'),
            'manage_options',
            AGATT_MENU_SLUG,
            array($this, 'agatt_settings_page')
        );

    }

    public function agatt_settings_page_init(){

        register_setting( 'agatt-group', $this->option_name, array($this, 'agatt_validator') );

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

              'parent_element'   =>  'scrolldepth',
              'element'          =>  'scroll_tracking_check',
              'type'             =>  'checkbox',
              'label_for'        =>  'Turn on scroll tracking. <a href="http://scrolldepth.parsnip.io/" target="_blank">Learn more.</a>',
              'default'          =>  'false'

            )
        );
        add_settings_field(
            'agatt-goog-analytics-scroll-scrolledelements',
            __('Comma seperated list of elements to for scrolldepth to check', 'agatt'),
            array($this, 'agatt_option_generator'),
            AGATT_MENU_SLUG,
            'agatt-goog-analytics',
            array(

              'parent_element'   =>  'scrolldepth',
              'element'          =>  'scrolledElements',
              'type'             =>  'text',
              'label_for'        =>  'Scrolling past these items will trigger an event.',
              'default'          =>  ''


            )
        );
        add_settings_field(
            'agatt-goog-analytics-scroll-minheight',
            __('Minimum Height', 'agatt'),
            array($this, 'agatt_option_generator'),
            AGATT_MENU_SLUG,
            'agatt-goog-analytics',
            array(

              'parent_element'   =>  'scrolldepth',
              'element'          =>  'minHeight',
              'type'             =>  'text',
              'label_for'        =>  'Minimum height',
              'default'          =>  0

            )
        );
        add_settings_field(
            'agatt-goog-analytics-scroll-percentage',
            __('Percentage check', 'agatt'),
            array($this, 'agatt_option_generator'),
            AGATT_MENU_SLUG,
            'agatt-goog-analytics',
            array(

              'parent_element'   =>  'scrolldepth',
              'element'          =>  'percentage',
              'type'             =>  'checkbox',
              'label_for'        =>  'Deactivate to only track scrolling to elements listed above.',
              'default'          =>  'true'

            )
        );
        add_settings_field(
            'agatt-goog-analytics-scroll-usertiming',
            __('User Timing', 'agatt'),
            array($this, 'agatt_option_generator'),
            AGATT_MENU_SLUG,
            'agatt-goog-analytics',
            array(

              'parent_element'   =>  'scrolldepth',
              'element'          =>  'userTiming',
              'type'             =>  'checkbox',
              'label_for'        =>  'Turn on scroll tracking',
              'default'          =>  'true'

            )
        );
        add_settings_field(
            'agatt-goog-analytics-scroll-pixel_depth',
            __('Pixel Depth', 'agatt'),
            array($this, 'agatt_option_generator'),
            AGATT_MENU_SLUG,
            'agatt-goog-analytics',
            array(

              'parent_element'   =>  'scrolldepth',
              'element'          =>  'pixel_Depth',
              'type'             =>  'checkbox',
              'label_for'        =>  'Pixel Depth events',
              'default'          =>  'true'

            )
        );

        # http://code.tutsplus.com/tutorials/create-a-settings-page-for-your-wordpress-theme--wp-20091
        add_settings_field(
            'agatt-goog-analytics-events',
            __('Click Event Tracking', 'agatt'),
            array($this, 'agatt_option_generator'),
            AGATT_MENU_SLUG,
            'agatt-goog-analytics',
            array(
              'parent_element'  =>  'click_tracker',
              'element'         =>  'track_these_elements',
              'type'            =>  'repeating_text',
              'label_for'       =>  '<a href="https://developers.google.com/analytics/devguides/collection/gajs/eventTrackerGuide" target="_blank">Read more about event tracking.</a>.',
              'default'         =>  array(0 => array(
                                        'domElement' => 'body',
                                        'category'   => 'primary_elements',
                                        'action'     => 'click',
                                        'label'      => 'Body Click'
                                    )),
              'fields'          =>  array(
                                        'DOM Element'       =>  'domElement',
                                        'Object Group Name' =>  'category',
                                        'Action'            =>  'action',
                                        'Event Label'       =>  'label'
                                    )
            )
        );

    }

    public function agatt_settings_page() {
      # Methodology: http://kovshenin.com/2012/the-wordpress-settings-api/
      ?>
      <div class="wrap">
          <h2>Advanced Google Analytics Tracking</h2>
          <form action="options.php" method="POST">
              <?php settings_fields( 'agatt-group' ); ?>
              <?php $agatt_settings = get_option( $this->option_name, array() ); ?>
              <?php do_settings_sections( AGATT_MENU_SLUG ); ?>
              <?php submit_button(); ?>
          </form>
          <a href="https://developers.google.com/analytics/devguides/collection/gajs/eventTrackerGuide" target="_blank">Read more about event tracking.</a>.
      </div>
      <?php
    }

    public function agatt_goog_analytics_section(){
      echo 'Set up options for advanced Google Analytics tracking.';
    }

    public function agatt_setting($args, $default = array()){
          # Once we're sure that we've enforced singleton, we'll take care of it that way.
          if (empty($agatt_settings)){
            $agatt_settings = get_option( $this->option_name, array() );
          }
        if (empty($agatt_settings)) {



        } elseif (!empty($args['element'])){
            $r = $agatt_settings[$args['parent_element']][$args['element']];
        } else {
            $r = $agatt_settings[$args['parent_element']];
        }

        if (empty($r)){
            #$default = array($args['parent_element'] => array($args['element'] => ''));
            return $default;
        } else {
            return $r;
        }
    }

    # Method from http://wordpress.stackexchange.com/questions/21256/how-to-pass-arguments-from-add-settings-field-to-the-callback-function
    public function agatt_option_generator($args){
       #echo '<pre>'; var_dump($args); echo '</pre>';  return;
      $parent_element = $args['parent_element'];
      $element = $args['element'];
      $type = $args['type'];
      $label = $args['label_for'];
      $default = $args['default'];
      switch ($type) {
          case 'checkbox':
            $check = self::agatt_setting($args, $default);
            if ('true' == $check){
                $mark = 'checked';
            } else {
                $mark = '';
            }
            echo '<input type="checkbox" name="agatt_settings['.$parent_element.']['.$element.']" value="true" '.$mark.' class="'.$args['parent_element'].' '.$args['element'].'" />  <label for="agatt_settings['.$parent_element.']['.$element.']" class="'.$args['parent_element'].' '.$args['element'].'" >' . $label . '</label>';
            break;
          case 'text':
            echo "<input type='text' name='agatt_settings[".$parent_element."][".$element."]' value='".esc_attr(self::agatt_setting($args, $default))."' class='".$args['parent_element']." ".$args['element']."' /> <label for='agatt_settings[".$parent_element."][".$element."]' class='".$args['parent_element']." ".$args['element']."' >" . $label . "</label>";
            break;
          case 'repeating_text':
            $fields = $args['fields'];
            $c = 0;
            $group = self::agatt_setting($args, $default);
            ?>
            <h3 class="agatt-event">Events to track:</h3>
            <ul class="repeater-container" for="repeat-element-<?php echo $parent_element; ?>-<?php echo $element; ?>" id="repeater-<?php echo $parent_element; echo '-'; echo $element; ?>">
                <?php foreach ($group as $event){
                    if ($c > 0) { $id_c = '-'.$c; } else { $id_c = ''; }
                ?>
                    <li class="repeat-element repeat-element-<?php echo $element; echo ' '; echo $element; echo ' '; echo $parent_element; ?> " id="repeat-element-<?php echo $parent_element.'-'.$element . $id_c; ?>">

                        <?php

                            foreach ($fields as $f_label => $field){
                                echo '<input class="'.$field.'" type="text" name="agatt_settings['.$parent_element.']['.$element.'][element-num-'.$c.']['.$field.']" value="'.esc_attr($event[$field]).'" /> <label class="'.$field.'" for="agatt_settings['.$parent_element.']['.$element.'][element-num-'.$c.']['.$field.']">' . $f_label . '</label><br />';
                            }

                        if ($c>0){
                          echo '<a class="repeat-element-remover" href="#">Remove</a><br /><br />';
                        } else {
                          echo '<a class="repeat-element-remover" style="display:none;" href="#">Remove</a><br /><br />';
                        }
                        ?>
                    </li>
                <?php
                $c++;

                }
				echo '<input type="hidden" id="counter-for-repeat-element-'.$parent_element.'-'.$element.'" name="element-max-id-'.$parent_element.'-'.$element.'" value="'.$c.'">';
                ?>
				<a href="#" class="add-repeater">Track another click event.</a>
            </ul>
            <?php
            break;
      }

    }

    public function add_admin_scripts($hook){
        global $pagenow;

        wp_register_script(AGATT_SLUG . '-admin', AGATT_URL . 'assets/js/wpagatt-admin.js' , array( 'jquery' ));

        if ('tools_page_agatt-menu' == $hook){
            wp_enqueue_script(AGATT_SLUG.'-admin');
        }

    }

    public function agatt_validator($input){
        $output = get_option( $this->option_name );
        #var_dump($input); die();
        return $input;
    }

}
