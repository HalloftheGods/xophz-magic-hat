<?php
/**
 * Site Background & Canvas Customizer Section Registration
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Site Background & Canvas section and contextual controls.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_background_section( $wp_customize ) {
	// ==============================================
	// SECTION: Site Background & Animated Canvases
	// ==============================================
	$wp_customize->add_section( 'magic_hat_background', array(
		'title'       => __( '🖼️ Site Background & Canvas', 'xophz-magic-hat' ),
		'description' => __( 'Configure your site canvas: choose standard daylight theme surface, solid color, gradient, custom image, or one of 21 interactive generative animated canvas backgrounds.', 'xophz-magic-hat' ),
		'priority'    => 40,
		'panel'       => 'magic_hat_general_settings',
	) );

	// Background Mode
	$wp_customize->add_setting( 'mh_bg_mode', array(
		'default'           => 'default',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'mh_bg_mode', array(
		'type'        => 'select',
		'section'     => 'magic_hat_background',
		'label'       => __( 'Background Mode', 'xophz-magic-hat' ),
		'choices'     => array(
			'default'  => __( 'Default Circadian Theme Surface', 'xophz-magic-hat' ),
			'solid'    => __( 'Solid Color', 'xophz-magic-hat' ),
			'gradient' => __( 'Linear Gradient', 'xophz-magic-hat' ),
			'image'    => __( 'Custom Background Image', 'xophz-magic-hat' ),
			'canvas'   => __( '✨ Animated Generative Canvas (21 Presets)', 'xophz-magic-hat' ),
		),
	) );

	// Solid Color (Only shown when mode is solid)
	$wp_customize->add_setting( 'mh_bg_solid_color', array(
		'default'           => '#0a0b10',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_solid_color', array(
		'label'           => __( 'Solid Background Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_solid',
	) ) );

	// Gradient Start & End (Only shown when mode is gradient)
	$wp_customize->add_setting( 'mh_bg_gradient_start', array(
		'default'           => '#0f172a',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_gradient_start', array(
		'label'           => __( 'Gradient Start Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_gradient',
	) ) );

	$wp_customize->add_setting( 'mh_bg_gradient_end', array(
		'default'           => '#020617',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_gradient_end', array(
		'label'           => __( 'Gradient End Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_gradient',
	) ) );

	// Background Image Controls (Only shown when mode is image)
	$wp_customize->add_setting( 'mh_bg_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mh_bg_image', array(
		'label'           => __( 'Background Image', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_image',
	) ) );

	$wp_customize->add_setting( 'mh_bg_image_size', array(
		'default'           => 'cover',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'mh_bg_image_size', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Image Sizing', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_image',
		'choices'         => array(
			'cover'   => __( 'Fit to Screen (Cover)', 'xophz-magic-hat' ),
			'contain' => __( 'Fit Inside Screen (Contain)', 'xophz-magic-hat' ),
			'auto'    => __( 'Original Size (Auto)', 'xophz-magic-hat' ),
		),
	) );

	$wp_customize->add_setting( 'mh_bg_image_repeat', array(
		'default'           => 'no-repeat',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'mh_bg_image_repeat', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Repeat / Tile', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_image',
		'choices'         => array(
			'no-repeat' => __( 'Do Not Repeat', 'xophz-magic-hat' ),
			'repeat'    => __( 'Tile Horizontally & Vertically', 'xophz-magic-hat' ),
			'repeat-x'  => __( 'Tile Horizontally', 'xophz-magic-hat' ),
			'repeat-y'  => __( 'Tile Vertically', 'xophz-magic-hat' ),
		),
	) );

	$wp_customize->add_setting( 'mh_bg_image_position', array(
		'default'           => 'center center',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'mh_bg_image_position', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Image Position', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_image',
		'choices'         => array(
			'center center' => __( 'Center Center', 'xophz-magic-hat' ),
			'top center'    => __( 'Top Center', 'xophz-magic-hat' ),
			'top left'      => __( 'Top Left', 'xophz-magic-hat' ),
			'top right'     => __( 'Top Right', 'xophz-magic-hat' ),
			'bottom center' => __( 'Bottom Center', 'xophz-magic-hat' ),
		),
	) );

	$wp_customize->add_setting( 'mh_bg_image_attachment', array(
		'default'           => 'fixed',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'mh_bg_image_attachment', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Scroll Behavior', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_image',
		'choices'         => array(
			'fixed'  => __( 'Fixed (Parallax)', 'xophz-magic-hat' ),
			'scroll' => __( 'Scroll with Page', 'xophz-magic-hat' ),
		),
	) );

	$wp_customize->add_setting( 'mh_bg_image_bg_color', array(
		'default'           => '#0a0b10',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_image_bg_color', array(
		'label'           => __( 'Underlying Background Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_image',
	) ) );

	// Canvas Preset (21 Presets - Only shown when mode is canvas)
	$wp_customize->add_setting( 'mh_bg_canvas_preset', array(
		'default'           => 'electric-wave',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_bg_canvas_preset', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Animated Canvas Preset', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_canvas',
		'choices'         => array(
			'electric-wave'     => __( '⚡ Electric Waves', 'xophz-magic-hat' ),
			'aurora-smoke'      => __( '🌌 Aurora Smoke', 'xophz-magic-hat' ),
			'celestial-cosmos'  => __( '✨ Celestial Cosmos', 'xophz-magic-hat' ),
			'quantum-particles' => __( '⚛️ Quantum Particles', 'xophz-magic-hat' ),
			'cyber-matrix'      => __( '💻 Cyber Matrix', 'xophz-magic-hat' ),
			'tesseract-4d'      => __( '🧊 Tesseract 4D Grid', 'xophz-magic-hat' ),
			'bubblegum'         => __( '🫧 Bubblegum Spheres', 'xophz-magic-hat' ),
			'alphabet-soup'     => __( '🍜 Alphabet Soup Noodles', 'xophz-magic-hat' ),
			'midnight-nerd'     => __( '🌆 Midnight Synthwave', 'xophz-magic-hat' ),
			'wormhole'          => __( '🌀 Wormhole Tunnel', 'xophz-magic-hat' ),
			'sun-corona'        => __( '☀️ Sun Corona', 'xophz-magic-hat' ),
			'saturn-rings'      => __( '🪐 Saturn Rings', 'xophz-magic-hat' ),
			'fluid-mesh'        => __( '💧 Fluid Ambient Mesh', 'xophz-magic-hat' ),
			'wizards-tower'     => __( '🧙 Wizards Tower Runes', 'xophz-magic-hat' ),
			'magic-formula'     => __( '🧪 Magic Formula Flask', 'xophz-magic-hat' ),
			'enchiridion'       => __( '📜 Enchiridion Neural Net', 'xophz-magic-hat' ),
			'omega-source'      => __( '💫 Omega Source Vortex', 'xophz-magic-hat' ),
			'telescope'         => __( '🔭 Telescope Deep Space', 'xophz-magic-hat' ),
			'logos'             => __( '💎 Logos Constellation', 'xophz-magic-hat' ),
			'nucleos'           => __( '🔬 Nucleos Atomic Orbits', 'xophz-magic-hat' ),
			'jupiter-gravity'   => __( '🪐 Jupiter Gravitational Lensing', 'xophz-magic-hat' ),
		),
	) );

	// Canvas Tint Color (Only shown when mode is canvas)
	$wp_customize->add_setting( 'mh_bg_canvas_color', array(
		'default'           => '#2563eb',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_canvas_color', array(
		'label'           => __( 'Canvas Accent / Tint Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_canvas',
	) ) );

	// Canvas Opacity (Only shown when mode is canvas)
	$wp_customize->add_setting( 'mh_bg_canvas_opacity', array(
		'default'           => '0.6',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_bg_canvas_opacity', array(
		'type'            => 'number',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Canvas Opacity (0.1 - 1.0)', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_canvas',
		'input_attrs'     => array( 'min' => '0.1', 'max' => '1.0', 'step' => '0.05' ),
	) );

	// Canvas Speed (Only shown when mode is canvas)
	$wp_customize->add_setting( 'mh_bg_canvas_speed', array(
		'default'           => '1.0',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_bg_canvas_speed', array(
		'type'            => 'number',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Animation Speed Multiplier (0.2 - 3.0)', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_canvas',
		'input_attrs'     => array( 'min' => '0.2', 'max' => '3.0', 'step' => '0.1' ),
	) );
}
