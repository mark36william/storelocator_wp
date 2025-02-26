<?php
/**
 * WP Ultimo Dashboard Admin Page.
 *
 * @package WP_Ultimo
 * @subpackage Admin_Pages
 * @since 2.0.24
 */

namespace WP_Ultimo\Admin_Pages;

// Exit if accessed directly
defined('ABSPATH') || exit;

use \WP_Ultimo\License;
use \WP_Ultimo\Installers\Migrator;
use \WP_Ultimo\Installers\Core_Installer;
use \WP_Ultimo\Installers\Default_Content_Installer;
use \WP_Ultimo\Logger;

/**
 * WP Ultimo Dashboard Admin Page.
 */
class Migration_Alert_Admin_Page extends Wizard_Admin_Page {

	/**
	 * Holds the ID for this page, this is also used as the page slug.
	 *
	 * @var string
	 */
	protected $id = 'wp-ultimo-migration-alert';

	/**
	 * Is this a top-level menu or a submenu?
	 *
	 * @since 1.8.2
	 * @var string
	 */
	protected $type = 'submenu';

	/**
	 * Is this a top-level menu or a submenu?
	 *
	 * @since 1.8.2
	 * @var string
	 */
	protected $parent = 'none';

	/**
	 * This page has no parent, so we need to highlight another sub-menu.
	 *
	 * @since 2.0.24
	 * @var string
	 */
	protected $highlight_menu_slug = 'wp-ultimo-settings';

	/**
	 * If this number is greater than 0, a badge with the number will be displayed alongside the menu title
	 *
	 * @since 1.8.2
	 * @var integer
	 */
	protected $badge_count = 0;

	/**
	 * Holds the admin panels where this page should be displayed, as well as which capability to require.
	 *
	 * To add a page to the regular admin (wp-admin/), use: 'admin_menu' => 'capability_here'
	 * To add a page to the network admin (wp-admin/network), use: 'network_admin_menu' => 'capability_here'
	 * To add a page to the user (wp-admin/user) admin, use: 'user_admin_menu' => 'capability_here'
	 *
	 * @since 2.0.24
	 * @var array
	 */
	protected $supported_panels = array(
		'network_admin_menu' => 'manage_network',
	);

	/**
	 * Overrides original construct method.
	 *
	 * We need to override the construct method to make sure
	 * we make the necessary changes to the Wizard page when it's
	 * being run for the first time.
	 *
	 * @since 2.0.24
	 * @return void
	 */
	public function __construct() {

		parent::__construct();

	} // end __construct;

	/**
	 * Returns the logo for the wizard.
	 *
	 * @since 2.0.24
	 * @return string
	 */
	public function get_logo() {

		return wu_get_asset('logo.png', 'img');

	} // end get_logo;

	/**
	 * Returns the title of the page.
	 *
	 * @since 2.0.24
	 * @return string Title of the page.
	 */
	public function get_title() {

		return sprintf(__('Migration', 'wp-ultimo'));

	} // end get_title;

	/**
	 * Returns the title of menu for this page.
	 *
	 * @since 2.0.24
	 * @return string Menu label of the page.
	 */
	public function get_menu_title() {

		return WP_Ultimo()->is_loaded() ? __('WP Ultimo Migration Alert', 'wp-ultimo') : __('WP Ultimo', 'wp-ultimo');

	} // end get_menu_title;

	/**
	 * Returns the sections for this Wizard.
	 *
	 * @since 2.0.24
	 * @return array
	 */
	public function get_sections() {

		return array(
			'alert' => array(
				'title'   => __('Alert!', 'wp-ultimo'),
				'view'    => array($this, 'section_alert'),
				'handler' => array($this, 'handle_proceed'),
			),
		);

	} // end get_sections;

	/**
	 * Displays the content of the final section.
	 *
	 * @since 2.0.24
	 * @return void
	 */
	public function section_alert() {

		wu_get_template('wizards/setup/alert', array(
			'screen' => get_current_screen(),
			'page'   => $this,
		));

	} // end section_alert;

	/**
	 * Handles the proceed action.
	 *
	 * @since 2.0.24
	 * @return void
	 */
	public function handle_proceed() {

		delete_network_option(null, 'wu_setup_finished');
		delete_network_option(null, 'wu_is_migration_done');

		wp_redirect(wu_network_admin_url('wp-ultimo-setup'));

		exit;

	} // end handle_proceed;

} // end class Migration_Alert_Admin_Page;
