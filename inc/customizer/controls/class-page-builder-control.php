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
			<style>
				.mh-template-switch-wrap { margin-bottom: 14px; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; }
				.mh-template-switch-title { font-size: 11px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
				.mh-template-switch-desc { margin: 0 0 10px; font-size: 11px; color: #64748b; line-height: 1.3; }
				.mh-template-switch-btns { display: flex; gap: 6px; }
				.mh-template-switch-btns .mh-switch-btn { flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 11px; font-weight: 600; padding: 6px 8px; border-radius: 4px; border: 1px solid #cbd5e1; background: #fff; color: #475569; cursor: pointer; transition: all 0.15s ease; }
				.mh-template-switch-btns .mh-switch-btn:hover { border-color: #2563eb; color: #2563eb; }
				.mh-template-switch-btns .mh-switch-btn.active { background: #2563eb; border-color: #2563eb; color: #fff; box-shadow: 0 1px 3px rgba(37,99,235,0.3); }
				.mh-template-notice { margin-top: 8px; font-size: 11px; line-height: 1.35; padding: 6px 8px; border-radius: 4px; }
				.mh-template-notice.warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
				.mh-template-notice.success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
				#mh_page_rows { list-style: none; margin: 0 0 14px; padding: 0; }
				#mh_page_rows .empty { color: #646970; font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; padding: 16px 10px; text-align: center; border: 1px dashed #c3c4c7; border-radius: 4px; background: #f6f7f7; }
				#mh_page_rows .mh-section-item { background: #fff; border: 1px solid #dcdcde; margin-bottom: 8px; padding: 8px 10px; display: flex; align-items: center; gap: 8px; border-radius: 4px; cursor: pointer; transition: border-color 0.15s ease, box-shadow 0.15s ease; }
				#mh_page_rows .mh-section-item:hover { border-color: #2271b1; box-shadow: 0 1px 4px rgba(34,113,177,0.12); }
				.mh-add-section-wrap { margin-top: 10px; }
				.mh-add-section-wrap .mh-add-section { width: 100%; padding: 8px; font-weight: 600; font-size: 12px; border-radius: 4px; border: none; cursor: pointer; transition: opacity 0.2s; }
				.mh-add-section-wrap .mh-add-section:hover { opacity: 0.9; }
			</style>

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
				<div style="margin-top: 8px; padding: 8px 10px; background: #fef2f2; border-left: 3px solid #dc3232; border-radius: 2px;">
					<p style="margin: 0; color: #dc3232; font-weight: 600; font-size: 11px;"><?php _e( 'Magic Wand Required', 'xophz-magic-hat' ); ?></p>
					<p style="margin: 3px 0 0; font-size: 11px; color: #888; line-height: 1.3;"><?php _e( 'Activate the companion plugin to unlock this.', 'xophz-magic-hat' ); ?></p>
				</div>
			<?php endif; ?>

			<div class="mh-add-section-wrap">
				<button type="button" class="button button-primary mh-add-section" style="<?php echo $is_active ? 'background: #2563eb; border-color: #2563eb; color: #fff;' : 'opacity: 0.4; cursor: not-allowed;'; ?>" <?php disabled( ! $is_active ); ?>><?php _e( '+ Add Section', 'xophz-magic-hat' ); ?></button>
			</div>

			<input type="hidden" id="mh_page_sections_input" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>">
			<?php
		}
	}
}
