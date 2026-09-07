<?php
/**
 * Customizer Layout Picker Control
 *
 * Provides a visual preview card and modal catalog launcher for Header and Footer layout archetypes.
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Magic_Hat_Layout_Picker_Control' ) ) {
	/**
	 * Layout picker control with visual active badge and modal launcher.
	 */
	class Magic_Hat_Layout_Picker_Control extends WP_Customize_Control {
		public $type = 'mh-layout-picker';
		public $layout_type = 'header'; // 'header' or 'footer'
		public $layouts = array();

		public function render_content() {
			$val = $this->value();
			$label = isset( $this->layouts[ $val ] ) ? $this->layouts[ $val ] : ucfirst( str_replace( '_', ' ', $val ) );
			?>
			<div class="mh-layout-picker-wrap" data-target-setting="<?php echo esc_attr( $this->id ); ?>" data-layout-type="<?php echo esc_attr( $this->layout_type ); ?>">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>

				<div class="mh-layout-picker-card">
					<div class="mh-layout-picker-card-info">
						<span class="mh-layout-picker-badge"><?php echo esc_html( strtoupper( $this->layout_type ) ); ?> TEMPLATE</span>
						<div class="mh-layout-picker-current-title" id="<?php echo esc_attr( $this->id ); ?>_current_title"><?php echo esc_html( $label ); ?></div>
						<div class="mh-layout-picker-current-id" id="<?php echo esc_attr( $this->id ); ?>_current_id"><?php echo esc_html( $val ); ?></div>
					</div>
					<button type="button" class="button button-primary mh-btn-open-layout-modal" data-type="<?php echo esc_attr( $this->layout_type ); ?>" data-setting="<?php echo esc_attr( $this->id ); ?>">
						<span class="dashicons dashicons-layout"></span> <?php esc_html_e( 'Choose Layout', 'xophz-magic-hat' ); ?>
					</button>
				</div>

				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>_input" class="mh-layout-picker-input" <?php $this->link(); ?> value="<?php echo esc_attr( $val ); ?>">
			</div>
			<?php
		}
	}
}
