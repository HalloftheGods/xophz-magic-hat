<?php
/**
 * Customizer Dynamic CSS Variables & Global Styles Generator
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Customizer CSS variables in the <head>
 */
function xophz_magic_hat_customizer_css() {
	$font_family    = get_theme_mod( 'mh_font_family', 'Inter, sans-serif' );
	$font_size      = get_theme_mod( 'mh_font_size', '16' );
	$line_height    = get_theme_mod( 'mh_line_height', '1.6' );
	$heading_weight = get_theme_mod( 'mh_heading_weight', '600' );
	$heading_lh     = get_theme_mod( 'mh_heading_line_height', '1.2' );
	
	// Extract font name for Google Fonts API (e.g. "Space Grotesk, sans-serif" -> "Space Grotesk")
	$font_name = trim( explode( ',', $font_family )[0] );
	$font_url  = 'https://fonts.googleapis.com/css2?family=' . urlencode( $font_name ) . ':wght@300;400;500;600;700;800;900&display=swap';
	
	// Fetch Colors from canonical definitions
	$color_defs = mh_get_color_definitions();

	// Pre-calculate server circadian rhythm for initial SSR/no-FOUC state
	$now_hours            = (float) current_time( 'H' ) + ( (float) current_time( 'i' ) / 60 );
	$server_ratio         = ( cos( ( $now_hours - 12 ) * M_PI / 12 ) + 1 ) / 2;
	$server_is_day        = ( $server_ratio >= 0.5 );
	$server_phase_class   = $server_is_day ? 'phase-day' : 'phase-night';
	$server_phase_percent = $server_is_day 
		? number_format( ( $server_ratio - 0.5 ) * 200, 2, '.', '' ) . '%' 
		: number_format( $server_ratio * 200, 2, '.', '' ) . '%';
	$server_daylight      = number_format( $server_ratio * 100, 2, '.', '' ) . '%';
	?>
	<style type="text/css">
		@import url('<?php echo esc_url( $font_url ); ?>');

		:root, html[data-theme="light"] {
			--mh-font-family: <?php echo esc_attr( $font_family ); ?>;
			--mh-font-heading: <?php echo esc_attr( $font_family ); ?>;
			--mh-font-body: <?php echo esc_attr( $font_family ); ?>;
			--mh-font-size: <?php echo esc_attr( $font_size ); ?>px;
			--mh-line-height: <?php echo esc_attr( $line_height ); ?>;
			--mh-heading-weight: <?php echo esc_attr( $heading_weight ); ?>;
			--mh-heading-line-height: <?php echo esc_attr( $heading_lh ); ?>;
			
			--mh-space-base: <?php echo esc_attr( get_theme_mod( 'mh_space_base', '8' ) ); ?>px;
			--mh-space-1: calc(var(--mh-space-base) * 0.5);
			--mh-space-2: calc(var(--mh-space-base) * 1);
			--mh-space-3: calc(var(--mh-space-base) * 1.5);
			--mh-space-4: calc(var(--mh-space-base) * 2);
			--mh-space-5: calc(var(--mh-space-base) * 3);
			--mh-space-6: calc(var(--mh-space-base) * 4);
			--mh-space-7: calc(var(--mh-space-base) * 6);
			--mh-space-8: calc(var(--mh-space-base) * 8);
			--mh-content-width: <?php echo esc_attr( get_theme_mod( 'mh_content_width', '1200' ) ); ?>px;
			
			--mh-font-size-h1: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h1', 48 ) ); ?>px;
			--mh-font-size-h2: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h2', 36 ) ); ?>px;
			--mh-font-size-h3: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h3', 28 ) ); ?>px;
			--mh-font-size-h4: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h4', 24 ) ); ?>px;
			--mh-font-size-h5: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h5', 20 ) ); ?>px;
			--mh-font-size-h6: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h6', 16 ) ); ?>px;

			--mh-phase-primary: <?php echo esc_attr( $server_phase_percent ); ?>;
			--mh-daylight: <?php echo esc_attr( $server_daylight ); ?>;

			<?php foreach ( $color_defs as $key => $data ) : 
				$light_val = get_theme_mod( 'mh_color_' . str_replace( '-', '_', $key ), $data['light'] );
				$twi_val   = get_theme_mod( 'mh_color_' . str_replace( '-', '_', $key ) . '_twilight', $data['twilight'] );
				$dark_val  = get_theme_mod( 'mh_color_' . str_replace( '-', '_', $key ) . '_dark', $data['dark'] );
			?>
			--mh-color-<?php echo esc_attr( $key ); ?>-light: <?php echo esc_attr( $light_val ); ?>;
			--mh-color-<?php echo esc_attr( $key ); ?>-twi: <?php echo esc_attr( $twi_val ); ?>;
			--mh-color-<?php echo esc_attr( $key ); ?>-dark: <?php echo esc_attr( $dark_val ); ?>;
			
			/* Default fallback matches server-calculated circadian phase */
			<?php if ( $server_is_day ) : ?>
			--mh-color-<?php echo esc_attr( $key ); ?>: color-mix(in oklch, var(--mh-color-<?php echo esc_attr( $key ); ?>-light) var(--mh-phase-primary, <?php echo esc_attr( $server_phase_percent ); ?>), var(--mh-color-<?php echo esc_attr( $key ); ?>-twi));
			<?php else : ?>
			--mh-color-<?php echo esc_attr( $key ); ?>: color-mix(in oklch, var(--mh-color-<?php echo esc_attr( $key ); ?>-twi) var(--mh-phase-primary, <?php echo esc_attr( $server_phase_percent ); ?>), var(--mh-color-<?php echo esc_attr( $key ); ?>-dark));
			<?php endif; ?>

			/* Synchronize Gutenberg Core & FSE block color presets with Magic Hat tokens */
			--wp--preset--color--<?php echo esc_attr( $data['slug'] ); ?>: var(--mh-color-<?php echo esc_attr( $key ); ?>);
			<?php endforeach; ?>
			
			--mh-font-family: <?php echo esc_attr( $font_family ); ?>;
			--mh-border-radius: <?php echo esc_attr( get_theme_mod( 'mh_radius_base', '4' ) ); ?>px;
			--mh-border-width: <?php echo esc_attr( get_theme_mod( 'mh_border_width', '1' ) ); ?>px;
			--mh-btn-font-weight: <?php echo esc_attr( get_theme_mod( 'mh_button_font_weight', '600' ) ); ?>;
			--mh-btn-text-transform: <?php echo esc_attr( get_theme_mod( 'mh_button_text_transform', 'none' ) ); ?>;
			--mh-btn-letter-spacing: <?php echo esc_attr( get_theme_mod( 'mh_button_letter_spacing', '0' ) ); ?>px;
		}

		<?php 
		$schedule_mode = get_theme_mod( 'mh_color_schedule_mode', 'circadian' );
		$bg_mode       = get_theme_mod( 'mh_bg_mode', 'default' );

		if ( $schedule_mode === 'light' ) : ?>
		:root, html {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr( $key ); ?>: var(--mh-color-<?php echo esc_attr( $key ); ?>-light) !important;
			<?php endforeach; ?>
		}
		<?php elseif ( $schedule_mode === 'twilight' ) : ?>
		:root, html {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr( $key ); ?>: var(--mh-color-<?php echo esc_attr( $key ); ?>-twi) !important;
			<?php endforeach; ?>
		}
		<?php elseif ( $schedule_mode === 'dark' ) : ?>
		:root, html {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr( $key ); ?>: var(--mh-color-<?php echo esc_attr( $key ); ?>-dark) !important;
			<?php endforeach; ?>
		}
		<?php else : ?>
		:root.phase-day, html.phase-day, body.phase-day {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr( $key ); ?>: color-mix(in oklch, var(--mh-color-<?php echo esc_attr( $key ); ?>-light) var(--mh-phase-primary, 100%), var(--mh-color-<?php echo esc_attr( $key ); ?>-twi));
			<?php endforeach; ?>
		}
		
		:root.phase-night, html.phase-night, body.phase-night {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr( $key ); ?>: color-mix(in oklch, var(--mh-color-<?php echo esc_attr( $key ); ?>-twi) var(--mh-phase-primary, 100%), var(--mh-color-<?php echo esc_attr( $key ); ?>-dark));
			<?php endforeach; ?>
		}
		<?php endif; ?>

		/* Circadian Smooth Transitions */
		body,
		header,
		footer,
		main,
		section,
		.mh-header,
		.mh-front-page-hero,
		.mh-section,
		[data-mw-type] {
			transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease;
		}

		<?php if ( $bg_mode === 'solid' ) : ?>
		body {
			background-color: <?php echo esc_attr( get_theme_mod( 'mh_bg_solid_color', '#0a0b10' ) ); ?> !important;
		}
		<?php elseif ( $bg_mode === 'gradient' ) : ?>
		body {
			background: linear-gradient(135deg, <?php echo esc_attr( get_theme_mod( 'mh_bg_gradient_start', '#0f172a' ) ); ?>, <?php echo esc_attr( get_theme_mod( 'mh_bg_gradient_end', '#020617' ) ); ?>) !important;
			background-attachment: fixed !important;
		}
		<?php elseif ( $bg_mode === 'image' ) : ?>
		body {
			<?php $bg_img = get_theme_mod( 'mh_bg_image' ); ?>
			<?php if ( ! empty( $bg_img ) ) : ?>
			background-image: url('<?php echo esc_url( $bg_img ); ?>') !important;
			background-size: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_size', 'cover' ) ); ?> !important;
			background-position: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_position', 'center center' ) ); ?> !important;
			background-repeat: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_repeat', 'no-repeat' ) ); ?> !important;
			background-attachment: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_attachment', 'fixed' ) ); ?> !important;
			<?php endif; ?>
			background-color: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_bg_color', '#0a0b10' ) ); ?> !important;
		}
		<?php elseif ( $bg_mode === 'canvas' ) : ?>
		body {
			background-color: #05050d !important;
		}
		body.mh-has-ambient-canvas #page,
		body.mh-has-ambient-canvas header,
		body.mh-has-ambient-canvas main,
		body.mh-has-ambient-canvas footer,
		body.mh-has-ambient-canvas .wp-site-blocks,
		body.mh-has-ambient-canvas .site-content {
			position: relative;
			z-index: 1;
		}
		<?php else : ?>
		body {
			background-color: var(--mh-color-body);
		}
		<?php endif; ?>
		body {
			font-family: var(--mh-font-family);
			color: var(--mh-color-text-main);
		}
		h1, h2, h3, h4, h5, h6 {
			font-family: var(--mh-font-heading);
			font-weight: var(--mh-heading-weight);
			line-height: var(--mh-heading-line-height);
			color: var(--mh-color-text-heading);
			margin-top: 0;
			margin-bottom: var(--mh-space-3);
		}
		h1, .wp-block-heading h1, h1.wp-block-heading { font-size: var(--mh-font-size-h1); }
		h2, .wp-block-heading h2, h2.wp-block-heading { font-size: var(--mh-font-size-h2); }
		h3, .wp-block-heading h3, h3.wp-block-heading { font-size: var(--mh-font-size-h3); }
		h4, .wp-block-heading h4, h4.wp-block-heading { font-size: var(--mh-font-size-h4); }
		h5, .wp-block-heading h5, h5.wp-block-heading { font-size: var(--mh-font-size-h5); }
		h6, .wp-block-heading h6, h6.wp-block-heading { font-size: var(--mh-font-size-h6); }
		p, .wp-block-paragraph {
			font-size: var(--mh-font-size);
			line-height: var(--mh-line-height);
			color: var(--mh-color-text-main);
		}
		a {
			color: var(--mh-color-link);
		}
		.btn-primary { background: var(--mh-color-cta-base); color: var(--mh-color-text-inverse); }
		.btn-secondary { background: var(--mh-color-cta-muted); color: var(--mh-color-text-main); }
		
		/* Global Element Resets */
		button, input[type="submit"], input[type="reset"], input[type="button"] {
			font-family: var(--mh-font-body);
			border-radius: var(--mh-border-radius);
			border: var(--mh-border-width) solid transparent;
			padding: var(--mh-space-2) var(--mh-space-4);
			transition: all 0.2s;
			cursor: pointer;
		}
		
		input[type="text"], input[type="email"], input[type="password"], input[type="search"], input[type="number"], input[type="url"], input[type="tel"], textarea, select {
			width: 100%;
			padding: var(--mh-space-2) var(--mh-space-3);
			border: var(--mh-border-width) solid var(--mh-color-border-base);
			border-radius: var(--mh-border-radius);
			font-family: var(--mh-font-body);
			font-size: 1rem;
			background: var(--mh-color-main);
			color: var(--mh-color-text-main);
			transition: border-color 0.2s, box-shadow 0.2s;
			box-sizing: border-box;
			max-width: 100%;
		}
		
		input::placeholder, textarea::placeholder {
			color: var(--mh-color-text-muted);
			opacity: 0.7;
		}
		
		input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="number"]:focus, input[type="url"]:focus, input[type="tel"]:focus, textarea:focus, select:focus {
			outline: none;
			border-color: var(--mh-color-brand-base);
			box-shadow: 0 0 0 3px color-mix(in srgb, var(--mh-color-brand-base) 20%, transparent);
		}
		
		hr {
			border: 0;
			border-top: var(--mh-border-width) solid var(--mh-color-border-base);
			margin: var(--mh-space-5) 0;
		}
		
		/* Layout Utilities */
		.w-full { width: 100%; }
		.h-full { height: 100%; }
		.max-w-content { max-width: var(--mh-content-width); margin-left: auto; margin-right: auto; }
		.rounded { border-radius: var(--mh-border-radius); }
		.border { border-width: var(--mh-border-width); border-style: solid; border-color: var(--mh-color-border-muted); }
		
		/* Spacing Utilities (Tailwind Syntax) */
		<?php for ( $i = 1; $i <= 8; $i++ ) : ?>
		.p-<?php echo $i; ?> { padding: var(--mh-space-<?php echo $i; ?>); }
		.py-<?php echo $i; ?> { padding-top: var(--mh-space-<?php echo $i; ?>); padding-bottom: var(--mh-space-<?php echo $i; ?>); }
		.px-<?php echo $i; ?> { padding-left: var(--mh-space-<?php echo $i; ?>); padding-right: var(--mh-space-<?php echo $i; ?>); }
		.pt-<?php echo $i; ?> { padding-top: var(--mh-space-<?php echo $i; ?>); }
		.pr-<?php echo $i; ?> { padding-right: var(--mh-space-<?php echo $i; ?>); }
		.pb-<?php echo $i; ?> { padding-bottom: var(--mh-space-<?php echo $i; ?>); }
		.pl-<?php echo $i; ?> { padding-left: var(--mh-space-<?php echo $i; ?>); }
		
		.m-<?php echo $i; ?> { margin: var(--mh-space-<?php echo $i; ?>); }
		.my-<?php echo $i; ?> { margin-top: var(--mh-space-<?php echo $i; ?>); margin-bottom: var(--mh-space-<?php echo $i; ?>); }
		.mx-<?php echo $i; ?> { margin-left: var(--mh-space-<?php echo $i; ?>); margin-right: var(--mh-space-<?php echo $i; ?>); }
		.mt-<?php echo $i; ?> { margin-top: var(--mh-space-<?php echo $i; ?>); }
		.mr-<?php echo $i; ?> { margin-right: var(--mh-space-<?php echo $i; ?>); }
		.mb-<?php echo $i; ?> { margin-bottom: var(--mh-space-<?php echo $i; ?>); }
		.ml-<?php echo $i; ?> { margin-left: var(--mh-space-<?php echo $i; ?>); }
		
		.gap-<?php echo $i; ?> { gap: var(--mh-space-<?php echo $i; ?>); }
		<?php endfor; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'xophz_magic_hat_customizer_css' );
