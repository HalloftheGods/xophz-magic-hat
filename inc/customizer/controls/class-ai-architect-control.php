<?php
/**
 * Customizer AI Page Architect Control
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Magic_Hat_AI_Architect_Control' ) ) {
	/**
	 * AI Page Architect control interfacing with WP Connectors and local synthesizers.
	 */
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
			<div class="mh-ai-studio-container">
				<!-- AI Studio Workspace Header -->
				<div class="mh-ai-studio-header">
					<div class="mh-ai-studio-header-top">
						<div class="mh-ai-studio-title-wrap">
							<span class="mh-ai-studio-title">🪄 AI Studio Architect</span>
							<span id="mh-ai-status-badge" class="mh-ai-badge mh-ai-badge-active">
								Loading...
							</span>
						</div>
						<div class="mh-ai-studio-actions-top">
							<button type="button" id="mh-ai-toggle-studio-width" class="button button-secondary button-small mh-ai-btn-studio" title="Toggle wide studio view">
								⇄ Expand
							</button>
							<button type="button" id="mh-ai-toggle-settings" class="button button-secondary button-small mh-ai-btn-studio" title="Toggle model, vibe, and page settings">
								⚙️ Settings
							</button>
						</div>
					</div>
					<p class="mh-ai-studio-desc">
						Chat conversationally with the Architect to build, modify, and refine your website in real time.
					</p>
				</div>

				<!-- Collapsible Settings Drawer -->
				<div id="mh-ai-settings-drawer" class="mh-ai-settings-drawer" style="display: none;">
					<div class="mh-ai-drawer-field">
						<div class="mh-ai-field-label-row">
							<label for="mh-ai-connector">AI Connector:</label>
							<a href="<?php echo esc_url( admin_url( 'options-connectors.php' ) ); ?>" target="_blank" class="mh-ai-link">⚙️ WP Connectors</a>
						</div>
						<select id="mh-ai-connector" class="mh-ai-select">
							<?php foreach ( $connectors as $cid => $cinfo ) : ?>
								<option value="<?php echo esc_attr( $cid ); ?>" <?php selected( $cid, $default_connector ); ?> data-configured="<?php echo ! empty( $cinfo['configured'] ) ? '1' : '0'; ?>">
									<?php echo ! empty( $cinfo['configured'] ) ? '● ' : '○ '; ?><?php echo esc_html( $cinfo['name'] ); ?><?php echo ! empty( $cinfo['configured'] ) ? '' : ' (Not Configured)'; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="mh-ai-drawer-field">
						<label for="mh-ai-model">Model:</label>
						<select id="mh-ai-model" class="mh-ai-select">
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

					<div class="mh-ai-drawer-field">
						<label for="mh-ai-vibe">Visual Vibe:</label>
						<select id="mh-ai-vibe" class="mh-ai-select">
							<option value="starship-neon" selected>🌌 Starship Neon (Deep obsidian, cyan glow)</option>
							<option value="minimal-glass">💎 Minimal Glass (Translucent frosted clean)</option>
							<option value="cyberpunk-dusk">⚡ Cyberpunk Dusk (High contrast magenta &amp; cyan)</option>
							<option value="solar-dawn">🌅 Solar Dawn (Warm golden hour lighting)</option>
							<option value="enterprise-clean">🏢 Enterprise Clean (Navy slate corporate)</option>
							<option value="creative-studio">🎨 Creative Studio (Asymmetric editorial pop)</option>
						</select>
					</div>

					<div class="mh-ai-drawer-field">
						<label for="mh-ai-archetype">Layout Archetype:</label>
						<select id="mh-ai-archetype" class="mh-ai-select">
							<option value="landing" selected>🚀 SaaS Landing (Hero + Features + Metrics + CTA)</option>
							<option value="portfolio">💼 Portfolio Showcase (Split Hero + Project Masonry)</option>
							<option value="saas">⚡ Product Platform (Value Hero + Pricing Matrix)</option>
							<option value="editorial">📖 Editorial Story (Cover Hero + Longform Grid)</option>
							<option value="microhub">🍱 Bento Micro-Hub (Bento Dashboard Grid)</option>
						</select>
					</div>

					<?php $front_page_id = absint( get_option( 'page_on_front', 0 ) ); ?>
					<div class="mh-ai-drawer-field">
						<label for="mh-ai-target-page">Destination Page:</label>
						<select id="mh-ai-target-page" class="mh-ai-select">
							<?php if ( $front_page_id > 0 ) : 
								$front_post  = get_post( $front_page_id );
								$front_title = $front_post ? $front_post->post_title : __( 'Home', 'xophz-magic-hat' );
							?>
								<option value="<?php echo esc_attr( $front_page_id ); ?>" selected>
									🏠 <?php echo esc_html( $front_title ); ?> (<?php esc_html_e( 'Front Page', 'xophz-magic-hat' ); ?>)
								</option>
							<?php endif; ?>
							<option value="0" <?php echo ! $front_page_id ? 'selected' : ''; ?>><?php esc_html_e( 'Customizer Canvas Only (Draft Preview)', 'xophz-magic-hat' ); ?></option>
							<?php foreach ( $pages as $p ) : 
								if ( $p->ID === $front_page_id ) {
									continue;
								}
							?>
								<option value="<?php echo esc_attr( $p->ID ); ?>"><?php echo esc_html( $p->post_title ); ?> (ID: <?php echo esc_html( $p->ID ); ?>)</option>
							<?php endforeach; ?>
						</select>
						<label class="mh-ai-checkbox-label">
							<input type="checkbox" id="mh-ai-auto-save" value="1" <?php echo $front_page_id > 0 ? 'checked' : ''; ?>>
							<span><?php esc_html_e( 'Publish directly to live website', 'xophz-magic-hat' ); ?></span>
						</label>
					</div>
				</div>

				<!-- Starter Archetype Quick Chips -->
				<div class="mh-ai-starter-chips-wrap">
					<span class="mh-ai-chips-caption">Starter Blueprints:</span>
					<div class="mh-ai-starter-chips">
						<button type="button" class="mh-ai-chip-btn" data-prompt="Create an autonomous AI developer platform with high-converting hero, 3-card feature matrix, live cluster telemetry, and CTA" data-vibe="starship-neon" data-arch="saas">🚀 SaaS Platform</button>
						<button type="button" class="mh-ai-chip-btn" data-prompt="Design a clean frosted glass portfolio with split hero, case studies grid, and contact form" data-vibe="minimal-glass" data-arch="portfolio">💼 Glass Portfolio</button>
						<button type="button" class="mh-ai-chip-btn" data-prompt="Build a high contrast cyberpunk product launch page with dark obsidian cards and vibrant highlights" data-vibe="cyberpunk-dusk" data-arch="landing">⚡ Cyber Launch</button>
						<button type="button" class="mh-ai-chip-btn" data-prompt="Design an elegant editorial manifesto on circadian rhythm design systems and spatial software" data-vibe="solar-dawn" data-arch="editorial">📖 Editorial Story</button>
						<button type="button" class="mh-ai-chip-btn" data-prompt="Create a bento-grid showcase with interactive metrics, feature cards, and dark glass surfaces" data-vibe="starship-neon" data-arch="microhub">🍱 Bento Hub</button>
					</div>
				</div>

				<!-- AI Studio Chat Stream Container -->
				<div class="mh-ai-chat-thread-container">
					<div id="mh-ai-chat-thread" class="mh-ai-chat-thread">
						<!-- Initial Greeting Bubble -->
						<div class="mh-ai-msg mh-ai-msg-assistant">
							<div class="mh-ai-msg-header">
								<span class="mh-ai-msg-avatar">🪄</span>
								<span class="mh-ai-msg-name">Architect</span>
								<span class="mh-ai-msg-time">Ready</span>
							</div>
							<div class="mh-ai-msg-body">
								<p>Welcome to <strong>AI Studio</strong>. I am your Gutenberg site architect. Tell me what kind of page or section you want to build, or click a starter blueprint above.</p>
								<p class="mh-ai-msg-subtext">You can chat iteratively to refine headlines, add pricing tables, change vibes, or insert new sections.</p>
							</div>
						</div>
					</div>
				</div>

				<!-- Real-Time Progress Stream / Status Box -->
				<div id="mh-ai-status" class="mh-ai-status-panel" style="display: none;"></div>

				<!-- Quick Iteration Follow-Up Chips -->
				<div class="mh-ai-followup-chips-wrap">
					<div class="mh-ai-followup-chips">
						<button type="button" class="mh-ai-followup-chip" data-append="Add a 3-tier pricing table with Starter, Professional, and Enterprise cards">+ Pricing Table</button>
						<button type="button" class="mh-ai-followup-chip" data-append="Add a customer testimonial grid with quotes and star ratings">+ Testimonials</button>
						<button type="button" class="mh-ai-followup-chip" data-append="Add an interactive FAQ accordion section with 4 questions">+ FAQ Section</button>
						<button type="button" class="mh-ai-followup-chip" data-append="Make the headline more punchy and switch vibe to Cyberpunk Dusk">⚡ Cyber Vibe</button>
						<button type="button" class="mh-ai-followup-chip" data-append="Switch palette to Solar Dawn warm golden hour illumination">🌅 Solar Vibe</button>
					</div>
				</div>

				<!-- Bottom Chat Input Dock -->
				<div class="mh-ai-chat-dock">
					<div class="mh-ai-input-wrap">
						<textarea id="mh-ai-prompt" class="mh-ai-chat-input" rows="2" placeholder="Chat with the Architect... (e.g. 'Add a pricing table', 'Make the hero more punchy')"></textarea>
						<div class="mh-ai-input-actions">
							<div class="mh-ai-input-hints">
								<span id="mh-ai-active-model-indicator" class="mh-ai-mini-model-pill">Gemini 3.5 Flash</span>
								<span class="mh-ai-key-hint">Enter to send</span>
							</div>
							<button type="button" id="mh-ai-conjure-btn" class="button button-primary mh-ai-send-btn">
								<span id="mh-ai-conjure-icon" class="mh-ai-send-icon">🪄</span>
								<span id="mh-ai-conjure-label">Send</span>
							</button>
						</div>
					</div>
				</div>

				<!-- History & Checkpoints Bar -->
				<div class="mh-ai-checkpoints-bar">
					<button type="button" id="mh-ai-new-thread-btn" class="mh-ai-checkpoint-btn" title="Start fresh layout thread">
						✨ Fresh Page
					</button>
					<button type="button" id="mh-ai-save-to-page" class="mh-ai-checkpoint-btn" style="display: none;">
						💾 Save to DB
					</button>
				</div>
			</div>
			<?php
		}
	}
}
