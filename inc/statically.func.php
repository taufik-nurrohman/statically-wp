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