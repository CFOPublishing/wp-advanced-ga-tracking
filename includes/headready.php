<?php

class AGATT_HeadReady {

    function __construct(){

        add_action( 'wp_head', array( $this, 'agatt_user_set_js_variables' ) );
        add_action( 'wp_head', array( $this, 'gen_click_tracks' ) );
    }
    
    # Replace calls to this with singleton calls later.
    public function agatt_setting($args, $default = array()){
          # Once we're sure that we've enforced singleton, we'll take care of it that way.
          if (empty($agatt_settings)){
            $agatt_settings = get_option( agatt()->admin->option_name, array() );
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


    public function agatt_user_set_js_variables(){
        $scrollset = self::agatt_setting(array('parent_element' => 'scrolldepth'));
        if ('true' == $scrollset['scroll_tracking_check']) {
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
                jQuery(document).ready(function( $ ) {

                    $.scrollDepth({
                      minHeight: agatt_sd_minHeight,
                      elements: agatt_scrolledElements,
                      percentage: agatt_sd_percentage,
                      userTiming: agatt_sd_userTiming,
                      pixelDepth: agatt_sd_pixel_Depth
                    });

                });
            </script>
            <?php
        }
    }
    
    public function gen_click_tracks(){
        $track_these = self::agatt_setting(array('parent_element' => 'click_tracker', 'element' => 'track_these_elements'));
        agatt()->create_basic_jquery_ga_click_events($track_these);
    }
    
}