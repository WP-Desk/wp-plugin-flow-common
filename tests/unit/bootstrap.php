<?php
/**
 * PHPUnit bootstrap file
 */

error_reporting( E_ALL ^ E_DEPRECATED );

require_once __DIR__ . '/../../vendor/autoload.php';

WP_Mock::setUsePatchwork( true );
WP_Mock::bootstrap();
