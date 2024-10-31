<?php # -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * Plugin Name: Nixwood Grid for Elementor
 * Plugin URI:  nixwood.com
 * Description: Nixwood Grid for Elementor allows you to improve page structure designing experience through the fancy Grid.
 * Version:     1.0
 * Author:      Nixwood team
 * License:     GPL-2.0
 * Text Domain: nixwoodgrid
 */

namespace Nixwoodgrid;

require_once dirname(__FILE__) . '/vendor/autoload.php';

class NixwoodGridMain
{
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	public function is_compatible() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}
		
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return false;
		}

		return true;
	}
	
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Nixwood Grid for Elementor', 'nixwoodgrid' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'nixwoodgrid' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'nixwoodgrid' ),
			'<strong>' . esc_html__( 'Nixwood Grid for Elementor', 'nixwoodgrid' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'nixwoodgrid' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}
	
}

$load = new NixwoodGridMain();
if(!$load->is_compatible()) {
	do_action('admin_notice_missing_main_plugin');
} else {
	new SetUp;
}