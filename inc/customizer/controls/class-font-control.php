<?php
/**
 * Customizer Font Picker Control
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Magic_Hat_Font_Control' ) ) {
	/**
	 * Custom Font Picker with lazy Google Fonts preview rendering.
	 */
	class Magic_Hat_Font_Control extends WP_Customize_Control {
		public $type = 'mh_font';

		public function render_content() {
			$current_val  = $this->value();
			$current_name = explode( ',', $current_val )[0];
			?>
			<div class="mh-font-picker-wrap" style="margin-bottom: 15px;">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				
				<div class="mh-faux-select" style="position: relative;">
					<div class="mh-faux-value" style="padding: 8px 10px; background: #fff; border: 1px solid #8c8f94; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-radius: 3px; overflow: hidden;">
						<span class="mh-font-name" style="font-family: '<?php echo esc_attr( $current_name ); ?>', sans-serif; font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 85%;"><?php echo esc_html( $current_name ); ?></span>
						<span class="dashicons dashicons-arrow-down-alt2" style="flex-shrink: 0;"></span>
					</div>
					<div class="mh-faux-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #8c8f94; border-top: none; max-height: 65vh; overflow-y: auto; z-index: 99999; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
						<?php foreach ( $this->choices as $value => $label ) : 
							$font_name = explode( ',', $value )[0];
							$is_active = ( $current_val === $value );
						?>
							<div class="mh-font-option" data-value="<?php echo esc_attr( $value ); ?>" data-name="<?php echo esc_attr( $font_name ); ?>" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #f0f0f1; font-size: 18px; background: <?php echo $is_active ? '#e6f0ff' : '#fff'; ?>;">
								<?php echo esc_html( $font_name ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				
				<input type="hidden" class="mh-font-hidden-input" value="<?php echo esc_attr( $current_val ); ?>" <?php $this->link(); ?> />
			</div>
			
			<script>
				jQuery(document).ready(function($) {
					var wrap = $('.mh-font-picker-wrap');
					var valDisplay = wrap.find('.mh-faux-value span.mh-font-name');
					var dropdown = wrap.find('.mh-faux-dropdown');
					var hiddenInput = wrap.find('.mh-font-hidden-input');

					// Load full initial font
					loadFullFont('<?php echo esc_js( $current_name ); ?>');

					wrap.find('.mh-faux-value').on('click', function(e) {
						e.stopPropagation();
						$('.mh-faux-dropdown').not(dropdown).hide(); // Close others
						dropdown.toggle();
						
						// Lazy load the exact characters needed to preview the fonts
						if (!dropdown.hasClass('fonts-loaded')) {
							dropdown.addClass('fonts-loaded');
							var families = [];
							wrap.find('.mh-font-option').each(function() {
								var fn = $(this).data('name');
								$(this).css('font-family', '"' + fn + '", sans-serif');
								families.push('family=' + fn.replace(/\s+/g, '+') + '&text=' + encodeURIComponent(fn));
							});
							
							// Google Fonts allows multiple families. We split into chunks of 20.
							for (var i = 0; i < families.length; i += 20) {
								var chunk = families.slice(i, i + 20).join('&');
								$('head').append('<link href="https://fonts.googleapis.com/css2?' + chunk + '&display=swap" rel="stylesheet">');
							}
						}
					});

					wrap.find('.mh-font-option').on('click', function() {
						var val = $(this).data('value');
						var name = $(this).data('name');
						
						valDisplay.text(name).css('font-family', '"' + name + '", sans-serif');
						wrap.find('.mh-font-option').css('background', '#fff');
						$(this).css('background', '#e6f0ff');
						dropdown.hide();
						
						loadFullFont(name);
						hiddenInput.val(val).trigger('change');
					});

					$(document).on('click', function() { dropdown.hide(); });

					function loadFullFont(name) {
						var linkId = 'mh-full-font-' + name.replace(/\s+/g, '-').toLowerCase();
						if ($('#' + linkId).length === 0) {
							$('head').append('<link id="' + linkId + '" href="https://fonts.googleapis.com/css2?family=' + name.replace(/\s+/g, '+') + ':wght@300;400;500;600;700&display=swap" rel="stylesheet">');
						}
					}
				});
			</script>
			<?php
		}
	}
}
