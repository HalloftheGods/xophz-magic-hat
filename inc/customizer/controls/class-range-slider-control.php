<?php
/**
 * Customizer Range Slider Control
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Magic_Hat_Range_Slider_Control' ) ) {
	/**
	 * Range slider with live numeric badge output and change event firing.
	 */
	class Magic_Hat_Range_Slider_Control extends WP_Customize_Control {
		public $type = 'range';

		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<div style="display:flex; align-items:center; gap: 10px; margin-top: 5px;">
					<input type="range" 
						value="<?php echo esc_attr( floatval( $this->value() ) ); ?>" 
						<?php $this->link(); ?> 
						min="<?php echo esc_attr( $this->input_attrs['min'] ?? 0 ); ?>" 
						max="<?php echo esc_attr( $this->input_attrs['max'] ?? 100 ); ?>" 
						step="<?php echo esc_attr( $this->input_attrs['step'] ?? 1 ); ?>"
						style="flex: 1;"
						oninput="this.nextElementSibling.value = this.value + '<?php echo esc_attr( $this->input_attrs['unit'] ?? '' ); ?>'; if (window.jQuery) { jQuery(this).trigger('change'); }"
					/>
					<input type="text" 
						value="<?php echo esc_attr( $this->value() ); ?>" 
						readonly
						style="width: 65px; text-align: center; background: #f0f0f1; border: 1px solid #ccc; border-radius: 3px;"
					/>
				</div>
			</label>
			<?php
		}
	}
}
