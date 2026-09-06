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

// 1. Dedicated Front Page Hero Section
if ( function_exists( 'mh_render_hero_markup' ) ) {
	echo mh_render_hero_markup();
}
?>

<main id="mw-front-content" class="mh-front-page-main" style="width: 100%; min-height: 40vh; position: relative; z-index: 1;">
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
