<?php

if ( ! function_exists( 'wpdesk_plugin_flow_php52_admin_notice' ) ) {
	/**
	 * Display an admin notice about an unsupported PHP version.
	 *
	 * @return void
	 */
	function wpdesk_plugin_flow_php52_admin_notice() {
		printf(
			'<p><strong style="color: red;">%s</strong></p>',
			esc_html__( 'PHP version is older than 5.3 so no WP Desk plugins will work. Please contact your host and ask them to upgrade.', 'wp-plugin-flow-common' )
		);
	}
}
