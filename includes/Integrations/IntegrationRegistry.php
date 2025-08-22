<?php
declare( strict_types=1 );

namespace WooCommerce\Facebook\Integrations;

defined( 'ABSPATH' ) || exit;

/**
 * Centralized registry of all supported localization integrations.
 *
 * Provides discovery mechanism for available integrations and manages
 * instantiation of integration classes.
 */
class IntegrationRegistry {

	/**
	 * @var array<string, string> Map of integration keys to class names
	 */
	private static $localization_integrations = [
		'polylang' => Polylang::class,
		'wpml' => WPML::class,
		// Future integrations can be added here:
		// 'translatepress' => TranslatePress::class,
	];

	/**
	 * @var array<string, Abstract_Localization_Integration> Cached integration instances
	 */
	private static $integration_instances = [];

	/**
	 * Get all localization integration keys
	 *
	 * @return array<string> Array of integration keys
	 */
	public static function get_localization_integration_keys(): array {
		return array_keys( self::$localization_integrations );
	}

	/**
	 * Get a specific localization integration instance
	 *
	 * @param string $integration_key Integration key (e.g., 'polylang')
	 * @return Abstract_Localization_Integration|null Integration instance or null if not found
	 */
	public static function get_localization_integration( string $integration_key ): ?Abstract_Localization_Integration {
		if ( ! isset( self::$localization_integrations[ $integration_key ] ) ) {
			return null;
		}

		// Return cached instance if available
		if ( isset( self::$integration_instances[ $integration_key ] ) ) {
			return self::$integration_instances[ $integration_key ];
		}

		$class_name = self::$localization_integrations[ $integration_key ];

		// Verify class exists and extends the abstract base
		if ( ! class_exists( $class_name ) || ! is_subclass_of( $class_name, Abstract_Localization_Integration::class ) ) {
			return null;
		}

		// Create and cache the instance
		$instance = new $class_name();
		self::$integration_instances[ $integration_key ] = $instance;

		return $instance;
	}

	/**
	 * Get all localization integration instances
	 *
	 * @return array<string, Abstract_Localization_Integration> Array of integration instances keyed by integration key
	 */
	public static function get_all_localization_integrations(): array {
		$integrations = [];

		foreach ( self::get_localization_integration_keys() as $key ) {
			$integration = self::get_localization_integration( $key );
			if ( $integration ) {
				$integrations[ $key ] = $integration;
			}
		}

		return $integrations;
	}

	/**
	 * Get availability data for all localization integrations
	 *
	 * @return array<string, array> Array of integration availability data keyed by integration key
	 */
	public static function get_all_localization_availability_data(): array {
		$availability_data = [];

		foreach ( self::get_all_localization_integrations() as $key => $integration ) {
			$availability_data[ $key ] = self::get_integration_availability_data( $integration );
		}

		return $availability_data;
	}

	/**
	 * Get availability data for a specific integration
	 *
	 * @param Abstract_Localization_Integration $integration Integration instance
	 * @return array Integration availability data
	 */
	private static function get_integration_availability_data( Abstract_Localization_Integration $integration ): array {
		// Use the standardized method from the abstract base class
		return $integration->get_availability_data();
	}

	/**
	 * Register a new localization integration
	 *
	 * @param string $key Integration key
	 * @param string $class_name Integration class name
	 * @return bool True if registered successfully, false otherwise
	 */
	public static function register_localization_integration( string $key, string $class_name ): bool {
		// Verify class exists and extends the abstract base
		if ( ! class_exists( $class_name ) || ! is_subclass_of( $class_name, Abstract_Localization_Integration::class ) ) {
			return false;
		}

		self::$localization_integrations[ $key ] = $class_name;

		// Clear cached instance if it exists
		unset( self::$integration_instances[ $key ] );

		return true;
	}

	/**
	 * Clear all cached integration instances
	 *
	 * Useful for testing or when integration states might have changed
	 */
	public static function clear_cache(): void {
		self::$integration_instances = [];
	}
}
