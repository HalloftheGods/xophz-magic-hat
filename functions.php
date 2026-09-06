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
    add_editor_style( 'assets/css/sections/hero-overlap.css' );
    add_editor_style( 'assets/css/sections/content-about.css' );
    add_editor_style( 'assets/css/sections/features-numbers.css' );
    add_editor_style( 'assets/css/sections/team-testimonials.css' );
    add_editor_style( 'assets/css/sections/cta-contact.css' );
    add_editor_style( 'assets/css/sections/pricing-portfolio.css' );

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
    // Enqueue Dashicons and FontAwesome for icons
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'magic-hat-font-awesome', get_template_directory_uri() . '/assets/font-awesome/font-awesome.min.css', array(), '4.7.0' );

    // Enqueue the foundational Design Tokens
    wp_enqueue_style( 'magic-hat-variables', get_template_directory_uri() . '/assets/css/variables.css', array(), wp_get_theme()->get('Version') );
    
    // Enqueue Header & Footer styles
    wp_enqueue_style( 'magic-hat-header-footer', get_template_directory_uri() . '/assets/css/header-footer.css', array('magic-hat-variables'), wp_get_theme()->get('Version') );

    // Enqueue modular section stylesheets
    $section_categories = array(
        'hero-overlap',
        'content-about',
        'features-numbers',
        'team-testimonials',
        'cta-contact',
        'pricing-portfolio',
    );
    $section_handles = array();

    foreach ( $section_categories as $category ) {
        $handle = 'magic-hat-section-' . $category;
        wp_enqueue_style(
            $handle,
            get_template_directory_uri() . '/assets/css/sections/' . $category . '.css',
            array( 'magic-hat-variables', 'magic-hat-font-awesome' ),
            wp_get_theme()->get( 'Version' )
        );
        $section_handles[] = $handle;
    }

    // Enqueue the main stylesheet
    wp_enqueue_style(
        'magic-hat-style',
        get_stylesheet_uri(),
        array_merge( array( 'magic-hat-variables', 'magic-hat-header-footer' ), $section_handles ),
        wp_get_theme()->get( 'Version' )
    );

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
 * Register Gutenberg Block Editor Script Handle
 */
function xophz_magic_hat_register_block_editor_assets() {
    wp_register_script(
        'magic-hat-editor-blocks',
        get_template_directory_uri() . '/assets/js/editor-blocks.js',
        array( 'wp-blocks', 'wp-element', 'wp-server-side-render', 'wp-i18n' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'init', 'xophz_magic_hat_register_block_editor_assets', 5 );

/**
 * Enqueue scripts and styles for Gutenberg Site Editor & Block Editor
 */
function xophz_magic_hat_block_editor_assets() {
    wp_enqueue_script( 'magic-hat-editor-blocks' );

    wp_enqueue_style(
        'magic-hat-header-footer-editor',
        get_template_directory_uri() . '/assets/css/header-footer.css',
        array( 'magic-hat-variables' ),
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'enqueue_block_editor_assets', 'xophz_magic_hat_block_editor_assets' );

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

    $color_defs = mh_get_color_definitions();

    $palette = array();
    foreach ( $color_defs as $key => $data ) {
        $setting_base = 'mh_color_' . str_replace( '-', '_', $key );
        $setting_key  = $setting_base;
        $default_val  = $data['light'];

        if ( $phase === 'dark' ) {
            $setting_key = $setting_base . '_dark';
            $default_val = $data['dark'];
        } elseif ( $phase === 'twilight' ) {
            $setting_key = $setting_base . '_twilight';
            $default_val = $data['twilight'];
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
