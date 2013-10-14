<?php wp_enqueue_style( 'wp-site-admin' ); ?>
<?php wp_enqueue_script( 'media-metabox' ); ?>
<?php echo $nonce; ?>

<p class="site-image-parent">
	<label>
		<?php _e( 'Header Image', 'wp_site' ); ?>
	</label>
	<span class="howto">
		<?php _e( 'Display a large image in the header. See the help page', 'wp_site' ); ?>
	</span>
	<div class="site-image-upload-preview">
		
		<?php if ( ! empty( $site_custom_header ) ) : ?>
			<?php $header_ids = preg_split('/,/', $site_custom_header, -1, PREG_SPLIT_NO_EMPTY); ?>
			<?php foreach ( $header_ids as $header_id ) : ?>
				<?php $source = wp_get_attachment_image_src( $header_id, 'small' ); ?>
				<?php if ( isset( $source[0] ) ) : ?>
					<img src="<?php	 echo esc_attr( $source[0] ); ?>" />
				<?php endif; ?>
				<a href="#" class="site-image-remove-button" data-image-id="<?php echo $header_id; ?>">
					<?php _e( 'Remove header image', 'wp_site' ); ?>
				</a>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class="site-image-upload-holder">
		<input class="site-image-upload-id" type="hidden" name="site_custom_header" value="<?php echo esc_attr( $site_custom_header ); ?>"/>
		<a class="site-image-upload-button" href="#" data-update-button-text="<?php esc_attr_e( 'Updated!', 'wp_site' ); ?>" data-title="<?php esc_attr_e( 'Choose Header Image', 'wp_site' ); ?>" data-update-text="<?php esc_attr_e( 'Updated Header Image', 'wp_site' ); ?>">
			<?php _e( 'Choose Header', 'wp_site' ); ?>
		</a>
	</div>
</p>