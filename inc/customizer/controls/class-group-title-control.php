<?php
/**
 * Customizer Group Title / Subheading Control
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Magic_Hat_Group_Title_Control' ) ) {
	/**
	 * Renders a stylized section subheading separator in Customizer panels.
	 */
	class Magic_Hat_Group_Title_Control extends WP_Customize_Control {
		public $type = 'mh_group_title';

		public function render_content() {
			?>
			<div class="mh-customizer-group-heading">
				<h4><?php echo esc_html( $this->label ); ?></h4>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
			</div>
			<?php
		}
	}
}
