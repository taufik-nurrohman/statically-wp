<?php

/**
 * Statically_Debugger
 *
 * @since 0.5.0
 */

class Statically_Debugger
{

    /**
     * add settings page
     *
     * @since 0.0.1
     */
    public static function add_settings_page() {
        $page = add_submenu_page(
            [ 'Statically_Settings', 'add_settings_page' ],
            'Statically Debugging Center',
            '',
            'manage_options',
            'statically-debugger',
            [
                __CLASS__,
                'settings_page',
            ]
        );
    }

    /**
     * settings page
     *
     * @since 0.0.1
     *
     * @return void
     */
    public static function settings_page() {
        $options = Statically::get_options();

        include STATICALLY_DIR . '/views/header.php';
        include STATICALLY_DIR . '/views/options.php';
        include STATICALLY_DIR . '/views/footer.php';
    }
}
