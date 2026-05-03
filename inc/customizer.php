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
	


	// Add Theme Colors Panel (Because WP does not support nested panels)
	$wp_customize->add_panel( 'magic_hat_colors_panel', array(
		'title'       => __( '🎭 Site Colors', 'xophz-magic-hat' ),
		'description' => 'Customize your core design system colors.',
		'priority'    => 30,
	) );

	// Define the groups and their settings
	$color_groups = array(
		'Brand' => array(
			'mh_color_brand_base'  => array('label' => 'Base', 'default' => '#62c9ff'),
			'mh_color_brand_hover' => array('label' => 'Hover', 'default' => '#8be0ff'),
			'mh_color_brand_active'=> array('label' => 'Active', 'default' => '#40a0df'),
			'mh_color_brand_muted' => array('label' => 'Muted', 'default' => '#1a3a4d'),
		),
		'Action (CTA)' => array(
			'mh_color_cta_base'    => array('label' => 'Base', 'default' => '#ff3366'),
			'mh_color_cta_hover'   => array('label' => 'Hover', 'default' => '#ff668c'),
			'mh_color_cta_active'  => array('label' => 'Active', 'default' => '#e62050'),
			'mh_color_cta_muted'   => array('label' => 'Muted', 'default' => '#4d1a26'),
		),
		'Links' => array(
			'mh_color_link'        => array('label' => 'Default', 'default' => '#62c9ff'),
			'mh_color_link_hover'  => array('label' => 'Hover', 'default' => '#ff3366'),
			'mh_color_link_active' => array('label' => 'Active', 'default' => '#e62050'),
			'mh_color_link_visited'=> array('label' => 'Visited', 'default' => '#9b59b6'),
		),
		'Text' => array(
			'mh_color_text_heading'=> array('label' => 'Heading', 'default' => '#ffffff'),
			'mh_color_text_main'   => array('label' => 'Main', 'default' => '#f8fafc'),
			'mh_color_text_muted'  => array('label' => 'Muted', 'default' => '#94a3b8'),
			'mh_color_text_inverse'=> array('label' => 'Inverse', 'default' => '#0f172a'),
		),
		'Surfaces & Layers' => array(
			'mh_color_body'        => array('label' => 'Body (Base)', 'default' => '#0a0b10'),
			'mh_color_main'        => array('label' => 'Main Background', 'default' => '#0f172a'),
			'mh_color_section'     => array('label' => 'Section', 'default' => 'rgba(255, 255, 255, 0.02)'),
			'mh_color_card'        => array('label' => 'Card', 'default' => 'rgba(255, 255, 255, 0.05)'),
		),
		'Borders & Lines' => array(
			'mh_color_border_base'  => array('label' => 'Base', 'default' => '#334155'),
			'mh_color_border_hover' => array('label' => 'Hover', 'default' => '#475569'),
			'mh_color_border_focus' => array('label' => 'Focus', 'default' => '#62c9ff'),
			'mh_color_border_muted' => array('label' => 'Muted/Divider', 'default' => '#1e293b'),
		),
		'Status System' => array(
			'mh_color_success'     => array('label' => 'Success', 'default' => '#10b981'),
			'mh_color_warning'     => array('label' => 'Warning', 'default' => '#f59e0b'),
			'mh_color_danger'      => array('label' => 'Danger', 'default' => '#ef4444'),
			'mh_color_info'        => array('label' => 'Info', 'default' => '#3b82f6'),
		),
	);

	$div_count = 0;
	foreach ( $color_groups as $group_label => $settings ) {
		$div_count++;
		$section_id = 'mh_colors_' . sanitize_title( $group_label );
		
		$wp_customize->add_section( $section_id, array(
			'title'    => $group_label,
			'panel'    => 'magic_hat_colors_panel',
			'priority' => 10 + $div_count,
		) );

		foreach ( $settings as $id => $data ) {
			$wp_customize->add_setting( $id, array( 'default' => $data['default'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array( 'label' => $data['label'], 'section' => $section_id ) ) );
		}
	}

	// ==============================================
	// SECTION: Editor Settings
	// ==============================================
	$wp_customize->add_section( 'mh_colors_editor_settings', array(
		'title'    => __( 'Editor Settings', 'xophz-magic-hat' ),
		'panel'    => 'magic_hat_colors_panel',
		'priority' => 99,
	) );
	
	$wp_customize->add_setting( 'mh_enforce_site_colors', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	
	$wp_customize->add_control( 'mh_enforce_site_colors', array(
		'type'        => 'checkbox',
		'section'     => 'mh_colors_editor_settings',
		'label'       => __( 'Enforce Site Colors', 'xophz-magic-hat' ),
		'description' => __( 'When enabled, editors can only choose from the colors defined above.', 'xophz-magic-hat' ),
	) );

	// ==============================================
	// SECTION: Typography
	// ==============================================
	$wp_customize->add_section( 'magic_hat_typography', array(
		'title'    => __( '🪶 Typography', 'xophz-magic-hat' ),
		'priority' => 31,
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
		'title'    => __( '👆 Buttons', 'xophz-magic-hat' ),
		'priority' => 32,
	) );
	$wp_customize->add_setting( 'mh_button_radius', array( 'default' => '4' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_button_radius', array(
		'label'       => __( 'Border Radius', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'input_attrs' => array( 'min' => 0, 'max' => 50, 'step' => 1, 'unit' => 'px' ),
	) ) );

	$wp_customize->add_setting( 'mh_button_padding_y', array( 'default' => '12' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_button_padding_y', array(
		'label'       => __( 'Vertical Padding', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'input_attrs' => array( 'min' => 0, 'max' => 40, 'step' => 1, 'unit' => 'px' ),
	) ) );

	$wp_customize->add_setting( 'mh_button_padding_x', array( 'default' => '24' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_button_padding_x', array(
		'label'       => __( 'Horizontal Padding', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'input_attrs' => array( 'min' => 0, 'max' => 80, 'step' => 1, 'unit' => 'px' ),
	) ) );

	$wp_customize->add_setting( 'mh_button_border_width', array( 'default' => '2' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_button_border_width', array(
		'label'       => __( 'Border Width', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'input_attrs' => array( 'min' => 0, 'max' => 10, 'step' => 1, 'unit' => 'px' ),
	) ) );

	$wp_customize->add_setting( 'mh_button_font_weight', array( 'default' => '600' ) );
	$wp_customize->add_control( 'mh_button_font_weight', array(
		'label'       => __( 'Font Weight', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'type'        => 'select',
		'choices'     => array(
			'300' => '300 - Light',
			'400' => '400 - Normal',
			'500' => '500 - Medium',
			'600' => '600 - Semi-Bold',
			'700' => '700 - Bold',
			'800' => '800 - Extra Bold',
			'900' => '900 - Black',
		),
	) );

	$wp_customize->add_setting( 'mh_button_text_transform', array( 'default' => 'none' ) );
	$wp_customize->add_control( 'mh_button_text_transform', array(
		'label'       => __( 'Text Transform', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'type'        => 'select',
		'choices'     => array(
			'none'      => 'None',
			'uppercase' => 'UPPERCASE',
			'lowercase' => 'lowercase',
			'capitalize'=> 'Capitalize',
		),
	) );

	$wp_customize->add_setting( 'mh_button_letter_spacing', array( 'default' => '0' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_button_letter_spacing', array(
		'label'       => __( 'Letter Spacing', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_buttons',
		'input_attrs' => array( 'min' => -5, 'max' => 10, 'step' => 0.1, 'unit' => 'px' ),
	) ) );

	// ==============================================
	// SECTION: Forms
	// ==============================================
	$wp_customize->add_section( 'magic_hat_forms', array(
		'title'    => __( '📜 Forms & Inputs', 'xophz-magic-hat' ),
		'priority' => 33,
	) );
	
	$wp_customize->add_setting( 'mh_form_radius', array( 'default' => '4' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_form_radius', array(
		'label'       => __( 'Input Border Radius', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_forms',
		'input_attrs' => array( 'min' => 0, 'max' => 50, 'step' => 1, 'unit' => 'px' ),
	) ) );

	// ==============================================
	// SECTION: Cards
	// ==============================================
	$wp_customize->add_section( 'magic_hat_cards', array(
		'title'    => __( '🃏 Cards & Grids', 'xophz-magic-hat' ),
		'priority' => 34,
	) );
	
	$wp_customize->add_setting( 'mh_card_radius', array( 'default' => '8' ) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_card_radius', array(
		'label'       => __( 'Card Border Radius', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_cards',
		'input_attrs' => array( 'min' => 0, 'max' => 50, 'step' => 1, 'unit' => 'px' ),
	) ) );

}
add_action( 'customize_register', 'xophz_magic_hat_customize_register' );

/**
 * Inject Global Style Guide Toggle into Customizer Sidebar
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
			// Add hover styles matching the native Customizer X button
			$('head').append('<style>#mh-toggle-sb:hover, #mh-toggle-home:hover { background: #f0f0f1; color: #2271b1 !important; }</style>');
			
			var styleguideBtn = $(
				'<button type="button" id="mh-toggle-sb" title="Stylebook" style="width: 45px; height: 46px; border: none; border-right: 1px solid #ddd; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #50575e; box-sizing: border-box; padding: 0;">🎩</button>'
			);
			var homeBtn = $(
				'<button type="button" id="mh-toggle-home" title="Homepage" style="width: 45px; height: 46px; border: none; border-right: 1px solid #ddd; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #50575e; box-sizing: border-box; padding: 0;"><span class="dashicons dashicons-admin-home" style="font-size: 20px; width: 20px; height: 20px;"></span></button>'
			);
			
			// Inject next to the Close button at the top left
			var actionWrapper = $('<div style="position: absolute; left: 45px; top: 0; bottom: 0; display: flex; align-items: center;"></div>');
			actionWrapper.append(homeBtn).append(styleguideBtn);
			$('#customize-header-actions').append(actionWrapper);

			$('#mh-toggle-sb').on('click', function(e) {
				e.preventDefault();
				wp.customize.previewer.previewUrl('<?php echo esc_url( home_url( '?magic_hat_stylebook=1' ) ); ?>');
			});
			$('#mh-toggle-home').on('click', function(e) {
				e.preventDefault();
				wp.customize.previewer.previewUrl('<?php echo esc_url( home_url( '/' ) ); ?>');
			});

			// Anchor scrolling in Style Guide when sections are expanded
			wp.customize.bind('ready', function() {
				wp.customize.state('expandedSection').bind(function(section) {
					if (section) {
						var map = {
							'magic_hat_colors': 'section-colors',
							'magic_hat_typography': 'section-typography',
							'magic_hat_buttons': 'section-buttons',
							'magic_hat_forms': 'section-forms',
							'magic_hat_cards': 'section-cards',
							'nav_menus': 'section-menus',
							'magic_hat_media': 'section-media',
							'magic_hat_post': 'section-post',
							'magic_hat_comments': 'section-comments'
						};
						if (map[section.id]) {
							wp.customize.previewer.send('mh-scroll-to', map[section.id]);
						}
					}
				});
			});



		});
	</script>
	<style>
		/* Magic Hat Styles Panel Styling */
		#accordion-panel-magic_hat_options > h3.accordion-section-title {
			background: linear-gradient(135deg, #1e1e2f 0%, #10101b 100%);
			color: #fff !important;
			border-left: 4px solid #62c9ff;
			box-shadow: 0 2px 10px rgba(0,0,0,0.2);
			transition: all 0.3s ease;
		}
		#accordion-panel-magic_hat_options > h3.accordion-section-title:hover {
			background: linear-gradient(135deg, #2a2a3f 0%, #1a1a2b 100%);
			color: #62c9ff !important;
			border-left-color: #ff3366;
		}
		#accordion-panel-magic_hat_options > h3.accordion-section-title:after {
			color: rgba(255, 255, 255, 0.7);
		}
		#accordion-panel-magic_hat_options > h3.accordion-section-title:hover:after {
			color: #fff;
		}
	</style>
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
		'brand-base'    => get_theme_mod( 'mh_color_brand_base', '#62c9ff' ),
		'brand-hover'   => get_theme_mod( 'mh_color_brand_hover', '#8be0ff' ),
		'brand-active'  => get_theme_mod( 'mh_color_brand_active', '#40a0df' ),
		'brand-muted'   => get_theme_mod( 'mh_color_brand_muted', '#1a3a4d' ),
		
		'cta-base'      => get_theme_mod( 'mh_color_cta_base', '#ff3366' ),
		'cta-hover'     => get_theme_mod( 'mh_color_cta_hover', '#ff668c' ),
		'cta-active'    => get_theme_mod( 'mh_color_cta_active', '#e62050' ),
		'cta-muted'     => get_theme_mod( 'mh_color_cta_muted', '#4d1a26' ),
		'link'          => get_theme_mod( 'mh_color_link', '#62c9ff' ),
		'link-hover'    => get_theme_mod( 'mh_color_link_hover', '#ff3366' ),
		'link-active'   => get_theme_mod( 'mh_color_link_active', '#e62050' ),
		'link-visited'  => get_theme_mod( 'mh_color_link_visited', '#9b59b6' ),
		'body'          => get_theme_mod( 'mh_color_body', '#0a0b10' ),
		'main'          => get_theme_mod( 'mh_color_main', '#0f172a' ),
		'section'       => get_theme_mod( 'mh_color_section', 'rgba(255, 255, 255, 0.02)' ),
		'card'          => get_theme_mod( 'mh_color_card', 'rgba(255, 255, 255, 0.05)' ),
		'border-base'   => get_theme_mod( 'mh_color_border_base', '#334155' ),
		'border-hover'  => get_theme_mod( 'mh_color_border_hover', '#475569' ),
		'border-focus'  => get_theme_mod( 'mh_color_border_focus', '#62c9ff' ),
		'border-muted'  => get_theme_mod( 'mh_color_border_muted', '#1e293b' ),
		'text-main'     => get_theme_mod( 'mh_color_text_main', '#f8fafc' ),
		'text-muted'    => get_theme_mod( 'mh_color_text_muted', '#94a3b8' ),
		'text-inverse'  => get_theme_mod( 'mh_color_text_inverse', '#0f172a' ),
		'success'       => get_theme_mod( 'mh_color_success', '#10b981' ),
		'warning'       => get_theme_mod( 'mh_color_warning', '#f59e0b' ),
		'danger'        => get_theme_mod( 'mh_color_danger', '#ef4444' ),
		'info'          => get_theme_mod( 'mh_color_info', '#3b82f6' ),
	);
	?>
	<style type="text/css">
		@import url('<?php echo esc_url($font_url); ?>');

		:root {
			--mh-font-family: <?php echo esc_attr( $font_family ); ?>;
			--mh-font-heading: <?php echo esc_attr( $font_family ); ?>;
			--mh-font-body: <?php echo esc_attr( $font_family ); ?>;
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

			<?php foreach ( $colors as $key => $val ) : ?>
			--mh-color-<?php echo esc_attr($key); ?>: <?php echo esc_attr( $val ); ?>;
			<?php 
			// Only output -rgb if it's a hex color, skip if it's already rgba
			if ( strpos($val, '#') !== false ) : ?>
			--mh-color-<?php echo esc_attr($key); ?>-rgb: <?php echo esc_attr( mh_hex2rgb($val) ); ?>;
			<?php endif; ?>
			<?php endforeach; ?>
			
			--mh-font-family: <?php echo esc_attr( $font_family ); ?>;
			--mh-border-radius: <?php echo esc_attr( get_theme_mod( 'mh_button_radius', '4' ) ); ?>px;
			--mh-btn-padding-y: <?php echo esc_attr( get_theme_mod( 'mh_button_padding_y', '12' ) ); ?>px;
			--mh-btn-padding-x: <?php echo esc_attr( get_theme_mod( 'mh_button_padding_x', '24' ) ); ?>px;
			--mh-btn-border-width: <?php echo esc_attr( get_theme_mod( 'mh_button_border_width', '2' ) ); ?>px;
			--mh-btn-font-weight: <?php echo esc_attr( get_theme_mod( 'mh_button_font_weight', '600' ) ); ?>;
			--mh-btn-text-transform: <?php echo esc_attr( get_theme_mod( 'mh_button_text_transform', 'none' ) ); ?>;
			--mh-btn-letter-spacing: <?php echo esc_attr( get_theme_mod( 'mh_button_letter_spacing', '0' ) ); ?>px;
			--mh-form-radius: <?php echo esc_attr( get_theme_mod( 'mh_form_radius', '4' ) ); ?>px;
			--mh-card-radius: <?php echo esc_attr( get_theme_mod( 'mh_card_radius', '8' ) ); ?>px;
		}
		body {
			background-color: var(--mh-color-body);
			font-family: var(--mh-font-family);
			color: var(--mh-color-text-main);
		}
		a {
			color: var(--mh-color-link);
		}
		.btn-primary { background: var(--mh-color-cta-base); color: var(--mh-color-text-inverse); }
		.btn-secondary { background: var(--mh-color-cta-muted); color: var(--mh-color-text-main); }
	</style>
	<?php
}
add_action( 'wp_head', 'xophz_magic_hat_customizer_css' );

/**
 * Virtual Page: Render the Stylebook
 */
function xophz_magic_hat_stylebook_template() {
	if ( isset( $_GET['magic_hat_stylebook'] ) && $_GET['magic_hat_stylebook'] == '1' ) {
		nocache_headers();
		require_once get_template_directory() . '/inc/stylebook-template.php';
		exit;
	}
}
add_action( 'template_redirect', 'xophz_magic_hat_stylebook_template' );
