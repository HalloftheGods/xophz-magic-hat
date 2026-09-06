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
						<a href="<?php echo esc_url( admin_url( 'options-connectors.php' ) ); ?>" target="_blank" style="font-size: 10px; color: #2563eb; text-decoration: none;" title="Configure API keys in WP Connectors">
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
}
