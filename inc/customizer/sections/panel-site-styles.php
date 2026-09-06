<?php
/**
 * Site Styles Customizer Panel Registration
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Site Styles parent panel.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_site_styles_panel( $wp_customize ) {
	$wp_customize->add_panel( 'magic_hat_site_styles', array(
		'title'       => __( '🧑‍🎨 Site Styles', 'xophz-magic-hat' ),
		'description' => __( 'Manage global design system styling: background canvas, colors, typography, spacing, buttons, and custom CSS.', 'xophz-magic-hat' ),
		'priority'    => 55,
	) );
}
