<?php
/**
 * Front Page Template (One Page Express Architecture)
 *
 * Dedicated homepage canvas featuring customizable Hero section,
 * modular page builder sections (full width / boxed), and zero intrusive page titles.
 *
 * @package Xophz_Magic_Hat
 */

get_header();

// 1. Dedicated Hero Section
if ( function_exists( 'mh_render_hero_markup' ) ) {
	echo mh_render_hero_markup();
}
?>

<div class="<?php echo esc_attr( mh_get_content_container_classes() ); ?>">
	<?php if ( mh_has_left_sidebar() ) : ?>
		<?php mh_render_sidebar( 'left' ); ?>
	<?php endif; ?>

	<main id="mw-front-content" class="mh-front-page-main site-main" data-mw-type="content">
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
