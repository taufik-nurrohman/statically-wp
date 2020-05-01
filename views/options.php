<?php defined( 'ABSPATH' ) OR exit; ?>

<section class="stly-plugin-container">
    <div class="stly stly-options wrap">
        <h2 style="display: none;"><?php _e( 'Statically', 'statically' ); ?></h2>

            <?php if ( Statically::admin_pagenow( 'statically' ) ) : ?>

                <form method="post" action="options.php">
                <?php
                settings_fields( 'statically' );

                include STATICALLY_DIR . '/views/options-general.php';
                include STATICALLY_DIR . '/views/options-speed.php';
                include STATICALLY_DIR . '/views/options-extra.php';
                include STATICALLY_DIR . '/views/options-caching.php';
                include STATICALLY_DIR . '/views/options-labs.php';
                include STATICALLY_DIR . '/views/options-tools.php';

                ?>
                </form>
            
            <?php endif; ?>

            <?php if ( Statically::admin_pagenow( 'statically-debugger' ) ) :
                include STATICALLY_DIR . '/views/debugger.php';
            endif; ?>
    </div>
</section>