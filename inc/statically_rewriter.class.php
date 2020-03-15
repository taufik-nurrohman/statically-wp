<?php

/**
 * Statically_Rewriter
 *
 * @since 0.0.1
 */

class Statically_Rewriter
{
    var $blog_url       = null;     // origin URL
    var $cdn_url        = null;     // Zone URL

    var $dirs           = null;     // included directories
    var $excludes       = [];       // excludes
    var $quality        = null;     // set image quality
    var $size           = null;     // set image size
    var $relative       = false;    // use CDN on relative paths
    var $https          = false;    // use CDN on HTTPS
    var $query_strings  = false;    // remove query strings from assets

    var $statically_api_key = null; // required API key for Statically
    var $statically_cdn_url = 'https://cdn.statically.io'; // Statically CDN URL
    var $statically_wpbase_url = 'https://cdn.statically.io/wp'; // Statically Libs for WP

    /**
     * constructor
     *
     * @since   0.0.1
     * @change  0.0.1
     */

    function __construct(
        $blog_url,
        $cdn_url,
        $dirs,
        array $excludes,
        $quality,
        $size,
        $emoji,
        $relative,
        $https,
        $query_strings,
        $statically_api_key
    ) {
        $this->blog_url       = $blog_url;
        $this->cdn_url        = $cdn_url;
        $this->dirs           = $dirs;
        $this->excludes       = $excludes;
        $this->quality        = $quality;
        $this->size           = $size;
        $this->emoji          = $emoji;
        $this->relative       = $relative;
        $this->https          = $https;
        $this->query_strings  = $query_strings;
        $this->statically_api_key = $statically_api_key;

        // remove query strings
        if ( $this->query_strings ) {
            add_filter( 'style_loader_src', [ $this, 'remove_query_strings' ], 999 );
            add_filter( 'script_loader_src', [ $this, 'remove_query_strings' ], 999 );
        }

        // replace default WordPress emoji CDN with Statically
        if ( $this->emoji ) {
            add_filter( 'emoji_svg_url', [ $this, 'cdn_url_emoji' ], 999 );
            add_filter( 'emoji_url', [ $this, 'cdn_url_emoji' ], 999 );
            add_filter( 'script_loader_src', [ $this, 'cdn_url_emoji_release_js' ], 10, 2 );
        }

        $this->_deregister_styles();
        $this->_deregister_scripts();
    }


    /**
     * exclude assets that should not be rewritten
     *
     * @since   0.0.1
     * @change  0.0.1
     *
     * @param   string  $asset  current asset
     * @return  boolean  true if need to be excluded
     */

    protected function exclude_asset( &$asset ) {
        // excludes
        foreach ( $this->excludes as $exclude ) {
            if ( !! $exclude && stristr( $asset, $exclude ) != false ) {
                return true;
            }
        }
        return false;
    }


    /**
     * relative url
     *
     * @since   0.0.1
     * @change  0.0.1
     *
     * @param   string  $url a full url
     * @return  string  protocol relative url
     */
    protected function relative_url( $url ) {
        return substr( $url, strpos( $url, '//' ) );
    }


    /**
     * rewrite url
     *
     * @since   0.0.1
     * @change  0.2.0
     *
     * @param   string  $asset  current asset
     * @return  string  updated url if not excluded
     */

    protected function rewrite_url( &$asset ) {
        if ( $this->exclude_asset( $asset[0]) ) {
            return $asset[0];
        }

        // Don't rewrite if in preview mode
        if ( is_admin_bar_showing()
                && array_key_exists( 'preview', $_GET )
                && $_GET['preview'] == 'true' )
        {
            return $asset[0];
        }

        $cdn_url = $this->cdn_url;
        $blog_url = $this->relative_url( $this->blog_url );
        $subst_urls = [ 'http:'.$blog_url ];

        // rewrite both http and https URLs if we ticked 'enable CDN for HTTPS connections'
        if ( $this->https ) {
            $subst_urls = [
                'http:'.$blog_url,
                'https:'.$blog_url,
            ];
        }

        // check if it is an image
        if ( preg_match( '/.(bmp|gif|jpe?g|png|webp)/', $asset[0] ) ) {
            // if image quality is set
            if ( $this->quality !== 0 && $this->size === 0 ) {
                $asset[0] = str_replace( $blog_url, $blog_url . '/q=' . $this->quality, $asset[0] );
            }

            // if image size is set
            if ( $this->quality === 0 && $this->size !== 0 ) {
                $asset[0] = str_replace( $blog_url, $blog_url . '/w=' . $this->size, $asset[0] );
            }

            // if both image quality and size are set
            if ( $this->quality !== 0 && $this->size !== 0 ) {
                $asset[0] = str_replace(
                    $blog_url, $blog_url . '/q=' . $this->quality . ',w=' . $this->size, $asset[0]
                );
            }

            // for relative URL when image quality is set
            if ( $this->relative
                    && ! strstr( $asset[0], $blog_url )
                    && $this->quality !== 0
                    && $this->size === 0 )
            {
                $asset[0] = str_replace( $asset[0], '/q=' . $this->quality . $asset[0], $asset[0] );
            }

            // for relative URL when image size is set
            if ( $this->relative
                    && !strstr( $asset[0], $blog_url )
                    && $this->quality === 0
                    && $this->size !== 0 )
            {
                $asset[0] = str_replace( $asset[0], '/w=' . $this->size . $asset[0], $asset[0] );
            }

            // for relative URL when both image quality and size are set
            if ( $this->relative
                    && ! strstr( $asset[0], $blog_url )
                    && $this->quality !== 0
                    && $this->size !== 0 )
            {
                $asset[0] = str_replace(
                    $asset[0], '/q=' . $this->quality . ',w=' . $this->size . $asset[0], $asset[0]
                );
            }

            // use /img/
            $cdn_url = str_replace( '/sites', '/img', $this->cdn_url );

            // if it's a custom domain
            if ( ! preg_match( '/cdn.statically.io/', $this->cdn_url ) ) {
                $cdn_url = $cdn_url . '/statically/img';
            }
        }

        // SVG image
        if ( preg_match( '/.svg/', $asset[0] ) ) {
            $cdn_url = str_replace( '/sites', '/img', $this->cdn_url );
        }

        // is it a protocol independent URL?
        if ( strpos( $asset[0], '//' ) === 0 ) {
            return str_replace( $blog_url, $cdn_url, $asset[0] );
        }

        // check if not a relative path
        if ( ! $this->relative || strstr( $asset[0], $blog_url ) ) {
            return str_replace( $subst_urls, $cdn_url, $asset[0] );
        }

        // relative URL
        return $cdn_url . $asset[0];
    }


    /**
     * get directory scope
     *
     * @since   0.0.1
     * @change  0.0.1
     *
     * @return  string  directory scope
     */

    protected function get_dir_scope() {
        $input = explode( ',', $this->dirs );

        // default
        if ( $this->dirs == '' || count( $input ) < 1 ) {
            return 'wp\-content|wp\-includes';
        }

        return implode( '|', array_map( 'quotemeta', array_map( 'trim', $input ) ) );
    }


    /**
     * register new style URL
     * 
     * @since 0.1.0
     */

    private function _deregister_styles() {
        global $wp_version;

        wp_deregister_style( 'wp-block-library' );
        wp_register_style( 'wp-block-library', $this->statically_wpbase_url . "/c/$wp_version/wp-includes/css/dist/block-library/style.min.css", false, $wp_version );

        wp_deregister_style( 'wp-block-library-theme' );
        wp_register_style( 'wp-block-library-theme', $this->statically_wpbase_url . "/c/$wp_version/wp-includes/css/dist/block-library/theme.min.css", false, $wp_version );

        wp_deregister_style( 'dashicons' );
        wp_register_style( 'dashicons', $this->statically_wpbase_url . "/c/$wp_version/wp-includes/css/dashicons.min.css", false, $wp_version );
    }


    /**
     * register new script URL
     * 
     * @since 0.1.0
     */

    private function _deregister_scripts() {
        global $wp_version;

        $jq_v = '1.12.4';
        $jq_migrate_v = '1.4.1';

        // load jQuery from Statically Libs instead of proxy it from the site
        wp_deregister_script( 'jquery-core' );
        wp_register_script( 'jquery-core', $this->statically_wpbase_url . "/c/$wp_version/wp-includes/js/jquery/jquery.js", false, $jq_v );

        // load jQuery migrate from Statically Libs instead of proxy it from the site
        wp_deregister_script( 'jquery-migrate' );
        wp_register_script( 'jquery-migrate', $this->statically_wpbase_url . "/c/$wp_version/wp-includes/js/jquery/jquery-migrate.min.js", false, $jq_migrate_v );
    }


    /**
     * set new emoji CDN URL
     * 
     * @since 0.1.0
     */

    public function cdn_url_emoji() {
        $url = $this->statically_cdn_url . '/twemoji/';
        return $url;
    }


    /**
     * set new wp-emoji-release.min.js CDN URL
     * 
     * @since 0.1.0
     */

    public function cdn_url_emoji_release_js( $src, $name ) {
        global $wp_version;

        if ( 'concatemoji' == $name ) {
            $src = $this->statically_wpbase_url . "/c/$wp_version/wp-includes/js/wp-emoji-release.min.js";
        }

        return $src;
    }


    /**
     * remove query strings from asset URL
     *
     * @since   0.1.0
     * @change  0.1.0
     *
     * @param   string  $src  original asset URL
     * @return  string  asset URL without query strings
     */

    public function remove_query_strings( $src ) {
		if ( strpos( $src, '.css?' ) !== false || strpos( $src, '.js?' ) !== false ) {
			$src = preg_replace( '/\?.*/', '', $src );
		}

		return $src;
    }


    /**
     * rewrite url
     *
     * @since   0.0.1
     * @change  0.0.1
     *
     * @param   string  $html  current raw HTML doc
     * @return  string  updated HTML doc with CDN links
     */

    public function rewrite( $html ) {
        // check if HTTPS and use CDN over HTTPS enabled
        if ( ! $this->https && isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == 'on' ) {
            return $html;
        }

        // get dir scope in regex format
        $dirs = $this->get_dir_scope();
        $blog_url = $this->https
            ? '(https?:|)'.$this->relative_url( quotemeta( $this->blog_url ) )
            : '(http:|)'.$this->relative_url( quotemeta( $this->blog_url ) );

        // regex rule start
        $regex_rule = '#(?<=[(\"\'])';

        // check if relative paths
        if ( $this->relative ) {
            $regex_rule .= '(?:'.$blog_url.')?';
        } else {
            $regex_rule .= $blog_url;
        }

        // regex rule end
        $regex_rule .= '/(?:((?:'.$dirs.')[^\"\')]+)|([^/\"\']+\.[^/\"\')]+))(?=[\"\')])#';

        // call the cdn rewriter callback
        $cdn_html = preg_replace_callback( $regex_rule, [$this, 'rewrite_url'], $html );

        return $cdn_html;
    }
}
