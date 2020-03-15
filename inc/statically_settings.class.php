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
     * @change  0.0.1
     *
     * @param   array  $data  array with form data
     * @return  array         array with validated values
     */

    public static function validate_settings( $data )
    {
        if ( ! isset( $data['emoji'] ) ) {
            $data['emoji'] = 0;
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
        if ( ! isset( $data['statically_api_key'] ) ) {
            $data['statically_api_key'] = '';
        }

        return [
            'url'             => esc_url( $data['url'] ),
            'dirs'            => esc_attr( $data['dirs'] ),
            'excludes'        => esc_attr( $data['excludes'] ),
            'quality'         => (int)( $data['quality'] ),
            'size'            => (int)( $data['size'] ),
            'emoji'           => (int)( $data['emoji'] ),
            'relative'        => (int)( $data['relative'] ),
            'https'           => (int)( $data['https'] ),
            'query_strings'   => (int)( $data['query_strings'] ),
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
     * @change  0.0.1
     *
     * @return  void
     */

    public static function settings_page()
    {
        $options = Statically::get_options();

      ?>

        <div class="statically wrap">
            <header>
                <div class="logo">
                    <a href="https://statically.io/" target="_blank" title="<?php _e( 'Optimize your WordPress site', 'statically' ); ?>">
                        <img src="<?php echo plugin_dir_url( __FILE__ ) . 'statically.svg'; ?>" />
                    </a>
                </div>

                <nav>
                    <ul>
                        <li><a href="https://wordpress.org/support/plugin/statically/" target="_blank"><?php _e( 'Help', 'statically' ); ?></a></li>
                        <li><a href="https://twitter.com/intent/follow?screen_name=staticallyio" target="_blank" title="Follow @staticallyio on Twitter"><i class="dashicons dashicons-twitter"></i></a></li>
                    </ul>
                </nav>
            </header>

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
                                    <input type="text" name="statically[url]" id="statically_url" value="<?php echo $options['url']; ?>" size="64" class="regular-text" />
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
                                   <input type="password" name="statically[statically_api_key]" id="statically_api_key" value="<?php echo $options['statically_api_key']; ?>" size="64" class="regular-text" />
                               </label>

                               <p class="description">
                                   <?php _e( 'Statically API key to make this plugin working &#8212; <a href="https://statically.io/wordpress" target="_blank">Get one here</a>', 'statically' ); ?>
                               </p>
                            </fieldset>
                       </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <?php _e( 'Included Directories', 'statically' ); ?>
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
                            <?php _e( 'Exclusions', 'statically' ); ?>
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

                    <tr valign="top">
                        <th scope="row">
                            <?php _e( 'Image Optimization', 'statically' ); ?>
                        </th>
                        <td>
                            <fieldset>
                                <label for="statically_images">
                                    <input type="checkbox" name="statically[images]" id="statically_images" value="1" checked="checked" disabled />
                                    <?php _e( 'Automatically enabled for maximum performance of your image files.', 'statically' ); ?>
                                </label>
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
                                    <?php _e( 'Value between: <code>10 - 100</code>', 'statically' ); ?>
                                </label>

                                <p class="description">
                                    <?php _e( 'Set the maximum quality for all images. Set <code>0</code> to disable.', 'statically' ); ?>
                                </p>
                            </fieldset>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <?php _e( 'Image Size', 'statically' ); ?>
                        </th>
                        <td>
                            <fieldset>
                                <label for="statically_size">
                                    <input type="number" name="statically[size]" id="statically_size" value="<?php echo $options['size']; ?>" min="0" max="2000" style="max-width: 6em" />
                                    <?php _e( 'Value up to: <code>2000</code>', 'statically' ); ?>
                                </label>

                                <p class="description">
                                    <?php _e( 'Set the maximum size for all images. Set <code>0</code> to disable.', 'statically' ); ?>
                                </p>
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
                                    <?php _e( 'Replace the default WordPress emoji CDN with Statically (default: enabled).', 'statically' ); ?>
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
                                    <?php _e( 'Enable CDN for relative paths (default: enabled).', 'statically' ); ?>
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
                                   <?php _e( 'Enable CDN for HTTPS connections (default: enabled).', 'statically' ); ?>
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
                                   <?php _e( 'Strip query strings such as <code>?ver=1.0</code> from assets.', 'statically' ); ?>
                               </label>

                               <p class="description">
                                   <?php _e( 'Because Statically ignores query strings when downloading content from your site, it is recommended to leave this enabled.', 'statically' ); ?>
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
                                <label for="statically_relative">
                                    <?php _e( 'Coming soon! While auto purging is not currently supported, alternatively you can use <a href="https://statically.io/purge" target="_blank">this page</a> to send purge request. Please note this solution could be much longer than expected, this is because we received dozens of purge request per day. We would suggest to rename the file instead if possible.', 'statically' ); ?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
        </div><?php
    }
}
