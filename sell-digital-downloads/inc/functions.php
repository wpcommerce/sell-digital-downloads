<?php

function isell_absolute_from_url($src_file_url) {
    // Converts $src_file_url into an absolute file path, starting at the server's root directory.
    if (preg_match("/^http/i", $src_file_url) != 1) return FALSE;    // Not a qualified URL.
    $domain_url = $_SERVER['SERVER_NAME'];                // Get domain name.
    $absolute_path_root = $_SERVER['DOCUMENT_ROOT'];        // Get absolute document root path.
    // Calculate position in $src_file_url just after the domain name...
    $domain_name_pos = stripos($src_file_url, $domain_url);
    if($domain_name_pos === FALSE) return FALSE;            // Rats!  URL is not on this server.
    $domain_name_length = strlen($domain_url);
    $total_length = $domain_name_pos+$domain_name_length;
    // Replace http*://SERVER_NAME in $src_file_url with the absolute document root path.
    return substr_replace($src_file_url, $absolute_path_root, 0, $total_length);
}

function isell_change_product_post_messages($messages){
	 	global $post;
		$messages['isell-product'] = array(
					0 => '', // Unused. Messages start at index 1.
					1 =>  __('Product updated.'),
					2 => __('Custom field updated.'),
					3 => __('Custom field deleted.'),
					4 => __('Product updated.'),
					/* translators: %s: date and time of the revision */
					5 => isset($_GET['revision']) ? sprintf( __('Product restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
					6 => __('Product created.'),
					7 => __('Product saved.'),
					8 => '',
					9 => sprintf( __('Product scheduled for: <strong>%1$s</strong>.'),
					  // translators: Publish box date format, see http://php.net/date
					  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
					10 => __('Product draft updated.')
				);

		return $messages;
}
function isell_change_order_post_messages($messages){
		global $post;
		$messages['isell-order'] = array(
					0 => '', // Unused. Messages start at index 1.
					1 =>  __('Order updated.'),
					2 => __('Custom field updated.'),
					3 => __('Custom field deleted.'),
					4 => __('Order updated.'),
					/* translators: %s: date and time of the revision */
					5 => isset($_GET['revision']) ? sprintf( __('Order restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
					6 => __('Order created.'),
					7 => __('Order saved.'),
					8 => '',
					9 => sprintf( __('Order scheduled for: <strong>%1$s</strong>.'),
					  // translators: Publish box date format, see http://php.net/date
					  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
					10 => __('Order draft updated.')
				);

		return $messages;
}
function isell_remove_post_row_actions( $actions )
{
    if( get_post_type() === 'isell-order' ){
        unset( $actions['view'] );
        unset( $actions['inline hide-if-no-js'] );
        unset( $actions['pgcache_purge'] );
    }
    if( get_post_type() === 'isell-product' ){
        unset( $actions['view'] );
        unset( $actions['inline hide-if-no-js'] );
        unset( $actions['pgcache_purge'] );
    }
    return $actions;
}

function isell_generate_product_url($product_id){
		$product_url = sprintf('%s/?iproduct=%s',site_url(),$product_id);
		return apply_filters ( 'isell_product_url' , $product_url, $product_id );
}

function isell_generate_product_download_url($order_id,$product_id,$txn_id){
    $product_download_url = sprintf("%s?action=%s&product=%s&order=%s&trans=%s",admin_url( 'admin-ajax.php'),'isell_download_file',$product_id,$order_id,$txn_id);
	return apply_filters ( 'isell_product_download_url' ,$product_download_url, $order_id, $product_id, $txn_id );
}

function isell_shortcode_list_product_files(){
		
}

function isell_get_options(){
	return get_option('isell_options');
}


function isell_generate_order_title( $order_id = NULL, $args = NULL, $title_format = NULL ) {
	
	if ( $title_format == NULL )
		$title_format = 'Product Name: %s | ID: %s | Payment Status: %s | Amount Paid: %s | Buyer Name: %s | Buyer Email: %s';
		
	if ( $order_id == NULL ) {
		
		$defaults = array( 
				'product_name' => '',
				'txn_id' => '',
				'payment_status' => '',
				'amount_paid' => '',
				'buyer_name' => '',
				'buyer_email' => ''
			);
		
		$args = wp_parse_args( $args, $defaults );
		
		extract( $args, EXTR_SKIP );
		
		$title = sprintf( 
			$title_format,
			$product_name, $txn_id, $payment_status, $amount_paid, $buyer_name, $buyer_email
		 );
		
		return $title;
	}
	
	if ( $args == NULL  ) {
		
		$buyer_info = get_post_meta( $order_id, 'buyer_info', true );
		$payment_info = get_post_meta( $order_id, 'payment_info', true );
		$product_info = get_post_meta( $order_id, 'product_info', true );
		$product_id = $product_info['id'];
		
		$product_name = get_the_title( $product_id );
		
		$txn_id = $payment_info['txn_id'];
		$payment_status = $payment_info['status'];
		$amount_paid = $payment_info['amount_paid'];
		
		$buyer_name = $buyer_info['first_name'] . ' ' . $buyer_info['last_name'];
		$buyer_email = $buyer_info['email'];	
		
		
		
		$title = sprintf( 
			$title_format,
			$product_name, $txn_id, $payment_status, $amount_paid, $buyer_name, $buyer_email
		 );
		 
		 return $title;
	}
	
	
}

function isell_settings_page(){
	$menu_slug = 'isell_settings_page';
	$icon_url = plugins_url() . '/' . iSell_Dir_Name  .  '/images/menu-icon.png'; 

	add_object_page(__('iSell Settings','isell'), 'iSell', 'manage_options', $menu_slug, 'isell_settings_page_view', $icon_url);
	
	do_action('isell_admin_menu', $menu_slug);

	//add_submenu_page($menu_slug,__('Support Forums','isell'), 'Support Forums', 'manage_options', 'isell_redirect_to_support', 'isell_support_forum_redirect', 1);
	
	do_action('isell_admin_menu_after', $menu_slug);

}

function isell_support_forum_redirect() {

	$link = 'https://wp-ecommerce.net/wordpress-isell-easily-sell-digital-downloads-from-your-wordpress-site-1916';
	wp_redirect( $link );

}

if ( !function_exists('isell_save_settings') ){
	function isell_save_settings($options){
		if ( !current_user_can('manage_options') ) return false;

		$options['paypal'] = array(
				'email' => $_POST['paypal_email'],
				'platform' => $_POST['paypal_platform']
			);
		$options['store'] = array(
				'currency' => $_POST['currency'],
				'error_page' => $_POST['error_page'],
				'thanks_page' => $_POST['thanks_page'],
				'download_page' => $_POST['download_page']
	 		);
		$options['file_management']['max_downloads'] = (int)$_POST['max_downloads'];
		$options['advanced']['use_fsockopen_or_curl'] = $_POST['use_fsockopen_or_curl'];
                $options['advanced']['wp_isell_enable_debug'] = $_POST["wp_isell_enable_debug"]=='1'?1:'';
		update_option('isell_options',$options);
		return $options;
	}
}
if ( !function_exists('isell_settings_page_view') ){
	function isell_settings_page_view() {
		$options = get_option('isell_options');
		$currencies = isell_currencies();
		$show_settings_updated_notice = false;
                $debug_reset_notice = '';
		if ( isset($_POST['submit']) && isset($_POST['isell_options_page']) ){
			if ( !wp_verify_nonce($_POST['nonce'],'isell_options_page') ) return;
			$options = isell_save_settings($options);
			$show_settings_updated_notice = true;
		}
                if(isset($_POST['wp_isell_reset_logfile'])){
                    // Reset the debug log file
                    if(wp_isell_reset_logfile()){
                        $debug_reset_notice = '1';
                    }
                    else{
                        $debug_reset_notice = '0';
                    }
                    $show_settings_updated_notice = false;
                }
		
		include_once(iSell_Path.'/views/settings_page.php');
	}
}

if ( !function_exists('isell_error_redirect') ){
	function isell_error_redirect($error_code,$error_page=NULL){
		
		if ( $error_page == NULL ) {
			$options = isell_get_options();
			
			if ( is_numeric( $error_page ) )
				$error_page = get_permalink( $options['store']['error_page'] );
			else
				$error_page = $options['store']['error_page'];
		}
		
		$default_string = "%s?isell_error=%d";

		if ( !get_option('permalink_structure') )
			$default_string = "%s&isell_error=%d";
		
		wp_redirect(sprintf($default_string,$error_page,$error_code));
		exit;
	}
}

function isell_download_page_link( $txn_id, $order_id, $download_page = NULL ) {
	
	if ( $download_page == NULL ) {
		$options = isell_get_options();
		
		if( is_numeric( $download_page ) )
			$download_page = get_permalink( $options['store']['download_page'] );
		else
			$download_page = $options['store']['download_page']; 
	}

	$default_string = "%s?trans=%s&order=%s";

	if ( ! get_option( 'permalink_structure' ) )
			$default_string = "%s&trans=%s&order=%s";

	return sprintf( $default_string, $download_page, $txn_id, $order_id );
	//wp_redirect( sprintf( $default_string, $download_page, $txn_id, $order_id ) );
	//exit;

}

function isell_currencies(){
	return array('USD' => array('title' => 'U.S. Dollar', 'code' => 'USD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'EUR' => array('title' => 'Euro', 'code' => 'EUR', 'symbol_left' => '', 'symbol_right' => '€', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'JPY' => array('title' => 'Japanese Yen', 'code' => 'JPY', 'symbol_left' => '¥', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'GBP' => array('title' => 'Pounds Sterling', 'code' => 'GBP', 'symbol_left' => '£', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'CHF' => array('title' => 'Swiss Franc', 'code' => 'CHF', 'symbol_left' => '', 'symbol_right' => 'CHF', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'AUD' => array('title' => 'Australian Dollar', 'code' => 'AUD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'CAD' => array('title' => 'Canadian Dollar', 'code' => 'CAD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'SEK' => array('title' => 'Swedish Krona', 'code' => 'SEK', 'symbol_left' => '', 'symbol_right' => 'kr', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'HKD' => array('title' => 'Hong Kong Dollar', 'code' => 'HKD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'NOK' => array('title' => 'Norwegian Krone', 'code' => 'NOK', 'symbol_left' => 'kr', 'symbol_right' => '', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'NZD' => array('title' => 'New Zealand Dollar', 'code' => 'NZD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'MXN' => array('title' => 'Mexican Peso', 'code' => 'MXN', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'SGD' => array('title' => 'Singapore Dollar', 'code' => 'SGD', 'symbol_left' => '$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'BRL' => array('title' => 'Brazilian Real', 'code' => 'BRL', 'symbol_left' => 'R$', 'symbol_right' => '', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'CNY' => array('title' => 'Chinese RMB', 'code' => 'CNY', 'symbol_left' => '￥', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           //'CZK' => array('title' => 'Czech Koruna', 'code' => 'CZK', 'symbol_left' => '', 'symbol_right' => '', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'DKK' => array('title' => 'Danish Krone', 'code' => 'DKK', 'symbol_left' => '', 'symbol_right' => 'kr', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'HUF' => array('title' => 'Hungarian Forint', 'code' => 'HUF', 'symbol_left' => '', 'symbol_right' => 'Ft', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'ILS' => array('title' => 'Israeli New Shekel', 'code' => 'ILS', 'symbol_left' => '₪', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'INR' => array('title' => 'Indian Rupee', 'code' => 'INR', 'symbol_left' => 'Rs.', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'MYR' => array('title' => 'Malaysian Ringgit', 'code' => 'MYR', 'symbol_left' => 'RM', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'PHP' => array('title' => 'Philippine Peso', 'code' => 'PHP', 'symbol_left' => 'Php', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'PLN' => array('title' => 'Polish Zloty', 'code' => 'PLN', 'symbol_left' => '', 'symbol_right' => 'zł', 'decimal_point' => ',', 'thousands_point' => '.', 'decimal_places' => '2'),
                           'THB' => array('title' => 'Thai Baht', 'code' => 'THB', 'symbol_left' => '', 'symbol_right' => '฿', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'),
                           'TWD' => array('title' => 'Taiwan New Dollar', 'code' => 'TWD', 'symbol_left' => 'NT$', 'symbol_right' => '', 'decimal_point' => '.', 'thousands_point' => ',', 'decimal_places' => '2'));
}

if ( ! function_exists( 'isell_calc_product_storage_size' ) ) {
	function isell_calc_product_storage_size( $product_directory_path ) {

		$storage_size = 0;
		if ( class_exists('RecursiveIteratorIterator') && class_exists('RecursiveDirectoryIterator') && file_exists( $product_directory_path ) ) {
		    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($product_directory_path)) as $file){ 
		        $storage_size += $file->getSize(); 
		   	}
	   	}

	   	return $storage_size;

	}
}

add_action('admin_notices', 'isell_admin_notice');
add_action('admin_init', 'isell_ignore_notice');

function isell_admin_notice() {
   
    if ( ! current_user_can('manage_options') ) return;
    if ( ! get_option( 'isell_ignore_installed_notice' ) ) {
        echo '<div class="updated"><p>';
        printf(__('Thank you for using the iSell Plugin, Please go to the <a href="%1$s">iSell settings</a> page to setup the plugin.'), admin_url().'?page=isell_settings_page&isell_ignore_notice=0');
        echo "</p></div>";
    }
}
function isell_ignore_notice() {
	
	if ( isset($_GET['isell_ignore_notice']) && '0' == $_GET['isell_ignore_notice'] ) {
		add_option( 'isell_ignore_installed_notice', true );
	}
    
}


//language translation
function isell_load_plugin_textdomain() {
  load_plugin_textdomain( 'isell', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action('plugins_loaded', 'isell_load_plugin_textdomain');

add_action( 'admin_head', 'isell_custom_edit_icons_on_edit_screen' );

function isell_custom_edit_icons_on_edit_screen() {
	
	include iSell_Path . '/views/custom_edit_icons_style.php';
	
}

function isell_get_product_price( $product_id  ) {
	
	$price = isell_get_product_meta( $product_id, 'product_price' );
	
	$price = apply_filters( 'isell_product_price', $price, $product_id );
	
	return $price;
}

function isell_format_product_price( $product_id ) {
		
		$price = isell_get_product_price( $product_id );
		
		$price = apply_filters( 'isell_format_product_price', $price, $product_id );

		return $price;
}

function isell_get_product_meta( $product_id, $meta_key ) {
	return get_post_meta( $product_id, $meta_key, true  );
}

function isell_update_product_meta( $product_id, $meta_key, $meta_value ) {
	return update_post_meta( $product_id, $meta_key, $meta_value );
}

function isell_add_product_meta( $product_id, $meta_key, $meta_value, $unique  ) {
	return add_post_meta( $product_id, $meta_key, $meta_value, $unique );
}


function isell_get_order_meta( $order_id, $meta_key ) {
	return get_post_meta( $order_id, $meta_key, true  );
}

function isell_update_order_meta( $order_id, $meta_key, $meta_value ) {
	return update_post_meta( $order_id, $meta_key, $meta_value );
}

function isell_add_order_meta( $order_id, $meta_key, $meta_value, $unique  ) {
	return add_post_meta( $order_id, $meta_key, $meta_value, $unique );
}
?>