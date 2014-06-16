<?php

class AGATT_HeadReady {

    function __construct(){

        add_action( 'wp_head', array( $this, 'agatt_user_set_js_variables' ) );
    }
    
    # Replace calls to this with singleton calls later.
    public function agatt_setting($args){
          # Once we're sure that we've enforced singleton, we'll take care of it that way.
          if (empty($agatt_settings)){
            $agatt_settings = get_option( 'agatt_settings', array() );
          }
        if (!empty($args['element'])){
            return $agatt_settings[$args['parent_element']][$args['element']];
        } else {
            return $agatt_settings[$args['parent_element']];
        }
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