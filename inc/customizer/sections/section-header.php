<?php
/**
 * Header Settings Customizer Section Registration
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Header Settings section and controls.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_header_section( $wp_customize ) {
	// Build navigation menus list for dropdown selector
	$all_nav_menus = wp_get_nav_menus();
	$menu_choices  = array(
		'_primary' => __( 'Follow Location: Primary Menu', 'xophz-magic-hat' ),
	);
	if ( ! empty( $all_nav_menus ) && ! is_wp_error( $all_nav_menus ) ) {
		foreach ( $all_nav_menus as $nav_m ) {
			$menu_choices[ $nav_m->term_id ] = $nav_m->name;
		}
	}

	// ==============================================
	// SECTION: Header Settings
	// ==============================================
	$wp_customize->add_section( 'magic_hat_header', array(
		'title'       => __( '🎩 Header Settings', 'xophz-magic-hat' ),
		'description' => __( 'Configure header layout, navigation menu, sticky behavior, and mobile drawer.', 'xophz-magic-hat' ),
		'priority'    => 10,
	) );

	$header_layouts = array(
		'standard'        => __( 'Standard Corporate (Logo Left, Nav Center, CTA)', 'xophz-magic-hat' ),
		'centered'        => __( 'Centered Showcase (Logo Top Center, Nav Below)', 'xophz-magic-hat' ),
		'split'           => __( 'Split Symmetrical (Left Nav, Center Logo, Right Nav)', 'xophz-magic-hat' ),
		'minimal'         => __( 'Minimal Mobile-First (Logo Left, Hamburger Right)', 'xophz-magic-hat' ),
		'floating_pill'   => __( 'Floating Glass Island (Suspended Pill Dock)', 'xophz-magic-hat' ),
		'stacked_utility' => __( 'Stacked Utility Bar (Announcement Strip + Nav Bar)', 'xophz-magic-hat' ),
		'inline_search'       => __( 'Inline Search & Commands (Logo, Search Bar, Nav)', 'xophz-magic-hat' ),
		'offcanvas_focus'     => __( 'Off-Canvas Focus (Logo Left, Menu Drawer Right)', 'xophz-magic-hat' ),
		'announcement_ticker' => __( 'Announcement Ticker (Notification Strip + Navbar)', 'xophz-magic-hat' ),
		'dual_cta'            => __( 'Dual Action / Converter (Ghost Link + Primary Button)', 'xophz-magic-hat' ),
	);

	// Header Layout
	$wp_customize->add_setting( 'mh_header_layout', array(
		'default'           => 'standard',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Layout_Picker_Control( $wp_customize, 'mh_header_layout', array(
		'label'       => __( 'Header Layout Style', 'xophz-magic-hat' ),
		'description' => __( 'Choose from 8 curated layout templates.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'layout_type' => 'header',
		'layouts'     => $header_layouts,
	) ) );

	// Brand Display (Logo Only, Logo + Text, Text Only)
	$wp_customize->add_setting( 'mh_header_brand_display', array(
		'default'           => 'both',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_brand_display', array(
		'label'       => __( 'Brand Display Style', 'xophz-magic-hat' ),
		'description' => __( 'Display logo only (no text) for cleaner branding, or include site title.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'select',
		'choices'     => array(
			'logo_only'  => __( 'Logo Only (No Site Title)', 'xophz-magic-hat' ),
			'both'       => __( 'Logo and Site Title', 'xophz-magic-hat' ),
			'title_only' => __( 'Site Title Only (No Logo)', 'xophz-magic-hat' ),
		),
	) );

	// Header Logo Max Height
	$wp_customize->add_setting( 'mh_header_logo_height', array(
		'default'           => 36,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_header_logo_height', array(
		'label'       => __( 'Logo Max Height', 'xophz-magic-hat' ),
		'description' => __( 'Adjust logo height to scale rectangular or square logos cleanly.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'input_attrs' => array(
			'min'  => 20,
			'max'  => 80,
			'step' => 1,
			'unit' => 'px',
		),
	) ) );

	// Header Navigation Menu
	$wp_customize->add_setting( 'mh_header_menu', array(
		'default'           => '_primary',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_menu', array(
		'label'       => __( 'Navigation Menu', 'xophz-magic-hat' ),
		'description' => __( 'Choose which WordPress menu to render in the header.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'select',
		'choices'     => $menu_choices,
	) );

	// Sticky Header
	$wp_customize->add_setting( 'mh_header_sticky', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_sticky', array(
		'label'       => __( 'Enable Sticky Header', 'xophz-magic-hat' ),
		'description' => __( 'Keep header fixed to top on scroll with subtle blur.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'checkbox',
	) );

	// Header Container Width
	$wp_customize->add_setting( 'mh_header_width', array(
		'default'           => 'contained',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_width', array(
		'label'       => __( 'Header Width', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'select',
		'choices'     => array(
			'contained' => __( 'Contained (1200px max)', 'xophz-magic-hat' ),
			'full'      => __( 'Full Width (100%)', 'xophz-magic-hat' ),
		),
	) );

	// Show CTA Button
	$wp_customize->add_setting( 'mh_header_show_cta', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_show_cta', array(
		'label'       => __( 'Show Action Button (CTA)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'checkbox',
	) );

	// CTA Button Text
	$wp_customize->add_setting( 'mh_header_cta_text', array(
		'default'           => __( 'Get Started', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_cta_text', array(
		'label'       => __( 'CTA Button Text', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'text',
	) );

	// CTA Button URL
	$wp_customize->add_setting( 'mh_header_cta_url', array(
		'default'           => '#contact',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_cta_url', array(
		'label'       => __( 'CTA Button URL', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'text',
	) );

	// Announcement Ticker Badge
	$wp_customize->add_setting( 'mh_header_ticker_badge', array(
		'default'           => __( 'NEW RELEASE', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_ticker_badge', array(
		'label'       => __( 'Ticker Badge Text', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'text',
	) );

	// Announcement Ticker Text
	$wp_customize->add_setting( 'mh_header_ticker_text', array(
		'default'           => __( 'Explore our latest quantum components and layouts.', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_ticker_text', array(
		'label'       => __( 'Ticker Announcement Text', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'text',
	) );

	// Announcement Ticker URL
	$wp_customize->add_setting( 'mh_header_ticker_url', array(
		'default'           => '#explore',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_ticker_url', array(
		'label'       => __( 'Ticker Target URL', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'text',
	) );

	// Dual Action: Secondary CTA Text
	$wp_customize->add_setting( 'mh_header_secondary_cta_text', array(
		'default'           => __( 'Sign In', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_secondary_cta_text', array(
		'label'       => __( 'Secondary CTA Text (Dual Action)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'text',
	) );

	// Dual Action: Secondary CTA URL
	$wp_customize->add_setting( 'mh_header_secondary_cta_url', array(
		'default'           => '#signin',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_secondary_cta_url', array(
		'label'       => __( 'Secondary CTA URL (Dual Action)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'text',
	) );
}
