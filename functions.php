<?php
/**
 * Xophz Blank Slate Functions
 */

// We leave Gutenberg block styles intact so that native content blocks (like galleries or columns) 
// within the_content() don't break. 
// If you specifically want to dequeue theme.json global styles later, you can add it here.

// Disable WP emojis
// remove_action('wp_head', 'print_emoji_detection_script', 7);
// remove_action('wp_print_styles', 'print_emoji_styles');

function xophz_magic_hat_setup() {
    // Support title tag
    add_theme_support( 'title-tag' );

    // Support post thumbnails
    add_theme_support( 'post-thumbnails' );

    // Support align wide for Gutenberg blocks
    add_theme_support( 'align-wide' );

    // Support core block styles
    add_theme_support( 'wp-block-styles' );

    // Support block templates and template parts (Full Site Editing)
    add_theme_support( 'block-templates' );
    add_theme_support( 'block-template-parts' );

    // Support editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/variables.css' );
    add_editor_style( 'assets/css/editor-style.css' );

    // Support selective refresh in Customizer
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Register navigation menus
    register_nav_menus( array(
        'primary'  => __( 'Primary Menu', 'xophz-magic-hat' ),
        'footer_1' => __( 'Footer Menu 1 (Explore)', 'xophz-magic-hat' ),
        'footer_2' => __( 'Footer Menu 2 (Resources)', 'xophz-magic-hat' ),
        'footer_3' => __( 'Footer Menu 3 (Legal)', 'xophz-magic-hat' ),
        'footer_4' => __( 'Footer Menu 4 (Contact)', 'xophz-magic-hat' ),
    ) );
}
add_action( 'after_setup_theme', 'xophz_magic_hat_setup' );

function xophz_magic_hat_create_default_menus() {
    if ( get_option( 'mh_default_menus_created' ) && get_option( 'mh_contact_menu_created' ) ) {
        return;
    }

    $menus = array(
        'Primary Menu' => array(
            'location' => 'primary',
            'items' => array('About', 'Services', 'Portfolio', 'Team'),
        ),
        'Explore' => array(
            'location' => 'footer_1',
            'items' => array('About Us', 'Features', 'Our Work', 'Pricing'),
        ),
        'Resources' => array(
            'location' => 'footer_2',
            'items' => array('Help Center', 'Documentation', 'Community', 'Blog'),
        ),
        'Legal' => array(
            'location' => 'footer_3',
            'items' => array('Privacy Policy', 'Terms of Service', 'Cookie Policy'),
        ),
        'Contact' => array(
            'location' => 'footer_4',
            'custom_items' => array(
                array('title' => 'hello@youmeos.com', 'url' => 'mailto:hello@youmeos.com'),
                array('title' => '+1 (234) 567-890', 'url' => 'tel:+1234567890'),
                array('title' => 'Support Desk', 'url' => '#'),
            ),
        ),
    );

    $locations = get_theme_mod('nav_menu_locations', array());
    $menu_created = false;

    foreach ($menus as $menu_name => $menu_data) {
        $menu_exists = wp_get_nav_menu_object($menu_name);

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);
            if (!is_wp_error($menu_id)) {
                if (isset($menu_data['custom_items'])) {
                    foreach ($menu_data['custom_items'] as $item) {
                        wp_update_nav_menu_item($menu_id, 0, array(
                            'menu-item-title'  => $item['title'],
                            'menu-item-url'    => $item['url'],
                            'menu-item-status' => 'publish',
                            'menu-item-type'   => 'custom',
                        ));
                    }
                } elseif (isset($menu_data['items'])) {
                    foreach ($menu_data['items'] as $title) {
                        $page_id = mh_get_or_create_page($title);
                        wp_update_nav_menu_item($menu_id, 0, array(
                            'menu-item-title'     => $title,
                            'menu-item-object-id' => $page_id,
                            'menu-item-object'    => 'page',
                            'menu-item-type'      => 'post_type',
                            'menu-item-status'    => 'publish',
                        ));
                    }
                }
                $is_unassigned = ! isset( $locations[$menu_data['location']] ) || $locations[$menu_data['location']] == 0;
                if ( $is_unassigned ) {
                    $locations[$menu_data['location']] = $menu_id;
                    $menu_created = true;
                }
            }
        } else if (!isset($locations[$menu_data['location']]) || $locations[$menu_data['location']] == 0) {
            $locations[$menu_data['location']] = $menu_exists->term_id;
            $menu_created = true;
        }
    }

    if ($menu_created) {
        set_theme_mod('nav_menu_locations', $locations);
    }
    
    update_option( 'mh_default_menus_created', true );
    update_option( 'mh_contact_menu_created', true );
}

function mh_get_or_create_page( $title ) {
    $slug = sanitize_title( $title );
    $existing = get_page_by_path( $slug );
    if ( $existing ) {
        return $existing->ID;
    }
    return wp_insert_post( array(
        'post_title'   => $title,
        'post_name'    => $slug,
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ) );
}

add_action('init', 'xophz_magic_hat_create_default_menus');

// Load Customizer Settings, AI Architect Engine & Header/Footer Engine
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/class-magic-hat-ai-architect.php';
require_once get_template_directory() . '/inc/header-footer.php';
require_once get_template_directory() . '/inc/hero.php';

// Enqueue Theme Styles & Scripts
function xophz_magic_hat_enqueue_styles() {
    // Enqueue Dashicons for header/footer icons
    wp_enqueue_style( 'dashicons' );

    // Enqueue the foundational Design Tokens
    wp_enqueue_style( 'magic-hat-variables', get_template_directory_uri() . '/assets/css/variables.css', array(), wp_get_theme()->get('Version') );
    
    // Enqueue Header & Footer styles
    wp_enqueue_style( 'magic-hat-header-footer', get_template_directory_uri() . '/assets/css/header-footer.css', array('magic-hat-variables'), wp_get_theme()->get('Version') );

    // Enqueue the main stylesheet
    wp_enqueue_style( 'magic-hat-style', get_stylesheet_uri(), array('magic-hat-variables', 'magic-hat-header-footer'), wp_get_theme()->get('Version') );

    // Enqueue Header & Footer client-side controller (mobile drawer, hamburger toggle)
    wp_enqueue_script( 'magic-hat-header-footer', get_template_directory_uri() . '/assets/js/header-footer.js', array(), wp_get_theme()->get('Version'), true );
}
add_action( 'wp_enqueue_scripts', 'xophz_magic_hat_enqueue_styles' );

/**
 * Enqueue scripts for Customizer Live Preview
 */
function xophz_magic_hat_customize_preview_init() {
    wp_enqueue_script(
        'magic-hat-customize-preview-ai',
        get_template_directory_uri() . '/assets/js/customizer-preview-ai.js',
        array( 'customize-preview', 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );

    wp_enqueue_script(
        'magic-hat-customize-preview-header-footer',
        get_template_directory_uri() . '/assets/js/customizer-preview-header-footer.js',
        array( 'customize-preview', 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'customize_preview_init', 'xophz_magic_hat_customize_preview_init' );

/**
 * Ensure Appearance > Customize is always present in WP Admin
 */
function xophz_magic_hat_add_customize_menu() {
    add_theme_page(
        __( 'Customize', 'xophz-magic-hat' ),
        __( 'Customize', 'xophz-magic-hat' ),
        'edit_theme_options',
        'customize.php'
    );
}
add_action( 'admin_menu', 'xophz_magic_hat_add_customize_menu' );

/**
 * Sync Magic Hat Customizer Colors with Gutenberg Editor Palette
 */
function xophz_magic_hat_gutenberg_palette() {
    $schedule_mode = get_theme_mod( 'mh_color_schedule_mode', 'circadian' );

    // Determine active phase suffix
    $phase = 'light';
    if ( $schedule_mode === 'dark' ) {
        $phase = 'dark';
    } elseif ( $schedule_mode === 'twilight' ) {
        $phase = 'twilight';
    } elseif ( $schedule_mode === 'circadian' ) {
        $hour = intval( current_time( 'H' ) );
        if ( $hour < 6 || $hour >= 21 ) {
            $phase = 'dark';
        } elseif ( ( $hour >= 6 && $hour < 8 ) || ( $hour >= 18 && $hour < 21 ) ) {
            $phase = 'twilight';
        } else {
            $phase = 'light';
        }
    }

    $colors = array(
        // Brand
        'mh_color_brand_base'   => array('name' => 'Brand Base', 'default' => '#2563eb', 'default_dark' => '#62c9ff', 'slug' => 'brand-base'),
        'mh_color_brand_hover'  => array('name' => 'Brand Hover', 'default' => '#3b82f6', 'default_dark' => '#8be0ff', 'slug' => 'brand-hover'),
        'mh_color_brand_active' => array('name' => 'Brand Active', 'default' => '#1d4ed8', 'default_dark' => '#40a0df', 'slug' => 'brand-active'),
        'mh_color_brand_muted'  => array('name' => 'Brand Muted', 'default' => '#dbeafe', 'default_dark' => '#1a3a4d', 'slug' => 'brand-muted'),
        
        // Action (CTA)
        'mh_color_cta_base'     => array('name' => 'Action Base', 'default' => '#ff3366', 'default_dark' => '#ff3366', 'slug' => 'cta-base'),
        'mh_color_cta_hover'    => array('name' => 'Action Hover', 'default' => '#ff668c', 'default_dark' => '#ff668c', 'slug' => 'cta-hover'),
        'mh_color_cta_active'   => array('name' => 'Action Active', 'default' => '#e62050', 'default_dark' => '#e62050', 'slug' => 'action-active'),
        'mh_color_cta_muted'    => array('name' => 'Action Muted', 'default' => '#ffe4e6', 'default_dark' => '#4d1a26', 'slug' => 'action-muted'),
        
        // Links
        'mh_color_link'         => array('name' => 'Link Default', 'default' => '#2563eb', 'default_dark' => '#62c9ff', 'slug' => 'link-default'),
        'mh_color_link_hover'   => array('name' => 'Link Hover', 'default' => '#ff3366', 'default_dark' => '#ff3366', 'slug' => 'link-hover'),
        'mh_color_link_active'  => array('name' => 'Link Active', 'default' => '#1d4ed8', 'default_dark' => '#e62050', 'slug' => 'link-active'),
        'mh_color_link_visited' => array('name' => 'Link Visited', 'default' => '#7c3aed', 'default_dark' => '#9b59b6', 'slug' => 'link-visited'),
        
        // Text
        'mh_color_text_heading' => array('name' => 'Text Heading', 'default' => '#0f172a', 'default_dark' => '#ffffff', 'slug' => 'text-heading'),
        'mh_color_text_main'    => array('name' => 'Text Main', 'default' => '#334155', 'default_dark' => '#f8fafc', 'slug' => 'text-main'),
        'mh_color_text_muted'   => array('name' => 'Text Muted', 'default' => '#64748b', 'default_dark' => '#94a3b8', 'slug' => 'text-muted'),
        'mh_color_text_inverse' => array('name' => 'Text Inverse', 'default' => '#ffffff', 'default_dark' => '#0f172a', 'slug' => 'text-inverse'),
        
        // Surfaces & Layers
        'mh_color_body'         => array('name' => 'Body (Base)', 'default' => '#ffffff', 'default_dark' => '#0a0b10', 'slug' => 'surface-body'),
        'mh_color_main'         => array('name' => 'Main Background', 'default' => '#ffffff', 'default_dark' => '#0f172a', 'slug' => 'surface-main'),
        'mh_color_section'      => array('name' => 'Section Layer', 'default' => '#f8fafc', 'default_dark' => 'rgba(255, 255, 255, 0.02)', 'slug' => 'surface-section'),
        'mh_color_card'         => array('name' => 'Card Layer', 'default' => '#ffffff', 'default_dark' => 'rgba(255, 255, 255, 0.05)', 'slug' => 'surface-card'),
        
        // Status System
        'mh_color_success'      => array('name' => 'Status Success', 'default' => '#10b981', 'default_dark' => '#10b981', 'slug' => 'status-success'),
        'mh_color_warning'      => array('name' => 'Status Warning', 'default' => '#f59e0b', 'default_dark' => '#f59e0b', 'slug' => 'status-warning'),
        'mh_color_danger'       => array('name' => 'Status Danger', 'default' => '#ef4444', 'default_dark' => '#ef4444', 'slug' => 'status-danger'),
        'mh_color_info'         => array('name' => 'Status Info', 'default' => '#3b82f6', 'default_dark' => '#3b82f6', 'slug' => 'status-info'),
    );

    $palette = array();
    foreach ( $colors as $key => $data ) {
        $setting_key = $key;
        $default_val = $data['default'];

        if ( $phase === 'dark' ) {
            $setting_key = $key . '_dark';
            $default_val = isset( $data['default_dark'] ) ? $data['default_dark'] : $data['default'];
        } elseif ( $phase === 'twilight' ) {
            $setting_key = $key . '_twilight';
        }

        $palette[] = array(
            'name'  => $data['name'],
            'slug'  => $data['slug'],
            'color' => get_theme_mod( $setting_key, $default_val ),
        );
    }

    add_theme_support( 'editor-color-palette', $palette );
    
    // Only disable custom colors if the option is checked in Customizer
    if ( get_theme_mod( 'mh_enforce_site_colors', false ) ) {
        add_theme_support( 'disable-custom-colors' );
    }
}
add_action( 'after_setup_theme', 'xophz_magic_hat_gutenberg_palette' );
