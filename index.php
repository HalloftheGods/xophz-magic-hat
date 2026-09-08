<?php
/**
 * The ultimate blank slate for Xophz-COMPASS Magic Wand.
 */

get_header(); ?>

<div class="<?php echo esc_attr( mh_get_content_container_classes() ); ?>">
	<?php if ( mh_has_left_sidebar() ) : ?>
		<?php mh_render_sidebar( 'left' ); ?>
	<?php endif; ?>

	<!-- Magic Wand Content Goes Here -->
	<main id="mw-content" class="mw-template-part mw-dropzone site-main" data-mw-type="content" style="min-height: 200px; padding: 10px;">
		<?php
		// Basic fallback output so it isn't completely empty if previewed directly
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		} else {
			echo '<p>' . esc_html__( 'Drag and drop your content here.', 'xophz-magic-hat' ) . '</p>';
		}
		?>
	</main>

	<?php if ( mh_has_right_sidebar() ) : ?>
		<?php mh_render_sidebar( 'right' ); ?>
	<?php endif; ?>
</div>

<?php get_footer();
