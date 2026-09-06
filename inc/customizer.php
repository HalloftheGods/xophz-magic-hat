<?php
/**
 * Xophz Magic Hat Theme Customizer
 */

/**
 * Check if the current Customizer preview is the Stylebook
 */
function mh_is_stylebook_preview() {
	return ( isset( $_GET['magic_hat_stylebook'] ) && $_GET['magic_hat_stylebook'] == '1' );
}

/**
 * Canonical 28-token Design Token Palette definitions for Light (Day), Twilight, and Dark (Night) modes.
 * Single Source of Truth for Customizer, Circadian Rhythm Engine, and Gutenberg Palette.
 *
 * @return array
 */
function mh_get_color_definitions() {
	return array(
		// Brand
		'brand-base'    => array( 'group' => 'Brand', 'label' => 'Base',          'name' => 'Brand Base',   'slug' => 'brand-base',   'light' => '#2563eb', 'twilight' => '#38bdf8', 'dark' => '#62c9ff' ),
		'brand-hover'   => array( 'group' => 'Brand', 'label' => 'Hover',         'name' => 'Brand Hover',  'slug' => 'brand-hover',  'light' => '#3b82f6', 'twilight' => '#7dd3fc', 'dark' => '#8be0ff' ),
		'brand-active'  => array( 'group' => 'Brand', 'label' => 'Active',        'name' => 'Brand Active', 'slug' => 'brand-active', 'light' => '#1d4ed8', 'twilight' => '#0284c7', 'dark' => '#40a0df' ),
		'brand-muted'   => array( 'group' => 'Brand', 'label' => 'Muted',         'name' => 'Brand Muted',  'slug' => 'brand-muted',  'light' => '#dbeafe', 'twilight' => '#0c4a6e', 'dark' => '#1a3a4d' ),

		// Action (CTA)
		'cta-base'      => array( 'group' => 'Action (CTA)', 'label' => 'Base',   'name' => 'Action Base',   'slug' => 'cta-base',      'light' => '#ff3366', 'twilight' => '#fb7185', 'dark' => '#ff3366' ),
		'cta-hover'     => array( 'group' => 'Action (CTA)', 'label' => 'Hover',  'name' => 'Action Hover',  'slug' => 'cta-hover',     'light' => '#ff668c', 'twilight' => '#fda4af', 'dark' => '#ff668c' ),
		'cta-active'    => array( 'group' => 'Action (CTA)', 'label' => 'Active', 'name' => 'Action Active', 'slug' => 'cta-active',    'light' => '#e62050', 'twilight' => '#f43f5e', 'dark' => '#e62050' ),
		'cta-muted'     => array( 'group' => 'Action (CTA)', 'label' => 'Muted',  'name' => 'Action Muted',  'slug' => 'cta-muted',     'light' => '#ffe4e6', 'twilight' => '#4c0519', 'dark' => '#4d1a26' ),

		// Links
		'link'          => array( 'group' => 'Links', 'label' => 'Default',       'name' => 'Link Default', 'slug' => 'link-default', 'light' => '#2563eb', 'twilight' => '#38bdf8', 'dark' => '#62c9ff' ),
		'link-hover'    => array( 'group' => 'Links', 'label' => 'Hover',         'name' => 'Link Hover',   'slug' => 'link-hover',   'light' => '#ff3366', 'twilight' => '#fb7185', 'dark' => '#ff3366' ),
		'link-active'   => array( 'group' => 'Links', 'label' => 'Active',        'name' => 'Link Active',  'slug' => 'link-active',  'light' => '#1d4ed8', 'twilight' => '#fda4af', 'dark' => '#e62050' ),
		'link-visited'  => array( 'group' => 'Links', 'label' => 'Visited',       'name' => 'Link Visited', 'slug' => 'link-visited', 'light' => '#7c3aed', 'twilight' => '#a78bfa', 'dark' => '#9b59b6' ),

		// Text
		'text-heading'  => array( 'group' => 'Text', 'label' => 'Heading',        'name' => 'Text Heading', 'slug' => 'text-heading', 'light' => '#0f172a', 'twilight' => '#f8fafc', 'dark' => '#ffffff' ),
		'text-main'     => array( 'group' => 'Text', 'label' => 'Main',           'name' => 'Text Main',    'slug' => 'text-main',    'light' => '#334155', 'twilight' => '#e2e8f0', 'dark' => '#f8fafc' ),
		'text-muted'    => array( 'group' => 'Text', 'label' => 'Muted',          'name' => 'Text Muted',   'slug' => 'text-muted',   'light' => '#64748b', 'twilight' => '#94a3b8', 'dark' => '#94a3b8' ),
		'text-inverse'  => array( 'group' => 'Text', 'label' => 'Inverse',        'name' => 'Text Inverse', 'slug' => 'text-inverse', 'light' => '#ffffff', 'twilight' => '#0f172a', 'dark' => '#0a0b10' ),

		// Surfaces & Layers
		'body'          => array( 'group' => 'Surfaces & Layers', 'label' => 'Body (Base)',      'name' => 'Body (Base)',      'slug' => 'surface-body',    'light' => '#ffffff', 'twilight' => '#0f172a', 'dark' => '#0a0b10' ),
		'main'          => array( 'group' => 'Surfaces & Layers', 'label' => 'Main Background', 'name' => 'Main Background', 'slug' => 'surface-main',    'light' => '#ffffff', 'twilight' => '#1e293b', 'dark' => '#0f172a' ),
		'section'       => array( 'group' => 'Surfaces & Layers', 'label' => 'Section',         'name' => 'Section Layer',    'slug' => 'surface-section', 'light' => '#f8fafc', 'twilight' => '#162235', 'dark' => 'rgba(255, 255, 255, 0.03)' ),
		'card'          => array( 'group' => 'Surfaces & Layers', 'label' => 'Card',            'name' => 'Card Layer',       'slug' => 'surface-card',    'light' => '#ffffff', 'twilight' => '#1e293b', 'dark' => 'rgba(255, 255, 255, 0.05)' ),

		// Borders & Lines
		'border-base'   => array( 'group' => 'Borders & Lines', 'label' => 'Base',          'name' => 'Border Base',  'slug' => 'border-base',  'light' => '#e2e8f0', 'twilight' => '#334155', 'dark' => 'rgba(255, 255, 255, 0.12)' ),
		'border-hover'  => array( 'group' => 'Borders & Lines', 'label' => 'Hover',         'name' => 'Border Hover', 'slug' => 'border-hover', 'light' => '#cbd5e1', 'twilight' => '#475569', 'dark' => '#62c9ff' ),
		'border-focus'  => array( 'group' => 'Borders & Lines', 'label' => 'Focus',         'name' => 'Border Focus', 'slug' => 'border-focus', 'light' => '#2563eb', 'twilight' => '#38bdf8', 'dark' => '#62c9ff' ),
		'border-muted'  => array( 'group' => 'Borders & Lines', 'label' => 'Muted/Divider', 'name' => 'Border Muted', 'slug' => 'border-muted', 'light' => '#e2e8f0', 'twilight' => '#1e293b', 'dark' => 'rgba(255, 255, 255, 0.06)' ),

		// Status System
		'success'       => array( 'group' => 'Status System', 'label' => 'Success', 'name' => 'Status Success', 'slug' => 'status-success', 'light' => '#10b981', 'twilight' => '#34d399', 'dark' => '#10b981' ),
		'warning'       => array( 'group' => 'Status System', 'label' => 'Warning', 'name' => 'Status Warning', 'slug' => 'status-warning', 'light' => '#f59e0b', 'twilight' => '#fbbf24', 'dark' => '#f59e0b' ),
		'danger'        => array( 'group' => 'Status System', 'label' => 'Danger',  'name' => 'Status Danger',  'slug' => 'status-danger',  'light' => '#ef4444', 'twilight' => '#f87171', 'dark' => '#ef4444' ),
		'info'          => array( 'group' => 'Status System', 'label' => 'Info',    'name' => 'Status Info',    'slug' => 'status-info',    'light' => '#3b82f6', 'twilight' => '#60a5fa', 'dark' => '#3b82f6' ),
	);
}

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
	
	// Custom AI Page Architect Control
	class Magic_Hat_AI_Architect_Control extends WP_Customize_Control {
		public $type = 'mh-ai-architect-control';

		public function render_content() {
			$connectors = class_exists( 'Magic_Hat_AI_Architect' ) ? Magic_Hat_AI_Architect::get_available_connectors() : array();
			$default_connector = 'gemini';
			if ( ! empty( $connectors['gemini']['configured'] ) ) {
				$default_connector = 'gemini';
			} elseif ( ! empty( $connectors['anthropic']['configured'] ) ) {
				$default_connector = 'anthropic';
			} elseif ( ! empty( $connectors['openai']['configured'] ) ) {
				$default_connector = 'openai';
			} elseif ( ! empty( $connectors['openrouter']['configured'] ) ) {
				$default_connector = 'openrouter';
			} elseif ( ! empty( $connectors['ollama']['configured'] ) ) {
				$default_connector = 'ollama';
			} else {
				$default_connector = 'procedural';
			}
			$pages = get_pages();
			?>
			<script>
				window.mhAiConnectors = <?php echo wp_json_encode( $connectors ); ?>;
				window.mhAiNonce = <?php echo wp_json_encode( wp_create_nonce( 'wp_rest' ) ); ?>;
				window.mhAiRestUrl = <?php echo wp_json_encode( esc_url_raw( rest_url() ) ); ?>;
			</script>
			<div class="mh-ai-architect-container">
				<div class="mh-ai-header" style="background: linear-gradient(135deg, #0a0b10 0%, #1e293b 100%); border: 1px solid rgba(98, 201, 255, 0.3); border-radius: 6px; padding: 12px; margin-bottom: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
						<span style="font-size: 11px; font-weight: 800; color: #62c9ff; letter-spacing: 1px; text-transform: uppercase;">✨ AI Page Architect</span>
						<span id="mh-ai-status-badge" style="font-size: 10px; padding: 2px 6px; border-radius: 4px; background: rgba(16, 185, 129, 0.2); color: #10b981; font-weight: 600;">
							Loading...
						</span>
					</div>
					<p style="margin: 0; font-size: 11px; color: #94a3b8; line-height: 1.4;">
						Prompt the architect to conjure complete Gutenberg pages across customizable vibes, archetypes, and models.
					</p>
				</div>

				<div class="mh-ai-field" style="margin-bottom: 12px;">
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
						<label for="mh-ai-connector" style="font-size: 11px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px;">
							AI Connector:
						</label>
						<a href="<?php echo esc_url( admin_url( 'options-general.php?page=wp-connectors' ) ); ?>" target="_blank" style="font-size: 10px; color: #2563eb; text-decoration: none;" title="Configure API keys in WP Connectors">
							⚙️ Connectors
						</a>
					</div>
					<select id="mh-ai-connector" style="width: 100%;">
						<?php foreach ( $connectors as $cid => $cinfo ) : ?>
							<option value="<?php echo esc_attr( $cid ); ?>" <?php selected( $cid, $default_connector ); ?> data-configured="<?php echo ! empty( $cinfo['configured'] ) ? '1' : '0'; ?>">
								<?php echo ! empty( $cinfo['configured'] ) ? '● ' : '○ '; ?><?php echo esc_html( $cinfo['name'] ); ?><?php echo ! empty( $cinfo['configured'] ) ? '' : ' (Not Configured)'; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="mh-ai-field" style="margin-bottom: 14px;">
					<label for="mh-ai-model" style="display: block; font-size: 11px; font-weight: 700; color: #1e293b; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">
						Model:
					</label>
					<select id="mh-ai-model" style="width: 100%;">
						<?php 
						$default_models = ! empty( $connectors[$default_connector]['models'] ) ? $connectors[$default_connector]['models'] : array();
						$default_model_id = ! empty( $connectors[$default_connector]['default_model'] ) ? $connectors[$default_connector]['default_model'] : '';
						foreach ( $default_models as $dm ) : ?>
							<option value="<?php echo esc_attr( $dm['id'] ); ?>" <?php selected( $dm['id'], $default_model_id ); ?>>
								<?php echo esc_html( $dm['name'] ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="mh-ai-field" style="margin-bottom: 14px;">
					<label for="mh-ai-prompt" style="display: block; font-size: 11px; font-weight: 700; color: #1e293b; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">
						Prompt Vision / Objectives:
					</label>
					<textarea id="mh-ai-prompt" rows="3" placeholder="e.g. Build an autonomous SaaS launch page with hero section, 3-card feature matrix, interactive metrics, and conversion banner..." style="width: 100%; border: 1px solid #cbd5e1; border-radius: 4px; padding: 8px; font-size: 12px; line-height: 1.4; resize: vertical; box-sizing: border-box;"></textarea>
				</div>

				<div class="mh-ai-field" style="margin-bottom: 14px;">
					<label for="mh-ai-vibe" style="display: block; font-size: 11px; font-weight: 700; color: #1e293b; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">
						Visual Vibe (Atmosphere):
					</label>
					<select id="mh-ai-vibe" style="width: 100%; margin-bottom: 8px;">
						<option value="starship-neon" selected>🌌 Starship Neon (Deep obsidian, cyan glow)</option>
						<option value="minimal-glass">💎 Minimal Glass (Translucent frosted clean)</option>
						<option value="cyberpunk-dusk">⚡ Cyberpunk Dusk (High contrast magenta &amp; cyan)</option>
						<option value="solar-dawn">🌅 Solar Dawn (Warm golden hour lighting)</option>
						<option value="enterprise-clean">🏢 Enterprise Clean (Navy slate corporate)</option>
						<option value="creative-studio">🎨 Creative Studio (Asymmetric editorial pop)</option>
					</select>
					<div class="mh-vibe-pills" style="display: flex; flex-wrap: wrap; gap: 4px;">
						<button type="button" class="mh-vibe-pill active" data-vibe="starship-neon">Starship</button>
						<button type="button" class="mh-vibe-pill" data-vibe="minimal-glass">Glass</button>
						<button type="button" class="mh-vibe-pill" data-vibe="cyberpunk-dusk">Cyberpunk</button>
						<button type="button" class="mh-vibe-pill" data-vibe="solar-dawn">Solar</button>
						<button type="button" class="mh-vibe-pill" data-vibe="enterprise-clean">Enterprise</button>
						<button type="button" class="mh-vibe-pill" data-vibe="creative-studio">Studio</button>
					</div>
				</div>

				<div class="mh-ai-field" style="margin-bottom: 14px;">
					<label for="mh-ai-archetype" style="display: block; font-size: 11px; font-weight: 700; color: #1e293b; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">
						Layout Archetype:
					</label>
					<select id="mh-ai-archetype" style="width: 100%; margin-bottom: 8px;">
						<option value="landing" selected>🚀 SaaS Landing (Hero + Features + Metrics + CTA)</option>
						<option value="portfolio">💼 Portfolio Showcase (Split Hero + Project Masonry)</option>
						<option value="saas">⚡ Product Platform (Value Hero + Pricing Matrix)</option>
						<option value="editorial">📖 Editorial Story (Cover Hero + Longform Grid)</option>
						<option value="microhub">🍱 Bento Micro-Hub (Bento Dashboard Grid)</option>
					</select>
					<div class="mh-arch-pills" style="display: flex; flex-wrap: wrap; gap: 4px;">
						<button type="button" class="mh-arch-pill active" data-arch="landing">Landing</button>
						<button type="button" class="mh-arch-pill" data-arch="portfolio">Portfolio</button>
						<button type="button" class="mh-arch-pill" data-arch="saas">Platform</button>
						<button type="button" class="mh-arch-pill" data-arch="editorial">Editorial</button>
						<button type="button" class="mh-arch-pill" data-arch="microhub">Bento</button>
					</div>
				</div>

				<div class="mh-ai-field" style="margin-bottom: 14px;">
					<label for="mh-ai-target-page" style="display: block; font-size: 11px; font-weight: 700; color: #1e293b; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">
						Destination Page:
					</label>
					<select id="mh-ai-target-page" style="width: 100%;">
						<option value="0">Current Customizer Canvas (Preview Only)</option>
						<?php foreach ( $pages as $p ) : ?>
							<option value="<?php echo esc_attr( $p->ID ); ?>"><?php echo esc_html( $p->post_title ); ?> (ID: <?php echo esc_html( $p->ID ); ?>)</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="mh-ai-actions" style="margin-bottom: 14px;">
					<button type="button" id="mh-ai-conjure-btn" class="button button-primary" style="width: 100%; height: 38px; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border-color: #0284c7; font-weight: 700; font-size: 13px; text-shadow: none; box-shadow: 0 2px 6px rgba(2,132,199,0.3); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
						<span>✨ Conjure Page with AI</span>
					</button>
					<button type="button" id="mh-ai-save-to-page" class="button button-secondary" style="width: 100%; margin-top: 6px; display: none;">
						💾 Save to Database
					</button>
				</div>

				<div id="mh-ai-status" style="display: none; padding: 8px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 11px; color: #334155; margin-bottom: 14px;">
				</div>

				<div class="mh-ai-presets-wrap" style="border-top: 1px solid #e2e8f0; padding-top: 12px; margin-bottom: 14px;">
					<span style="display: block; font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">1-Click Instant Archetypes:</span>
					<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
						<button type="button" class="button mh-preset-btn" data-prompt="Autonomous developer platform with CLI tools, SDK integrations, and cloud clusters" data-vibe="starship-neon" data-arch="saas" style="font-size: 10px; text-align: left; padding: 4px 8px; height: auto;">🚀 SaaS Platform</button>
						<button type="button" class="button mh-preset-btn" data-prompt="Interactive digital laboratory showcase with case studies and spatial components" data-vibe="minimal-glass" data-arch="portfolio" style="font-size: 10px; text-align: left; padding: 4px 8px; height: auto;">💼 Glass Portfolio</button>
						<button type="button" class="button mh-preset-btn" data-prompt="High-converting product launch with dark obsidian gradients and action banners" data-vibe="cyberpunk-dusk" data-arch="landing" style="font-size: 10px; text-align: left; padding: 4px 8px; height: auto;">⚡ Cyber Launch</button>
						<button type="button" class="button mh-preset-btn" data-prompt="Longform manifesto on circadian rhythm design systems and spatial software" data-vibe="solar-dawn" data-arch="editorial" style="font-size: 10px; text-align: left; padding: 4px 8px; height: auto;">📖 Editorial Story</button>
					</div>
				</div>

				<div class="mh-ai-history-wrap" style="border-top: 1px solid #e2e8f0; padding-top: 12px;">
					<span style="display: block; font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Session Layout History:</span>
					<ul id="mh-ai-history-list" style="margin: 0; padding: 0; list-style: none;">
						<li style="color: #8c8f94; font-size: 11px; padding: 6px 0;">No layouts conjured yet.</li>
					</ul>
				</div>
			</div>
			<?php
		}
	}
	
	// Custom Page Builder Control
	class Magic_Hat_Page_Builder_Control extends WP_Customize_Control {
		public $type = 'mh-page-builder-control';

		public function render_content() {
			$is_active = class_exists( 'Xophz_Compass_Magic_Wand' ) || defined( 'XOPHZ_COMPASS_MAGIC_WAND_VERSION' );
			$show_on_front = get_option( 'show_on_front', 'posts' );
			$is_magic_hat = ( $show_on_front === 'page' );
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
				#mh_page_rows { list-style: none; margin: 0; padding: 0; }
				#mh_page_rows .empty { color: #b0b0b0; font-size: 11px; text-transform: uppercase; font-weight: 600; letter-spacing: 1.5px; padding: 18px 10px; text-align: center; border: 1px dashed #d5d5d5; border-radius: 3px; background: #f9f9f9; }
				#mh_page_rows .mh-section-item { background: #fff; border: 1px solid #e2e2e2; margin-bottom: 6px; padding: 8px 10px; display: flex; justify-content: space-between; align-items: center; border-radius: 3px; cursor: move; transition: border-color 0.15s; }
				#mh_page_rows .mh-section-item:hover { border-color: #2563eb; }
				.mh-add-section-wrap { margin-top: 10px; }
				.mh-add-section-wrap .mh-add-section { width: 100%; padding: 8px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; font-size: 11px; border-radius: 3px; border: none; cursor: pointer; transition: opacity 0.2s; }
				.mh-add-section-wrap .mh-add-section:hover { opacity: 0.85; }
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
					<p style="margin: 0; color: #dc3232; font-weight: 600; font-size: 11px;"><?php _e('Magic Wand Required', 'xophz-magic-hat'); ?></p>
					<p style="margin: 3px 0 0; font-size: 11px; color: #888; line-height: 1.3;"><?php _e('Activate the companion plugin to unlock this.', 'xophz-magic-hat'); ?></p>
				</div>
			<?php endif; ?>

			<div class="mh-add-section-wrap">
				<button type="button" class="button button-primary mh-add-section" style="<?php echo $is_active ? 'background: #2563eb; border-color: #2563eb; color: #fff;' : 'opacity: 0.4; cursor: not-allowed;'; ?>" <?php disabled( ! $is_active ); ?>><?php _e( '+ Add Section', 'xophz-magic-hat' ); ?></button>
			</div>

			<input type="hidden" id="mh_page_sections_input" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>">
			<?php
		}
	}

	// AI Page Architect Section (Top Priority in Customizer)
	$wp_customize->add_section( 'mh_ai_page_architect', array(
		'title'       => __( '✨ AI Page Architect', 'xophz-magic-hat' ),
		'description' => __( 'Prompt Gemini or the internal layout synthesizer to conjure custom Gutenberg pages across diverse vibes and archetypes.', 'xophz-magic-hat' ),
		'priority'    => 10,
	) );

	$wp_customize->add_setting( 'mh_ai_generated_blocks', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new Magic_Hat_AI_Architect_Control( $wp_customize, 'mh_ai_generated_blocks', array(
		'section' => 'mh_ai_page_architect',
	) ) );

	$wp_customize->add_section( 'mh_page_builder', array(
		'title'    => __( '🏗️ Page Builder', 'xophz-magic-hat' ),
		'priority' => 90,
	) );

	// Setting to store JSON data for the page sections
	$wp_customize->add_setting( 'mh_page_sections', array(
		'default'           => '[]',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	
	$wp_customize->add_control( new Magic_Hat_Page_Builder_Control( $wp_customize, 'mh_page_sections', array(
		'section' => 'mh_page_builder',
	) ) );

	// Custom Group Title / Subheading Control
	class Magic_Hat_Group_Title_Control extends WP_Customize_Control {
		public $type = 'mh_group_title';
		public function render_content() {
			?>
			<div class="mh-customizer-group-heading" style="margin-top: 14px; margin-bottom: 6px; padding-top: 10px; border-top: 1px solid #dcdcde;">
				<h4 style="margin: 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #1d2327;"><?php echo esc_html( $this->label ); ?></h4>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description" style="margin-top: 2px; display: block;"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
			</div>
			<?php
		}
	}

	// ==============================================
	// PANEL: Site Styles (Parent Panel)
	// ==============================================
	$wp_customize->add_panel( 'magic_hat_site_styles', array(
		'title'       => __( '🧑‍🎨 Site Styles', 'xophz-magic-hat' ),
		'description' => __( 'Manage global design system styling: background canvas, colors, typography, spacing, buttons, and custom CSS.', 'xophz-magic-hat' ),
		'priority'    => 30,
	) );

	// ==============================================
	// PANEL: Site Colors (Dedicated Panel with Sub-Sections)
	// ==============================================
	$wp_customize->add_panel( 'magic_hat_colors_panel', array(
		'title'       => __( '🎨 Site Colors', 'xophz-magic-hat' ),
		'description' => __( 'Customize your core design system colors and circadian schedule behavior.', 'xophz-magic-hat' ),
		'priority'    => 35,
	) );

	// Schedule & Mode Override Section
	$wp_customize->add_section( 'mh_colors_schedule', array(
		'title'    => __( '⏱️ Day / Night Schedule & Color Mode', 'xophz-magic-hat' ),
		'panel'    => 'magic_hat_colors_panel',
		'priority' => 1,
	) );

	$wp_customize->add_setting( 'mh_color_schedule_mode', array(
		'default'           => 'circadian',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'mh_color_schedule_mode', array(
		'type'        => 'select',
		'section'     => 'mh_colors_schedule',
		'label'       => __( 'Color Schedule Behavior', 'xophz-magic-hat' ),
		'description' => __( 'Select whether theme colors dynamically shift with the 24-hour astronomical circadian clock or stay permanently locked to Light, Twilight, or Dark.', 'xophz-magic-hat' ),
		'choices'     => array(
			'circadian' => __( '🌅 Circadian Rhythm (Dynamic 24h astronomical sync)', 'xophz-magic-hat' ),
			'light'     => __( '☀️ Always Light (Static 24/7)', 'xophz-magic-hat' ),
			'twilight'  => __( '🌅 Always Twilight (Static 24/7)', 'xophz-magic-hat' ),
			'dark'      => __( '🌙 Always Dark (Static 24/7)', 'xophz-magic-hat' ),
		),
	) );

	// Group the canonical definitions for Customizer sections
	$color_defs = mh_get_color_definitions();
	$color_groups = array();
	foreach ( $color_defs as $key => $data ) {
		$group = $data['group'];
		$setting_id = 'mh_color_' . str_replace( '-', '_', $key );
		if ( ! isset( $color_groups[ $group ] ) ) {
			$color_groups[ $group ] = array();
		}
		$color_groups[ $group ][ $setting_id ] = $data;
	}

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
			// Light Mode
			$wp_customize->add_setting( $id, array( 'default' => $data['light'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
				'label'   => $data['label'] . ' (Light)',
				'section' => $section_id,
			) ) );

			// Twilight Mode
			$twi_id = $id . '_twilight';
			$wp_customize->add_setting( $twi_id, array( 'default' => $data['twilight'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $twi_id, array(
				'label'   => $data['label'] . ' (Twilight)',
				'section' => $section_id,
			) ) );

			// Dark Mode
			$dark_id = $id . '_dark';
			$wp_customize->add_setting( $dark_id, array( 'default' => $data['dark'], 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $dark_id, array(
				'label'   => $data['label'] . ' (Dark)',
				'section' => $section_id,
			) ) );
		}
	}

	// ==============================================
	// SECTION: Site Background & Animated Canvases
	// ==============================================
	$wp_customize->add_section( 'magic_hat_background', array(
		'title'       => __( '🖼️ Site Background & Canvas', 'xophz-magic-hat' ),
		'description' => __( 'Configure your site canvas: choose standard daylight theme surface, solid color, gradient, custom image, or one of 21 interactive generative animated canvas backgrounds.', 'xophz-magic-hat' ),
		'priority'    => 10,
		'panel'       => 'magic_hat_site_styles',
	) );

	// Contextual active callbacks for background mode controls
	function mh_is_bg_mode_solid( $control ) {
		return $control->manager->get_setting( 'mh_bg_mode' ) && $control->manager->get_setting( 'mh_bg_mode' )->value() === 'solid';
	}

	function mh_is_bg_mode_gradient( $control ) {
		return $control->manager->get_setting( 'mh_bg_mode' ) && $control->manager->get_setting( 'mh_bg_mode' )->value() === 'gradient';
	}

	function mh_is_bg_mode_image( $control ) {
		return $control->manager->get_setting( 'mh_bg_mode' ) && $control->manager->get_setting( 'mh_bg_mode' )->value() === 'image';
	}

	function mh_is_bg_mode_canvas( $control ) {
		return $control->manager->get_setting( 'mh_bg_mode' ) && $control->manager->get_setting( 'mh_bg_mode' )->value() === 'canvas';
	}

	// Background Mode
	$wp_customize->add_setting( 'mh_bg_mode', array(
		'default'           => 'default',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'mh_bg_mode', array(
		'type'        => 'select',
		'section'     => 'magic_hat_background',
		'label'       => __( 'Background Mode', 'xophz-magic-hat' ),
		'choices'     => array(
			'default'  => __( 'Default Circadian Theme Surface', 'xophz-magic-hat' ),
			'solid'    => __( 'Solid Color', 'xophz-magic-hat' ),
			'gradient' => __( 'Linear Gradient', 'xophz-magic-hat' ),
			'image'    => __( 'Custom Background Image', 'xophz-magic-hat' ),
			'canvas'   => __( '✨ Animated Generative Canvas (21 Presets)', 'xophz-magic-hat' ),
		),
	) );

	// Solid Color (Only shown when mode is solid)
	$wp_customize->add_setting( 'mh_bg_solid_color', array(
		'default'           => '#0a0b10',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_solid_color', array(
		'label'           => __( 'Solid Background Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_solid',
	) ) );

	// Gradient Start & End (Only shown when mode is gradient)
	$wp_customize->add_setting( 'mh_bg_gradient_start', array(
		'default'           => '#0f172a',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_gradient_start', array(
		'label'           => __( 'Gradient Start Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_gradient',
	) ) );

	$wp_customize->add_setting( 'mh_bg_gradient_end', array(
		'default'           => '#020617',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_gradient_end', array(
		'label'           => __( 'Gradient End Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_gradient',
	) ) );

	// Background Image Controls (Only shown when mode is image)
	$wp_customize->add_setting( 'mh_bg_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mh_bg_image', array(
		'label'           => __( 'Background Image', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_image',
	) ) );

	$wp_customize->add_setting( 'mh_bg_image_size', array(
		'default'           => 'cover',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'mh_bg_image_size', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Image Sizing', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_image',
		'choices'         => array(
			'cover'   => __( 'Fit to Screen (Cover)', 'xophz-magic-hat' ),
			'contain' => __( 'Fit Inside Screen (Contain)', 'xophz-magic-hat' ),
			'auto'    => __( 'Original Size (Auto)', 'xophz-magic-hat' ),
		),
	) );

	$wp_customize->add_setting( 'mh_bg_image_repeat', array(
		'default'           => 'no-repeat',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'mh_bg_image_repeat', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Repeat / Tile', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_image',
		'choices'         => array(
			'no-repeat' => __( 'Do Not Repeat', 'xophz-magic-hat' ),
			'repeat'    => __( 'Tile Horizontally & Vertically', 'xophz-magic-hat' ),
			'repeat-x'  => __( 'Tile Horizontally', 'xophz-magic-hat' ),
			'repeat-y'  => __( 'Tile Vertically', 'xophz-magic-hat' ),
		),
	) );

	$wp_customize->add_setting( 'mh_bg_image_position', array(
		'default'           => 'center center',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'mh_bg_image_position', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Image Position', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_image',
		'choices'         => array(
			'center center' => __( 'Center Center', 'xophz-magic-hat' ),
			'top center'    => __( 'Top Center', 'xophz-magic-hat' ),
			'top left'      => __( 'Top Left', 'xophz-magic-hat' ),
			'top right'     => __( 'Top Right', 'xophz-magic-hat' ),
			'bottom center' => __( 'Bottom Center', 'xophz-magic-hat' ),
		),
	) );

	$wp_customize->add_setting( 'mh_bg_image_attachment', array(
		'default'           => 'fixed',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'mh_bg_image_attachment', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Scroll Behavior', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_image',
		'choices'         => array(
			'fixed'  => __( 'Fixed (Parallax)', 'xophz-magic-hat' ),
			'scroll' => __( 'Scroll with Page', 'xophz-magic-hat' ),
		),
	) );

	$wp_customize->add_setting( 'mh_bg_image_bg_color', array(
		'default'           => '#0a0b10',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_image_bg_color', array(
		'label'           => __( 'Underlying Background Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_image',
	) ) );

	// Canvas Preset (21 Presets - Only shown when mode is canvas)
	$wp_customize->add_setting( 'mh_bg_canvas_preset', array(
		'default'           => 'electric-wave',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_bg_canvas_preset', array(
		'type'            => 'select',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Animated Canvas Preset', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_canvas',
		'choices'         => array(
			'electric-wave'     => __( '⚡ Electric Waves', 'xophz-magic-hat' ),
			'aurora-smoke'      => __( '🌌 Aurora Smoke', 'xophz-magic-hat' ),
			'celestial-cosmos'  => __( '✨ Celestial Cosmos', 'xophz-magic-hat' ),
			'quantum-particles' => __( '⚛️ Quantum Particles', 'xophz-magic-hat' ),
			'cyber-matrix'      => __( '💻 Cyber Matrix', 'xophz-magic-hat' ),
			'tesseract-4d'      => __( '🧊 Tesseract 4D Grid', 'xophz-magic-hat' ),
			'bubblegum'         => __( '🫧 Bubblegum Spheres', 'xophz-magic-hat' ),
			'alphabet-soup'     => __( '🍜 Alphabet Soup Noodles', 'xophz-magic-hat' ),
			'midnight-nerd'     => __( '🌆 Midnight Synthwave', 'xophz-magic-hat' ),
			'wormhole'          => __( '🌀 Wormhole Tunnel', 'xophz-magic-hat' ),
			'sun-corona'        => __( '☀️ Sun Corona', 'xophz-magic-hat' ),
			'saturn-rings'      => __( '🪐 Saturn Rings', 'xophz-magic-hat' ),
			'fluid-mesh'        => __( '💧 Fluid Ambient Mesh', 'xophz-magic-hat' ),
			'wizards-tower'     => __( '🧙 Wizards Tower Runes', 'xophz-magic-hat' ),
			'magic-formula'     => __( '🧪 Magic Formula Flask', 'xophz-magic-hat' ),
			'enchiridion'       => __( '📜 Enchiridion Neural Net', 'xophz-magic-hat' ),
			'omega-source'      => __( '💫 Omega Source Vortex', 'xophz-magic-hat' ),
			'telescope'         => __( '🔭 Telescope Deep Space', 'xophz-magic-hat' ),
			'logos'             => __( '💎 Logos Constellation', 'xophz-magic-hat' ),
			'nucleos'           => __( '🔬 Nucleos Atomic Orbits', 'xophz-magic-hat' ),
			'jupiter-gravity'   => __( '🪐 Jupiter Gravitational Lensing', 'xophz-magic-hat' ),
		),
	) );

	// Canvas Tint Color (Only shown when mode is canvas)
	$wp_customize->add_setting( 'mh_bg_canvas_color', array(
		'default'           => '#2563eb',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mh_bg_canvas_color', array(
		'label'           => __( 'Canvas Accent / Tint Color', 'xophz-magic-hat' ),
		'section'         => 'magic_hat_background',
		'active_callback' => 'mh_is_bg_mode_canvas',
	) ) );

	// Canvas Opacity (Only shown when mode is canvas)
	$wp_customize->add_setting( 'mh_bg_canvas_opacity', array(
		'default'           => '0.6',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_bg_canvas_opacity', array(
		'type'            => 'number',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Canvas Opacity (0.1 - 1.0)', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_canvas',
		'input_attrs'     => array( 'min' => '0.1', 'max' => '1.0', 'step' => '0.05' ),
	) );

	// Canvas Speed (Only shown when mode is canvas)
	$wp_customize->add_setting( 'mh_bg_canvas_speed', array(
		'default'           => '1.0',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_bg_canvas_speed', array(
		'type'            => 'number',
		'section'         => 'magic_hat_background',
		'label'           => __( 'Animation Speed Multiplier (0.2 - 3.0)', 'xophz-magic-hat' ),
		'active_callback' => 'mh_is_bg_mode_canvas',
		'input_attrs'     => array( 'min' => '0.2', 'max' => '3.0', 'step' => '0.1' ),
	) );

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
		'title'           => __( '🪶 Typography', 'xophz-magic-hat' ),
		'priority'        => 30,
		'panel'           => 'magic_hat_site_styles',
	) );

	// Base Font Family
	$wp_customize->add_setting( 'mh_font_family', array(
		'default'           => 'Inter, sans-serif',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
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
	$wp_customize->add_setting( 'mh_font_size', array(
		'default'           => '16',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_font_size', array(
		'label'       => __( 'Base / Paragraph Font Size (px)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_typography',
		'input_attrs' => array( 'min' => 12, 'max' => 24, 'step' => 1 ),
	) ) );

	// Base Line Height
	$wp_customize->add_setting( 'mh_line_height', array(
		'default'           => '1.6',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_line_height', array(
		'label'       => __( 'Base Line Height', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_typography',
		'input_attrs' => array( 'min' => 1.0, 'max' => 2.5, 'step' => 0.1 ),
	) ) );

	// Heading Weight
	$wp_customize->add_setting( 'mh_heading_weight', array(
		'default'           => '600',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
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
	$wp_customize->add_setting( 'mh_heading_line_height', array(
		'default'           => '1.2',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
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
		$wp_customize->add_setting( 'mh_font_size_h' . $level, array(
			'default'           => $default_size,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_font_size_h' . $level, array(
			'label'       => sprintf( __( 'H%s Font Size (px)', 'xophz-magic-hat' ), $level ),
			'section'     => 'magic_hat_typography',
			'input_attrs' => array( 'min' => 10, 'max' => 120, 'step' => 1 ),
		) ) );
	}

	// ==============================================
	// SECTION: Spacing & Layout
	// ==============================================
	$wp_customize->add_section( 'magic_hat_spacing', array(
		'title'           => __( '📏 Spacing & Layout', 'xophz-magic-hat' ),
		'priority'        => 40,
		'panel'           => 'magic_hat_site_styles',
	) );
	
	$wp_customize->add_setting( 'mh_space_base', array(
		'default'           => '8',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_space_base', array(
		'label'       => __( 'Base Spacing Unit (px)', 'xophz-magic-hat' ),
		'description' => __( 'This is the master unit. All spacing scales proportionally from this base value.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_spacing',
		'input_attrs' => array( 'min' => 2, 'max' => 24, 'step' => 1 ),
	) ) );
	
	$wp_customize->add_setting( 'mh_content_width', array(
		'default'           => '1200',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_content_width', array(
		'label'       => __( 'Max Content Width (px)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_spacing',
		'input_attrs' => array( 'min' => 600, 'max' => 2400, 'step' => 10 ),
	) ) );

	$wp_customize->add_setting( 'mh_radius_base', array(
		'default'           => '4',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_radius_base', array(
		'label'       => __( 'Base Border Radius (px)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_spacing',
		'input_attrs' => array( 'min' => 0, 'max' => 50, 'step' => 1 ),
	) ) );

	$wp_customize->add_setting( 'mh_border_width', array(
		'default'           => '1',
		'sanitize_callback' => 'absint',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new Magic_Hat_Range_Slider_Control( $wp_customize, 'mh_border_width', array(
		'label'       => __( 'Global Border Width (px)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_spacing',
		'input_attrs' => array( 'min' => 0, 'max' => 10, 'step' => 1 ),
	) ) );

	// ==============================================
	// SECTION: Buttons
	// ==============================================
	$wp_customize->add_section( 'magic_hat_buttons', array(
		'title'           => __( '👆 Buttons', 'xophz-magic-hat' ),
		'priority'        => 50,
		'panel'           => 'magic_hat_site_styles',
	) );

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

	// Build navigation menus list for dropdown selector
	$all_nav_menus = wp_get_nav_menus();
	$menu_choices = array(
		'_primary' => __( 'Follow Location: Primary Menu', 'xophz-magic-hat' ),
	);
	if ( ! empty( $all_nav_menus ) && ! is_wp_error( $all_nav_menus ) ) {
		foreach ( $all_nav_menus as $nav_m ) {
			$menu_choices[ $nav_m->term_id ] = $nav_m->name;
		}
	}

	// ==============================================
	// SECTION: Header Settings
	// ==============================================
	$wp_customize->add_section( 'magic_hat_header', array(
		'title'       => __( '🎩 Header Settings', 'xophz-magic-hat' ),
		'description' => __( 'Configure header layout, navigation menu, sticky behavior, and mobile drawer.', 'xophz-magic-hat' ),
		'priority'    => 70,
	) );

	// Header Layout
	$wp_customize->add_setting( 'mh_header_layout', array(
		'default'           => 'standard',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_layout', array(
		'label'       => __( 'Header Layout', 'xophz-magic-hat' ),
		'description' => __( 'Select the visual arrangement of logo, navigation, and actions.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'select',
		'choices'     => array(
			'standard' => __( 'Standard (Logo Left, Nav Center/Right, CTA)', 'xophz-magic-hat' ),
			'centered' => __( 'Centered (Logo Top Center, Nav Below)', 'xophz-magic-hat' ),
			'split'    => __( 'Split (Nav Left & Right, Logo Center)', 'xophz-magic-hat' ),
			'minimal'  => __( 'Minimal (Logo Left, CTA & Hamburger Right)', 'xophz-magic-hat' ),
		),
	) );

	// Header Navigation Menu
	$wp_customize->add_setting( 'mh_header_menu', array(
		'default'           => '_primary',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_menu', array(
		'label'       => __( 'Navigation Menu', 'xophz-magic-hat' ),
		'description' => __( 'Choose which WordPress menu to render in the header.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'select',
		'choices'     => $menu_choices,
	) );

	// Sticky Header
	$wp_customize->add_setting( 'mh_header_sticky', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_sticky', array(
		'label'       => __( 'Enable Sticky Header', 'xophz-magic-hat' ),
		'description' => __( 'Keep header fixed to top on scroll with subtle blur.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'checkbox',
	) );

	// Header Container Width
	$wp_customize->add_setting( 'mh_header_width', array(
		'default'           => 'contained',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_width', array(
		'label'       => __( 'Header Width', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'select',
		'choices'     => array(
			'contained' => __( 'Contained (1200px max)', 'xophz-magic-hat' ),
			'full'      => __( 'Full Width (100%)', 'xophz-magic-hat' ),
		),
	) );

	// Show CTA Button
	$wp_customize->add_setting( 'mh_header_show_cta', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_show_cta', array(
		'label'       => __( 'Show Action Button (CTA)', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'checkbox',
	) );

	// CTA Button Text
	$wp_customize->add_setting( 'mh_header_cta_text', array(
		'default'           => __( 'Get Started', 'xophz-magic-hat' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_cta_text', array(
		'label'       => __( 'CTA Button Text', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'text',
	) );

	// CTA Button URL
	$wp_customize->add_setting( 'mh_header_cta_url', array(
		'default'           => '#contact',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_header_cta_url', array(
		'label'       => __( 'CTA Button URL', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_header',
		'type'        => 'text',
	) );

	// ==============================================
	// SECTION: Footer Settings
	// ==============================================
	$wp_customize->add_section( 'magic_hat_footer', array(
		'title'       => __( '🦶 Footer Settings', 'xophz-magic-hat' ),
		'description' => __( 'Configure footer layout, background theme, navigation columns, and copyright.', 'xophz-magic-hat' ),
		'priority'    => 100,
	) );

	// Footer Layout
	$wp_customize->add_setting( 'mh_footer_layout', array(
		'default'           => 'columns_4',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_layout', array(
		'label'       => __( 'Footer Layout', 'xophz-magic-hat' ),
		'description' => __( 'Select column layout or clean minimal presentation.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'select',
		'choices'     => array(
			'columns_4'        => __( '4-Column Mega Footer (Brand + 4 Menu Cols)', 'xophz-magic-hat' ),
			'columns_3'        => __( '3-Column Footer (Brand + 2 Menu Cols)', 'xophz-magic-hat' ),
			'minimal_centered' => __( 'Centered Minimal (Logo, Inline Nav, Copyright)', 'xophz-magic-hat' ),
			'split'            => __( 'Split Modern (Brand Left, Menus/Social Right)', 'xophz-magic-hat' ),
		),
	) );

	// Footer Background Style
	$wp_customize->add_setting( 'mh_footer_bg', array(
		'default'           => 'surface_section',
		'sanitize_callback' => 'sanitize_key',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_bg', array(
		'label'       => __( 'Footer Background', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'select',
		'choices'     => array(
			'surface_section' => __( 'Section Slate (#f8fafc)', 'xophz-magic-hat' ),
			'surface_white'   => __( 'Clean White (#ffffff)', 'xophz-magic-hat' ),
			'surface_dark'    => __( 'Deep Dark (#0f172a)', 'xophz-magic-hat' ),
		),
	) );

	// Show Footer Navigation Columns
	$wp_customize->add_setting( 'mh_footer_show_menus', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'mh_footer_show_menus', array(
		'label'       => __( 'Show Menu Columns', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'checkbox',
	) );

	// Copyright Text
	$wp_customize->add_setting( 'mh_footer_copyright_text', array(
		'default'           => '&copy; {year} {site_title}. All rights reserved.',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'mh_footer_copyright_text', array(
		'label'       => __( 'Copyright Text', 'xophz-magic-hat' ),
		'description' => __( 'Use {year} for current year and {site_title} for site name.', 'xophz-magic-hat' ),
		'section'     => 'magic_hat_footer',
		'type'        => 'textarea',
	) );

	// Social Links
	$social_networks = array(
		'facebook'  => 'Facebook',
		'twitter'   => 'Twitter (X)',
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'github'    => 'GitHub',
	);

	foreach ( $social_networks as $key => $label ) {
		$wp_customize->add_setting( 'mh_social_' . $key, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'mh_social_' . $key, array(
			'label'       => sprintf( __( '%s URL', 'xophz-magic-hat' ), $label ),
			'section'     => 'magic_hat_footer',
			'type'        => 'url',
		) );
	}

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Selective Refresh Partials for Header and Footer
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->remove_partial( 'site_icon' );

		// Header Partial
		$wp_customize->selective_refresh->add_partial( 'mh_header_partial', array(
			'selector'            => '#mw-header',
			'settings'            => array(
				'mh_header_layout',
				'mh_header_menu',
				'mh_header_sticky',
				'mh_header_width',
				'mh_header_show_cta',
				'mh_header_cta_text',
				'mh_header_cta_url',
				'blogname',
			),
			'container_inclusive' => true,
			'render_callback'     => 'mh_render_header_markup',
		) );

		// Footer Partial
		$wp_customize->selective_refresh->add_partial( 'mh_footer_partial', array(
			'selector'            => '#mw-footer',
			'settings'            => array(
				'mh_footer_layout',
				'mh_footer_bg',
				'mh_footer_show_menus',
				'mh_footer_copyright_text',
				'mh_social_facebook',
				'mh_social_twitter',
				'mh_social_instagram',
				'mh_social_linkedin',
				'mh_social_youtube',
				'mh_social_github',
				'blogname',
				'blogdescription',
			),
			'container_inclusive' => true,
			'render_callback'     => 'mh_render_footer_markup',
		) );
	}

	// Reorder core sections and panels to align with the design hierarchy
	if ( $wp_customize->get_section( 'title_tagline' ) ) {
		$wp_customize->get_section( 'title_tagline' )->title    = __( '🆔 Site Identity', 'xophz-magic-hat' );
		$wp_customize->get_section( 'title_tagline' )->priority = 20;
	}
	if ( $wp_customize->get_section( 'custom_css' ) ) {
		$wp_customize->get_section( 'custom_css' )->panel    = 'magic_hat_site_styles';
		$wp_customize->get_section( 'custom_css' )->priority = 60;
	}
	if ( $wp_customize->get_section( 'static_front_page' ) ) {
		$wp_customize->get_section( 'static_front_page' )->title    = __( '🏠 Homepage Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'static_front_page' )->priority = 40;
	}
	if ( $wp_customize->get_panel( 'woocommerce' ) ) {
		$wp_customize->get_panel( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'woocommerce' )->priority = 50;
	}
	if ( $wp_customize->get_section( 'woocommerce' ) ) {
		$wp_customize->get_section( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'woocommerce' )->priority = 50;
	}
	if ( $wp_customize->get_panel( 'nav_menus' ) ) {
		$wp_customize->get_panel( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'nav_menus' )->priority = 115;
	}
	if ( $wp_customize->get_section( 'nav_menus' ) ) {
		$wp_customize->get_section( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'nav_menus' )->priority = 115;
	}
}
add_action( 'customize_register', 'xophz_magic_hat_customize_register' );

/**
 * Late Customizer panel/section adjustments for WooCommerce and third-party plugins
 */
function xophz_magic_hat_late_customizer_adjustments( $wp_customize ) {
	if ( $wp_customize->get_section( 'title_tagline' ) ) {
		$wp_customize->get_section( 'title_tagline' )->title    = __( '🆔 Site Identity', 'xophz-magic-hat' );
		$wp_customize->get_section( 'title_tagline' )->priority = 20;
	}
	if ( $wp_customize->get_section( 'static_front_page' ) ) {
		$wp_customize->get_section( 'static_front_page' )->title    = __( '🏠 Homepage Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'static_front_page' )->priority = 40;
	}
	if ( $wp_customize->get_panel( 'woocommerce' ) ) {
		$wp_customize->get_panel( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'woocommerce' )->priority = 50;
	}
	if ( $wp_customize->get_section( 'woocommerce' ) ) {
		$wp_customize->get_section( 'woocommerce' )->title    = __( '🛍️ Shop Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'woocommerce' )->priority = 50;
	}
	if ( $wp_customize->get_section( 'custom_css' ) ) {
		$wp_customize->get_section( 'custom_css' )->panel    = 'magic_hat_site_styles';
		$wp_customize->get_section( 'custom_css' )->priority = 60;
	}
	if ( $wp_customize->get_panel( 'nav_menus' ) ) {
		$wp_customize->get_panel( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_panel( 'nav_menus' )->priority = 115;
	}
	if ( $wp_customize->get_section( 'nav_menus' ) ) {
		$wp_customize->get_section( 'nav_menus' )->title    = __( '🧭 Menu Settings', 'xophz-magic-hat' );
		$wp_customize->get_section( 'nav_menus' )->priority = 115;
	}
}
add_action( 'customize_register', 'xophz_magic_hat_late_customizer_adjustments', 999 );

function mh_render_footer_brand() {
	?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 12px; margin-bottom: 12px;">
		<?php if ( has_site_icon() ) : ?>
			<img src="<?php echo esc_url( get_site_icon_url( 256 ) ); ?>" alt="Logo" style="height: 128px; width: 128px; object-fit: contain; filter: drop-shadow(0 0 6px rgba(98,201,255,0.4)); opacity: 0.8; border-radius: 12px;" />
		<?php else : ?>
			<img src="<?php echo esc_url( get_template_directory_uri() . '/icon.svg' ); ?>" alt="Logo" style="height: 128px; width: 128px; object-fit: contain; filter: drop-shadow(0 0 6px rgba(98,201,255,0.4)); opacity: 0.8;" />
		<?php endif; ?>
		<span class="mh-footer-site-name" style="font-size: 18px; font-weight: 700; color: rgba(255,255,255,0.9); font-family: var(--mh-font-heading, sans-serif);"><?php bloginfo('name'); ?></span>
	</a>
	<p class="mh-footer-tagline" style="font-size: 14px; line-height: 1.6; max-width: 250px; margin: 0;"><?php bloginfo('description'); ?></p>
	<?php
}

function mh_render_footer_bottom() {
    $default_copyright = '&copy; {year} {site_title}. All rights reserved.';
    $copyright_text = get_theme_mod('mh_footer_copyright_text', $default_copyright);
    $copyright_text = str_replace(
        array('{year}', '{site_title}'),
        array(date('Y'), get_bloginfo('name')),
        $copyright_text
    );
    
    echo '<div class="mh-footer-copyright-text">';
    echo wp_kses_post($copyright_text);
    echo '</div>';

    $social_networks = array(
        'facebook'  => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
        'twitter'   => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16h4.267l-11.733 -16z"></path><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
        'linkedin'  => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
        'youtube'   => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
        'github'    => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>',
    );
    
    $has_social = false;
    ob_start();
    echo '<div class="mh-social-links" style="display: flex; gap: 15px; align-items: center;">';
    foreach ( $social_networks as $key => $svg ) {
        $url = get_theme_mod( 'mh_social_' . $key );
        if ( ! empty( $url ) ) {
            $has_social = true;
            echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color=\'#62c9ff\'" onmouseout="this.style.color=\'rgba(255,255,255,0.6)\'" aria-label="' . esc_attr( ucfirst( $key ) ) . '">' . $svg . '</a>';
        }
    }
    echo '</div>';
    $social_html = ob_get_clean();
    
    echo '<div class="mh-social-links-wrapper">';
    if ( $has_social ) {
        echo $social_html;
    }
    echo '</div>';
}


/**
 * Polyfill window.crypto.randomUUID for insecure HTTP / local development
 */
function xophz_magic_hat_polyfill_random_uuid() {
	?>
	<script>
		if (typeof window !== 'undefined') {
			if (!window.crypto) { window.crypto = {}; }
			if (!window.crypto.randomUUID) {
				window.crypto.randomUUID = function() {
					return '10000000-1000-4000-8000-100000000000'.replace(/[018]/g, function(c) {
						return (c ^ (window.crypto.getRandomValues ? window.crypto.getRandomValues(new Uint8Array(1))[0] : Math.floor(Math.random() * 256)) & 15 >> c / 4).toString(16);
					});
				};
			}
		}
	</script>
	<?php
}
add_action( 'customize_controls_print_scripts', 'xophz_magic_hat_polyfill_random_uuid', 0 );
add_action( 'admin_print_scripts', 'xophz_magic_hat_polyfill_random_uuid', 0 );
add_action( 'admin_head', 'xophz_magic_hat_polyfill_random_uuid', 0 );
add_action( 'wp_head', 'xophz_magic_hat_polyfill_random_uuid', 0 );

/**
 * Enqueue Customizer Control Scripts
 */
function xophz_magic_hat_customize_controls_enqueue() {
	wp_enqueue_script( 'wp-api' );
	wp_enqueue_script(
		'magic-hat-ai-architect-js',
		get_template_directory_uri() . '/assets/js/customizer-ai-architect.js',
		array( 'jquery', 'customize-controls', 'wp-api' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
	wp_localize_script(
		'magic-hat-ai-architect-js',
		'mhAiSettings',
		array(
			'nonce'      => wp_create_nonce( 'wp_rest' ),
			'restUrl'    => esc_url_raw( rest_url() ),
			'connectors' => class_exists( 'Magic_Hat_AI_Architect' ) ? Magic_Hat_AI_Architect::get_available_connectors() : array(),
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', 'xophz_magic_hat_customize_controls_enqueue' );

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
		/* AI Page Architect Styles */
		.mh-vibe-pill, .mh-arch-pill {
			transition: all 0.2s ease;
		}
		.mh-vibe-pill:hover, .mh-arch-pill:hover {
			border-color: #0284c7 !important;
			color: #0284c7 !important;
		}
		.mh-vibe-pill.active, .mh-arch-pill.active {
			background: #0284c7 !important;
			color: #ffffff !important;
			border-color: #0284c7 !important;
			font-weight: 700;
			box-shadow: 0 1px 4px rgba(2,132,199,0.3);
		}
		@keyframes mhSpin {
			100% { transform: rotate(360deg); }
		}
		.dashicons.spin {
			animation: mhSpin 1.2s linear infinite;
		}
		#mh-ai-conjure-btn.loading {
			opacity: 0.7;
			cursor: not-allowed;
		}
	</style>
	<script>
		jQuery(document).ready(function($) {
			// Add hover styles matching the native Customizer X button
			$('head').append('<style>#mh-toggle-sb:hover, #mh-toggle-home:hover, #mh-toggle-dark:hover { background: #f0f0f1; color: #2271b1 !important; }</style>');
			
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
							'magic_hat_spacing': 'section-spacing',
							'magic_hat_buttons': 'section-buttons',
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

			// Contextual controls live visibility toggle for Site Background & Canvas
			wp.customize.bind('ready', function() {
				if (wp.customize('mh_bg_mode')) {
					var updateBgControlsVisibility = function(mode) {
						var isSolid = (mode === 'solid');
						var isGradient = (mode === 'gradient');
						var isImage = (mode === 'image');
						var isCanvas = (mode === 'canvas');

						var setControlActive = function(controlId, active) {
							if (wp.customize.control(controlId)) {
								wp.customize.control(controlId).active.set(active);
							}
						};

						setControlActive('mh_bg_solid_color', isSolid);
						setControlActive('mh_bg_gradient_start', isGradient);
						setControlActive('mh_bg_gradient_end', isGradient);
						setControlActive('mh_bg_image', isImage);
						setControlActive('mh_bg_image_size', isImage);
						setControlActive('mh_bg_image_repeat', isImage);
						setControlActive('mh_bg_image_position', isImage);
						setControlActive('mh_bg_image_attachment', isImage);
						setControlActive('mh_bg_image_bg_color', isImage);
						setControlActive('mh_bg_canvas_preset', isCanvas);
						setControlActive('mh_bg_canvas_color', isCanvas);
						setControlActive('mh_bg_canvas_opacity', isCanvas);
						setControlActive('mh_bg_canvas_speed', isCanvas);
					};

					updateBgControlsVisibility(wp.customize('mh_bg_mode').get());
					wp.customize('mh_bg_mode').bind(updateBgControlsVisibility);
				}
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
	
	// Fetch Colors from canonical definitions
	$color_defs = mh_get_color_definitions();

	// Pre-calculate server circadian rhythm for initial SSR/no-FOUC state
	$now_hours = (float) current_time( 'H' ) + ( (float) current_time( 'i' ) / 60 );
	$server_ratio = ( cos( ( $now_hours - 12 ) * M_PI / 12 ) + 1 ) / 2;
	$server_is_day = ( $server_ratio >= 0.5 );
	$server_phase_class = $server_is_day ? 'phase-day' : 'phase-night';
	$server_phase_percent = $server_is_day 
		? number_format( ( $server_ratio - 0.5 ) * 200, 2, '.', '' ) . '%' 
		: number_format( $server_ratio * 200, 2, '.', '' ) . '%';
	$server_daylight = number_format( $server_ratio * 100, 2, '.', '' ) . '%';
	?>
	<style type="text/css">
		@import url('<?php echo esc_url($font_url); ?>');

		:root, html[data-theme="light"] {
			--mh-font-family: <?php echo esc_attr( $font_family ); ?>;
			--mh-font-heading: <?php echo esc_attr( $font_family ); ?>;
			--mh-font-body: <?php echo esc_attr( $font_family ); ?>;
			--mh-font-size: <?php echo esc_attr( $font_size ); ?>px;
			--mh-line-height: <?php echo esc_attr( $line_height ); ?>;
			--mh-heading-weight: <?php echo esc_attr( $heading_weight ); ?>;
			--mh-heading-line-height: <?php echo esc_attr( $heading_lh ); ?>;
			
			--mh-space-base: <?php echo esc_attr( get_theme_mod( 'mh_space_base', '8' ) ); ?>px;
			--mh-space-1: calc(var(--mh-space-base) * 0.5);
			--mh-space-2: calc(var(--mh-space-base) * 1);
			--mh-space-3: calc(var(--mh-space-base) * 1.5);
			--mh-space-4: calc(var(--mh-space-base) * 2);
			--mh-space-5: calc(var(--mh-space-base) * 3);
			--mh-space-6: calc(var(--mh-space-base) * 4);
			--mh-space-7: calc(var(--mh-space-base) * 6);
			--mh-space-8: calc(var(--mh-space-base) * 8);
			--mh-content-width: <?php echo esc_attr( get_theme_mod( 'mh_content_width', '1200' ) ); ?>px;
			
			--mh-font-size-h1: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h1', 48 ) ); ?>px;
			--mh-font-size-h2: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h2', 36 ) ); ?>px;
			--mh-font-size-h3: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h3', 28 ) ); ?>px;
			--mh-font-size-h4: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h4', 24 ) ); ?>px;
			--mh-font-size-h5: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h5', 20 ) ); ?>px;
			--mh-font-size-h6: <?php echo esc_attr( get_theme_mod( 'mh_font_size_h6', 16 ) ); ?>px;

			--mh-phase-primary: <?php echo esc_attr( $server_phase_percent ); ?>;
			--mh-daylight: <?php echo esc_attr( $server_daylight ); ?>;

			<?php foreach ( $color_defs as $key => $data ) : 
				$light_val = get_theme_mod( 'mh_color_' . str_replace('-', '_', $key), $data['light'] );
				$twi_val   = get_theme_mod( 'mh_color_' . str_replace('-', '_', $key) . '_twilight', $data['twilight'] );
				$dark_val  = get_theme_mod( 'mh_color_' . str_replace('-', '_', $key) . '_dark', $data['dark'] );
			?>
			--mh-color-<?php echo esc_attr($key); ?>-light: <?php echo esc_attr( $light_val ); ?>;
			--mh-color-<?php echo esc_attr($key); ?>-twi: <?php echo esc_attr( $twi_val ); ?>;
			--mh-color-<?php echo esc_attr($key); ?>-dark: <?php echo esc_attr( $dark_val ); ?>;
			
			/* Default fallback matches server-calculated circadian phase */
			<?php if ( $server_is_day ) : ?>
			--mh-color-<?php echo esc_attr($key); ?>: color-mix(in oklch, var(--mh-color-<?php echo esc_attr($key); ?>-light) var(--mh-phase-primary, <?php echo esc_attr( $server_phase_percent ); ?>), var(--mh-color-<?php echo esc_attr($key); ?>-twi));
			<?php else : ?>
			--mh-color-<?php echo esc_attr($key); ?>: color-mix(in oklch, var(--mh-color-<?php echo esc_attr($key); ?>-twi) var(--mh-phase-primary, <?php echo esc_attr( $server_phase_percent ); ?>), var(--mh-color-<?php echo esc_attr($key); ?>-dark));
			<?php endif; ?>

			/* Synchronize Gutenberg Core & FSE block color presets with Magic Hat tokens */
			--wp--preset--color--<?php echo esc_attr( $data['slug'] ); ?>: var(--mh-color-<?php echo esc_attr( $key ); ?>);
			<?php endforeach; ?>
			
			--mh-font-family: <?php echo esc_attr( $font_family ); ?>;
			--mh-border-radius: <?php echo esc_attr( get_theme_mod( 'mh_radius_base', '4' ) ); ?>px;
			--mh-border-width: <?php echo esc_attr( get_theme_mod( 'mh_border_width', '1' ) ); ?>px;
			--mh-btn-font-weight: <?php echo esc_attr( get_theme_mod( 'mh_button_font_weight', '600' ) ); ?>;
			--mh-btn-text-transform: <?php echo esc_attr( get_theme_mod( 'mh_button_text_transform', 'none' ) ); ?>;
			--mh-btn-letter-spacing: <?php echo esc_attr( get_theme_mod( 'mh_button_letter_spacing', '0' ) ); ?>px;
		}

		<?php 
		$schedule_mode = get_theme_mod( 'mh_color_schedule_mode', 'circadian' );
		$bg_mode       = get_theme_mod( 'mh_bg_mode', 'default' );

		if ( $schedule_mode === 'light' ) : ?>
		:root, html {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr($key); ?>: var(--mh-color-<?php echo esc_attr($key); ?>-light) !important;
			<?php endforeach; ?>
		}
		<?php elseif ( $schedule_mode === 'twilight' ) : ?>
		:root, html {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr($key); ?>: var(--mh-color-<?php echo esc_attr($key); ?>-twi) !important;
			<?php endforeach; ?>
		}
		<?php elseif ( $schedule_mode === 'dark' ) : ?>
		:root, html {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr($key); ?>: var(--mh-color-<?php echo esc_attr($key); ?>-dark) !important;
			<?php endforeach; ?>
		}
		<?php else : ?>
		:root.phase-day, html.phase-day, body.phase-day {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr($key); ?>: color-mix(in oklch, var(--mh-color-<?php echo esc_attr($key); ?>-light) var(--mh-phase-primary, 100%), var(--mh-color-<?php echo esc_attr($key); ?>-twi));
			<?php endforeach; ?>
		}
		
		:root.phase-night, html.phase-night, body.phase-night {
			<?php foreach ( $color_defs as $key => $data ) : ?>
			--mh-color-<?php echo esc_attr($key); ?>: color-mix(in oklch, var(--mh-color-<?php echo esc_attr($key); ?>-twi) var(--mh-phase-primary, 100%), var(--mh-color-<?php echo esc_attr($key); ?>-dark));
			<?php endforeach; ?>
		}
		<?php endif; ?>

		/* Circadian Smooth Transitions */
		body,
		header,
		footer,
		main,
		section,
		.mh-header,
		.mh-front-page-hero,
		.mh-section,
		[data-mw-type] {
			transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease;
		}

		<?php if ( $bg_mode === 'solid' ) : ?>
		body {
			background-color: <?php echo esc_attr( get_theme_mod( 'mh_bg_solid_color', '#0a0b10' ) ); ?> !important;
		}
		<?php elseif ( $bg_mode === 'gradient' ) : ?>
		body {
			background: linear-gradient(135deg, <?php echo esc_attr( get_theme_mod( 'mh_bg_gradient_start', '#0f172a' ) ); ?>, <?php echo esc_attr( get_theme_mod( 'mh_bg_gradient_end', '#020617' ) ); ?>) !important;
			background-attachment: fixed !important;
		}
		<?php elseif ( $bg_mode === 'image' ) : ?>
		body {
			<?php $bg_img = get_theme_mod( 'mh_bg_image' ); ?>
			<?php if ( ! empty( $bg_img ) ) : ?>
			background-image: url('<?php echo esc_url( $bg_img ); ?>') !important;
			background-size: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_size', 'cover' ) ); ?> !important;
			background-position: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_position', 'center center' ) ); ?> !important;
			background-repeat: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_repeat', 'no-repeat' ) ); ?> !important;
			background-attachment: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_attachment', 'fixed' ) ); ?> !important;
			<?php endif; ?>
			background-color: <?php echo esc_attr( get_theme_mod( 'mh_bg_image_bg_color', '#0a0b10' ) ); ?> !important;
		}
		<?php elseif ( $bg_mode === 'canvas' ) : ?>
		body {
			background-color: #05050d !important;
		}
		body.mh-has-ambient-canvas #page,
		body.mh-has-ambient-canvas header,
		body.mh-has-ambient-canvas main,
		body.mh-has-ambient-canvas footer,
		body.mh-has-ambient-canvas .wp-site-blocks,
		body.mh-has-ambient-canvas .site-content {
			position: relative;
			z-index: 1;
		}
		<?php else : ?>
		body {
			background-color: var(--mh-color-body);
		}
		<?php endif; ?>
		body {
			font-family: var(--mh-font-family);
			color: var(--mh-color-text-main);
		}
		h1, h2, h3, h4, h5, h6 {
			font-family: var(--mh-font-heading);
			font-weight: var(--mh-heading-weight);
			line-height: var(--mh-heading-line-height);
			color: var(--mh-color-text-heading);
			margin-top: 0;
			margin-bottom: var(--mh-space-3);
		}
		h1, .wp-block-heading h1, h1.wp-block-heading { font-size: var(--mh-font-size-h1); }
		h2, .wp-block-heading h2, h2.wp-block-heading { font-size: var(--mh-font-size-h2); }
		h3, .wp-block-heading h3, h3.wp-block-heading { font-size: var(--mh-font-size-h3); }
		h4, .wp-block-heading h4, h4.wp-block-heading { font-size: var(--mh-font-size-h4); }
		h5, .wp-block-heading h5, h5.wp-block-heading { font-size: var(--mh-font-size-h5); }
		h6, .wp-block-heading h6, h6.wp-block-heading { font-size: var(--mh-font-size-h6); }
		p, .wp-block-paragraph {
			font-size: var(--mh-font-size);
			line-height: var(--mh-line-height);
			color: var(--mh-color-text-main);
		}
		a {
			color: var(--mh-color-link);
		}
		.btn-primary { background: var(--mh-color-cta-base); color: var(--mh-color-text-inverse); }
		.btn-secondary { background: var(--mh-color-cta-muted); color: var(--mh-color-text-main); }
		
		/* Global Element Resets */
		button, input[type="submit"], input[type="reset"], input[type="button"] {
			font-family: var(--mh-font-body);
			border-radius: var(--mh-border-radius);
			border: var(--mh-border-width) solid transparent;
			padding: var(--mh-space-2) var(--mh-space-4);
			transition: all 0.2s;
			cursor: pointer;
		}
		
		input[type="text"], input[type="email"], input[type="password"], input[type="search"], input[type="number"], input[type="url"], input[type="tel"], textarea, select {
			width: 100%;
			padding: var(--mh-space-2) var(--mh-space-3);
			border: var(--mh-border-width) solid var(--mh-color-border-base);
			border-radius: var(--mh-border-radius);
			font-family: var(--mh-font-body);
			font-size: 1rem;
			background: var(--mh-color-main);
			color: var(--mh-color-text-main);
			transition: border-color 0.2s, box-shadow 0.2s;
			box-sizing: border-box;
			max-width: 100%;
		}
		
		input::placeholder, textarea::placeholder {
			color: var(--mh-color-text-muted);
			opacity: 0.7;
		}
		
		input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="number"]:focus, input[type="url"]:focus, input[type="tel"]:focus, textarea:focus, select:focus {
			outline: none;
			border-color: var(--mh-color-brand-base);
			box-shadow: 0 0 0 3px color-mix(in srgb, var(--mh-color-brand-base) 20%, transparent);
		}
		
		hr {
			border: 0;
			border-top: var(--mh-border-width) solid var(--mh-color-border-base);
			margin: var(--mh-space-5) 0;
		}
		
		/* Layout Utilities */
		.w-full { width: 100%; }
		.h-full { height: 100%; }
		.max-w-content { max-width: var(--mh-content-width); margin-left: auto; margin-right: auto; }
		.rounded { border-radius: var(--mh-border-radius); }
		.border { border-width: var(--mh-border-width); border-style: solid; border-color: var(--mh-color-border-muted); }
		
		/* Spacing Utilities (Tailwind Syntax) */
		<?php for ($i = 1; $i <= 8; $i++) : ?>
		.p-<?php echo $i; ?> { padding: var(--mh-space-<?php echo $i; ?>); }
		.py-<?php echo $i; ?> { padding-top: var(--mh-space-<?php echo $i; ?>); padding-bottom: var(--mh-space-<?php echo $i; ?>); }
		.px-<?php echo $i; ?> { padding-left: var(--mh-space-<?php echo $i; ?>); padding-right: var(--mh-space-<?php echo $i; ?>); }
		.pt-<?php echo $i; ?> { padding-top: var(--mh-space-<?php echo $i; ?>); }
		.pr-<?php echo $i; ?> { padding-right: var(--mh-space-<?php echo $i; ?>); }
		.pb-<?php echo $i; ?> { padding-bottom: var(--mh-space-<?php echo $i; ?>); }
		.pl-<?php echo $i; ?> { padding-left: var(--mh-space-<?php echo $i; ?>); }
		
		.m-<?php echo $i; ?> { margin: var(--mh-space-<?php echo $i; ?>); }
		.my-<?php echo $i; ?> { margin-top: var(--mh-space-<?php echo $i; ?>); margin-bottom: var(--mh-space-<?php echo $i; ?>); }
		.mx-<?php echo $i; ?> { margin-left: var(--mh-space-<?php echo $i; ?>); margin-right: var(--mh-space-<?php echo $i; ?>); }
		.mt-<?php echo $i; ?> { margin-top: var(--mh-space-<?php echo $i; ?>); }
		.mr-<?php echo $i; ?> { margin-right: var(--mh-space-<?php echo $i; ?>); }
		.mb-<?php echo $i; ?> { margin-bottom: var(--mh-space-<?php echo $i; ?>); }
		.ml-<?php echo $i; ?> { margin-left: var(--mh-space-<?php echo $i; ?>); }
		
		.gap-<?php echo $i; ?> { gap: var(--mh-space-<?php echo $i; ?>); }
		<?php endfor; ?>
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

/**
 * Add Circadian Phase to Body Classes for immediate CSS matching
 */
function mh_circadian_body_class( $classes ) {
	$schedule_mode = get_theme_mod( 'mh_color_schedule_mode', 'circadian' );
	if ( $schedule_mode === 'circadian' ) {
		$now_hours = (float) current_time( 'H' ) + ( (float) current_time( 'i' ) / 60 );
		$ratio     = ( cos( ( $now_hours - 12 ) * M_PI / 12 ) + 1 ) / 2;
		$classes[] = ( $ratio >= 0.5 ) ? 'phase-day' : 'phase-night';
	}
	return $classes;
}
add_filter( 'body_class', 'mh_circadian_body_class' );

/**
 * 24-Hour Circadian Rhythm Calculator
 */
function mh_circadian_rhythm_scripts() {
	$schedule_mode = get_theme_mod( 'mh_color_schedule_mode', 'circadian' );
	if ( $schedule_mode !== 'circadian' ) {
		return;
	}
	?>
	<script>
		(function() {
			function updateDaylight() {
				var now = new Date();
				var hours = now.getHours() + now.getMinutes() / 60 + now.getSeconds() / 3600;
				// cos((hours - 12) * PI / 12) = 1 at 12:00 (Noon), -1 at 00:00 (Midnight)
				var ratio = (Math.cos((hours - 12) * Math.PI / 12) + 1) / 2;
				var phasePercent;
				var isDay = ratio >= 0.5;
				if (isDay) {
					// Day Phase: Mix Light (100%) and Twilight (0%)
					phasePercent = ((ratio - 0.5) * 200).toFixed(2) + '%';
					document.documentElement.classList.remove('phase-night');
					document.documentElement.classList.add('phase-day');
					if (document.body) {
						document.body.classList.remove('phase-night');
						document.body.classList.add('phase-day');
					}
				} else {
					// Night Phase: Mix Twilight (100%) and Dark (0%)
					phasePercent = (ratio * 200).toFixed(2) + '%';
					document.documentElement.classList.remove('phase-day');
					document.documentElement.classList.add('phase-night');
					if (document.body) {
						document.body.classList.remove('phase-day');
						document.body.classList.add('phase-night');
					}
				}
				
				document.documentElement.style.setProperty('--mh-phase-primary', phasePercent);
				document.documentElement.style.setProperty('--mh-daylight', (ratio * 100).toFixed(2) + '%');
			}
			updateDaylight();
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', updateDaylight);
			}
			setInterval(updateDaylight, 60000); // Update every minute
		})();
	</script>
	<?php
}
add_action( 'wp_head', 'mh_circadian_rhythm_scripts', 1 );

/**
 * Render Ambient Background Canvas
 */
function xophz_magic_hat_render_ambient_canvas() {
	$bg_mode = get_theme_mod( 'mh_bg_mode', 'default' );
	$is_preview = is_customize_preview();
	if ( ! $is_preview && $bg_mode !== 'canvas' ) {
		return;
	}
	$preset  = get_theme_mod( 'mh_bg_canvas_preset', 'electric-wave' );
	$color   = get_theme_mod( 'mh_bg_canvas_color', '#2563eb' );
	$opacity = floatval( get_theme_mod( 'mh_bg_canvas_opacity', '0.6' ) );
	$speed   = floatval( get_theme_mod( 'mh_bg_canvas_speed', '1.0' ) );
	$display = ( $bg_mode === 'canvas' ) ? 'block' : 'none';
	?>
	<canvas id="mh-ambient-canvas" class="mh-ambient-canvas" style="position: fixed; inset: 0; width: 100vw; height: 100vh; pointer-events: none; z-index: 0; display: <?php echo esc_attr( $display ); ?>;"></canvas>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if (window.MagicHatCanvases && document.getElementById('mh-ambient-canvas')) {
				window.mhCanvasInstance = window.MagicHatCanvases.mount(
					document.getElementById('mh-ambient-canvas'),
					<?php echo json_encode( $preset ); ?>,
					{
						color: <?php echo json_encode( $color ); ?>,
						opacity: <?php echo $opacity; ?>,
						speed: <?php echo $speed; ?>
					}
				);
			}
		});
	</script>
	<?php
}
add_action( 'wp_body_open', 'xophz_magic_hat_render_ambient_canvas', 1 );

/**
 * Enqueue Animated Canvas Engine Assets
 */
function xophz_magic_hat_enqueue_canvas_assets() {
	$bg_mode = get_theme_mod( 'mh_bg_mode', 'default' );
	if ( is_customize_preview() || $bg_mode === 'canvas' ) {
		wp_enqueue_script(
			'magic-hat-canvases',
			get_template_directory_uri() . '/assets/js/canvases/magic-hat-canvases.js',
			array(),
			'1.0.0',
			false
		);
	}
}
add_action( 'wp_enqueue_scripts', 'xophz_magic_hat_enqueue_canvas_assets' );

add_filter( 'body_class', function( $classes ) {
	if ( get_theme_mod( 'mh_bg_mode', 'default' ) === 'canvas' || is_customize_preview() ) {
		$classes[] = 'mh-has-ambient-canvas';
	}
	return $classes;
} );

/**
 * Page Builder Preview: Add Section Button
 */
add_filter('the_content', function ($content) {
	global $post;
	if (is_customize_preview() && $post && $post->post_type === 'page') {
		$is_active = class_exists('Xophz_Compass_Magic_Wand') || defined('XOPHZ_COMPASS_MAGIC_WAND_VERSION');

		// If Magic Wand isn't active, show the floating install prompt
		if ( ! $is_active ) {
			include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			if ( file_exists( WP_PLUGIN_DIR . '/xophz-compass-magic-wand/xophz-compass-magic-wand.php' ) ) {
				$action_url = wp_nonce_url( self_admin_url('plugins.php?action=activate&plugin=xophz-compass-magic-wand/xophz-compass-magic-wand.php&plugin_status=all&paged=1&s'), 'activate-plugin_xophz-compass-magic-wand/xophz-compass-magic-wand.php' );
				$btn_text = __('Activate now', 'xophz-magic-hat');
			} else {
				$action_url = wp_nonce_url( self_admin_url('update.php?action=install-plugin&plugin=xophz-compass-magic-wand'), 'install-plugin_xophz-compass-magic-wand' );
				$btn_text = __('Install now', 'xophz-magic-hat');
			}
			
			$prompt = '
			<div id="mh-plugin-prompt" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 40px; border-radius: 4px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); z-index: 999999; max-width: 450px; text-align: center; font-family: sans-serif;">
				<button type="button" onclick="document.getElementById(\'mh-plugin-prompt\').style.display=\'none\';" style="position: absolute; top: 10px; right: 15px; background: transparent; border: none; font-size: 24px; cursor: pointer; color: #50575e;">&times;</button>
				<h3 style="margin-top: 0; font-size: 16px; color: #3c434a; font-weight: 400; line-height: 1.5; margin-bottom: 25px;">' . esc_html__('Please Install the Magic Wand Companion Plugin to Enable All the Theme Features', 'xophz-magic-hat') . '</h3>
				<a href="' . esc_url($action_url) . '" class="button" style="display: inline-block; text-decoration: none; background: #2563eb; border: 1px solid #2563eb; color: #fff; padding: 10px 30px; font-weight: 600; text-transform: uppercase; font-size: 13px; border-radius: 4px;">' . esc_html($btn_text) . '</a>
			</div>';
			$content .= $prompt;
		}

		// Only append Add Section helper if page is in Magic Hat canvas mode or has sections
		$show_on_front = get_option( 'show_on_front', 'posts' );
		$is_canvas = ( $show_on_front === 'page' && is_front_page() );
		$page_sections = get_post_meta( $post->ID, '_mh_page_sections', true );
		if ( $is_canvas || ! empty( $page_sections ) ) {
			$add_section = '
			<div class="mh-add-section-preview" style="margin-top: 40px; padding: 60px 0; border: 2px dashed var(--mh-color-border-muted, #cbd5e1); text-align: center; background: var(--mh-color-section, #f8fafc); border-radius: 8px;">';
				if ( $is_active ) {
					$add_section .= '<button type="button" class="mh-add-section" onclick="if(typeof parent.mhOpenAddSectionPanel === \'function\') { parent.mhOpenAddSectionPanel(); } else { parent.wp.customize.section(\'mh_page_builder\').focus(); }" style="background: var(--mh-color-cta-base, #2563eb); border: none; color: var(--mh-color-text-inverse, #fff); padding: 12px 32px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; border-radius: 6px; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">' . __('+ Add Section', 'xophz-magic-hat') . '</button>';
				} else {
					$add_section .= '<button type="button" class="btn-primary" style="background: #2563eb; border-color: #2563eb; text-transform: uppercase; letter-spacing: 1px;" onclick="if(window.parent && window.parent.wp && window.parent.wp.customize){ window.parent.wp.customize.section(\'mh_page_builder\').focus(); }">
					' . esc_html__('+ ADD SECTION', 'xophz-magic-hat') . '
				</button>';
				}
			$add_section .= '</div>';
			$content .= $add_section;
		}
	}
	return $content;
}, PHP_INT_MAX);
