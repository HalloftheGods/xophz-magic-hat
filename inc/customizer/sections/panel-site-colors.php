<?php
/**
 * Site Colors Customizer Panel Registration
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Site Colors panel and sub-sections (schedule, color groups, editor settings).
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_site_colors_panel( $wp_customize ) {
	// ==============================================
	// PANEL: Site Colors (Dedicated Panel with Sub-Sections)
	// ==============================================
	$wp_customize->add_panel( 'magic_hat_colors_panel', array(
		'title'       => __( '🎨 Site Colors', 'xophz-magic-hat' ),
		'description' => __( 'Customize your core design system colors and circadian schedule behavior.', 'xophz-magic-hat' ),
		'priority'    => 50,
	) );

	// Schedule & Mode Override Section
	$wp_customize->add_section( 'mh_colors_schedule', array(
		'title'    => __( '⏱️ Day / Night Schedule & Color Mode', 'xophz-magic-hat' ),
		'panel'    => 'magic_hat_colors_panel',
		'priority' => 1,
	) );

	$wp_customize->add_setting( 'mh_color_schedule_mode', array(
		'default'           => 'circadian',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'mh_color_schedule_mode', array(
		'type'        => 'select',
		'section'     => 'mh_colors_schedule',
		'label'       => __( 'Color Schedule Behavior', 'xophz-magic-hat' ),
		'description' => __( 'Select whether theme colors dynamically shift with the 24-hour astronomical circadian clock or stay permanently locked to Light, Twilight, or Dark.', 'xophz-magic-hat' ),
		'choices'     => array(
			'circadian' => __( '🌅 Circadian Rhythm (Dynamic 24h astronomical sync)', 'xophz-magic-hat' ),
			'light'     => __( '☀️ Always Light (Static 24/7)', 'xophz-magic-hat' ),
			'twilight'  => __( '🌅 Always Twilight (Static 24/7)', 'xophz-magic-hat' ),
			'dark'      => __( '🌙 Always Dark (Static 24/7)', 'xophz-magic-hat' ),
		),
	) );

	// Group the canonical definitions for Customizer sections
	$color_defs   = mh_get_color_definitions();
	$color_groups = array();
	foreach ( $color_defs as $key => $data ) {
		$group      = $data['group'];
		$setting_id = 'mh_color_' . str_replace( '-', '_', $key );
		if ( ! isset( $color_groups[ $group ] ) ) {
			$color_groups[ $group ] = array();
		}
		$color_groups[ $group ][ $setting_id ] = $data;
	}

	$div_count = 0;
	foreach ( $color_groups as $group_label => $settings ) {
		$div_count++;
		$section_id = 'mh_colors_' . sanitize_title( $group_label );
		
		$wp_customize->add_section( $section_id, array(
			'title'    => $group_label,
			'panel'    => 'magic_hat_colors_panel',
			'priority' => 10 + $div_count,
		) );

		foreach ( $settings as $id => $data ) {
			// Light Mode
			$wp_customize->add_setting( $id, array( 'default' => $data['light'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
				'label'   => $data['label'] . ' (Light)',
				'section' => $section_id,
			) ) );

			// Twilight Mode
			$twi_id = $id . '_twilight';
			$wp_customize->add_setting( $twi_id, array( 'default' => $data['twilight'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $twi_id, array(
				'label'   => $data['label'] . ' (Twilight)',
				'section' => $section_id,
			) ) );

			// Dark Mode
			$dark_id = $id . '_dark';
			$wp_customize->add_setting( $dark_id, array( 'default' => $data['dark'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $dark_id, array(
				'label'   => $data['label'] . ' (Dark)',
				'section' => $section_id,
			) ) );
		}
	}

	// ==============================================
	// SECTION: Editor Settings
	// ==============================================
	$wp_customize->add_section( 'mh_colors_editor_settings', array(
		'title'    => __( 'Editor Settings', 'xophz-magic-hat' ),
		'panel'    => 'magic_hat_colors_panel',
		'priority' => 99,
	) );
	
	$wp_customize->add_setting( 'mh_enforce_site_colors', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	
	$wp_customize->add_control( 'mh_enforce_site_colors', array(
		'type'        => 'checkbox',
		'section'     => 'mh_colors_editor_settings',
		'label'       => __( 'Enforce Site Colors', 'xophz-magic-hat' ),
		'description' => __( 'When enabled, editors can only choose from the colors defined above.', 'xophz-magic-hat' ),
	) );
}
