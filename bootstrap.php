<?php // phpcs:ignore

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use FluentC\Bootstrap_FluentC;


/**
 * Only use for get one context
 *
 * @since 2.0
 */
abstract class Context_FluentC {

	/**
	 * @static
	 * @since 2.0
	 * @var Bootstrap_FluentC|null
	 */
	protected static $context;

	/**
	 * Create context if not exist
	 *
	 * @static
	 * @return object
	 * @since 2.0
	 */
	public static function fluentc_get_context() {
		if ( null !== self::$context ) {
			return self::$context;
		}

		self::$context = new Bootstrap_FluentC();

		// If PHP > 5.6, it will be possible to autoload the classes without listing them.
		$services = array(
			'\FluentC\Services\Cache',
			'\FluentC\Services\URL',
			'\FluentC\Services\Frontpage',  
			'\FluentC\Services\Widget',
			'\FluentC\Services\Connect',
			
		);

		self::$context->set_services( $services );

        $actions = array(
			'FluentC\Actions\Insert',
			'FluentC\Actions\Links'

		);

		self::$context->set_actions( $actions );
		return self::$context;
	}
}


/**
 * Init plugin
 * @return void
 * @version 2.0.1
 * @since 2.0
 */
function fluentc_init() {
	// add filter to prevent load weglot if not needed.
	/*$cancel_init = apply_filters( 'weglot_cancel_init', false );

	if ( $cancel_init ) {
		return;
	}

	if ( ! function_exists( 'curl_version' ) || ! function_exists( 'curl_exec' ) ) {
		add_action( 'admin_notices', array( '\WeglotWP\Notices\Curl_Weglot', 'admin_notice' ) );
	}

	if ( ! function_exists( 'json_last_error' ) ) {
		add_action( 'admin_notices', array( '\WeglotWP\Notices\Json_Function_Weglot', 'admin_notice' ) );
	}

	load_plugin_textdomain( 'weglot', false, WEGLOT_DIR_LANGUAGES ); */
	Context_FluentC::fluentc_get_context()->init_plugin();
}
