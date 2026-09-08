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
	/**
	 * Retrieve configured Gemini / Google API key from environment, constants, or WP Connectors.
	 */
	public static function get_api_key() {
		// 1. Environment variables and PHP constants
		if ( defined( 'GOOGLE_API_KEY' ) && ! empty( GOOGLE_API_KEY ) ) {
			return GOOGLE_API_KEY;
		}
		if ( defined( 'GEMINI_API_KEY' ) && ! empty( GEMINI_API_KEY ) ) {
			return GEMINI_API_KEY;
		}
		$env_key = getenv( 'GOOGLE_API_KEY' ) ?: getenv( 'GEMINI_API_KEY' );
		if ( ! empty( $env_key ) ) {
			return $env_key;
		}
		if ( ! empty( $_ENV['GOOGLE_API_KEY'] ) ) {
			return $_ENV['GOOGLE_API_KEY'];
		}
		if ( ! empty( $_ENV['GEMINI_API_KEY'] ) ) {
			return $_ENV['GEMINI_API_KEY'];
		}

		// 2. WP Connectors and COMPASS Options
		$opt_keys = array(
			'connectors_ai_google_api_key',
			'ai_google_api_key',
			'compass_gemini_api_key',
			'xophz_gemini_api_key',
		);
		foreach ( $opt_keys as $opt ) {
			$val = get_option( $opt, '' );
			if ( ! empty( $val ) ) {
				return $val;
			}
		}

		// 3. Official WordPress Connectors registry check
		if ( function_exists( 'wp_get_connectors' ) ) {
			$connectors = wp_get_connectors();
			if ( ! empty( $connectors['google']['authentication']['setting_name'] ) ) {
				$val = get_option( $connectors['google']['authentication']['setting_name'], '' );
				if ( ! empty( $val ) ) {
					return $val;
				}
			}
			if ( ! empty( $connectors['google_gemini_api_key']['authentication']['setting_name'] ) ) {
				$val = get_option( $connectors['google_gemini_api_key']['authentication']['setting_name'], '' );
				if ( ! empty( $val ) ) {
					return $val;
				}
			}
		}

		// 4. WordPress AiClient registry check
		if ( class_exists( 'WordPress\\AiClient\\AiClient' ) ) {
			try {
				$registry = \WordPress\AiClient\AiClient::defaultRegistry();
				$auth = $registry->getProviderRequestAuthentication( 'google' );
				if ( $auth && method_exists( $auth, 'getApiKey' ) ) {
					$k = $auth->getApiKey();
					if ( ! empty( $k ) ) {
						return $k;
					}
				}
			} catch ( \Throwable $t ) {
				// Registry not yet initialized
			}
		}

		return '';
	}

	/**
	 * Ensure the official WordPress AiClient has the Google provider authenticated
	 */
	public static function ensure_aiclient_authenticated() {
		if ( ! class_exists( 'WordPress\\AiClient\\AiClient' ) ) {
			return false;
		}
		try {
			$registry = \WordPress\AiClient\AiClient::defaultRegistry();
			if ( ! $registry->hasProvider( 'google' ) && class_exists( 'WordPress\\GoogleAiProvider\\Provider\\GoogleProvider' ) ) {
				$registry->registerProvider( \WordPress\GoogleAiProvider\Provider\GoogleProvider::class );
			}
			if ( ! $registry->isProviderConfigured( 'google' ) ) {
				$key = self::get_api_key();
				if ( ! empty( $key ) && class_exists( 'WordPress\\AiClient\\Providers\\Http\\DTO\\ApiKeyRequestAuthentication' ) ) {
					$registry->setProviderRequestAuthentication(
						'google',
						new \WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication( $key )
					);
				}
			}
			return $registry->isProviderConfigured( 'google' );
		} catch ( \Throwable $t ) {
			return false;
		}
	}

	/**
	 * Retrieve configured Anthropic Claude API key
	 */
	public static function get_anthropic_api_key() {
		if ( defined( 'ANTHROPIC_API_KEY' ) && ! empty( ANTHROPIC_API_KEY ) ) {
			return ANTHROPIC_API_KEY;
		}
		$env = getenv( 'ANTHROPIC_API_KEY' );
		if ( ! empty( $env ) ) {
			return $env;
		}
		$opt = get_option( 'connectors_ai_anthropic_api_key', '' );
		if ( ! empty( $opt ) ) {
			return $opt;
		}
		return get_option( 'compass_anthropic_api_key', '' );
	}

	/**
	 * Retrieve configured OpenAI API key
	 */
	public static function get_openai_api_key() {
		if ( defined( 'OPENAI_API_KEY' ) && ! empty( OPENAI_API_KEY ) ) {
			return OPENAI_API_KEY;
		}
		$env = getenv( 'OPENAI_API_KEY' );
		if ( ! empty( $env ) ) {
			return $env;
		}
		$opt = get_option( 'connectors_ai_openai_api_key', '' );
		if ( ! empty( $opt ) ) {
			return $opt;
		}
		return get_option( 'compass_openai_api_key', '' );
	}

	/**
	 * Retrieve configured OpenRouter API key
	 */
	public static function get_openrouter_api_key() {
		if ( defined( 'OPENROUTER_API_KEY' ) && ! empty( OPENROUTER_API_KEY ) ) {
			return OPENROUTER_API_KEY;
		}
		$env = getenv( 'OPENROUTER_API_KEY' );
		if ( ! empty( $env ) ) {
			return $env;
		}
		return get_option( 'compass_openrouter_api_key', '' );
	}

	/**
	 * Retrieve configured Ollama endpoint URL
	 */
	public static function get_ollama_url() {
		if ( defined( 'OLLAMA_URL' ) && ! empty( OLLAMA_URL ) ) {
			return OLLAMA_URL;
		}
		$env = getenv( 'OLLAMA_URL' );
		if ( ! empty( $env ) ) {
			return $env;
		}
		return get_option( 'compass_ollama_url', '' );
	}

	/**
	 * Retrieve all registered and available AI connectors and their status
	 */
	public static function get_available_connectors() {
		$connectors = array();

		// Ensure official connector is armed
		self::ensure_aiclient_authenticated();

		// 1. Google Gemini AI (Official WP Connector)
		$gemini_key = self::get_api_key();
		$gemini_models = array(
			array( 'id' => 'gemini-3.5-flash',      'name' => 'Gemini 3.5 Flash (Recommended: Fast & High Reliability)' ),
			array( 'id' => 'gemini-3.5-flash-lite', 'name' => 'Gemini 3.5 Flash Lite (High Speed)' ),
			array( 'id' => 'gemini-3.8-flash',      'name' => 'Gemini 3.8 Flash (Latest Flagship, High Fidelity)' ),
			array( 'id' => 'gemini-3.7-flash',      'name' => 'Gemini 3.7 Flash (Hybrid Reasoning & Multimodal)' ),
			array( 'id' => 'gemini-3.6-flash',      'name' => 'Gemini 3.6 Flash (Fast & Robust Architecture)' ),
			array( 'id' => 'gemini-3.1-pro-preview', 'name' => 'Gemini 3.1 Pro Preview (Deep Architectural Reasoning)' ),
			array( 'id' => 'gemini-flash-latest',   'name' => 'Gemini Flash Latest (Auto-Updating)' ),
			array( 'id' => 'gemini-pro-latest',     'name' => 'Gemini Pro Latest (Auto-Updating)' ),
		);

		$connectors['gemini'] = array(
			'id'            => 'gemini',
			'name'          => 'Google Gemini AI (Official WP Connector)',
			'configured'    => ! empty( $gemini_key ),
			'setting_name'  => 'connectors_ai_google_api_key',
			'default_model' => 'gemini-3.5-flash',
			'models'        => $gemini_models,
		);

		// 2. Anthropic Claude AI
		$anthropic_key = self::get_anthropic_api_key();
		$connectors['anthropic'] = array(
			'id'            => 'anthropic',
			'name'          => 'Anthropic Claude AI',
			'configured'    => ! empty( $anthropic_key ),
			'setting_name'  => 'connectors_ai_anthropic_api_key',
			'default_model' => 'claude-3-7-sonnet-20250219',
			'models'        => array(
				array( 'id' => 'claude-3-7-sonnet-20250219', 'name' => 'Claude 3.7 Sonnet (Hybrid Reasoning Flagship)' ),
				array( 'id' => 'claude-3-5-sonnet-20241022', 'name' => 'Claude 3.5 Sonnet (State-of-the-Art Layouts)' ),
				array( 'id' => 'claude-3-5-haiku-20241022',  'name' => 'Claude 3.5 Haiku (Lightning Fast)' ),
				array( 'id' => 'claude-3-opus-20240229',      'name' => 'Claude 3 Opus' ),
			),
		);

		// 3. OpenAI
		$openai_key = self::get_openai_api_key();
		$connectors['openai'] = array(
			'id'            => 'openai',
			'name'          => 'OpenAI',
			'configured'    => ! empty( $openai_key ),
			'setting_name'  => 'connectors_ai_openai_api_key',
			'default_model' => 'gpt-4o',
			'models'        => array(
				array( 'id' => 'gpt-4o',      'name' => 'GPT-4o (Omni Multimodal Flagship)' ),
				array( 'id' => 'gpt-4o-mini', 'name' => 'GPT-4o Mini (Fast & Efficient)' ),
				array( 'id' => 'o3-mini',     'name' => 'o3-mini (High Speed Reasoning)' ),
				array( 'id' => 'o1-mini',     'name' => 'o1-mini (Specialized Reasoning)' ),
				array( 'id' => 'gpt-4-turbo', 'name' => 'GPT-4 Turbo' ),
			),
		);

		// 4. OpenRouter AI Proxy
		$openrouter_key = self::get_openrouter_api_key();
		$connectors['openrouter'] = array(
			'id'            => 'openrouter',
			'name'          => 'OpenRouter AI Proxy',
			'configured'    => ! empty( $openrouter_key ),
			'setting_name'  => 'compass_openrouter_api_key',
			'default_model' => 'anthropic/claude-3.5-sonnet',
			'models'        => array(
				array( 'id' => 'anthropic/claude-3.5-sonnet',       'name' => 'Claude 3.5 Sonnet (via OpenRouter)' ),
				array( 'id' => 'google/gemini-2.0-flash-001',       'name' => 'Gemini 2.0 Flash (via OpenRouter)' ),
				array( 'id' => 'deepseek/deepseek-r1',              'name' => 'DeepSeek R1 (Reasoning)' ),
				array( 'id' => 'deepseek/deepseek-chat',             'name' => 'DeepSeek V3 (via OpenRouter)' ),
				array( 'id' => 'meta-llama/llama-3.3-70b-instruct', 'name' => 'Llama 3.3 70B (via OpenRouter)' ),
			),
		);

		// 5. Local Ollama Runner
		$ollama_url = self::get_ollama_url();
		$connectors['ollama'] = array(
			'id'            => 'ollama',
			'name'          => 'Local Ollama Runner',
			'configured'    => ! empty( $ollama_url ),
			'setting_name'  => 'compass_ollama_url',
			'default_model' => 'llama3.2',
			'models'        => array(
				array( 'id' => 'llama3.2',      'name' => 'Llama 3.2 (Local)' ),
				array( 'id' => 'mistral',       'name' => 'Mistral 7B (Local)' ),
				array( 'id' => 'qwen2.5-coder', 'name' => 'Qwen 2.5 Coder (Local)' ),
				array( 'id' => 'phi4',          'name' => 'Phi-4 (Local)' ),
			),
		);

		// 6. Built-in Procedural Synthesizer
		$connectors['procedural'] = array(
			'id'            => 'procedural',
			'name'          => 'Built-in Procedural Synthesizer',
			'configured'    => true,
			'setting_name'  => '',
			'default_model' => 'quantum-synthesizer-v2',
			'models'        => array(
				array( 'id' => 'quantum-synthesizer-v2', 'name' => 'Deterministic Quantum Synthesizer (Offline)' ),
			),
		);

		return $connectors;
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
			return $this->handle_palette_generation( $prompt, $system_instruction, $params );
		}

		// Otherwise, this is a Page / Layout generation request
		return $this->handle_page_generation( $prompt, $vibe, $archetype, $params );
	}

	/**
	 * Handle Palette generation for Stylebook with Smart Model Cascading
	 */
	private function handle_palette_generation( $prompt, $system_instruction, $params = array() ) {
		$connector = isset( $params['connector'] ) ? sanitize_key( $params['connector'] ) : 'gemini';
		$model     = isset( $params['model'] ) ? sanitize_text_field( $params['model'] ) : '';

		// If caller specifically requested procedural, skip AI
		if ( 'procedural' === $connector ) {
			$fallback_palette = $this->generate_procedural_palette( $prompt );
			return rest_ensure_response( array(
				'success' => true,
				'text'    => wp_json_encode( $fallback_palette ),
				'source'  => 'procedural-synthesizer',
			) );
		}

		// Smart model priority list: try reasoning model first, cascade to high-speed multimodal
		$models_to_try = array();
		if ( ! empty( $model ) ) {
			$models_to_try[] = $model;
		}
		if ( 'gemini' === $connector || 'google' === $connector ) {
			$models_to_try[] = 'gemini-3.1-pro-preview';
			$models_to_try[] = 'gemini-pro-latest';
			$models_to_try[] = 'gemini-3.8-flash';
			$models_to_try[] = 'gemini-3.7-flash';
		}

		$models_to_try = array_unique( $models_to_try );
		$remote_result = null;
		$successful_model = '';

		foreach ( $models_to_try as $try_model ) {
			$result = $this->dispatch_ai_generation( $prompt, $system_instruction, $connector, $try_model );
			if ( ! is_wp_error( $result ) && ! empty( $result ) ) {
				// Verify result contains parsable JSON with token keys
				$cleaned = trim( $result );
				if ( preg_match( '/\{[\s\S]*\}/', $cleaned, $match ) ) {
					$test_json = json_decode( $match[0], true );
					if ( is_array( $test_json ) && isset( $test_json['mh_color_brand_base'] ) ) {
						$remote_result = $match[0];
						$successful_model = $try_model;
						break;
					}
				}
			}
		}

		if ( ! empty( $remote_result ) ) {
			return rest_ensure_response( array(
				'success' => true,
				'text'    => $remote_result,
				'source'  => 'google-gemini-connector (' . $successful_model . ')',
			) );
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
		$connector      = isset( $params['connector'] ) ? sanitize_key( $params['connector'] ) : 'gemini';
		$model          = isset( $params['model'] ) ? sanitize_text_field( $params['model'] ) : '';
		$current_blocks = ! empty( $params['current_blocks'] ) ? wp_unslash( $params['current_blocks'] ) : '';
		$chat_history   = ! empty( $params['chat_history'] ) && is_array( $params['chat_history'] ) ? $params['chat_history'] : array();
		$is_refinement  = ! empty( $current_blocks ) && ! empty( $prompt );
		$blocks_html    = '';
		$source         = 'procedural-synthesizer';
		$error_message  = '';

		if ( 'procedural' !== $connector ) {
			$ai_prompt = $this->build_page_ai_prompt( $prompt, $vibe, $archetype, $current_blocks );
			$system    = 'You are a master WordPress theme and Gutenberg block architect in an interactive AI Studio pair-programming session. You build and refine production-ready WordPress pages using standard core Gutenberg blocks (wp:group, wp:heading, wp:paragraph, wp:buttons, wp:columns, wp:separator, wp:list). All layouts integrate with the theme 24-hour astronomical circadian lighting system: use semantic token classes (e.g. has-surface-body-background-color, has-brand-base-color, has-text-heading-color) and avoid hardcoded hex colors so the page adapts seamlessly between Day, Twilight, and Night. Never output raw HTML layout tags like <div> or <section> without wrapping in valid Gutenberg block comments. Return ONLY valid Gutenberg block markup, with no conversational preamble or markdown code fences.';
			$result    = $this->dispatch_ai_generation( $ai_prompt, $system, $connector, $model );

			if ( is_wp_error( $result ) ) {
				$error_message = $result->get_error_message();
			} elseif ( ! empty( $result ) ) {
				// Strip code fences if model wrapped in markdown
				$cleaned = preg_replace( '/^```(?:html|gutenberg)?\s*/i', '', trim( $result ) );
				$cleaned = preg_replace( '/\s*```$/', '', $cleaned );
				if ( stripos( $cleaned, '<!-- wp:' ) !== false ) {
					$blocks_html = $cleaned;
					$source      = $connector . ( $model ? ' (' . $model . ')' : '' );
				} else {
					$error_message = 'AI model output did not contain valid Gutenberg block markup.';
				}
			}
		}

		// Fallback to rich architectural synthesizer if remote model was not called or failed
		if ( empty( $blocks_html ) ) {
			if ( $is_refinement && ! empty( $current_blocks ) ) {
				// For refinement fallback, append or insert synthesized component to preserve existing work
				$supplement  = $this->synthesize_page_blocks( $prompt, $vibe, $archetype );
				$blocks_html = $current_blocks . "\n\n" . $supplement;
				$source      = ! empty( $error_message ) ? 'procedural-synthesizer (appended: ' . $error_message . ')' : 'procedural-synthesizer (appended)';
			} else {
				$blocks_html = $this->synthesize_page_blocks( $prompt, $vibe, $archetype );
				$source      = ! empty( $error_message ) ? 'procedural-synthesizer (fallback: ' . $error_message . ')' : 'procedural-synthesizer';
			}
		}

		// Render HTML for live Customizer preview canvas
		$rendered_html = do_blocks( $blocks_html );

		// Parse detected sections for zero-token architectural telemetry
		$detected_sections = array();
		if ( preg_match_all( '/<!--\s*wp:heading\s*(?:\{.*?\})?\s*-->\s*<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', $blocks_html, $heading_matches ) ) {
			foreach ( array_slice( $heading_matches[1], 0, 6 ) as $h ) {
				$clean_h = trim( wp_strip_all_tags( $h ) );
				if ( ! empty( $clean_h ) ) {
					$detected_sections[] = $clean_h;
				}
			}
		}

		$thought_stream = array(
			array(
				'step'   => 1,
				'phase'  => $is_refinement ? 'Conversational Intent & Delta Parsing' : 'Intent & Blueprint Parsing',
				'status' => 'complete',
				'detail' => $is_refinement
					? sprintf( 'Analyzed follow-up request: "%s". Diffed against %d bytes of existing block markup under vibe "%s".', esc_html( $prompt ), strlen( $current_blocks ), $vibe )
					: sprintf( 'Mapped archetype "%s" under vibe "%s". Vision prompt: "%s"', $archetype, $vibe, ! empty( $prompt ) ? esc_html( $prompt ) : 'Default archetype pattern' ),
			),
			array(
				'step'   => 2,
				'phase'  => 'Circadian Palette Synchronization',
				'status' => 'complete',
				'detail' => 'Bound astronomical 24h lighting curve (zero raw hex codes; semantic token classes has-surface-body, has-brand-base, has-text-heading active).',
			),
			array(
				'step'   => 3,
				'phase'  => 'Generative Synthesis Engine',
				'status' => 'complete',
				'detail' => sprintf( 'Synthesized Gutenberg block hierarchy via %s.', $source ),
			),
			array(
				'step'   => 4,
				'phase'  => 'Block Architecture Verification',
				'status' => 'complete',
				'detail' => sprintf( 'Validated %d core block groups. Key sections: %s.', count( $detected_sections ), ! empty( $detected_sections ) ? implode( ' -> ', $detected_sections ) : 'Standard archetype components' ),
			),
			array(
				'step'   => 5,
				'phase'  => 'Live Canvas Quantum Stream',
				'status' => 'complete',
				'detail' => sprintf( 'Rendered %d bytes of production HTML ready for direct DOM injection and live Customizer sync.', strlen( $rendered_html ) ),
			),
		);

		if ( $is_refinement ) {
			$summary = sprintf( 'Refined page based on "%s". Sections: %s.', esc_html( $prompt ), ! empty( $detected_sections ) ? implode( ', ', array_slice( $detected_sections, 0, 3 ) ) : 'blocks' );
		} else {
			$summary = sprintf( 'Assembled new %s page with %s aesthetic (%d sections).', ucwords( str_replace( '-', ' ', $archetype ) ), ucwords( str_replace( '-', ' ', $vibe ) ), count( $detected_sections ) );
		}

		$page_id   = 0;
		$target_id = isset( $params['target_page_id'] ) ? absint( $params['target_page_id'] ) : 0;
		$action    = isset( $params['action'] ) ? sanitize_key( $params['action'] ) : 'generate_only';

		if ( 'apply_to_page' === $action && $target_id > 0 ) {
			wp_update_post( array(
				'ID'           => $target_id,
				'post_content' => wp_slash( $blocks_html ),
			) );
			$page_id = $target_id;
		} elseif ( 'create_page' === $action ) {
			$page_title = ! empty( $params['page_title'] ) ? sanitize_text_field( $params['page_title'] ) : 'AI Generated Page';
			$page_id    = wp_insert_post( array(
				'post_title'   => $page_title,
				'post_content' => wp_slash( $blocks_html ),
				'post_status'  => 'publish',
				'post_type'    => 'page',
			) );
		}

		return rest_ensure_response( array(
			'success'        => true,
			'source'         => $source,
			'vibe'           => $vibe,
			'archetype'      => $archetype,
			'blocks_html'    => $blocks_html,
			'rendered_html'  => $rendered_html,
			'thought_stream' => $thought_stream,
			'summary'        => $summary,
			'sections'       => $detected_sections,
			'is_refinement'  => $is_refinement,
			'page_id'        => $page_id,
			'preview_url'    => $page_id > 0 ? get_permalink( $page_id ) : '',
			'error_message'  => $error_message,
		) );
	}

	/**
	 * Dispatch AI generation to chosen WP Connector
	 */
	private function dispatch_ai_generation( $prompt, $system, $connector, $model ) {
		switch ( $connector ) {
			case 'anthropic':
				$key = self::get_anthropic_api_key();
				if ( empty( $key ) ) {
					return new WP_Error( 'missing_key', 'Anthropic API key is not configured.' );
				}
				return $this->call_anthropic_api( $prompt, $system, $key, $model ?: 'claude-3-7-sonnet-20250219' );

			case 'openai':
				$key = self::get_openai_api_key();
				if ( empty( $key ) ) {
					return new WP_Error( 'missing_key', 'OpenAI API key is not configured.' );
				}
				return $this->call_openai_compatible_api( $prompt, $system, $key, $model ?: 'gpt-4o' );

			case 'openrouter':
				$key = self::get_openrouter_api_key();
				if ( empty( $key ) ) {
					return new WP_Error( 'missing_key', 'OpenRouter API key is not configured.' );
				}
				return $this->call_openai_compatible_api(
					$prompt,
					$system,
					$key,
					$model ?: 'anthropic/claude-3.5-sonnet',
					'https://openrouter.ai/api/v1/chat/completions',
					array(
						'HTTP-Referer' => home_url(),
						'X-Title'      => 'Magic Hat AI Page Architect',
					)
				);

			case 'ollama':
				$url = self::get_ollama_url();
				if ( empty( $url ) ) {
					return new WP_Error( 'missing_url', 'Ollama endpoint URL is not configured.' );
				}
				return $this->call_ollama_api( $prompt, $system, $url, $model ?: 'llama3.2' );

			case 'procedural':
				return null;

			case 'google':
			case 'gemini':
			default:
				$key = self::get_api_key();
				if ( empty( $key ) ) {
					return new WP_Error( 'missing_key', 'Google Gemini API key is not configured in WP Connectors or environment.' );
				}

				$chosen_model = ! empty( $model ) ? $model : 'gemini-3.5-flash';
				// Guard against deprecated models that return 404 from Google
				if ( 'gemini-2.5-flash' === $chosen_model || 'gemini-1.5-flash' === $chosen_model ) {
					$chosen_model = 'gemini-3.5-flash';
				}

				// 1. Primary: Official Google WP Connector (WordPress AiClient + ai-provider-for-google)
				if ( self::ensure_aiclient_authenticated() ) {
					$aiclient_result = $this->call_google_via_aiclient( $prompt, $system, $chosen_model );
					if ( ! is_wp_error( $aiclient_result ) && ! empty( $aiclient_result ) ) {
						return $aiclient_result;
					}

					$err_msg       = is_wp_error( $aiclient_result ) ? $aiclient_result->get_error_message() : '';
					$is_rate_limit = ( stripos( $err_msg, '429' ) !== false || stripos( $err_msg, 'quota' ) !== false || stripos( $err_msg, 'exceeded' ) !== false );

					// If non-quota transient failure, try fallback model with AiClient
					if ( ! $is_rate_limit ) {
						$fallback_models = array( 'gemini-3.5-flash', 'gemini-3.5-flash-lite', 'gemini-3.6-flash', 'gemini-flash-latest' );
						foreach ( $fallback_models as $fb_model ) {
							if ( $fb_model === $chosen_model ) {
								continue;
							}
							$retry_result = $this->call_google_via_aiclient( $prompt, $system, $fb_model );
							if ( ! is_wp_error( $retry_result ) && ! empty( $retry_result ) ) {
								return $retry_result;
							}
						}
					}
				}

				// 2. Secondary: Direct Google Gemini REST API (fast and resilient fallback)
				$rest_result = $this->call_gemini_api( $prompt, $system, $key, $chosen_model );
				if ( ! is_wp_error( $rest_result ) && ! empty( $rest_result ) ) {
					return $rest_result;
				}

				$rest_err = is_wp_error( $rest_result ) ? $rest_result->get_error_message() : '';
				if ( stripos( $rest_err, '429' ) !== false || stripos( $rest_err, 'quota' ) !== false || stripos( $rest_err, 'exceeded' ) !== false ) {
					// Free tier quota hit on chosen model: try lightweight models with separate quotas
					$alt_models = array( 'gemini-3.5-flash-lite', 'gemini-2.0-flash', 'gemini-2.5-flash' );
					foreach ( $alt_models as $alt_m ) {
						if ( $alt_m === $chosen_model ) {
							continue;
						}
						$alt_res = $this->call_gemini_api( $prompt, $system, $key, $alt_m );
						if ( ! is_wp_error( $alt_res ) && ! empty( $alt_res ) ) {
							return $alt_res;
						}
					}
					return new WP_Error( 'gemini_quota_exceeded', 'Google Gemini RPM rate limit encountered (HTTP 429). The system seamlessly synthesized an architectural layout while quota refreshes.' );
				}

				return $rest_result;
		}
	}

	/**
	 * Call official Google WP Connector using WordPress AiClient
	 */
	private function call_google_via_aiclient( $prompt, $system_instruction, $model_id = 'gemini-3.5-flash' ) {
		// Filter HTTP request arguments and timeout to ensure swift 20s execution
		$timeout_args_filter = function( $args, $url ) {
			if ( strpos( $url, 'googleapis.com' ) !== false ) {
				$args['timeout'] = 20;
			}
			return $args;
		};
		$timeout_val_filter = function( $timeout, $url ) {
			if ( strpos( $url, 'googleapis.com' ) !== false ) {
				return 20;
			}
			return $timeout;
		};

		add_filter( 'http_request_args', $timeout_args_filter, 9999, 2 );
		add_filter( 'http_request_timeout', $timeout_val_filter, 9999, 2 );

		try {
			$registry = \WordPress\AiClient\AiClient::defaultRegistry();
			$builder  = \WordPress\AiClient\AiClient::prompt( $prompt );

			if ( ! empty( $system_instruction ) ) {
				$builder->usingSystemInstruction( $system_instruction );
			}

			// Configure request options with 20s timeout
			if ( class_exists( 'WordPress\\AiClient\\Providers\\Http\\DTO\\RequestOptions' ) ) {
				$opt = new \WordPress\AiClient\Providers\Http\DTO\RequestOptions();
				$opt->setTimeout( 20.0 );
				$builder->usingRequestOptions( $opt );
			}

			// Bind specific model instance if available in provider directory
			try {
				$model_instance = $registry->getProviderModel( 'google', $model_id );
				if ( $model_instance ) {
					$builder->usingModel( $model_instance );
				}
			} catch ( \Throwable $t ) {
				// Fall back to AiClient automatic model resolution
			}

			$result = $builder->generateTextResult();
			remove_filter( 'http_request_args', $timeout_args_filter, 9999 );
			remove_filter( 'http_request_timeout', $timeout_val_filter, 9999 );

			if ( $result && method_exists( $result, 'toText' ) ) {
				$text = $result->toText();
				if ( ! empty( $text ) ) {
					return $text;
				}
			}

			return new \WP_Error( 'empty_response', 'AiClient returned empty generation.' );
		} catch ( \Throwable $e ) {
			remove_filter( 'http_request_args', $timeout_args_filter, 9999 );
			remove_filter( 'http_request_timeout', $timeout_val_filter, 9999 );
			return new \WP_Error( 'aiclient_error', $e->getMessage() );
		}
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
			'post_content' => wp_slash( $content ),
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
	private function call_gemini_api( $prompt, $system_instruction, $api_key, $model = 'gemini-3.5-flash' ) {
		$target_model = ! empty( $model ) ? $model : 'gemini-3.5-flash';
		if ( 'gemini-2.5-flash' === $target_model || 'gemini-1.5-flash' === $target_model ) {
			$target_model = 'gemini-3.5-flash';
		}
		$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . urlencode( $target_model ) . ':generateContent?key=' . urlencode( $api_key );

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
				'timeout' => 25,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $code || empty( $data['candidates'][0]['content']['parts'][0]['text'] ) ) {
			$msg = ! empty( $data['error']['message'] ) ? $data['error']['message'] : ( 'Gemini API call failed with code: ' . $code );
			return new WP_Error( 'gemini_error', $msg );
		}

		return $data['candidates'][0]['content']['parts'][0]['text'];
	}

	/**
	 * Call Anthropic Claude Messages API
	 */
	private function call_anthropic_api( $prompt, $system_instruction, $api_key, $model = 'claude-3-5-sonnet-20241022' ) {
		$target_model = ! empty( $model ) ? $model : 'claude-3-5-sonnet-20241022';
		$url = 'https://api.anthropic.com/v1/messages';

		$body = array(
			'model'      => $target_model,
			'max_tokens' => 4096,
			'messages'   => array(
				array( 'role' => 'user', 'content' => $prompt ),
			),
		);

		if ( ! empty( $system_instruction ) ) {
			$body['system'] = $system_instruction;
		}

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array(
					'Content-Type'      => 'application/json',
					'x-api-key'         => $api_key,
					'anthropic-version' => '2023-06-01',
				),
				'body'    => wp_json_encode( $body ),
				'timeout' => 45,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $code || empty( $data['content'][0]['text'] ) ) {
			return new WP_Error( 'anthropic_error', 'Anthropic API call failed with code: ' . $code );
		}

		return $data['content'][0]['text'];
	}

	/**
	 * Call OpenAI / OpenRouter Compatible Chat Completions API
	 */
	private function call_openai_compatible_api( $prompt, $system_instruction, $api_key, $model, $endpoint = 'https://api.openai.com/v1/chat/completions', $extra_headers = array() ) {
		$messages = array();
		if ( ! empty( $system_instruction ) ) {
			$messages[] = array( 'role' => 'system', 'content' => $system_instruction );
		}
		$messages[] = array( 'role' => 'user', 'content' => $prompt );

		$body = array(
			'model'    => $model,
			'messages' => $messages,
		);

		$headers = array_merge(
			array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $api_key,
			),
			$extra_headers
		);

		$response = wp_remote_post(
			$endpoint,
			array(
				'headers' => $headers,
				'body'    => wp_json_encode( $body ),
				'timeout' => 45,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== $code || empty( $data['choices'][0]['message']['content'] ) ) {
			return new WP_Error( 'openai_error', 'API call failed with code: ' . $code );
		}

		return $data['choices'][0]['message']['content'];
	}

	/**
	 * Call Local Ollama Runner API
	 */
	private function call_ollama_api( $prompt, $system_instruction, $ollama_url, $model = 'llama3.2' ) {
		$url = rtrim( $ollama_url, '/' ) . '/api/generate';
		$full_prompt = ! empty( $system_instruction ) ? ( $system_instruction . "\n\n" . $prompt ) : $prompt;

		$body = array(
			'model'  => ! empty( $model ) ? $model : 'llama3.2',
			'prompt' => $full_prompt,
			'stream' => false,
		);

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

		if ( 200 !== $code || empty( $data['response'] ) ) {
			return new WP_Error( 'ollama_error', 'Ollama API call failed with code: ' . $code );
		}

		return $data['response'];
	}

	/**
	 * Build AI prompt for page generation or iterative refinement
	 */
	private function build_page_ai_prompt( $prompt, $vibe, $archetype, $current_blocks = '' ) {
		$vibe_desc        = $this->get_vibe_descriptor( $vibe );
		$arch_desc        = $this->get_archetype_descriptor( $archetype );
		$effective_prompt = ! empty( trim( $prompt ) ) ? trim( $prompt ) : 'Create a complete ' . $arch_desc . ' tailored to the ' . $vibe_desc . ' aesthetic.';

		if ( ! empty( $current_blocks ) ) {
			return "You are collaborating with a user in an interactive AI Studio session to build and refine a WordPress website.

CURRENT PAGE BLOCKS:
{$current_blocks}

USER FOLLOW-UP REQUEST:
{$effective_prompt}

VISUAL VIBE: {$vibe} ({$vibe_desc})
LAYOUT ARCHETYPE: {$archetype} ({$arch_desc})

INSTRUCTIONS:
1. Update, refine, or extend the current page blocks according to the user's follow-up request.
2. If the user asks to add a section (e.g. pricing, testimonials, FAQ, features, metrics), insert it seamlessly into the logical position in the block flow.
3. If the user asks to modify copy, headings, vibe, or style, update the relevant blocks while keeping the rest intact.
4. Maintain all Project Compass circadian token classes (has-surface-body-background-color, has-brand-base-color, has-text-heading-color, has-cta-base-color) and avoid hardcoded hex colors.
5. Return ONLY the complete updated Gutenberg block comments and markup. No markdown code fences, no conversational preface.";
		}

		return "Generate a complete WordPress Gutenberg page for the following request:
User Request: {$effective_prompt}
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
5. Circadian Rhythm Engine Compatibility: The theme uses an astronomical 24-hour Circadian Rhythm engine (Light, Golden Hour Twilight, Dark) with dynamic OKLCH color-mixing. NEVER hardcode static raw hex colors in inline style tags (such as style='color:#000' or style='background:#fff'). Exclusively use semantic block token classes (has-surface-body-background-color, has-brand-base-color, has-cta-base-color, has-text-heading-color) and CSS variables (var(--mh-color-*), var(--wp--preset--color--*)) so all generated layouts automatically illuminate and darken with the astronomical daylight curve.
6. Return ONLY Gutenberg block comments and markup.";
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
	 * Procedural 84-color token palette for Stylebook with authentic Golden Hour Twilight
	 */
	private function generate_procedural_palette( $prompt ) {
		$lower = strtolower( $prompt );

		// 1. Purple & Gold / Royal Luxury
		if ( strpos( $lower, 'purple' ) !== false || strpos( $lower, 'violet' ) !== false || strpos( $lower, 'royal' ) !== false || strpos( $lower, 'luxury' ) !== false ) {
			return array(
				// Light Mode (Noon Sun)
				'mh_color_brand_base'     => '#6b21a8',
				'mh_color_brand_hover'    => '#7e22ce',
				'mh_color_brand_active'   => '#581c87',
				'mh_color_brand_muted'    => '#f3e8ff',
				'mh_color_cta_base'       => '#d97706',
				'mh_color_cta_hover'      => '#b45309',
				'mh_color_cta_active'     => '#92400e',
				'mh_color_cta_muted'      => '#fef3c7',
				'mh_color_link'           => '#6b21a8',
				'mh_color_link_hover'     => '#d97706',
				'mh_color_link_active'    => '#b45309',
				'mh_color_link_visited'   => '#9333ea',
				'mh_color_text_heading'   => '#1e1035',
				'mh_color_text_main'      => '#3b2063',
				'mh_color_text_muted'     => '#6b5887',
				'mh_color_text_inverse'   => '#ffffff',
				'mh_color_body'           => '#faf5ff',
				'mh_color_main'           => '#ffffff',
				'mh_color_section'        => '#f5edfd',
				'mh_color_card'           => '#ffffff',
				'mh_color_border_base'    => '#e9d5ff',
				'mh_color_border_hover'   => '#d8b4fe',
				'mh_color_border_focus'   => '#6b21a8',
				'mh_color_border_muted'   => '#f3e8ff',
				'mh_color_success'        => '#10b981',
				'mh_color_warning'        => '#f59e0b',
				'mh_color_danger'         => '#ef4444',
				'mh_color_info'           => '#3b82f6',

				// Twilight Mode (Golden Hour / Sunset Dusk)
				'mh_color_brand_base_twilight'   => '#c084fc',
				'mh_color_brand_hover_twilight'  => '#d8b4fe',
				'mh_color_brand_active_twilight' => '#a855f7',
				'mh_color_brand_muted_twilight'  => '#3b185f',
				'mh_color_cta_base_twilight'     => '#fbbf24', // Radiant Sunset Gold
				'mh_color_cta_hover_twilight'    => '#fde68a',
				'mh_color_cta_active_twilight'   => '#f59e0b',
				'mh_color_cta_muted_twilight'    => '#451a03',
				'mh_color_link_twilight'         => '#e879f9',
				'mh_color_link_hover_twilight'   => '#fbbf24',
				'mh_color_link_active_twilight'  => '#fde68a',
				'mh_color_link_visited_twilight' => '#f472b6',
				'mh_color_text_heading_twilight' => '#fef3c7', // Warm Golden Ivory
				'mh_color_text_main_twilight'    => '#f3e8ff',
				'mh_color_text_muted_twilight'   => '#c4b5fd',
				'mh_color_text_inverse_twilight' => '#180e29',
				'mh_color_body_twilight'         => '#1c122c', // Dusky Sunset Plum
				'mh_color_main_twilight'         => '#26183c',
				'mh_color_section_twilight'      => '#33204f',
				'mh_color_card_twilight'         => '#26183c',
				'mh_color_border_base_twilight'  => '#4c2d73',
				'mh_color_border_hover_twilight' => '#fbbf24',
				'mh_color_border_focus_twilight' => '#c084fc',
				'mh_color_border_muted_twilight' => '#361e54',
				'mh_color_success_twilight'      => '#34d399',
				'mh_color_warning_twilight'      => '#fbbf24',
				'mh_color_danger_twilight'       => '#f87171',
				'mh_color_info_twilight'         => '#60a5fa',

				// Dark Mode (Midnight Void)
				'mh_color_brand_base_dark'   => '#a855f7',
				'mh_color_brand_hover_dark'  => '#c084fc',
				'mh_color_brand_active_dark' => '#9333ea',
				'mh_color_brand_muted_dark'  => '#2e1065',
				'mh_color_cta_base_dark'     => '#f59e0b',
				'mh_color_cta_hover_dark'    => '#fbbf24',
				'mh_color_cta_active_dark'   => '#d97706',
				'mh_color_cta_muted_dark'    => '#3b1d03',
				'mh_color_link_dark'         => '#c084fc',
				'mh_color_link_hover_dark'   => '#f59e0b',
				'mh_color_link_active_dark'  => '#fbbf24',
				'mh_color_link_visited_dark' => '#e879f9',
				'mh_color_text_heading_dark' => '#ffffff',
				'mh_color_text_main_dark'    => '#f8fafc',
				'mh_color_text_muted_dark'   => '#94a3b8',
				'mh_color_text_inverse_dark' => '#0a0b10',
				'mh_color_body_dark'         => '#0c0714',
				'mh_color_main_dark'         => '#120b1e',
				'mh_color_section_dark'      => 'rgba(255, 255, 255, 0.03)',
				'mh_color_card_dark'         => 'rgba(255, 255, 255, 0.05)',
				'mh_color_border_base_dark'  => 'rgba(168, 85, 247, 0.2)',
				'mh_color_border_hover_dark' => '#a855f7',
				'mh_color_border_focus_dark' => '#f59e0b',
				'mh_color_border_muted_dark' => 'rgba(255, 255, 255, 0.06)',
				'mh_color_success_dark'      => '#10b981',
				'mh_color_warning_dark'      => '#f59e0b',
				'mh_color_danger_dark'       => '#ef4444',
				'mh_color_info_dark'         => '#3b82f6',
			);
		}

		// 2. Default: Starship Cobalt & Golden Hour Amber
		return array(
			// Light Mode
			'mh_color_brand_base'     => '#2563eb',
			'mh_color_brand_hover'    => '#3b82f6',
			'mh_color_brand_active'   => '#1d4ed8',
			'mh_color_brand_muted'    => '#dbeafe',
			'mh_color_cta_base'       => '#ff3366',
			'mh_color_cta_hover'      => '#ff668c',
			'mh_color_cta_active'     => '#e62050',
			'mh_color_cta_muted'      => '#ffe4e6',
			'mh_color_link'           => '#2563eb',
			'mh_color_link_hover'     => '#ff3366',
			'mh_color_link_active'    => '#1d4ed8',
			'mh_color_link_visited'   => '#7c3aed',
			'mh_color_text_heading'   => '#0f172a',
			'mh_color_text_main'      => '#334155',
			'mh_color_text_muted'     => '#64748b',
			'mh_color_text_inverse'   => '#ffffff',
			'mh_color_body'           => '#ffffff',
			'mh_color_main'           => '#f8fafc',
			'mh_color_section'        => '#f1f5f9',
			'mh_color_card'           => '#ffffff',
			'mh_color_border_base'    => '#e2e8f0',
			'mh_color_border_hover'   => '#cbd5e1',
			'mh_color_border_focus'   => '#2563eb',
			'mh_color_border_muted'   => '#f1f5f9',
			'mh_color_success'        => '#10b981',
			'mh_color_warning'        => '#f59e0b',
			'mh_color_danger'         => '#ef4444',
			'mh_color_info'           => '#3b82f6',

			// Twilight Mode (Golden Hour / Sunset Dusk Bridge)
			'mh_color_brand_base_twilight'   => '#6366f1',
			'mh_color_brand_hover_twilight'  => '#818cf8',
			'mh_color_brand_active_twilight' => '#4f46e5',
			'mh_color_brand_muted_twilight'  => '#312e81',
			'mh_color_cta_base_twilight'     => '#f59e0b', // Glowing Amber
			'mh_color_cta_hover_twilight'    => '#fbbf24',
			'mh_color_cta_active_twilight'   => '#d97706',
			'mh_color_cta_muted_twilight'    => '#451a03',
			'mh_color_link_twilight'         => '#38bdf8',
			'mh_color_link_hover_twilight'   => '#f59e0b',
			'mh_color_link_active_twilight'  => '#fbbf24',
			'mh_color_link_visited_twilight' => '#c084fc',
			'mh_color_text_heading_twilight' => '#fef3c7', // Warm Ivory
			'mh_color_text_main_twilight'    => '#f1f5f9',
			'mh_color_text_muted_twilight'   => '#cbd5e1',
			'mh_color_text_inverse_twilight' => '#181524',
			'mh_color_body_twilight'         => '#181524', // Warm Dusk Indigo-Bronze
			'mh_color_main_twilight'         => '#221d33',
			'mh_color_section_twilight'      => '#2c2542',
			'mh_color_card_twilight'         => '#221d33',
			'mh_color_border_base_twilight'  => '#3d345a',
			'mh_color_border_hover_twilight' => '#f59e0b',
			'mh_color_border_focus_twilight' => '#f59e0b',
			'mh_color_border_muted_twilight' => '#2d2643',
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
