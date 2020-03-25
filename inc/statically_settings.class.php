<?php

/**
 * Statically_Settings
 *
 * @since 0.0.1
 */

class Statically_Settings
{
    const ICON_BASE64_SVG = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYuNjc1bW0iIGhlaWdodD0iMTYuNjc1bW0iIGNsaXAtcnVsZT0iZXZlbm9kZCIgZmlsbC1ydWxlPSJldmVub2RkIiBpbWFnZS1yZW5kZXJpbmc9Im9wdGltaXplUXVhbGl0eSIgc2hhcGUtcmVuZGVyaW5nPSJnZW9tZXRyaWNQcmVjaXNpb24iIHRleHQtcmVuZGVyaW5nPSJnZW9tZXRyaWNQcmVjaXNpb24iIHZpZXdCb3g9IjAgMCA0NDUuNjcgNDQ1LjMzIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxkZWZzPjxsaW5lYXJHcmFkaWVudCBpZD0iYSIgeDE9IjExMC42NyIgeDI9IjMzNC4zMiIgeTE9IjQxNC44IiB5Mj0iMzAuMTkiIGdyYWRpZW50VHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCwxNTU1LjcpIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHN0b3Agc3RvcC1jb2xvcj0iI2Q3MjQzMCIgb2Zmc2V0PSIwIi8+PHN0b3Agc3RvcC1jb2xvcj0iI2ZkYTI1OSIgb2Zmc2V0PSIxIi8+PC9saW5lYXJHcmFkaWVudD48L2RlZnM+PGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLjMzNDI3IC0xNTU2KSI+PHBhdGggZD0ibTMwIDE2NjYuN2M2Mi0xMDcgMTk4LTE0MyAzMDQtODEgMTA3IDYyIDE0MyAxOTggODEgMzA0LTYyIDEwNy0xOTggMTQzLTMwNCA4MS0xMDctNjItMTQzLTE5OC04MS0zMDR6IiBmaWxsPSJ1cmwoI2EpIi8+PGcgdHJhbnNmb3JtPSJtYXRyaXgoMS4yMTk0IC4xMTIzNiAtLjExMjkyIDEuMjI1NSAtMjIuMjUxIDE0NzguNCkiIHN0cm9rZT0iI2ZlZmVmZSIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgc3Ryb2tlLXdpZHRoPSIyMy4wNDEiPjxnIHN0cm9rZT0iI2ZlZmVmZSIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgc3Ryb2tlLXdpZHRoPSIyMy4wNDEiPjxwYXRoIGQ9Ik0yODMgMjI2bC00Ny0xOSA3NC0xMDVMMTU2IDIyMmw1MiAxOC03MyAxMDZ6IiBmaWxsPSIjZmVmZWZlIiBzdHJva2U9IiNmZWZlZmUiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIHN0cm9rZS13aWR0aD0iMjMuMDQxIi8+PC9nPjwvZz48L2c+PC9zdmc+';


    /**
     * register settings
     *
     * @since   0.0.1
     * @change  0.0.1
     */

    public static function register_settings()
    {
        register_setting(
            'statically',
            'statically',
            [
                __CLASS__,
                'validate_settings',
            ]
        );
    }


    /**
     * validation of settings
     *
     * @since   0.0.1
     * @change  0.5.0
     *
     * @param   array  $data  array with form data
     * @return  array         array with validated values
     */

    public static function validate_settings( $data )
    {
        if ( ! isset( $data['quality'] ) ) {
            $data['quality'] = 0;
        }
        if ( ! isset( $data['width'] ) ) {
            $data['width'] = 0;
        }
        if ( ! isset( $data['height'] ) ) {
            $data['height'] = 0;
        }
        if ( ! isset( $data['webp'] ) ) {
            $data['webp'] = 0;
        }
        if ( ! isset( $data['emoji'] ) ) {
            $data['emoji'] = 0;
        }
        if ( ! isset( $data['favicon'] ) ) {
            $data['favicon'] = 0;
        }
        if ( ! isset( $data['favicon_shape'] ) ) {
            $data['favicon_shape'] = 'rounded';
        }
        if ( ! isset( $data['favicon_bg'] ) ) {
            $data['favicon_bg'] = '#000000';
        }
        if ( ! isset( $data['favicon_color'] ) ) {
            $data['favicon_color'] = '#ffffff';
        }
        if ( ! isset( $data['og'] ) ) {
            $data['og'] = 0;
        }
        if ( ! isset( $data['og_theme'] ) ) {
            $data['og_theme'] = 'light';
        }
        if ( ! isset( $data['og_theme'] ) ) {
            $data['og_fontsize'] = 'medium';
        }
        if ( ! isset( $data['og_type'] ) ) {
            $data['og_type'] = 'jpeg';
        }
        if ( ! isset( $data['wpadmin'] ) ) {
            $data['wpadmin'] = 0;
        }
        if ( ! isset( $data['relative'] ) ) {
            $data['relative'] = 0;
        }
        if ( ! isset( $data['https'] ) ) {
            $data['https'] = 0;
        }
        if ( ! isset( $data['query_strings'] ) ) {
            $data['query_strings'] = 0;
        }
        if ( ! isset( $data['wpcdn'] ) ) {
            $data['wpcdn'] = 0;
        }
        if ( ! isset( $data['private'] ) ) {
            $data['private'] = 0;
        }
        if ( ! isset( $data['statically_api_key'] ) ) {
            $data['statically_api_key'] = '';
        }

        return [
            'url'             => esc_url( $data['url'] ),
            'dirs'            => esc_attr( $data['dirs'] ),
            'excludes'        => esc_attr( $data['excludes'] ),
            'qs_excludes'     => esc_attr( $data['qs_excludes'] ),
            'quality'         => (int)( $data['quality'] ),
            'width'           => (int)( $data['width'] ),
            'height'          => (int)( $data['height'] ),
            'webp'            => (int)( $data['webp'] ),
            'external_images'  => esc_attr( $data['external_images'] ),
            'emoji'           => (int)( $data['emoji'] ),
            'favicon'         => (int)( $data['favicon'] ),
            'favicon_shape'   => esc_attr( $data['favicon_shape'] ),
            'favicon_bg'      => esc_attr( $data['favicon_bg'] ),
            'favicon_color'   => esc_attr( $data['favicon_color'] ),
            'og'              => (int)( $data['og'] ),
            'og_theme'        => esc_attr( $data['og_theme'] ),
            'og_fontsize'     => esc_attr( $data['og_fontsize'] ),
            'og_type'         => esc_attr( $data['og_type'] ),
            'wpadmin'         => (int)( $data['wpadmin'] ),
            'relative'        => (int)( $data['relative'] ),
            'https'           => (int)( $data['https'] ),
            'query_strings'   => (int)( $data['query_strings'] ),
            'wpcdn'           => (int)( $data['wpcdn'] ),
            'private'         => (int)( $data['private'] ),
            'statically_api_key'  => esc_attr( $data['statically_api_key'] ),
        ];
    }


    /**
     * add menu page
     *
     * @since   0.0.1
     * @change  0.5.0
     */

    public static function add_settings_page()
    {
        $page = add_menu_page(
            'Statically',
            'Statically',
            'manage_options',
            'statically',
            [
                __CLASS__,
                'settings_page',
            ],
            self::ICON_BASE64_SVG
        );
    }


    /**
     * Adjusts position of dashboard menu icons
     *
     * @param string[] $menu_order list of menu items
     * @return string[] list of menu items
     */
    public static function set_menu_order( array $menu_order ) : array {
        $order = [];
        $file  = plugin_basename( __FILE__ );

        foreach ( $menu_order as $index => $item ) {
            if ( $item === 'index.php' ) {
                $order[] = $item;
            }
        }

        $order = array(
            'index.php',
            'statically',
        );

        return $order;
    }


    /**
     * settings page
     *
     * @since   0.0.1
     * @change  0.5.0
     *
     * @return  void
     */

    public static function settings_page()
    {
        $options = Statically::get_options();

        include STATICALLY_DIR . '/views/header.php';
        include STATICALLY_DIR . '/views/options.php';
        include STATICALLY_DIR . '/views/footer.php';
    }
}
