<?php defined( 'ABSPATH' ) OR exit; ?>

<div data-stly-layout="labs">
    <h3 class="title"><?php _e( 'Labs', 'statically' ); ?></h3>
    <p class="description">
        <?php _e( 'Everyone likes to try things! This is a list of Beta features, these options are intended to try out new features from Statically for WordPress based websites. We will add related feature as main menu when we receive good feedback for it. Use wisely!', 'statically' ); ?>
    </p>

    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e( 'Load pages without reloading', 'statically' ); ?>
            </th>
            <td>
                <fieldset>
                    <label for="statically_pagebooster">
                        <input type="checkbox" name="statically[pagebooster]" id="statically_pagebooster" value="1" <?php checked(1, $options['pagebooster']) ?> />
                        <?php _e( 'Make your WordPress smooth and performant by applying progressive enhancements to internal pages. Default: <code>OFF</code>', 'statically' ); ?>
                    </label>
                </fieldset>

                <fieldset>
                    <label for="statically_pagebooster-content">
                        <h4><?php _e( 'Selector', 'statically' ); ?></h4>
                        <input type="text" name="statically[pagebooster_content]" id="statically_pagebooster-content" value="<?php echo $options['pagebooster_content']; ?>" style="max-width: 10em" />
                        <?php _e( ' &#8212; Can be class, ID, or element.', 'statically' ); ?>
                    </label>
                </fieldset>

                <?php if ( $options['dev'] ) : ?>
                <fieldset>
                    <label for="statically_pagebooster-custom-js">
                        <h4><?php _e( 'F3H Settings (custom JS)', 'statically' ); ?></h4>
                        <textarea type="text" name="statically[pagebooster_custom_js]" id="statically_pagebooster-custom-js"><?php echo $options['pagebooster_custom_js']; ?></textarea><br>

                        <input type="checkbox" name="statically[pagebooster_custom_js_enabled]" id="statically_pagebooster-custom-js-enabled" value="1" <?php checked(1, $options['pagebooster_custom_js_enabled']) ?> />
                        <?php _e( 'Enable this custom JS will overwrite Selector setting above.', 'statically' ); ?>

                        <p class="description"><?php _e( 'This feature uses F3H.js as the main function, matching this setting with your site to achieve the best possible progressive WordPress. Learn more how to configure F3H <a href="https://statically.io/go/f3h" target="_blank">here</a>.', 'statically' ); ?></p>
                    </label>
                </fieldset>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <?php submit_button(); ?>
</div>