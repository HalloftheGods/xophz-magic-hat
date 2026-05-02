<?php
/**
 * The ultimate blank slate for Xophz-COMPASS Magic Wand.
 */

get_header(); ?>

    <!-- Magic Wand Content Goes Here -->
    <main id="mw-content" class="mw-template-part mw-dropzone" data-mw-type="content" style="min-height: 200px; outline: 1px dashed rgba(0,0,0,0.1); padding: 10px;">
        <?php
        // Basic fallback output so it isn't completely empty if previewed directly
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                the_content();
            }
        } else {
            echo '<p>Drag and drop your content here.</p>';
        }
        ?>
    </main>

<?php get_footer();
