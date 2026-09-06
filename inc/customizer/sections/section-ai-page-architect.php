<?php
/**
 * AI Page Architect Customizer Section Registration
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register AI Page Architect section and control.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_ai_page_architect_section( $wp_customize ) {
	$wp_customize->add_section( 'mh_ai_page_architect', array(
		'title'       => __( '✨ AI Page Architect', 'xophz-magic-hat' ),
		'description' => __( 'Prompt Gemini or the internal layout synthesizer to conjure custom Gutenberg pages across diverse vibes and archetypes.', 'xophz-magic-hat' ),
		'priority'    => 10,
	) );

	$wp_customize->add_setting( 'mh_ai_generated_blocks', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new Magic_Hat_AI_Architect_Control( $wp_customize, 'mh_ai_generated_blocks', array(
		'section' => 'mh_ai_page_architect',
	) ) );
}
