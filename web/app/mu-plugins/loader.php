<?php
/*
Plugin Name: WP Skeleton mu-plugins Loader
Description: Loads the mu-plugins required to run the site.
Author: Udit Desai
Author URI: https://github.com/desaiuditd
Version: 1.0
*/

if ( ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) ) {
	return;
}

$wps_mu_plugins = [
	/*
	 * Example:
	 * 'plugin/plugin.php',
	 */
];

add_action(
	'pre_current_active_plugins',
	function () use ( $wps_mu_plugins ) {
		global $plugins, $wp_list_table;

		// Add our own mu-plugins to the page
		foreach ( $wps_mu_plugins as $plugin_file ) {
			$plugin_data = get_plugin_data( WPMU_PLUGIN_DIR . "/$plugin_file", false, false ); // Do not apply markup/translate as it'll be cached.

			if ( empty( $plugin_data['Name'] ) ) {
				$plugin_data['Name'] = $plugin_file;
			}

			// @codingStandardsIgnoreLine WordPress.Variables.GlobalVariables.OverrideProhibited
			$plugins['mustuse'][ $plugin_file ] = $plugin_data;
		}

		// Recount totals
		// @codingStandardsIgnoreLine WordPress.Variables.GlobalVariables.OverrideProhibited
		$GLOBALS['totals']['mustuse'] = count( $plugins['mustuse'] );

		// Only apply the rest if we're actually looking at the page
		if ( 'mustuse' !== $GLOBALS['status'] ) {
			return;
		}

		// Reset the list table's data
		$wp_list_table->items = $plugins['mustuse'];
		foreach ( $wp_list_table->items as $plugin_file => $plugin_data ) {
			$wp_list_table->items[ $plugin_file ] = _get_plugin_data_markup_translate( $plugin_file, $plugin_data, false, true );
		}

		$total_this_page = $GLOBALS['totals']['mustuse'];

		if ( $GLOBALS['orderby'] ) {
			uasort( $wp_list_table->items, [ $wp_list_table, '_order_callback' ] );
		}

		// Force showing all plugins
		// See https://core.trac.wordpress.org/ticket/27110
		$plugins_per_page = $total_this_page;

		$wp_list_table->set_pagination_args(
			[
				'total_items' => $total_this_page,
				'per_page'    => $plugins_per_page,
			]
		);
	}
);

add_action(
	'network_admin_plugin_action_links',
	function ( $actions, $plugin_file, $plugin_data, $context ) use ( $wps_mu_plugins ) {
		if ( 'mustuse' !== $context || ! in_array( $plugin_file, $wps_mu_plugins, true ) ) {
			return;
		}

		$actions[] = sprintf( '<span style="color:#333">File: <code>%s</code></span>', $plugin_file );

		return $actions;
	},
	10,
	4
);

foreach ( $wps_mu_plugins as $file ) {
	require_once WPMU_PLUGIN_DIR . '/' . $file;
}
unset( $file );
