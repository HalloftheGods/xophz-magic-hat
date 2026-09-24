<?php
/**
 * Page Builder Customizer Section Registration
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Page Builder section and controls.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_page_builder_section( $wp_customize ) {
	$wp_customize->add_section( 'mh_page_builder', array(
		'title'       => __( '🪄 Page Settings', 'xophz-magic-hat' ),
		'priority'    => 60,
		'description' => __( 'Configure page layouts, sidebars, and modular page builder sections.', 'xophz-magic-hat' ),
	) );

	// ── Page Layout & Sidebar Settings (WordPress Industry Standard) ──
	$wp_customize->add_setting( 'mh_page_layout', array(
		'default'           => 'no_sidebar',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_page_layout', array(
		'label'       => __( 'Page Layout / Sidebar Layout', 'xophz-magic-hat' ),
		'description' => __( 'Select standard content and sidebar arrangement across pages.', 'xophz-magic-hat' ),
		'section'     => 'mh_page_builder',
		'type'        => 'select',
		'choices'     => array(
			'no_sidebar'    => __( 'Full Width (No Sidebar - Default)', 'xophz-magic-hat' ),
			'left_sidebar'  => __( 'Left Sidebar (Sidebar + Content)', 'xophz-magic-hat' ),
			'right_sidebar' => __( 'Right Sidebar (Content + Sidebar)', 'xophz-magic-hat' ),
			'three_column'  => __( '3-Column (Left Sidebar + Content + Right Sidebar)', 'xophz-magic-hat' ),
		),
		'priority'    => 10,
	) );

	// Sidebar Width (px)
	$wp_customize->add_setting( 'mh_sidebar_width', array(
		'default'           => 280,
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_sidebar_width', array(
		'label'       => __( 'Sidebar Width (Desktop px)', 'xophz-magic-hat' ),
		'description' => __( 'Width of desktop sidebars in pixels (200px to 380px).', 'xophz-magic-hat' ),
		'section'     => 'mh_page_builder',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 200,
			'max'  => 380,
			'step' => 10,
		),
		'priority'    => 15,
	) );

	// Setting to store JSON data for the page sections
	$wp_customize->add_setting( 'mh_page_sections', array(
		'default'           => '[]',
		'sanitize_callback' => function( $val ) {
			if ( is_array( $val ) ) {
				return wp_json_encode( $val );
			}
			$decoded = json_decode( $val, true );
			return ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) ? $val : '[]';
		},
		'transport'         => 'postMessage',
	) );
	
	$wp_customize->add_control( new Magic_Hat_Page_Builder_Control( $wp_customize, 'mh_page_sections', array(
		'section' => 'mh_page_builder',
	) ) );
}

/**
 * Synchronize page sections theme mod to front page post content on Customizer publish if plugin is absent.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function mh_sync_sections_on_save( $wp_customize ) {
	$has_magic_wand_public = class_exists( 'Xophz_Compass_Magic_Wand_Public' );
	if ( $has_magic_wand_public ) {
		return;
	}

	$setting = $wp_customize->get_setting( 'mh_page_sections' );
	$sections_json = '';
	if ( $setting ) {
		$sections_json = $setting->post_value();
		if ( empty( $sections_json ) ) {
			$sections_json = $setting->value();
		}
	}

	if ( empty( $sections_json ) ) {
		$sections_json = get_theme_mod( 'mh_page_sections', '[]' );
	}

	$sections = json_decode( $sections_json, true );
	$is_valid_sections_array = is_array( $sections );
	if ( ! $is_valid_sections_array || empty( $sections ) ) {
		return;
	}

	$front_page_id = absint( get_option( 'page_on_front' ) );
	if ( ! $front_page_id ) {
		$home_page = get_page_by_path( 'home' );
		if ( $home_page ) {
			$front_page_id = $home_page->ID;
		}
	}

	if ( ! $front_page_id ) {
		return;
	}

	update_post_meta( $front_page_id, '_mh_page_sections', wp_slash( $sections_json ) );
}
add_action( 'customize_save_after', 'mh_sync_sections_on_save' );
