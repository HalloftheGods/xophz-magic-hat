<?php
/**
 * Single Page Template
 *
 * Standard canvas for singular WordPress pages featuring optional Hero Settings,
 * clean content rendering, and full compatibility with Magic Wand and Gutenberg.
 *
 * @package Xophz_Magic_Hat
 */

get_header();

// 1. Optional Hero Section (rendered if enabled for this page)
if ( function_exists( 'mh_render_hero_markup' ) ) {
	echo mh_render_hero_markup();
}
?>

<div class="<?php echo esc_attr( mh_get_content_container_classes() ); ?>">
	<?php if ( mh_has_left_sidebar() ) : ?>
		<?php mh_render_sidebar( 'left' ); ?>
	<?php endif; ?>

	<main id="mw-page-content" class="mh-page-main site-main" data-mw-type="content">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
		}
		?>
	</main>

	<?php if ( mh_has_right_sidebar() ) : ?>
		<?php mh_render_sidebar( 'right' ); ?>
	<?php endif; ?>
</div>

<?php
get_footer();
