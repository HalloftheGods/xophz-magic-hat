<?php
/**
 * Spacing & Layout Customizer Section Registration
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Spacing & Layout section and controls.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_spacing_section( $wp_customize ) {
	// ==============================================
	// SECTION: Spacing & Layout
	// ==============================================
	$wp_customize->add_section( 'magic_hat_spacing', array(
		'title'    => __( '📏 Spacing & Layout', 'xophz-magic-hat' ),
		'priority' => 30,
		'panel'    => 'magic_hat_site_styles',
	) );
	
	$wp_customize->add_setting( 'mh_space_base', array(
		'default'           => '8',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_space_base', array(
		'label'       => __( 'Base Spacing Unit (px)', 'xophz-magic-hat' ),
		'description' => __( 'This is the master unit. All spacing scales proportionally from this base value.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_spacing',
		'input_attrs' => array( 'min' => 2, 'max' => 24, 'step' => 1 ),
	) ) );
	
	$wp_customize->add_setting( 'mh_content_width', array(
		'default'           => '1200',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_content_width', array(
		'label'       => __( 'Max Content Width (px)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_spacing',
		'input_attrs' => array( 'min' => 600, 'max' => 2400, 'step' => 10 ),
	) ) );

	$wp_customize->add_setting( 'mh_radius_base', array(
		'default'           => '4',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_radius_base', array(
		'label'       => __( 'Base Border Radius (px)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_spacing',
		'input_attrs' => array( 'min' => 0, 'max' => 50, 'step' => 1 ),
	) ) );

	$wp_customize->add_setting( 'mh_border_width', array(
		'default'           => '1',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_border_width', array(
		'label'       => __( 'Global Border Width (px)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_spacing',
		'input_attrs' => array( 'min' => 0, 'max' => 10, 'step' => 1 ),
	) ) );
}
