<?php
/**
 * Xophz Magic Hat Theme Customizer
 */

function xophz_magic_hat_customize_register( $wp_customize ) {

	// Custom Range Slider Control
	class Magic_Hat_Range_Slider_Control extends WP_Customize_Control {
		public $type = 'range';
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<div style="display:flex; align-items:center; gap: 10px; margin-top: 5px;">
					<input type="range" 
						value="<?php echo esc_attr( floatval($this->value()) ); ?>" 
						<?php $this->link(); ?> 
						min="<?php echo esc_attr( $this->input_attrs['min'] ?? 0 ); ?>" 
						max="<?php echo esc_attr( $this->input_attrs['max'] ?? 100 ); ?>" 
						step="<?php echo esc_attr( $this->input_attrs['step'] ?? 1 ); ?>"
						style="flex: 1;"
						oninput="this.nextElementSibling.value = this.value + '<?php echo esc_attr( $this->input_attrs['unit'] ?? '' ); ?>'"
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

	// Custom Font Picker Control
	class Magic_Hat_Font_Control extends WP_Customize_Control {
		public $type = 'mh_font';
		public function render_content() {
			$current_val = $this->value();
			$current_name = explode(',', $current_val)[0];
			?>
			<div class="mh-font-picker-wrap" style="margin-bottom: 15px;">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				
				<div class="mh-faux-select" style="position: relative;">
					<div class="mh-faux-value" style="padding: 8px 10px; background: #fff; border: 1px solid #8c8f94; cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-radius: 3px; overflow: hidden;">
						<span class="mh-font-name" style="font-family: '<?php echo esc_attr($current_name); ?>', sans-serif; font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 85%;"><?php echo esc_html($current_name); ?></span>
						<span class="dashicons dashicons-arrow-down-alt2" style="flex-shrink: 0;"></span>
					</div>
					<div class="mh-faux-dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #8c8f94; border-top: none; max-height: 65vh; overflow-y: auto; z-index: 99999; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
						<?php foreach ( $this->choices as $value => $label ) : 
							$font_name = explode(',', $value)[0];
							$is_active = ($current_val === $value);
						?>
							<div class="mh-font-option" data-value="<?php echo esc_attr($value); ?>" data-name="<?php echo esc_attr($font_name); ?>" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #f0f0f1; font-size: 18px; background: <?php echo $is_active ? '#e6f0ff' : '#fff'; ?>;">
								<?php echo esc_html($font_name); ?>
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
					loadFullFont('<?php echo esc_js($current_name); ?>');

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
	
	// Add Magic Hat Global Panel
	$wp_customize->add_panel( 'magic_hat_options', array(
		'title'       => __( 'Magic Hat Options', 'xophz-magic-hat' ),
		'description' => 'Global styling options for your generated theme.',
		'priority'    => 10,
	) );

	// ==============================================
	// SECTION: Colors
	// ==============================================
	$wp_customize->add_section( 'magic_hat_colors', array(
		'title'    => __( 'Theme Colors', 'xophz-magic-hat' ),
		'panel'    => 'magic_hat_options',
	) );

	// Brand
	$wp_customize->add_setting( 'mh_color_brand', array( 'default' => '#00E5FF', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_color_brand', array( 'label' => __( 'Brand', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Text
	$wp_customize->add_setting( 'mh_color_text', array( 'default' => '#333333', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_color_text', array( 'label' => __( 'Text', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Link
	$wp_customize->add_setting( 'mh_color_link', array( 'default' => '#0056b3', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_color_link', array( 'label' => __( 'Link', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Primary CTA
	$wp_customize->add_setting( 'mh_color_primary_cta', array( 'default' => '#007BFF', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_color_primary_cta', array( 'label' => __( 'Primary CTA', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Secondary CTA
	$wp_customize->add_setting( 'mh_color_secondary_cta', array( 'default' => '#6C757D', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_color_secondary_cta', array( 'label' => __( 'Secondary CTA', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Success
	$wp_customize->add_setting( 'mh_color_success', array( 'default' => '#28A745', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_color_success', array( 'label' => __( 'Success', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Warning
	$wp_customize->add_setting( 'mh_color_warning', array( 'default' => '#FFC107', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_color_warning', array( 'label' => __( 'Warning', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Danger
	$wp_customize->add_setting( 'mh_color_danger', array( 'default' => '#DC3545', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_color_danger', array( 'label' => __( 'Danger', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Info
	$wp_customize->add_setting( 'mh_color_info', array( 'default' => '#17A2B8', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_color_info', array( 'label' => __( 'Info', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// ==============================================
	// SECTION: Backgrounds (Part of Colors)
	// ==============================================

	// Body Background Color
	$wp_customize->add_setting( 'mh_bg_body', array( 'default' => '#f0f0f1', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_body', array( 'label' => __( 'Body Background', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Main Background Color
	$wp_customize->add_setting( 'mh_bg_main', array( 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_main', array( 'label' => __( 'Main Background', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// Section Background Color
	$wp_customize->add_setting( 'mh_bg_section', array( 'default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'refresh' ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_section', array( 'label' => __( 'Section Background', 'xophz-magic-hat' ), 'section' => 'magic_hat_colors' ) ) );

	// ==============================================
	// SECTION: Typography
	// ==============================================
	$wp_customize->add_section( 'magic_hat_typography', array(
		'title'    => __( 'Typography', 'xophz-magic-hat' ),
		'panel'    => 'magic_hat_options',
	) );

	// Base Font Family
	$wp_customize->add_setting( 'mh_font_family', array(
		'default'           => 'Inter, sans-serif',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$google_fonts = array(
		'Abril Fatface, display' => 'Abril Fatface',
		'Acme, sans-serif' => 'Acme',
		'Aleo, serif' => 'Aleo',
		'Alfa Slab One, display' => 'Alfa Slab One',
		'Amatic SC, cursive' => 'Amatic SC',
		'Anton, sans-serif' => 'Anton',
		'Archivo, sans-serif' => 'Archivo',
		'Arimo, sans-serif' => 'Arimo',
		'Asap, sans-serif' => 'Asap',
		'Barlow, sans-serif' => 'Barlow',
		'Bebas Neue, display' => 'Bebas Neue',
		'Bitter, serif' => 'Bitter',
		'Bree Serif, serif' => 'Bree Serif',
		'Cabin, sans-serif' => 'Cabin',
		'Cardo, serif' => 'Cardo',
		'Catamaran, sans-serif' => 'Catamaran',
		'Caveat, cursive' => 'Caveat',
		'Comfortaa, cursive' => 'Comfortaa',
		'Cormorant Garamond, serif' => 'Cormorant Garamond',
		'Crimson Text, serif' => 'Crimson Text',
		'DM Sans, sans-serif' => 'DM Sans',
		'Dancing Script, cursive' => 'Dancing Script',
		'Domine, serif' => 'Domine',
		'Dosis, sans-serif' => 'Dosis',
		'EB Garamond, serif' => 'EB Garamond',
		'Exo 2, sans-serif' => 'Exo 2',
		'Fira Code, monospace' => 'Fira Code',
		'Fira Sans, sans-serif' => 'Fira Sans',
		'Fjalla One, sans-serif' => 'Fjalla One',
		'Fraunces, serif' => 'Fraunces',
		'Heebo, sans-serif' => 'Heebo',
		'Hind, sans-serif' => 'Hind',
		'IBM Plex Mono, monospace' => 'IBM Plex Mono',
		'IBM Plex Sans, sans-serif' => 'IBM Plex Sans',
		'Inconsolata, monospace' => 'Inconsolata',
		'Inter, sans-serif' => 'Inter',
		'JetBrains Mono, monospace' => 'JetBrains Mono',
		'Josefin Sans, sans-serif' => 'Josefin Sans',
		'Jost, sans-serif' => 'Jost',
		'Kanit, sans-serif' => 'Kanit',
		'Karla, sans-serif' => 'Karla',
		'Lato, sans-serif' => 'Lato',
		'Libre Baskerville, serif' => 'Libre Baskerville',
		'Lora, serif' => 'Lora',
		'Manrope, sans-serif' => 'Manrope',
		'Maven Pro, sans-serif' => 'Maven Pro',
		'Merriweather, serif' => 'Merriweather',
		'Merriweather Sans, sans-serif' => 'Merriweather Sans',
		'Montserrat, sans-serif' => 'Montserrat',
		'Mukta, sans-serif' => 'Mukta',
		'Mulish, sans-serif' => 'Mulish',
		'Noto Sans, sans-serif' => 'Noto Sans',
		'Noto Serif, serif' => 'Noto Serif',
		'Nunito, sans-serif' => 'Nunito',
		'Open Sans, sans-serif' => 'Open Sans',
		'Oswald, sans-serif' => 'Oswald',
		'Outfit, sans-serif' => 'Outfit',
		'Oxygen, sans-serif' => 'Oxygen',
		'PT Sans, sans-serif' => 'PT Sans',
		'PT Serif, serif' => 'PT Serif',
		'Pacifico, cursive' => 'Pacifico',
		'Patua One, display' => 'Patua One',
		'Playfair Display, serif' => 'Playfair Display',
		'Poppins, sans-serif' => 'Poppins',
		'Prompt, sans-serif' => 'Prompt',
		'Public Sans, sans-serif' => 'Public Sans',
		'Questrial, sans-serif' => 'Questrial',
		'Quicksand, sans-serif' => 'Quicksand',
		'Raleway, sans-serif' => 'Raleway',
		'Righteous, display' => 'Righteous',
		'Roboto, sans-serif' => 'Roboto',
		'Roboto Condensed, sans-serif' => 'Roboto Condensed',
		'Roboto Mono, monospace' => 'Roboto Mono',
		'Roboto Slab, serif' => 'Roboto Slab',
		'Rokkitt, serif' => 'Rokkitt',
		'Rubik, sans-serif' => 'Rubik',
		'Shadows Into Light, cursive' => 'Shadows Into Light',
		'Signika, sans-serif' => 'Signika',
		'Sora, sans-serif' => 'Sora',
		'Source Code Pro, monospace' => 'Source Code Pro',
		'Space Grotesk, sans-serif' => 'Space Grotesk',
		'Space Mono, monospace' => 'Space Mono',
		'Spectral, serif' => 'Spectral',
		'Syne, sans-serif' => 'Syne',
		'Teko, sans-serif' => 'Teko',
		'Titillium Web, sans-serif' => 'Titillium Web',
		'Ubuntu, sans-serif' => 'Ubuntu',
		'Varela Round, sans-serif' => 'Varela Round',
		'Vollkorn, serif' => 'Vollkorn',
		'Work Sans, sans-serif' => 'Work Sans',
		'Zilla Slab, serif' => 'Zilla Slab'
	);

	$wp_customize->add_control( new Magic_Hat_Font_Control( $wp_customize, 'mh_font_family', array(
		'label'    => __( 'Base Font Family', 'xophz-magic-hat' ),
		'section'  => 'magic_hat_typography',
		'choices'  => $google_fonts,
	) ) );

	// Base Font Size
	$wp_customize->add_setting( 'mh_font_size', array( 'default' => '16' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_font_size', array(
		'label'       => __( 'Base / Paragraph Font Size (px)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_typography',
		'input_attrs' => array( 'min' => 12, 'max' => 24, 'step' => 1 ),
	) ) );

	// Base Line Height
	$wp_customize->add_setting( 'mh_line_height', array( 'default' => '1.6' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_line_height', array(
		'label'       => __( 'Base Line Height', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_typography',
		'input_attrs' => array( 'min' => 1.0, 'max' => 2.5, 'step' => 0.1 ),
	) ) );

	// Heading Weight
	$wp_customize->add_setting( 'mh_heading_weight', array( 'default' => '600' ) );
	$wp_customize->add_control( 'mh_heading_weight', array(
		'label'    => __( 'Heading Font Weight', 'xophz-magic-hat' ),
		'section'  => 'magic_hat_typography',
		'type'     => 'select',
		'choices'  => array(
			'300' => '300 - Light',
			'400' => '400 - Normal',
			'500' => '500 - Medium',
			'600' => '600 - Semi-Bold',
			'700' => '700 - Bold',
			'800' => '800 - Extra Bold',
			'900' => '900 - Black',
		),
	) );

	// Heading Line Height
	$wp_customize->add_setting( 'mh_heading_line_height', array( 'default' => '1.2' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_heading_line_height', array(
		'label'       => __( 'Heading Line Height', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_typography',
		'input_attrs' => array( 'min' => 0.8, 'max' => 2.0, 'step' => 0.05 ),
	) ) );

	// H1 - H6 Sizes
	$headings = array(
		'1' => 48,
		'2' => 36,
		'3' => 28,
		'4' => 24,
		'5' => 20,
		'6' => 16,
	);
	foreach ( $headings as $level => $default_size ) {
		$wp_customize->add_setting( 'mh_font_size_h' . $level, array( 'default' => $default_size ) );
		$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_font_size_h' . $level, array(
			'label'       => sprintf( __( 'H%s Font Size (px)', 'xophz-magic-hat' ), $level ),
			'section'     => 'magic_hat_typography',
			'input_attrs' => array( 'min' => 10, 'max' => 120, 'step' => 1 ),
		) ) );
	}

	// ==============================================
	// SECTION: Buttons
	// ==============================================
	$wp_customize->add_section( 'magic_hat_buttons', array(
		'title'    => __( 'Buttons', 'xophz-magic-hat' ),
		'panel'    => 'magic_hat_options',
	) );
	$wp_customize->add_setting( 'mh_button_radius', array( 'default' => '4' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_button_radius', array(
		'label'       => __( 'Button Border Radius', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'input_attrs' => array( 'min' => 0, 'max' => 50, 'step' => 1, 'unit' => 'px' ),
	) ) );

	// ==============================================
	// SECTION: Forms
	// ==============================================
	$wp_customize->add_section( 'magic_hat_forms', array(
		'title'    => __( 'Forms & Inputs', 'xophz-magic-hat' ),
		'panel'    => 'magic_hat_options',
	) );

	// ==============================================
	// SECTION: Cards
	// ==============================================
	$wp_customize->add_section( 'magic_hat_cards', array(
		'title'    => __( 'Cards & Grids', 'xophz-magic-hat' ),
		'panel'    => 'magic_hat_options',
	) );

}
add_action( 'customize_register', 'xophz_magic_hat_customize_register' );

/**
 * Inject Global Stylebook Toggle into Customizer Sidebar
 */
function xophz_magic_hat_customize_controls_scripts() {
	?>
	<style>
		#customize-theme-controls .customize-pane-child.accordion-section-content {
			padding: 12px;
			height: 100%;
		}
	</style>
	<script>
		jQuery(document).ready(function($) {
			var stylebookBtn = $(
				'<div id="mh-global-stylebook-toggle" style="position: absolute; bottom: 0; left: 0; width: 100%; background: #fff; border-top: 1px solid #ddd; padding: 15px; box-sizing: border-box; z-index: 500000; display: flex; gap: 10px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">' + 
					'<button type="button" class="button button-primary" id="mh-toggle-sb" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;"><span class="dashicons dashicons-visibility" style="margin-top: 2px;"></span> Stylebook</button>' +
					'<button type="button" class="button" id="mh-toggle-home" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;"><span class="dashicons dashicons-admin-home" style="margin-top: 2px;"></span> Homepage</button>' +
				'</div>'
			);
			
			$('#customize-controls').append(stylebookBtn);
			
			// Add padding to the bottom of the sidebar to prevent overlap with our new sticky footer
			$('#customize-theme-controls .wp-full-overlay-sidebar-content').css('padding-bottom', '80px');

			$('#mh-toggle-sb').on('click', function(e) {
				e.preventDefault();
				wp.customize.previewer.previewUrl('<?php echo esc_url( home_url( '?magic_hat_stylebook=1' ) ); ?>');
			});
			$('#mh-toggle-home').on('click', function(e) {
				e.preventDefault();
				wp.customize.previewer.previewUrl('<?php echo esc_url( home_url( '/' ) ); ?>');
			});

			// Anchor scrolling in Stylebook when sections are expanded
			wp.customize.bind('ready', function() {
				wp.customize.state('expandedSection').bind(function(section) {
					if (section) {
						var map = {
							'magic_hat_colors': 'section-colors',
							'magic_hat_typography': 'section-typography',
							'magic_hat_buttons': 'section-buttons'
						};
						if (map[section.id]) {
							wp.customize.previewer.send('mh-scroll-to', map[section.id]);
						}
					}
				});
			});
		});
	</script>
	<?php
}
add_action( 'customize_controls_print_footer_scripts', 'xophz_magic_hat_customize_controls_scripts' );

/**
 * Output Customizer CSS variables in the <head>
 */
function mh_hex2rgb($hex) {
	$hex = str_replace('#', '', $hex);
	if (strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	return "$r, $g, $b";
}

function xophz_magic_hat_customizer_css() {
	$font_family = get_theme_mod( 'mh_font_family', 'Inter, sans-serif' );
	$font_size   = get_theme_mod( 'mh_font_size', '16' );
	$line_height = get_theme_mod( 'mh_line_height', '1.6' );
	$heading_weight = get_theme_mod( 'mh_heading_weight', '600' );
	$heading_lh     = get_theme_mod( 'mh_heading_line_height', '1.2' );
	
	// Extract font name for Google Fonts API (e.g. "Space Grotesk, sans-serif" -> "Space Grotesk")
	$font_name = trim( explode(',', $font_family)[0] );
	$font_url = 'https://fonts.googleapis.com/css2?family=' . urlencode($font_name) . ':wght@300;400;500;600;700;800;900&display=swap';
	
	// Fetch Colors
	$colors = array(
		'brand'         => get_theme_mod( 'mh_color_brand', '#00E5FF' ),
		'text'          => get_theme_mod( 'mh_color_text', '#333333' ),
		'link'          => get_theme_mod( 'mh_color_link', '#0056b3' ),
		'primary-cta'   => get_theme_mod( 'mh_color_primary_cta', '#007BFF' ),
		'secondary-cta' => get_theme_mod( 'mh_color_secondary_cta', '#6C757D' ),
		'success'       => get_theme_mod( 'mh_color_success', '#28A745' ),
		'warning'       => get_theme_mod( 'mh_color_warning', '#FFC107' ),
		'danger'        => get_theme_mod( 'mh_color_danger', '#DC3545' ),
		'info'          => get_theme_mod( 'mh_color_info', '#17A2B8' ),
	);
	?>
	<style type="text/css">
		@import url('<?php echo esc_url($font_url); ?>');

		:root {
			--mh-font-size: <?php echo esc_attr( $font_size ); ?>px;
			--mh-line-height: <?php echo esc_attr( $line_height ); ?>;
			--mh-heading-weight: <?php echo esc_attr( $heading_weight ); ?>;
			--mh-heading-line-height: <?php echo esc_attr( $heading_lh ); ?>;
			
			--mh-font-size-h1: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h1', 48 ) ); ?>px;
			--mh-font-size-h2: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h2', 36 ) ); ?>px;
			--mh-font-size-h3: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h3', 28 ) ); ?>px;
			--mh-font-size-h4: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h4', 24 ) ); ?>px;
			--mh-font-size-h5: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h5', 20 ) ); ?>px;
			--mh-font-size-h6: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h6', 16 ) ); ?>px;

			<?php foreach ( $colors as $key => $hex ) : ?>
			--mh-color-<?php echo esc_attr($key); ?>: <?php echo esc_attr( $hex ); ?>;
			--mh-color-<?php echo esc_attr($key); ?>-rgb: <?php echo esc_attr( mh_hex2rgb($hex) ); ?>;
			<?php endforeach; ?>
			
			--mh-font-family: <?php echo esc_attr( $font_family ); ?>;
			--mh-font-size: <?php echo esc_attr( get_theme_mod( 'mh_font_size', '16' ) ); ?>px;
			--mh-line-height: <?php echo esc_attr( get_theme_mod( 'mh_line_height', '1.6' ) ); ?>;
			--mh-border-radius: <?php echo esc_attr( get_theme_mod( 'mh_button_radius', '4' ) ); ?>px;
			
			--mh-bg-body: <?php echo esc_attr( get_theme_mod( 'mh_bg_body', '#f0f0f1' ) ); ?>;
			--mh-bg-main: <?php echo esc_attr( get_theme_mod( 'mh_bg_main', '#ffffff' ) ); ?>;
			--mh-bg-section: <?php echo esc_attr( get_theme_mod( 'mh_bg_section', '#ffffff' ) ); ?>;
		}
		body {
			background-color: var(--mh-bg-body);
			font-family: var(--mh-font-family);
			color: var(--mh-color-text);
		}
		a {
			color: var(--mh-color-link);
		}
		.btn-primary { background: var(--mh-color-primary-cta); color: #fff; }
		.btn-secondary { background: var(--mh-color-secondary-cta); color: #fff; }
	</style>
	<?php
}
add_action( 'wp_head', 'xophz_magic_hat_customizer_css' );

/**
 * Virtual Page: Render the Stylebook
 */
function xophz_magic_hat_stylebook_template() {
	require_once get_template_directory() . '/inc/stylebook-template.php';
}
add_action( 'template_redirect', 'xophz_magic_hat_stylebook_template' );
