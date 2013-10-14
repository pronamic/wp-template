<?php

/*
 * Plugin Name: Template for SITE plugin
 * Plugin URI: http://pronamic.nl
 * Description: SITE site plugin
 * Version: 1.0.0
 * Author: Pronamic
 * Author URI: http://pronamic.nl
 * License: GPLv2
 */

/* 
Copyright (C) 2013 Pronamic

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

class SITE {
	
	/**
	 * Holds the WP_KWPN plugin class
	 * @var Pronamic_SITE_Plugin
	 */
	public $plugin;
	
	/**
	 * Automatically registers the autoloader, loads the text domain
	 * and prepares the modules.
	 */
	public function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );
		
		// Plugin textdomain 
		load_plugin_textdomain( 
			'wp_site', 
			false, 
			dirname( plugin_basename( $this->plugin_file() ) ) . '/languages/' 
		);
				
		// Plugin
		$this->plugin = new Pronamic_SITE_Plugin();
		
		// Metaboxes
		$this->extra_meta_box = new Pronamic_SITE_ExtraMetaBox();
		
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		
	}
	
	public function enqueue() {
		wp_register_style( 'wp-site-admin', plugins_url( 'assets/css/wp-site-admin.css', __FILE__ ) );
		wp_register_script( 'media-metabox', plugins_url( 'assets/js/media-metabox.js', __FILE__ ) );
	}
	
	/**
	 * Autoloader. Loads the classes as per the PEAR autoloader and naming
	 * convention. No namespaces.
	 * 
	 * @access public
	 * @param string $class_name
	 */
	public function autoload( $class_name ) {
		$name = str_replace( array( '\\', '_' ), DIRECTORY_SEPARATOR, $class_name );

		$file = $this->plugin_directory() . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $name . '.php';

		if ( is_readable( $file ) )
			require_once $file;
	}
	
	public function plugin_directory() {
		return dirname( __FILE__ );
	}
	
	public function plugin_file() {
		return __FILE__;
	}
}

$GLOBALS['SITE'] = new SITE();