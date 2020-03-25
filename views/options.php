<?php defined( 'ABSPATH' ) OR exit; ?>

<section class="stly-plugin-container">
    <div class="stly stly-options wrap">
        <h2 style="display: none;"><?php _e( 'Statically', 'statically' ); ?></h2>

            <?php if ( Statically::admin_pagenow( 'statically' ) ) : ?>

                <form method="post" action="options.php">
                <?php
                settings_fields( 'statically' );

                include STATICALLY_DIR . '/views/options-general.php';
                include STATICALLY_DIR . '/views/options-optimization.php';
                include STATICALLY_DIR . '/views/options-misc.php';
                include STATICALLY_DIR . '/views/options-caching.php';
                include STATICALLY_DIR . '/views/options-advanced.php';

                submit_button(); ?>
                </form>
            
            <?php endif; ?>

            <?php if ( Statically::admin_pagenow( 'statically-debugger' ) ) :
                include STATICALLY_DIR . '/views/debugger.php';
            endif; ?>
    </div>
</section>