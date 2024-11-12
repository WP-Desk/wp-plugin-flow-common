<?php

use WPDesk\Plugin\Flow\Initialization\InitializationFactory;
use WPDesk\Plugin\Flow\Initialization\InitializationStrategy;
use WPDesk\Plugin\Flow\PluginBootstrap;


class Test_Plugin_Bootstrap extends \WP_Mock\Tools\TestCase {

	const WP_VERSION = 5.5;

	public function setUp(): void {
		WP_Mock::setUp();

		WP_Mock::userFunction( 'get_locale',
			[
				'return' => 'whatever',
			] );

		WP_Mock::userFunction( 'load_plugin_textdomain',
			[
				'return' => true,
			] );

		WP_Mock::userFunction( 'get_bloginfo',
			[
				'return' => self::WP_VERSION,
			] );
		WP_Mock::userFunction( 'plugin_basename',
			[
				'return' => 'whatever',
			] );
		WP_Mock::userFunction( 'plugins_url',
			[
				'return' => 'whatever',
			] );
        WP_Mock::userFunction( 'wp_using_ext_object_cache',
            [
                'return' => false,
            ] );

		! defined( 'WP_PLUGIN_DIR' ) && define( 'WP_PLUGIN_DIR', __DIR__ . '/../../Stub/' );
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_free_plugin_can_reach_build_phase() {
		$plugin_version           = '1.0.0';
		$plugin_release_timestamp = '2019-07-18 15:28';

		$plugin_name        = 'whatever';
		$plugin_class_name  = 'whatever';
		$plugin_text_domain = 'whatever';
		$product_id         = 'whatever';
		$plugin_file        = 'whatever';
		$plugin_dir         = 'whatever';
		$requirements       = [
			'php' => 5.6,
			'wp'  => self::WP_VERSION,
		];

		$plugin_build_factory = $this->createMock( InitializationFactory::class );

		/** @noinspection PhpParamsInspection */
		$bootstrap = new PluginBootstrap(
			$plugin_version,
			$plugin_release_timestamp,
			$plugin_name,
			$plugin_class_name,
			$plugin_text_domain,
			$plugin_dir,
			$plugin_file,
			$requirements,
			$product_id,
			$plugin_build_factory,
			[]
		);

		$plugin_build_factory->expects( $this->once() )
		                     ->method( 'create_initialization_strategy' )
		                     ->willReturn($this->createMock( InitializationStrategy::class));

		$bootstrap->run();
	}
}
