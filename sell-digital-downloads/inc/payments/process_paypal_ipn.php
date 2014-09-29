<?php

$listener = new IpnListener();

$options = isell_get_options();
$platform = $options['paypal']['platform'];
if ($platform == 'sandbox')
    $listener->use_sandbox = true;

//uncomment the line below to disable ssl, paypal ipn might not work
//$listener->use_ssl = false; 
//set it to true to use fsockopen
$listener->use_curl = false;
if ($options['advanced']['use_fsockopen_or_curl'] == 'curl')
    $listener->use_curl = true;

try {
    //verify that ipn is valid
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    //do nothing
    exit(0);
}
if ($verified) 
{
    if ($_POST['payment_status'] == 'Completed' || $_POST['payment_status'] == 'Pending') 
    {
        wp_isell_write_debug('Payment Status: ' . $_POST['payment_status'], true);
        if ($_POST['receiver_email'] == $options['paypal']['email']) 
        {
            wp_isell_write_debug('Merchant email: '.$options['paypal']['email'].', Receiver email: '.$_POST['receiver_email'], true);
            $product_id = $_POST['item_number'];
            $price = isell_format_product_price($product_id);
            if (!$price){
                wp_isell_write_debug('This product does not have a price', false);
                return;
            }
            if ((float) $_POST['mc_gross'] >= (float) $price) 
            {
                wp_isell_write_debug('Product price : '.$price.', Amount paid: '.$_POST['mc_gross'], true);
                if ($_POST['mc_currency'] == $options['store']['currency']) 
                {
                    wp_isell_write_debug('Store currency: '.$options['store']['currency'].', Currency used for the payment: '.$_POST['mc_currency'], true);
                    global $wpdb;
                    $txn_id = $wpdb->escape($_POST['txn_id']);
                    wp_isell_write_debug('Transaction ID: ' . $txn_id, true);
                    $txn_id_query = $wpdb->prepare("SELECT * FROM  $wpdb->postmeta WHERE  meta_value =  %s AND meta_key = %s", $txn_id, 'txn_id');
                    if ($wpdb->query($txn_id_query) >= 1) 
                    {
                        wp_isell_write_debug('This record already exists in the database', true);
                        if ($_POST['payment_status'] == 'Completed') 
                        {
                            $get_txn_id_query = $wpdb->prepare("SELECT post_id FROM  $wpdb->postmeta WHERE  meta_value =  %s AND meta_key = %s", $txn_id, 'txn_id');
                            $order_id = $wpdb->get_var($get_txn_id_query);
                            $product_id = $wpdb->escape($_POST['item_number']);
                            wp_isell_write_debug('Order ID: ' . $order_id . ", Product ID: " . $product_id, true);
                            if ($order_id) 
                            {
                                update_post_meta($order_id, 'ipn_text_report', $listener->getTextReport());
                                $payment_info = get_post_meta($order_id, 'payment_info', true);
                                $payment_info['status'] = 'Completed';
                                update_post_meta($order_id, 'payment_info', $payment_info);
                                $product_name = get_the_title($product_id);
                                $order_title = sprintf("Order: %s | ID: %s | Status: %s", $product_name, $txn_id, $payment_info['status']);
                                $order_post = array(
                                    'ID' => $order_id,
                                    'post_title' => wp_strip_all_tags($order_title)
                                );
                                wp_update_post($order_post);
                                wp_isell_write_debug('Record updated successfully', true);
                                do_action('isell_payment_completed', $_POST, $order_id);
                            } 
                            else {
                                wp_isell_write_debug('Order ID could not be found', false);
                            }
                        } 
                        else {
                            wp_isell_write_debug('This transaction is not completed yet', true);
                        }
                        exit;
                    }
                    wp_isell_write_debug('This is a new order', true);
                    $payer_email = $_POST['payer_email'];
                    $payment_status = esc_html($_POST['payment_status']);
                    $first_name = esc_html($_POST['first_name']);
                    $last_name = esc_html($_POST['last_name']);
                    $country_code = esc_html($_POST['address_country_code']);
                    $zip_code = esc_html($_POST['address_zip']);
                    $state = esc_html($_POST['address_state']);
                    $city = esc_html($_POST['address_city']);
                    $street = esc_html($_POST['address_street']);
                    $amount_paid = esc_html($_POST['mc_gross']);
                    $product_name = get_post_meta($product_id, 'product_name', true);
                    $title = sprintf("Order: %s | ID: %s | Status: %s", $product_name, $txn_id, $payment_status);
                    $buyer_info = array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $payer_email,
                        'phone' => '',
                        'country' => $country_code,
                        'state' => $state,
                        'city' => $city,
                        'zip' => $zip_code
                    );
                    $payment_info = array(
                        'status' => $payment_status,
                        'amount_paid' => $amount_paid,
                        'txn_id' => $txn_id
                    );
                    $product_info = array(
                        'id' => $product_id,
                        'name' => $product_name,
                        'download_url' => '',
                        'downloads' => 0,
                        'link_status' => 'valid'
                    );
                    $order_post = array(
                        'post_title' => wp_strip_all_tags($title),
                        'post_status' => 'publish',
                        'post_author' => 1,
                        'post_type' => 'isell-order'
                    );
                    $order_id = wp_insert_post($order_post);
                    if (!is_wp_error($order_id)) {
                        update_post_meta($order_id, 'txn_id', $txn_id);
                        update_post_meta($order_id, 'ipn_text_report', $listener->getTextReport());
                        update_post_meta($order_id, 'buyer_info', $buyer_info);
                        update_post_meta($order_id, 'payment_info', $payment_info);
                        update_post_meta($order_id, 'product_info', $product_info);
                        wp_isell_write_debug('Order inserted successfully', true);
                    } else {
                        wp_isell_write_debug('Could not insert the order into the database', true);
                        wp_mail(get_option('admin_email'), 'iSell: Error in creating an order', $listener->getTextReport());
                    }
                    if ($_POST['payment_status'] == 'Completed') {
                        do_action('isell_payment_completed', $_POST, $order_id);
                    } else {
                        do_action('isell_payment_pending', $_POST, $order_id);
                    }
                }
                else{
                    wp_isell_write_debug('Store currency did not match with the currency used for the payment', false);
                }
            }
            else{
                wp_isell_write_debug('Product price did not match with the amount paid', false);
            }
        }
        else{
            wp_isell_write_debug('Merchant email address did not match with the receiver email', false);
        }
    } 
    else 
    {
        if ($_POST['payment_status'] == 'Refunded') 
        {
            wp_isell_write_debug('Payment Status: ' . $_POST['payment_status'], true);
            if ($_POST['receiver_email'] == $options['paypal']['email']) 
            {
                wp_isell_write_debug('Merchant email: '.$options['paypal']['email'].', Receiver email: '.$_POST['receiver_email'], true);
                global $wpdb;
                $txn_id = $wpdb->escape($_POST['txn_id']);
                wp_isell_write_debug('Transaction ID: ' . $txn_id, true);
                $txn_id_query = $wpdb->prepare("SELECT post_id FROM  $wpdb->postmeta WHERE  meta_value =  %s AND meta_key = %s", $txn_id, 'txn_id');
                $order_id = $wpdb->get_var($txn_id_query);
                $product_id = $wpdb->escape($_POST['item_number']);
                wp_isell_write_debug('Order ID: ' . $order_id . ", Product ID: " . $product_id, true);
                if ($order_id) 
                {
                    update_post_meta($order_id, 'ipn_text_report', $listener->getTextReport());
                    $payment_info = get_post_meta($order_id, 'payment_info', true);
                    $payment_info['status'] = 'Refunded';
                    update_post_meta($order_id, 'payment_info', $payment_info);
                    $product_name = get_the_title($product_id);
                    //$order_title = sprintf("Order: %s | ID: %s | Status: %s",$product_name,$txn_id,$payment_info['status']);
                    $order_title = isell_generate_order_title($order_id);
                    $order_post = array(
                        'ID' => $order_id,
                        'post_title' => wp_strip_all_tags($order_title)
                    );
                    wp_update_post($order_post);
                    wp_isell_write_debug('Record updated successfully', true);
                    do_action('isell_payment_refunded', $_POST, $order_id);
                }
                else {
                    wp_isell_write_debug('Order ID could not be found', false);
                }
            }
            else{
                wp_isell_write_debug('Merchant email address did not match with the receiver email', false);
            }
        }//end payment status refunded 
    }
}
?>