<?php 

/*
* WP SNIPET TERMS AND CONDITIONS
* Héctor Guedea
* https://hectorguedea.com
*/


/*
Save the following code in your theme's functions.php file. 
Replace 'textdomain' with the appropriate domain for your theme or plugin. 
The saved code will include data from the terms in new orders.
*/


//Default checked on terms and conditions checkbox 
add_filter( 'woocommerce_terms_is_checked_default', '__return_true' );

add_filter('manage_edit-shop_order_columns', 'add_terms_accepted_column');

function add_terms_accepted_column($columns) {
    $columns['terms_accepted'] = __('Términos Aceptados', 'textdomain'); 
    return $columns;
}

// Show terms acceptance status in column
add_action('manage_shop_order_posts_custom_column', 'display_terms_accepted_column', 10, 2);

function display_terms_accepted_column($column, $post_id) {
    if ($column === 'terms_accepted') {
        $terms_accepted = get_post_meta($post_id, '_terms_accepted', true);

        if ($terms_accepted) {
            echo __('Sí', 'textdomain');
        } else {
            echo __('No', 'textdomain'); 
        }
    }
}

// Save terms in _terms_accepted field on order meta on database
add_action('woocommerce_checkout_update_order_meta', 'save_terms_accepted');
function save_terms_accepted($order_id) {
    if ( isset($_POST['terms-field']) ) {
        update_post_meta( $order_id, '_terms_accepted', esc_attr( $_POST['terms-field'] ) );
    }
}

// Display terms and conditions in order detail in admin Woocommerce
add_action('woocommerce_admin_order_data_after_billing_address', 'display_woocommerce_terms_admin');

function display_woocommerce_terms_admin($order) {
    $terms_accepted = get_post_meta($order->get_id(), '_terms_accepted', true);

    if ($terms_accepted) {
        echo '<p><strong>' . __('Términos y condiciones aceptados:', 'textdomain') . '</strong> ' . __('Sí', 'textdomain') . '</p>';
    } else {
        echo '<p><strong>' . __('Términos y condiciones aceptados:', 'textdomain') . '</strong> ' . __('No', 'textdomain') . '</p>';
    }
}

?>