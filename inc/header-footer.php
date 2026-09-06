<?php
/**
 * Magic Hat Unified Header & Footer Engine
 *
 * Provides dynamic layout rendering, responsive navigation with mobile hamburger drawer,
 * strict menu location discipline, and dynamic block registrations for Full Site Editing.
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register dynamic Gutenberg blocks for Header and Footer
 */
function mh_register_header_footer_blocks() {
	if ( function_exists( 'register_block_type' ) ) {
		register_block_type( 'xophz-magic-hat/header', array(
			'render_callback' => 'mh_render_header_block',
			'editor_script'   => 'magic-hat-editor-blocks',
		) );

		register_block_type( 'xophz-magic-hat/footer', array(
			'render_callback' => 'mh_render_footer_block',
			'editor_script'   => 'magic-hat-editor-blocks',
		) );
	}
}
add_action( 'init', 'mh_register_header_footer_blocks' );

/**
 * Block render callback for Header
 */
function mh_render_header_block() {
	return mh_get_header_markup();
}

/**
 * Block render callback for Footer
 */
function mh_render_footer_block() {
	return mh_get_footer_markup();
}

/**
 * Fallback callback for primary navigation when no menu is assigned.
 * Strictly prevents dumping all site pages into navigation.
 */
function mh_default_nav_fallback() {
	echo '<ul class="mh-nav-menu">';
	echo '<li><a href="#about">' . esc_html__( 'About', 'xophz-magic-hat' ) . '</a></li>';
	echo '<li><a href="#services">' . esc_html__( 'Services', 'xophz-magic-hat' ) . '</a></li>';
	echo '<li><a href="#portfolio">' . esc_html__( 'Portfolio', 'xophz-magic-hat' ) . '</a></li>';
	echo '<li><a href="#contact">' . esc_html__( 'Contact', 'xophz-magic-hat' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Render navigation menu items
 */
function mh_render_nav_items( $menu_id_setting = 0, $is_mobile = false ) {
	$menu_args = array(
		'container'       => false,
		'menu_class'      => $is_mobile ? 'mh-mobile-menu-list' : 'mh-nav-menu',
		'fallback_cb'     => 'mh_default_nav_fallback',
		'depth'           => 2,
	);

	if ( ! empty( $menu_id_setting ) && $menu_id_setting !== '_primary' && is_nav_menu( $menu_id_setting ) ) {
		$menu_args['menu'] = $menu_id_setting;
	} else {
		$menu_args['theme_location'] = 'primary';
	}

	wp_nav_menu( $menu_args );
}

/**
 * Helper to split navigation items into two halves for Split Layout
 */
function mh_render_split_nav_items( $part = 'left', $menu_id_setting = 0 ) {
	$locations = get_nav_menu_locations();
	$menu = null;

	if ( ! empty( $menu_id_setting ) && $menu_id_setting !== '_primary' && is_nav_menu( $menu_id_setting ) ) {
		$menu = wp_get_nav_menu_object( $menu_id_setting );
	} elseif ( isset( $locations['primary'] ) ) {
		$menu = wp_get_nav_menu_object( $locations['primary'] );
	}

	if ( ! $menu ) {
		echo '<ul class="mh-nav-menu">';
		if ( $part === 'left' ) {
			echo '<li><a href="#about">' . esc_html__( 'About', 'xophz-magic-hat' ) . '</a></li>';
			echo '<li><a href="#services">' . esc_html__( 'Services', 'xophz-magic-hat' ) . '</a></li>';
		} else {
			echo '<li><a href="#portfolio">' . esc_html__( 'Portfolio', 'xophz-magic-hat' ) . '</a></li>';
			echo '<li><a href="#contact">' . esc_html__( 'Contact', 'xophz-magic-hat' ) . '</a></li>';
		}
		echo '</ul>';
		return;
	}

	$items = wp_get_nav_menu_items( $menu->term_id );
	if ( empty( $items ) ) {
		mh_default_nav_fallback();
		return;
	}

	// Filter top-level items
	$top_items = array();
	foreach ( $items as $item ) {
		if ( empty( $item->menu_item_parent ) ) {
			$top_items[] = $item;
		}
	}

	$half = (int) ceil( count( $top_items ) / 2 );
	$slice = ( $part === 'left' ) ? array_slice( $top_items, 0, $half ) : array_slice( $top_items, $half );

	echo '<ul class="mh-nav-menu">';
	foreach ( $slice as $item ) {
		echo '<li><a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Return Header HTML markup string
 */
function mh_get_header_markup() {
	ob_start();
	mh_render_header_markup();
	return ob_get_clean();
}

/**
 * Output Header HTML markup
 */
function mh_render_header_markup() {
	$layout       = get_theme_mod( 'mh_header_layout', 'standard' );
	$sticky       = get_theme_mod( 'mh_header_sticky', true );
	$width_mode   = get_theme_mod( 'mh_header_width', 'contained' );
	$menu_setting = get_theme_mod( 'mh_header_menu', '_primary' );
	$show_cta     = get_theme_mod( 'mh_header_show_cta', true );
	$cta_text     = get_theme_mod( 'mh_header_cta_text', __( 'Get Started', 'xophz-magic-hat' ) );
	$cta_url      = get_theme_mod( 'mh_header_cta_url', '#contact' );

	$header_classes = array( 'mh-header', 'mh-header-layout-' . sanitize_html_class( $layout ) );
	if ( $sticky ) {
		$header_classes[] = 'mh-header-sticky';
	}

	$container_class = ( $width_mode === 'full' ) ? 'mh-header-container-full' : 'mh-header-container-contained';
	?>
	<header id="mw-header" class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>" data-mw-type="header">
		<div class="mh-header-inner <?php echo esc_attr( $container_class ); ?>">
			
			<?php if ( $layout === 'centered' ) : ?>
				<!-- Centered Layout: Logo on Top, Nav + CTA Below -->
				<div class="mh-header-row mh-header-row-top">
					<div class="mh-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mh-logo-link">
							<?php if ( has_site_icon() ) : ?>
								<img src="<?php echo esc_url( get_site_icon_url( 96 ) ); ?>" alt="Logo" class="mh-logo-img" />
							<?php else : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" class="mh-logo-img" />
							<?php endif; ?>
							<span class="mh-site-title"><?php bloginfo( 'name' ); ?></span>
						</a>
					</div>
					<button type="button" class="mh-hamburger" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
						<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
					</button>
				</div>
				<div class="mh-header-row mh-header-row-bottom mh-desktop-nav-wrap">
					<nav class="mh-desktop-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'xophz-magic-hat' ); ?>">
						<?php mh_render_nav_items( $menu_setting ); ?>
					</nav>
					<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
						<div class="mh-header-cta-wrap">
							<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta"><?php echo esc_html( $cta_text ); ?></a>
						</div>
					<?php endif; ?>
				</div>

			<?php elseif ( $layout === 'split' ) : ?>
				<!-- Split Layout: Half Nav Left, Logo Center, Half Nav + CTA Right -->
				<div class="mh-header-row mh-header-split-row">
					<nav class="mh-desktop-nav mh-nav-split-left" aria-label="<?php esc_attr_e( 'Left Navigation', 'xophz-magic-hat' ); ?>">
						<?php mh_render_split_nav_items( 'left', $menu_setting ); ?>
					</nav>

					<div class="mh-logo mh-logo-center">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mh-logo-link">
							<?php if ( has_site_icon() ) : ?>
								<img src="<?php echo esc_url( get_site_icon_url( 96 ) ); ?>" alt="Logo" class="mh-logo-img" />
							<?php else : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" class="mh-logo-img" />
							<?php endif; ?>
							<span class="mh-site-title"><?php bloginfo( 'name' ); ?></span>
						</a>
					</div>

					<div class="mh-split-right-wrap">
						<nav class="mh-desktop-nav mh-nav-split-right" aria-label="<?php esc_attr_e( 'Right Navigation', 'xophz-magic-hat' ); ?>">
							<?php mh_render_split_nav_items( 'right', $menu_setting ); ?>
						</nav>
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<div class="mh-header-cta-wrap">
								<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta"><?php echo esc_html( $cta_text ); ?></a>
							</div>
						<?php endif; ?>
						<button type="button" class="mh-hamburger" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
							<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
						</button>
					</div>
				</div>

			<?php elseif ( $layout === 'minimal' ) : ?>
				<!-- Minimal Layout: Logo Left, CTA + Hamburger Right -->
				<div class="mh-header-row mh-header-minimal-row">
					<div class="mh-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mh-logo-link">
							<?php if ( has_site_icon() ) : ?>
								<img src="<?php echo esc_url( get_site_icon_url( 96 ) ); ?>" alt="Logo" class="mh-logo-img" />
							<?php else : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" class="mh-logo-img" />
							<?php endif; ?>
							<span class="mh-site-title"><?php bloginfo( 'name' ); ?></span>
						</a>
					</div>
					<div class="mh-minimal-actions">
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta mh-cta-minimal"><?php echo esc_html( $cta_text ); ?></a>
						<?php endif; ?>
						<button type="button" class="mh-hamburger mh-hamburger-always" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
							<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
						</button>
					</div>
				</div>

			<?php else : ?>
				<!-- Standard Layout: Logo Left, Nav Center/Right, CTA Far Right -->
				<div class="mh-header-row mh-header-standard-row">
					<div class="mh-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mh-logo-link">
							<?php if ( has_site_icon() ) : ?>
								<img src="<?php echo esc_url( get_site_icon_url( 96 ) ); ?>" alt="Logo" class="mh-logo-img" />
							<?php else : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" class="mh-logo-img" />
							<?php endif; ?>
							<span class="mh-site-title"><?php bloginfo( 'name' ); ?></span>
						</a>
					</div>

					<nav class="mh-desktop-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'xophz-magic-hat' ); ?>">
						<?php mh_render_nav_items( $menu_setting ); ?>
					</nav>

					<div class="mh-header-right-actions">
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<div class="mh-header-cta-wrap">
								<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta"><?php echo esc_html( $cta_text ); ?></a>
							</div>
						<?php endif; ?>
						<button type="button" class="mh-hamburger" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
							<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
						</button>
					</div>
				</div>
			<?php endif; ?>

		</div>

		<!-- Mobile Navigation Slide-Out Drawer -->
		<div class="mh-mobile-nav" id="mh-mobile-nav" aria-hidden="true">
			<div class="mh-mobile-nav-backdrop" id="mh-mobile-backdrop"></div>
			<div class="mh-mobile-nav-drawer">
				<div class="mh-mobile-nav-top">
					<span class="mh-mobile-nav-brand"><?php bloginfo( 'name' ); ?></span>
					<button type="button" class="mh-mobile-nav-close" id="mh-mobile-close" aria-label="<?php esc_attr_e( 'Close navigation', 'xophz-magic-hat' ); ?>">&times;</button>
				</div>
				<nav class="mh-mobile-nav-body" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'xophz-magic-hat' ); ?>">
					<?php mh_render_nav_items( $menu_setting, true ); ?>
				</nav>
				<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
					<div class="mh-mobile-nav-footer">
						<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta mh-mobile-cta"><?php echo esc_html( $cta_text ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</header>
	<?php
}

/**
 * Return Footer HTML markup string
 */
function mh_get_footer_markup() {
	ob_start();
	mh_render_footer_markup();
	return ob_get_clean();
}

/**
 * Output Footer HTML markup
 */
function mh_render_footer_markup() {
	$layout       = get_theme_mod( 'mh_footer_layout', 'columns_4' );
	$bg_style     = get_theme_mod( 'mh_footer_bg', 'surface_section' );
	$show_menus   = get_theme_mod( 'mh_footer_show_menus', true );
	$raw_copy     = get_theme_mod( 'mh_footer_copyright_text', '&copy; {year} {site_title}. All rights reserved.' );

	$copyright_text = str_replace(
		array( '{year}', '{site_title}' ),
		array( date( 'Y' ), get_bloginfo( 'name' ) ),
		$raw_copy
	);

	$footer_classes = array(
		'mh-footer',
		'mh-footer-layout-' . sanitize_html_class( $layout ),
		'mh-footer-bg-' . sanitize_html_class( $bg_style ),
	);

	$footer_locations = array(
		'footer_1' => __( 'Explore', 'xophz-magic-hat' ),
		'footer_2' => __( 'Resources', 'xophz-magic-hat' ),
		'footer_3' => __( 'Legal', 'xophz-magic-hat' ),
		'footer_4' => __( 'Contact', 'xophz-magic-hat' ),
	);
	?>
	<footer id="mw-footer" class="<?php echo esc_attr( implode( ' ', $footer_classes ) ); ?>" data-mw-type="footer">
		<div class="mh-footer-container">

			<?php if ( $layout === 'minimal_centered' ) : ?>
				<!-- Centered Minimal Footer -->
				<div class="mh-footer-centered-wrap">
					<div class="mh-footer-brand-center">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mh-footer-logo-link">
							<?php if ( has_site_icon() ) : ?>
								<img src="<?php echo esc_url( get_site_icon_url( 96 ) ); ?>" alt="Logo" class="mh-footer-logo-img" />
							<?php else : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" class="mh-footer-logo-img" />
							<?php endif; ?>
							<span class="mh-footer-site-name"><?php bloginfo( 'name' ); ?></span>
						</a>
						<?php if ( get_bloginfo( 'description' ) ) : ?>
							<p class="mh-footer-tagline"><?php bloginfo( 'description' ); ?></p>
						<?php endif; ?>
					</div>

					<nav class="mh-footer-inline-nav" aria-label="<?php esc_attr_e( 'Footer Navigation', 'xophz-magic-hat' ); ?>">
						<?php 
						if ( has_nav_menu( 'primary' ) ) {
							wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'mh-inline-menu', 'depth' => 1, 'fallback_cb' => false ) );
						} elseif ( has_nav_menu( 'footer_1' ) ) {
							wp_nav_menu( array( 'theme_location' => 'footer_1', 'container' => false, 'menu_class' => 'mh-inline-menu', 'depth' => 1, 'fallback_cb' => false ) );
						} else {
							echo '<ul class="mh-inline-menu">';
							echo '<li><a href="#about">' . esc_html__( 'About', 'xophz-magic-hat' ) . '</a></li>';
							echo '<li><a href="#services">' . esc_html__( 'Services', 'xophz-magic-hat' ) . '</a></li>';
							echo '<li><a href="#portfolio">' . esc_html__( 'Portfolio', 'xophz-magic-hat' ) . '</a></li>';
							echo '<li><a href="#contact">' . esc_html__( 'Contact', 'xophz-magic-hat' ) . '</a></li>';
							echo '</ul>';
						}
						?>
					</nav>

					<?php mh_render_footer_social_icons(); ?>

					<div class="mh-footer-bottom-bar">
						<p class="mh-copyright-text"><?php echo wp_kses_post( $copyright_text ); ?></p>
					</div>
				</div>

			<?php elseif ( $layout === 'split' ) : ?>
				<!-- Split Modern Footer -->
				<div class="mh-footer-split-wrap">
					<div class="mh-footer-split-left">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mh-footer-logo-link">
							<?php if ( has_site_icon() ) : ?>
								<img src="<?php echo esc_url( get_site_icon_url( 96 ) ); ?>" alt="Logo" class="mh-footer-logo-img" />
							<?php else : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" class="mh-footer-logo-img" />
							<?php endif; ?>
							<span class="mh-footer-site-name"><?php bloginfo( 'name' ); ?></span>
						</a>
						<?php if ( get_bloginfo( 'description' ) ) : ?>
							<p class="mh-footer-tagline"><?php bloginfo( 'description' ); ?></p>
						<?php endif; ?>
						<p class="mh-copyright-text"><?php echo wp_kses_post( $copyright_text ); ?></p>
					</div>
					<div class="mh-footer-split-right">
						<nav class="mh-footer-inline-nav" aria-label="<?php esc_attr_e( 'Footer Navigation', 'xophz-magic-hat' ); ?>">
							<?php 
							if ( has_nav_menu( 'footer_1' ) ) {
								wp_nav_menu( array( 'theme_location' => 'footer_1', 'container' => false, 'menu_class' => 'mh-inline-menu', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								mh_default_nav_fallback();
							}
							?>
						</nav>
						<?php mh_render_footer_social_icons(); ?>
					</div>
				</div>

			<?php else : ?>
				<!-- Column Footer: 3 or 4 Columns -->
				<?php 
				$col_limit = ( $layout === 'columns_3' ) ? 2 : 4;
				?>
				<div class="mh-footer-grid mh-footer-cols-<?php echo esc_attr( $col_limit + 1 ); ?>">
					<!-- Brand Column -->
					<div class="mh-footer-col mh-footer-brand-col">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mh-footer-logo-link">
							<?php if ( has_site_icon() ) : ?>
								<img src="<?php echo esc_url( get_site_icon_url( 128 ) ); ?>" alt="Logo" class="mh-footer-logo-img" />
							<?php else : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" class="mh-footer-logo-img" />
							<?php endif; ?>
							<span class="mh-footer-site-name"><?php bloginfo( 'name' ); ?></span>
						</a>
						<?php if ( get_bloginfo( 'description' ) ) : ?>
							<p class="mh-footer-tagline"><?php bloginfo( 'description' ); ?></p>
						<?php endif; ?>
						<?php mh_render_footer_social_icons(); ?>
					</div>

					<?php if ( $show_menus ) : ?>
						<?php 
						$locations = get_nav_menu_locations();
						$rendered = 0;
						foreach ( $footer_locations as $loc_slug => $default_heading ) :
							if ( $rendered >= $col_limit ) break;
							$rendered++;
							
							$menu_name = $default_heading;
							if ( isset( $locations[ $loc_slug ] ) && $locations[ $loc_slug ] ) {
								$menu_obj = wp_get_nav_menu_object( $locations[ $loc_slug ] );
								if ( $menu_obj && ! empty( $menu_obj->name ) ) {
									$menu_name = $menu_obj->name;
								}
							}
						?>
							<div class="mh-footer-col mh-footer-nav-col">
								<h4 class="mh-footer-heading"><?php echo esc_html( $menu_name ); ?></h4>
								<?php 
								if ( has_nav_menu( $loc_slug ) ) {
									wp_nav_menu( array(
										'theme_location' => $loc_slug,
										'container'      => false,
										'menu_class'     => 'mh-footer-menu-list',
										'fallback_cb'    => false,
										'depth'          => 1,
									) );
								} else {
									echo '<ul class="mh-footer-menu-list">';
									if ( $loc_slug === 'footer_1' ) {
										echo '<li><a href="#about">' . esc_html__( 'About Us', 'xophz-magic-hat' ) . '</a></li>';
										echo '<li><a href="#features">' . esc_html__( 'Features', 'xophz-magic-hat' ) . '</a></li>';
										echo '<li><a href="#portfolio">' . esc_html__( 'Our Work', 'xophz-magic-hat' ) . '</a></li>';
										echo '<li><a href="#pricing">' . esc_html__( 'Pricing', 'xophz-magic-hat' ) . '</a></li>';
									} elseif ( $loc_slug === 'footer_2' ) {
										echo '<li><a href="#">' . esc_html__( 'Help Center', 'xophz-magic-hat' ) . '</a></li>';
										echo '<li><a href="#">' . esc_html__( 'Documentation', 'xophz-magic-hat' ) . '</a></li>';
										echo '<li><a href="#">' . esc_html__( 'Community', 'xophz-magic-hat' ) . '</a></li>';
										echo '<li><a href="#">' . esc_html__( 'Guides', 'xophz-magic-hat' ) . '</a></li>';
									} elseif ( $loc_slug === 'footer_3' ) {
										echo '<li><a href="#">' . esc_html__( 'Privacy Policy', 'xophz-magic-hat' ) . '</a></li>';
										echo '<li><a href="#">' . esc_html__( 'Terms of Service', 'xophz-magic-hat' ) . '</a></li>';
										echo '<li><a href="#">' . esc_html__( 'Security', 'xophz-magic-hat' ) . '</a></li>';
									} else {
										echo '<li><a href="#">' . esc_html__( 'Contact Sales', 'xophz-magic-hat' ) . '</a></li>';
										echo '<li><a href="#">' . esc_html__( 'Support Desk', 'xophz-magic-hat' ) . '</a></li>';
									}
									echo '</ul>';
								}
								?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<div class="mh-footer-bottom-bar">
					<p class="mh-copyright-text"><?php echo wp_kses_post( $copyright_text ); ?></p>
					<a href="#mw-header" class="mh-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'xophz-magic-hat' ); ?>">
						<span class="dashicons dashicons-arrow-up-alt2"></span>
					</a>
				</div>
			<?php endif; ?>

		</div>
	</footer>
	<?php
}

/**
 * Output Social Icons for Footer
 */
function mh_render_footer_social_icons() {
	$networks = array(
		'facebook'  => array( 'label' => 'Facebook', 'icon' => 'facebook' ),
		'twitter'   => array( 'label' => 'Twitter', 'icon' => 'twitter' ),
		'instagram' => array( 'label' => 'Instagram', 'icon' => 'instagram' ),
		'linkedin'  => array( 'label' => 'LinkedIn', 'icon' => 'linkedin' ),
		'youtube'   => array( 'label' => 'YouTube', 'icon' => 'youtube' ),
		'github'    => array( 'label' => 'GitHub', 'icon' => 'admin-generic' ),
	);

	$found = array();
	foreach ( $networks as $key => $info ) {
		$url = get_theme_mod( 'mh_social_' . $key );
		if ( ! empty( $url ) ) {
			$found[ $key ] = array( 'url' => $url, 'info' => $info );
		}
	}

	if ( empty( $found ) ) {
		return;
	}

	echo '<div class="mh-footer-social-wrap">';
	foreach ( $found as $key => $data ) {
		echo '<a href="' . esc_url( $data['url'] ) . '" target="_blank" rel="noopener noreferrer" class="mh-social-btn mh-social-' . esc_attr( $key ) . '" aria-label="' . esc_attr( $data['info']['label'] ) . '">';
		echo '<span class="dashicons dashicons-' . esc_attr( $data['info']['icon'] ) . '"></span>';
		echo '</a>';
	}
	echo '</div>';
}
