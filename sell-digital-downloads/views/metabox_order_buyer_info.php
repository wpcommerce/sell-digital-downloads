<?php
if (!current_user_can('edit_post')) wp_die( __('You do not have sufficient permissions to access this page.') );
?>
<table class="form-table">
	<tr valign="top">
       <th scope="row"><strong><label for="first_name"><?php echo __('First Name:','isell'); ?></label><strong></th>
       <td>
       		<input type="text" value="<?php echo $buyer_info['first_name']; ?>" id="first_name" name="first_name"  />
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="last_name"><?php echo __('Last Name:','isell'); ?></label><strong></th>
       <td>
       		<input type="text" value="<?php echo $buyer_info['last_name']; ?>" id="last_name" name="last_name"   />
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="email"><?php echo __('Email:','isell'); ?></label><strong></th>
       <td>
       		<input type="text" value="<?php echo $buyer_info['email']; ?>" id="email" name="email"   />
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="phone"><?php echo __('Phone:','isell'); ?></label><strong></th>
       <td>
          <input type="text" value="<?php echo $buyer_info['phone']; ?>" id="phone" name="phone"  />
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="country"><?php echo __('Country:','isell'); ?></label><strong></th>
       <td>
          <input type="text" value="<?php echo $buyer_info['country']; ?>" id="country" name="country"  />
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="state"><?php echo __('State:','isell'); ?></label><strong></th>
       <td>
          <input type="text" value="<?php echo $buyer_info['state']; ?>" id="state" name="state"  />
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="city"><?php echo __('City:','isell'); ?></label><strong></th>
       <td>
          <input type="text" value="<?php echo $buyer_info['city']; ?>" id="city" name="city"  />
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="zip"><?php echo __('Zip code:','isell'); ?></label><strong></th>
       <td>
          <input type="text" value="<?php echo $buyer_info['zip']; ?>" id="zip" name="zip"  />
        </td>
    </tr>
    
</table>
<style>
#normal-sortables,#post-preview{
  display:none;
}
</style>