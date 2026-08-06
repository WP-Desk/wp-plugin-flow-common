<?php


namespace WPDesk\Plugin\Flow\Initialization\Simple;

use WPDesk\Tracker\OptInOptOut;

/**
 * Trait helps with tracker initialization
 *
 * @package WPDesk\Plugin\Flow\Initialization\Simple\
 */
trait TrackerInstanceAsFilterTrait {
	/** @var \WPDesk_Tracker_Interface[] */
	private static $tracker_instances = [];

	/** @var string|null */
	private $tracker_bucket;

	/**
	 * Returns filter action name for tracker instance
	 *
	 * @return string
	 */
	private function get_tracker_action_name() {
		return 'wpdesk_tracker_instance';
	}

	/**
	 * Returns version of the tracker. Inc when trackker is changed and should be instantiated fist.
	 *
	 * @return int
	 */
	private function get_tracker_version() {
		return 2;
	}

	/**
	 * @return \WPDesk_Tracker_Interface
	 */
	private function get_tracker_instance() {
		return apply_filters( $this->get_bucket_tracker_action_name(), null );
	}

	private function get_bucket_tracker_action_name() {
		return $this->get_tracker_action_name() . '/' . $this->get_tracker_bucket();
	}

	private function get_tracker_bucket() {
		if ( null !== $this->tracker_bucket ) {
			return $this->tracker_bucket;
		}

		$plugin_data = get_file_data(
			$this->plugin_info->get_plugin_file_name(),
			[ 'Author' => 'Author' ]
		);
		$bucket      = sanitize_key( $plugin_data['Author'] ?? '' );
		$bucket      = apply_filters( 'wpdesk/tracker/bucket/' . $this->plugin_info->get_plugin_slug(), $bucket );

		$this->tracker_bucket = sanitize_key( $bucket ) ?: 'wpdesk';

		return $this->tracker_bucket;
	}

	/**
	 * Prepare tracker to be instantiated using wpdesk_tracker_instance filter
	 *
	 * @return void|\WPDesk_Tracker
	 */
	private function prepare_tracker_action() {
		class_exists( \WPDesk_Tracker_Factory::class ); //autoload this class

		$bucket = $this->get_tracker_bucket();
		add_filter( $this->get_tracker_action_name() . '/' . $bucket, function ( $tracker_instance ) use ( $bucket ) {
			if ( is_object( $tracker_instance ) ) {
				return $tracker_instance;
			}
			if ( isset( self::$tracker_instances[ $bucket ] ) ) {
				return self::$tracker_instances[ $bucket ];
			}
			if ( apply_filters( 'wpdesk_can_start_tracker', true, $this->plugin_info ) ) {
				$tracker_factory                    = new \WPDesk_Tracker_Factory_Prefixed();
				self::$tracker_instances[ $bucket ] = $tracker_factory->create_tracker(
					basename( $this->plugin_info->get_plugin_file_name() ),
					$bucket
				);

				do_action( 'wpdesk_tracker_started', self::$tracker_instances[ $bucket ], $this->plugin_info );

				return self::$tracker_instances[ $bucket ];
			}
		}, 10 - $this->get_tracker_version() );

		add_filter( $this->get_tracker_action_name(), function ( $tracker_instance ) {
			return is_object( $tracker_instance ) ? $tracker_instance : $this->get_tracker_instance();
		}, 10 - $this->get_tracker_version() );
	}

	private function register_tracker_ui_extensions() {
		$shops    = $this->plugin_info->get_plugin_shops();
		$shop_url = $shops[ get_locale() ] ?? ( $shops['default'] ?? 'https://wpdesk.net' );
		$tracker_ui = new OptInOptOut(
			$this->plugin_info->get_plugin_file_name(),
			$this->plugin_info->get_plugin_slug(),
			$shop_url,
			$this->plugin_info->get_plugin_name()
		);
		$tracker_ui->create_objects();
		$tracker_ui->hooks();
  }
}
