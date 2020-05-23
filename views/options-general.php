<?php defined( 'ABSPATH' ) OR exit; ?>

<div data-stly-layout="general">
    <h3 class="title"><?php _e( 'General', 'statically' ); ?></h3>
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
                        <?php _e( 'Enter the CDN URL without trailing slash', 'statically' ); ?>. <?php _e( 'Example:', 'statically' ); ?> <code>https://cdn.statically.io/sites/example.com</code>
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
                        <?php _e( 'Statically API key to make this plugin working. Never share it to anybody! Treat this API key as a password. &#8212; <a href="https://statically.io/wordpress/" target="_blank">Get one here</a>', 'statically' ); ?>
                    </p>
                </fieldset>
            </td>
        </tr>

        <tr valign="top" <?php if ( !Statically::is_custom_domain() ) echo 'style="display:none"'; ?>>
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

        <tr valign="top" <?php if ( !Statically::is_custom_domain() ) echo 'style="display:none"'; ?>>
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
    </table>

    <?php submit_button(); ?>
</div>
