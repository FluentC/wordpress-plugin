<?php 

namespace FluentC;
use FluentC\Models\Hooks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;

/**
 * Init plugin
 *
 * @since 2.0
 */
class Bootstrap_FluentC {
	/**
	 * List actions WordPress
	 * @since 2.0
	 * @var array
	 */
	protected $actions = array();

	/**
	 * List class services
	 * @since 1.2
	 * @var array
	 */
	protected $services = array();

	/**
	 * Set actions
	 *
	 * @since 1.2
	 * @param array $actions
	 * @return BootStrap_FluentC
	 */
	public function set_actions( $actions ) {
		$this->actions = $actions;
		return $this;
	}

	/**
	 * Set services
	 * @since 1.2
	 * @param array $services
	 * @return BootStrap_FluentC
	 */
	public function set_services( $services ) {
		foreach ( $services as $service ) {
			$this->set_service( $service );
		}
		return $this;
	}

	/**
	 * Set a service
	 * @since 1.2
	 * @param string $service
	 * @return BootStrap_FluentC
	 */
	public function set_service( $service ) {
		$name = explode( '\\', $service );
		end( $name );
		$key                             = key( $name );
		$this->services[ $name[ $key ] ] = $service;
		return $this;
	}

	/**
	 * Get one service by classname
	 * @param string $name
	 * @return object
	 * @throws Exception
	 * @since 1.2
	 */
	public function get_service( $name ) {
		if ( ! array_key_exists( $name, $this->services ) ) {
			throw new Exception( 'Service : ' . $name . ' not exist' );
		}

		if ( is_string( $this->services[ $name ] ) ) {
			$this->services[ $name ] = new $this->services[ $name ]();
		}

		return $this->services[ $name ];
	}

	/**
	 * Init plugin
	 * @since 1.2
	 * @return void
	 */
	public function init_plugin() {
		foreach ( $this->actions as $action ) {
			$action = new $action();
			if ( $action instanceof Hooks ) {
				$action->hooks();
			}
		}
	}

	/**
	 * Activate plugin
	 * @since 1.2
	 * @return void
	 */
	public function activate_plugin() {
		foreach ( $this->actions as $action ) {
			$action = new $action();
			if ( ! method_exists( $action, 'activate' ) ) {
				continue;
			}

			$action->activate();
		}
	}

	/**
	 * Deactivate plugin
	 * @since 1.2
	 * @return void
	 */
	public function deactivate_plugin() {
		foreach ( $this->actions as $action ) {
			$action = new $action();
			if ( ! method_exists( $action, 'deactivate' ) ) {
				continue;
			}

			$action->deactivate();
		}
	}
}
