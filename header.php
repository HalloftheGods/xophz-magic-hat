<?php
/**
 * Magic Hat Header
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <style>
        .mh-header { position: sticky; top: 0; z-index: 1000; background: rgba(26,27,38,0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; }
        .mh-nav { display: flex; gap: 30px; align-items: center; }
        .mh-nav ul { list-style: none; margin: 0; padding: 0; display: flex; gap: 30px; }
        .mh-nav a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 14px; font-weight: 500; font-family: var(--mh-font-body, sans-serif); transition: color 0.2s, text-shadow 0.2s; }
        .mh-nav a:hover { color: #62c9ff; text-shadow: 0 0 8px rgba(98,201,255,0.4); }
        .mh-header-cta { background: rgba(98,201,255,0.1) !important; border: 1px solid rgba(98,201,255,0.5) !important; color: #62c9ff !important; padding: 8px 24px !important; border-radius: 4px; font-weight: 600 !important; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s !important; }
        .mh-header-cta:hover { background: rgba(98,201,255,0.2) !important; box-shadow: 0 0 15px rgba(98,201,255,0.2) !important; border-color: #62c9ff !important; }
        
        .mh-footer { background: var(--mh-color-bg-alt, #0f172a); border-top: 1px solid rgba(255,255,255,0.05); padding: 60px 40px 30px; color: rgba(255,255,255,0.6); font-family: var(--mh-font-body, sans-serif); }
        .mh-footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; max-width: var(--mh-content-width, 1200px); margin: 0 auto; }
        .mh-footer h4 { color: #fff; font-size: 16px; margin-bottom: 20px; font-family: var(--mh-font-heading, sans-serif); }
        .mh-footer ul { list-style: none; padding: 0; margin: 0; }
        .mh-footer ul li { margin-bottom: 12px; }
        .mh-footer a { color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.2s; }
        .mh-footer a:hover { color: #62c9ff; }
        .mh-footer-bottom { max-width: var(--mh-content-width, 1200px); margin: 40px auto 0; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; font-size: 13px; }
    </style>
</head>
<body <?php body_class(); ?> data-mw-canvas="true">
    <?php wp_body_open(); ?>
    <header id="mw-header" class="mh-header mw-template-part mw-dropzone" data-mw-type="header">
        <div class="mh-logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" style="height: 36px; width: auto; filter: drop-shadow(0 0 8px rgba(98,201,255,0.5));" />
                <span class="site-title" style="font-size: 22px; font-weight: 700; color: #fff; font-family: var(--mh-font-heading, sans-serif); letter-spacing: 0.5px;"><?php bloginfo('name'); ?></span>
            </a>
        </div>
        <nav class="mh-nav">
            <?php 
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'mh-nav-menu',
                    'fallback_cb' => false
                ) );
            } else {
                echo '<ul>';
                echo '<li><a href="#about">About</a></li>';
                echo '<li><a href="#services">Services</a></li>';
                echo '<li><a href="#portfolio">Portfolio</a></li>';
                echo '<li><a href="#team">Team</a></li>';
                echo '</ul>';
            }
            ?>
            <a href="#contact" class="mh-header-cta">Get Started</a>
        </nav>
    </header>
