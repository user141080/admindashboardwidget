<?php

/**
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Admin Dashboard  Widget
 * Description:       Example of how create an admin dashboard widget with an save button
 * Version:           1.0.0
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ch_user_widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Add javascript
 */
function ch_add_script($hook){
    
    // add JS-File only on the dashboard page
    if ('index.php' !== $hook) {
        return;
    }
    
    wp_enqueue_script( 'ch_widget_script', plugin_dir_url(__FILE__) ."/js/widget-script.js", array(), NULL, true );
}

/**
 * hook to add js
 */
add_action( 'admin_enqueue_scripts', 'ch_add_script' );


/**
 * Registration of the Admin dashboard widget
 */
function ch_add_dashboard_widgets() {

    wp_add_dashboard_widget(
        'user_email_admin_dashboard_widget',      // Widget slug.
        __('Extra profile information', 'ch_user_widget'),         // Title.
        'ch_user_email_admin_dashboard_widget' // Display function.
    );	
}

/**
 * hook to register the Admin dashboard widget
 */
add_action( 'wp_dashboard_setup', 'ch_add_dashboard_widgets' );


/**
 * Output the html content of the dashboard widget
 */
function ch_user_email_admin_dashboard_widget() {
    
    // detect the current user to get his phone number
    $user = wp_get_current_user();
    ?>
    <form id="ch_form" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" >
        
        <!-- controlls on which function the post will send -->
        <input type="hidden" name="cp_action" id="cp_action" value="ch_user_data">
        
        <?php wp_nonce_field( 'ch_nonce', 'ch_nonce_field' ); ?>
        
        <p>Please add your phone number</p>

        <p>
            <label for="phone">Phone Number</label>
            <input type="text" name="phone" id="cp_phone_number" value="<?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?>" class="regular-text" />
        </p>
        <p>
          
          <input name="save-data" id="save-data" class="button button-primary" value="Save" type="submit">  
          <br class="clear">
        </p>
    
    </form>
   
    <?php
}


/**
 * Saves the data from the admin widget
 */
function ch_save_user_data() {
    
    $msg = '';
    if(array_key_exists('nonce', $_POST) AND  wp_verify_nonce( $_POST['nonce'], 'ch_nonce' ) ) 
    {   
        // detect the current user to get his phone number
        $user = wp_get_current_user();
        
        // change the phone number
        update_usermeta( $user->id, 'phone', $_POST['phone_number'] );
        
        // success message
        $msg = 'Phone number was saved';
    }
    else
    {   
        // error message
        $msg = 'Phone number was not saved';
    }
   
    wp_send_json( $msg );
}

/**
 * ajax hook for registered users
 */
add_action( 'wp_ajax_ch_user_data', 'ch_save_user_data' );