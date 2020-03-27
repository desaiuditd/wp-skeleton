<?php
/**
 * Plugin Name: Example mu-plugin
 * Description: Example mu-plugin for wp-skeleton to add a css file. Remove this plugin once we add actual plugin.
 * Version:     1.0.0
 * Author:      Udit Desai
 * Author URI:  https://github.com/desaiuditd
 *
 * This plugin is developed in-house.
 *
 * @package ThinkPrint Example mu-plugin
 */

 add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );

function enqueue_assets() {
	wp_enqueue_script( 'wps-example-mu-plugin', trailingslashit( TP_ASSETS_URL ) . 'wps-example-mu-plugin.js', [], floor( microtime( true ) ), true );

	if ( SCRIPT_DEBUG ) {
		return;
	}

	wp_enqueue_style( 'wps-example-mu-plugin', trailingslashit( TP_ASSETS_URL ) . '/wps-example-mu-plugin.css', [], floor( microtime( true ) ) );
}
