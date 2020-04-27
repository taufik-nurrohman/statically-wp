<?php

class Statically_PageBooster
{
    public static function add_js() {
        $options = Statically::get_options();

        if ( $options['pagebooster_content'] ) {
            $content = $options['pagebooster_content'];
        } else {
            $content = 'html';
        }

        $inline = 'let f3h = new F3H(state = {
            cache: true,
        }),
            content = document.querySelector("' . $content . '");
        f3h.on(200, function(response) {
            document.title = response.title;
            content.innerHTML = response.querySelector("' . $content . '").innerHTML;
        });';

        wp_enqueue_script( 'statically-f3h', Statically::CDN . 'gh/taufik-nurrohman/f3h/543cce1/f3h.min.js', array(), STATICALLY_VERSION );
        wp_add_inline_script( 'statically-f3h', $inline );
    }
}
