<?php
/**
 * Customizer Page Builder Control
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Magic_Hat_Page_Builder_Control' ) ) {
	/**
	 * Page builder control for modular front-page layout management.
	 */
	class Magic_Hat_Page_Builder_Control extends WP_Customize_Control {
		public $type = 'mh-page-builder-control';

		public function render_content() {
			$is_active     = class_exists( 'Xophz_Compass_Magic_Wand' ) || defined( 'XOPHZ_COMPASS_MAGIC_WAND_VERSION' );
			$show_on_front = get_option( 'show_on_front', 'posts' );
			$is_magic_hat  = ( $show_on_front === 'page' );
			?>
			<div class="mh-template-switch-wrap">
				<div class="mh-template-switch-title"><?php _e( 'Front Page Display', 'xophz-magic-hat' ); ?></div>
				<p class="mh-template-switch-desc"><?php _e( 'Switch between the modular Magic Hat canvas and standard blog posts.', 'xophz-magic-hat' ); ?></p>
				<div class="mh-template-switch-btns">
					<button type="button" class="mh-switch-btn <?php echo $is_magic_hat ? 'active' : ''; ?>" data-mode="magic_hat">
						<span class="dashicons dashicons-layout"></span> <?php _e( 'Magic Hat', 'xophz-magic-hat' ); ?>
					</button>
					<button type="button" class="mh-switch-btn <?php echo ! $is_magic_hat ? 'active' : ''; ?>" data-mode="posts">
						<span class="dashicons dashicons-admin-post"></span> <?php _e( 'Blog Posts', 'xophz-magic-hat' ); ?>
					</button>
				</div>
				<div class="mh-template-notice <?php echo $is_magic_hat ? 'success' : 'warning'; ?>" id="mh-template-status">
					<?php if ( $is_magic_hat ) : ?>
						<?php _e( '✓ Magic Hat canvas is active on your homepage.', 'xophz-magic-hat' ); ?>
					<?php else : ?>
						<?php _e( '⚠️ Showing standard blog posts. Switch to Magic Hat to display modular sections.', 'xophz-magic-hat' ); ?>
					<?php endif; ?>
				</div>
			</div>

			<ul id="mh_page_rows">
				<li class="empty"><?php _e( 'No sections added', 'xophz-magic-hat' ); ?></li>
			</ul>
			
			<?php if ( ! $is_active ) : ?>
				<div class="mh-plugin-notice">
					<p class="mh-plugin-notice-title"><?php _e( 'Magic Wand Required', 'xophz-magic-hat' ); ?></p>
					<p class="mh-plugin-notice-desc"><?php _e( 'Activate the companion plugin to unlock this.', 'xophz-magic-hat' ); ?></p>
				</div>
			<?php endif; ?>

			<div class="mh-add-section-wrap">
				<button type="button" class="button button-primary mh-add-section <?php echo $is_active ? 'mh-btn-active' : 'mh-btn-disabled'; ?>" <?php disabled( ! $is_active ); ?>><?php _e( '+ Add Section', 'xophz-magic-hat' ); ?></button>
			</div>

			<input type="hidden" id="mh_page_sections_input" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>">
			<?php
		}
	}
}
