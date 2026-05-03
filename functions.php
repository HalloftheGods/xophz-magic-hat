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

// Support title tag
add_theme_support( 'title-tag' );

// Support post thumbnails
add_theme_support( 'post-thumbnails' );

// Load Customizer Settings
require_once get_template_directory() . '/inc/customizer.php';

// Enqueue Theme Styles
function xophz_magic_hat_enqueue_styles() {
    // Enqueue the foundational Design Tokens
    wp_enqueue_style( 'magic-hat-variables', get_template_directory_uri() . '/assets/css/variables.css', array(), wp_get_theme()->get('Version') );
    
    // Enqueue the main stylesheet (if you eventually add custom rules there)
    wp_enqueue_style( 'magic-hat-style', get_stylesheet_uri(), array('magic-hat-variables'), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'xophz_magic_hat_enqueue_styles' );

/**
 * Sync Magic Hat Customizer Colors with Gutenberg Editor Palette
 */
function xophz_magic_hat_gutenberg_palette() {
    $colors = array(
        // Brand
        'mh_color_brand_base'   => array('name' => 'Brand Base', 'default' => '#62c9ff', 'slug' => 'brand-base'),
        'mh_color_brand_hover'  => array('name' => 'Brand Hover', 'default' => '#8be0ff', 'slug' => 'brand-hover'),
        'mh_color_brand_active' => array('name' => 'Brand Active', 'default' => '#40a0df', 'slug' => 'brand-active'),
        'mh_color_brand_muted'  => array('name' => 'Brand Muted', 'default' => '#1a3a4d', 'slug' => 'brand-muted'),
        
        // Action (CTA)
        'mh_color_cta_base'     => array('name' => 'Action Base', 'default' => '#ff3366', 'slug' => 'action-base'),
        'mh_color_cta_hover'    => array('name' => 'Action Hover', 'default' => '#ff668c', 'slug' => 'action-hover'),
        'mh_color_cta_active'   => array('name' => 'Action Active', 'default' => '#e62050', 'slug' => 'action-active'),
        'mh_color_cta_muted'    => array('name' => 'Action Muted', 'default' => '#4d1a26', 'slug' => 'action-muted'),
        
        // Links
        'mh_color_link'         => array('name' => 'Link Default', 'default' => '#62c9ff', 'slug' => 'link-default'),
        'mh_color_link_hover'   => array('name' => 'Link Hover', 'default' => '#ff3366', 'slug' => 'link-hover'),
        'mh_color_link_active'  => array('name' => 'Link Active', 'default' => '#e62050', 'slug' => 'link-active'),
        'mh_color_link_visited' => array('name' => 'Link Visited', 'default' => '#9b59b6', 'slug' => 'link-visited'),
        
        // Text
        'mh_color_text_heading' => array('name' => 'Text Heading', 'default' => '#ffffff', 'slug' => 'text-heading'),
        'mh_color_text_main'    => array('name' => 'Text Main', 'default' => '#f8fafc', 'slug' => 'text-main'),
        'mh_color_text_muted'   => array('name' => 'Text Muted', 'default' => '#94a3b8', 'slug' => 'text-muted'),
        'mh_color_text_inverse' => array('name' => 'Text Inverse', 'default' => '#0f172a', 'slug' => 'text-inverse'),
        
        // Surfaces & Layers
        'mh_color_body'         => array('name' => 'Body (Base)', 'default' => '#0a0b10', 'slug' => 'surface-body'),
        'mh_color_main'         => array('name' => 'Main Background', 'default' => '#0f172a', 'slug' => 'surface-main'),
        'mh_color_section'      => array('name' => 'Section Layer', 'default' => 'rgba(255, 255, 255, 0.02)', 'slug' => 'surface-section'),
        'mh_color_card'         => array('name' => 'Card Layer', 'default' => 'rgba(255, 255, 255, 0.05)', 'slug' => 'surface-card'),
        
        // Status System
        'mh_color_success'      => array('name' => 'Status Success', 'default' => '#10b981', 'slug' => 'status-success'),
        'mh_color_warning'      => array('name' => 'Status Warning', 'default' => '#f59e0b', 'slug' => 'status-warning'),
        'mh_color_danger'       => array('name' => 'Status Danger', 'default' => '#ef4444', 'slug' => 'status-danger'),
        'mh_color_info'         => array('name' => 'Status Info', 'default' => '#3b82f6', 'slug' => 'status-info'),
    );

    $palette = array();
    foreach ($colors as $key => $data) {
        $palette[] = array(
            'name'  => $data['name'],
            'slug'  => $data['slug'],
            'color' => get_theme_mod($key, $data['default']),
        );
    }

    add_theme_support('editor-color-palette', $palette);
    
    // Only disable custom colors if the option is checked in Customizer
    if (get_theme_mod('mh_enforce_site_colors', false)) {
        add_theme_support('disable-custom-colors');
    }
}
add_action('after_setup_theme', 'xophz_magic_hat_gutenberg_palette');
