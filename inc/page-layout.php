<?php
/**
 * Page Layout & Sidebar Architecture
 *
 * Implements WordPress industry standards (Astra, GeneratePress, Kadence)
 * for page layouts, sidebars, and grid containers decoupled from the masthead.
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get active page layout setting
 *
 * @return string 'no_sidebar', 'left_sidebar', 'right_sidebar', or 'three_column'
 */
function mh_get_page_layout() {
	$layout = get_theme_mod( 'mh_page_layout', 'no_sidebar' );

	/**
	 * Filter the active page layout
	 *
	 * @param string $layout The active page layout key.
	 */
	return apply_filters( 'mh_page_layout', $layout );
}

/**
 * Check whether the left sidebar should be rendered
 *
 * @return bool True if left sidebar is active for the current layout
 */
function mh_has_left_sidebar() {
	$layout = mh_get_page_layout();
	return in_array( $layout, array( 'left_sidebar', 'three_column' ), true );
}

/**
 * Check whether the right sidebar should be rendered
 *
 * @return bool True if right sidebar is active for the current layout
 */
function mh_has_right_sidebar() {
	$layout = mh_get_page_layout();
	return in_array( $layout, array( 'right_sidebar', 'three_column' ), true );
}

/**
 * Get CSS classes for the content container
 *
 * @param array $additional_classes Optional additional classes.
 * @return string Space-separated class list.
 */
function mh_get_content_container_classes( $additional_classes = array() ) {
	$layout = mh_get_page_layout();
	$classes = array(
		'mh-content-container',
		'mh-layout-' . sanitize_html_class( $layout ),
	);

	if ( mh_has_left_sidebar() ) {
		$classes[] = 'has-left-sidebar';
	}
	if ( mh_has_right_sidebar() ) {
		$classes[] = 'has-right-sidebar';
	}

	if ( ! empty( $additional_classes ) && is_array( $additional_classes ) ) {
		$classes = array_merge( $classes, $additional_classes );
	}

	return implode( ' ', array_unique( $classes ) );
}

/**
 * Render a sidebar area (left or right)
 *
 * @param string $side 'left' or 'right'
 */
function mh_render_sidebar( $side = 'left' ) {
	$side        = ( $side === 'right' ) ? 'right' : 'left';
	$sidebar_id  = 'mh-sidebar-' . $side;
	$aria_label  = ( $side === 'right' ) ? __( 'Secondary Sidebar', 'xophz-magic-hat' ) : __( 'Primary Sidebar', 'xophz-magic-hat' );
	$side_title  = ( $side === 'right' ) ? __( 'Right Sidebar', 'xophz-magic-hat' ) : __( 'Left Sidebar', 'xophz-magic-hat' );

	?>
	<aside id="<?php echo esc_attr( $sidebar_id ); ?>" class="mh-sidebar mh-sidebar-<?php echo esc_attr( $side ); ?>" role="complementary" aria-label="<?php echo esc_attr( $aria_label ); ?>">
		<div class="mh-sidebar-inner">
			<?php if ( is_active_sidebar( $sidebar_id ) ) : ?>
				<?php dynamic_sidebar( $sidebar_id ); ?>
			<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
				<div class="mh-sidebar-empty-state">
					<div class="mh-sidebar-empty-card">
						<span class="mh-sidebar-empty-badge"><?php echo esc_html( $side_title ); ?></span>
						<p class="mh-sidebar-empty-text"><?php esc_html_e( 'No widgets assigned to this area yet.', 'xophz-magic-hat' ); ?></p>
						<a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>" class="mh-sidebar-empty-btn"><?php esc_html_e( '+ Add Widgets', 'xophz-magic-hat' ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</aside>
	<?php
}

/**
 * Add page layout classes to <body>
 *
 * @param array $classes Existing body classes.
 * @return array Filtered body classes.
 */
function mh_page_layout_body_class( $classes ) {
	$layout = mh_get_page_layout();
	$classes[] = 'mh-layout-' . sanitize_html_class( $layout );

	if ( $layout === 'left_sidebar' ) {
		$classes[] = 'mh-has-left-sidebar';
	} elseif ( $layout === 'right_sidebar' ) {
		$classes[] = 'mh-has-right-sidebar';
	} elseif ( $layout === 'three_column' ) {
		$classes[] = 'mh-has-both-sidebars';
	} else {
		$classes[] = 'mh-no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'mh_page_layout_body_class' );
