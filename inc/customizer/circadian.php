<?php
/**
 * Circadian Astronomical Clock & Stylebook Template Routing
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Virtual Page: Render the Stylebook
 */
function xophz_magic_hat_stylebook_template() {
	if ( isset( $_GET['magic_hat_stylebook'] ) && $_GET['magic_hat_stylebook'] == '1' ) {
		nocache_headers();
		require_once get_template_directory() . '/inc/stylebook-template.php';
		exit;
	}
}
add_action( 'template_redirect', 'xophz_magic_hat_stylebook_template' );

/**
 * Add Circadian Phase to Body Classes for immediate CSS matching
 */
function mh_circadian_body_class( $classes ) {
	$schedule_mode = get_theme_mod( 'mh_color_schedule_mode', 'circadian' );
	if ( $schedule_mode === 'circadian' ) {
		$now_hours = (float) current_time( 'H' ) + ( (float) current_time( 'i' ) / 60 );
		$ratio     = ( cos( ( $now_hours - 12 ) * M_PI / 12 ) + 1 ) / 2;
		$classes[] = ( $ratio >= 0.5 ) ? 'phase-day' : 'phase-night';
	}
	return $classes;
}
add_filter( 'body_class', 'mh_circadian_body_class' );

/**
 * 24-Hour Circadian Rhythm Astronomical Calculator
 */
function mh_circadian_rhythm_scripts() {
	$schedule_mode = get_theme_mod( 'mh_color_schedule_mode', 'circadian' );
	if ( $schedule_mode !== 'circadian' ) {
		return;
	}
	?>
	<script>
		(function() {
			function updateDaylight() {
				var now = new Date();
				var hours = now.getHours() + now.getMinutes() / 60 + now.getSeconds() / 3600;
				// cos((hours - 12) * PI / 12) = 1 at 12:00 (Noon), -1 at 00:00 (Midnight)
				var ratio = (Math.cos((hours - 12) * Math.PI / 12) + 1) / 2;
				var phasePercent;
				var isDay = ratio >= 0.5;
				if (isDay) {
					// Day Phase: Mix Light (100%) and Twilight (0%)
					phasePercent = ((ratio - 0.5) * 200).toFixed(2) + '%';
					document.documentElement.classList.remove('phase-night');
					document.documentElement.classList.add('phase-day');
					if (document.body) {
						document.body.classList.remove('phase-night');
						document.body.classList.add('phase-day');
					}
				} else {
					// Night Phase: Mix Twilight (100%) and Dark (0%)
					phasePercent = (ratio * 200).toFixed(2) + '%';
					document.documentElement.classList.remove('phase-day');
					document.documentElement.classList.add('phase-night');
					if (document.body) {
						document.body.classList.remove('phase-day');
						document.body.classList.add('phase-night');
					}
				}
				
				document.documentElement.style.setProperty('--mh-phase-primary', phasePercent);
				document.documentElement.style.setProperty('--mh-daylight', (ratio * 100).toFixed(2) + '%');
			}
			updateDaylight();
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', updateDaylight);
			}
			setInterval(updateDaylight, 60000); // Update every minute
		})();
	</script>
	<?php
}
add_action( 'wp_head', 'mh_circadian_rhythm_scripts', 1 );
