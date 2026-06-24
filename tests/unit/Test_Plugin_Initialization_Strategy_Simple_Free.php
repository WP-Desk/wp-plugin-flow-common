<?php

use WPDesk\Plugin\Flow\Initialization\Simple\SimpleFreeStrategy;

class Test_Plugin_Initialization_Strategy_Simple_Free extends \WP_Mock\Tools\TestCase {

	public function setUp(): void {
		WP_Mock::setUp();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_strategy_can_build() {
		$info = new \WPDesk_Plugin_Info();
		$info->set_class_name( Stub_Plugin::class );

		WP_Mock::userFunction( 'plugin_dir_url',
			[
				'return' => 'whatever',
			] );
		WP_Mock::userFunction( 'plugin_basename',
			[
				'return' => 'whatever',
			] );
        WP_Mock::userFunction( 'get_locale',
            [
                'return' => 'en_US',
            ] );

		$strategy = new SimpleFreeStrategy( $info );
		$this->assertInstanceOf( Stub_Plugin::class, $strategy->run_init( $info ), "Plugin should be actually built" );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_strategy_can_build_without_tracker() {
		$info = new \WPDesk_Plugin_Info();
		$info->set_class_name( Stub_Plugin::class );

		WP_Mock::userFunction( 'plugin_dir_url',
			[
				'return' => 'whatever',
			] );
		WP_Mock::userFunction( 'plugin_basename',
			[
				'return' => 'whatever',
			] );
		WP_Mock::expectFilterNotAdded(
			'wpdesk_tracker_instance',
			\WP_Mock\Functions::type( \Closure::class ),
			8,
			1
		);

		$strategy = new SimpleFreeStrategy( $info, false );
		$this->assertInstanceOf( Stub_Plugin::class, $strategy->run_init( $info ), "Plugin should be actually built" );
		WP_Mock::assertHooksAdded();
	}
}
