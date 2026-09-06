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
		'priority'    => 70,
	) );

	// Header Layout
	$wp_customize->add_setting( 'mh_header_layout', array(
		'default'           => 'standard',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_layout', array(
		'label'       => __( 'Header Layout', 'xophz-magic-hat' ),
		'description' => __( 'Select the visual arrangement of logo, navigation, and actions.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'select',
		'choices'     => array(
			'standard' => __( 'Standard (Logo Left, Nav Center/Right, CTA)', 'xophz-magic-hat' ),
			'centered' => __( 'Centered (Logo Top Center, Nav Below)', 'xophz-magic-hat' ),
			'split'    => __( 'Split (Nav Left & Right, Logo Center)', 'xophz-magic-hat' ),
			'minimal'  => __( 'Minimal (Logo Left, CTA & Hamburger Right)', 'xophz-magic-hat' ),
		),
	) );

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
}
