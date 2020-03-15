<?php

class Statically_Images {

    public function __construct() {
        add_action( 'wp_head', array( $this, 'dns_prefetch' ) );
        add_action( 'template_redirect', array( $this, 'buffer' ) );
    }

    public function dns_prefetch() {
        $domain = "cdn.statically.io"; ?>
            <link rel='dns-prefetch' href='//<?php echo esc_attr( $domain ) ?>'/>
        <?php
    }

    public function buffer() {
        ob_start( array( $this, 'process_output' ) );
    }
                                      
    public function process_output($buffer) {
        $content_url = content_url();
        $content_url = str_replace( 'http://', '', $content_url );
        $content_url = str_replace( 'https://', '', $content_url );

        return preg_replace_callback(            
            '{'. $content_url .'/.+?\.(jpg|jpeg|png|gif|ico|bmp|svg|webp)}i',
            array( $this, 'replace' ),
            $buffer
        );
    }

    public function replace( $matches ) {
        $url = isset( $matches[0] ) ? $matches[0] : '';
        return "cdn.statically.io/img/{$url}";
    }

}

new Statically_Images;