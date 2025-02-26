<?php
/**
 * Base Custom Database Table Query Class.
 */

namespace WP_Ultimo\Database\Engine;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * The base class that all other database base classes extend.
 *
 * This class attempts to provide some universal immutability to all other
 * classes that extend it, starting with a magic getter, but likely expanding
 * into a magic call handler and others.
 *
 * @since 1.0.0
 */
class Query extends \WP_Ultimo\Dependencies\BerlinDB\Database\Query {

	/**
	 * The prefix for the custom table.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $prefix = 'wu';

	/**
	 * If we should use a global cache group.
	 *
	 * @since 2.1.2
	 * @var bool
	 */
	protected $global_cache = false;

	/**
	 * Keep track of the global cache groups we've added.
	 * This is to prevent adding the same group multiple times.
	 *
	 * @since 2.1.2
	 * @var array
	 */
	protected static $added_globals = array();

	/**
	 * The class constructor
	 *
	 * @since 2.1.2
	 * @param string|array $query Optional. An array or string of Query parameters.
	 * @return void
	 */
	public function __construct($query = array()) {

		$cache_group = $this->apply_prefix($this->cache_group, '-');

		if ($this->global_cache && !in_array($cache_group, self::$added_globals, true)) {

			wp_cache_add_global_groups(array($cache_group));

			self::$added_globals[] = $cache_group;

		} // end if;

		parent::__construct($query);
		
	} // end __construct;

	/**
	 * Get the plural name.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_plural_name() {

		return $this->item_name_plural;

	} // end get_plural_name;

} // end class Query;
