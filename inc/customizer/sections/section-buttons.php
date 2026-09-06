<?php
/**
 * Buttons Customizer Section Registration
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Buttons section and controls.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_buttons_section( $wp_customize ) {
	// ==============================================
	// SECTION: Buttons
	// ==============================================
	$wp_customize->add_section( 'magic_hat_buttons', array(
		'title'    => __( '👆 Buttons', 'xophz-magic-hat' ),
		'priority' => 70,
		'panel'    => 'magic_hat_general_settings',
	) );

	$wp_customize->add_setting( 'mh_button_font_weight', array( 'default' => '600' ) );
	$wp_customize->add_control( 'mh_button_font_weight', array(
		'label'       => __( 'Font Weight', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'type'        => 'select',
		'choices'     => array(
			'300' => '300 - Light',
			'400' => '400 - Normal',
			'500' => '500 - Medium',
			'600' => '600 - Semi-Bold',
			'700' => '700 - Bold',
			'800' => '800 - Extra Bold',
			'900' => '900 - Black',
		),
	) );

	$wp_customize->add_setting( 'mh_button_text_transform', array( 'default' => 'none' ) );
	$wp_customize->add_control( 'mh_button_text_transform', array(
		'label'       => __( 'Text Transform', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'type'        => 'select',
		'choices'     => array(
			'none'       => 'None',
			'uppercase'  => 'UPPERCASE',
			'lowercase'  => 'lowercase',
			'capitalize' => 'Capitalize',
		),
	) );

	$wp_customize->add_setting( 'mh_button_letter_spacing', array( 'default' => '0' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_button_letter_spacing', array(
		'label'       => __( 'Letter Spacing', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'input_attrs' => array( 'min' => -5, 'max' => 10, 'step' => 0.1, 'unit' => 'px' ),
	) ) );
}
