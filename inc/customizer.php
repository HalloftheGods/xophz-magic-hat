<?php
/**
 * Xophz Magic Hat Theme Customizer (Table-of-Contents Loader)
 *
 * Quantum Architecture Master Entrypoint. Orchestrates modular controls,
 * sections, design tokens, circadian rhythm, and generative canvases.
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── 1. Helpers & Canonical Palette Definitions ─────────────────
require_once __DIR__ . '/customizer/helpers.php';

// ── 2. Custom Customizer Controls ──────────────────────────────
require_once __DIR__ . '/customizer/controls/class-range-slider-control.php';
require_once __DIR__ . '/customizer/controls/class-font-control.php';
require_once __DIR__ . '/customizer/controls/class-ai-architect-control.php';
require_once __DIR__ . '/customizer/controls/class-page-builder-control.php';
require_once __DIR__ . '/customizer/controls/class-group-title-control.php';

// ── 3. Panels & Sections Registration Modules ─────────────────
require_once __DIR__ . '/customizer/sections/section-ai-page-architect.php';
require_once __DIR__ . '/customizer/sections/section-page-builder.php';
require_once __DIR__ . '/customizer/sections/panel-site-styles.php';
require_once __DIR__ . '/customizer/sections/panel-site-colors.php';
require_once __DIR__ . '/customizer/sections/section-background.php';
require_once __DIR__ . '/customizer/sections/section-typography.php';
require_once __DIR__ . '/customizer/sections/section-spacing.php';
require_once __DIR__ . '/customizer/sections/section-buttons.php';
require_once __DIR__ . '/customizer/sections/section-header.php';
require_once __DIR__ . '/customizer/sections/section-footer.php';
require_once __DIR__ . '/customizer/sections/reorder-hierarchy.php';

// ── 4. UI Scripts, CSS, Circadian & Ambient Canvas Engines ─────
require_once __DIR__ . '/customizer/controls-ui.php';
require_once __DIR__ . '/customizer/css-generator.php';
require_once __DIR__ . '/customizer/circadian.php';
require_once __DIR__ . '/customizer/ambient-canvas.php';

/**
 * Master Customizer Registration Callback.
 * Linear, declarative assembly of all customizer components.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function xophz_magic_hat_customize_register( $wp_customize ) {
	mh_register_ai_page_architect_section( $wp_customize );
	mh_register_page_builder_section( $wp_customize );
	mh_register_site_styles_panel( $wp_customize );
	mh_register_site_colors_panel( $wp_customize );
	mh_register_background_section( $wp_customize );
	mh_register_typography_section( $wp_customize );
	mh_register_spacing_section( $wp_customize );
	mh_register_buttons_section( $wp_customize );
	mh_register_header_section( $wp_customize );
	mh_register_footer_section( $wp_customize );
	mh_reorder_customizer_hierarchy( $wp_customize );
}
add_action( 'customize_register', 'xophz_magic_hat_customize_register' );
