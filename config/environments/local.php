<?php
/**
 * Configuration overrides for WP_ENV === 'local'
 */

use Roots\WPConfig\Config;

Config::define( 'SAVEQUERIES', true );
Config::define( 'WP_DEBUG', true );
Config::define( 'WP_DEBUG_LOG', true );
Config::define( 'WP_DEBUG_DISPLAY', true );
Config::define( 'WP_DISABLE_FATAL_ERROR_HANDLER', true );
Config::define( 'SCRIPT_DEBUG', true );

ini_set( 'display_errors', '1' ); // phpcs:ignore WordPress.PHP.IniSet.display_errors_Blacklisted

// Enable plugin and theme updates and installation from the admin
Config::define( 'DISALLOW_FILE_MODS', false );

// wp-skeleton Constants.
Config::define( 'WPS_ASSETS_URL', 'https://assets-wp-skeleton.lndo.site' );
Config::define( 'WPS_PREFERRED_DOMAIN', 'wp-skeleton.lndo.site' );
