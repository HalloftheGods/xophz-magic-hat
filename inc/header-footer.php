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

		register_block_type( 'xophz-magic-hat/quantum-atom', array(
			'render_callback' => 'mh_render_quantum_atom_block',
			'editor_script'   => 'magic-hat-editor-blocks',
			'attributes'      => array(
				'atom'  => array(
					'type'    => 'string',
					'default' => 'XBtn',
				),
				'props' => array(
					'type'    => 'object',
					'default' => array(),
				),
			),
		) );
	}
}
add_action( 'init', 'mh_register_header_footer_blocks' );

/**
 * Block render callback for Quantum Atom
 *
 * @param array<string, mixed> $attributes Block attributes.
 * @param string               $content    Block inner content.
 * @return string HTML output mounting the atom container.
 */
function mh_render_quantum_atom_block( $attributes = array(), $content = '' ) {
	$atom  = isset( $attributes['atom'] ) ? sanitize_text_field( $attributes['atom'] ) : 'XBtn';
	$props = isset( $attributes['props'] ) && is_array( $attributes['props'] ) ? $attributes['props'] : array();
	$tag   = strtolower( preg_replace( '/([a-z])([A-Z])/', '$1-$2', $atom ) );

	$props_json = esc_attr( wp_json_encode( $props ) );
	return sprintf(
		'<div class="mh-quantum-atom-container" data-magic-wand-mount data-atom="%s" data-props="%s"><%s>%s</%s></div>',
		esc_attr( $atom ),
		$props_json,
		esc_attr( $tag ),
		$content,
		esc_attr( $tag )
	);
}

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
 * Render brand logo and/or site title according to customizer settings.
 *
 * @param string $location 'header' or 'footer'
 */
function mh_render_brand_logo( $location = 'header' ) {
	$display_mode = get_theme_mod( 'mh_' . $location . '_brand_display', 'both' );
	if ( $display_mode === 'none' ) {
		return;
	}

	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$logo_url = '';
	$logo_alt = get_bloginfo( 'name' );

	if ( ! empty( $custom_logo_id ) ) {
		$logo_data = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		if ( ! empty( $logo_data[0] ) ) {
			$logo_url = $logo_data[0];
		}
	}

	if ( empty( $logo_url ) && has_site_icon() ) {
		$size = ( $location === 'footer' ) ? 128 : 96;
		$logo_url = get_site_icon_url( $size );
	}

	if ( empty( $logo_url ) ) {
		$logo_url = get_template_directory_uri() . '/icon.svg';
	}

	$show_image = ( $display_mode === 'both' || $display_mode === 'logo_only' );
	$show_text  = ( $display_mode === 'both' || $display_mode === 'title_only' );

	$default_h  = ( $location === 'footer' ) ? 40 : 36;
	$height_setting = get_theme_mod( 'mh_' . $location . '_logo_height', $default_h );
	$height_px  = absint( $height_setting ) ? absint( $height_setting ) . 'px' : $default_h . 'px';

	$link_class = ( $location === 'footer' ) ? 'mh-footer-logo-link' : 'mh-logo-link';
	$img_class  = ( $location === 'footer' ) ? 'mh-footer-logo-img' : 'mh-logo-img';
	$title_class = ( $location === 'footer' ) ? 'mh-footer-site-name' : 'mh-site-title';
	?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo esc_attr( $link_class ); ?>" rel="home">
		<?php if ( $show_image && ! empty( $logo_url ) ) : ?>
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" class="<?php echo esc_attr( $img_class ); ?>" style="--mh-logo-h: <?php echo esc_attr( $height_px ); ?>; max-height: <?php echo esc_attr( $height_px ); ?>; height: <?php echo esc_attr( $height_px ); ?>;" />
		<?php endif; ?>
		<?php if ( $show_text ) : ?>
			<span class="<?php echo esc_attr( $title_class ); ?>"><?php bloginfo( 'name' ); ?></span>
		<?php endif; ?>
	</a>
	<?php
}

/**
 * Return JSON encoded list of menu items for quick-search client-side autocomplete.
 *
 * @param string|int $menu_id_setting
 * @return string JSON string
 */
function mh_get_nav_menu_items_json( $menu_id_setting = 0 ) {
	$items_data = array();
	$locations  = get_nav_menu_locations();
	$menu       = null;

	if ( ! empty( $menu_id_setting ) && $menu_id_setting !== '_primary' && is_nav_menu( $menu_id_setting ) ) {
		$menu = wp_get_nav_menu_object( $menu_id_setting );
	} elseif ( isset( $locations['primary'] ) && $locations['primary'] ) {
		$menu = wp_get_nav_menu_object( $locations['primary'] );
	}

	if ( $menu ) {
		$items = wp_get_nav_menu_items( $menu->term_id );
		if ( ! empty( $items ) ) {
			foreach ( $items as $it ) {
				$items_data[] = array(
					'title' => $it->title,
					'url'   => $it->url,
				);
			}
		}
	}

	if ( empty( $items_data ) ) {
		$pages = get_pages( array( 'number' => 8 ) );
		if ( ! empty( $pages ) ) {
			foreach ( $pages as $p ) {
				$items_data[] = array(
					'title' => $p->post_title,
					'url'   => get_permalink( $p->ID ),
				);
			}
		}
	}

	return wp_json_encode( $items_data );
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

		<?php if ( $layout === 'stacked_utility' ) : ?>
			<!-- Top Utility Strip -->
			<div class="mh-header-utility-bar">
				<div class="mh-header-inner <?php echo esc_attr( $container_class ); ?>">
					<div class="mh-utility-inner">
						<div class="mh-utility-left">
							<span class="mh-utility-tagline"><?php bloginfo( 'description' ); ?></span>
						</div>
						<div class="mh-utility-right">
							<?php mh_render_footer_social_icons(); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="mh-header-inner <?php echo esc_attr( $container_class ); ?>">
			
			<?php if ( $layout === 'centered' ) : ?>
				<!-- Centered Layout: Logo on Top, Nav + CTA Below -->
				<div class="mh-header-row mh-header-row-top">
					<div class="mh-logo">
						<?php mh_render_brand_logo( 'header' ); ?>
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
							<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta" data-mh-focus="mh_header_cta_text" data-mh-btn-url="mh_header_cta_url"><?php echo esc_html( $cta_text ); ?></a>
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
						<?php mh_render_brand_logo( 'header' ); ?>
					</div>

					<div class="mh-split-right-wrap">
						<nav class="mh-desktop-nav mh-nav-split-right" aria-label="<?php esc_attr_e( 'Right Navigation', 'xophz-magic-hat' ); ?>">
							<?php mh_render_split_nav_items( 'right', $menu_setting ); ?>
						</nav>
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<div class="mh-header-cta-wrap">
								<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta" data-mh-focus="mh_header_cta_text" data-mh-btn-url="mh_header_cta_url"><?php echo esc_html( $cta_text ); ?></a>
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
						<?php mh_render_brand_logo( 'header' ); ?>
					</div>
					<div class="mh-minimal-actions">
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta mh-cta-minimal" data-mh-focus="mh_header_cta_text" data-mh-btn-url="mh_header_cta_url"><?php echo esc_html( $cta_text ); ?></a>
						<?php endif; ?>
						<button type="button" class="mh-hamburger mh-hamburger-always" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
							<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
						</button>
					</div>
				</div>

			<?php elseif ( $layout === 'floating_pill' ) : ?>
				<!-- Floating Glass Island Layout: Suspended Pill Dock -->
				<div class="mh-header-pill-wrap">
					<div class="mh-header-pill">
						<div class="mh-logo">
							<?php mh_render_brand_logo( 'header' ); ?>
						</div>
						<nav class="mh-desktop-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'xophz-magic-hat' ); ?>">
							<?php mh_render_nav_items( $menu_setting ); ?>
						</nav>
						<div class="mh-header-right-actions">
							<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
								<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta mh-cta-pill" data-mh-focus="mh_header_cta_text" data-mh-btn-url="mh_header_cta_url"><?php echo esc_html( $cta_text ); ?></a>
							<?php endif; ?>
							<button type="button" class="mh-hamburger" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
								<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
							</button>
						</div>
					</div>
				</div>

			<?php elseif ( $layout === 'inline_search' ) : ?>
				<!-- Inline Search & Command Bar Layout -->
				<div class="mh-header-row mh-header-search-row">
					<div class="mh-logo">
						<?php mh_render_brand_logo( 'header' ); ?>
					</div>

					<div class="mh-header-search-container" data-menu-items="<?php echo esc_attr( mh_get_nav_menu_items_json( $menu_setting ) ); ?>">
						<form role="search" method="get" class="mh-header-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<span class="dashicons dashicons-search mh-search-field-icon"></span>
							<input type="search" class="mh-header-search-input" placeholder="<?php esc_attr_e( 'Search site or navigate...', 'xophz-magic-hat' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" autocomplete="off" />
							<kbd class="mh-search-shortcut-badge">⌘K</kbd>
							<div class="mh-search-autocomplete-dropdown" style="display:none;"></div>
						</form>
					</div>

					<nav class="mh-desktop-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'xophz-magic-hat' ); ?>">
						<?php mh_render_nav_items( $menu_setting ); ?>
					</nav>

					<div class="mh-header-right-actions">
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta" data-mh-focus="mh_header_cta_text" data-mh-btn-url="mh_header_cta_url"><?php echo esc_html( $cta_text ); ?></a>
						<?php endif; ?>
						<button type="button" class="mh-hamburger" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
							<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
						</button>
					</div>
				</div>

			<?php elseif ( $layout === 'offcanvas_focus' ) : ?>
				<!-- Off-Canvas Focus Layout -->
				<div class="mh-header-row mh-header-offcanvas-row">
					<div class="mh-logo">
						<?php mh_render_brand_logo( 'header' ); ?>
					</div>
					<div class="mh-offcanvas-trigger-wrap">
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta mh-cta-offcanvas" data-mh-focus="mh_header_cta_text" data-mh-btn-url="mh_header_cta_url"><?php echo esc_html( $cta_text ); ?></a>
						<?php endif; ?>
						<button type="button" class="mh-hamburger mh-hamburger-always mh-hamburger-focus" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Open Navigation Menu', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
							<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
							<span class="mh-hamburger-text"><?php esc_html_e( 'Menu', 'xophz-magic-hat' ); ?></span>
						</button>
					</div>
				</div>

			<?php elseif ( $layout === 'announcement_ticker' ) : ?>
				<!-- Announcement Ticker Layout: Top Notification Bar + Streamlined Navbar -->
				<?php
				$ticker_badge = get_theme_mod( 'mh_header_ticker_badge', __( 'NEW RELEASE', 'xophz-magic-hat' ) );
				$ticker_text  = get_theme_mod( 'mh_header_ticker_text', __( 'Explore our latest quantum components and layouts.', 'xophz-magic-hat' ) );
				$ticker_url   = get_theme_mod( 'mh_header_ticker_url', '#explore' );
				?>
				<div class="mh-header-ticker-bar">
					<div class="mh-ticker-content">
						<span class="mh-ticker-badge" data-mh-focus="mh_header_ticker_badge"><?php echo esc_html( $ticker_badge ); ?></span>
						<a href="<?php echo esc_url( $ticker_url ); ?>" class="mh-ticker-link" data-mh-btn-url="mh_header_ticker_url">
							<span class="mh-ticker-text" data-mh-focus="mh_header_ticker_text"><?php echo esc_html( $ticker_text ); ?></span> &rarr;
						</a>
					</div>
				</div>
				<div class="mh-header-row mh-header-ticker-row">
					<div class="mh-logo">
						<?php mh_render_brand_logo( 'header' ); ?>
					</div>
					<nav class="mh-desktop-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'xophz-magic-hat' ); ?>">
						<?php mh_render_nav_items( $menu_setting ); ?>
					</nav>
					<div class="mh-header-right-actions">
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<div class="mh-header-cta-wrap">
								<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta" data-mh-focus="mh_header_cta_text" data-mh-btn-url="mh_header_cta_url"><?php echo esc_html( $cta_text ); ?></a>
							</div>
						<?php endif; ?>
						<button type="button" class="mh-hamburger" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
							<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
						</button>
					</div>
				</div>

			<?php elseif ( $layout === 'dual_cta' ) : ?>
				<!-- Dual Converter Layout: Brand Left, Nav Center, Dual Action (Ghost + Primary) Right -->
				<?php
				$sec_cta_text = get_theme_mod( 'mh_header_secondary_cta_text', __( 'Sign In', 'xophz-magic-hat' ) );
				$sec_cta_url  = get_theme_mod( 'mh_header_secondary_cta_url', '#signin' );
				?>
				<div class="mh-header-row mh-header-dual-cta-row">
					<div class="mh-logo">
						<?php mh_render_brand_logo( 'header' ); ?>
					</div>
					<nav class="mh-desktop-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'xophz-magic-hat' ); ?>">
						<?php mh_render_nav_items( $menu_setting ); ?>
					</nav>
					<div class="mh-dual-cta-group">
						<a href="<?php echo esc_url( $sec_cta_url ); ?>" class="mh-header-ghost-btn" data-mh-focus="mh_header_secondary_cta_text" data-mh-btn-url="mh_header_secondary_cta_url"><?php echo esc_html( $sec_cta_text ); ?></a>
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta mh-cta-primary" data-mh-focus="mh_header_cta_text" data-mh-btn-url="mh_header_cta_url"><?php echo esc_html( $cta_text ); ?></a>
						<?php endif; ?>
						<button type="button" class="mh-hamburger" id="mh-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation', 'xophz-magic-hat' ); ?>" aria-expanded="false" aria-controls="mh-mobile-nav">
							<span class="mh-hamburger-box"><span class="mh-hamburger-inner"></span></span>
						</button>
					</div>
				</div>

			<?php else : ?>
				<!-- Standard Corporate / Stacked Main Row: Logo Left, Nav Center/Right, CTA Far Right -->
				<div class="mh-header-row mh-header-standard-row">
					<div class="mh-logo">
						<?php mh_render_brand_logo( 'header' ); ?>
					</div>

					<nav class="mh-desktop-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'xophz-magic-hat' ); ?>">
						<?php mh_render_nav_items( $menu_setting ); ?>
					</nav>

					<div class="mh-header-right-actions">
						<?php if ( $show_cta && ! empty( $cta_text ) ) : ?>
							<div class="mh-header-cta-wrap">
								<a href="<?php echo esc_url( $cta_url ); ?>" class="mh-header-cta" data-mh-focus="mh_header_cta_text" data-mh-btn-url="mh_header_cta_url"><?php echo esc_html( $cta_text ); ?></a>
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
						<?php mh_render_brand_logo( 'footer' ); ?>
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
						<?php mh_render_brand_logo( 'footer' ); ?>
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

			<?php elseif ( $layout === 'bento' ) : ?>
				<!-- Bento Grid Footer -->
				<div class="mh-footer-bento-wrap">
					<div class="mh-bento-grid">
						<!-- Tile 1: Brand & Status -->
						<div class="mh-bento-tile mh-bento-tile-brand">
							<div class="mh-bento-brand-header">
								<?php mh_render_brand_logo( 'footer' ); ?>
								<div class="mh-status-indicator-pill">
									<span class="mh-status-dot"></span>
									<span class="mh-status-label"><?php esc_html_e( 'Systems Online', 'xophz-magic-hat' ); ?></span>
								</div>
							</div>
							<?php if ( get_bloginfo( 'description' ) ) : ?>
								<p class="mh-footer-tagline"><?php bloginfo( 'description' ); ?></p>
							<?php endif; ?>
							<?php mh_render_footer_social_icons(); ?>
						</div>

						<!-- Tile 2: Primary Quick Links -->
						<div class="mh-bento-tile mh-bento-tile-nav">
							<h4 class="mh-footer-heading"><?php esc_html_e( 'Navigation', 'xophz-magic-hat' ); ?></h4>
							<?php
							if ( has_nav_menu( 'primary' ) ) {
								wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'mh-footer-menu-list', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								mh_default_nav_fallback();
							}
							?>
						</div>

						<!-- Tile 3: Resources -->
						<div class="mh-bento-tile mh-bento-tile-resources">
							<h4 class="mh-footer-heading"><?php esc_html_e( 'Resources', 'xophz-magic-hat' ); ?></h4>
							<?php
							if ( has_nav_menu( 'footer_1' ) ) {
								wp_nav_menu( array( 'theme_location' => 'footer_1', 'container' => false, 'menu_class' => 'mh-footer-menu-list', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								echo '<ul class="mh-footer-menu-list">';
								echo '<li><a href="#about">' . esc_html__( 'About Us', 'xophz-magic-hat' ) . '</a></li>';
								echo '<li><a href="#features">' . esc_html__( 'Features', 'xophz-magic-hat' ) . '</a></li>';
								echo '<li><a href="#pricing">' . esc_html__( 'Pricing', 'xophz-magic-hat' ) . '</a></li>';
								echo '<li><a href="#contact">' . esc_html__( 'Contact', 'xophz-magic-hat' ) . '</a></li>';
								echo '</ul>';
							}
							?>
						</div>

						<!-- Tile 4: Newsletter / Connect Card -->
						<div class="mh-bento-tile mh-bento-tile-connect">
							<h4 class="mh-footer-heading"><?php esc_html_e( 'Stay Informed', 'xophz-magic-hat' ); ?></h4>
							<p class="mh-bento-desc"><?php esc_html_e( 'Receive updates, new tools, and releases directly in your inbox.', 'xophz-magic-hat' ); ?></p>
							<form class="mh-footer-newsletter-form" onsubmit="event.preventDefault(); alert('Subscribed!');">
								<div class="mh-newsletter-input-group">
									<input type="email" placeholder="<?php esc_attr_e( 'Enter your email...', 'xophz-magic-hat' ); ?>" required class="mh-newsletter-input" />
									<button type="submit" class="mh-newsletter-submit"><?php esc_html_e( 'Join', 'xophz-magic-hat' ); ?></button>
								</div>
							</form>
						</div>
					</div>

					<div class="mh-footer-bottom-bar">
						<p class="mh-copyright-text"><?php echo wp_kses_post( $copyright_text ); ?></p>
						<a href="#mw-header" class="mh-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'xophz-magic-hat' ); ?>">
							<span class="dashicons dashicons-arrow-up-alt2"></span>
						</a>
					</div>
				</div>

			<?php elseif ( $layout === 'big_statement' ) : ?>
				<!-- Big Statement CTA Footer -->
				<?php
				$stmt_badge = get_theme_mod( 'mh_footer_statement_badge', __( 'Next Steps', 'xophz-magic-hat' ) );
				$stmt_title = get_theme_mod( 'mh_footer_statement_title', __( "Let's build something remarkable together.", 'xophz-magic-hat' ) );
				$stmt_btn   = get_theme_mod( 'mh_footer_statement_cta_text', __( 'Get Started', 'xophz-magic-hat' ) );
				$stmt_url   = get_theme_mod( 'mh_footer_statement_cta_url', '#contact' );
				?>
				<div class="mh-footer-statement-wrap">
					<div class="mh-statement-top">
						<span class="mh-statement-badge" data-mh-focus="mh_footer_statement_badge"><?php echo esc_html( $stmt_badge ); ?></span>
						<h2 class="mh-statement-title" data-mh-focus="mh_footer_statement_title"><?php echo esc_html( $stmt_title ); ?></h2>
						<div class="mh-statement-cta-row">
							<a href="<?php echo esc_url( $stmt_url ); ?>" class="mh-statement-btn" data-mh-focus="mh_footer_statement_cta_text" data-mh-btn-url="mh_footer_statement_cta_url">
								<span class="mh-statement-btn-label"><?php echo esc_html( $stmt_btn ); ?></span> &rarr;
							</a>
						</div>
					</div>

					<div class="mh-statement-bottom">
						<div class="mh-statement-brand">
							<?php mh_render_brand_logo( 'footer' ); ?>
						</div>
						<nav class="mh-statement-nav" aria-label="<?php esc_attr_e( 'Footer Navigation', 'xophz-magic-hat' ); ?>">
							<?php
							if ( has_nav_menu( 'primary' ) ) {
								wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'mh-inline-menu', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								mh_default_nav_fallback();
							}
							?>
						</nav>
						<div class="mh-statement-social">
							<?php mh_render_footer_social_icons(); ?>
						</div>
					</div>

					<div class="mh-footer-bottom-bar">
						<p class="mh-copyright-text"><?php echo wp_kses_post( $copyright_text ); ?></p>
						<a href="#mw-header" class="mh-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'xophz-magic-hat' ); ?>">
							<span class="dashicons dashicons-arrow-up-alt2"></span>
						</a>
					</div>
				</div>

			<?php elseif ( $layout === 'newsletter_first' ) : ?>
				<!-- Newsletter Lead-In Footer -->
				<?php
				$news_title = get_theme_mod( 'mh_footer_newsletter_title', __( 'Subscribe to our updates', 'xophz-magic-hat' ) );
				$news_desc  = get_theme_mod( 'mh_footer_newsletter_desc', __( 'Get the latest releases, design inspiration, and news directly to your inbox.', 'xophz-magic-hat' ) );
				$news_btn   = get_theme_mod( 'mh_footer_newsletter_btn_text', __( 'Join', 'xophz-magic-hat' ) );
				?>
				<div class="mh-footer-newsletter-first-wrap">
					<div class="mh-newsletter-banner-card">
						<div class="mh-newsletter-banner-content">
							<h3 class="mh-newsletter-banner-title" data-mh-focus="mh_footer_newsletter_title"><?php echo esc_html( $news_title ); ?></h3>
							<p class="mh-newsletter-banner-desc" data-mh-focus="mh_footer_newsletter_desc"><?php echo esc_html( $news_desc ); ?></p>
						</div>
						<form class="mh-newsletter-banner-form" onsubmit="event.preventDefault(); alert('Subscribed!');">
							<div class="mh-newsletter-input-group">
								<input type="email" placeholder="<?php esc_attr_e( 'Your email address...', 'xophz-magic-hat' ); ?>" required class="mh-newsletter-input" />
								<button type="submit" class="mh-newsletter-submit" data-mh-focus="mh_footer_newsletter_btn_text"><?php echo esc_html( $news_btn ); ?></button>
							</div>
						</form>
					</div>

					<div class="mh-footer-grid mh-footer-cols-4">
						<div class="mh-footer-col mh-footer-brand-col">
							<?php mh_render_brand_logo( 'footer' ); ?>
							<?php if ( get_bloginfo( 'description' ) ) : ?>
								<p class="mh-footer-tagline"><?php bloginfo( 'description' ); ?></p>
							<?php endif; ?>
							<?php mh_render_footer_social_icons(); ?>
						</div>
						<?php
						$locations = get_nav_menu_locations();
						$rendered = 0;
						foreach ( $footer_locations as $loc_slug => $default_heading ) :
							if ( $rendered >= 3 ) break;
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
									wp_nav_menu( array( 'theme_location' => $loc_slug, 'container' => false, 'menu_class' => 'mh-footer-menu-list', 'depth' => 1, 'fallback_cb' => false ) );
								} else {
									echo '<ul class="mh-footer-menu-list">';
									echo '<li><a href="#about">' . esc_html__( 'About', 'xophz-magic-hat' ) . '</a></li>';
									echo '<li><a href="#features">' . esc_html__( 'Features', 'xophz-magic-hat' ) . '</a></li>';
									echo '<li><a href="#contact">' . esc_html__( 'Contact', 'xophz-magic-hat' ) . '</a></li>';
									echo '</ul>';
								}
								?>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="mh-footer-bottom-bar">
						<p class="mh-copyright-text"><?php echo wp_kses_post( $copyright_text ); ?></p>
						<a href="#mw-header" class="mh-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'xophz-magic-hat' ); ?>">
							<span class="dashicons dashicons-arrow-up-alt2"></span>
						</a>
					</div>
				</div>

			<?php elseif ( $layout === 'floating_dock' ) : ?>
				<!-- Floating Dock Footer -->
				<div class="mh-footer-dock-wrap">
					<div class="mh-footer-dock-bar">
						<div class="mh-dock-left">
							<?php mh_render_brand_logo( 'footer' ); ?>
						</div>
						<nav class="mh-dock-center" aria-label="<?php esc_attr_e( 'Footer Navigation', 'xophz-magic-hat' ); ?>">
							<?php
							if ( has_nav_menu( 'primary' ) ) {
								wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'mh-inline-menu', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								mh_default_nav_fallback();
							}
							?>
						</nav>
						<div class="mh-dock-right">
							<?php mh_render_footer_social_icons(); ?>
							<span class="mh-dock-copy"><?php echo wp_kses_post( $copyright_text ); ?></span>
						</div>
					</div>
				</div>

			<?php elseif ( $layout === 'sitemap_dense' ) : ?>
				<!-- Enterprise Site Directory / Dense Sitemap -->
				<div class="mh-footer-sitemap-wrap">
					<div class="mh-sitemap-top-row">
						<div class="mh-sitemap-brand-block">
							<?php mh_render_brand_logo( 'footer' ); ?>
							<div class="mh-sitemap-system-status">
								<span class="mh-status-beacon-dot"></span>
								<span class="mh-status-beacon-label"><?php esc_html_e( 'All Systems Operational', 'xophz-magic-hat' ); ?></span>
							</div>
						</div>
						<div class="mh-sitemap-social-block">
							<?php mh_render_footer_social_icons(); ?>
						</div>
					</div>

					<div class="mh-sitemap-grid">
						<div class="mh-sitemap-col">
							<h5 class="mh-sitemap-col-title"><?php esc_html_e( 'Platform', 'xophz-magic-hat' ); ?></h5>
							<?php
							if ( has_nav_menu( 'primary' ) ) {
								wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'mh-sitemap-links', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								mh_default_nav_fallback();
							}
							?>
						</div>
						<div class="mh-sitemap-col">
							<h5 class="mh-sitemap-col-title"><?php esc_html_e( 'Solutions', 'xophz-magic-hat' ); ?></h5>
							<?php
							if ( has_nav_menu( 'footer_1' ) ) {
								wp_nav_menu( array( 'theme_location' => 'footer_1', 'container' => false, 'menu_class' => 'mh-sitemap-links', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								echo '<ul class="mh-sitemap-links"><li><a href="#enterprise">Enterprise</a></li><li><a href="#agencies">Agencies</a></li><li><a href="#creators">Creators</a></li></ul>';
							}
							?>
						</div>
						<div class="mh-sitemap-col">
							<h5 class="mh-sitemap-col-title"><?php esc_html_e( 'Developers', 'xophz-magic-hat' ); ?></h5>
							<?php
							if ( has_nav_menu( 'footer_2' ) ) {
								wp_nav_menu( array( 'theme_location' => 'footer_2', 'container' => false, 'menu_class' => 'mh-sitemap-links', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								echo '<ul class="mh-sitemap-links"><li><a href="#docs">Documentation</a></li><li><a href="#sdk">Compass SDK</a></li><li><a href="#changelog">Changelog</a></li></ul>';
							}
							?>
						</div>
						<div class="mh-sitemap-col">
							<h5 class="mh-sitemap-col-title"><?php esc_html_e( 'Company', 'xophz-magic-hat' ); ?></h5>
							<?php
							if ( has_nav_menu( 'footer_3' ) ) {
								wp_nav_menu( array( 'theme_location' => 'footer_3', 'container' => false, 'menu_class' => 'mh-sitemap-links', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								echo '<ul class="mh-sitemap-links"><li><a href="#about">About Us</a></li><li><a href="#careers">Careers</a></li><li><a href="#press">Newsroom</a></li></ul>';
							}
							?>
						</div>
						<div class="mh-sitemap-col">
							<h5 class="mh-sitemap-col-title"><?php esc_html_e( 'Legal', 'xophz-magic-hat' ); ?></h5>
							<ul class="mh-sitemap-links">
								<li><a href="#privacy"><?php esc_html_e( 'Privacy Policy', 'xophz-magic-hat' ); ?></a></li>
								<li><a href="#terms"><?php esc_html_e( 'Terms of Service', 'xophz-magic-hat' ); ?></a></li>
								<li><a href="#security"><?php esc_html_e( 'Security', 'xophz-magic-hat' ); ?></a></li>
							</ul>
						</div>
					</div>

					<div class="mh-footer-bottom-bar">
						<p class="mh-copyright-text"><?php echo wp_kses_post( $copyright_text ); ?></p>
						<a href="#mw-header" class="mh-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'xophz-magic-hat' ); ?>">
							<span class="dashicons dashicons-arrow-up-alt2"></span>
						</a>
					</div>
				</div>

			<?php elseif ( $layout === 'social_hub' ) : ?>
				<!-- Creator & Community Social Hub Footer -->
				<div class="mh-footer-social-hub-wrap">
					<div class="mh-social-hub-headline">
						<h3 class="mh-social-hub-title"><?php esc_html_e( 'Connect with our community', 'xophz-magic-hat' ); ?></h3>
						<p class="mh-social-hub-subtitle"><?php esc_html_e( 'Follow our journey, explore open source projects, and join live developer discussions.', 'xophz-magic-hat' ); ?></p>
					</div>

					<div class="mh-social-cards-grid">
						<a href="<?php echo esc_url( get_theme_mod( 'mh_social_github', 'https://github.com' ) ); ?>" target="_blank" rel="noopener noreferrer" class="mh-social-card mh-social-card-github">
							<div class="mh-social-card-icon">
								<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
							</div>
							<div class="mh-social-card-meta">
								<span class="mh-social-card-name">GitHub</span>
								<span class="mh-social-card-badge"><?php esc_html_e( 'Open Source', 'xophz-magic-hat' ); ?></span>
							</div>
						</a>

						<a href="<?php echo esc_url( get_theme_mod( 'mh_social_twitter', 'https://twitter.com' ) ); ?>" target="_blank" rel="noopener noreferrer" class="mh-social-card mh-social-card-x">
							<div class="mh-social-card-icon">
								<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16h4.267l-11.733 -16z"></path><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path></svg>
							</div>
							<div class="mh-social-card-meta">
								<span class="mh-social-card-name">X (Twitter)</span>
								<span class="mh-social-card-badge"><?php esc_html_e( 'Updates', 'xophz-magic-hat' ); ?></span>
							</div>
						</a>

						<a href="<?php echo esc_url( get_theme_mod( 'mh_social_youtube', 'https://youtube.com' ) ); ?>" target="_blank" rel="noopener noreferrer" class="mh-social-card mh-social-card-youtube">
							<div class="mh-social-card-icon">
								<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
							</div>
							<div class="mh-social-card-meta">
								<span class="mh-social-card-name">YouTube</span>
								<span class="mh-social-card-badge"><?php esc_html_e( 'Tutorials', 'xophz-magic-hat' ); ?></span>
							</div>
						</a>

						<a href="<?php echo esc_url( get_theme_mod( 'mh_social_linkedin', 'https://linkedin.com' ) ); ?>" target="_blank" rel="noopener noreferrer" class="mh-social-card mh-social-card-discord">
							<div class="mh-social-card-icon">
								<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
							</div>
							<div class="mh-social-card-meta">
								<span class="mh-social-card-name">Community</span>
								<span class="mh-social-card-badge"><?php esc_html_e( 'Network', 'xophz-magic-hat' ); ?></span>
							</div>
						</a>
					</div>

					<div class="mh-social-hub-bottom">
						<div class="mh-social-hub-brand">
							<?php mh_render_brand_logo( 'footer' ); ?>
						</div>
						<nav class="mh-social-hub-nav" aria-label="<?php esc_attr_e( 'Footer Navigation', 'xophz-magic-hat' ); ?>">
							<?php
							if ( has_nav_menu( 'primary' ) ) {
								wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'mh-inline-menu', 'depth' => 1, 'fallback_cb' => false ) );
							} else {
								mh_default_nav_fallback();
							}
							?>
						</nav>
						<p class="mh-copyright-text"><?php echo wp_kses_post( $copyright_text ); ?></p>
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
						<?php mh_render_brand_logo( 'footer' ); ?>
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
