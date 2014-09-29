jQuery(document).ready(function() {
	var flag_thumbnail = false;
    jQuery('#product_file_upload_button').click(function() {
        formfield = jQuery('#product_file_url').attr('name');
        tb_show('Upload Your File', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

	jQuery('#product_thumbnail_upload_button').click(function() {
        formfield = jQuery('#product_thumbnail_url').attr('name');
		flag_thumbnail = true;
        tb_show('Upload Your File', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    window.send_to_editor = function(html) {
		fileurl = jQuery(html).attr('href');
		if( flag_thumbnail ){
			jQuery('#product_thumbnail_url').val(fileurl);
			flag_thumbnail = false;
		}
        else{
			jQuery('#product_file_url').val(fileurl);
		}
        tb_remove();
    }

});