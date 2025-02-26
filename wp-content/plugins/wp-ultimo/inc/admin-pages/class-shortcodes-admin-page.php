<?php
/**
 * WP Ultimo Shortcodes Admin Page.
 *
 * @package WP_Ultimo
 * @subpackage Admin_Pages
 * @since 2.0.24
 */

namespace WP_Ultimo\Admin_Pages;

use WP_Ultimo\UI\Base_Element;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WP Ultimo Shortcodes Admin Page.
 */
class Shortcodes_Admin_Page extends Base_Admin_Page {

	/**
	 * Holds the ID for this page, this is also used as the page slug.
	 *
	 * @var string
	 */
	protected $id = 'wp-ultimo-shortcodes';

	/**
	 * Is this a top-level menu or a submenu?
	 *
	 * @since 1.8.2
	 * @var string
	 */
	protected $type = 'submenu';

	/**
	 * If this is a submenu, we need a parent menu to attach this to
	 *
	 * @since 1.8.2
	 * @var string
	 */
	protected $parent = 'none';

	/**
	 * Allows us to highlight another menu page, if this page has no parent page at all.
	 *
	 * @since 2.0.0
	 * @var boolean
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
	 * @since 2.0.0
	 * @var array
	 */
	protected $supported_panels = array(
		'network_admin_menu' => 'manage_network',
	);

	/**
	 * Allow child classes to add further initializations.
	 *
	 * @since 1.8.2
	 * @return void
	 */
	public function init() {

		parent::init();

	} // end init;

	/**
	 * Returns the title of the page.
	 *
	 * @since 2.0.0
	 * @return string Title of the page.
	 */
	public function get_title() {

		return __('Available Shortcodes', 'wp-ultimo');

	} // end get_title;

	/**
	 * Returns the title of menu for this page.
	 *
	 * @since 2.0.0
	 * @return string Menu label of the page.
	 */
	public function get_menu_title() {

		return __('Available Shortcodes', 'wp-ultimo');

	} // end get_menu_title;

	/**
	 * Allows admins to rename the sub-menu (first item) for a top-level page.
	 *
	 * @since 2.0.0
	 * @return string False to use the title menu or string with sub-menu title.
	 */
	public function get_submenu_title() {

		return __('Dashboard', 'wp-ultimo');

	} // end get_submenu_title;

	/**
	 * Every child class should implement the output method to display the contents of the page.
	 *
	 * @since 1.8.2
	 * @return void
	 */
	public function output() {

		$screen = get_current_screen();

		wu_get_template('shortcodes/shortcodes', array(
			'screen' => $screen,
			'data'   => $this->get_data(),
		));

	} // end output;

	/**
	 * Get data for shortcodes
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_data() {

		$elements = Base_Element::get_public_elements();

		$data = array();

		foreach ($elements as $element) {

			$defaults = $element->defaults();

			$params = array_filter($element->fields(), function($el) {
				return $el['type'] !== 'note' && $el['type'] !== 'header';
			});

			foreach ($params as $key => $value) {

				$params[$key]['default'] = wu_get_isset($defaults, $key, '');

				$params[$key]['desc'] = !isset($value['desc']) ? '' : $params[$key]['desc'];

				switch ($value['type']) {
					case 'toggle':
						$params[$key]['options'] = '0 | 1';
						break;
					case 'select':
						$params[$key]['options'] = implode(' | ', array_keys(wu_get_isset($value, 'options', array())));
						break;
					case 'int':
						$params[$key]['options'] = __('integer', 'wp-ultimo');
						break;
					case 'number':
						$params[$key]['options'] = __('number', 'wp-ultimo');
						break;
					case 'text':
						$params[$key]['options'] = __('text', 'wp-ultimo');
						break;
					case 'textarea':
						$params[$key]['options'] = __('text', 'wp-ultimo');
						break;
					default:
						$params[$key]['options'] = $value['type'];
						break;
				} // end switch;

			} // end foreach;

			$id = $element->get_id();

			if (substr($id, 0, strlen('wp-ultimo/')) === 'wp-ultimo/') {

				$id = substr($element->get_id(), strlen('wp-ultimo/'));

			} // end if;

			$data[] = array(
				'generator_form_url' => wu_get_form_url("shortcode_{$id}"),
				'title'              => $element->get_title(),
				'shortcode'          => $element->get_shortcode_id(),
				'description'        => $element->get_description(),
				'params'             => $params,
			);

		} // end foreach;

		return $data;

	} // end get_data;

} // end class Shortcodes_Admin_Page;
