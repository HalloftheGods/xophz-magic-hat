<?php
/**
 * Xophz Blank Slate Functions
 */

// We leave Gutenberg block styles intact so that native content blocks (like galleries or columns) 
// within the_content() don't break. 
// If you specifically want to dequeue theme.json global styles later, you can add it here.

// Disable WP emojis
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Support title tag
add_theme_support( 'title-tag' );

// Support post thumbnails
add_theme_support( 'post-thumbnails' );

// Load Customizer Settings
require_once get_template_directory() . '/inc/customizer.php';
