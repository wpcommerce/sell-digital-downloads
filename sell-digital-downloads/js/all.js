jQuery(document).ready(function($){
	
	

if ( pagenow != undefined && pagenow == 'isell-product'){
jQuery('#deletefile').click(function(){
        if ( jQuery('#deletefile').hasClass('disabled') )return;
        if ( !confirm('Delete this file ?') ) return;

        jQuery('#deletefile').text(isell.deleting_file)

        jQuery.ajax({
                type: 'POST',
                url: isell.ajaxurl,
                data: { action: 'isell_delete_file',post_id: jQuery('#isell_product_id').val(), nonce: isell.file_delete_nonce  },
                dataType: "json",
                success: function(response){
                        if ( response.status === 1 ){
                                jQuery('#post').delay(500).submit();
                        }else{
                                alert(response.message);
                        }
                }

        });
});
var isell_uploader = new plupload.Uploader({
        runtimes: isell.plupload.runtimes,
        flash_swf_url: isell.plupload.flash_swf_url,
        silverlight_xap_url: isell.plupload.silverlight_xap_url,
        browse_button: isell.plupload.browse_button,
        container: isell.plupload.container,
        chunk_size : isell.plupload.chunk_size,
        unique_names : isell.plupload.unique_names,
        multi_selection: isell.plupload.multi_selection,
        multipart: isell.plupload.multipart,
        url: isell.plupload.url,
        multipart_params: {
                nonce: isell.file_upload_nonce,
                action: isell.plupload.multipart_params_action,
                post_id: jQuery('#isell_product_id').val(),
                file_name: jQuery('#isell_product_file_name').val()
         }
        
    });


isell_uploader.init();



jQuery('#isell_product_file_name').change(function(){
    isell_uploader.settings.multipart_params.file_name = jQuery('#isell_product_file_name').val();
});

    
document.getElementById('uploadfiles').onclick = function() {
    if ( jQuery('#uploadfiles').hasClass('disabled') )return;
    isell_uploader.start();
};

isell_uploader.bind('FilesAdded', function(up, files) {
        //alert('Click on start upload button to start the upload of this file');
        if ( !jQuery('#deletefile').hasClass('disabled') )return;
        jQuery('#uploadfiles').removeClass('disabled');
        up.refresh();
});

isell_uploader.bind('FileUploaded', function(up, file, response) {
        //alert('The file is uploaded successfully');
        var result = jQuery.parseJSON(response["response"]);
        if ( result.status === 1 )
                jQuery('#post').delay(1000).submit();
        else
                alert(result.message);
});
isell_uploader.bind('UploadProgress', function(up, file) {
        jQuery( "#file_upload_progressbar" ).progressbar({
                        value: file.percent
        });

});

jQuery('#product_url_label').click(function(e){
        jQuery('#product_url').select();
});




}

if ( pagenow != undefined && pagenow == 'isell-order'){

jQuery('#download_url_label').click(function(e){
        jQuery('#download_url').select();
});

}

});

