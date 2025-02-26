<?php
/**
 * Class used for querying discount_codes.
 *
 * @package WP_Ultimo
 * @subpackage Database\Discount_Code
 * @since 2.0.0
 */

namespace WP_Ultimo\Database\Discount_Codes;

use WP_Ultimo\Database\Engine\Table;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Setup the "wu_discount_codes" database table
 *
 * @since 2.0.0
 */
final class Discount_Codes_Table extends Table {

	/**
	 * Table name
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $name = 'discount_codes';

	/**
	 * Is this table global?
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $global = true;

	/**
	 * Table current version
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $version = '2.0.1-revision.20230601';

	/**
	 * List of table upgrades.
	 *
	 * @var array
	 */
	protected $upgrades = array(
		'2.0.1-revision.20230601' => 20230601,
	);

	/**
	 * Discount_Code constructor.
	 *
	 * @access public
	 * @since  2.0.0
	 * @return void
	 */
	public function __construct() {

		parent::__construct();

	} // end __construct;

	/**
	 * Setup the database schema
	 *
	 * @access protected
	 * @since  2.0.0
	 * @return void
	 */
	protected function set_schema() {

		$this->schema = "id bigint(20) NOT NULL auto_increment,
			name tinytext NOT NULL DEFAULT '',
			code varchar(20) NOT NULL default '',
			description longtext NULL default '',
			uses int default '0',
			max_uses int,
			apply_to_renewals tinyint(4) default 0,
			type enum('percentage', 'absolute') NOT NULL default 'percentage',
			value decimal(13,4) default 0,
			setup_fee_type enum('percentage', 'absolute') NOT NULL default 'percentage',
			setup_fee_value decimal(13,4) default 0,
			active tinyint(4) default 1,
			date_start datetime NULL,
			date_expiration datetime NULL,
			date_created datetime NULL,
			date_modified datetime NULL,
			PRIMARY KEY (id)";

	} // end set_schema;

	/**
	 * Fixes the datetime columns to accept null.
	 *
	 * @since 2.1.2
	 * @return bool
	 */
	protected function __20230601() {

		$null_columns = array(
			'date_created',
			'date_modified',
		);

		foreach ($null_columns as $column) {

			$query = "ALTER TABLE {$this->table_name} MODIFY COLUMN `{$column}` datetime DEFAULT NULL;";

			$result = $this->get_db()->query($query);

			if (!$this->is_success($result)) {

				return false;

			} // end if;

		} // end foreach;

		return true;

	} // end __20230601;

} // end class Discount_Codes_Table;
