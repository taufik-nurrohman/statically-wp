<?php

/**
 * Statically_Settings
 *
 * @since 0.0.1
 */

class Statically_Settings
{


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
     * @change  0.4.2
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
        if ( ! isset( $data['emoji'] ) ) {
            $data['emoji'] = 0;
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
        if ( ! isset( $data['relative'] ) ) {
            $data['relative'] = 0;
        }
        if ( ! isset( $data['https'] ) ) {
            $data['https'] = 0;
        }
        if ( ! isset( $data['query_strings'] ) ) {
            $data['query_strings'] = 0;
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
            'emoji'           => (int)( $data['emoji'] ),
            'og'              => (int)( $data['og'] ),
            'og_theme'        => esc_attr( $data['og_theme'] ),
            'og_fontsize'     => esc_attr( $data['og_fontsize'] ),
            'og_type'         => esc_attr( $data['og_type'] ),
            'relative'        => (int)( $data['relative'] ),
            'https'           => (int)( $data['https'] ),
            'query_strings'   => (int)( $data['query_strings'] ),
            'private'         => (int)( $data['private'] ),
            'statically_api_key'  => esc_attr( $data['statically_api_key'] ),
        ];
    }


    /**
     * add settings page
     *
     * @since   0.0.1
     * @change  0.0.1
     */

    public static function add_settings_page()
    {
        $page = add_options_page(
            'Statically',
            'Statically',
            'manage_options',
            'statically',
            [
                __CLASS__,
                'settings_page',
            ]
        );
    }


    /**
     * register plugin styles
     * 
     * @since 0.0.1
     * @change 0.1.0
     */

    public function enqueue_styles() {
        $current_screen = get_current_screen();

        if ( strpos( $current_screen->base, 'statically' ) === false ) {
            return;
        }

        // main css
		wp_enqueue_style( 'statically', plugin_dir_url( __FILE__ ) . 'statically.css', array(), STATICALLY_VERSION );
    }


    /**
     * settings page
     *
     * @since   0.0.1
     * @change  0.4.2
     *
     * @return  void
     */

    public static function settings_page()
    {
        $options = Statically::get_options();

      ?>

<header class="stly-header-container">
    <div class="stly-header">
        <div class="logo">
            <a href="https://statically.io/" target="_blank" title="<?php _e( 'Optimization for your website static assets.', 'statically' ); ?>">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'statically.svg'; ?>" />
            </a>
        </div>

        <nav>
            <ul>
                <li><a href="https://wordpress.org/support/plugin/statically/" target="_blank"><?php _e( 'Help', 'statically' ); ?></a></li>
                <li><a href="https://twitter.com/intent/follow?screen_name=staticallyio" target="_blank" title="Follow @staticallyio on Twitter"><i class="dashicons dashicons-twitter"></i></a></li>
            </ul>
        </nav>
    </div>
</header>

<section class="stly-plugin-container">
    <div class="stly stly-options wrap">
        <h2 style="display: none;"><?php _e( 'Statically', 'statically' ); ?></h2>

        <form method="post" action="options.php">
            <?php settings_fields( 'statically' ) ?>

            <table class="form-table">

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Statically CDN URL', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_url">
                                <input type="text" name="statically[url]" id="statically_url" value="<?php echo $options['url']; ?>" size="64" class="regular-text" required />
                            </label>

                            <p class="description">
                                <?php _e( 'Enter the CDN URL without trailing slash', 'statically' ); ?>. <?php _e( 'The format is', 'statically' ); ?> <code>https://cdn.statically.io/sites/:domain</code>
                            </p>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Statically API Key', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_api_key">
                                <input type="password" name="statically[statically_api_key]" id="statically_api_key" value="<?php echo $options['statically_api_key']; ?>" size="64" class="regular-text" required />
                            </label>

                            <p class="description">
                                <?php _e( 'Statically API key to make this plugin working &#8212; <a href="https://statically.io/wordpress" target="_blank">Get one here</a>', 'statically' ); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Asset Inclusions', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_dirs">
                                <input type="text" name="statically[dirs]" id="statically_dirs" value="<?php echo $options['dirs']; ?>" size="64" class="regular-text" />
                                <?php _e( 'Default: <code>wp-content,wp-includes</code>', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( 'Assets in these directories will be pointed to the CDN URL. Enter the directories separated by', 'statically' ); ?> <code>,</code>
                            </p>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Asset Exclusions', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_excludes">
                                <input type="text" name="statically[excludes]" id="statically_excludes" value="<?php echo $options['excludes']; ?>" size="64" class="regular-text" />
                                <?php _e( 'Default: <code>.php</code>', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( 'Enter the exclusions (directories or extensions) separated by', 'statically' ); ?> <code>,</code>
                            </p>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top" id="images">
                    <th scope="row">
                        <?php _e( 'Image Optimization', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_images">
                                <input type="checkbox" name="statically[images]" id="statically_images" value="1" checked="checked" disabled />
                                <?php _e( 'Automatically enabled for maximum performance of your image files.', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( 'Optimize all images via CDN. Add the ability to compress and resize. It also minimizes SVG files. Configure the settings below to set a different quality or size.', 'statically' ); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Image Quality', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_quality">
                                <input type="number" name="statically[quality]" id="statically_quality" value="<?php echo $options['quality']; ?>" min="0" max="100" style="max-width: 6em" />
                                <?php _e( ' % &#8212; Value between: <code>10 - 100</code>', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( 'Set the compression rate for all images.  Set <code>0</code> to disable.', 'statically' ); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Image Resize', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_width">
                                <h4 style="margin-top: 0;">Max-width</h4>
                                <input type="number" name="statically[width]" id="statically_width" value="<?php echo $options['width']; ?>" min="0" max="2000" style="max-width: 6em" />
                                <?php _e( ' px &#8212; Value up to: <code>2000</code>', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( 'Set the maximum width for all images. Set <code>0</code> to disable.', 'statically' ); ?>
                            </p>

                            <label for="statically_height">
                                <h4>Max-height</h4>
                                <input type="number" name="statically[height]" id="statically_height" value="<?php echo $options['height']; ?>" min="0" max="2000" style="max-width: 6em" />
                                <?php _e( ' px &#8212; Value up to: <code>2000</code>', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( 'Set the maximum height for all images. Set <code>0</code> to disable.', 'statically' ); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Auto WebP', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_webp">
                                <input type="checkbox" name="statically[webp]" id="statically_webp" value="1" <?php checked(1, $options['webp']) ?> />
                                <?php _e( 'Convert image into the next-gen WebP format when browser supports it. Default: <code>OFF</code>', 'statically' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'CSS & JS Minifications', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_minifications">
                                <input type="checkbox" name="statically[minifications]" id="statically_minifications" value="1" checked="checked" disabled />
                                <?php _e( 'Automatically enabled for maximum performance of your static assets.', 'statically' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Emoji CDN', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_emoji">
                                <input type="checkbox" name="statically[emoji]" id="statically_emoji" value="1" <?php checked(1, $options['emoji']) ?> />
                                <?php _e( 'Replace the default WordPress emoji CDN with Statically. Default: <code>ON</code>', 'statically' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Open Graph Image', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_og">
                                <input type="checkbox" name="statically[og]" id="statically_og" value="1" <?php checked(1, $options['og']) ?> />
                                <?php _e( 'Enable automatic Open Graph Image service. Default: <code>OFF</code>', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( 'When there is no Featured Image set, create an image from the page title with the Statically OG Image service and use it as your site\'s metadata. Useful for improving visibility on Facebook and Twitter. Learn more about <a href="https://ogp.me" target="_blank">The Open Graph protocol</a>.', 'statically' ); ?>
                            </p>

                            <label for="statically_og-theme">
                                <h4>Theme</h4>
                                <select name="statically[og_theme]">
                                    <option <?php if ( $options['og_theme'] === 'light' ) echo 'selected="selected"'; ?> value="light">light</option>
                                    <option <?php if ( $options['og_theme'] === 'dark' ) echo 'selected="selected"'; ?> value="dark">dark</option>
                                </select>
                            </label>

                            <label for="statically_og-fontsize">
                                <h4>Font Size</h4>
                                <select name="statically[og_fontsize]">
                                    <option <?php if ( $options['og_fontsize'] === 'medium' ) echo 'selected="selected"'; ?> value="medium">medium</option>
                                    <option <?php if ( $options['og_fontsize'] === 'large' ) echo 'selected="selected"'; ?> value="large">large</option>
                                    <option <?php if ( $options['og_fontsize'] === 'extra-large' ) echo 'selected="selected"'; ?> value="extra-large">extra-large</option>
                                </select>
                            </label>

                            <label for="statically_og-type">
                                <h4>File Type</h4>
                                <select name="statically[og_type]">
                                    <option <?php if ( $options['og_type'] === 'jpeg' ) echo 'selected="selected"'; ?> value="jpeg">jpeg</option>
                                    <option <?php if ( $options['og_type'] === 'png' ) echo 'selected="selected"'; ?> value="png">png</option>
                                </select>
                            </label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Relative Path', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_relative">
                                <input type="checkbox" name="statically[relative]" id="statically_relative" value="1" <?php checked(1, $options['relative']) ?> />
                                <?php _e( 'Enable CDN for relative paths. Default: <code>ON</code>', 'statically' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'CDN HTTPS', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_https">
                                <input type="checkbox" name="statically[https]" id="statically_https" value="1" <?php checked(1, $options['https']) ?> />
                                <?php _e( 'Enable CDN for HTTPS connections. Default: <code>ON</code>', 'statically' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Remove Query Strings', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_query_strings">
                                <input type="checkbox" name="statically[query_strings]" id="statically_query_strings" value="1" <?php checked(1, $options['query_strings']) ?> />
                                <?php _e( 'Strip query strings such as <code>?ver=1.0</code> from assets. Default: <code>ON</code>', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( 'Since Statically ignores query strings when downloading content from your site, it is recommended to leave this option enabled.', 'statically' ); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Disable for Logged-in Users', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_private">
                                <input type="checkbox" name="statically[private]" id="statically_private" value="1" <?php checked(1, $options['private']) ?> />
                                <?php _e( 'Turn off Statically for logged-in users. Default: <code>OFF</code>', 'statically' ); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Exclude from Pages with Query Strings', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_qs-excludes">
                                <input type="text" name="statically[qs_excludes]" id="statically_qs-excludes" value="<?php echo $options['qs_excludes']; ?>" size="64" class="regular-text" />
                                <?php _e( 'Default: <code>no-statically</code>', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( 'Pages with query string containing these parameters will not perform Statically optimization. For example, if we set <code>no-statically</code> then <code>/?no-statically=1</code> will not be optimized. Enter the query string keys separated by', 'statically' ); ?> <code>,</code>
                            </p>

                            <p class="description">
                                <?php _e( 'This can be useful when you use other plugins that require query strings to work and you want to turn off Statically to avoid possible JavaScript errors.', 'statically' ); ?>
                            </p>
                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <?php _e( 'Purge Files', 'statically' ); ?>
                    </th>
                    <td>
                        <fieldset>
                            <label for="statically_purge">
                                <?php _e( 'Coming soon!', 'statically' ); ?>
                            </label>

                            <p class="description">
                                <?php _e( '<strong>You can also try changing <a href="#images">Image Quality or Resize</a> settings</strong> to get the fresh version of the image.', 'statically' ); ?>
                            </p>

                            <p class="description">
                                <?php _e( 'While auto purging is not currently supported, alternatively you can use <a href="https://statically.io/purge" target="_blank">this page</a> to send purge request. Please note this solution could be much longer than expected, this is because we received dozens of purge request per day. We would suggest to rename the file instead if possible.', 'statically' ); ?>
                            </p>

                            <label style="margin-top: 2em!important;">
                                <i class="dashicons dashicons-info"></i>
                                <?php _e( 'Note that you must specify the full URL of the file in order for your request being procced.', 'statically' ); ?>
                            </label>

                            <ul>
                                <li>
                                    <code><em>https://cdn.statically.io/sites/example.com/myscript.js</em></code> <i class="dashicons dashicons-yes"></i>
                                </li>
                                <li>
                                    <code><em>https://cdn.statically.io/img/example.com/myphoto.jpg</em></code> <i class="dashicons dashicons-yes"></i>
                                </li>
                                <li>
                                    <code><em>https://example.com/myphoto.jpg</em></code> <i class="dashicons dashicons-yes"></i>
                                </li>
                            
                                <li>
                                    <code><em>https://cdn.statically.io/sites/example.com</em></code> <i class="dashicons dashicons-no"></i>
                                </li>
                                <li>
                                    <code><em>https://cdn.statically.io/img/example.com/*</em></code> <i class="dashicons dashicons-no"></i>
                                </li>
                                <li>
                                    <code><em>https://example.com/*</em></code> <i class="dashicons dashicons-no"></i>
                                </li>
                            </ul>
                        </fieldset>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
</section>

<footer class="stly stly-footer">
    <a href="https://statically.io/" target="_blank"><?php _e( 'About', 'statically'); ?></a>
    <a href="https://statically.io/contact/" target="_blank"><?php _e( 'Get a custom domain', 'statically'); ?></a>

    <div class="social">
        <span class="rating">
            <?php _e( '<span class="wide-display">Do you think this is useful? Be kind and</span> rate Statically', 'statically' ); ?>
            <a class="stars" href="https://wordpress.org/support/plugin/statically/reviews/?filter=5#new-post" target="_blank">
                <i class="dashicons dashicons-star-filled"></i>
                <i class="dashicons dashicons-star-filled"></i>
                <i class="dashicons dashicons-star-filled"></i>
                <i class="dashicons dashicons-star-filled"></i>
                <i class="dashicons dashicons-star-filled"></i>
            </a>
        </span>
        <p><?php _e( 'and', 'statically' ); ?></p>
        <span class="twitter">
            <span class="mobile-block">
                <?php _e( 'Share with your friends so that their website is as fast as yours!', 'statically' ); ?>
            </span>
            <a
                class="tweet"
                href="https://twitter.com/intent/tweet?hashtags=statically&amp;related=statically&amp;url=https://statically.io&amp;text=<?php _e( urlencode(  'The all-in-one solution for your website static asset optimization and CDN, try @staticallyio now!' ), 'statically' ); ?>"
                target="_blank"
            >
                <i class="dashicons dashicons-twitter"></i> <?php _e ( 'Tweet', 'statically' ); ?>
            </a>
        </span>
    </div>
</footer>
        <?php
    }
}
