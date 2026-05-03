<?php
/**
 * Magic Hat High-Fidelity Stylebook
 */

if ( ! isset( $_GET['magic_hat_stylebook'] ) || $_GET['magic_hat_stylebook'] !== '1' ) {
	return;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<style>
		:root { font-size: var(--mh-font-size, 16px); }
		body { background-color: var(--mh-color-body); color: var(--mh-color-text-main); font-family: var(--mh-font-body, sans-serif); line-height: var(--mh-line-height, 1.6); margin: 0; padding: 0; }
		.stylebook-layout { display: flex; height: 100vh; overflow: hidden; background-color: transparent; }
		
		/* Sidebar Navigation */
		.stylebook-nav { width: 250px; background: var(--mh-color-main); border-right: 1px solid var(--mh-color-section); overflow-y: auto; padding: 20px 0; }
		.stylebook-nav ul { list-style: none; margin: 0; padding: 0; }
		.stylebook-nav li a { display: block; padding: 10px 20px; color: var(--mh-color-text-muted); text-decoration: none; font-size: 14px; font-weight: 500; }
		.stylebook-nav li a:hover { background: var(--mh-color-section); color: var(--mh-color-brand-hover); }
		.stylebook-nav li a.active { background: var(--mh-color-card); color: var(--mh-color-brand-base); font-weight: 700; border-right: 3px solid var(--mh-color-brand-base); }
		.stylebook-nav .nav-header { padding: 10px 20px; font-size: 11px; text-transform: uppercase; color: var(--mh-color-text-muted); letter-spacing: 1px; margin-top: 15px; }
		
		/* Main Content */
		.stylebook-content { flex: 1; overflow-y: auto; padding: 60px; background-color: var(--mh-color-body); }
		.stylebook-section { display: none; max-width: 1000px; margin: 0 auto 80px auto; background: var(--mh-color-main); padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: relative; border: 1px solid var(--mh-color-section); }
		.stylebook-section.active { display: block; }
		.section-actions { position: absolute; top: 20px; right: 20px; display: flex; gap: 8px; }
		.action-btn { background: var(--mh-color-section); border: 1px solid var(--mh-color-card); padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; color: var(--mh-color-text-main); display: flex; align-items: center; gap: 6px; transition: all 0.2s; font-family: var(--mh-font-body); }
		.action-btn:hover { background: var(--mh-color-brand-base); color: var(--mh-color-text-inverse); border-color: var(--mh-color-brand-base); }
		.action-btn.active-toggle { background: var(--mh-color-brand-base); color: var(--mh-color-text-inverse); border-color: var(--mh-color-brand-base); }
		.action-btn.primary { background: var(--mh-color-brand-base); color: var(--mh-color-text-inverse); border-color: var(--mh-color-brand-base); }
		.action-btn.primary:hover { opacity: 0.9; }
		
		.section-title { font-size: 24px; margin-top: 0; margin-bottom: 30px; border-bottom: 2px solid var(--mh-color-section); padding-bottom: 15px; color: var(--mh-color-text-heading); font-family: var(--mh-font-heading); }
		.subsection-title { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: var(--mh-color-text-muted); margin: 30px 0 15px 0; }
		
		h1, h2, h3, h4, h5, h6 { color: var(--mh-color-text-heading); font-family: var(--mh-font-heading); }
		
		/* Colors */
		.palette-container { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 40px; }
		.palette-column { display: flex; flex-direction: column; flex: 1; min-width: 120px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden; border: 1px solid var(--mh-color-section); }
		.palette-header { background: var(--mh-color-main); padding: 10px; font-size: 11px; text-transform: uppercase; color: var(--mh-color-text-muted); font-weight: bold; text-align: center; border-bottom: 1px solid var(--mh-color-section); cursor: pointer; transition: all 0.2s; }
		.palette-header:hover { background: var(--mh-color-section); color: var(--mh-color-text-main); }
		.color-swatch { height: 80px; display: flex; flex-direction: column; justify-content: flex-end; padding: 10px; font-size: 11px; font-weight: 600; color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3); border-bottom: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: opacity 0.2s; }
		.color-swatch:hover { opacity: 0.85; }
		.color-swatch:last-child { border-bottom: none; }
		.swatch-light { color: #333; text-shadow: none; border-bottom: 1px solid rgba(0,0,0,0.05); }
		
		/* Typography */
		.type-preview h1, .type-preview h2, .type-preview h3, .type-preview h4, .type-preview h5, .type-preview h6 { font-weight: var(--mh-heading-weight, 600); line-height: var(--mh-heading-line-height, 1.2); color: var(--mh-color-text-heading); margin: 0 0 10px 0; font-family: var(--mh-font-heading); }
		.type-preview h1 { font-size: var(--mh-text-4xl, 2.25rem); }
		.type-preview h2 { font-size: var(--mh-text-3xl, 1.875rem); }
		.type-preview h3 { font-size: var(--mh-text-2xl, 1.5rem); }
		.type-preview p { font-size: var(--mh-text-base, 1rem); line-height: var(--mh-line-height, 1.6); color: var(--mh-color-text-main); max-width: 800px; font-family: var(--mh-font-body); }
		.type-preview blockquote { border-left: 4px solid var(--mh-color-brand-base); margin: 20px 0; padding: 10px 20px; font-size: 1.2em; font-style: italic; background: var(--mh-color-section); }
		
		/* Buttons */
		.btn-preview { display: flex; gap: 15px; flex-wrap: wrap; align-items: center; margin-bottom: 30px; }
		.mh-btn { 
			--btn-base: var(--mh-color-cta-base);
			--btn-hover: var(--mh-color-cta-hover);
			--btn-text: var(--mh-color-text-inverse);
			display: inline-flex; 
			align-items: center; 
			justify-content: center; 
			padding: var(--mh-space-2, 8px) var(--mh-space-4, 16px); 
			border-radius: var(--mh-border-radius, 4px); 
			font-family: var(--mh-font-body); 
			font-weight: var(--mh-btn-font-weight, 600); 
			text-transform: var(--mh-btn-text-transform, none);
			letter-spacing: var(--mh-btn-letter-spacing, 0);
			cursor: pointer; 
			text-decoration: none; 
			transition: all 0.2s; 
			border: var(--mh-border-width, 1px) solid transparent;
			gap: var(--mh-space-1, 4px);
		}

		/* Color Modifiers */
		.mh-btn.color-brand { --btn-base: var(--mh-color-brand-base); --btn-hover: var(--mh-color-brand-hover); }
		.mh-btn.color-success { --btn-base: var(--mh-color-success); --btn-hover: color-mix(in srgb, var(--mh-color-success) 85%, black); }
		.mh-btn.color-warning { --btn-base: var(--mh-color-warning); --btn-hover: color-mix(in srgb, var(--mh-color-warning) 85%, black); --btn-text: #111; }
		.mh-btn.color-danger { --btn-base: var(--mh-color-danger); --btn-hover: color-mix(in srgb, var(--mh-color-danger) 85%, black); }
		.mh-btn.color-info { --btn-base: var(--mh-color-info); --btn-hover: color-mix(in srgb, var(--mh-color-info) 85%, black); }
		
		/* Style Variants */
		.mh-btn-primary {
			background: var(--btn-base);
			border-color: var(--btn-base);
			color: var(--btn-text);
		}
		.mh-btn-primary:hover {
			background: var(--btn-hover);
			border-color: var(--btn-hover);
		}
		
		.mh-btn-outline {
			background: transparent;
			border-color: var(--btn-base);
			color: var(--btn-base);
		}
		.mh-btn-outline:hover {
			background: var(--btn-base);
			color: var(--btn-text);
		}
		
		.mh-btn-text {
			background: transparent;
			border-color: transparent;
			color: var(--btn-base);
			padding-left: 0;
			padding-right: 0;
		}
		.mh-btn-text:hover {
			color: var(--btn-hover);
		}
		
		/* Cards */
		.card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: var(--mh-space-4, 16px); }
		.card, .mh-card { background: var(--mh-color-card); border-radius: var(--mh-border-radius, 4px); box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; border: var(--mh-border-width, 1px) solid var(--mh-color-section); }
		.card-img { height: 180px; background-size: cover; background-position: center; }
		.card-body { padding: var(--mh-space-4, 16px); }
		.card-title { margin: 0 0 var(--mh-space-2, 8px) 0; font-size: 1.1rem; }
		.mh-card { padding: var(--mh-space-4, 16px); }
		
		/* Forms */
		.form-group { margin-bottom: var(--mh-space-3, 12px); }
		label { display: block; margin-bottom: var(--mh-space-1, 4px); font-weight: 500; font-size: 14px; color: var(--mh-color-text-main); font-family: var(--mh-font-body); }
		
		/* Alerts */
		.alert { display: flex; align-items: center; gap: var(--mh-space-2, 8px); padding: var(--mh-space-3, 12px) var(--mh-space-4, 16px); border-radius: var(--mh-border-radius, 4px); margin-bottom: var(--mh-space-3, 12px); font-size: 14px; border: var(--mh-border-width, 1px) solid transparent; }
		.alert-success { background: color-mix(in srgb, var(--mh-color-success) 10%, transparent); color: var(--mh-color-success); border-color: color-mix(in srgb, var(--mh-color-success) 25%, transparent); }
		.alert-warning { background: color-mix(in srgb, var(--mh-color-warning) 10%, transparent); color: var(--mh-color-warning); border-color: color-mix(in srgb, var(--mh-color-warning) 25%, transparent); }
		.alert-danger { background: color-mix(in srgb, var(--mh-color-danger) 10%, transparent); color: var(--mh-color-danger); border-color: color-mix(in srgb, var(--mh-color-danger) 25%, transparent); }
		.alert-info { background: color-mix(in srgb, var(--mh-color-info) 10%, transparent); color: var(--mh-color-info); border-color: color-mix(in srgb, var(--mh-color-info) 25%, transparent); }
		
		/* Accordion */
		.accordion { border: var(--mh-border-width, 1px) solid var(--mh-color-section); border-radius: var(--mh-border-radius, 4px); overflow: hidden; }
		.accordion-item { padding: var(--mh-space-3, 12px) var(--mh-space-4, 16px); border-bottom: var(--mh-border-width, 1px) solid var(--mh-color-section); display: flex; justify-content: space-between; align-items: center; cursor: pointer; font-size: 14px; font-weight: 500; transition: background 0.2s; }
		.accordion-item:last-child { border-bottom: none; }
		.accordion-item:hover { background: var(--mh-color-section); }
		
		/* Progress */
		.progress-bar { height: 8px; background: var(--mh-color-section); border-radius: var(--mh-border-radius, 4px); overflow: hidden; margin-bottom: 20px; }
		.progress-fill { height: 100%; background: var(--mh-color-brand-base); width: 65%; }
	</style>
	<style>
		@keyframes conjurePulse {
			0% { box-shadow: 0 0 0 0 color-mix(in srgb, var(--mh-color-brand-base) 70%, transparent); }
			70% { box-shadow: 0 0 0 10px transparent; }
			100% { box-shadow: 0 0 0 0 transparent; }
		}
		.conjuring {
			animation: conjurePulse 1.5s infinite;
			opacity: 0.7;
			cursor: not-allowed !important;
			pointer-events: none;
		}
		.conjuring .dashicons {
			animation: spin 2s linear infinite;
		}
		@keyframes spin { 100% { transform: rotate(360deg); } }
		
		@keyframes bounceDots {
			0% { content: "..."; }
			25% { content: ".."; }
			50% { content: "."; }
			75% { content: ".."; }
			100% { content: "..."; }
		}
		.loading-ellipsis::after {
			content: "...";
			display: inline-block;
			width: 1.5em;
			text-align: left;
			animation: bounceDots 1.5s infinite step-end;
		}
	</style>
	<script>
		function focusCustomizer( controlId ) {
			if ( window.parent && window.parent.wp && window.parent.wp.customize ) {
				if ( window.parent.wp.customize.control( controlId ) ) {
					window.parent.wp.customize.control( controlId ).focus();
				} else if ( window.parent.wp.customize.panel( controlId ) ) {
					window.parent.wp.customize.panel( controlId ).expand();
				} else if ( window.parent.wp.customize.section( controlId ) ) {
					window.parent.wp.customize.section( controlId ).expand();
				}
			} else {
				console.warn('Customizer API not found. Are you previewing outside the customizer?');
			}
		}

		function focusCustomizerControl(controlId) {
			if ( window.parent && window.parent.wp && window.parent.wp.customize ) {
				var control = window.parent.wp.customize.control( controlId );
				if ( control ) {
					var sectionId = control.section();
					var isMhColorSection = sectionId && sectionId.indexOf('mh_colors_') === 0;
					
					if (isMhColorSection) {
						window.parent.wp.customize.panel( 'magic_hat_colors_panel' ).expand();
						setTimeout(function() { control.focus(); }, 350);
					} else {
						// For top-level sections like Typography and Buttons, just focus directly
						control.focus();
					}
				}
			}
		}
	</script>
	<script>
		var stylebook_rest_nonce = '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>';
	</script>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	
	<div class="stylebook-layout">
		<!-- Sidebar Navigation -->
		<nav class="stylebook-nav">
			<div class="nav-header">Design Tokens</div>
			<ul>
				<li><a href="#section-colors" data-customizer-target="magic_hat_colors_panel">Colors</a></li>
				<li><a href="#section-typography" data-customizer-target="magic_hat_typography">Typography</a></li>
				<li><a href="#section-spacing" data-customizer-target="magic_hat_spacing">Spacing & Layout</a></li>
			</ul>
			<div class="nav-header">Components</div>
			<ul>
				<li><a href="#section-buttons" data-customizer-target="magic_hat_buttons">Buttons</a></li>
				<li><a href="#section-forms">Forms & Inputs</a></li>
				<li><a href="#section-cards">Cards, Grids & Features</a></li>
				<li><a href="#section-ui">UI Elements</a></li>
				<li><a href="#section-media">Media & Galleries</a></li>
			</ul>
			<div class="nav-header">WordPress Blocks</div>
			<ul>
				<li><a href="#section-post">Post & Query Loop</a></li>
				<li><a href="#section-comments">Comments</a></li>
			</ul>
		</nav>

		<!-- Main Content Area -->
		<main class="stylebook-content">
			
			<!-- COLORS -->
			<section id="section-colors" class="stylebook-section active">
				<div class="section-actions">
					<button class="action-btn" onclick="toggleImportPanel()" id="btn-import">
						<span class="dashicons dashicons-upload"></span> Import
					</button>
					<button class="action-btn" onclick="exportPalette()">
						<span class="dashicons dashicons-download"></span> Export
					</button>
					<button class="action-btn" onclick="toggleAIConjurer()" id="btn-conjure">
						<span class="dashicons dashicons-art"></span> Conjure
					</button>
				</div>
				<h2 class="section-title">Site Colors</h2>
				
				<!-- Import/Export Panel -->
				<div id="import-panel" style="display: none; background: rgba(98, 201, 255, 0.05); border: 1px solid rgba(98, 201, 255, 0.2); border-radius: 8px; padding: 20px; margin-bottom: 30px;">
					<h3 style="margin-top: 0; margin-bottom: 10px; font-size: 16px; display: flex; align-items: center; gap: 8px;">
						<span class="dashicons dashicons-upload"></span> Import Palette JSON
					</h3>
					<p style="margin-top: 0; margin-bottom: 15px; font-size: 14px; color: var(--mh-color-text-muted);">Paste a previously exported JSON palette here to instantly apply it.</p>
					<textarea id="import-textarea" style="width: 100%; height: 120px; padding: 10px; border-radius: 4px; border: 1px solid #ddd; font-family: monospace; font-size: 12px; margin-bottom: 10px; background: var(--mh-color-main); color: var(--mh-color-text-main);"></textarea>
					<button onclick="executeImport()" class="action-btn primary" style="display: inline-flex;">Apply JSON Palette</button>
				</div>

				<!-- AI Palette Conjurer -->
				<div id="ai-conjurer-panel" style="display: none; background: rgba(98, 201, 255, 0.05); border: 1px solid rgba(98, 201, 255, 0.2); border-radius: 8px; padding: 20px; margin-bottom: 30px;">
					<h3 style="margin-top: 0; margin-bottom: 10px; font-size: 16px; display: flex; align-items: center; gap: 8px;">
						<span class="dashicons dashicons-superhero"></span> AI Palette Conjurer
					</h3>
					<p style="margin-top: 0; margin-bottom: 15px; font-size: 14px; color: var(--mh-color-text-muted);">Describe your desired brand or vibe, and Gemini will generate a complete color system for you.</p>
					<div style="display: flex; gap: 10px;">
						<input type="text" id="ai-palette-prompt" placeholder="e.g. Fast food burger chain, cyberpunk neon hacker, minimalist luxury spa..." style="flex: 1; padding: 10px 15px; border-radius: 4px; border: 1px solid #ddd; font-family: inherit;">
						<button id="ai-conjure-btn" onclick="conjureAIPalette()" style="background: var(--mh-color-brand-base, #62c9ff); color: #fff; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px;">
							<span class="dashicons dashicons-art"></span> <span class="btn-text">Conjure</span>
						</button>
					</div>
					<div id="ai-palette-status" style="display: none; margin-top: 15px; font-size: 13px; color: var(--mh-color-brand-base); font-weight: 500;">
						✨ Please wait<span class="loading-ellipsis"></span>
					</div>
					<div id="ai-palette-history" style="margin-top: 15px; display: none; gap: 8px; flex-wrap: wrap;">
						<!-- Cached palettes will appear here -->
					</div>
				</div>
				
				<div class="palette-container">
					
					<!-- Brand Column -->
					<div class="palette-column">
						<div class="palette-header" onclick="focusCustomizer('mh_colors_brand')">Brand</div>
						<div class="color-swatch" style="background: var(--mh-color-brand-base);" onclick="focusCustomizerControl('mh_color_brand_base')">Base</div>
						<div class="color-swatch" style="background: var(--mh-color-brand-hover);" onclick="focusCustomizerControl('mh_color_brand_hover')">Hover</div>
						<div class="color-swatch" style="background: var(--mh-color-brand-active);" onclick="focusCustomizerControl('mh_color_brand_active')">Active</div>
						<div class="color-swatch" style="background: var(--mh-color-brand-muted);" onclick="focusCustomizerControl('mh_color_brand_muted')">Muted</div>
					</div>

					<!-- CTA Column -->
					<div class="palette-column">
						<div class="palette-header" onclick="focusCustomizer('mh_colors_action-cta')">Action</div>
						<div class="color-swatch" style="background: var(--mh-color-cta-base);" onclick="focusCustomizerControl('mh_color_cta_base')">Base</div>
						<div class="color-swatch" style="background: var(--mh-color-cta-hover);" onclick="focusCustomizerControl('mh_color_cta_hover')">Hover</div>
						<div class="color-swatch" style="background: var(--mh-color-cta-active);" onclick="focusCustomizerControl('mh_color_cta_active')">Active</div>
						<div class="color-swatch" style="background: var(--mh-color-cta-muted);" onclick="focusCustomizerControl('mh_color_cta_muted')">Muted</div>
					</div>

					<!-- Link Column -->
					<div class="palette-column">
						<div class="palette-header" onclick="focusCustomizer('mh_colors_links')">Links</div>
						<div class="color-swatch" style="background: var(--mh-color-link);" onclick="focusCustomizerControl('mh_color_link')">Default</div>
						<div class="color-swatch" style="background: var(--mh-color-link-hover);" onclick="focusCustomizerControl('mh_color_link_hover')">Hover</div>
						<div class="color-swatch" style="background: var(--mh-color-link-active);" onclick="focusCustomizerControl('mh_color_link_active')">Active</div>
						<div class="color-swatch" style="background: var(--mh-color-link-visited);" onclick="focusCustomizerControl('mh_color_link_visited')">Visited</div>
					</div>

					<!-- Text Column -->
					<div class="palette-column">
						<div class="palette-header" onclick="focusCustomizer('mh_colors_text')">Text</div>
						<div class="color-swatch" style="background: var(--mh-color-text-heading);" onclick="focusCustomizerControl('mh_color_text_heading')">Heading</div>
						<div class="color-swatch" style="background: var(--mh-color-text-main);" onclick="focusCustomizerControl('mh_color_text_main')">Main</div>
						<div class="color-swatch" style="background: var(--mh-color-text-muted);" onclick="focusCustomizerControl('mh_color_text_muted')">Muted</div>
						<div class="color-swatch" style="background: var(--mh-color-text-inverse);" onclick="focusCustomizerControl('mh_color_text_inverse')">Inverse</div>
					</div>

					<!-- Layers Column -->
					<div class="palette-column">
						<div class="palette-header" onclick="focusCustomizer('mh_colors_surfaces-layers')">Layers</div>
						<div class="color-swatch" style="background: var(--mh-color-body);" onclick="focusCustomizerControl('mh_color_body')">Body (Base)</div>
						<div class="color-swatch" style="background: var(--mh-color-main);" onclick="focusCustomizerControl('mh_color_main')">Main</div>
						<div class="color-swatch" style="background: var(--mh-color-section);" onclick="focusCustomizerControl('mh_color_section')">Section</div>
						<div class="color-swatch" style="background: var(--mh-color-card);" onclick="focusCustomizerControl('mh_color_card')">Card</div>
					</div>

					<!-- Borders Column -->
					<div class="palette-column">
						<div class="palette-header" onclick="focusCustomizer('mh_colors_borders-lines')">Borders</div>
						<div class="color-swatch" style="background: var(--mh-color-border-base);" onclick="focusCustomizerControl('mh_color_border_base')">Base</div>
						<div class="color-swatch" style="background: var(--mh-color-border-hover);" onclick="focusCustomizerControl('mh_color_border_hover')">Hover</div>
						<div class="color-swatch" style="background: var(--mh-color-border-focus);" onclick="focusCustomizerControl('mh_color_border_focus')">Focus</div>
						<div class="color-swatch" style="background: var(--mh-color-border-muted);" onclick="focusCustomizerControl('mh_color_border_muted')">Muted</div>
					</div>

					<!-- System Column -->
					<div class="palette-column">
						<div class="palette-header" onclick="focusCustomizer('mh_colors_status-system')">System</div>
						<div class="color-swatch" style="background: var(--mh-color-success);" onclick="focusCustomizerControl('mh_color_success')">Success</div>
						<div class="color-swatch" style="background: var(--mh-color-warning);" onclick="focusCustomizerControl('mh_color_warning')">Warning</div>
						<div class="color-swatch" style="background: var(--mh-color-danger);" onclick="focusCustomizerControl('mh_color_danger')">Danger</div>
						<div class="color-swatch" style="background: var(--mh-color-info);" onclick="focusCustomizerControl('mh_color_info')">Info</div>
					</div>

				</div>
				
				<div class="subsection-title">Circadian Rhythm Preview</div>
				<div style="background: var(--mh-color-card); border: var(--mh-border-width, 1px) solid var(--mh-color-section); border-radius: var(--mh-border-radius, 4px); padding: var(--mh-space-4, 16px);">
					<div style="display: flex; align-items: center; gap: var(--mh-space-3, 12px); margin-bottom: var(--mh-space-2, 8px);">
						<span id="daylight-icon" style="font-size: 20px;">🌙</span>
						<input type="range" id="daylight-slider" min="0" max="1440" step="1" style="flex: 1; accent-color: var(--mh-color-brand-base); cursor: pointer;">
						<span id="daylight-time" style="font-size: 14px; font-weight: 600; min-width: 60px; text-align: right; color: var(--mh-color-text-main); font-family: var(--mh-font-body);">12:00 PM</span>
					</div>
					<div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--mh-color-text-muted); padding: 0 2px;">
						<span>🌙 Midnight</span>
						<span>🌅 6 AM</span>
						<span>☀️ Noon</span>
						<span>🌅 6 PM</span>
						<span>🌙 Midnight</span>
					</div>
					<div id="daylight-bar" style="height: 6px; border-radius: 3px; margin-top: var(--mh-space-2, 8px); background: linear-gradient(to right, #0a0b2e, #1a1a4e, #4a6fa5, #87ceeb, #ffd700, #87ceeb, #4a6fa5, #1a1a4e, #0a0b2e); opacity: 0.6;"></div>
				</div>
				<script>
					(function() {
						var slider = document.getElementById('daylight-slider');
						var timeLabel = document.getElementById('daylight-time');
						var icon = document.getElementById('daylight-icon');

						var now = new Date();
						var currentMinutes = now.getHours() * 60 + now.getMinutes();
						slider.value = currentMinutes;

						function formatTime(totalMinutes) {
							var h = Math.floor(totalMinutes / 60) % 24;
							var m = Math.floor(totalMinutes % 60);
							var ampm = h >= 12 ? 'PM' : 'AM';
							var displayH = h % 12 || 12;
							return displayH + ':' + (m < 10 ? '0' : '') + m + ' ' + ampm;
						}

						function updateFromSlider() {
							var minutes = parseInt(slider.value);
							var hours = minutes / 60;
							var ratio = (Math.cos((hours - 12) * Math.PI / 12) + 1) / 2;
							var percent = (ratio * 100).toFixed(2) + '%';
							document.documentElement.style.setProperty('--mh-daylight', percent);
							timeLabel.textContent = formatTime(minutes);

							var isDay = ratio > 0.6;
							var isTwilight = ratio > 0.3 && ratio <= 0.6;
							icon.textContent = isDay ? '☀️' : (isTwilight ? '🌅' : '🌙');
						}

						slider.addEventListener('input', updateFromSlider);
						updateFromSlider();
					})();
				</script>
			</section>

			<!-- TYPOGRAPHY -->
			<section id="section-typography" class="stylebook-section">
				<h2 class="section-title">Typography</h2>
				
				<div class="type-preview">
					<h1>H1. Heading 1</h1>
					<h2>H2. Heading 2</h2>
					<h3>H3. Heading 3</h3>
					<h4>H4. Heading 4</h4>
					<h5>H5. Heading 5</h5>
					<h6>H6. Heading 6</h6>
					
					<div class="subsection-title">Paragraphs</div>
					<p>A paragraph in a website refers to a distinct block of text that is used to present and organize information. It is a fundamental unit of content in web design and is typically composed of a group of related sentences or thoughts focused on a particular topic or idea.</p>
					
					<div class="subsection-title">Blockquote & Pullquote</div>
					<blockquote>
						"Good design is obvious. Great design is transparent."
						<cite style="display:block; font-size: 0.8rem; margin-top: 10px; font-style: normal;">— Joe Sparano</cite>
					</blockquote>

					<div class="subsection-title">Lists</div>
					<div style="display:flex; gap: 40px;">
						<ul>
							<li>Unordered List Item 1</li>
							<li>Unordered List Item 2</li>
							<li>Unordered List Item 3</li>
						</ul>
						<ol>
							<li>Ordered List Item 1</li>
							<li>Ordered List Item 2</li>
							<li>Ordered List Item 3</li>
						</ol>
					</div>
				</div>
			</section>

			<!-- SPACING & LAYOUT -->
			<section id="section-spacing" class="stylebook-section">
				<h2 class="section-title">Spacing & Layout</h2>
				<p style="font-size: 14px; color: var(--mh-color-text-muted); margin-bottom: 20px;">
					Instead of tracking padding and radiuses across dozens of components, the system uses a single unified scale. 
					Watch how these components breathe and scale proportionally based on your Master Unit setting.
				</p>
				
				<div style="background: var(--mh-color-body); padding: var(--mh-space-8, 64px); border: var(--mh-border-width, 1px) solid var(--mh-color-section); border-radius: var(--mh-border-radius, 4px);">
					<div style="background: var(--mh-color-main); padding: var(--mh-space-6, 32px); border-radius: var(--mh-border-radius, 4px); max-width: var(--mh-content-width, 1200px); margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
						
						<div style="margin-bottom: var(--mh-space-5, 24px);">
							<h3 style="margin: 0 0 var(--mh-space-2, 8px) 0;">Main Container</h3>
							<p style="margin: 0; color: var(--mh-color-text-muted);">
								This layout uses <code>--mh-space-8</code> for body padding, <code>--mh-space-6</code> for main content padding, and <code>--mh-border-radius</code> on everything.
							</p>
						</div>

						<div class="mh-card" style="padding: var(--mh-space-4, 16px);">
							<h4 style="margin: 0 0 var(--mh-space-3, 12px) 0;">Inner Card</h4>
							<p style="margin: 0 0 var(--mh-space-4, 16px) 0;">
								Nested elements naturally adapt using smaller spacing variants like <code>--mh-space-4</code>.
							</p>
							<div style="display: flex; gap: var(--mh-space-3, 12px);">
								<button class="mh-btn mh-btn-primary">Action</button>
								<button class="mh-btn mh-btn-outline">Cancel</button>
							</div>
						</div>
						
					</div>
				</div>
			</section>

			<!-- BUTTONS -->
			<section id="section-buttons" class="stylebook-section">
				<h2 class="section-title">Buttons & Calls to Action</h2>
				
				<div class="subsection-title">Standard Call To Action</div>
				<div class="btn-preview">
					<button class="mh-btn mh-btn-primary">Primary Button</button>
					<button class="mh-btn mh-btn-outline">Outline Button</button>
					<button class="mh-btn mh-btn-text">Text Button <span class="dashicons dashicons-arrow-right-alt"></span></button>
				</div>

				<div class="subsection-title">Brand / Utility</div>
				<div class="btn-preview">
					<button class="mh-btn mh-btn-primary color-brand">Brand Primary</button>
					<button class="mh-btn mh-btn-outline color-brand">Brand Outline</button>
					<button class="mh-btn mh-btn-text color-brand"><span class="dashicons dashicons-menu" style="font-size:24px;width:24px;height:24px;"></span> Menu</button>
				</div>

				<div class="subsection-title">Status & System</div>
				<div class="btn-preview" style="gap: 10px;">
					<button class="mh-btn mh-btn-primary color-success"><span class="dashicons dashicons-yes"></span> Success</button>
					<button class="mh-btn mh-btn-primary color-warning"><span class="dashicons dashicons-warning"></span> Warning</button>
					<button class="mh-btn mh-btn-primary color-danger"><span class="dashicons dashicons-no"></span> Danger</button>
					<button class="mh-btn mh-btn-primary color-info"><span class="dashicons dashicons-info"></span> Info</button>
				</div>
			</section>

			<!-- FORMS -->
			<section id="section-forms" class="stylebook-section">
				<h2 class="section-title">Forms & Inputs</h2>
				
				<div style="max-width: 500px;">
					<div class="form-group">
						<label>Standard Text Input</label>
						<input type="text" placeholder="Enter your name...">
					</div>
					<div class="form-group">
						<label>Select Dropdown</label>
						<select>
							<option>Option 1</option>
							<option>Option 2</option>
						</select>
					</div>
					<div class="form-group">
						<label>Textarea</label>
						<textarea rows="4" placeholder="Enter your message..."></textarea>
					</div>
					<div class="form-group" style="display:flex; gap:10px; align-items:center;">
						<input type="checkbox" id="check1"> <label for="check1" style="margin:0;">I agree to the terms</label>
					</div>
				</div>
			</section>

			<!-- CARDS & GRIDS -->
			<section id="section-cards" class="stylebook-section">
				<h2 class="section-title">Cards, Grids & Features</h2>
				
				<div class="card-grid">
					<!-- Standard Card -->
					<div class="card">
						<div class="card-img" style="background: url('https://placehold.co/400x200') center/cover;"></div>
						<div class="card-body">
							<h3 class="card-title">Feature Card</h3>
							<p style="font-size: 14px; color: var(--mh-color-text-muted);">This is a standard card component used for displaying blog posts, features, or team members.</p>
							<a href="#" class="mh-btn mh-btn-text">Read More</a>
						</div>
					</div>
					
					<!-- Pricing Box -->
					<div class="card" style="text-align: center; padding: var(--mh-space-6, 32px);">
						<div style="text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: var(--mh-color-brand-base);">Pro Plan</div>
						<h2 style="font-size: 3rem; margin: 15px 0;">$49<span style="font-size: 1rem; color: var(--mh-color-text-muted);">/mo</span></h2>
						<ul style="list-style: none; padding: 0; margin: 20px 0; color: var(--mh-color-text-muted); font-size: 14px; line-height: 2;">
							<li>Unlimited Projects</li>
							<li>24/7 Support</li>
							<li>Custom Domain</li>
						</ul>
						<button class="mh-btn mh-btn-primary" style="width: 100%;">Get Started</button>
					</div>

					<!-- Icon Box / Feature -->
					<div class="card" style="padding: 30px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; background: color-mix(in srgb, var(--mh-color-brand-base) 3%, transparent);">
						<div style="width: 60px; height: 60px; border-radius: 50%; background: color-mix(in srgb, var(--mh-color-brand-base) 10%, transparent); color: var(--mh-color-brand-base); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
							<span class="dashicons dashicons-admin-site-alt3" style="font-size: 30px; width: 30px; height: 30px;"></span>
						</div>
						<h3 class="card-title">Global Network</h3>
						<p style="font-size: 14px; color: var(--mh-color-text-muted); margin: 0;">Our infrastructure scales globally with absolute zero latency across regions.</p>
					</div>
				</div>
			</section>

			<!-- UI ELEMENTS -->
			<section id="section-ui" class="stylebook-section">
				<h2 class="section-title">UI Elements</h2>

				<div class="subsection-title">Alerts & Notifications (Transparency Layering)</div>
				<p style="font-size: 14px; color: var(--mh-color-text-muted); margin-bottom: 20px;">These alerts use <code>color-mix()</code> to create 10% opacity backgrounds from your solid status colors.</p>
				
				<div class="alert alert-success">
					<span class="dashicons dashicons-yes-alt"></span> System operations completed successfully.
				</div>
				<div class="alert alert-warning">
					<span class="dashicons dashicons-warning"></span> You are approaching your storage limit.
				</div>
				<div class="alert alert-danger">
					<span class="dashicons dashicons-dismiss"></span> Critical error! Unable to connect to the database.
				</div>
				<div class="alert alert-info">
					<span class="dashicons dashicons-info"></span> A new version of the theme is available for download.
				</div>
				
				<div class="subsection-title" style="margin-top: 40px;">Accordion / Toggle</div>
				<div class="accordion">
					<div class="accordion-item"><span>What is Magic Wand?</span> <span>+</span></div>
					<div class="accordion-item" style="background: var(--mh-color-section); cursor: default;">
						<div style="font-weight: normal; font-size: 14px; color: var(--mh-color-text-muted); padding: var(--mh-space-2, 8px) 0;">It is a visual compiler for WordPress that generates child themes instantly.</div>
					</div>
					<div class="accordion-item"><span>Does it support custom posts?</span> <span>+</span></div>
				</div>

				<div class="subsection-title" style="margin-top: 40px;">Progress Bars</div>
				<div class="progress-bar"><div class="progress-fill"></div></div>
				
				<div class="subsection-title" style="margin-top: 40px;">Dividers & Spacers</div>
				<hr style="border: none; border-top: 2px dashed var(--mh-color-border-base); margin: var(--mh-space-5, 24px) 0;">
			</section>

			<!-- MEDIA & GALLERIES -->
			<section id="section-media" class="stylebook-section">
				<h2 class="section-title">Media & Galleries</h2>
				<p style="font-size: 14px; color: var(--mh-color-text-muted); margin-bottom: 20px;">Standard WordPress image alignments and gallery grids.</p>
				<div style="display: flex; gap: var(--mh-space-4, 16px); flex-wrap: wrap;">
					<img src="https://picsum.photos/400/300?random=1" style="border-radius: var(--mh-border-radius, 4px); max-width: 100%; height: auto; border: var(--mh-border-width, 1px) solid var(--mh-color-section); flex: 1; min-width: 250px; object-fit: cover;">
					<div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--mh-space-2, 8px); flex: 1; min-width: 250px;">
						<img src="https://picsum.photos/200/145?random=2" style="border-radius: var(--mh-border-radius, 4px); width: 100%; height: 100%; object-fit: cover; border: var(--mh-border-width, 1px) solid var(--mh-color-section);">
						<img src="https://picsum.photos/200/145?random=3" style="border-radius: var(--mh-border-radius, 4px); width: 100%; height: 100%; object-fit: cover; border: var(--mh-border-width, 1px) solid var(--mh-color-section);">
						<img src="https://picsum.photos/200/145?random=4" style="border-radius: var(--mh-border-radius, 4px); width: 100%; height: 100%; object-fit: cover; border: var(--mh-border-width, 1px) solid var(--mh-color-section);">
						<img src="https://picsum.photos/200/145?random=5" style="border-radius: var(--mh-border-radius, 4px); width: 100%; height: 100%; object-fit: cover; border: var(--mh-border-width, 1px) solid var(--mh-color-section);">
					</div>
				</div>
			</section>

			<!-- POST & QUERY LOOP -->
			<section id="section-post" class="stylebook-section">
				<h2 class="section-title">Post & Query Loop</h2>
				<p style="font-size: 14px; color: var(--mh-color-text-muted); margin-bottom: 20px;">How standard blog posts and archives render.</p>
				<article class="mh-card" style="margin-bottom: 30px;">
					<h3 style="margin-top:0;"><a href="#" style="text-decoration:none;">The Magic of Web Design</a></h3>
					<div style="font-size: 12px; color: var(--mh-color-text-muted); margin-bottom: 15px;">Published on May 2, 2026 by Admin</div>
					<p>WordPress query loops display your dynamic content. This is a preview of a standard blog post excerpt showing how typography and spacing align within the card components...</p>
					<a href="#" class="mh-btn" style="background: transparent; color: var(--mh-color-link); padding: 0;">Read more &rarr;</a>
				</article>
				<article class="mh-card">
					<h3 style="margin-top:0;"><a href="#" style="text-decoration:none;">Optimizing Block Themes</a></h3>
					<div style="font-size: 12px; color: var(--mh-color-text-muted); margin-bottom: 15px;">Published on April 28, 2026 by Admin</div>
					<p>Block themes provide unprecedented control over site architecture. Let's explore how the new editor enhances performance...</p>
					<a href="#" class="mh-btn" style="background: transparent; color: var(--mh-color-link); padding: 0;">Read more &rarr;</a>
				</article>
			</section>

			<!-- COMMENTS -->
			<section id="section-comments" class="stylebook-section">
				<h2 class="section-title">Comments</h2>
				<div class="mh-card" style="margin-bottom: 20px; background: var(--mh-color-section); box-shadow: none;">
					<div style="display: flex; gap: var(--mh-space-3, 12px);">
						<div style="width: 40px; height: 40px; border-radius: 50%; background: var(--mh-color-brand-base); color: var(--mh-color-text-inverse); display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">U</div>
						<div>
							<div style="font-weight: bold; font-size: 14px;">User Name <span style="font-weight:normal; color: var(--mh-color-text-muted); font-size:12px; margin-left: 10px;">May 1, 2026</span></div>
							<p style="margin: 5px 0 0 0; font-size: 14px;">This is a fantastic article! The layout looks incredibly clean and magical.</p>
						</div>
					</div>
				</div>
				<div style="margin-top: var(--mh-space-5, 24px);">
					<h3 style="font-size: 16px; margin-bottom: var(--mh-space-3, 12px); font-family: var(--mh-font-heading);">Leave a Reply</h3>
					<div class="form-group">
						<label>Comment</label>
						<textarea rows="4" placeholder="Share your thoughts..."></textarea>
					</div>
					<button class="mh-btn mh-btn-primary">Post Comment</button>
				</div>
			</section>

		</main>
	</div>
	
	<?php wp_footer(); ?>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// --- Dynamic Swatch Text Contrast ---
			function updateSwatchTextColors() {
				var swatches = document.querySelectorAll('.color-swatch');
				swatches.forEach(function(swatch) {
					var bg = window.getComputedStyle(swatch).backgroundColor;
					var match = bg.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
					if (match) {
						var r = parseInt(match[1]);
						var g = parseInt(match[2]);
						var b = parseInt(match[3]);
						var luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
						if (luminance > 0.55) {
							swatch.style.color = '#111';
							swatch.style.textShadow = 'none';
						} else {
							swatch.style.color = '#fff';
							swatch.style.textShadow = '0 1px 3px rgba(0,0,0,0.8)';
						}
					}
				});
			}
			// Run once on load. If Customizer causes a refresh, it runs again.
			updateSwatchTextColors();

			var container = document.querySelector('.stylebook-content');
			
			if (container) {
				// --- Tabbed Navigation System ---
				var sections = document.querySelectorAll('.stylebook-section');
				var navLinks = document.querySelectorAll('.stylebook-nav a');

				window.mhSwitchTab = function(targetId) {
					sections.forEach(function(sec) {
						sec.classList.remove('active');
					});
					navLinks.forEach(function(link) {
						if (link.getAttribute('href') === '#' + targetId) {
							link.classList.add('active');
						} else {
							link.classList.remove('active');
						}
					});
					var targetSection = document.getElementById(targetId);
					if (targetSection) {
						targetSection.classList.add('active');
						if (container) container.scrollTop = 0;
					}
					// Save active tab
					sessionStorage.setItem('mh_stylebook_tab', targetId);
				};

				// --- Tab State Persistence & Initialization ---
				var activeTab = sessionStorage.getItem('mh_stylebook_tab') || 'section-colors';
				
				// Overwrite with active customizer parent state if available
				if ( window.parent && window.parent.wp && window.parent.wp.customize ) {
					var expandedSection = window.parent.wp.customize.state('expandedSection');
					if (expandedSection && expandedSection.get()) {
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
						var sectionId = expandedSection.get().id;
						if (map[sectionId]) {
							activeTab = map[sectionId];
						}
					}
					var expandedPanel = window.parent.wp.customize.state('expandedPanel');
					if (expandedPanel && expandedPanel.get()) {
						if (expandedPanel.get().id === 'magic_hat_colors_panel') {
							activeTab = 'section-colors';
						}
					}
				}
				
				// Apply active tab on load
				window.mhSwitchTab(activeTab);

				navLinks.forEach(function(link) {
					link.addEventListener('click', function(e) {
						e.preventDefault();
						var targetId = this.getAttribute('href').substring(1);
						window.mhSwitchTab(targetId);
						
						// Sync to Customizer Left Sidebar
						var targetControl = this.getAttribute('data-customizer-target');
						if (targetControl) {
							focusCustomizer(targetControl);
						}
					});
				});
			}

			// --- Customizer Cross-Communication ---
			if ( typeof wp !== 'undefined' && wp.customize && wp.customize.preview ) {
				wp.customize.bind('preview-ready', function() {
					wp.customize.preview.bind('mh-scroll-to', function(sectionId) {
						if (window.mhSwitchTab) {
							window.mhSwitchTab(sectionId);
						}
					});
				});
			}
		});
	</script>
	<script>
		// --- AI Palette Caching System ---
		function loadPaletteHistory() {
			const historyRaw = localStorage.getItem('mh_ai_palette_history');
			return historyRaw ? JSON.parse(historyRaw) : [];
		}

		function savePaletteToHistory(prompt, colors) {
			let history = loadPaletteHistory();
			// Remove if already exists to move it to the front
			history = history.filter(h => h.prompt.toLowerCase() !== prompt.toLowerCase());
			// Add to front
			history.unshift({ prompt, colors, timestamp: Date.now() });
			// Keep only last 10
			if (history.length > 10) history.pop();
			localStorage.setItem('mh_ai_palette_history', JSON.stringify(history));
			renderPaletteHistory();
		}

		function renderPaletteHistory() {
			const history = loadPaletteHistory();
			const container = document.getElementById('ai-palette-history');
			if (history.length === 0) {
				container.style.display = 'none';
				return;
			}
			
			container.style.display = 'flex';
			container.innerHTML = '<span style="font-size: 12px; color: #666; margin-right: 5px; align-self: center;">Recent:</span>';
			
			history.forEach((item, index) => {
				const btn = document.createElement('button');
				btn.innerText = item.prompt;
				btn.style.cssText = `
					background: #fff;
					border: 1px solid #ddd;
					padding: 4px 10px;
					border-radius: 20px;
					font-size: 11px;
					cursor: pointer;
					color: #333;
					transition: all 0.2s;
					display: flex;
					align-items: center;
					gap: 4px;
				`;
				
				// Show a tiny swatch dot of the brand color
				const dot = document.createElement('span');
				dot.style.cssText = `width:8px; height:8px; border-radius:50%; background:${item.colors.mh_color_brand_base};`;
				btn.prepend(dot);

				btn.onmouseover = () => btn.style.borderColor = 'var(--mh-color-brand-base, #62c9ff)';
				btn.onmouseout = () => btn.style.borderColor = '#ddd';
				
				btn.onclick = () => applyPaletteToCustomizer(item.colors);
				container.appendChild(btn);
			});
		}

		function applyPaletteToCustomizer(colors) {
			if ( window.parent && window.parent.wp && window.parent.wp.customize ) {
				for (const [key, val] of Object.entries(colors)) {
					if (window.parent.wp.customize(key)) {
						window.parent.wp.customize(key).set(val);
					}
				}
				window.parent.wp.customize.panel( 'magic_hat_colors_panel' ).expand();
			}
		}

		// Initialize history on load
		document.addEventListener('DOMContentLoaded', renderPaletteHistory);

		// --- UI Toggles ---
		function toggleAIConjurer() {
			const panel = document.getElementById('ai-conjurer-panel');
			const importPanel = document.getElementById('import-panel');
			const btn = document.getElementById('btn-conjure');
			
			if (panel.style.display === 'none') {
				panel.style.display = 'block';
				btn.classList.add('active-toggle');
				importPanel.style.display = 'none';
				document.getElementById('btn-import').classList.remove('active-toggle');
			} else {
				panel.style.display = 'none';
				btn.classList.remove('active-toggle');
			}
		}

		function toggleImportPanel() {
			const panel = document.getElementById('import-panel');
			const conjurePanel = document.getElementById('ai-conjurer-panel');
			const btn = document.getElementById('btn-import');
			
			if (panel.style.display === 'none') {
				panel.style.display = 'block';
				btn.classList.add('active-toggle');
				conjurePanel.style.display = 'none';
				document.getElementById('btn-conjure').classList.remove('active-toggle');
			} else {
				panel.style.display = 'none';
				btn.classList.remove('active-toggle');
			}
		}

		// --- Export / Import ---
		const COLOR_KEYS = [
			'mh_color_brand_base', 'mh_color_brand_hover', 'mh_color_brand_active', 'mh_color_brand_muted',
			'mh_color_cta_base', 'mh_color_cta_hover', 'mh_color_cta_active', 'mh_color_cta_muted',
			'mh_color_link', 'mh_color_link_hover', 'mh_color_link_active', 'mh_color_link_visited',
			'mh_color_text_heading', 'mh_color_text_main', 'mh_color_text_muted', 'mh_color_text_inverse',
			'mh_color_body', 'mh_color_main', 'mh_color_section', 'mh_color_card',
			'mh_color_border_base', 'mh_color_border_hover', 'mh_color_border_focus', 'mh_color_border_muted',
			'mh_color_success', 'mh_color_warning', 'mh_color_danger', 'mh_color_info'
		];
		const FULL_COLOR_KEYS = [...COLOR_KEYS, ...COLOR_KEYS.map(k => k + '_dark')];

		function generatePaletteName(colors) {
			let str = JSON.stringify(colors);
			let hash = 0;
			for (let i = 0; i < str.length; i++) {
				hash = ((hash << 5) - hash) + str.charCodeAt(i);
				hash |= 0;
			}
			hash = Math.abs(hash);

			const adjectives = ['neon', 'cyber', 'mystic', 'cosmic', 'abyssal', 'radiant', 'velvet', 'crystal', 'phantom', 'solar', 'lunar', 'astral', 'crimson', 'azure', 'jade', 'obsidian', 'pearl', 'quartz', 'nova', 'nebula'];
			const nouns = ['wizard', 'dragon', 'potion', 'grimoire', 'scroll', 'hat', 'wand', 'cloak', 'amulet', 'talisman', 'oracle', 'cipher', 'matrix', 'nexus', 'vortex', 'specter', 'wraith', 'prism', 'aura', 'flare'];
			
			const adj = adjectives[hash % adjectives.length];
			const noun = nouns[(hash >> 4) % nouns.length];
			const shortHash = hash.toString(16).substring(0, 4);

			return `magic-hat-${adj}-${noun}-${shortHash}.json`;
		}

		function exportPalette() {
			if ( window.parent && window.parent.wp && window.parent.wp.customize ) {
				const colors = {};
				FULL_COLOR_KEYS.forEach(key => {
					if(window.parent.wp.customize(key)) {
						colors[key] = window.parent.wp.customize(key).get();
					}
				});
				
				const exportObj = {
					version: "<?php echo esc_js( wp_get_theme()->get('Version') ); ?>",
					type: "Magic Hat Design Tokens",
					tokens: colors
				};
				const jsonStr = JSON.stringify(exportObj, null, 2);
				const fileName = generatePaletteName(colors);
				
				const blob = new Blob([jsonStr], { type: 'application/json' });
				const url = URL.createObjectURL(blob);
				
				try {
					// Use the parent document to bypass the iframe sandbox restriction
					const doc = (window.parent && window.parent.document) ? window.parent.document : document;
					const a = doc.createElement('a');
					a.href = url;
					a.download = fileName;
					doc.body.appendChild(a);
					a.click();
					doc.body.removeChild(a);
				} catch (e) {
					console.error("Download blocked:", e);
					// Fallback to textarea
					const textarea = document.getElementById('import-textarea');
					if (document.getElementById('import-panel').style.display === 'none') toggleImportPanel();
					textarea.value = jsonStr;
					textarea.focus();
					textarea.select();
					alert("Download blocked by browser sandbox. Please copy the JSON manually.");
				}
				
				setTimeout(() => URL.revokeObjectURL(url), 1000);
			}
		}

		function executeImport() {
			const textarea = document.getElementById('import-textarea');
			const jsonStr = textarea.value.trim();
			if (!jsonStr) {
				alert("Please paste valid JSON.");
				return;
			}
			try {
				const parsed = JSON.parse(jsonStr);
				let tokensToImport = parsed;
				
				// Support Versioned Exports (v1.1.0+)
				if (parsed.version && parsed.tokens) {
					tokensToImport = parsed.tokens;
					console.log("Importing Magic Hat Theme Version: " + parsed.version);
				}
				
				applyPaletteToCustomizer(tokensToImport);
				alert("✨ Palette imported successfully!");
				toggleImportPanel();
				textarea.value = '';
			} catch(e) {
				alert("❌ Invalid JSON format! " + e.message);
			}
		}

		async function conjureAIPalette() {
			var promptInput = document.getElementById('ai-palette-prompt');
			var promptText = promptInput.value.trim();
			var statusDiv = document.getElementById('ai-palette-status');
			var btn = document.getElementById('ai-conjure-btn');
			var btnText = btn.querySelector('.btn-text');
			var btnIcon = btn.querySelector('.dashicons');
			
			if (!promptText) {
				alert("Please describe a brand or vibe first!");
				return;
			}

			// Check Cache First!
			const history = loadPaletteHistory();
			const cached = history.find(h => h.prompt.toLowerCase() === promptText.toLowerCase());
			if (cached) {
				statusDiv.style.display = 'block';
				statusDiv.innerText = '✨ Pulled from your grimoire! Applied instantly.';
				statusDiv.style.color = '#10b981';
				applyPaletteToCustomizer(cached.colors);
				// Move to top
				savePaletteToHistory(cached.prompt, cached.colors);
				return;
			}
			
			// UI Loading State
			statusDiv.style.display = 'block';
			statusDiv.innerHTML = '✨ Please wait<span class="loading-ellipsis"></span>';
			statusDiv.style.color = 'var(--mh-color-brand-base, #62c9ff)';
			btn.classList.add('conjuring');
			btn.disabled = true;
			btnText.innerText = 'Conjuring...';
			btnIcon.classList.replace('dashicons-art', 'dashicons-update-alt');
			
			try {
				const response = await fetch('/wp-json/xophz/v1/gemini/generate', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-WP-Nonce': stylebook_rest_nonce
					},
					body: JSON.stringify({
						prompt: "Generate a color palette for this brand/vibe: " + promptText,
						system_instruction: `You are the most artistic, colorfully talented, and visionary UI/UX designer in the world. You possess a transcendent understanding of color theory, emotional resonance, and spatial contrast. You know color relationships better than anyone alive. 
						When the user describes a vibe or brand, you must envision a breathtaking, cohesive, and perfectly balanced color system.
						Return ONLY a valid JSON object mapping exactly these 56 keys to stunning hex color codes. Do not include any other text or markdown outside the JSON.
						Base Keys (28): mh_color_brand_base, mh_color_brand_hover, mh_color_brand_active, mh_color_brand_muted, mh_color_cta_base, mh_color_cta_hover, mh_color_cta_active, mh_color_cta_muted, mh_color_link, mh_color_link_hover, mh_color_link_active, mh_color_link_visited, mh_color_text_heading, mh_color_text_main, mh_color_text_muted, mh_color_text_inverse, mh_color_body, mh_color_main, mh_color_section, mh_color_card, mh_color_border_base, mh_color_border_hover, mh_color_border_focus, mh_color_border_muted, mh_color_success, mh_color_warning, mh_color_danger, mh_color_info.
						Dark Keys (28): Duplicate the exact keys above but append "_dark" to each key name (e.g. mh_color_brand_base_dark). Provide a beautifully harmonized dark mode counterpart.
						CRITICAL CONTRAST RULE: You MUST ensure your background colors (body, main, section, card) contrast perfectly with your text and primary colors. For example, never put white/light text on a light background, and never put dark text on a dark background. The base keys are for Light Mode (light backgrounds, dark text). The _dark keys are for Dark Mode (dark backgrounds, light text). Let your genius shine.`
					})
				});
				
				const data = await response.json();
				if (response.ok && data.text) {
					// Extract JSON from markdown if present
					const match = data.text.match(/```(?:json)?\s*([\s\S]*?)\s*```/i);
					const jsonStr = match ? match[1] : data.text;
					const colors = JSON.parse(jsonStr.trim());
					
					// Apply and Save!
					applyPaletteToCustomizer(colors);
					savePaletteToHistory(promptText, colors);
					
					statusDiv.innerText = '✨ Palette conjured and saved to your grimoire!';
					statusDiv.style.color = '#10b981'; // success
					promptInput.value = '';
				} else {
					throw new Error(data?.message || data?.error?.message || "Failed to generate");
				}
			} catch (err) {
				console.error("AI Palette Error:", err);
				statusDiv.innerText = '❌ The magic fizzled: ' + err.message;
				statusDiv.style.color = '#ef4444'; // error
			} finally {
				// Restore UI State
				btn.classList.remove('conjuring');
				btn.disabled = false;
				btnText.innerText = 'Conjure';
				btnIcon.classList.replace('dashicons-update-alt', 'dashicons-art');
			}
		}
	</script>
</body>
</html>
<?php
exit;
