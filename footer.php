<?php
/**
 * Magic Hat Footer
 */
?>
    <footer id="mw-footer" class="mh-footer mw-template-part mw-dropzone" data-mw-type="footer">
        <div class="mh-footer-grid">
            <div class="mh-footer-brand" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; position: relative;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <?php if ( has_site_icon() ) : ?>
                        <img src="<?php echo esc_url( get_site_icon_url( 256 ) ); ?>" alt="Logo" style="height: 128px; width: 128px; object-fit: contain; filter: drop-shadow(0 0 6px rgba(98,201,255,0.4)); opacity: 0.8; border-radius: 12px;" />
                    <?php else : ?>
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" style="height: 128px; width: 128px; object-fit: contain; filter: drop-shadow(0 0 6px rgba(98,201,255,0.4)); opacity: 0.8;" />
                    <?php endif; ?>
                    <span class="mh-footer-site-name" style="font-size: 18px; font-weight: 700; color: rgba(255,255,255,0.9); font-family: var(--mh-font-heading, sans-serif);"><?php bloginfo('name'); ?></span>
                </a>
                <p class="mh-footer-tagline" style="font-size: 14px; line-height: 1.6; max-width: 250px; margin: 0;"><?php bloginfo('description'); ?></p>
            </div>
            <div>
                <?php 
                if ( has_nav_menu( 'footer_1' ) ) {
                    $locations = get_nav_menu_locations();
                    $menu = wp_get_nav_menu_object( $locations['footer_1'] );
                    echo '<h4>' . esc_html( $menu ? $menu->name : 'Explore' ) . '</h4>';
                    wp_nav_menu( array(
                        'theme_location' => 'footer_1',
                        'container' => false,
                        'menu_class' => 'mh-footer-menu',
                        'fallback_cb' => false
                    ) );
                } else {
                    echo '<h4>Explore</h4>';
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
                <?php 
                if ( has_nav_menu( 'footer_2' ) ) {
                    $locations = get_nav_menu_locations();
                    $menu = wp_get_nav_menu_object( $locations['footer_2'] );
                    echo '<h4>' . esc_html( $menu ? $menu->name : 'Resources' ) . '</h4>';
                    wp_nav_menu( array(
                        'theme_location' => 'footer_2',
                        'container' => false,
                        'menu_class' => 'mh-footer-menu',
                        'fallback_cb' => false
                    ) );
                } else {
                    echo '<h4>Resources</h4>';
                    echo '<ul>';
                    echo '<li><a href="#">Help Center</a></li>';
                    echo '<li><a href="#">Documentation</a></li>';
                    echo '<li><a href="#">Community</a></li>';
                    echo '<li><a href="#">Blog</a></li>';
                    echo '</ul>';
                }
                ?>
            </div>
            <div>
                <?php 
                if ( has_nav_menu( 'footer_3' ) ) {
                    $locations = get_nav_menu_locations();
                    $menu = wp_get_nav_menu_object( $locations['footer_3'] );
                    echo '<h4>' . esc_html( $menu ? $menu->name : 'Legal' ) . '</h4>';
                    wp_nav_menu( array(
                        'theme_location' => 'footer_3',
                        'container' => false,
                        'menu_class' => 'mh-footer-menu',
                        'fallback_cb' => false
                    ) );
                } else {
                    echo '<h4>Legal</h4>';
                    echo '<ul>';
                    echo '<li><a href="#">Privacy Policy</a></li>';
                    echo '<li><a href="#">Terms of Service</a></li>';
                    echo '<li><a href="#">Cookie Policy</a></li>';
                    echo '</ul>';
                }
                ?>
            </div>
            <div>
                <?php 
                if ( has_nav_menu( 'footer_4' ) ) {
                    $locations = get_nav_menu_locations();
                    $menu = wp_get_nav_menu_object( $locations['footer_4'] );
                    echo '<h4>' . esc_html( $menu ? $menu->name : 'Contact' ) . '</h4>';
                    wp_nav_menu( array(
                        'theme_location' => 'footer_4',
                        'container' => false,
                        'menu_class' => 'mh-footer-menu',
                        'fallback_cb' => false
                    ) );
                } else {
                    echo '<h4>Contact</h4>';
                    echo '<ul>';
                    echo '<li><a href="mailto:hello@youmeos.com">hello@youmeos.com</a></li>';
                    echo '<li><a href="tel:+1234567890">+1 (234) 567-890</a></li>';
                    echo '<li><a href="#">Support Desk</a></li>';
                    echo '</ul>';
                }
                ?>
            </div>
        </div>
        <div class="mh-footer-bottom" style="position: relative;">
            <?php 
            if ( function_exists('mh_render_footer_bottom') ) {
                mh_render_footer_bottom(); 
            }
            ?>
        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
