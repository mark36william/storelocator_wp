<?php
/**
 * Class used for querying domain mappings.
 *
 * @package WP_Ultimo
 * @subpackage Database\Domains
 * @since 2.0.0
 */

namespace WP_Ultimo\Database\Domains;

use WP_Ultimo\Database\Engine\Table;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Setup the "wu_domain_mapping" database table
 *
 * @since 2.0.0
 */
final class Domains_Table extends Table {

	/**
	 * Table name
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $name = 'domain_mappings';

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
	 * Domains constructor.
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
			blog_id bigint(20) NOT NULL,
			domain varchar(191) NOT NULL,
			active tinyint(4) default 1,
			primary_domain tinyint(4) default 0,
			secure tinyint(4) default 0,
			stage enum('checking-dns', 'checking-ssl-cert', 'done', 'failed', 'done-without-ssl') DEFAULT 'checking-dns',
			date_created datetime NULL,
			date_modified datetime NULL,
			PRIMARY KEY (id),
			KEY blog_id (blog_id,domain,active),
			KEY domain (domain)";

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

} // end class Domains_Table;
