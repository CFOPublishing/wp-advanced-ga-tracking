jQuery(document).ready(function( $ ) {
	
    $(function() {
        $.scrollDepth({
            minHeight: agatt_sd_minHeight,
            elements: agatt_scrolledElements,
            percentage: agatt_sd_percentage,
            userTiming: agatt_sd_userTiming,
            pixelDepth: agatt_sd_pixel_Depth
        });
    });
	
});