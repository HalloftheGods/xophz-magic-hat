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
		body { background-color: var(--mh-bg-body, #f0f0f1); color: var(--mh-color-text, #333); font-family: var(--mh-font-family, sans-serif); line-height: var(--mh-line-height, 1.6); margin: 0; padding: 0; }
		.stylebook-layout { display: flex; height: 100vh; overflow: hidden; background-color: transparent; }
		
		/* Sidebar Navigation */
		.stylebook-nav { width: 250px; background: #fff; border-right: 1px solid #ddd; overflow-y: auto; padding: 20px 0; }
		.stylebook-nav ul { list-style: none; margin: 0; padding: 0; }
		.stylebook-nav li a { display: block; padding: 10px 20px; color: #555; text-decoration: none; font-size: 14px; font-weight: 500; }
		.stylebook-nav li a:hover { background: #f5f5f5; color: var(--mh-primary-color); }
		.stylebook-nav .nav-header { padding: 10px 20px; font-size: 11px; text-transform: uppercase; color: #999; letter-spacing: 1px; margin-top: 15px; }
		
		/* Main Content */
		.stylebook-content { flex: 1; overflow-y: auto; padding: 60px; background-color: var(--mh-bg-main, #ffffff); }
		.stylebook-section { max-width: 1000px; margin: 0 auto 80px auto; background: var(--mh-bg-section, #ffffff); padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: relative; }
		
		.edit-trigger { position: absolute; top: 20px; right: 20px; background: #f0f0f1; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; color: #333; display: flex; align-items: center; gap: 6px; }
		.edit-trigger:hover { background: var(--mh-primary-color); color: #fff; }
		
		.section-title { font-size: 24px; margin-top: 0; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px; color: #111; }
		.subsection-title { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin: 30px 0 15px 0; }
		
		/* Colors */
		.color-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 20px; }
		.color-swatch { height: 100px; border-radius: 8px; box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1); display: flex; align-items: flex-end; padding: 10px; font-size: 12px; font-weight: bold; }
		
		/* Typography */
		.type-preview h1 { font-size: 3rem; margin: 0 0 10px 0; line-height: 1.2; }
		.type-preview h2 { font-size: 2.5rem; margin: 0 0 10px 0; }
		.type-preview h3 { font-size: 2rem; margin: 0 0 10px 0; }
		.type-preview p { font-size: 1rem; line-height: 1.6; color: var(--mh-color-text); max-width: 800px; }
		.type-preview blockquote { border-left: 4px solid var(--mh-color-brand); margin: 20px 0; padding: 10px 20px; font-size: 1.2rem; font-style: italic; background: rgba(0,0,0,0.02); }
		
		/* Buttons */
		.btn-preview { display: flex; gap: 15px; flex-wrap: wrap; align-items: center; }
		.btn { display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; border-radius: var(--mh-border-radius, 4px); font-family: inherit; font-weight: 600; cursor: pointer; border: none; text-decoration: none; transition: all 0.2s; }
		.btn-primary { background: var(--mh-color-primary-cta); color: #fff; }
		.btn-outline { background: transparent; border: 2px solid var(--mh-color-primary-cta); color: var(--mh-color-primary-cta); }
		.btn-text { background: transparent; color: var(--mh-color-primary-cta); padding: 12px 0; }
		
		/* Cards */
		.card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; }
		.card { background: var(--mh-bg-section, #ffffff); border-radius: var(--mh-border-radius, 8px); box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid var(--mh-border-color, #eee); }
		.card-img { height: 150px; background: #ddd; }
		.card-body { padding: 20px; }
		.card-title { margin: 0 0 10px 0; font-size: 1.2rem; }
		
		/* Forms */
		.form-group { margin-bottom: 20px; }
		.form-group label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; }
		.form-control { width: 100%; padding: 12px; border: 1px solid var(--mh-border-color, #ccc); border-radius: var(--mh-border-radius, 4px); font-family: inherit; font-size: 1rem; background: var(--mh-input-bg, #fff); color: inherit; }
		.form-control:focus { outline: none; border-color: var(--mh-color-brand); box-shadow: 0 0 0 3px rgba(0,229,255,0.2); }
		
		/* UI Elements */
		.accordion { border: 1px solid var(--mh-border-color, #eee); border-radius: var(--mh-border-radius, 4px); }
		.accordion-item { border-bottom: 1px solid var(--mh-border-color, #eee); padding: 15px 20px; cursor: pointer; display: flex; justify-content: space-between; font-weight: 500; }
		.accordion-item:last-child { border-bottom: none; }
		
		.progress-bar { height: 8px; background: #eee; border-radius: 4px; overflow: hidden; margin-bottom: 20px; }
		.progress-fill { height: 100%; background: var(--mh-color-brand); width: 65%; }

		/* Alerts / Banners (Transparency Demo) */
		.alert { padding: 15px 20px; border-radius: var(--mh-border-radius, 4px); margin-bottom: 15px; display: flex; align-items: center; gap: 10px; font-weight: 500; }
		.alert-success { background: rgba(var(--mh-color-success-rgb), 0.1); color: var(--mh-color-success); border: 1px solid rgba(var(--mh-color-success-rgb), 0.2); }
		.alert-warning { background: rgba(var(--mh-color-warning-rgb), 0.1); color: var(--mh-color-warning); border: 1px solid rgba(var(--mh-color-warning-rgb), 0.2); }
		.alert-danger { background: rgba(var(--mh-color-danger-rgb), 0.1); color: var(--mh-color-danger); border: 1px solid rgba(var(--mh-color-danger-rgb), 0.2); }
		.alert-info { background: rgba(var(--mh-color-info-rgb), 0.1); color: var(--mh-color-info); border: 1px solid rgba(var(--mh-color-info-rgb), 0.2); }

		/* Dark Mode overrides based on CSS var injection if needed */
		body[style*="--mh-bg-color: #0"] .stylebook-nav, body[style*="--mh-bg-color: #1"] .stylebook-nav { background: #111; border-color: #333; }
		body[style*="--mh-bg-color: #0"] .stylebook-section, body[style*="--mh-bg-color: #1"] .stylebook-section { background: #1e1e1e; color: #fff; }
		body[style*="--mh-bg-color: #0"] .section-title, body[style*="--mh-bg-color: #1"] .section-title { color: #fff; border-color: #333; }
	</style>
	<script>
		function focusCustomizer(sectionId) {
			if (window.parent && window.parent.wp && window.parent.wp.customize) {
				window.parent.wp.customize.section(sectionId).focus();
			} else {
				console.warn('Customizer API not found. Are you previewing outside the customizer?');
			}
		}
	</script>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	
	<div class="stylebook-layout">
		<!-- Sidebar Navigation -->
		<nav class="stylebook-nav">
			<div class="nav-header">Design Tokens</div>
			<ul>
				<li><a href="#section-colors">Colors</a></li>
				<li><a href="#section-typography">Typography</a></li>
			</ul>
			<div class="nav-header">Components</div>
			<ul>
				<li><a href="#section-buttons">Buttons</a></li>
				<li><a href="#section-forms">Forms & Inputs</a></li>
				<li><a href="#section-cards">Cards & Grids</a></li>
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
			<section id="section-colors" class="stylebook-section">
				<button class="edit-trigger" onclick="focusCustomizer('magic_hat_colors')">
					<span class="dashicons dashicons-edit"></span> Edit Colors
				</button>
				<h2 class="section-title">Colors</h2>
				
				<div class="subsection-title">Core Identity & Text</div>
				<div class="color-grid">
					<div class="color-swatch" style="background: var(--mh-color-brand); color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Brand</div>
					<div class="color-swatch" style="background: var(--mh-color-text); color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Text</div>
					<div class="color-swatch" style="background: var(--mh-color-link); color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Link</div>
				</div>

				<div class="subsection-title">Surfaces & Backgrounds</div>
				<div class="color-grid">
					<div class="color-swatch" style="background: var(--mh-bg-body); color: var(--mh-color-text); border: 1px solid rgba(0,0,0,0.1);">Body BG</div>
					<div class="color-swatch" style="background: var(--mh-bg-main); color: var(--mh-color-text); border: 1px solid rgba(0,0,0,0.1);">Main BG</div>
					<div class="color-swatch" style="background: var(--mh-bg-section); color: var(--mh-color-text); border: 1px solid rgba(0,0,0,0.1);">Section BG</div>
				</div>

				<div class="subsection-title">Action</div>
				<div class="color-grid">
					<div class="color-swatch" style="background: var(--mh-color-primary-cta); color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Primary CTA</div>
					<div class="color-swatch" style="background: var(--mh-color-secondary-cta); color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Secondary CTA</div>
				</div>

				<div class="subsection-title">Status</div>
				<div class="color-grid">
					<div class="color-swatch" style="background: var(--mh-color-success); color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Success</div>
					<div class="color-swatch" style="background: var(--mh-color-warning); color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Warning</div>
					<div class="color-swatch" style="background: var(--mh-color-danger); color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Danger</div>
					<div class="color-swatch" style="background: var(--mh-color-info); color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Info</div>
				</div>
			</section>

			<!-- TYPOGRAPHY -->
			<section id="section-typography" class="stylebook-section">
				<button class="edit-trigger" onclick="focusCustomizer('magic_hat_typography')">
					<span class="dashicons dashicons-edit"></span> Edit Typography
				</button>
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

			<!-- BUTTONS -->
			<section id="section-buttons" class="stylebook-section">
				<button class="edit-trigger" onclick="focusCustomizer('magic_hat_buttons')">
					<span class="dashicons dashicons-edit"></span> Edit Buttons
				</button>
				<h2 class="section-title">Buttons & Calls to Action</h2>
				
				<div class="btn-preview">
					<button class="btn btn-primary">Primary Button</button>
					<button class="btn btn-outline">Outline Button</button>
					<button class="btn btn-text">Text Button <span class="dashicons dashicons-arrow-right-alt"></span></button>
				</div>
			</section>

			<!-- FORMS -->
			<section id="section-forms" class="stylebook-section">
				<button class="edit-trigger" onclick="focusCustomizer('magic_hat_forms')">
					<span class="dashicons dashicons-edit"></span> Edit Forms
				</button>
				<h2 class="section-title">Forms & Inputs</h2>
				
				<div style="max-width: 500px;">
					<div class="form-group">
						<label>Standard Text Input</label>
						<input type="text" class="form-control" placeholder="Enter your name...">
					</div>
					<div class="form-group">
						<label>Select Dropdown</label>
						<select class="form-control">
							<option>Option 1</option>
							<option>Option 2</option>
						</select>
					</div>
					<div class="form-group">
						<label>Textarea</label>
						<textarea class="form-control" rows="4" placeholder="Enter your message..."></textarea>
					</div>
					<div class="form-group" style="display:flex; gap:10px; align-items:center;">
						<input type="checkbox" id="check1"> <label for="check1" style="margin:0;">I agree to the terms</label>
					</div>
				</div>
			</section>

			<!-- CARDS & GRIDS -->
			<section id="section-cards" class="stylebook-section">
				<button class="edit-trigger" onclick="focusCustomizer('magic_hat_cards')">
					<span class="dashicons dashicons-edit"></span> Edit Cards
				</button>
				<h2 class="section-title">Cards, Grids & Features</h2>
				
				<div class="card-grid">
					<!-- Standard Card -->
					<div class="card">
						<div class="card-img" style="background: url('https://placehold.co/400x200') center/cover;"></div>
						<div class="card-body">
							<h3 class="card-title">Feature Card</h3>
							<p style="font-size: 14px; color: #666;">This is a standard card component used for displaying blog posts, features, or team members.</p>
							<a href="#" class="btn btn-text" style="padding:0;">Read More</a>
						</div>
					</div>
					
					<!-- Pricing Box -->
					<div class="card" style="text-align: center; padding: 30px;">
						<div style="text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: var(--mh-color-brand);">Pro Plan</div>
						<h2 style="font-size: 3rem; margin: 15px 0;">$49<span style="font-size: 1rem; color: #888;">/mo</span></h2>
						<ul style="list-style: none; padding: 0; margin: 20px 0; color: #555; font-size: 14px; line-height: 2;">
							<li>Unlimited Projects</li>
							<li>24/7 Support</li>
							<li>Custom Domain</li>
						</ul>
						<button class="btn btn-primary" style="width: 100%;">Get Started</button>
					</div>

					<!-- Icon Box / Feature -->
					<div class="card" style="padding: 30px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(var(--mh-color-brand-rgb), 0.03);">
						<div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(var(--mh-color-brand-rgb), 0.1); color: var(--mh-color-brand); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
							<span class="dashicons dashicons-admin-site-alt3" style="font-size: 30px; width: 30px; height: 30px;"></span>
						</div>
						<h3 class="card-title">Global Network</h3>
						<p style="font-size: 14px; color: #666; margin: 0;">Our infrastructure scales globally with absolute zero latency across regions.</p>
					</div>
				</div>
			</section>

			<!-- UI ELEMENTS -->
			<section id="section-ui" class="stylebook-section">
				<h2 class="section-title">UI Elements</h2>

				<div class="subsection-title">Alerts & Notifications (Transparency Layering)</div>
				<p style="font-size: 14px; color: #666; margin-bottom: 20px;">These components demonstrate the use of the generated RGB variables to create 10% opacity backgrounds based on your solid status colors.</p>
				
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
					<div class="accordion-item" style="background: rgba(0,0,0,0.02); cursor: default;">
						<div style="font-weight: normal; font-size: 14px; color: #555; padding: 10px 0;">It is a visual compiler for WordPress that generates child themes instantly.</div>
					</div>
					<div class="accordion-item"><span>Does it support custom posts?</span> <span>+</span></div>
				</div>

				<div class="subsection-title" style="margin-top: 40px;">Progress Bars</div>
				<div class="progress-bar"><div class="progress-fill"></div></div>
				
				<div class="subsection-title" style="margin-top: 40px;">Dividers & Spacers</div>
				<hr style="border: none; border-top: 2px dashed var(--mh-border-color, #ccc); margin: 20px 0;">
			</section>

		</main>
	</div>
	
	<?php wp_footer(); ?>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			if ( typeof wp !== 'undefined' && wp.customize && wp.customize.preview ) {
				wp.customize.bind('preview-ready', function() {
					wp.customize.preview.bind('mh-scroll-to', function(sectionId) {
						var el = document.getElementById(sectionId);
						if (el) {
							var container = document.querySelector('.stylebook-content');
							container.scrollTo({
								top: el.offsetTop - 60, // Account for top padding
								behavior: 'smooth'
							});
						}
					});
				});
			}
		});
	</script>
</body>
</html>
<?php
exit;
