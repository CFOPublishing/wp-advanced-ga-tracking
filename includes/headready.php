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
        $viewabilitySet = self::agatt_setting(array('parent_element' => 'viewability'));
        if ((!empty($scrollset['scroll_tracking_check']) && 'true' == $scrollset['scroll_tracking_check'])
              || (!empty($viewabilitySet['viewability_check']) && 'true' == $viewabilitySet['viewability_check'])) {
            ?>
            <script type="text/javascript">
              <?php
			  if (!empty($scrollset['scroll_tracking_check']) && 'true' == $scrollset['scroll_tracking_check']){

                    if (!empty($scrollset['scrolledElements'])){

                        $scrolled = explode(',', $scrollset['scrolledElements']);
                        $elements = '';
                        $c = 0;
                        foreach ($scrolled as $scroll){
                          if ($c > 0) { $e_delim = ', '; } else { $e_delim = ''; }
                          $elements .= $e_delim."'".$scroll."'";
                          $c++;

                        }
                        ?>
                        var agatt_scrolledElements = [<?php echo $elements; ?>];
                        <?php
                    } else {
                        ?>var agatt_scrolledElements = [];<?php
                    }
                ?>

                <?php
                    if (!empty($scrollset['minHeight'])){
                        ?>
                        var agatt_sd_minHeight = <?php echo $scrollset['minHeight']; ?>;
                        <?php
                    } else {
                        ?>var agatt_sd_minHeight = 0;<?php
                    }
                ?>

                <?php
                    if (!empty($scrollset['percentage'])){
                        ?>
                        var agatt_sd_percentage = <?php echo $scrollset['percentage']; ?>;
                        <?php
                    } else {
                        ?>var agatt_sd_percentage = true;<?php
                    }
                ?>

                <?php
                    if (!empty($scrollset['userTiming'])){
                        ?>
                        var agatt_sd_userTiming = <?php echo $scrollset['userTiming']; ?>;
                        <?php
                    } else {
                        ?>var agatt_sd_userTiming = true;<?php
                    }
                ?>
                <?php
                    if (!empty($scrollset['pixel_Depth'])){
                        ?>
                        var agatt_sd_pixel_Depth = <?php echo $scrollset['pixel_Depth']; ?>;
                        <?php
                    } else {
                        ?>var agatt_sd_pixel_Depth = true;<?php
                    }

                }
                if (!empty($viewabilitySet['viewability_check']) && 'true' == $viewabilitySet['viewability_check']){
                    self::gen_viewability_fields();

                    if (!empty($viewabilitySet['reportInterval'])){
                        ?>
                        var agatt_sd_viewability_reportInterval = <?php echo $viewabilitySet['reportInterval']; ?>;
                        <?php
                    } else {
                        ?>var agatt_sd_viewability_reportInterval = 15;<?php
                    }

                    if (!empty($viewabilitySet['percentOnScreen'])){
                        ?>
                        var agatt_sd_viewability_percentOnScreen = <?php echo '"'.$viewabilitySet['percentOnScreen'].'"'; ?>;
                        <?php
                    } else {
                        ?>var agatt_sd_viewability_percentOnScreen = "50%";<?php
                    }

                    if (!empty($viewabilitySet['googleAnalytics'])){
                        ?>
                        var agatt_sd_viewability_googleAnalytics = <?php echo $viewabilitySet['googleAnalytics']; ?>;
                        <?php
                    } else {
                        ?>var agatt_sd_viewability_googleAnalytics = true;<?php
                    }

                }
                ?>


                jQuery(document).ready(function( $ ) {

                    <?php
                    if (!empty($scrollset['scroll_tracking_check']) && 'true' == $scrollset['scroll_tracking_check']){
                    ?>

                      $.scrollDepth({
                        minHeight: agatt_sd_minHeight,
                        elements: agatt_scrolledElements,
                        percentage: agatt_sd_percentage,
                        userTiming: agatt_sd_userTiming,
                        pixelDepth: agatt_sd_pixel_Depth
                      });
                    <?php
                    }

                    if (!empty($viewabilitySet['viewability_check']) && 'true' == $viewabilitySet['viewability_check']){
                    ?>

                      $.screentime({
                          fields: viewability_fields,
                          reportInterval: agatt_sd_viewability_reportInterval,
                          percentOnScreen: agatt_sd_viewability_percentOnScreen,
                          googleAnalytics: agatt_sd_viewability_googleAnalytics
                      })
                    <?php
                    }
                    ?>

                });
            </script>
            <?php
        }
    }

    public function gen_click_tracks(){
        $track_these = self::agatt_setting(array('parent_element' => 'click_tracker', 'element' => 'track_these_elements'));
        agatt()->create_basic_jquery_ga_click_events($track_these);
    }

    public function gen_viewability_fields(){
      $track_these = self::agatt_setting(array('parent_element' => 'viewable_tracker', 'element' => 'track_these_viewable_elements'));
      self::create_viewability_fields($track_these);
    }

    public function create_viewability_fields($args){
        $count = count($args);
        $c = 0;
        echo 'var viewability_fields = [';
        foreach ($args as $field){
          $c++;
          echo '{';

          echo '  selector: "'.$field['selector'].'",';
          echo '  name:  "'.$field['name'].'"';

          if ($count != $c){
            echo '},';
          } else {
            echo '}';
          }
        }
        echo '];';
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
