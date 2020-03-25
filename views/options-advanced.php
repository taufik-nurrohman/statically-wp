<?php defined( 'ABSPATH' ) OR exit; ?>

<div data-stly-layout="advanced">
    <h3 class="title">Advanced</h3>          
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e( 'Enable on WP-Admin', 'statically' ); ?>
            </th>
            <td>
                <fieldset>
                    <label for="statically_wpadmin">
                        <input type="checkbox" name="statically[wpadmin]" id="statically_wpadmin" value="1" <?php checked(1, $options['wpadmin']) ?> />
                        <?php _e( 'Enable Statically on WP-Admin area. Default: <code>OFF</code>', 'statically' ); ?>
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
                        <?php _e( 'Strip query strings like <code>?ver=1.0</code> from assets. Default: <code>ON</code>', 'statically' ); ?>
                    </label>

                    <p class="description">
                        <?php _e( 'Since Statically ignores query strings when downloading content from your site, it is recommended to leave this option enabled.', 'statically' ); ?>
                    </p>
                </fieldset>
            </td>
        </tr>
    </table>
</div>