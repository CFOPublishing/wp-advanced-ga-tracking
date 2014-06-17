var elementCounter = 0;
    jQuery(document).ready(function() {           
        jQuery(".add-repeater").click(function() {
            var parent  =   jQuery(this).parent('.repeater-container');
            var parentID =  parent.attr('id');
            var elementID = parent.attr('for');
            var elementRow = jQuery(parentID + " #" + elementID).clone();
            var newID = elementID + "-" + elementCounter;
            console.log(elementRow);    
            elementRow.attr("id", newID);
            elementRow.show();
            
            
            jQuery('#' + newID + ' input').each(function (index) {
                
                var input_index_num_begins_at = this.attr("name").indexOf("element-num-");
                var input_stringStarts = this.attr("name").substring(0,index_num_begins_at);
                var input_stringEnds = this.attr("class");
                var input_stringEnds_complete = "["+stringEnds+"]";
                var input_string = stringStarts + "element-num-" + elementCounter +"]"+stringEnds_complete;
                this.attr("name", input_string); 
                 
            });
                                                
            jQuery('#' + newID + ' label').each(function (index) {
                
                var input_index_num_begins_at = this.attr("for").indexOf("element-num-");
                var input_stringStarts = this.attr("for").substring(0,index_num_begins_at);
                var input_stringEnds = this.attr("class");
                var input_stringEnds_complete = "["+stringEnds+"]";
                var input_string = stringStarts + "element-num-" + elementCounter +"]"+stringEnds_complete;
                this.attr("for", input_string); 
                 
            });
 
            elementCounter++;
            jQuery("#counter-for-"+elementID).val(elementCounter);
                 
            jQuery("#"+parentID).append(elementRow);
            
                
            return false;
        });         
    });