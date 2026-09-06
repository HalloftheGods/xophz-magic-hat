<?php
/**
 * Customizer Accordion Toggle Control
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Magic_Hat_Accordion_Toggle_Control' ) ) {
	/**
	 * Collapsible Accordion Header Control for grouping controls cleanly.
	 */
	class Magic_Hat_Accordion_Toggle_Control extends WP_Customize_Control {
		public $type = 'mh_accordion_toggle';
		public $group_id = '';
		public $is_open = false;

		/**
		 * Enqueue control scripts and styles if needed.
		 */
		public function render_content() {
			$group_id = ! empty( $this->group_id ) ? esc_attr( $this->group_id ) : sanitize_title( $this->id );
			$is_open_class = $this->is_open ? ' is-expanded' : '';
			?>
			<div class="mh-accordion-header<?php echo esc_attr( $is_open_class ); ?>" data-accordion-group="<?php echo esc_attr( $group_id ); ?>" role="button" tabindex="0" aria-expanded="<?php echo $this->is_open ? 'true' : 'false'; ?>">
				<div class="mh-accordion-title-wrap">
					<span class="mh-accordion-title"><?php echo esc_html( $this->label ); ?></span>
					<?php if ( ! empty( $this->description ) ) : ?>
						<span class="mh-accordion-badge"><?php echo esc_html( $this->description ); ?></span>
					<?php endif; ?>
				</div>
				<span class="dashicons dashicons-arrow-down-alt2 mh-accordion-icon"></span>
			</div>
			<?php
		}
	}
}
