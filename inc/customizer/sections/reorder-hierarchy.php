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
 * @param WP_Customize_Manager $wp_customize
 */
function mh_reorder_customizer_hierarchy( $wp_customize ) {
	if ( $wp_customize->get_section( 'title_tagline' ) ) {
		$wp_customize->get_section( 'title_tagline' )->title    = __( '🆔 Site Identity', 'xophz-magic-hat' );
		$wp_customize->get_section( 'title_tagline' )->priority = 20;
	}
	if ( $wp_customize->get_section( 'custom_css' ) ) {
		$wp_customize->get_section( 'custom_css' )->panel    = 'magic_hat_site_styles';
		$wp_customize->get_section( 'custom_css' )->priority = 60;
	}
	if ( $wp_customize->get_section( 'static_front_page' ) ) {
		$wp_customize->get_section( 'static_front_page' )->title    = __( '🏠 Homepage Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'static_front_page' )->priority = 40;
	}
	if ( $wp_customize->get_panel( 'woocommerce' ) ) {
		$wp_customize->get_panel( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'woocommerce' )->priority = 50;
	}
	if ( $wp_customize->get_section( 'woocommerce' ) ) {
		$wp_customize->get_section( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'woocommerce' )->priority = 50;
	}
	if ( $wp_customize->get_panel( 'nav_menus' ) ) {
		$wp_customize->get_panel( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'nav_menus' )->priority = 115;
	}
	if ( $wp_customize->get_section( 'nav_menus' ) ) {
		$wp_customize->get_section( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'nav_menus' )->priority = 115;
	}
}

/**
 * Late Customizer panel/section adjustments for WooCommerce and third-party plugins.
 * Hooked at priority 999.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function xophz_magic_hat_late_customizer_adjustments( $wp_customize ) {
	if ( $wp_customize->get_section( 'title_tagline' ) ) {
		$wp_customize->get_section( 'title_tagline' )->title    = __( '🆔 Site Identity', 'xophz-magic-hat' );
		$wp_customize->get_section( 'title_tagline' )->priority = 20;
	}
	if ( $wp_customize->get_section( 'static_front_page' ) ) {
		$wp_customize->get_section( 'static_front_page' )->title    = __( '🏠 Homepage Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'static_front_page' )->priority = 40;
	}
	if ( $wp_customize->get_panel( 'woocommerce' ) ) {
		$wp_customize->get_panel( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'woocommerce' )->priority = 50;
	}
	if ( $wp_customize->get_section( 'woocommerce' ) ) {
		$wp_customize->get_section( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'woocommerce' )->priority = 50;
	}
	if ( $wp_customize->get_section( 'custom_css' ) ) {
		$wp_customize->get_section( 'custom_css' )->panel    = 'magic_hat_site_styles';
		$wp_customize->get_section( 'custom_css' )->priority = 60;
	}
	if ( $wp_customize->get_panel( 'nav_menus' ) ) {
		$wp_customize->get_panel( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'nav_menus' )->priority = 115;
	}
	if ( $wp_customize->get_section( 'nav_menus' ) ) {
		$wp_customize->get_section( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'nav_menus' )->priority = 115;
	}
}
add_action( 'customize_register', 'xophz_magic_hat_late_customizer_adjustments', 999 );
