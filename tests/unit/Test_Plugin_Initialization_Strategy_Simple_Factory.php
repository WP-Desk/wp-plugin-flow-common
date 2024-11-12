<?php

use WPDesk\Plugin\Flow\Initialization\Simple\SimpleFactory;
use WPDesk\Plugin\Flow\Initialization\Simple\SimpleFreeStrategy;
use WPDesk\Plugin\Flow\Initialization\Simple\SimplePaidStrategy;


class Test_Plugin_Initialization_Strategy_Simple_Factory extends \WP_Mock\Tools\TestCase {

	public function setUp(): void {
		WP_Mock::setUp();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function test_free_plugin_can_create_free_strategy() {
		$stubInfo = new \WPDesk_Plugin_Info();
		$factory = new SimpleFactory(true);
		$strategy =  $factory->create_initialization_strategy($stubInfo);
		$this->assertInstanceOf(SimpleFreeStrategy::class, $strategy, 'Free strategy should be created');
	}

	public function test_free_plugin_can_create_standard_strategy() {
		$stubInfo = new \WPDesk_Plugin_Info();
		$factory = new SimpleFactory();
		$strategy =  $factory->create_initialization_strategy($stubInfo);
		$this->assertInstanceOf( SimplePaidStrategy::class, $strategy, 'Standard strategy should be created');
	}
}
