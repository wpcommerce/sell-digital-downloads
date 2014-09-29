<?php do_action( 'isell_download_page_before' ); ?>

<?php if ( $show ): ?>

<p><?php echo $other_text; ?> <a href="<?php echo $download_link ?>" /><?php echo $link_text ?></a></p>

<?php endif; ?>

<?php if ( $auto_start ): ?>

<iframe width="1" height="1" frameborder="0" src="<?php echo $download_link ?>"></iframe>

<?php endif; ?>

<?php do_action( 'isell_download_page_after' ); ?>