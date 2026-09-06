<?php
/**
 * Site Colors Customizer Section Registration
 *
 * Implements accordion-grouped color controls inside General Settings panel.
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Site Colors section with accordion groups.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_site_colors_section( $wp_customize ) {
	// ==============================================
	// SECTION: Site Colors (Inside General Settings)
	// ==============================================
	$wp_customize->add_section( 'magic_hat_colors', array(
		'title'       => __( '🎨 Site Colors', 'xophz-magic-hat' ),
		'panel'       => 'magic_hat_general_settings',
		'priority'    => 30,
		'description' => __( 'Configure circadian dynamic color modes and customize palette tokens with collapsible accordion groups.', 'xophz-magic-hat' ),
	) );

	// Schedule & Mode Override Setting
	$wp_customize->add_setting( 'mh_color_schedule_mode', array(
		'default'           => 'circadian',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'mh_color_schedule_mode', array(
		'type'        => 'select',
		'section'     => 'magic_hat_colors',
		'priority'    => 5,
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

	$group_metadata = array(
		'Brand'             => array( 'title' => __( '🎨 Brand Colors', 'xophz-magic-hat' ), 'slug' => 'brand' ),
		'Action (CTA)'      => array( 'title' => __( '⚡ Action (CTA) Colors', 'xophz-magic-hat' ), 'slug' => 'action-cta' ),
		'Links'             => array( 'title' => __( '🔗 Link Colors', 'xophz-magic-hat' ), 'slug' => 'links' ),
		'Text'              => array( 'title' => __( '📝 Text & Typography Colors', 'xophz-magic-hat' ), 'slug' => 'text' ),
		'Surfaces & Layers' => array( 'title' => __( '🏢 Surfaces & Layers', 'xophz-magic-hat' ), 'slug' => 'surfaces' ),
		'Borders & Lines'   => array( 'title' => __( '🖼️ Borders & Lines', 'xophz-magic-hat' ), 'slug' => 'borders' ),
		'Status System'     => array( 'title' => __( '🚦 Status System', 'xophz-magic-hat' ), 'slug' => 'status' ),
	);

	$priority = 10;

	foreach ( $color_groups as $group_label => $settings ) {
		$meta       = isset( $group_metadata[ $group_label ] ) ? $group_metadata[ $group_label ] : array( 'title' => $group_label, 'slug' => sanitize_title( $group_label ) );
		$group_slug = $meta['slug'];
		$toggle_id  = 'mh_toggle_group_' . str_replace( '-', '_', $group_slug );

		// Accordion Group Header
		$wp_customize->add_setting( $toggle_id, array(
			'sanitize_callback' => 'sanitize_text_field',
		) );

		if ( class_exists( 'Magic_Hat_Accordion_Toggle_Control' ) ) {
			$wp_customize->add_control( new Magic_Hat_Accordion_Toggle_Control( $wp_customize, $toggle_id, array(
				'label'       => $meta['title'],
				'description' => count( $settings ) . ' tokens',
				'section'     => 'magic_hat_colors',
				'priority'    => $priority++,
				'group_id'    => $group_slug,
				'is_open'     => false,
			) ) );
		}

		foreach ( $settings as $id => $data ) {
			// Light Mode
			$wp_customize->add_setting( $id, array(
				'default'           => $data['light'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
				'label'       => $data['label'] . ' (Light)',
				'section'     => 'magic_hat_colors',
				'priority'    => $priority++,
				'input_attrs' => array( 'data-accordion-child' => $group_slug ),
			) ) );

			// Twilight Mode
			$twi_id = $id . '_twilight';
			$wp_customize->add_setting( $twi_id, array(
				'default'           => $data['twilight'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $twi_id, array(
				'label'       => $data['label'] . ' (Twilight)',
				'section'     => 'magic_hat_colors',
				'priority'    => $priority++,
				'input_attrs' => array( 'data-accordion-child' => $group_slug ),
			) ) );

			// Dark Mode
			$dark_id = $id . '_dark';
			$wp_customize->add_setting( $dark_id, array(
				'default'           => $data['dark'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $dark_id, array(
				'label'       => $data['label'] . ' (Dark)',
				'section'     => 'magic_hat_colors',
				'priority'    => $priority++,
				'input_attrs' => array( 'data-accordion-child' => $group_slug ),
			) ) );
		}
	}

	// Editor Settings Accordion Group
	$editor_toggle_id = 'mh_toggle_group_editor_settings';
	$wp_customize->add_setting( $editor_toggle_id, array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	if ( class_exists( 'Magic_Hat_Accordion_Toggle_Control' ) ) {
		$wp_customize->add_control( new Magic_Hat_Accordion_Toggle_Control( $wp_customize, $editor_toggle_id, array(
			'label'       => __( '⚙️ Editor Settings', 'xophz-magic-hat' ),
			'section'     => 'magic_hat_colors',
			'priority'    => $priority++,
			'group_id'    => 'editor-settings',
			'is_open'     => false,
		) ) );
	}

	$wp_customize->add_setting( 'mh_enforce_site_colors', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );

	$wp_customize->add_control( 'mh_enforce_site_colors', array(
		'type'        => 'checkbox',
		'section'     => 'magic_hat_colors',
		'label'       => __( 'Enforce Site Colors', 'xophz-magic-hat' ),
		'description' => __( 'When enabled, editors can only choose from the colors defined above.', 'xophz-magic-hat' ),
		'priority'    => $priority++,
		'input_attrs' => array( 'data-accordion-child' => 'editor-settings' ),
	) );
}

/**
 * Backward compatibility alias for legacy site colors panel caller.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_site_colors_panel( $wp_customize ) {
	mh_register_site_colors_section( $wp_customize );
}
