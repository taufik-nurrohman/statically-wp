<?php

/**
 * Statically
 *
 * @since 0.0.1
 */

class Statically
{


    /**
     * pseudo-constructor
     *
     * @since   0.0.1
     * @change  0.0.1
     */

    public static function instance() {
        new self();
    }


    /**
     * constructor
     *
     * @since   0.0.1
     * @change  0.0.1
     */

    public function __construct() {
        /* CDN rewriter hook */
        add_action(
            'template_redirect',
            [
                __CLASS__,
                'handle_rewrite_hook',
            ]
        );

        /* Rewrite rendered content in REST API */
        add_filter(
            'the_content',
            [
                __CLASS__,
                'rewrite_the_content',
            ],
            100
        );

        /* Hooks */
        add_action(
            'admin_init',
            [
                __CLASS__,
                'register_textdomain',
            ]
        );
        add_action(
            'admin_init',
            [
                'Statically_Settings',
                'register_settings',
            ]
        );
        add_action(
            'admin_menu',
            [
                'Statically_Settings',
                'add_settings_page',
            ]
        );
        add_action(
            'admin_enqueue_scripts',
            [
                'Statically_Settings',
                'enqueue_styles',
            ]
        );
        add_filter(
            'plugin_action_links_' . STATICALLY_BASE,
            [
                __CLASS__,
                'add_action_link',
            ]
        );

        /* admin notices */
        add_action(
            'all_admin_notices',
            [
                __CLASS__,
                'statically_requirements_check',
            ]
        );
    }


    /**
     * add action links
     *
     * @since   0.0.1
     * @change  0.0.1
     *
     * @param   array  $data  alreay existing links
     * @return  array  $data  extended array with links
     */

    public static function add_action_link($data) {
        // check permission
        if ( ! current_user_can( 'manage_options' ) ) {
            return $data;
        }

        return array_merge(
            $data,
            [
                sprintf(
                    '<a href="%s">%s</a>',
                    add_query_arg(
                        [
                            'page' => 'statically',
                        ],
                        admin_url( 'options-general.php' )
                    ),
                    __("Settings")
                ),
            ]
        );
    }


    /**
     * run uninstall hook
     *
     * @since   0.0.1
     * @change  0.0.1
     */

    public static function handle_uninstall_hook() {
        delete_option( 'statically' );
    }


    /**
     * run activation hook
     *
     * @since   0.0.1
     * @change  0.4.2
     */

    public static function handle_activation_hook() {
        add_option(
            'statically',
            [
                'url'            => 'https://cdn.statically.io/sites/' . parse_url( get_option( 'home' ), PHP_URL_HOST ),
                'dirs'           => 'wp-content,wp-includes',
                'excludes'       => '.php',
                'qs_excludes'    => 'no-statically',
                'quality'        => '0',
                'width'          => '0',
                'height'         => '0',
                'external_images' => '',
                'webp'           => '0',
                'emoji'          => '1',
                'favicon'        => '0',
                'og'             => '0',
                'og_theme'       => 'light',
                'og_fontsize'    => 'medium',
                'og_type'        => 'jpeg',
                'relative'       => '1',
                'https'          => '1',
                'query_strings'  => '1',
                'private'        => '0',
                'statically_api_key' => '',
            ]
        );
    }


    /**
     * check plugin requirements
     *
     * @since   0.0.1
     * @change  0.0.1
     */

    public static function statically_requirements_check() {
        // WordPress version check
        if ( version_compare( $GLOBALS['wp_version'], STATICALLY_MIN_WP.'alpha', '<') ) {
            show_message(
                sprintf(
                    '<div class="error"><p>%s</p></div>',
                    sprintf(
                        __( "Statically is optimized for WordPress %s. Please disable the plugin or upgrade your WordPress installation (recommended).", "statically" ),
                        STATICALLY_MIN_WP
                    )
                )
            );
        }
    }


    /**
     * register textdomain
     *
     * @since   0.0.1
     * @change  0.0.1
     */

    public static function register_textdomain() {
        load_plugin_textdomain(
            'statically',
            false,
            'statically/lang'
        );
    }


    /**
     * return plugin options
     *
     * @since   0.0.1
     * @change  0.4.2
     *
     * @return  array  $diff  data pairs
     */

    public static function get_options() {
        return wp_parse_args(
            get_option( 'statically' ),
            [
                'url'             => 'https://cdn.statically.io/sites/' . parse_url( get_option( 'home' ), PHP_URL_HOST ),
                'dirs'            => 'wp-content,wp-includes',
                'excludes'        => '.php',
                'qs_excludes'     => 'no-statically',
                'quality'         => '0',
                'width'           => '0',
                'height'          => '0',
                'external_images'  => '',
                'webp'            => 0,
                'emoji'           => 1,
                'favicon'         => 0,
                'og'              => 0,
                'og_theme'        => 'light',
                'og_fontsize'     => 'medium',
                'og_type'         => 'jpeg',
                'relative'        => 1,
                'https'           => 1,
                'query_strings'   => 1,
                'private'         => 0,
                'statically_api_key'  => '',
            ]
        );
    }


    /**
     * return new rewriter
     *
     * @since   0.0.1
     * @change  0.4.2
     *
     */

    public static function get_rewriter() {
        $options = self::get_options();

        $excludes = array_map( 'trim', explode( ',', $options['excludes'] ) );

        return new Statically_Rewriter(
            get_option( 'home' ),
            $options['url'],
            $options['dirs'],
            $excludes,
            $options['quality'],
            $options['width'],
            $options['height'],
            $options['external_images'],
            $options['webp'],
            $options['emoji'],
            $options['favicon'],
            $options['og'],
            $options['og_theme'],
            $options['og_fontsize'],
            $options['og_type'],
            $options['relative'],
            $options['https'],
            $options['query_strings'],
            $options['private'],
            $options['statically_api_key']
        );
    }


    /**
     * run rewrite hook
     *
     * @since   0.0.1
     * @change  0.4.2
     */

    public static function handle_rewrite_hook() {
        $options = self::get_options();
        $qs_excludes = array_map( 'trim', explode( ',', $options['qs_excludes'] ) );

        // check if origin equals cdn url
        if ( get_option( 'home' ) == $options['url'] ) {
            return;
        }

        // check if Statically API Key is set before start rewriting
        if ( ! array_key_exists( 'statically_api_key', $options )
              || strlen( $options['statically_api_key'] ) < 32 ) {
            return;
        }

        // check for query strings that should be ignored from rewriting
        foreach ( $qs_excludes as $qs_exclude ) {
            if ( !! $qs_exclude && array_key_exists( $qs_exclude, $_GET ) ) {
                return;
            }
        }

        // check if private is enabled
        if ( $options['private'] === 1 && is_user_logged_in() ) {
            return;
        }

        $rewriter = self::get_rewriter();
        ob_start( array( &$rewriter, 'rewrite' ) );
    }


    /**
     * rewrite html content
     *
     * @since   0.0.1
     * @change  0.0.1
     */

    public static function rewrite_the_content( $html ) {
        $rewriter = self::get_rewriter();
        return $rewriter->rewrite( $html );
    }

}
