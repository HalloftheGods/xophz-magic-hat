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

<main id="mw-page-content" class="mh-page-main" style="width: 100%; min-height: 40vh; position: relative; z-index: 1;">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	}
	?>
</main>

<?php
get_footer();
