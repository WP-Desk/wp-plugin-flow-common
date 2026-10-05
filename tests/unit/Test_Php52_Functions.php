<?php

use WP_Mock\Tools\TestCase;

require_once __DIR__ . '/../../src/php52-functions.php';

class Test_Php52_Functions extends TestCase {

	public function setUp(): void {
		WP_Mock::setUp();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_admin_notice_escapes_text_before_adding_markup() {
		$escaped_translation = 'Escaped translated notice';

		WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 1,
				'args'   => [
					'PHP version is older than 5.3 so no WP Desk plugins will work. Please contact your host and ask them to upgrade.',
					'wp-plugin-flow-common',
				],
				'return' => $escaped_translation,
			]
		);

		ob_start();
		wpdesk_plugin_flow_php52_admin_notice();
		$output = ob_get_clean();

		$this->assertSame(
			'<p><strong style="color: red;">Escaped translated notice</strong></p>',
			$output
		);
	}
}
