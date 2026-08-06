[![pipeline status](https://gitlab.com/wpdesk/library/wp-plugin-flow-common/badges/master/pipeline.svg)](https://gitlab.com/wpdesk/wp-plugin-flow-common/commits/master) 
Integration: [![coverage report](https://gitlab.com/wpdesk/library/wp-plugin-flow-common/badges/master/coverage.svg?job=integration+test+lastest+coverage)](https://gitlab.com/wpdesk/wp-plugin-flow-common/commits/master)
Unit: [![coverage report](https://gitlab.com/wpdesk/library/wp-plugin-flow-common/badges/master/coverage.svg?job=unit+test+lastest+coverage)](https://gitlab.com/wpdesk/wp-plugin-flow-common/commits/master)

# wp-plugin-flow-common

A small library for bootstrapping WordPress plugins built on WP Desk flow. It handles requirements checks, translations initialization, and startup strategy selection.

## Requirements

- PHP `>= 7.4`
- WordPress

## Installation

```bash
composer require wpdesk/wp-plugin-flow-common
```

## Usage

Prepare the basic plugin variables and include one of the bootstrap files:

```php
$plugin_version = '1.0.0';
$plugin_name = 'My Plugin';
$plugin_class_name = MyPlugin::class;
$plugin_text_domain = 'my-plugin';
$plugin_dir = __DIR__;
$plugin_file = __FILE__;
$requirements = [
	'php' => '7.4',
	'wp'  => '6.0',
];
$product_id = 'my-plugin';

require __DIR__ . '/vendor/wpdesk/wp-plugin-flow-common/src/plugin-init-php52.php';
```

For a free plugin use:

```php
require __DIR__ . '/vendor/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';
```

If you need a custom initialization strategy, define `$plugin_init_factory` before including the bootstrap file.

Usage trackers are shared between plugins with the same normalized `Author` header. The bucket can be overridden for an individual plugin:

```php
add_filter( 'wpdesk/tracker/bucket/my-plugin', function () {
	return 'my-company';
} );
```

## Tests

```bash
composer phpunit-unit-fast
composer phpunit-integration-fast
```
