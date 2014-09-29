<?php
if (!current_user_can('edit_post')) wp_die( __('You do not have sufficient permissions to access this page.') );
?>
<table class="form-table">
	<tr valign="top">
       <th scope="row"><strong><label for="product_id"><?php echo __('ID:','isell'); ?></label><strong></th>
       <td>
       		<input type="number" value="<?php echo $product_info['id']; ?>" id="product_id" name="product_id" min="0" required />
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="product_name"><?php echo __('Name:','isell'); ?></label><strong></th>
       <td>
       		<input type="text" value="<?php echo $product_info['name']; ?>" id="product_name" name="product_name"  class="disabled" disabled/>
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="product_downloads"><?php echo __('Downloads:','isell'); ?></label><strong></th>
       <td>
       		<input type="number" value="<?php echo $product_info['downloads']; ?>" id="product_downloads" name="product_downloads"  class="disabled" disabled/>
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="download_url" id="download_url_label"><?php echo __('Download URL:','isell'); ?></label><strong></th>
       <td>
       		<input type="url" value="<?php echo $product_info['download_url']; ?>" id="download_url" name="download_url"  class="disabled" disabled />
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="product_link_status"><?php echo __('Link Status:','isell'); ?></label><strong></th>
       <td>
       		<select name="product_link_status" id="product_link_status">
       			<option value="expired" <?php echo (strtolower($product_info['link_status'])=='expired') ? 'selected':''; ?> >Expired</option>
       			<option value="valid" <?php echo (strtolower($product_info['link_status'])=='valid') ? 'selected':''; ?> >Valid</option>
       		</select>
        </td>
    </tr>
</table>