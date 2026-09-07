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

	$footer_layouts = array(
		'columns_4'        => __( '4-Column Mega Footer (Brand + 4 Menu Cols)', 'xophz-magic-hat' ),
		'columns_3'        => __( '3-Column Balanced Footer (Brand + 2 Menu Cols)', 'xophz-magic-hat' ),
		'minimal_centered' => __( 'Centered Minimal (Logo, Inline Nav, Copyright)', 'xophz-magic-hat' ),
		'split'            => __( 'Split Modern (Brand Left, Menus/Social Right)', 'xophz-magic-hat' ),
		'bento'            => __( 'Bento Grid Footer (Asymmetrical Cards + Status)', 'xophz-magic-hat' ),
		'big_statement'    => __( 'Big Statement CTA (Giant Headline + Action Bar)', 'xophz-magic-hat' ),
		'newsletter_first' => __( 'Newsletter Lead-In (Full Banner + 3 Menu Cols)', 'xophz-magic-hat' ),
		'floating_dock'    => __( 'Floating Dock (Compact Single-Row Horizontal Dock)', 'xophz-magic-hat' ),
		'sitemap_dense'    => __( 'Site Directory / Sitemap (5 Structured Cols + Status)', 'xophz-magic-hat' ),
		'social_hub'       => __( 'Creator & Community Social Hub (Interactive Cards)', 'xophz-magic-hat' ),
	);

	// Footer Layout
	$wp_customize->add_setting( 'mh_footer_layout', array(
		'default'           => 'columns_4',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Layout_Picker_Control( $wp_customize, 'mh_footer_layout', array(
		'label'       => __( 'Footer Layout Style', 'xophz-magic-hat' ),
		'description' => __( 'Choose from 8 curated layout templates.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'layout_type' => 'footer',
		'layouts'     => $footer_layouts,
	) ) );

	// Footer Brand Display
	$wp_customize->add_setting( 'mh_footer_brand_display', array(
		'default'           => 'both',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_brand_display', array(
		'label'       => __( 'Footer Brand Display', 'xophz-magic-hat' ),
		'description' => __( 'Choose whether to display your logo graphic, text site title, or both.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'select',
		'choices'     => array(
			'both'       => __( 'Logo and Site Title', 'xophz-magic-hat' ),
			'logo_only'  => __( 'Logo Only (No Site Title)', 'xophz-magic-hat' ),
			'title_only' => __( 'Site Title Only (No Logo)', 'xophz-magic-hat' ),
			'none'       => __( 'Hide Brand Identity', 'xophz-magic-hat' ),
		),
	) );

	// Footer Logo Max Height
	$wp_customize->add_setting( 'mh_footer_logo_height', array(
		'default'           => 40,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_footer_logo_height', array(
		'label'       => __( 'Footer Logo Height', 'xophz-magic-hat' ),
		'description' => __( 'Adjust footer logo scale cleanly.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'input_attrs' => array(
			'min'  => 24,
			'max'  => 100,
			'step' => 1,
			'unit' => 'px',
		),
	) ) );

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

	// Big Statement CTA: Badge Text
	$wp_customize->add_setting( 'mh_footer_statement_badge', array(
		'default'           => __( 'Next Steps', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_statement_badge', array(
		'label'       => __( 'Statement Badge Text', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'text',
	) );

	// Big Statement CTA: Headline
	$wp_customize->add_setting( 'mh_footer_statement_title', array(
		'default'           => __( "Let's build something remarkable together.", 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_statement_title', array(
		'label'       => __( 'Statement Headline', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'text',
	) );

	// Big Statement CTA: Button Text
	$wp_customize->add_setting( 'mh_footer_statement_cta_text', array(
		'default'           => __( 'Get Started', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_statement_cta_text', array(
		'label'       => __( 'Statement Action Button Text', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'text',
	) );

	// Big Statement CTA: Button URL
	$wp_customize->add_setting( 'mh_footer_statement_cta_url', array(
		'default'           => '#contact',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_statement_cta_url', array(
		'label'       => __( 'Statement Action Button URL', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'text',
	) );

	// Newsletter Lead-In: Title
	$wp_customize->add_setting( 'mh_footer_newsletter_title', array(
		'default'           => __( 'Subscribe to our updates', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_newsletter_title', array(
		'label'       => __( 'Newsletter Banner Title', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'text',
	) );

	// Newsletter Lead-In: Description
	$wp_customize->add_setting( 'mh_footer_newsletter_desc', array(
		'default'           => __( 'Get the latest releases, design inspiration, and news directly to your inbox.', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_newsletter_desc', array(
		'label'       => __( 'Newsletter Banner Description', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'text',
	) );

	// Newsletter Lead-In: Button Text
	$wp_customize->add_setting( 'mh_footer_newsletter_btn_text', array(
		'default'           => __( 'Join', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_newsletter_btn_text', array(
		'label'       => __( 'Newsletter Submit Button Text', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'text',
	) );

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
				'mh_header_brand_display',
				'mh_header_logo_height',
				'mh_header_menu',
				'mh_header_sticky',
				'mh_header_width',
				'mh_header_show_cta',
				'mh_header_cta_text',
				'mh_header_cta_url',
				'mh_header_ticker_badge',
				'mh_header_ticker_text',
				'mh_header_ticker_url',
				'mh_header_secondary_cta_text',
				'mh_header_secondary_cta_url',
				'blogname',
				'custom_logo',
			),
			'container_inclusive' => true,
			'render_callback'     => 'mh_render_header_markup',
		) );

		// Footer Partial
		$wp_customize->selective_refresh->add_partial( 'mh_footer_partial', array(
			'selector'            => '#mw-footer',
			'settings'            => array(
				'mh_footer_layout',
				'mh_footer_brand_display',
				'mh_footer_logo_height',
				'mh_footer_bg',
				'mh_footer_show_menus',
				'mh_footer_copyright_text',
				'mh_footer_statement_badge',
				'mh_footer_statement_title',
				'mh_footer_statement_cta_text',
				'mh_footer_statement_cta_url',
				'mh_footer_newsletter_title',
				'mh_footer_newsletter_desc',
				'mh_footer_newsletter_btn_text',
				'mh_social_facebook',
				'mh_social_twitter',
				'mh_social_instagram',
				'mh_social_linkedin',
				'mh_social_youtube',
				'mh_social_github',
				'blogname',
				'blogdescription',
				'custom_logo',
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
	mh_render_brand_logo( 'footer' );
	if ( get_bloginfo( 'description' ) ) {
		echo '<p class="mh-footer-tagline" style="font-size: 14px; line-height: 1.6; max-width: 250px; margin: 0;">' . esc_html( get_bloginfo( 'description' ) ) . '</p>';
	}
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
