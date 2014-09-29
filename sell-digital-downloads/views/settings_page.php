<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div><h2><?php echo __('Settings','isell'); ?></h2>
<div class="update-nag">Please visit the <a target="_blank" href="https://wp-ecommerce.net/wordpress-isell-easily-sell-digital-downloads-from-your-wordpress-site-1916">WP iSell</a> documentation page for usage instructions.</div>
<?php if($debug_reset_notice != ''){
        if($debug_reset_notice == '1'){ ?>
            <div id="message" class="updated fade"><p><strong><?php echo __('Debug log file has been reset!'); ?></strong></p></div>
            <?php
        }
        else{ ?>
            <div id="message" class="updated fade"><p><strong><?php echo __('Debug log file could not be reset!'); ?></strong></p></div>
            <?php
        }
} ?>

<?php if ( $show_settings_updated_notice ): ?>
	<div id="message" class="updated below-h2" style="margin:5px">
		<p><?php echo __('Settings updated.'); ?></p>
	</div>
<?php endif; ?>

<form method="post" action="">
<input type="hidden" name="isell_options_page" value="general">
<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('isell_options_page'); ?>" />
<table class="form-table">
<tbody>
<tr valign="top">
<th scope="row"><label for="paypal_email"><?php echo __('PayPal Email','isell'); ?></label></th>
<td>
	<input name="paypal_email" type="email" id="paypal_email" value="<?php echo esc_html($options['paypal']['email']) ?>" class="regular-text">
	<p class="description">
		<?php //echo __('This email address will also be used as "From:" address in notification emails.','isell'); ?>
	</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="paypal_platform"><?php echo __('PayPal Environment','isell'); ?></label></th>
<td>
	<select name="paypal_platform">
		<option value="sandbox" <?php echo (strtolower($options['paypal']['platform'])=='sandbox') ? 'selected':''; ?>><?php echo __('Sandbox','isell'); ?></option>
		<option value="live" <?php echo (strtolower($options['paypal']['platform'])=='live') ? 'selected':''; ?>><?php echo __('Production','isell'); ?></option>
	</select>
	<p class="description">
	 <?php echo __('It\'s highly recommended you remember to set this option to Production after testing.','isell'); ?>
	</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="currency"><?php echo __('Currency','isell'); ?></label></th>
<td>
	<select name="currency">
	<?php foreach ($currencies as $key => $currency): ?>
		<option value="<?php echo $currency['code']; ?>" <?php echo ($options['store']['currency']==$currency['code']) ? 'selected':''; ?>><?php echo $currency['title']; ?></option>
	<?php endforeach; ?>
	</select>
	
</td>
</tr>
<!--
<tr valign="top">
<th scope="row"><label for="directory_name"><?php echo __('Directory','isell'); ?></label></th>
<td>
	<input name="directory_name"  type="text" id="directory_name" value="<?php echo esc_html($options['file_management']['directory_name']) ?>" class="regular-text disabled" disabled  />
	<p class="description">
		<?php echo __('After you upload a file from product edit screen please make sure if this directory does not exist in the root folder of your site create it and also assign it writeable and readable permissions like "0755" otherwise you won\'t be able to attach files to products nor customers who purchase your product would be able to download the file.','isell'); ?>
	</p>
</td>
</tr>
-->
<tr valign="top">
<th scope="row"><label for="max_downloads"><?php echo __('Max Downloads','isell'); ?></label></th>
<td>
	<input name="max_downloads" type="number" id="max_downloads" value="<?php echo esc_html($options['file_management']['max_downloads']) ?>"  />
	<p class="description">
		<?php echo __('Your customers cannot download the product file more then max downloads.','isell'); ?>
	</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="error_page"><?php echo __('Error Page','isell'); ?></label></th>
<td>
	<select name="error_page"> 
	 <option value="">
	<?php echo esc_attr( __( 'Select page' ) ); ?></option> 
	 <?php 
	  $pages = get_pages(); 
	  foreach ( $pages as $page ) {
	  	$selected = ( $options['store']['error_page'] == $page->ID  ) ? 'selected': '';
	  	$option = '<option ' . $selected .' value="' .  $page->ID  . '">';
		$option .= $page->post_title;
		$option .= '</option>';
		echo $option;
	  }
	 ?>
	</select>
	<p class="description">
		<?php echo __('Make sure you also include this shortcode on the page','isell'); ?>
		<code>[isell_errors]</code>
	</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="thanks_page"><?php echo __('Thanks Page','isell'); ?></label></th>
<td>
	<select name="thanks_page"> 
	 <option value="">
	<?php echo esc_attr( __( 'Select page' ) ); ?></option> 
	 <?php 
	  $pages = get_pages(); 
	  foreach ( $pages as $page ) {
	  	$selected = ( $options['store']['thanks_page'] == $page->ID  ) ? 'selected': '';
	  	$option = '<option ' . $selected .' value="' .  $page->ID  . '">';
		$option .= $page->post_title;
		$option .= '</option>';
		echo $option;
	  }
	 ?>
	</select>
	<p class="description">
		<?php echo __('PayPal will redirect customers to this page after Payment is completed successfully','isell'); ?>
	</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="download_page"><?php echo __('Download Page','isell'); ?></label></th>
<td>
	<select name="download_page"> 
	 <option value="">
	<?php echo esc_attr( __( 'Select page' ) ); ?></option> 
	 <?php 
	  $pages = get_pages(); 
	  foreach ( $pages as $page ) {
	  	$selected = ( $options['store']['download_page'] == $page->ID ) ? 'selected': '';
	  	$option = '<option ' . $selected .' value="' .  $page->ID  . '">';
		$option .= $page->post_title;
		$option .= '</option>';
		echo $option;
	  }
	 ?>
	</select>
	<p class="description">
		<?php echo __('If download page is not set the product download will start with an empty blank page which may confuse users. Make sure you include this shortcode on the page','isell'); ?>
		<code>[isell_download_page]</code>
	</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="use_fsockopen_or_curl"><?php echo __('Use fsockopen or CURL','isell'); ?></label></th>
<td>
	<select name="use_fsockopen_or_curl" id="use_fsockopen_or_curl">
		<option value="fsockopen"  <?php echo ($options['advanced']['use_fsockopen_or_curl']=='fsockopen') ? 'selected':''; ?>>fsockopen</option>
		<option value="curl" <?php echo ($options['advanced']['use_fsockopen_or_curl']=='curl') ? 'selected':''; ?>>CURL</option>
	</select>
	<p class="description">
		<?php echo __('If your host don\'t support both ask them to enable one for your site.','isell'); ?>
	</p>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="wp_isell_enable_debug"><?php echo __('Enable Debug','isell'); ?></label></th>
<td>
<input name="wp_isell_enable_debug" type="checkbox"<?php if(isset( $options['advanced']['wp_isell_enable_debug'] ) && $options['advanced']['wp_isell_enable_debug']!='') echo ' checked="checked"'; ?> value="1"/>
<p class="description">If checked, debug output will be written to log files. This is useful for troubleshooting post payment failures (for example, if you are not receiving the email after payment).</p>
<p class="description">You can check the debug log file by clicking on the link below (The log file can be viewed using any text editor):</p>
<ul>
    <li><a href="<?php echo ISELL_PLUGIN_URL.'/wp_isell_debug.log'; ?>" target="_blank">wp_isell_debug.log</a></li>
</ul>
<div class="submit"><input type="submit" name="wp_isell_reset_logfile" style="font-weight:bold; color:red" value="Reset Debug Log file"/> WP iSell log file is "reset" and timestamped with a log file reset message.</div>
</td></tr>

</tbody>
</table>

<?php do_action('isell_before_submit_settings_page'); ?>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __('Save Changes','isell'); ?>"></p></form>

</div>