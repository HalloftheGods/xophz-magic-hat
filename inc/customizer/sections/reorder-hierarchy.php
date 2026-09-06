<?php
/**
 * Customizer Core Hierarchy Reordering & Late Adjustments
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reorder core sections and panels to align with the design hierarchy.
 *
 * Root sequence:
 * 1. Header (10)
 * 2. Menu Settings (15)
 * 3. General Settings (20)
 * 4. Shop Settings (30)
 * 5. Hero (40)
 * 6. Page Settings / Page AI (50 / 55)
 * 7. Footer (70)
 *
 * @param WP_Customize_Manager $wp_customize
 */
function mh_reorder_customizer_hierarchy( $wp_customize ) {
	// ── 1. Root Level Sections & Panels ───────────────────────────
	if ( $wp_customize->get_section( 'magic_hat_header' ) ) {
		$wp_customize->get_section( 'magic_hat_header' )->priority = 10;
	}

	if ( $wp_customize->get_panel( 'nav_menus' ) ) {
		$wp_customize->get_panel( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'nav_menus' )->priority = 15;
	}
	if ( $wp_customize->get_section( 'nav_menus' ) ) {
		$wp_customize->get_section( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'nav_menus' )->priority = 15;
	}

	if ( $wp_customize->get_panel( 'magic_hat_general_settings' ) ) {
		$wp_customize->get_panel( 'magic_hat_general_settings' )->priority = 20;
	} elseif ( $wp_customize->get_panel( 'magic_hat_site_styles' ) ) {
		$wp_customize->get_panel( 'magic_hat_site_styles' )->priority = 20;
	}

	if ( $wp_customize->get_panel( 'woocommerce' ) ) {
		$wp_customize->get_panel( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'woocommerce' )->priority = 30;
	}
	if ( $wp_customize->get_section( 'woocommerce' ) ) {
		$wp_customize->get_section( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'woocommerce' )->priority = 30;
	}

	if ( $wp_customize->get_section( 'mh_front_page_hero' ) ) {
		$wp_customize->get_section( 'mh_front_page_hero' )->priority = 40;
	}

	if ( $wp_customize->get_section( 'mh_page_builder' ) ) {
		$wp_customize->get_section( 'mh_page_builder' )->title    = __( '🏗️ Page Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'mh_page_builder' )->priority = 50;
	}

	if ( $wp_customize->get_section( 'mh_ai_page_architect' ) ) {
		$wp_customize->get_section( 'mh_ai_page_architect' )->priority = 55;
	}

	if ( $wp_customize->get_section( 'magic_hat_footer' ) ) {
		$wp_customize->get_section( 'magic_hat_footer' )->priority = 70;
	}

	// ── 2. General Settings Panel Child Sections ──────────────────
	$target_panel = $wp_customize->get_panel( 'magic_hat_general_settings' ) ? 'magic_hat_general_settings' : 'magic_hat_site_styles';

	if ( $wp_customize->get_section( 'title_tagline' ) ) {
		$wp_customize->get_section( 'title_tagline' )->title    = __( '🆔 Site Identity', 'xophz-magic-hat' );
		$wp_customize->get_section( 'title_tagline' )->panel    = $target_panel;
		$wp_customize->get_section( 'title_tagline' )->priority = 10;
	}

	if ( $wp_customize->get_section( 'static_front_page' ) ) {
		$wp_customize->get_section( 'static_front_page' )->title    = __( '🏠 Homepage Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'static_front_page' )->panel    = $target_panel;
		$wp_customize->get_section( 'static_front_page' )->priority = 20;
	}

	if ( $wp_customize->get_section( 'magic_hat_colors' ) ) {
		$wp_customize->get_section( 'magic_hat_colors' )->panel    = $target_panel;
		$wp_customize->get_section( 'magic_hat_colors' )->priority = 30;
	}

	if ( $wp_customize->get_section( 'magic_hat_background' ) ) {
		$wp_customize->get_section( 'magic_hat_background' )->panel    = $target_panel;
		$wp_customize->get_section( 'magic_hat_background' )->priority = 40;
	}

	if ( $wp_customize->get_section( 'magic_hat_typography' ) ) {
		$wp_customize->get_section( 'magic_hat_typography' )->panel    = $target_panel;
		$wp_customize->get_section( 'magic_hat_typography' )->priority = 50;
	}

	if ( $wp_customize->get_section( 'magic_hat_spacing' ) ) {
		$wp_customize->get_section( 'magic_hat_spacing' )->panel    = $target_panel;
		$wp_customize->get_section( 'magic_hat_spacing' )->priority = 60;
	}

	if ( $wp_customize->get_section( 'magic_hat_buttons' ) ) {
		$wp_customize->get_section( 'magic_hat_buttons' )->panel    = $target_panel;
		$wp_customize->get_section( 'magic_hat_buttons' )->priority = 70;
	}

	if ( $wp_customize->get_section( 'custom_css' ) ) {
		$wp_customize->get_section( 'custom_css' )->panel    = $target_panel;
		$wp_customize->get_section( 'custom_css' )->priority = 80;
	}
}

/**
 * Late Customizer panel/section adjustments for WooCommerce and third-party plugins.
 * Hooked at priority 999.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function xophz_magic_hat_late_customizer_adjustments( $wp_customize ) {
	mh_reorder_customizer_hierarchy( $wp_customize );
}
add_action( 'customize_register', 'xophz_magic_hat_late_customizer_adjustments', 999 );
