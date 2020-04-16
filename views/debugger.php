<?php

defined( 'ABSPATH' ) OR exit;

global $wp_version;
$options = Statically::get_options();
unset( $options['statically_api_key'] );

?>

<pre class="pre debugger">
<strong>PHP version:</strong> <?php echo phpversion() . "\n"; ?>
<strong>WordPress version:</strong> <?php echo $wp_version . "\n"; ?>
<?php
    foreach ( $options as $key => $value ) {
        echo '<strong>' . $key . '</strong>: ' . $value . "\n";
    }
?>
<strong>Active Plugins</strong>: <?php echo wp_list_the_plugins() . "\n"; ?>
</pre>