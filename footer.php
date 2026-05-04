<?php
/**
 * Magic Hat Footer
 */
?>
    <footer id="mw-footer" class="mh-footer mw-template-part mw-dropzone" data-mw-type="footer">
        <div class="mh-footer-grid">
            <div>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" style="height: 28px; width: auto; filter: drop-shadow(0 0 6px rgba(98,201,255,0.4)); opacity: 0.8;" />
                    <span style="font-size: 18px; font-weight: 700; color: rgba(255,255,255,0.9); font-family: var(--mh-font-heading, sans-serif);"><?php bloginfo('name'); ?></span>
                </a>
                <p style="font-size: 14px; line-height: 1.6; max-width: 250px;">Empowering creators with a magical, beautiful, and effortless web building experience.</p>
            </div>
            <div>
                <h4>Explore</h4>
                <?php 
                if ( has_nav_menu( 'footer' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'container' => false,
                        'menu_class' => 'mh-footer-menu',
                        'fallback_cb' => false
                    ) );
                } else {
                    echo '<ul>';
                    echo '<li><a href="#about">About Us</a></li>';
                    echo '<li><a href="#features">Features</a></li>';
                    echo '<li><a href="#portfolio">Our Work</a></li>';
                    echo '<li><a href="#pricing">Pricing</a></li>';
                    echo '</ul>';
                }
                ?>
            </div>
            <div>
                <h4>Resources</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Community</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </div>
            <div>
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="mh-footer-bottom">
            <div>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</div>
            <div style="display: flex; gap: 15px;">
                <a href="#" style="font-size: 16px; color: rgba(255,255,255,0.6); text-decoration: none;">X</a>
                <a href="#" style="font-size: 16px; color: rgba(255,255,255,0.6); text-decoration: none;">in</a>
                <a href="#" style="font-size: 16px; color: rgba(255,255,255,0.6); text-decoration: none;">GH</a>
            </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
