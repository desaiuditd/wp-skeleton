<?php
/**
 * Plugin Name: Bedrock Autoloader
 * Plugin URI: https://github.com/roots/bedrock/
 * Description: An autoloader that enables standard plugins to be required just like must-use plugins. The autoloaded plugins are included during mu-plugin loading. An asterisk (*) next to the name of the plugin designates the plugins that have been autoloaded.
 * Version: 1.0.0
 * Author: Roots
 * Author URI: https://roots.io/
 * License: MIT License
 */

namespace Roots\Bedrock;

if ( ! is_blog_installed() ) {
	return;
}

/**
 * Class Autoloader
 * @package Roots\Bedrock
 * @author Roots
 * @link https://roots.io/
 */
class Autoloader {
	/** @var static Singleton instance */
	private static $instance;

	/** @var array Store Autoloader cache and site option */
	private $cache;

	/** @var array Auto-loaded plugins */
	private $auto_plugins;

	/** @var array Auto-loaded mu-plugins */
	private $mu_plugins;

	/** @var int Number of plugins */
	private $count;

	/** @var array Newly activated plugins */
	private $activated;

	/** @var string Relative path to the mu-plugins dir */
	private $relative_path;

	/**
	 * Create singleton, populate vars, and set WordPress hooks
	 */
	public function __construct() {
		if ( isset( self::$instance ) ) {
			return;
		}

		self::$instance = $this;

		$this->relative_path = '/../' . basename( __DIR__ );

		if ( is_admin() ) {
			add_filter( 'show_advanced_plugins', [ $this, 'show_in_admin' ], 0, 2 );
		}

		$this->load_plugins();
	}

	/**
	* Run some checks then autoload our plugins.
	*/
	public function load_plugins() {
		$this->check_cache();
		$this->validate_plugins();
		$this->count_plugins();

		array_map(
			static function () {
				include_once WPMU_PLUGIN_DIR . '/' . func_get_args()[0];
			},
			array_keys( $this->cache['plugins'] )
		);

		$this->plugin_hooks();
	}

	/**
	 * Filter show_advanced_plugins to display the auto-loaded plugins.
	 * @param $show bool Whether to show the advanced plugins for the specified plugin type.
	 * @param $type string The plugin type, i.e., `mustuse` or `dropins`
	 * @return bool We return `false` to prevent WordPress from overriding our work
	 * {@internal We add the plugin details ourselves, so we return false to disable the filter.}
	 */
	public function show_in_admin( $show, $type ) {
		$screen  = get_current_screen();
		$current = is_multisite() ? 'plugins-network' : 'plugins';

		if ( $screen->base !== $current || 'mustuse' !== $type || ! current_user_can( 'activate_plugins' ) ) {
			return $show;
		}

		$this->update_cache();

		$this->auto_plugins = array_map(
			function ( $auto_plugin ) {
				$auto_plugin['Name'] .= ' *';
				return $auto_plugin;
			},
			$this->auto_plugins
		);

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['plugins']['mustuse'] = array_unique( array_merge( $this->auto_plugins, $this->mu_plugins ), SORT_REGULAR );

		return false;
	}

	/**
	 * This sets the cache or calls for an update
	 */
	private function check_cache() {
		$cache = get_site_option( 'bedrock_autoloader' );

		if ( false === $cache || ( isset( $cache['plugins'], $cache['count'] ) && count( $cache['plugins'] ) !== $cache['count'] ) ) {
			$this->update_cache();
			return;
		}

		$this->cache = $cache;
	}

	/**
	 * Get the plugins and mu-plugins from the mu-plugin path and remove duplicates.
	 * Check cache against current plugins for newly activated plugins.
	 * After that, we can update the cache.
	 */
	private function update_cache() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$this->auto_plugins = get_plugins( $this->relative_path );
		$this->mu_plugins   = get_mu_plugins();
		$plugins            = array_diff_key( $this->auto_plugins, $this->mu_plugins );
		$rebuild            = ! is_array( $this->cache['plugins'] );
		$this->activated    = $rebuild ? $plugins : array_diff_key( $plugins, $this->cache['plugins'] );
		$this->cache        = [
			'plugins' => $plugins,
			'count'   => $this->count_plugins(),
		];

		update_site_option( 'bedrock_autoloader', $this->cache );
	}

	/**
	 * This accounts for the plugin hooks that would run if the plugins were
	 * loaded as usual. Plugins are removed by deletion, so there's no way
	 * to deactivate or uninstall.
	 */
	private function plugin_hooks() {
		if ( ! is_array( $this->activated ) ) {
			return;
		}

		foreach ( array_keys( $this->activated ) as $plugin_file ) {
			do_action( 'activate_' . $plugin_file );
		}
	}

	/**
	 * Check that the plugin file exists, if it doesn't update the cache.
	 */
	private function validate_plugins() {
		foreach ( array_keys( $this->cache['plugins'] ) as $plugin_file ) {
			if ( ! file_exists( WPMU_PLUGIN_DIR . '/' . $plugin_file ) ) {
				$this->update_cache();
				break;
			}
		}
	}

	/**
	 * Count the number of auto-loaded plugins.
	 *
	 * Count our plugins (but only once) by counting the top level folders in the
	 * mu-plugins dir. If it's more or less than last time, update the cache.
	 *
	 * @return int Number of auto-loaded plugins.
	 */
	private function count_plugins() {
		if ( isset( $this->count ) ) {
			return $this->count;
		}

		$count = count( glob( WPMU_PLUGIN_DIR . '/*/', GLOB_ONLYDIR | GLOB_NOSORT ) );

		if ( ! isset( $this->cache['count'] ) || $count !== $this->cache['count'] ) {
			$this->count = $count;
			$this->update_cache();
		}

		return $this->count;
	}
}

new Autoloader();
