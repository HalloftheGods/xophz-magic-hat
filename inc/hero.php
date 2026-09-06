<?php
/**
 * Magic Hat Dedicated Front Page Hero Section (One Page Express Pattern)
 *
 * Provides a dedicated, customizable hero banner at the top of the homepage,
 * featuring 5 modular layouts, height/width toggles, dual CTA actions,
 * and live Selective Refresh in the Customizer.
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Front Page Hero Settings in the Customizer
 *
 * @param WP_Customize_Manager $wp_customize
 */
function xophz_magic_hat_register_hero_customizer( $wp_customize ) {

	// Hero Settings Section
	$wp_customize->add_section( 'mh_front_page_hero', array(
		'title'       => __( '🌟 Hero Settings', 'xophz-magic-hat' ),
		'description' => __( 'Configure your hero banner: choose between 5 layouts, customize typography, buttons, and toggle between Full Width and Boxed.', 'xophz-magic-hat' ),
		'priority'    => 35,
	) );

	// 1. Enable / Disable Hero
	$wp_customize->add_setting( 'mh_hero_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_enabled', array(
		'type'        => 'checkbox',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Enable Hero Section', 'xophz-magic-hat' ),
		'description' => __( 'Show this prominent hero section at the top of the page.', 'xophz-magic-hat' ),
	) );

	// 2. Hero Layout
	$wp_customize->add_setting( 'mh_hero_layout', array(
		'default'           => 'split',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_layout', array(
		'type'        => 'select',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Hero Layout', 'xophz-magic-hat' ),
		'choices'     => array(
			'split'     => __( 'Split 2-Column (Text Left, Image Right)', 'xophz-magic-hat' ),
			'centered'  => __( 'Centered Impact (Showcase & Avatars)', 'xophz-magic-hat' ),
			'editorial' => __( 'Editorial Minimal (Oversized Statement)', 'xophz-magic-hat' ),
			'app'       => __( 'App Showcase (Proposition & Mockup)', 'xophz-magic-hat' ),
			'video'     => __( 'Ambient Media (High-Contrast Backdrop)', 'xophz-magic-hat' ),
		),
	) );

	// 3. Hero Width (Full Width vs Boxed)
	$wp_customize->add_setting( 'mh_hero_width', array(
		'default'           => 'boxed',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_width', array(
		'type'        => 'select',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Section Width', 'xophz-magic-hat' ),
		'choices'     => array(
			'boxed' => __( '◻ Boxed (Contained 1200px)', 'xophz-magic-hat' ),
			'full'  => __( '⛶ Full Width (100% Bleed)', 'xophz-magic-hat' ),
		),
	) );

	// 4. Hero Height
	$wp_customize->add_setting( 'mh_hero_height', array(
		'default'           => 'large',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_height', array(
		'type'        => 'select',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Section Height / Spacing', 'xophz-magic-hat' ),
		'choices'     => array(
			'fullscreen' => __( 'Fullscreen (90vh)', 'xophz-magic-hat' ),
			'large'      => __( 'Large (620px)', 'xophz-magic-hat' ),
			'medium'     => __( 'Medium (480px)', 'xophz-magic-hat' ),
			'compact'    => __( 'Compact (360px)', 'xophz-magic-hat' ),
		),
	) );

	// 5. Badge Pill
	$wp_customize->add_setting( 'mh_hero_badge', array(
		'default'           => '⚡ NEW GENERATION THEME',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_badge', array(
		'type'        => 'text',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Badge Pill Text', 'xophz-magic-hat' ),
	) );

	// 6. Headline
	$wp_customize->add_setting( 'mh_hero_headline', array(
		'default'           => 'We Synthesize The Modern Web',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_headline', array(
		'type'        => 'text',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Main Headline', 'xophz-magic-hat' ),
	) );

	// 7. Subtitle
	$wp_customize->add_setting( 'mh_hero_subtitle', array(
		'default'           => 'Create stunning, high-converting digital experiences with modular precision and dynamic circadian lighting.',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_subtitle', array(
		'type'        => 'textarea',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Subtitle / Value Proposition', 'xophz-magic-hat' ),
	) );

	// 8. Primary CTA
	$wp_customize->add_setting( 'mh_hero_cta_primary_text', array(
		'default'           => 'Get Started',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_cta_primary_text', array(
		'type'        => 'text',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Primary Button Label', 'xophz-magic-hat' ),
	) );

	$wp_customize->add_setting( 'mh_hero_cta_primary_url', array(
		'default'           => '#features',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_cta_primary_url', array(
		'type'        => 'url',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Primary Button Destination URL', 'xophz-magic-hat' ),
	) );

	// 9. Secondary CTA
	$wp_customize->add_setting( 'mh_hero_cta_secondary_text', array(
		'default'           => 'Explore Architecture',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_cta_secondary_text', array(
		'type'        => 'text',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Secondary Button Label', 'xophz-magic-hat' ),
	) );

	$wp_customize->add_setting( 'mh_hero_cta_secondary_url', array(
		'default'           => '#about',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_cta_secondary_url', array(
		'type'        => 'url',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Secondary Button Destination URL', 'xophz-magic-hat' ),
	) );

	// 10. Visual Image
	$wp_customize->add_setting( 'mh_hero_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mh_hero_image', array(
		'label'       => __( 'Hero Graphic / Image', 'xophz-magic-hat' ),
		'section'     => 'mh_front_page_hero',
		'description' => __( 'Upload an image or illustration to display alongside the headline.', 'xophz-magic-hat' ),
	) ) );

	// 11. Hero Background Type
	$wp_customize->add_setting( 'mh_hero_bg_type', array(
		'default'           => 'default',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_hero_bg_type', array(
		'type'        => 'select',
		'section'     => 'mh_front_page_hero',
		'label'       => __( 'Hero Background Surface', 'xophz-magic-hat' ),
		'choices'     => array(
			'default'  => __( 'Theme Surface (Circadian Daylight)', 'xophz-magic-hat' ),
			'subtle'   => __( 'Subtle Slate Tint', 'xophz-magic-hat' ),
			'gradient' => __( 'Soft Linear Gradient', 'xophz-magic-hat' ),
			'dark'     => __( 'Deep Obsidian Slate', 'xophz-magic-hat' ),
		),
	) );

	// Selective Refresh Partials for Hero
	if ( isset( $wp_customize->selective_refresh ) ) {
		// Main Hero Section partial for structural layout / height / width changes
		$wp_customize->selective_refresh->add_partial( 'mh_hero_partial', array(
			'selector'            => '#mh-front-page-hero',
			'settings'            => array(
				'mh_hero_enabled',
				'mh_hero_layout',
				'mh_hero_width',
				'mh_hero_height',
				'mh_hero_bg_type',
			),
			'render_callback'     => 'mh_render_hero_markup',
			'container_inclusive' => true,
		) );

		// Granular Badge partial
		$wp_customize->selective_refresh->add_partial( 'mh_hero_badge_partial', array(
			'selector'        => '.mh-hero-badge',
			'settings'        => array( 'mh_hero_badge' ),
			'render_callback' => function() {
				return esc_html( get_theme_mod( 'mh_hero_badge', '⚡ NEW GENERATION THEME' ) );
			},
		) );

		// Granular Headline partial
		$wp_customize->selective_refresh->add_partial( 'mh_hero_headline_partial', array(
			'selector'        => '.mh-hero-headline',
			'settings'        => array( 'mh_hero_headline' ),
			'render_callback' => function() {
				return esc_html( get_theme_mod( 'mh_hero_headline', 'We Synthesize The Modern Web' ) );
			},
		) );

		// Granular Subtitle partial
		$wp_customize->selective_refresh->add_partial( 'mh_hero_subtitle_partial', array(
			'selector'        => '.mh-hero-subtitle',
			'settings'        => array( 'mh_hero_subtitle' ),
			'render_callback' => function() {
				return esc_html( get_theme_mod( 'mh_hero_subtitle', 'Create stunning, high-converting digital experiences with modular precision and dynamic circadian lighting.' ) );
			},
		) );

		// Granular CTA 1 partial
		$wp_customize->selective_refresh->add_partial( 'mh_hero_cta1_partial', array(
			'selector'        => '.mh-hero-cta1',
			'settings'        => array( 'mh_hero_cta_primary_text' ),
			'render_callback' => function() {
				return esc_html( get_theme_mod( 'mh_hero_cta_primary_text', 'Get Started' ) );
			},
		) );

		// Granular CTA 2 partial
		$wp_customize->selective_refresh->add_partial( 'mh_hero_cta2_partial', array(
			'selector'        => '.mh-hero-cta2',
			'settings'        => array( 'mh_hero_cta_secondary_text' ),
			'render_callback' => function() {
				return esc_html( get_theme_mod( 'mh_hero_cta_secondary_text', 'Explore Architecture' ) );
			},
		) );
	}
}
add_action( 'customize_register', 'xophz_magic_hat_register_hero_customizer', 25 );

/**
 * Register Gutenberg Block for Native FSE Block Templates
 */
function xophz_magic_hat_register_hero_block() {
	if ( function_exists( 'register_block_type' ) ) {
		register_block_type( 'xophz-magic-hat/hero', array(
			'render_callback' => 'mh_render_hero_markup',
		) );
	}
}
add_action( 'init', 'xophz_magic_hat_register_hero_block' );

/**
 * Render Hero HTML Markup (Supports any page with dynamic title fallbacks)
 */
function mh_render_hero_markup( $post_id = null ) {
	$current_id    = $post_id ? absint( $post_id ) : get_queried_object_id();
	$front_page_id = absint( get_option( 'page_on_front' ) );
	$is_front      = is_front_page() || ( $front_page_id && $current_id === $front_page_id );

	// Check per-page hero enabled toggle if set, otherwise fallback to global theme mod
	$page_hero_meta = $current_id ? get_post_meta( $current_id, '_mh_hero_enabled', true ) : '';
	if ( '' !== $page_hero_meta ) {
		$enabled = ( '1' === $page_hero_meta || 'yes' === $page_hero_meta || true === $page_hero_meta );
	} else {
		$enabled = get_theme_mod( 'mh_hero_enabled', true );
	}

	if ( ! $enabled && ! is_customize_preview() ) {
		return '';
	}

	$layout      = get_theme_mod( 'mh_hero_layout', 'split' );
	$width_mode  = get_theme_mod( 'mh_hero_width', 'boxed' );
	$height_mode = get_theme_mod( 'mh_hero_height', 'large' );
	$badge       = get_theme_mod( 'mh_hero_badge', '⚡ NEW GENERATION THEME' );

	// Headline resolution: per-page meta > page title (on non-front pages) > global theme mod
	$page_headline_meta = $current_id ? get_post_meta( $current_id, '_mh_hero_headline', true ) : '';
	if ( ! empty( $page_headline_meta ) ) {
		$headline = $page_headline_meta;
	} elseif ( ! $is_front && $current_id && get_the_title( $current_id ) ) {
		$headline = get_the_title( $current_id );
	} else {
		$headline = get_theme_mod( 'mh_hero_headline', 'We Synthesize The Modern Web' );
	}

	$page_subtitle_meta = $current_id ? get_post_meta( $current_id, '_mh_hero_subtitle', true ) : '';
	if ( ! empty( $page_subtitle_meta ) ) {
		$subtitle = $page_subtitle_meta;
	} else {
		$subtitle = get_theme_mod( 'mh_hero_subtitle', 'Create stunning, high-converting digital experiences with modular precision and dynamic circadian lighting.' );
	}

	$cta1_text   = get_theme_mod( 'mh_hero_cta_primary_text', 'Get Started' );
	$cta1_url    = get_theme_mod( 'mh_hero_cta_primary_url', '#features' );
	$cta2_text   = get_theme_mod( 'mh_hero_cta_secondary_text', 'Explore Architecture' );
	$cta2_url    = get_theme_mod( 'mh_hero_cta_secondary_url', '#about' );
	$image_url   = get_theme_mod( 'mh_hero_image', '' );
	$bg_type     = get_theme_mod( 'mh_hero_bg_type', 'default' );

	if ( empty( $image_url ) ) {
		$image_url = 'https://picsum.photos/seed/magichat77/800/550';
	}

	// Height calculation
	$min_height = '560px';
	if ( $height_mode === 'fullscreen' ) {
		$min_height = 'calc(90vh - 70px)';
	} elseif ( $height_mode === 'medium' ) {
		$min_height = '460px';
	} elseif ( $height_mode === 'compact' ) {
		$min_height = '340px';
	}

	// Background surface
	$bg_style = 'background: var(--mh-color-body, #ffffff);';
	if ( $bg_type === 'subtle' ) {
		$bg_style = 'background: var(--mh-color-section, #f8fafc);';
	} elseif ( $bg_type === 'gradient' ) {
		$bg_style = 'background: linear-gradient(135deg, var(--mh-color-body, #f8fafc) 0%, var(--mh-color-section, #eff6ff) 100%);';
	} elseif ( $bg_type === 'dark' ) {
		$bg_style = 'background: var(--mh-color-main, #0f172a); color: var(--mh-color-text-heading, #ffffff);';
	}

	// Container width
	$container_style = ( $width_mode === 'full' ) 
		? 'max-width: 100%; padding: 0 48px; width: 100%; box-sizing: border-box;' 
		: 'max-width: var(--mh-content-width, 1200px); margin: 0 auto; padding: 0 24px; width: 100%; box-sizing: border-box;';

	$display_style = ( ! $enabled && is_customize_preview() ) ? 'display: none;' : '';

	ob_start();
	?>
	<section id="mh-front-page-hero" class="mh-front-page-hero mh-hero-<?php echo esc_attr( $layout ); ?> mh-hero-width-<?php echo esc_attr( $width_mode ); ?>" style="position: relative; z-index: 1; border-bottom: 1px solid var(--mh-color-border-muted, #e2e8f0); min-height: <?php echo esc_attr( $min_height ); ?>; display: flex; align-items: center; <?php echo $bg_style; ?> <?php echo $display_style; ?>" data-mw-type="hero">
		
		<div class="mh-hero-container" style="<?php echo $container_style; ?>">
			<?php if ( $layout === 'split' ) : ?>
				<!-- Split 2-Column Layout -->
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 48px; align-items: center; padding: 60px 0;">
					<div class="mh-hero-text">
						<?php if ( ! empty( $badge ) ) : ?>
							<span class="mh-hero-badge" data-mh-focus="mh_hero_badge" style="display: inline-block; padding: 4px 12px; background: color-mix(in srgb, var(--mh-color-brand-base, #2563eb) 12%, transparent); color: var(--mh-color-brand-base, #2563eb); border-radius: 9999px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 16px; cursor: pointer;">
								<?php echo esc_html( $badge ); ?>
							</span>
						<?php endif; ?>
						<h1 class="mh-hero-headline" data-mh-focus="mh_hero_headline" style="font-size: clamp(32px, 5vw, 56px); font-weight: 800; line-height: 1.15; color: var(--mh-color-text-heading, #0f172a); margin: 0 0 18px; cursor: text;">
							<?php echo esc_html( $headline ); ?>
						</h1>
						<p class="mh-hero-subtitle" data-mh-focus="mh_hero_subtitle" style="font-size: clamp(16px, 2vw, 19px); line-height: 1.6; color: var(--mh-color-text-muted, #64748b); margin: 0 0 32px; max-width: 540px; cursor: text;">
							<?php echo esc_html( $subtitle ); ?>
						</p>
						<div style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
							<?php if ( ! empty( $cta1_text ) ) : ?>
								<a href="<?php echo esc_url( $cta1_url ); ?>" class="mh-hero-cta1 btn-primary" data-mh-focus="mh_hero_cta_primary_text" data-mh-link="mh_hero_cta_primary_url" style="background: var(--mh-color-cta-base, #ff3366); color: var(--mh-color-text-inverse, #ffffff); padding: 13px 30px; font-size: 14px; font-weight: 700; border-radius: 6px; text-decoration: none; display: inline-block; box-shadow: 0 4px 14px color-mix(in srgb, var(--mh-color-cta-base, #ff3366) 35%, transparent); transition: transform 0.15s, box-shadow 0.15s; cursor: pointer;">
									<?php echo esc_html( $cta1_text ); ?>
								</a>
							<?php endif; ?>
							<?php if ( ! empty( $cta2_text ) ) : ?>
								<a href="<?php echo esc_url( $cta2_url ); ?>" class="mh-hero-cta2 btn-secondary" data-mh-focus="mh_hero_cta_secondary_text" data-mh-link="mh_hero_cta_secondary_url" style="background: var(--mh-color-card, #ffffff); color: var(--mh-color-text-heading, #0f172a); border: 1px solid var(--mh-color-border-muted, #cbd5e1); padding: 13px 26px; font-size: 14px; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-block; cursor: pointer;">
									<?php echo esc_html( $cta2_text ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
					<div class="mh-hero-media" style="text-align: center;">
						<img class="mh-hero-image-el" data-mh-focus="mh_hero_image" data-mh-image="mh_hero_image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $headline ); ?>" style="width: 100%; max-height: 480px; object-fit: cover; border-radius: 14px; box-shadow: 0 20px 35px -10px rgba(0,0,0,0.12); border: 1px solid var(--mh-color-border-muted, #e2e8f0); cursor: pointer;" />
					</div>
				</div>

			<?php elseif ( $layout === 'centered' ) : ?>
				<!-- Centered Impact Layout -->
				<div style="text-align: center; max-width: 820px; margin: 0 auto; padding: 80px 0;">
					<?php if ( ! empty( $badge ) ) : ?>
						<span class="mh-hero-badge" data-mh-focus="mh_hero_badge" style="display: inline-block; padding: 4px 14px; background: color-mix(in srgb, var(--mh-color-brand-base, #2563eb) 12%, transparent); color: var(--mh-color-brand-base, #2563eb); border-radius: 9999px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 18px; cursor: pointer;">
							<?php echo esc_html( $badge ); ?>
						</span>
					<?php endif; ?>
					<h1 class="mh-hero-headline" data-mh-focus="mh_hero_headline" style="font-size: clamp(34px, 5.5vw, 62px); font-weight: 800; line-height: 1.15; color: var(--mh-color-text-heading, #0f172a); margin: 0 0 20px; cursor: text;">
						<?php echo esc_html( $headline ); ?>
					</h1>
					<p class="mh-hero-subtitle" data-mh-focus="mh_hero_subtitle" style="font-size: clamp(16px, 2.2vw, 20px); line-height: 1.6; color: var(--mh-color-text-muted, #64748b); margin: 0 auto 36px; max-width: 640px; cursor: text;">
						<?php echo esc_html( $subtitle ); ?>
					</p>
					<div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-bottom: 40px;">
						<?php if ( ! empty( $cta1_text ) ) : ?>
							<a href="<?php echo esc_url( $cta1_url ); ?>" class="mh-hero-cta1 btn-primary" data-mh-focus="mh_hero_cta_primary_text" data-mh-link="mh_hero_cta_primary_url" style="background: var(--mh-color-cta-base, #ff3366); color: var(--mh-color-text-inverse, #ffffff); padding: 14px 34px; font-size: 15px; font-weight: 700; border-radius: 6px; text-decoration: none; display: inline-block; box-shadow: 0 4px 14px color-mix(in srgb, var(--mh-color-cta-base, #ff3366) 35%, transparent); cursor: pointer;">
								<?php echo esc_html( $cta1_text ); ?>
							</a>
						<?php endif; ?>
						<?php if ( ! empty( $cta2_text ) ) : ?>
							<a href="<?php echo esc_url( $cta2_url ); ?>" class="mh-hero-cta2 btn-secondary" data-mh-focus="mh_hero_cta_secondary_text" data-mh-link="mh_hero_cta_secondary_url" style="background: var(--mh-color-card, #ffffff); color: var(--mh-color-text-heading, #0f172a); border: 1px solid var(--mh-color-border-muted, #cbd5e1); padding: 14px 28px; font-size: 15px; font-weight: 600; border-radius: 6px; text-decoration: none; display: inline-block; cursor: pointer;">
								<?php echo esc_html( $cta2_text ); ?>
							</a>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $image_url ) ) : ?>
						<div style="margin-top: 20px;">
							<img class="mh-hero-image-el" data-mh-focus="mh_hero_image" data-mh-image="mh_hero_image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $headline ); ?>" style="width: 100%; max-height: 420px; object-fit: cover; border-radius: 12px; box-shadow: 0 25px 40px -15px rgba(0,0,0,0.15); border: 1px solid var(--mh-color-border-muted, #e2e8f0); cursor: pointer;" />
						</div>
					<?php endif; ?>
				</div>

			<?php elseif ( $layout === 'editorial' ) : ?>
				<!-- Editorial Minimal Layout -->
				<div style="max-width: 900px; padding: 90px 0;">
					<?php if ( ! empty( $badge ) ) : ?>
						<span class="mh-hero-badge" data-mh-focus="mh_hero_badge" style="font-size: 12px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: var(--mh-color-brand-base, #2563eb); display: block; margin-bottom: 16px; cursor: pointer;">
							<?php echo esc_html( $badge ); ?>
						</span>
					<?php endif; ?>
					<h1 class="mh-hero-headline" data-mh-focus="mh_hero_headline" style="font-size: clamp(38px, 6vw, 68px); font-weight: 900; line-height: 1.08; letter-spacing: -1px; color: var(--mh-color-text-heading, #0f172a); margin: 0 0 24px; cursor: text;">
						<?php echo esc_html( $headline ); ?>
					</h1>
					<p class="mh-hero-subtitle" data-mh-focus="mh_hero_subtitle" style="font-size: 20px; line-height: 1.7; color: var(--mh-color-text-muted, #64748b); margin: 0 0 32px; max-width: 680px; cursor: text;">
						<?php echo esc_html( $subtitle ); ?>
					</p>
					<?php if ( ! empty( $cta1_text ) ) : ?>
						<a href="<?php echo esc_url( $cta1_url ); ?>" class="mh-hero-cta1" data-mh-focus="mh_hero_cta_primary_text" data-mh-link="mh_hero_cta_primary_url" style="color: var(--mh-color-brand-base, #2563eb); font-size: 16px; font-weight: 700; text-decoration: none; border-bottom: 2px solid var(--mh-color-brand-base, #2563eb); padding-bottom: 4px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
							<?php echo esc_html( $cta1_text ); ?> &rarr;
						</a>
					<?php endif; ?>
				</div>

			<?php elseif ( $layout === 'app' ) : ?>
				<!-- App Showcase Layout -->
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; align-items: center; padding: 70px 0;">
					<div>
						<?php if ( ! empty( $badge ) ) : ?>
							<span class="mh-hero-badge" data-mh-focus="mh_hero_badge" style="display: inline-block; padding: 3px 10px; background: color-mix(in srgb, var(--mh-color-brand-base, #2563eb) 12%, transparent); color: var(--mh-color-brand-base, #2563eb); border-radius: 9999px; font-size: 11px; font-weight: 700; margin-bottom: 14px; cursor: pointer;">
								<?php echo esc_html( $badge ); ?>
							</span>
						<?php endif; ?>
						<h1 class="mh-hero-headline" data-mh-focus="mh_hero_headline" style="font-size: clamp(30px, 4.5vw, 50px); font-weight: 800; line-height: 1.2; color: var(--mh-color-text-heading, #0f172a); margin: 0 0 16px; cursor: text;">
							<?php echo esc_html( $headline ); ?>
						</h1>
						<p class="mh-hero-subtitle" data-mh-focus="mh_hero_subtitle" style="font-size: 16px; line-height: 1.6; color: var(--mh-color-text-muted, #64748b); margin: 0 0 28px; cursor: text;">
							<?php echo esc_html( $subtitle ); ?>
						</p>
						<div style="display: flex; gap: 12px; flex-wrap: wrap;">
							<?php if ( ! empty( $cta1_text ) ) : ?>
								<a href="<?php echo esc_url( $cta1_url ); ?>" class="mh-hero-cta1 btn-primary" data-mh-focus="mh_hero_cta_primary_text" data-mh-link="mh_hero_cta_primary_url" style="background: var(--mh-color-cta-base, #ff3366); color: var(--mh-color-text-inverse, #ffffff); padding: 12px 28px; font-size: 14px; font-weight: 700; border-radius: 6px; text-decoration: none; cursor: pointer; box-shadow: 0 4px 14px color-mix(in srgb, var(--mh-color-cta-base, #ff3366) 35%, transparent);">
									<?php echo esc_html( $cta1_text ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
					<div style="display: flex; justify-content: center;">
						<div style="background: var(--mh-color-card, #ffffff); border: 1px solid var(--mh-color-border-muted, #cbd5e1); border-radius: 18px; padding: 12px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.18); max-width: 440px; width: 100%;">
							<img class="mh-hero-image-el" data-mh-focus="mh_hero_image" data-mh-image="mh_hero_image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $headline ); ?>" style="width: 100%; border-radius: 10px; display: block; cursor: pointer;" />
						</div>
					</div>
				</div>

			<?php elseif ( $layout === 'video' ) : ?>
				<!-- Ambient Video / Media Layout -->
				<div style="text-align: center; max-width: 780px; margin: 0 auto; padding: 90px 0;">
					<h1 class="mh-hero-headline" data-mh-focus="mh_hero_headline" style="font-size: clamp(34px, 5vw, 60px); font-weight: 900; line-height: 1.15; color: var(--mh-color-text-heading, #0f172a); margin: 0 0 20px; cursor: text;">
						<?php echo esc_html( $headline ); ?>
					</h1>
					<p class="mh-hero-subtitle" data-mh-focus="mh_hero_subtitle" style="font-size: 18px; line-height: 1.6; color: var(--mh-color-text-muted, #64748b); margin: 0 auto 32px; cursor: text;">
						<?php echo esc_html( $subtitle ); ?>
					</p>
					<?php if ( ! empty( $cta1_text ) ) : ?>
						<a href="<?php echo esc_url( $cta1_url ); ?>" class="mh-hero-cta1 btn-primary" data-mh-focus="mh_hero_cta_primary_text" data-mh-link="mh_hero_cta_primary_url" style="background: var(--mh-color-cta-base, #ff3366); color: var(--mh-color-text-inverse, #ffffff); padding: 14px 34px; font-size: 15px; font-weight: 700; border-radius: 6px; text-decoration: none; display: inline-block; cursor: pointer; box-shadow: 0 4px 14px color-mix(in srgb, var(--mh-color-cta-base, #ff3366) 35%, transparent);">
							<?php echo esc_html( $cta1_text ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Register shortcode for backward compatibility
 */
add_shortcode( 'magic_hat_hero', 'mh_render_hero_markup' );
