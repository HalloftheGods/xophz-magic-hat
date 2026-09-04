<?php
/**
 * Magic Hat AI Page Architect & Gemini Generation Engine
 *
 * Provides REST API endpoints and generation logic for both:
 * 1. AI-driven circadian color palettes (Stylebook)
 * 2. Multi-vibe, multi-layout Gutenberg block page synthesis (Customizer Page Architect)
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Magic_Hat_AI_Architect {

	/**
	 * Singleton instance
	 */
	private static $instance = null;

	/**
	 * REST namespace
	 */
	const REST_NAMESPACE = 'xophz/v1';

	/**
	 * Get singleton instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Register REST routes
	 */
	public function register_rest_routes() {
		// Dedicated Magic Hat AI route
		register_rest_route(
			self::REST_NAMESPACE,
			'/magic-hat/ai-generate',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'handle_generate' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);

		// Backward-compatible route
		register_rest_route(
			self::REST_NAMESPACE,
			'/gemini/generate',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'handle_generate' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);

		register_rest_route(
			self::REST_NAMESPACE,
			'/ai/save-page',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'handle_save_page' ),
				'permission_callback' => array( $this, 'check_permission' ),
			)
		);
	}

	/**
	 * Check user capabilities for customization
	 */
	public function check_permission() {
		return current_user_can( 'edit_theme_options' );
	}

	/**
	 * Retrieve configured Gemini API key
	 */
	public static function get_api_key() {
		if ( defined( 'GEMINI_API_KEY' ) && ! empty( GEMINI_API_KEY ) ) {
			return GEMINI_API_KEY;
		}
		$env_key = getenv( 'GEMINI_API_KEY' );
		if ( ! empty( $env_key ) ) {
			return $env_key;
		}
		if ( ! empty( $_ENV['GEMINI_API_KEY'] ) ) {
			return $_ENV['GEMINI_API_KEY'];
		}
		$opt_key = get_option( 'xophz_gemini_api_key', '' );
		if ( ! empty( $opt_key ) ) {
			return $opt_key;
		}
		$compass_key = get_option( 'compass_gemini_api_key', '' );
		if ( ! empty( $compass_key ) ) {
			return $compass_key;
		}
		return '';
	}

	/**
	 * Handle generation requests
	 */
	public function handle_generate( WP_REST_Request $request ) {
		$params             = $request->get_json_params();
		$prompt             = isset( $params['prompt'] ) ? sanitize_text_field( $params['prompt'] ) : '';
		$system_instruction = isset( $params['system_instruction'] ) ? sanitize_textarea_field( $params['system_instruction'] ) : '';
		$vibe               = isset( $params['vibe'] ) ? sanitize_key( $params['vibe'] ) : 'starship-neon';
		$archetype          = isset( $params['archetype'] ) ? sanitize_key( $params['archetype'] ) : 'landing';
		$type               = isset( $params['type'] ) ? sanitize_key( $params['type'] ) : '';

		// Detect if this is a color palette request (e.g. from Stylebook)
		if ( 'palette' === $type || stripos( $prompt, 'color palette' ) !== false || stripos( $system_instruction, 'Base Keys (28' ) !== false ) {
			return $this->handle_palette_generation( $prompt, $system_instruction );
		}

		// Otherwise, this is a Page / Layout generation request
		return $this->handle_page_generation( $prompt, $vibe, $archetype, $params );
	}

	/**
	 * Handle Palette generation for Stylebook
	 */
	private function handle_palette_generation( $prompt, $system_instruction ) {
		$api_key = self::get_api_key();

		if ( ! empty( $api_key ) ) {
			$remote_result = $this->call_gemini_api( $prompt, $system_instruction, $api_key );
			if ( ! is_wp_error( $remote_result ) && ! empty( $remote_result ) ) {
				return rest_ensure_response( array(
					'success' => true,
					'text'    => $remote_result,
					'source'  => 'gemini-api',
				) );
			}
		}

		// Procedural fallback palette generator
		$fallback_palette = $this->generate_procedural_palette( $prompt );
		return rest_ensure_response( array(
			'success' => true,
			'text'    => wp_json_encode( $fallback_palette ),
			'source'  => 'procedural-synthesizer',
		) );
	}

	/**
	 * Handle Page Layout generation
	 */
	private function handle_page_generation( $prompt, $vibe, $archetype, $params ) {
		$api_key     = self::get_api_key();
		$blocks_html = '';
		$source      = 'procedural-synthesizer';

		if ( ! empty( $api_key ) ) {
			$ai_prompt = $this->build_page_ai_prompt( $prompt, $vibe, $archetype );
			$system    = 'You are a master WordPress theme and Gutenberg block architect. You build complete, production-ready WordPress pages using standard core Gutenberg blocks (wp:group, wp:heading, wp:paragraph, wp:buttons, wp:columns, wp:separator, wp:list). Never output raw HTML layout tags like <div> or <section> without wrapping in valid Gutenberg block comments. Output ONLY valid Gutenberg block markup, with no conversational filler.';
			$result    = $this->call_gemini_api( $ai_prompt, $system, $api_key );

			if ( ! is_wp_error( $result ) && ! empty( $result ) ) {
				// Strip code fences if model wrapped in markdown
				$cleaned = preg_replace( '/^```(?:html|gutenberg)?\s*/i', '', trim( $result ) );
				$cleaned = preg_replace( '/\s*```$/', '', $cleaned );
				if ( stripos( $cleaned, '<!-- wp:' ) !== false ) {
					$blocks_html = $cleaned;
					$source      = 'gemini-api';
				}
			}
		}

		// Fallback to rich architectural synthesizer if Gemini was not called or failed
		if ( empty( $blocks_html ) ) {
			$blocks_html = $this->synthesize_page_blocks( $prompt, $vibe, $archetype );
		}

		$page_id = 0;
		$target_id = isset( $params['target_page_id'] ) ? absint( $params['target_page_id'] ) : 0;
		$action    = isset( $params['action'] ) ? sanitize_key( $params['action'] ) : 'generate_only';

		if ( 'apply_to_page' === $action && $target_id > 0 ) {
			wp_update_post( array(
				'ID'           => $target_id,
				'post_content' => $blocks_html,
			) );
			$page_id = $target_id;
		} elseif ( 'create_page' === $action ) {
			$page_title = ! empty( $params['page_title'] ) ? sanitize_text_field( $params['page_title'] ) : 'AI Generated Page';
			$page_id    = wp_insert_post( array(
				'post_title'   => $page_title,
				'post_content' => $blocks_html,
				'post_status'  => 'publish',
				'post_type'    => 'page',
			) );
		}

		return rest_ensure_response( array(
			'success'     => true,
			'source'      => $source,
			'vibe'        => $vibe,
			'archetype'   => $archetype,
			'blocks_html' => $blocks_html,
			'page_id'     => $page_id,
			'preview_url' => $page_id > 0 ? get_permalink( $page_id ) : '',
		) );
	}

	/**
	 * Handle saving page content from Customizer
	 */
	public function handle_save_page( WP_REST_Request $request ) {
		$params  = $request->get_json_params();
		$page_id = isset( $params['page_id'] ) ? absint( $params['page_id'] ) : 0;
		$content = isset( $params['content'] ) ? wp_kses_post( $params['content'] ) : '';

		if ( $page_id <= 0 ) {
			return new WP_Error( 'invalid_page_id', 'Valid Page ID required.', array( 'status' => 400 ) );
		}

		$result = wp_update_post( array(
			'ID'           => $page_id,
			'post_content' => $content,
		) );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return rest_ensure_response( array(
			'success' => true,
			'page_id' => $page_id,
		) );
	}

	/**
	 * Call official Google Gemini REST API
	 */
	private function call_gemini_api( $prompt, $system_instruction, $api_key ) {
		$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . urlencode( $api_key );

		$body = array(
			'contents' => array(
				array(
					'parts' => array(
						array( 'text' => $prompt ),
					),
				),
			),
		);

		if ( ! empty( $system_instruction ) ) {
			$body['systemInstruction'] = array(
				'parts' => array(
					array( 'text' => $system_instruction ),
				),
			);
		}

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array( 'Content-Type' => 'application/json' ),
				'body'    => wp_json_encode( $body ),
				'timeout' => 45,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $code || empty( $data['candidates'][0]['content']['parts'][0]['text'] ) ) {
			return new WP_Error( 'gemini_error', 'Gemini API call failed with code: ' . $code );
		}

		return $data['candidates'][0]['content']['parts'][0]['text'];
	}

	/**
	 * Build AI prompt for page generation
	 */
	private function build_page_ai_prompt( $prompt, $vibe, $archetype ) {
		$vibe_desc = $this->get_vibe_descriptor( $vibe );
		$arch_desc = $this->get_archetype_descriptor( $archetype );

		return "Generate a complete WordPress Gutenberg page for the following request:
User Request: {$prompt}
Visual Vibe: {$vibe} ({$vibe_desc})
Layout Archetype: {$archetype} ({$arch_desc})

Requirements:
1. Use standard WordPress core Gutenberg blocks: <!-- wp:group -->, <!-- wp:heading -->, <!-- wp:paragraph -->, <!-- wp:buttons -->, <!-- wp:columns -->, <!-- wp:separator -->.
2. Structure sections logically (Hero banner with CTA, feature breakdowns, social proof, metrics/stats, and closing conversion section).
3. Utilize Project Compass CSS classes and semantic design tokens:
   - Backgrounds: has-surface-body-background-color, has-surface-main-background-color, has-surface-card-background-color
   - Text Colors: has-text-heading-color, has-brand-base-color, has-text-muted-color
   - Spacing presets: var(--wp--preset--spacing--6), var(--wp--preset--spacing--8), var(--wp--preset--spacing--12)
4. Do not include synthetic/mock personal identities (e.g. no fake names or fake emails). Keep copy punchy, direct, and professional.
5. Return ONLY Gutenberg block comments and markup.";
	}

	/**
	 * Visual Vibe Descriptors
	 */
	private function get_vibe_descriptor( $vibe ) {
		$vibes = array(
			'starship-neon'    => 'Signature dark starship aesthetic, neon cyan #62c9ff highlights, glassmorphic panels, glowing border accents',
			'minimal-glass'    => 'Ultra-clean translucent frosted glass, subtle 1px white borders, crisp typography, generous whitespace',
			'cyberpunk-dusk'   => 'High contrast dark obsidian canvas, dual-tone magenta and cyan accents, bold geometric structure',
			'solar-dawn'       => 'Warm sunrise illumination, golden amber and violet gradients, welcoming organic cards',
			'enterprise-clean' => 'Refined deep navy and cobalt slate surfaces, sharp corporate layout, authoritative clarity',
			'creative-studio'  => 'Asymmetric editorial composition, oversized display headings, vibrant accent pops',
		);
		return isset( $vibes[ $vibe ] ) ? $vibes[ $vibe ] : $vibes['starship-neon'];
	}

	/**
	 * Layout Archetype Descriptors
	 */
	private function get_archetype_descriptor( $archetype ) {
		$archetypes = array(
			'landing'   => 'High-conversion SaaS landing page: Hero with CTA -> Feature Grid -> Key Metrics -> Testimonials -> Final Banner',
			'portfolio' => 'Creative showcase: Split Statement Hero -> Project Masonry Cards -> Capabilities Matrix -> Transmission Link',
			'saas'      => 'Product platform launch: Value proposition hero -> Feature Deep Dives -> Tiered Pricing Matrix -> Conversion Form',
			'editorial' => 'Longform narrative story: Full-bleed title block -> Multi-column article grid -> Blockquote pullouts -> Newsletter',
			'microhub'  => 'Compact Bento dashboard: Central identity badge -> 4-card bento action grid -> Social endpoints',
		);
		return isset( $archetypes[ $archetype ] ) ? $archetypes[ $archetype ] : $archetypes['landing'];
	}

	/**
	 * Synthesize high-quality Gutenberg blocks procedurally
	 */
	public function synthesize_page_blocks( $user_prompt, $vibe, $archetype ) {
		$title = ! empty( $user_prompt ) ? esc_html( $user_prompt ) : 'Autonomous Digital Experience';

		switch ( $archetype ) {
			case 'portfolio':
				return $this->synthesize_portfolio_layout( $title, $vibe );
			case 'saas':
				return $this->synthesize_saas_layout( $title, $vibe );
			case 'editorial':
				return $this->synthesize_editorial_layout( $title, $vibe );
			case 'microhub':
				return $this->synthesize_microhub_layout( $title, $vibe );
			case 'landing':
			default:
				return $this->synthesize_landing_layout( $title, $vibe );
		}
	}

	/**
	 * Procedural Landing Layout
	 */
	private function synthesize_landing_layout( $title, $vibe ) {
		return '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|12","bottom":"var:preset|spacing|12","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-body","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-body-background-color has-background" style="padding-top:var(--wp--preset--spacing--12);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--12);padding-left:var(--wp--preset--spacing--6)">
	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|6"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"border":{"radius":"9999px","width":"1px","color":"rgba(98,201,255,0.3)"},"spacing":{"padding":{"top":"var:preset|spacing|1","bottom":"var:preset|spacing|1","left":"var:preset|spacing|3","right":"var:preset|spacing|3"}}},"backgroundColor":"surface-card","layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-group has-surface-card-background-color has-background" style="border-color:rgba(98,201,255,0.3);border-width:1px;border-radius:9999px;padding-top:var(--wp--preset--spacing--1);padding-right:var(--wp--preset--spacing--3);padding-bottom:var(--wp--preset--spacing--1);padding-left:var(--wp--preset--spacing--3)">
			<!-- wp:paragraph {"textColor":"brand-base","fontSize":"xs","style":{"typography":{"fontWeight":"700","letterSpacing":"1px"}}} -->
			<p class="has-brand-base-color has-text-color has-xs-font-size" style="font-weight:700;letter-spacing:1px">VIBE: ' . esc_html( strtoupper( str_replace( '-', ' ', $vibe ) ) ) . '</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontFamily":"var:preset|font-family|heading","fontSize":"clamp(2.5rem, 6vw, 4.2rem)","lineHeight":"1.15","fontWeight":"800"}},"textColor":"text-heading"} -->
		<h1 class="wp-block-heading has-text-align-center has-text-heading-color has-text-color" style="font-family:var(--wp--preset--font-family--heading);font-size:clamp(2.5rem, 6vw, 4.2rem);font-weight:800;line-height:1.15">' . $title . '</h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","textColor":"text-muted","style":{"typography":{"fontSize":"1.25rem","lineHeight":"1.6"}},"layout":{"type":"constrained","justifyContent":"center"}} -->
		<p class="has-text-align-center has-text-muted-color has-text-color" style="font-size:1.25rem;line-height:1.6">Designed and deployed via the Magic Hat AI Page Architect. Scalable, responsive, and seamlessly linked to live WordPress design tokens.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|4"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--4)">
			<!-- wp:button {"backgroundColor":"brand-base","textColor":"surface-body","style":{"border":{"radius":"6px"},"typography":{"fontWeight":"700"}}} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-surface-body-color has-brand-base-background-color has-text-color has-background wp-element-button" href="#explore" style="border-radius:6px;font-weight:700">Explore Architecture</a></div>
			<!-- /wp:button -->
			<!-- wp:button {"variant":"outline","style":{"border":{"radius":"6px","width":"1px","color":"rgba(255,255,255,0.2)"},"color":{"text":"var:preset|color|text-main"}}} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#features" style="border-color:rgba(255,255,255,0.2);border-width:1px;border-radius:6px;color:var(--wp--preset--color--text-main)">System Overview</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|12","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-main","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-main-background-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--12);padding-left:var(--wp--preset--spacing--6)">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|6","left":"var:preset|spacing|6"}}}} -->
		<div class="wp-block-columns">
			<!-- wp:column {"style":{"border":{"radius":"8px","width":"1px","color":"rgba(255,255,255,0.08)"},"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-card"} -->
			<div class="wp-block-column has-surface-card-background-color has-background" style="border-color:rgba(255,255,255,0.08);border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6)">
				<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var:preset|font-family|heading"}},"textColor":"brand-base"} -->
				<h3 class="wp-block-heading has-brand-base-color has-text-color" style="font-family:var(--wp--preset--font-family--heading)">Circadian Engine</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"text-muted"} -->
				<p class="has-text-muted-color has-text-color">Colors dynamically cycle between Day, Twilight, and Night phases across solar cosine calculations.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"style":{"border":{"radius":"8px","width":"1px","color":"rgba(255,255,255,0.08)"},"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-card"} -->
			<div class="wp-block-column has-surface-card-background-color has-background" style="border-color:rgba(255,255,255,0.08);border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6)">
				<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var:preset|font-family|heading"}},"textColor":"brand-base"} -->
				<h3 class="wp-block-heading has-brand-base-color has-text-color" style="font-family:var(--wp--preset--font-family--heading)">Dual Studio Modes</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"text-muted"} -->
				<p class="has-text-muted-color has-text-color">Direct visual tuning via WordPress Customizer coupled with native Gutenberg Site Editor template control.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"style":{"border":{"radius":"8px","width":"1px","color":"rgba(255,255,255,0.08)"},"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-card"} -->
			<div class="wp-block-column has-surface-card-background-color has-background" style="border-color:rgba(255,255,255,0.08);border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6)">
				<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var:preset|font-family|heading"}},"textColor":"brand-base"} -->
				<h3 class="wp-block-heading has-brand-base-color has-text-color" style="font-family:var(--wp--preset--font-family--heading)">Token Precision</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"text-muted"} -->
				<p class="has-text-muted-color has-text-color">Unified CSS Custom Properties automatically synchronize with Gutenberg block editor palettes and global styles.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->';
	}

	/**
	 * Procedural Portfolio Layout
	 */
	private function synthesize_portfolio_layout( $title, $vibe ) {
		return '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|12","bottom":"var:preset|spacing|12","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-body","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-body-background-color has-background" style="padding-top:var(--wp--preset--spacing--12);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--12);padding-left:var(--wp--preset--spacing--6)">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:heading {"level":1,"style":{"typography":{"fontFamily":"var:preset|font-family|heading","fontSize":"clamp(2.5rem, 5vw, 4rem)","fontWeight":"800"}},"textColor":"text-heading"} -->
		<h1 class="wp-block-heading has-text-heading-color has-text-color" style="font-family:var(--wp--preset--font-family--heading);font-size:clamp(2.5rem, 5vw, 4rem);font-weight:800">' . $title . '</h1>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"textColor":"brand-base","fontSize":"lg","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|8"}}}} -->
		<p class="has-brand-base-color has-text-color has-lg-font-size" style="margin-bottom:var(--wp--preset--spacing--8)">Selected Works &amp; Architectural Expeditions</p>
		<!-- /wp:paragraph -->

		<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|6","left":"var:preset|spacing|6"}}}} -->
		<div class="wp-block-columns">
			<!-- wp:column {"style":{"border":{"radius":"8px","width":"1px","color":"rgba(255,255,255,0.08)"},"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-card"} -->
			<div class="wp-block-column has-surface-card-background-color has-background" style="border-color:rgba(255,255,255,0.08);border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--8);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--8);padding-left:var(--wp--preset--spacing--6)">
				<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var:preset|font-family|heading"}},"textColor":"text-heading"} -->
				<h3 class="wp-block-heading has-text-heading-color has-text-color" style="font-family:var(--wp--preset--font-family--heading)">Sector Alpha Protocol</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"text-muted"} -->
				<p class="has-text-muted-color has-text-color">Autonomous UI components compiled with reactive zero-dependency Web Components.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"style":{"border":{"radius":"8px","width":"1px","color":"rgba(255,255,255,0.08)"},"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-card"} -->
			<div class="wp-block-column has-surface-card-background-color has-background" style="border-color:rgba(255,255,255,0.08);border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--8);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--8);padding-left:var(--wp--preset--spacing--6)">
				<!-- wp:heading {"level":3,"style":{"typography":{"fontFamily":"var:preset|font-family|heading"}},"textColor":"text-heading"} -->
				<h3 class="wp-block-heading has-text-heading-color has-text-color" style="font-family:var(--wp--preset--font-family--heading)">Event Horizon Navigation</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"text-muted"} -->
				<p class="has-text-muted-color has-text-color">Mathematical spatial lighting engine synchronized with high-precision solar telemetry.</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->';
	}

	/**
	 * Procedural SaaS Layout
	 */
	private function synthesize_saas_layout( $title, $vibe ) {
		return '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|12","bottom":"var:preset|spacing|12","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-body","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-body-background-color has-background" style="padding-top:var(--wp--preset--spacing--12);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--12);padding-left:var(--wp--preset--spacing--6)">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontFamily":"var:preset|font-family|heading","fontSize":"clamp(2.5rem, 5.5vw, 4rem)","fontWeight":"800"}},"textColor":"text-heading"} -->
		<h1 class="wp-block-heading has-text-align-center has-text-heading-color has-text-color" style="font-family:var(--wp--preset--font-family--heading);font-size:clamp(2.5rem, 5.5vw, 4rem);font-weight:800">' . $title . '</h1>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"text-muted","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|8"}}}} -->
		<p class="has-text-align-center has-text-muted-color has-text-color" style="margin-bottom:var(--wp--preset--spacing--8)">Enterprise-grade spatial design infrastructure for the modern web.</p>
		<!-- /wp:paragraph -->

		<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|6","left":"var:preset|spacing|6"}}}} -->
		<div class="wp-block-columns">
			<!-- wp:column {"style":{"border":{"radius":"8px","width":"1px","color":"rgba(255,255,255,0.08)"},"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-card"} -->
			<div class="wp-block-column has-surface-card-background-color has-background" style="border-color:rgba(255,255,255,0.08);border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6)">
				<!-- wp:heading {"level":3,"textColor":"text-heading"} -->
				<h3 class="wp-block-heading has-text-heading-color has-text-color">Standard Fleet</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"brand-base","fontSize":"2xl","style":{"typography":{"fontWeight":"700"}}} -->
				<p class="has-brand-base-color has-text-color has-2xl-font-size" style="font-weight:700">$29<span style="font-size:1rem;color:var(--wp--preset--color--text-muted)">/mo</span></p>
				<!-- /wp:paragraph -->
				<!-- wp:list {"textColor":"text-muted","fontSize":"sm"} -->
				<ul class="wp-block-list has-text-muted-color has-text-color has-sm-font-size">
					<li>Full Site Editing block templates</li>
					<li>24-hour Circadian lighting engine</li>
					<li>Standard token palette export</li>
				</ul>
				<!-- /wp:list -->
				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|4"}}}} -->
				<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--4)"><!-- wp:button {"backgroundColor":"brand-base","textColor":"surface-body","style":{"border":{"radius":"4px"}}} --><div class="wp-block-button"><a class="wp-block-button__link has-surface-body-color has-brand-base-background-color has-text-color has-background wp-element-button" href="#" style="border-radius:4px">Select Tier</a></div><!-- /wp:button --></div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"style":{"border":{"radius":"8px","width":"1px","color":"var(--wp--preset--color--brand-base)"},"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-main"} -->
			<div class="wp-block-column has-surface-main-background-color has-background" style="border-color:var(--wp--preset--color--brand-base);border-width:1px;border-radius:8px;padding-top:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6)">
				<!-- wp:heading {"level":3,"textColor":"brand-base"} -->
				<h3 class="wp-block-heading has-brand-base-color has-text-color">Command Tier</h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph {"textColor":"brand-base","fontSize":"2xl","style":{"typography":{"fontWeight":"700"}}} -->
				<p class="has-brand-base-color has-text-color has-2xl-font-size" style="font-weight:700">$99<span style="font-size:1rem;color:var(--wp--preset--color--text-muted)">/mo</span></p>
				<!-- /wp:paragraph -->
				<!-- wp:list {"textColor":"text-muted","fontSize":"sm"} -->
				<ul class="wp-block-list has-text-muted-color has-text-color has-sm-font-size">
					<li>Unlimited AI Page Architect generations</li>
					<li>Bidirectional Customizer synchronization</li>
					<li>Dedicated Project Compass cluster support</li>
				</ul>
				<!-- /wp:list -->
				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|4"}}}} -->
				<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--4)"><!-- wp:button {"backgroundColor":"brand-base","textColor":"surface-body","style":{"border":{"radius":"4px"}}} --><div class="wp-block-button"><a class="wp-block-button__link has-surface-body-color has-brand-base-background-color has-text-color has-background wp-element-button" href="#" style="border-radius:4px">Engage Command</a></div><!-- /wp:button --></div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->';
	}

	/**
	 * Procedural Editorial Layout
	 */
	private function synthesize_editorial_layout( $title, $vibe ) {
		return '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|12","bottom":"var:preset|spacing|12","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-body","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-body-background-color has-background" style="padding-top:var(--wp--preset--spacing--12);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--12);padding-left:var(--wp--preset--spacing--6)">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:heading {"level":1,"style":{"typography":{"fontFamily":"var:preset|font-family|heading","fontSize":"clamp(2.5rem, 5vw, 4.2rem)","lineHeight":"1.15"}},"textColor":"text-heading"} -->
		<h1 class="wp-block-heading has-text-heading-color has-text-color" style="font-family:var(--wp--preset--font-family--heading);font-size:clamp(2.5rem, 5vw, 4.2rem);line-height:1.15">' . $title . '</h1>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"textColor":"brand-base","fontSize":"base","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|8"}}}} -->
		<p class="has-brand-base-color has-text-color has-base-font-size" style="margin-bottom:var(--wp--preset--spacing--8)">DISPATCH &bull; TRANSLINK BROADCAST</p>
		<!-- /wp:paragraph -->
		<!-- wp:separator {"backgroundColor":"surface-card"} -->
		<hr class="wp-block-separator has-text-color has-surface-card-background-color has-background"/>
		<!-- /wp:separator -->
		<!-- wp:paragraph {"style":{"typography":{"fontSize":"1.2rem","lineHeight":"1.8"}},"textColor":"text-main"} -->
		<p class="has-text-main-color has-text-color" style="font-size:1.2rem;line-height:1.8">In the realm of modern web platforms, architecture is no longer static. Through mathematical illumination and semantic design tokens, interfaces adapt continuously to circadian daylight rhythms, aligning human perception with computational clarity.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->';
	}

	/**
	 * Procedural Micro-Hub Layout
	 */
	private function synthesize_microhub_layout( $title, $vibe ) {
		return '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8","left":"var:preset|spacing|4","right":"var:preset|spacing|4"}}},"backgroundColor":"surface-body","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-surface-body-background-color has-background" style="padding-top:var(--wp--preset--spacing--8);padding-right:var(--wp--preset--spacing--4);padding-bottom:var(--wp--preset--spacing--8);padding-left:var(--wp--preset--spacing--4)">
	<!-- wp:group {"style":{"border":{"radius":"12px","width":"1px","color":"rgba(255,255,255,0.08)"},"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"backgroundColor":"surface-main","layout":{"type":"constrained"}} -->
	<div class="wp-block-group has-surface-main-background-color has-background" style="border-color:rgba(255,255,255,0.08);border-width:1px;border-radius:12px;padding-top:var(--wp--preset--spacing--8);padding-right:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--8);padding-left:var(--wp--preset--spacing--6)">
		<!-- wp:site-logo {"width":64,"shouldSyncIcon":true,"align":"center","style":{"border":{"radius":"9999px"}}} /-->
		<!-- wp:heading {"textAlign":"center","level":2,"textColor":"text-heading"} -->
		<h2 class="wp-block-heading has-text-align-center has-text-heading-color has-text-color">' . $title . '</h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center","textColor":"text-muted","fontSize":"sm"} -->
		<p class="has-text-align-center has-text-muted-color has-text-color has-sm-font-size">Unified Command Hub</p>
		<!-- /wp:paragraph -->
		<!-- wp:buttons {"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|6"},"blockGap":"var:preset|spacing|3"}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--6)">
			<!-- wp:button {"width":100,"backgroundColor":"surface-card","textColor":"text-main","style":{"border":{"radius":"6px","width":"1px","color":"rgba(98,201,255,0.3)"}}} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-text-main-color has-surface-card-background-color has-text-color has-background wp-element-button" href="#portal" style="border-color:rgba(98,201,255,0.3);border-width:1px;border-radius:6px">Enter Compass Portal</a></div>
			<!-- /wp:button -->
			<!-- wp:button {"width":100,"backgroundColor":"surface-card","textColor":"text-main","style":{"border":{"radius":"6px","width":"1px","color":"rgba(255,255,255,0.1)"}}} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-text-main-color has-surface-card-background-color has-text-color has-background wp-element-button" href="#docs" style="border-color:rgba(255,255,255,0.1);border-width:1px;border-radius:6px">System Documentation</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->';
	}

	/**
	 * Procedural 84-color token palette for Stylebook
	 */
	private function generate_procedural_palette( $prompt ) {
		// Generate an exquisite neon starship or tailored palette
		$palette = array(
			// Light Mode
			'mh_color_brand_base'     => '#0284c7',
			'mh_color_brand_hover'    => '#0369a1',
			'mh_color_brand_active'   => '#075985',
			'mh_color_brand_muted'    => '#e0f2fe',
			'mh_color_cta_base'       => '#e11d48',
			'mh_color_cta_hover'      => '#be123c',
			'mh_color_cta_active'     => '#9f1239',
			'mh_color_cta_muted'      => '#ffe4e6',
			'mh_color_link'           => '#0284c7',
			'mh_color_link_hover'     => '#e11d48',
			'mh_color_link_active'    => '#be123c',
			'mh_color_link_visited'   => '#7c3aed',
			'mh_color_text_heading'   => '#0f172a',
			'mh_color_text_main'      => '#334155',
			'mh_color_text_muted'     => '#64748b',
			'mh_color_text_inverse'   => '#ffffff',
			'mh_color_body'           => '#f8fafc',
			'mh_color_main'           => '#ffffff',
			'mh_color_section'        => '#f1f5f9',
			'mh_color_card'           => '#ffffff',
			'mh_color_border_base'    => '#e2e8f0',
			'mh_color_border_hover'   => '#cbd5e1',
			'mh_color_border_focus'   => '#0284c7',
			'mh_color_border_muted'   => '#f1f5f9',
			'mh_color_success'        => '#10b981',
			'mh_color_warning'        => '#f59e0b',
			'mh_color_danger'         => '#ef4444',
			'mh_color_info'           => '#3b82f6',

			// Twilight Mode
			'mh_color_brand_base_twilight'   => '#38bdf8',
			'mh_color_brand_hover_twilight'  => '#7dd3fc',
			'mh_color_brand_active_twilight' => '#0284c7',
			'mh_color_brand_muted_twilight'  => '#0c4a6e',
			'mh_color_cta_base_twilight'     => '#fb7185',
			'mh_color_cta_hover_twilight'    => '#fda4af',
			'mh_color_cta_active_twilight'   => '#f43f5e',
			'mh_color_cta_muted_twilight'    => '#4c0519',
			'mh_color_link_twilight'         => '#38bdf8',
			'mh_color_link_hover_twilight'   => '#fb7185',
			'mh_color_link_active_twilight'  => '#fda4af',
			'mh_color_link_visited_twilight' => '#a78bfa',
			'mh_color_text_heading_twilight' => '#f8fafc',
			'mh_color_text_main_twilight'    => '#e2e8f0',
			'mh_color_text_muted_twilight'   => '#94a3b8',
			'mh_color_text_inverse_twilight' => '#0f172a',
			'mh_color_body_twilight'         => '#0f172a',
			'mh_color_main_twilight'         => '#1e293b',
			'mh_color_section_twilight'      => '#334155',
			'mh_color_card_twilight'         => '#1e293b',
			'mh_color_border_base_twilight'  => '#334155',
			'mh_color_border_hover_twilight' => '#475569',
			'mh_color_border_focus_twilight' => '#38bdf8',
			'mh_color_border_muted_twilight' => '#1e293b',
			'mh_color_success_twilight'      => '#34d399',
			'mh_color_warning_twilight'      => '#fbbf24',
			'mh_color_danger_twilight'       => '#f87171',
			'mh_color_info_twilight'         => '#60a5fa',

			// Dark Mode (Starship Neon Signature)
			'mh_color_brand_base_dark'   => '#62c9ff',
			'mh_color_brand_hover_dark'  => '#8be0ff',
			'mh_color_brand_active_dark' => '#40a0df',
			'mh_color_brand_muted_dark'  => '#1a3a4d',
			'mh_color_cta_base_dark'     => '#ff3366',
			'mh_color_cta_hover_dark'    => '#ff668c',
			'mh_color_cta_active_dark'   => '#e62050',
			'mh_color_cta_muted_dark'    => '#4d1a26',
			'mh_color_link_dark'         => '#62c9ff',
			'mh_color_link_hover_dark'   => '#ff3366',
			'mh_color_link_active_dark'  => '#e62050',
			'mh_color_link_visited_dark' => '#9b59b6',
			'mh_color_text_heading_dark' => '#ffffff',
			'mh_color_text_main_dark'    => '#f8fafc',
			'mh_color_text_muted_dark'   => '#94a3b8',
			'mh_color_text_inverse_dark' => '#0f172a',
			'mh_color_body_dark'         => '#0a0b10',
			'mh_color_main_dark'         => '#0f172a',
			'mh_color_section_dark'      => 'rgba(255, 255, 255, 0.02)',
			'mh_color_card_dark'         => 'rgba(255, 255, 255, 0.05)',
			'mh_color_border_base_dark'  => 'rgba(255, 255, 255, 0.1)',
			'mh_color_border_hover_dark' => '#62c9ff',
			'mh_color_border_focus_dark' => '#62c9ff',
			'mh_color_border_muted_dark' => 'rgba(255, 255, 255, 0.05)',
			'mh_color_success_dark'      => '#10b981',
			'mh_color_warning_dark'      => '#f59e0b',
			'mh_color_danger_dark'       => '#ef4444',
			'mh_color_info_dark'         => '#3b82f6',
		);

		return $palette;
	}
}

// Initialize AI Architect
Magic_Hat_AI_Architect::get_instance();
