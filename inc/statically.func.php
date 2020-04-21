<?php

/**
 * Statically Functions
 * 
 * @since 0.5.0
 */

if ( ! function_exists( 'wp_startswith' ) ) :
	function wp_startswith( $haystack, $needle ) {
		return 0 === strpos( $haystack, $needle );
	}
endif;

if ( ! function_exists( 'wp_list_the_plugins' ) ) :
	function wp_list_the_plugins() {
		$plugins = get_option( 'active_plugins', array () );
		foreach ( $plugins as $plugin ) {
				echo "$plugin,";
		}
	}
endif;

function statically_is_processable_image( $url ) {
	return preg_match( '/^.*\.(bmp|gif|jpe?g|png|webp)$/i', $url );
}

function statically_is_svg( $url ) {
	return preg_match( '/^.*\.svg$/i', $url );
}