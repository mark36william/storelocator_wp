<?php
/**
 * WP Ultimo overview collector.
 *
 * @package query-monitor
 * @since 2.0.11
 */

namespace WP_Ultimo\Development\Query_Monitor\Collectors;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Every QM Panel needs a collector.
 *
 * @since 2.0.11
 */
class Collector_Overview extends \QM_Collector {

	/**
	 * Sets the id of the collector.
	 *
	 * @since 2.0.11
	 * @var string
	 */
	public $id = 'wp-ultimo';

	/**
	 * Set-up routines.
	 *
	 * @since 2.0.11
	 * @return void
	 */
	public function set_up() {

		parent::set_up();

	} // end set_up;

	/**
	 * Tear down routines.
	 *
	 * @since 2.0.11
	 * @return void
	 */
	public function tear_down() {

		parent::tear_down();

	} // end tear_down;

	/**
	 * Process the collection.
	 *
	 * Here, we just need to add items to the
	 * data property array.
	 *
	 * @since 2.0.11
	 * @return void
	 */
	public function process() {

		$this->data = $_REQUEST;

	} // end process;

} // end class Collector_Overview;
