<?php

class posts_to_do_list_options_functions extends posts_to_do_list_core {
    //Outputs the html code for checkbox and radio fields checking whether the field should be checked or not
    static function print_checkable_inputs( $setting, $field, $name, $value = NULL, $id = NULL ) {
        if( $field == 'radio' ) {
            if( $setting == 1 )
                return '<input type="'.$field.'" name="'.$name.'" value="'.$value.'" id="'.$id.'" checked="checked" />';
            else
                return '<input type="'.$field.'" name="'.$name.'" value="'.$value.'" id="'.$id.'" />';
        } else if( $field == 'checkbox' ) {
            if( $setting == 1 )
                return '<input type="'.$field.'" name="'.$name.'" id="'.$id.'" checked="checked" />';
            else
                return '<input type="'.$field.'" name="'.$name.'" id="'.$id.'" />';
        }
    }
    
    //Used to print settings fields: a checkbox/radio on the left span and the related description on the right span
    static function print_p_field( $text, $setting, $field, $name, $tooltip_description = NULL, $value = NULL, $id = NULL ) { ?>
        <p>
            <span class="tooltip_span">
                <img src="<?php echo plugins_url( 'style/images/info.png', __FILE__ ); ?>" title="<?php echo $tooltip_description; ?>" class="tooltip_container" />
            </span>
            <label>
                <span style="float: left; width: 5%;">    
        <?php echo self::print_checkable_inputs( $setting, $field, $name, $value, $id ); ?>
                </span>
                <span style="width: 90%;"><?php echo $text ?></span>
            </label>
        </p>
    <?php }
    
    //Used when updating plugin options for defining checkboxes values
    static function determine_checkbox_value( $checkbox ) {
        if( ! isset( $checkbox ) )
            return 0;
        else
            return 1;
    }
}
