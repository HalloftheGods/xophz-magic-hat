<?php
/**
 * Magic Hat AI credentials, resolved through the WordPress core Connectors API.
 *
 * Every key the AI Page Architect uses (Google, Anthropic, OpenAI, OpenRouter, Ollama endpoint) is
 * read here and nowhere else. Core exposes no public function that returns a key's value, so
 * get_key() follows the same precedence as core's _wp_connectors_get_api_key_source():
 * environment variable, then PHP constant, then the database option. Every name comes from the
 * connector's own `authentication` definition, so the theme never hard-codes an option, constant
 * or environment variable name.
 *
 * Values are for server-side use only. Never echo one, log one, return one from a REST route or
 * put one in a URL.
 *
 * @package Xophz_Magic_Hat
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Magic_Hat_Connectors {

	/** Core AI provider connectors. */
	const GOOGLE = 'google';

	const ANTHROPIC = 'anthropic';

	const OPENAI = 'openai';

	/** Registered by this theme when no plugin has. */
	const OPENROUTER = 'openrouter_api_key';

	const OLLAMA_URL = 'ollama_url';

	/**
	 * Hook connector registration. Priority 20 runs after plugins that register at the default
	 * priority, so a connector another plugin already registers keeps that plugin's definition.
	 */
	public static function init() {
		add_action( 'wp_connectors_init', array( __CLASS__, 'register' ), 20 );
	}

	/**
	 * Register the connectors that core and the Compass plugins do not provide.
	 *
	 * @param object $registry WP_Connector_Registry.
	 */
	public static function register( $registry ) {
		if ( ! is_object( $registry ) || ! method_exists( $registry, 'register' ) ) {
			return;
		}

		self::register_once(
			$registry,
			self::OPENROUTER,
			'OpenRouter API Key',
			'API key for OpenRouter, an optional model proxy used by the Magic Hat AI Page Architect.',
			'compass_openrouter_api_key',
			'OPENROUTER_API_KEY',
			'https://openrouter.ai/keys'
		);
		self::register_once(
			$registry,
			self::OLLAMA_URL,
			'Ollama Endpoint URL',
			'Base URL of a local Ollama runner used by the Magic Hat AI Page Architect.',
			'compass_ollama_url',
			'OLLAMA_URL',
			'https://ollama.com/'
		);
	}

	/**
	 * The credential for a registered connector, or '' when it is unregistered or unset.
	 *
	 * @param string $connector_id Connector id, e.g. 'google' or 'anthropic'.
	 * @return string
	 */
	public static function get_key( $connector_id ) {
		$is_available = function_exists( 'wp_get_connector' ) && function_exists( 'wp_is_connector_registered' );
		if ( ! $is_available || ! wp_is_connector_registered( $connector_id ) ) {
			return '';
		}

		$connector = wp_get_connector( $connector_id );
		$auth      = is_array( $connector ) ? ( $connector['authentication'] ?? array() ) : array();

		$from_env = self::from_env( (string) ( $auth['env_var_name'] ?? '' ) );
		if ( '' !== $from_env ) {
			return $from_env;
		}

		$from_constant = self::from_constant( (string) ( $auth['constant_name'] ?? '' ) );
		if ( '' !== $from_constant ) {
			return $from_constant;
		}

		return self::from_option( (string) ( $auth['setting_name'] ?? '' ) );
	}

	private static function register_once( $registry, $id, $name, $description, $setting_name, $env_name, $credentials_url ) {
		if ( function_exists( 'wp_is_connector_registered' ) && wp_is_connector_registered( $id ) ) {
			return;
		}

		$registry->register(
			$id,
			array(
				'name'           => $name,
				'description'    => $description,
				'type'           => 'api_key',
				'authentication' => array(
					'method'          => 'api_key',
					'setting_name'    => $setting_name,
					'constant_name'   => $env_name,
					'env_var_name'    => $env_name,
					'credentials_url' => $credentials_url,
				),
			)
		);
	}

	private static function from_env( $env_var_name ) {
		return '' === $env_var_name ? '' : self::as_string( getenv( $env_var_name ) );
	}

	private static function from_constant( $constant_name ) {
		$is_defined = '' !== $constant_name && defined( $constant_name );
		return $is_defined ? self::as_string( constant( $constant_name ) ) : '';
	}

	private static function from_option( $setting_name ) {
		return '' === $setting_name ? '' : self::as_string( get_option( $setting_name, '' ) );
	}

	private static function as_string( $value ) {
		return is_scalar( $value ) ? trim( (string) $value ) : '';
	}
}
