<?php
$emails = array(
		'order_customer_product_download' => array(
				'subject' => __('Your {product_name} File Download - Order {txn_id}','isell'),
				'message' => __('Dear {customer_name},

Thank you for your order.  You may download using the following URL:

{product_download_url}','isell')
			),
		'admin_new_order' => array(
				'subject' => __('iSell Notification: New Order: {txn_id} - {customer_name} - {product_name}','isell'),
				'message' => __('You have received a new order for {product_name}

To view/edit the order, visit the following address:

{edit_order_link}','isell')
			)  
	);

?>
