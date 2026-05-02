<?php
/**
 * The ultimate blank slate for Xophz-COMPASS Magic Wand.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-mw-canvas="true">
    <?php wp_body_open(); ?>
    
    <!-- Magic Wand Content Goes Here -->
    <div id="xophz-canvas-root">
        <?php
        // Basic fallback output so it isn't completely empty if previewed directly
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                the_content();
            }
        }
        ?>
    </div>

    <?php wp_footer(); ?>
</body>
</html>
