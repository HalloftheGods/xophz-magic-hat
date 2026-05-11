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
        
        .mh-hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; z-index: 1001; }
        .mh-hamburger span { width: 25px; height: 2px; background: #fff; transition: all 0.3s; }
        
        @media (max-width: 768px) {
            .mh-header { padding: 15px 20px; }
            .mh-hamburger { display: flex; }
            .mh-nav { position: absolute; top: 100%; left: 0; right: 0; background: rgba(26,27,38,0.98); backdrop-filter: blur(12px); flex-direction: column; gap: 20px; padding: 30px 20px; border-top: 1px solid rgba(255,255,255,0.05); display: none; align-items: center; }
            .mh-nav.active { display: flex; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
            .mh-nav ul { flex-direction: column; align-items: center; width: 100%; gap: 20px; }
        }
        
        body { display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        
        /* Footer - Mobile First Base */
        .mh-footer { background: var(--mh-color-bg-alt, #0f172a); border-top: 1px solid rgba(255,255,255,0.05); padding: 40px 20px 30px; color: rgba(255,255,255,0.6); font-family: var(--mh-font-body, sans-serif); margin-top: auto; }
        .mh-footer-grid { display: grid; grid-template-columns: 1fr; gap: 40px; max-width: var(--mh-content-width, 1200px); margin: 0 auto; text-align: center; }
        .mh-footer-grid > div { display: flex; flex-direction: column; align-items: center; }
        .mh-footer h4 { color: #fff; font-size: 16px; margin-bottom: 20px; font-family: var(--mh-font-heading, sans-serif); }
        .mh-footer ul { list-style: none; padding: 0; margin: 0; }
        .mh-footer ul li { margin-bottom: 12px; }
        .mh-footer a { color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.2s; }
        .mh-footer a:hover { color: #62c9ff; }
        .mh-footer-bottom { max-width: var(--mh-content-width, 1200px); margin: 40px auto 0; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; flex-direction: column; justify-content: center; align-items: center; font-size: 13px; gap: 20px; text-align: center; }
        .mh-footer-brand .customize-partial-edit-shortcut { position: static !important; order: 99; margin-top: 12px; }
        .mh-footer-bottom .customize-partial-edit-shortcut { position: absolute !important; left: 50% !important; top: -15px !important; transform: translateX(-50%); }

        /* Footer - Tablet (min-width: 481px) */
        @media (min-width: 481px) {
            .mh-footer-grid { grid-template-columns: 1fr 1fr; }
        }

        /* Footer - Desktop (min-width: 769px) */
        @media (min-width: 769px) {
            .mh-footer { padding: 60px 40px 30px; }
            .mh-footer-grid { grid-template-columns: 1.5fr 1fr 1fr 1fr 1fr; text-align: left; }
            .mh-footer-grid > div { align-items: flex-start; }
            .mh-footer-bottom { flex-direction: row; justify-content: space-between; text-align: left; }
        }
    </style>
</head>
<body <?php body_class(); ?> data-mw-canvas="true">
    <?php wp_body_open(); ?>
    <header id="mw-header" class="mh-header mw-template-part mw-dropzone" data-mw-type="header">
        <div class="mh-logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
                <?php if ( has_site_icon() ) : ?>
                    <img src="<?php echo esc_url( get_site_icon_url() ); ?>" alt="Logo" style="height: 36px; width: auto; filter: drop-shadow(0 0 8px rgba(98,201,255,0.5)); border-radius: 4px;" />
                <?php else : ?>
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" style="height: 36px; width: auto; filter: drop-shadow(0 0 8px rgba(98,201,255,0.5));" />
                <?php endif; ?>
                <span class="site-title" style="font-size: 22px; font-weight: 700; color: #fff; font-family: var(--mh-font-heading, sans-serif); letter-spacing: 0.5px;"><?php bloginfo('name'); ?></span>
            </a>
        </div>
        <div class="mh-hamburger" id="mh-hamburger">
            <span></span>
            <span></span>
            <span></span>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var hamburger = document.getElementById('mh-hamburger');
            var nav = document.querySelector('.mh-nav');
            if (hamburger && nav) {
                hamburger.addEventListener('click', function() {
                    nav.classList.toggle('active');
                });
            }
        });
    </script>
