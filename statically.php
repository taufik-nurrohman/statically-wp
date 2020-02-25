<?php
/*
   Plugin Name: Statically
   Plugin URI:  https://statically.io/wordpress
   Description: Free CDN to optimize your static assets such as images, CSS or JavaScript files.
   Author:      Statically
   Author URI:  https://statically.io
   Text Domain: statically
   License:     GPLv2 or later
   Version:     0.0.1
 */


/* Check & Quit */
defined('ABSPATH') OR exit;


/* constants */
define('STATICALLY_VERSION', '0.0.1');
define('STATICALLY_FILE', __FILE__);
define('STATICALLY_DIR', dirname(__FILE__));
define('STATICALLY_BASE', plugin_basename(__FILE__));
define('STATICALLY_MIN_WP', '3.8');


/* loader */
add_action(
    'plugins_loaded',
    [
        'Statically',
        'instance',
    ]
);


/* uninstall */
register_uninstall_hook(
    __FILE__,
    [
        'Statically',
        'handle_uninstall_hook',
    ]
);


/* activation */
register_activation_hook(
    __FILE__,
    [
        'Statically',
        'handle_activation_hook',
    ]
);


/* autoload init */
spl_autoload_register('STATICALLY_autoload');

/* autoload funktion */
function STATICALLY_autoload($class) {
    if ( in_array($class, ['Statically', 'Statically_Rewriter', 'Statically_Settings']) ) {
        require_once(
            sprintf(
                '%s/inc/%s.class.php',
                STATICALLY_DIR,
                strtolower($class)
            )
        );
    }
}
