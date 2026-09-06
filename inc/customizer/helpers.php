<?php
/**
 * Customizer Helper Functions & Canonical Definitions
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if the current Customizer preview is the Stylebook.
 *
 * @return bool
 */
function mh_is_stylebook_preview() {
	return ( isset( $_GET['magic_hat_stylebook'] ) && $_GET['magic_hat_stylebook'] == '1' );
}

/**
 * Canonical 28-token Design Token Palette definitions for Light (Day), Twilight, and Dark (Night) modes.
 * Single Source of Truth for Customizer, Circadian Rhythm Engine, and Gutenberg Palette.
 *
 * @return array
 */
function mh_get_color_definitions() {
	return array(
		// Brand
		'brand-base'    => array( 'group' => 'Brand', 'label' => 'Base',          'name' => 'Brand Base',   'slug' => 'brand-base',   'light' => '#2563eb', 'twilight' => '#38bdf8', 'dark' => '#62c9ff' ),
		'brand-hover'   => array( 'group' => 'Brand', 'label' => 'Hover',         'name' => 'Brand Hover',  'slug' => 'brand-hover',  'light' => '#3b82f6', 'twilight' => '#7dd3fc', 'dark' => '#8be0ff' ),
		'brand-active'  => array( 'group' => 'Brand', 'label' => 'Active',        'name' => 'Brand Active', 'slug' => 'brand-active', 'light' => '#1d4ed8', 'twilight' => '#0284c7', 'dark' => '#40a0df' ),
		'brand-muted'   => array( 'group' => 'Brand', 'label' => 'Muted',         'name' => 'Brand Muted',  'slug' => 'brand-muted',  'light' => '#dbeafe', 'twilight' => '#0c4a6e', 'dark' => '#1a3a4d' ),

		// Action (CTA)
		'cta-base'      => array( 'group' => 'Action (CTA)', 'label' => 'Base',   'name' => 'Action Base',   'slug' => 'cta-base',      'light' => '#ff3366', 'twilight' => '#fb7185', 'dark' => '#ff3366' ),
		'cta-hover'     => array( 'group' => 'Action (CTA)', 'label' => 'Hover',  'name' => 'Action Hover',  'slug' => 'cta-hover',     'light' => '#ff668c', 'twilight' => '#fda4af', 'dark' => '#ff668c' ),
		'cta-active'    => array( 'group' => 'Action (CTA)', 'label' => 'Active', 'name' => 'Action Active', 'slug' => 'cta-active',    'light' => '#e62050', 'twilight' => '#f43f5e', 'dark' => '#e62050' ),
		'cta-muted'     => array( 'group' => 'Action (CTA)', 'label' => 'Muted',  'name' => 'Action Muted',  'slug' => 'cta-muted',     'light' => '#ffe4e6', 'twilight' => '#4c0519', 'dark' => '#4d1a26' ),

		// Links
		'link'          => array( 'group' => 'Links', 'label' => 'Default',       'name' => 'Link Default', 'slug' => 'link-default', 'light' => '#2563eb', 'twilight' => '#38bdf8', 'dark' => '#62c9ff' ),
		'link-hover'    => array( 'group' => 'Links', 'label' => 'Hover',         'name' => 'Link Hover',   'slug' => 'link-hover',   'light' => '#ff3366', 'twilight' => '#fb7185', 'dark' => '#ff3366' ),
		'link-active'   => array( 'group' => 'Links', 'label' => 'Active',        'name' => 'Link Active',  'slug' => 'link-active',  'light' => '#1d4ed8', 'twilight' => '#fda4af', 'dark' => '#e62050' ),
		'link-visited'  => array( 'group' => 'Links', 'label' => 'Visited',       'name' => 'Link Visited', 'slug' => 'link-visited', 'light' => '#7c3aed', 'twilight' => '#a78bfa', 'dark' => '#9b59b6' ),

		// Text
		'text-heading'  => array( 'group' => 'Text', 'label' => 'Heading',        'name' => 'Text Heading', 'slug' => 'text-heading', 'light' => '#0f172a', 'twilight' => '#f8fafc', 'dark' => '#ffffff' ),
		'text-main'     => array( 'group' => 'Text', 'label' => 'Main',           'name' => 'Text Main',    'slug' => 'text-main',    'light' => '#334155', 'twilight' => '#e2e8f0', 'dark' => '#f8fafc' ),
		'text-muted'    => array( 'group' => 'Text', 'label' => 'Muted',          'name' => 'Text Muted',   'slug' => 'text-muted',   'light' => '#64748b', 'twilight' => '#94a3b8', 'dark' => '#94a3b8' ),
		'text-inverse'  => array( 'group' => 'Text', 'label' => 'Inverse',        'name' => 'Text Inverse', 'slug' => 'text-inverse', 'light' => '#ffffff', 'twilight' => '#0f172a', 'dark' => '#0a0b10' ),

		// Surfaces & Layers
		'body'          => array( 'group' => 'Surfaces & Layers', 'label' => 'Body (Base)',      'name' => 'Body (Base)',      'slug' => 'surface-body',    'light' => '#ffffff', 'twilight' => '#0f172a', 'dark' => '#0a0b10' ),
		'main'          => array( 'group' => 'Surfaces & Layers', 'label' => 'Main Background', 'name' => 'Main Background', 'slug' => 'surface-main',    'light' => '#ffffff', 'twilight' => '#1e293b', 'dark' => '#0f172a' ),
		'section'       => array( 'group' => 'Surfaces & Layers', 'label' => 'Section',         'name' => 'Section Layer',    'slug' => 'surface-section', 'light' => '#f8fafc', 'twilight' => '#162235', 'dark' => 'rgba(255, 255, 255, 0.03)' ),
		'card'          => array( 'group' => 'Surfaces & Layers', 'label' => 'Card',            'name' => 'Card Layer',       'slug' => 'surface-card',    'light' => '#ffffff', 'twilight' => '#1e293b', 'dark' => 'rgba(255, 255, 255, 0.05)' ),

		// Borders & Lines
		'border-base'   => array( 'group' => 'Borders & Lines', 'label' => 'Base',          'name' => 'Border Base',  'slug' => 'border-base',  'light' => '#e2e8f0', 'twilight' => '#334155', 'dark' => 'rgba(255, 255, 255, 0.12)' ),
		'border-hover'  => array( 'group' => 'Borders & Lines', 'label' => 'Hover',         'name' => 'Border Hover', 'slug' => 'border-hover', 'light' => '#cbd5e1', 'twilight' => '#475569', 'dark' => '#62c9ff' ),
		'border-focus'  => array( 'group' => 'Borders & Lines', 'label' => 'Focus',         'name' => 'Border Focus', 'slug' => 'border-focus', 'light' => '#2563eb', 'twilight' => '#38bdf8', 'dark' => '#62c9ff' ),
		'border-muted'  => array( 'group' => 'Borders & Lines', 'label' => 'Muted/Divider', 'name' => 'Border Muted', 'slug' => 'border-muted', 'light' => '#e2e8f0', 'twilight' => '#1e293b', 'dark' => 'rgba(255, 255, 255, 0.06)' ),

		// Status System
		'success'       => array( 'group' => 'Status System', 'label' => 'Success', 'name' => 'Status Success', 'slug' => 'status-success', 'light' => '#10b981', 'twilight' => '#34d399', 'dark' => '#10b981' ),
		'warning'       => array( 'group' => 'Status System', 'label' => 'Warning', 'name' => 'Status Warning', 'slug' => 'status-warning', 'light' => '#f59e0b', 'twilight' => '#fbbf24', 'dark' => '#f59e0b' ),
		'danger'        => array( 'group' => 'Status System', 'label' => 'Danger',  'name' => 'Status Danger',  'slug' => 'status-danger',  'light' => '#ef4444', 'twilight' => '#f87171', 'dark' => '#ef4444' ),
		'info'          => array( 'group' => 'Status System', 'label' => 'Info',    'name' => 'Status Info',    'slug' => 'status-info',    'light' => '#3b82f6', 'twilight' => '#60a5fa', 'dark' => '#3b82f6' ),
	);
}

/**
 * Convert Hex color to RGB comma-delimited string.
 *
 * @param string $hex
 * @return string
 */
function mh_hex2rgb( $hex ) {
	$hex = str_replace( '#', '', $hex );
	if ( strlen( $hex ) == 3 ) {
		$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}
	return "$r, $g, $b";
}

/**
 * Contextual active callbacks for background mode controls
 */
function mh_is_bg_mode_solid( $control ) {
	return $control->manager->get_setting( 'mh_bg_mode' ) && $control->manager->get_setting( 'mh_bg_mode' )->value() === 'solid';
}

function mh_is_bg_mode_gradient( $control ) {
	return $control->manager->get_setting( 'mh_bg_mode' ) && $control->manager->get_setting( 'mh_bg_mode' )->value() === 'gradient';
}

function mh_is_bg_mode_image( $control ) {
	return $control->manager->get_setting( 'mh_bg_mode' ) && $control->manager->get_setting( 'mh_bg_mode' )->value() === 'image';
}

function mh_is_bg_mode_canvas( $control ) {
	return $control->manager->get_setting( 'mh_bg_mode' ) && $control->manager->get_setting( 'mh_bg_mode' )->value() === 'canvas';
}

/**
 * Polyfill window.crypto.randomUUID for insecure HTTP / local development
 */
function xophz_magic_hat_polyfill_random_uuid() {
	?>
	<script>
		if (typeof window !== 'undefined') {
			if (!window.crypto) { window.crypto = {}; }
			if (!window.crypto.randomUUID) {
				window.crypto.randomUUID = function() {
					return '10000000-1000-4000-8000-100000000000'.replace(/[018]/g, function(c) {
						return (c ^ (window.crypto.getRandomValues ? window.crypto.getRandomValues(new Uint8Array(1))[0] : Math.floor(Math.random() * 256)) & 15 >> c / 4).toString(16);
					});
				};
			}
		}
	</script>
	<?php
}
add_action( 'customize_controls_print_scripts', 'xophz_magic_hat_polyfill_random_uuid', 0 );
add_action( 'admin_print_scripts', 'xophz_magic_hat_polyfill_random_uuid', 0 );
add_action( 'admin_head', 'xophz_magic_hat_polyfill_random_uuid', 0 );
add_action( 'wp_head', 'xophz_magic_hat_polyfill_random_uuid', 0 );
