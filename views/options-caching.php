<?php defined( 'ABSPATH' ) OR exit; ?>

<div data-stly-layout="caching">
    <h3 class="title">Caching</h3>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <?php _e( 'Purge Cache', 'statically' ); ?>
            </th>
            <td>
                <fieldset>
                    <label for="statically_purge">
                        <?php _e( 'Coming soon!', 'statically' ); ?>
                    </label>

                    <p class="description">
                        <?php _e( '<strong>You can also try changing <a data-stly-tab="optimization" href="#optimization">Image Quality or Resize</a> settings</strong> to get the fresh version of the image.', 'statically' ); ?>
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
</div>