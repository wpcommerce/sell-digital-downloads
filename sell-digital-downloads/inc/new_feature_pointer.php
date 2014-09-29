<?php
add_action( 'admin_enqueue_scripts', 'isell_new_feature_pointer_header' );
function isell_new_feature_pointer_header() {

   //if ( ISELL_VERSION != '1.7' ) return;

    $enqueue = false;

    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

    if ( ! in_array( 'isell_new_feature_pointer_v_1.7', $dismissed ) ) {
        $enqueue = true;
        add_action( 'admin_print_footer_scripts', 'isell_new_feature_pointer_footer_scripts' );
    }
    
   
      

    if ( $enqueue ) {
        // Enqueue pointers
        wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_style( 'wp-pointer' );
    }
}
function isell_new_feature_pointer_footer_scripts() {
    $pointer_content = '<h3>iSell update!</h3>';
    $pointer_content .= '<p>Download Page support is added to the iSell plugin. Please visit the settings page to select the download page.</p>';
?>
<script type="text/javascript">
// <![CDATA[
   jQuery(document).ready( function($) {
    $('#toplevel_page_sell-digital-downloads-inc-functions').pointer({
        content: '<?php echo $pointer_content; ?>',
        position: 'top',
        close: function() {
            $.post( ajaxurl, {
                pointer: 'isell_new_feature_pointer_v_1.7',
                action: 'dismiss-wp-pointer'
            });
        }
      }).pointer('open');
   });
// ]]>
</script>
<?php
}

?>