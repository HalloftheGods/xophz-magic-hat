<?php
/**
 * General Settings Customizer Panel Registration
 *
 * Consolidates site identity, homepage routing, site colors,
 * design system tokens, and custom styling.
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register General Settings parent panel.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_general_settings_panel( $wp_customize ) {
	$wp_customize->add_panel( 'magic_hat_general_settings', array(
		'title'       => __( '⚙️ General Settings', 'xophz-magic-hat' ),
		'description' => __( 'Configure site identity, homepage routing, design tokens, circadian colors, and custom styles.', 'xophz-magic-hat' ),
		'priority'    => 20,
	) );
}

/**
 * Backward compatibility alias for legacy site styles panel caller.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_site_styles_panel( $wp_customize ) {
	mh_register_general_settings_panel( $wp_customize );
}
