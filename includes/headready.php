<?php

class AGATT_HeadReady {

    function __construct(){
        add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ));
        add_filter('print_scripts_array', array($this, 'filter_script_order')); 

        add_action( 'wp_head', array( $this, 'agatt_user_set_js_variables' ), 99999 );
        add_action( 'wp_head', array( $this, 'gen_click_tracks' ), 99999 );        
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
        if (!empty($scrollset['scroll_tracking_check']) && 'true' == $scrollset['scroll_tracking_check']) {
            ?>
            <script type="text/javascript">
                
                <?php
                    if (!empty($scrollset['scrolledElements'])){
                        ?>
                        var agatt_scrolledElements = [<?php echo $scrollset['scrolledElements']; ?>];
                        <?php
                    } else {
                        ?>var agatt_scrolledElements = [];<?php
                    }
                ?>

                <?php
                    if (!empty($scrollset['minHeight'])){
                        ?>
                        var agatt_sd_minHeight = [<?php echo $scrollset['minHeight']; ?>];
                        <?php
                    } else {
                        ?>var agatt_sd_minHeight = 0;<?php
                    }
                ?>              
                
                <?php
                    if (!empty($scrollset['percentage'])){
                        ?>
                        var agatt_sd_percentage = [<?php echo $scrollset['percentage']; ?>];
                        <?php
                    } else {
                        ?>var agatt_sd_percentage = true;<?php
                    }
                ?>                        
                
                <?php
                    if (!empty($scrollset['userTiming'])){
                        ?>
                        var agatt_sd_userTiming = [<?php echo $scrollset['userTiming']; ?>];
                        <?php
                    } else {
                        ?>var agatt_sd_userTiming = true;<?php
                    }
                ?>                        
                <?php
                    if (!empty($scrollset['pixel_Depth'])){
                        ?>
                        var agatt_sd_pixel_Depth = [<?php echo $scrollset['pixel_Depth']; ?>];
                        <?php
                    } else {
                        ?>var agatt_sd_pixel_Depth = true;<?php
                    }
                ?>                        
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
    
    public function add_scripts($hook){
        global $pagenow;

        $ga_js = get_option(AGATT_SLUG.'_google_analytics_js', '');
        $ga_js_a = array($ga_js);
        $deps = array('jquery'); 
        $deps_merged = array_merge($deps, $ga_js_a);
        #var_dump($hook); die();
            wp_register_script(AGATT_SLUG . '-scrolldepth', AGATT_URL . 'library/jquery-scrolldepth/jquery.scrolldepth.js' , $deps);
            wp_register_script(AGATT_SLUG . '-scrolldepth-min', AGATT_URL . 'library/jquery-scrolldepth/jquery.scrolldepth.min.js' , $deps);
        #Replace this with a singleton call later.
        $agatt_settings = get_option( 'agatt_settings', array() );
        
        if (!empty($agatt_settings['scrolldepth']['scroll_tracking_check']) && 'true' == $agatt_settings['scrolldepth']['scroll_tracking_check']){
                    if (SCRIPT_DEBUG){
                        wp_enqueue_script(AGATT_SLUG . '-scrolldepth-min');
                    } else {
                        wp_enqueue_script(AGATT_SLUG . '-scrolldepth');
                    }
        }

    }
    
    public function filter_script_order($array){
        
        $keys_to_unset = array();
        $scripts_to_last = array(AGATT_SLUG . '-scrolldepth', AGATT_SLUG . '-scrolldepth-min');
        foreach($scripts_to_last as $script){
            $key = array_search($script,$array);
            if(false != $key){
                array_push($array, $script);
                $keys_to_unset[] = $key;
            }
        }
        
        foreach ($keys_to_unset as $key){
            if(!empty($key)){
                unset($array[$key]);
            }
        }
        #var_dump($array); die();
        return $array;
        
    }
    
}