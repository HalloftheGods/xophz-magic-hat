<?php
/**
 * Brand Settings Customizer Panel Registration
 *
 * Consolidates brand identity, homepage routing, site colors,
 * design system tokens, typography, and custom styling.
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Brand Settings parent panel.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_brand_settings_panel( $wp_customize ) {
	$wp_customize->add_panel( 'magic_hat_brand_settings', array(
		'title'       => __( '👁️ Brand Settings', 'xophz-magic-hat' ),
		'description' => __( 'Configure brand identity, site colors, design tokens, circadian rhythm, typography, and custom styles.', 'xophz-magic-hat' ),
		'priority'    => 20,
	) );
}

/**
 * Backward compatibility alias for legacy general settings panel caller.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_general_settings_panel( $wp_customize ) {
	mh_register_brand_settings_panel( $wp_customize );
}

/**
 * Backward compatibility alias for legacy site styles panel caller.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_site_styles_panel( $wp_customize ) {
	mh_register_brand_settings_panel( $wp_customize );
}
