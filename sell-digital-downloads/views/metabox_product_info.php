<?php
if (!current_user_can('edit_post')) wp_die( __('You do not have sufficient permissions to access this page.') );

?>

<table class="form-table">
	<input type="hidden" name="product_token" />
	<tr valign="top">
       <th scope="row"><strong><label for="product_name"><?php echo __('Product Name:','isell'); ?></label><strong></th>
       <td>
       		<input type="text" value="<?php echo get_post_meta($post_id,'product_name',true); ?>" id="product_name" name="product_name" required class="regular-text" />
       		<p class="description"></p>
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="product_price"><?php echo __('Product Price:','isell'); ?></label><strong></th>
       <td>
       		<input type="text" value="<?php echo get_post_meta($post_id,'product_price',true); ?>" id="product_price" name="product_price"  required  /><span class="currency" style="font-weight:bold"><?php echo $currency; ?></span>
       		<p class="description"><?php echo __('Enter only a numeric value','isell'); ?></p>
        </td>
    </tr>
		<tr valign="top">
        <th scope="row"><strong><label for="product_thumbnail_url"><?php echo __('Thumbnail:','isell'); ?></label><strong></th>
        <td>
            <input type="text" value="<?php echo get_post_meta($post_id,'product_thumbnail_url',true); ?>" id="product_thumbnail_url" name="product_thumbnail_url" size="90" />
            <input id="product_thumbnail_upload_button" type="button" value="<?php echo __('Upload File','isell'); ?>" />
            <p class="description"><?php echo __('Enter the URL of your file or upload one','isell'); ?></p>
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="product_url" id="product_url_label"><?php echo __('Buy Now Url:','isell'); ?></label><strong></th>
       <td>
          <input type="text" disabled value="<?php echo isell_generate_product_url($post_id); ?>" id="product_url" name="product_url" min="0" required  class="regular-text disabled"/>
          <p class="description"><?php echo __('Use this link in your call to actions','isell'); ?></p>
        </td>
    </tr>

</table>

<style>
#normal-sortables,#post-preview{
  display:none;
}
</style>