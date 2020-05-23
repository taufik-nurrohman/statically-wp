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
                        <?php _e( 'Enable Custom Domain to get Purge API access.', 'statically' ); ?>
                        <a href="https://statically.io/contact/" target="_blank">Get a custom domain.</a>
                    </label>
                </fieldset>
            </td>
        </tr>
    </table>
</div>