<?php
if (!current_user_can('edit_post')) wp_die( __('You do not have sufficient permissions to access this page.') );
?>

<?php do_action( 'isell_product_metabox_other_information_before' ) ?>

<table class="form-table">
	<tr valign="top">
       <th scope="row">
       		<strong>Storage</strong>
       </th>
       <td>
       		<?php echo $storage; ?>
       </td>
    </tr>
	<tr valign="top">
       <th scope="row">
       		<strong>Size</strong>
       </th>
       <td>
       		<?php echo $storage_size; ?>
       </td>
    </tr>
    
    <?php do_action( 'isell_product_metabox_other_information' ) ?>
    
</table>

<?php do_action( 'isell_product_metabox_other_information_after' ) ?>