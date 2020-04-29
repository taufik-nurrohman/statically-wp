<?php defined( 'ABSPATH' ) OR exit; ?>

<div data-stly-layout="labs">
    <h3 class="title"><?php _e( 'Labs', 'statically' ); ?></h3>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e( 'Load pages without reloading', 'statically' ); ?>
            </th>
            <td>
                <fieldset>
                    <label for="statically_pagebooster">
                        <input type="checkbox" name="statically[pagebooster]" id="statically_pagebooster" value="1" <?php checked(1, $options['pagebooster']) ?> />
                        <?php _e( 'Load pages asynchronously using AJAX while maintaining the principles of progressive enhancement. Default: <code>OFF</code>', 'statically' ); ?>
                    </label>
                </fieldset>

                <fieldset>
                    <label for="statically_pagebooster-content">
                        <h4>Selector</h4>
                        <input type="text" name="statically[pagebooster_content]" id="statically_pagebooster-content" value="<?php echo $options['pagebooster_content']; ?>" style="max-width: 10em" />
                        <?php _e( ' &#8212; Can be class, ID, or element', 'statically' ); ?>
                    </label>
                </fieldset>
            </td>
        </tr>
    </table>

    <?php submit_button(); ?>
</div>