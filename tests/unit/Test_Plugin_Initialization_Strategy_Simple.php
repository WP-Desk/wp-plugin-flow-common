<?php

use WPDesk\Plugin\Flow\Initialization\Simple\SimplePaidStrategy;

class Test_Plugin_Initialization_Strategy_Simple extends \WP_Mock\Tools\TestCase {

	const WP_VERSION = 5.5;

	public function setUp(): void {
		WP_Mock::setUp();
        $this->markTestSkipped('Move this test to paid plugin flow library!');
    }

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_strategy_can_build_front() {
		$info = new \WPDesk_Plugin_Info();
		$info->set_class_name( Stub_Plugin::class );

		WP_Mock::userFunction( 'plugin_dir_url',
			[
				'return' => 'whatever',
			] );

		WP_Mock::userFunction( 'is_admin',
			[
				'return' => false,
			] );

		WP_Mock::userFunction( 'get_option',
			[
				'return' => 'whatever',
			] );
		WP_Mock::userFunction( 'plugin_basename',
			[
				'return' => 'whatever',
			] );
		WP_Mock::userFunction( 'trailingslashit' )->andReturnArg( 0 );
		WP_Mock::userFunction( 'plugins_url' )->andReturnArg( 0 );

		$strategy = new SimplePaidStrategy( $info );
		$this->assertInstanceOf( Stub_Plugin::class, $strategy->run_init( $info ), "Plugin should be actually built" );
	}
}
