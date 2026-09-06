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
		'title'    => __( '🏗️ Page Settings', 'xophz-magic-hat' ),
		'priority' => 40,
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
