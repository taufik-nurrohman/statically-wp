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
    var $width          = null;     // set image width
    var $height         = null;     // set image height
    var $webp           = false;    // enable WebP
    var $external_images = null;    // set external image domains
    var $emoji          = false;    // set emoji CDN
    var $favicon        = false;    // set website's Favicon
    var $favicon_shape  = null;     // set favicon's shape
    var $favicon_bg     = null;     // set favicon's background
    var $favicon_color = null;     // set favicon's font color
    var $og             = false;    // enable OG Image service
    var $og_theme       = null;     // set OG Image theme
    var $og_fontsize    = null;     // set OG Image font-size
    var $og_type        = null;     // set OG Image file type
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
     * @change  0.5.0
     */

    function __construct(
        $blog_url,
        $cdn_url,
        $dirs,
        array $excludes,
        $quality,
        $width,
        $height,
        $external_images,
        $webp,
        $emoji,
        $favicon,
        $favicon_shape,
        $favicon_bg,
        $favicon_color,
        $og,
        $og_theme,
        $og_fontsize,
        $og_type,
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
        $this->width          = $width;
        $this->height         = $height;
        $this->external_images = $external_images;
        $this->webp           = $webp;
        $this->emoji          = $emoji;
        $this->favicon        = $favicon;
        $this->favicon_shape  = $favicon_shape;
        $this->favicon_bg     = $favicon_bg;
        $this->favicon_color = $favicon_color;
        $this->og             = $og;
        $this->og_theme       = $og_theme;
        $this->og_fontsize    = $og_fontsize;
        $this->og_type        = $og_type;
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

        // OG image service
        if ( $this->og ) {
            add_action( 'wp_head', [ $this, 'og_image' ], 2 );
        }
        
        // Favicon service
        if ( $this->favicon ) {
            add_action( 'wp_head', [ $this, 'favicon' ], 2 );
        }

        // add DNS prefetch meta
        add_action( 'wp_head', [ $this, 'dns_prefetch' ], 1 );

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
     * @change  0.3.0
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
        if ( preg_match( '/\.(bmp|gif|jpe?g|png|webp)/', $asset[0] ) ) {
            // check options and apply transformations
            $asset[0] = str_replace( $blog_url, $blog_url . $this->image_tranformations(), $asset[0] );

            // relative URL
            if ( $this->relative && ! strstr( $asset[0], $blog_url ) ) {
                $asset[0] = str_replace( $asset[0], $this->image_tranformations() . $asset[0], $asset[0] );
            }

            // use /img/
            $cdn_url = str_replace( '/sites', '/img', $this->cdn_url );

            // if it's a custom domain
            if ( $this->is_custom_domain() && ( $this->quality || $this->width || $this->height ) ) {
                $cdn_url = $cdn_url . '/statically/img';
            }
        }

        // SVG image
        if ( preg_match( '/\.svg/', $asset[0] ) ) {
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

    protected function image_tranformations() {
        $tf = '/';

        // if image auto-webp is ON
        if ( $this->webp ) {
            $tf .= 'f=auto';
        }

        // if image width is ON
        if ( $this->width ) {
            $tf .= ',w=' . $this->width;
        }

        // if image height is ON
        if ( $this->height ) {
            $tf .= ',h=' . $this->height;
        }

        // if image quality is ON
        if ( $this->quality ) {
            $tf .= ',q=' . $this->quality;
        }

        // if everything are set except webp
        if ( $this->webp === 0 && (
                $this->width ||
                $this->height ||
                $this->quality
            ) ) {
            $tf = substr($tf, strpos($tf, ',') + 1);
            $tf = '/' . $tf;
        }

        return $tf;
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
     * check if the $cdn_url is custom domain
     *
     * @since   0.4.1
     * @change  0.4.1
     */

    protected function is_custom_domain() {
        return ! preg_match( '/cdn.statically.io/', $this->cdn_url );
    }


    /**
     * register new style URL
     * 
     * @since   0.1.0
     * @change  0.1.0
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
     * @since   0.1.0
     * @change  0.3.0
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

        // load wp-embed.js from Statically Libs instead of proxy it from the site
        wp_deregister_script( 'wp-embed' );
        wp_register_script( 'wp-embed', $this->statically_wpbase_url . "/c/$wp_version/wp-includes/js/wp-embed.min.js", false, $wp_version );
    }


    /**
     * set new emoji CDN URL
     * 
     * @since   0.1.0
     * @change  0.1.0
     */

    public function cdn_url_emoji() {
        $url = $this->statically_cdn_url . '/twemoji/';
        return $url;
    }


    /**
     * set new wp-emoji-release.min.js CDN URL
     * 
     * @since   0.1.0
     * @change  0.1.0
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
     * generate OG image
     * 
     * @since   0.4.0
     * @change  0.4.1
     */

    public function og_image() {
        if ( ! has_post_thumbnail() ) {
            $text = get_the_title();
            if ( is_home() ) {
                $text = get_bloginfo( 'name' );
            }
            if ( strlen( $text ) === 0 ) {
                return;
            }

            // start options
            $options = '';
            if ( $this->og_theme !== 'light' ) {
                $options = '/theme=' . $this->og_theme;
            }

            // define font size for OG Image Font Size option
            $font_lg = '120px';
            $font_xl = '150px';
            if ( $this->og_fontsize === 'large' ) {
                $fontsize = $font_lg;
            }
            if ( $this->og_fontsize === 'extra-large' ) {
                $fontsize = $font_xl;
            }

            // font size
            if ( $this->og_fontsize !== 'medium' ) {
                $options .= ',fontSize=' . $fontsize;
            }

            // image type
            $type = '.jpg';
            if ( $this->og_type === 'png' ) {
                $type = '.png';
            }

            // clean up params if the theme is light by finding and remove
            // the first comma `,` from options and change it to slash `/`
            if ( $this->og_theme === 'light' ) {
                $options = substr($options, strpos($options, ',') + 1);
                if ( $this->og_fontsize !== 'medium' ) {
                    $options = '/' . $options;
                }
            }

            // end options
            $options .= '/';

            // combine URL
            $url = $this->statically_cdn_url . '/og' . $options;
            $url = $url . rawurlencode( $text ) . $type;

            // this is where the OG Image service shows
            $og = '<meta property="og:image" content="' . $url . '" />' . "\n";
            $og .= '<meta property="og:image:secure_url" content="' . $url . '" />' . "\n";
            $og .= '<meta name="twitter:image" content="' . $url .'" />' . "\n";

            echo $og;
        }
    }

    /**
     * generate Favicon
     * 
     * @since   0.5.0
     * @change  0.5.0
     */

    public function favicon() {
        // default variables
        $size_small = '64';
        $size_medium = $size_small * 2;
        $size_large = $size_small * 4;
        $name = get_bloginfo( 'name' );
        $image = '/' . urlencode( $name ) . '.png';
        $favicon_bg = str_replace('#', '', $this->favicon_bg);
        $favicon_color = str_replace('#', '', $this->favicon_color);

        // set sizes for meta tag
        $sizes_small = $size_small . 'x' . $size_small;
        $sizes_medium = $size_medium . 'x' . $size_medium;

        // start options
        $options = '';
        if ( $this->favicon_shape !== 'rounded' ) {
            $options = '/square=1';
        }

        // option for background
        if ( $favicon_bg !== '000000' ) {
            $options .= ',bg=' . $favicon_bg;
        }

        // option for color
        if ( $favicon_color !== 'ffffff' ) {
            $options .= ',c=' . $favicon_color;
        }

        // option for size
        $options .= ',s=';

        // clean up params if shape is rounded by finding and remove
        // the first comma `,` from options and change it to slash `/`
        if ( $this->favicon_shape === 'rounded' && $favicon_bg === '000000' && $favicon_color === 'ffffff' ||
        $this->favicon_shape === 'rounded' && $favicon_bg !== '000000' && $favicon_color === 'ffffff' ||
        $this->favicon_shape === 'rounded' && $favicon_bg === '000000' && $favicon_color !== 'ffffff' ||
        $this->favicon_shape === 'rounded' && $favicon_bg !== '000000' && $favicon_color !== 'ffffff' ) {
            $options = substr($options, strpos($options, ',') + 1);
            $options = '/' . $options;
        }

        // combine URL
        $url = $this->statically_cdn_url . '/favicons/g' . $options;

        // meta tag
        $icon = '<link rel="icon" href="' . $url . $size_small . $image . '" sizes="' . $sizes_small . '" />' . "\n";
        $icon .= '<link rel="icon" href="' . $url . $size_medium . $image . '" sizes="' . $sizes_medium . '" />' . "\n";
        $icon .= '<link rel="apple-touch-icon-precomposed" href="' . $url . $size_large . $image . '" />' . "\n";

        echo $icon;
    }


    /**
     * add DNS prefetch meta
     * 
     * @since   0.4.1
     * @change  0.4.1
     */

    public function dns_prefetch() {
        // meta for custom domain
        if ( $this->is_custom_domain() ) {
            $domain = parse_url( $this->cdn_url, PHP_URL_HOST );
            $dns = '<link rel="dns-prefetch" href="//' . $domain . '" />' . "\n";
            echo $dns;
        }
    }


    /**
     * rewrite url
     *
     * @since   0.0.1
     * @change  0.5.0
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
        $blog_domain = parse_url( get_option( 'home' ), PHP_URL_HOST );
        $blog_url = $this->https
            ? '(https?:|)'.$this->relative_url( quotemeta( $this->blog_url ) )
            : '(http:|)'.$this->relative_url( quotemeta( $this->blog_url ) );
        $external_images = $excludes = array_map( 'trim', explode( ',', $this->external_images ) );

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

        // rules for proxying external images
        if ( $this->external_images ) {
            foreach ( $external_images as $domain ) {
                if ( !! $domain && ! strstr( $domain, $blog_domain ) ) {
                    $domain_regex = str_replace( '.', '\.', $domain );
                    $html = preg_replace(
                        "/(?:https?:)?\/\/$domain_regex(.*\.(?:bmp|gif|jpe?g|png|webp))/", $this->statically_cdn_url . '/img/' . $domain . $this->image_tranformations() . ',ext=1$1', $html
                    );
                }
            }
        }

        // call the cdn rewriter callback
        $cdn_html = preg_replace_callback( $regex_rule, [$this, 'rewrite_url'], $html );

        return $cdn_html;
    }
}
