<?php
/**
 * Typography Customizer Section Registration
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Typography section and controls.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_register_typography_section( $wp_customize ) {
	// ==============================================
	// SECTION: Typography
	// ==============================================
	$wp_customize->add_section( 'magic_hat_typography', array(
		'title'    => __( '🪶 Typography', 'xophz-magic-hat' ),
		'priority' => 50,
		'panel'    => 'magic_hat_general_settings',
	) );

	// Base Font Family
	$wp_customize->add_setting( 'mh_font_family', array(
		'default'           => 'Inter, sans-serif',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	
	$google_fonts = array(
		'Abril Fatface, display'     => 'Abril Fatface',
		'Acme, sans-serif'           => 'Acme',
		'Aleo, serif'                => 'Aleo',
		'Alfa Slab One, display'     => 'Alfa Slab One',
		'Amatic SC, cursive'         => 'Amatic SC',
		'Anton, sans-serif'          => 'Anton',
		'Archivo, sans-serif'        => 'Archivo',
		'Arimo, sans-serif'          => 'Arimo',
		'Asap, sans-serif'           => 'Asap',
		'Barlow, sans-serif'         => 'Barlow',
		'Bebas Neue, display'        => 'Bebas Neue',
		'Bitter, serif'              => 'Bitter',
		'Bree Serif, serif'          => 'Bree Serif',
		'Cabin, sans-serif'          => 'Cabin',
		'Cardo, serif'               => 'Cardo',
		'Catamaran, sans-serif'      => 'Catamaran',
		'Caveat, cursive'            => 'Caveat',
		'Comfortaa, cursive'         => 'Comfortaa',
		'Cormorant Garamond, serif'  => 'Cormorant Garamond',
		'Crimson Text, serif'        => 'Crimson Text',
		'DM Sans, sans-serif'        => 'DM Sans',
		'Dancing Script, cursive'    => 'Dancing Script',
		'Domine, serif'              => 'Domine',
		'Dosis, sans-serif'          => 'Dosis',
		'EB Garamond, serif'         => 'EB Garamond',
		'Exo 2, sans-serif'          => 'Exo 2',
		'Fira Code, monospace'       => 'Fira Code',
		'Fira Sans, sans-serif'      => 'Fira Sans',
		'Fjalla One, sans-serif'     => 'Fjalla One',
		'Fraunces, serif'            => 'Fraunces',
		'Heebo, sans-serif'          => 'Heebo',
		'Hind, sans-serif'           => 'Hind',
		'IBM Plex Mono, monospace'   => 'IBM Plex Mono',
		'IBM Plex Sans, sans-serif'  => 'IBM Plex Sans',
		'Inconsolata, monospace'     => 'Inconsolata',
		'Inter, sans-serif'          => 'Inter',
		'JetBrains Mono, monospace'  => 'JetBrains Mono',
		'Josefin Sans, sans-serif'   => 'Josefin Sans',
		'Jost, sans-serif'           => 'Jost',
		'Kanit, sans-serif'          => 'Kanit',
		'Karla, sans-serif'          => 'Karla',
		'Lato, sans-serif'           => 'Lato',
		'Libre Baskerville, serif'   => 'Libre Baskerville',
		'Lora, serif'                => 'Lora',
		'Manrope, sans-serif'        => 'Manrope',
		'Maven Pro, sans-serif'      => 'Maven Pro',
		'Merriweather, serif'        => 'Merriweather',
		'Merriweather Sans, sans-serif' => 'Merriweather Sans',
		'Montserrat, sans-serif'     => 'Montserrat',
		'Mukta, sans-serif'          => 'Mukta',
		'Mulish, sans-serif'         => 'Mulish',
		'Noto Sans, sans-serif'      => 'Noto Sans',
		'Noto Serif, serif'          => 'Noto Serif',
		'Nunito, sans-serif'         => 'Nunito',
		'Open Sans, sans-serif'      => 'Open Sans',
		'Oswald, sans-serif'         => 'Oswald',
		'Outfit, sans-serif'         => 'Outfit',
		'Oxygen, sans-serif'         => 'Oxygen',
		'PT Sans, sans-serif'        => 'PT Sans',
		'PT Serif, serif'            => 'PT Serif',
		'Pacifico, cursive'          => 'Pacifico',
		'Patua One, display'         => 'Patua One',
		'Playfair Display, serif'    => 'Playfair Display',
		'Poppins, sans-serif'        => 'Poppins',
		'Prompt, sans-serif'         => 'Prompt',
		'Public Sans, sans-serif'    => 'Public Sans',
		'Questrial, sans-serif'      => 'Questrial',
		'Quicksand, sans-serif'      => 'Quicksand',
		'Raleway, sans-serif'        => 'Raleway',
		'Righteous, display'         => 'Righteous',
		'Roboto, sans-serif'         => 'Roboto',
		'Roboto Condensed, sans-serif' => 'Roboto Condensed',
		'Roboto Mono, monospace'     => 'Roboto Mono',
		'Roboto Slab, serif'         => 'Roboto Slab',
		'Rokkitt, serif'             => 'Rokkitt',
		'Rubik, sans-serif'          => 'Rubik',
		'Shadows Into Light, cursive' => 'Shadows Into Light',
		'Signika, sans-serif'        => 'Signika',
		'Sora, sans-serif'           => 'Sora',
		'Source Code Pro, monospace' => 'Source Code Pro',
		'Space Grotesk, sans-serif'  => 'Space Grotesk',
		'Space Mono, monospace'      => 'Space Mono',
		'Spectral, serif'            => 'Spectral',
		'Syne, sans-serif'           => 'Syne',
		'Teko, sans-serif'           => 'Teko',
		'Titillium Web, sans-serif'  => 'Titillium Web',
		'Ubuntu, sans-serif'         => 'Ubuntu',
		'Varela Round, sans-serif'   => 'Varela Round',
		'Vollkorn, serif'            => 'Vollkorn',
		'Work Sans, sans-serif'      => 'Work Sans',
		'Zilla Slab, serif'          => 'Zilla Slab',
	);

	$wp_customize->add_control( new Magic_Hat_Font_Control( $wp_customize, 'mh_font_family', array(
		'label'    => __( 'Base Font Family', 'xophz-magic-hat' ),
		'section'  => 'magic_hat_typography',
		'choices'  => $google_fonts,
	) ) );

	// Base Font Size
	$wp_customize->add_setting( 'mh_font_size', array(
		'default'           => '16',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_font_size', array(
		'label'       => __( 'Base / Paragraph Font Size (px)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_typography',
		'input_attrs' => array( 'min' => 12, 'max' => 24, 'step' => 1 ),
	) ) );

	// Base Line Height
	$wp_customize->add_setting( 'mh_line_height', array(
		'default'           => '1.6',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_line_height', array(
		'label'       => __( 'Base Line Height', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_typography',
		'input_attrs' => array( 'min' => 1.0, 'max' => 2.5, 'step' => 0.1 ),
	) ) );

	// Heading Weight
	$wp_customize->add_setting( 'mh_heading_weight', array(
		'default'           => '600',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_heading_weight', array(
		'label'    => __( 'Heading Font Weight', 'xophz-magic-hat' ),
		'section'  => 'magic_hat_typography',
		'type'     => 'select',
		'choices'  => array(
			'300' => '300 - Light',
			'400' => '400 - Normal',
			'500' => '500 - Medium',
			'600' => '600 - Semi-Bold',
			'700' => '700 - Bold',
			'800' => '800 - Extra Bold',
			'900' => '900 - Black',
		),
	) );

	// Heading Line Height
	$wp_customize->add_setting( 'mh_heading_line_height', array(
		'default'           => '1.2',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_heading_line_height', array(
		'label'       => __( 'Heading Line Height', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_typography',
		'input_attrs' => array( 'min' => 0.8, 'max' => 2.0, 'step' => 0.05 ),
	) ) );

	// H1 - H6 Sizes
	$headings = array(
		'1' => 48,
		'2' => 36,
		'3' => 28,
		'4' => 24,
		'5' => 20,
		'6' => 16,
	);
	foreach ( $headings as $level => $default_size ) {
		$wp_customize->add_setting( 'mh_font_size_h' . $level, array(
			'default'           => $default_size,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_font_size_h' . $level, array(
			'label'       => sprintf( __( 'H%s Font Size (px)', 'xophz-magic-hat' ), $level ),
			'section'     => 'magic_hat_typography',
			'input_attrs' => array( 'min' => 10, 'max' => 120, 'step' => 1 ),
		) ) );
	}
}
