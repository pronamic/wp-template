<?php

class Pronamic_SITE_ExtraMetaBox {
	
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_site_extra' ) );
		
		add_action( 'wp_ajax_site_set_header', array( $this, 'ajax_set_header' ) );
	}
	
	public function meta_boxes() {
		add_meta_box(
			'site_extra',
			__( 'SITE Extra', 'wp_site' ),
			array( $this, 'view_site_extra' ),
			'post',
			'side'
		);
		
		add_meta_box(
			'site_extra',
			__( 'SITE Extra', 'wp_site' ),
			array( $this, 'view_site_extra' ),
			'page',
			'side'
		);
	}
	
	public function view_site_extra( $post ) {
		$nonce = wp_nonce_field( 'site_extra', 'site_extra_nonce', true, false );
		
		$site_custom_header = get_post_meta( $post->ID, '_site_custom_header', true );
		
		global $SITE;
		include $SITE->plugin_directory() . '/includes/view_site_extra_meta_box.php';
	}
	
	public function save_site_extra( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;
		
		if ( ! filter_has_var( INPUT_POST, 'site_extra_nonce' ) )
			return;
		
		if ( !wp_verify_nonce( $_POST['site_extra_nonce'], 'site_extra' ) )
			return;
		
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;
		
		// CUSTOM HEADER META VALUES
		
		$site_custom_header = filter_input( INPUT_POST, 'site_custom_header', FILTER_SANITIZE_STRING );
		
		if ( ! empty( $site_custom_header ) ) {
			update_post_meta( $post_id, '_site_custom_header', $site_custom_header );
		} else {
			delete_post_meta( $post_id, '_site_custom_header' );
		}
		
	}
	
	public function ajax_set_header() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$post_id = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
			
			if ( ! empty( $_POST['custom_header'] ) ) {
				if ( is_array( $_POST['custom_header'] ) ) {
					$custom_header_ids = implode(',', $_POST['custom_header'] );
				} else {
					$custom_header_ids = $_POST['custom_header'];
				}
				
				update_post_meta( $post_id, '_site_custom_header', $custom_header_ids );
			} else {
				delete_post_meta( $post_id, '_site_custom_header' );
			}
			
			$content = '';
			
			if ( ! empty( $_POST['custom_header'] ) ) {
				
				if ( ! is_array( $_POST['custom_header'] ) ) {
					$custom_headers = preg_split('/,/', $_POST['custom_header'], -1, PREG_SPLIT_NO_EMPTY);
				} else {
					$custom_headers = $_POST['custom_header'];
				}
				
				foreach ( $custom_headers as $custom_header ) {
					$source = wp_get_attachment_image_src( $custom_header, 'small' );
					if ( isset( $source[0] ) ) {
						$content .= "<img src='{$source[0]}'/>";
						$content .= "<a href='#' class='site-image-remove-button' data-image-id='{$custom_header}'>";
						$content .=		__( 'Remove header image', 'wp_site' );
						$content .= "</a>";
					}
					
				}
			}
			
			wp_send_json_success( $content );
		}
	}
}