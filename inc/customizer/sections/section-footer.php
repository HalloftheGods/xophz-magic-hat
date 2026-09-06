<?php
/**
 * Footer Settings Customizer Section Registration & Render Callbacks
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Footer Settings section and controls.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_footer_section( $wp_customize ) {
	// ==============================================
	// SECTION: Footer Settings
	// ==============================================
	$wp_customize->add_section( 'magic_hat_footer', array(
		'title'       => __( '🦶 Footer Settings', 'xophz-magic-hat' ),
		'description' => __( 'Configure footer layout, background theme, navigation columns, and copyright.', 'xophz-magic-hat' ),
		'priority'    => 100,
	) );

	// Footer Layout
	$wp_customize->add_setting( 'mh_footer_layout', array(
		'default'           => 'columns_4',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_layout', array(
		'label'       => __( 'Footer Layout', 'xophz-magic-hat' ),
		'description' => __( 'Select column layout or clean minimal presentation.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'select',
		'choices'     => array(
			'columns_4'        => __( '4-Column Mega Footer (Brand + 4 Menu Cols)', 'xophz-magic-hat' ),
			'columns_3'        => __( '3-Column Footer (Brand + 2 Menu Cols)', 'xophz-magic-hat' ),
			'minimal_centered' => __( 'Centered Minimal (Logo, Inline Nav, Copyright)', 'xophz-magic-hat' ),
			'split'            => __( 'Split Modern (Brand Left, Menus/Social Right)', 'xophz-magic-hat' ),
		),
	) );

	// Footer Background Style
	$wp_customize->add_setting( 'mh_footer_bg', array(
		'default'           => 'surface_section',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_bg', array(
		'label'       => __( 'Footer Background', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'select',
		'choices'     => array(
			'surface_section' => __( 'Section Slate (#f8fafc)', 'xophz-magic-hat' ),
			'surface_white'   => __( 'Clean White (#ffffff)', 'xophz-magic-hat' ),
			'surface_dark'    => __( 'Deep Dark (#0f172a)', 'xophz-magic-hat' ),
		),
	) );

	// Show Footer Navigation Columns
	$wp_customize->add_setting( 'mh_footer_show_menus', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_show_menus', array(
		'label'       => __( 'Show Menu Columns', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'checkbox',
	) );

	// Copyright Text
	$wp_customize->add_setting( 'mh_footer_copyright_text', array(
		'default'           => '&copy; {year} {site_title}. All rights reserved.',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'mh_footer_copyright_text', array(
		'label'       => __( 'Copyright Text', 'xophz-magic-hat' ),
		'description' => __( 'Use {year} for current year and {site_title} for site name.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'textarea',
	) );

	// Social Links
	$social_networks = array(
		'facebook'  => 'Facebook',
		'twitter'   => 'Twitter (X)',
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'github'    => 'GitHub',
	);

	foreach ( $social_networks as $key => $label ) {
		$wp_customize->add_setting( 'mh_social_' . $key, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'mh_social_' . $key, array(
			'label'       => sprintf( __( '%s URL', 'xophz-magic-hat' ), $label ),
			'section'     => 'magic_hat_footer',
			'type'        => 'url',
		) );
	}

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Selective Refresh Partials for Header and Footer
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->remove_partial( 'site_icon' );

		// Header Partial
		$wp_customize->selective_refresh->add_partial( 'mh_header_partial', array(
			'selector'            => '#mw-header',
			'settings'            => array(
				'mh_header_layout',
				'mh_header_menu',
				'mh_header_sticky',
				'mh_header_width',
				'mh_header_show_cta',
				'mh_header_cta_text',
				'mh_header_cta_url',
				'blogname',
			),
			'container_inclusive' => true,
			'render_callback'     => 'mh_render_header_markup',
		) );

		// Footer Partial
		$wp_customize->selective_refresh->add_partial( 'mh_footer_partial', array(
			'selector'            => '#mw-footer',
			'settings'            => array(
				'mh_footer_layout',
				'mh_footer_bg',
				'mh_footer_show_menus',
				'mh_footer_copyright_text',
				'mh_social_facebook',
				'mh_social_twitter',
				'mh_social_instagram',
				'mh_social_linkedin',
				'mh_social_youtube',
				'mh_social_github',
				'blogname',
				'blogdescription',
			),
			'container_inclusive' => true,
			'render_callback'     => 'mh_render_footer_markup',
		) );
	}
}

/**
 * Render footer brand logo and site title in selective refresh
 */
function mh_render_footer_brand() {
	?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 12px; margin-bottom: 12px;">
		<?php if ( has_site_icon() ) : ?>
			<img src="<?php echo esc_url( get_site_icon_url( 256 ) ); ?>" alt="Logo" style="height: 128px; width: 128px; object-fit: contain; filter: drop-shadow(0 0 6px rgba(98,201,255,0.4)); opacity: 0.8; border-radius: 12px;" />
		<?php else : ?>
			<img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" style="height: 128px; width: 128px; object-fit: contain; filter: drop-shadow(0 0 6px rgba(98,201,255,0.4)); opacity: 0.8;" />
		<?php endif; ?>
		<span class="mh-footer-site-name" style="font-size: 18px; font-weight: 700; color: rgba(255,255,255,0.9); font-family: var(--mh-font-heading, sans-serif);"><?php bloginfo( 'name' ); ?></span>
	</a>
	<p class="mh-footer-tagline" style="font-size: 14px; line-height: 1.6; max-width: 250px; margin: 0;"><?php bloginfo( 'description' ); ?></p>
	<?php
}

/**
 * Render footer bottom copyright and social links in selective refresh
 */
function mh_render_footer_bottom() {
	$default_copyright = '&copy; {year} {site_title}. All rights reserved.';
	$copyright_text    = get_theme_mod( 'mh_footer_copyright_text', $default_copyright );
	$copyright_text    = str_replace(
		array( '{year}', '{site_title}' ),
		array( date( 'Y' ), get_bloginfo( 'name' ) ),
		$copyright_text
	);
	
	echo '<div class="mh-footer-copyright-text">';
	echo wp_kses_post( $copyright_text );
	echo '</div>';

	$social_networks = array(
		'facebook'  => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
		'twitter'   => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16h4.267l-11.733 -16z"></path><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path></svg>',
		'instagram' => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
		'linkedin'  => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
		'youtube'   => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
		'github'    => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>',
	);
	
	$has_social = false;
	ob_start();
	echo '<div class="mh-social-links" style="display: flex; gap: 15px; align-items: center;">';
	foreach ( $social_networks as $key => $svg ) {
		$url = get_theme_mod( 'mh_social_' . $key );
		if ( ! empty( $url ) ) {
			$has_social = true;
			echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color=\'#62c9ff\'" onmouseout="this.style.color=\'rgba(255,255,255,0.6)\'" aria-label="' . esc_attr( ucfirst( $key ) ) . '">' . $svg . '</a>';
		}
	}
	echo '</div>';
	$social_html = ob_get_clean();
	
	echo '<div class="mh-social-links-wrapper">';
	if ( $has_social ) {
		echo $social_html;
	}
	echo '</div>';
}
