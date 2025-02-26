<?php
/**
 * Adds the My_Sites_Element UI to the Admin Panel.
 *
 * @package WP_Ultimo
 * @subpackage UI
 * @since 2.0.0
 */

namespace WP_Ultimo\UI;

use WP_Ultimo\UI\Base_Element;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Adds the Checkout Element UI to the Admin Panel.
 *
 * @since 2.0.0
 */
class My_Sites_Element extends Base_Element {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * The id of the element.
	 *
	 * Something simple, without prefixes, like 'checkout', or 'pricing-tables'.
	 *
	 * This is used to construct shortcodes by prefixing the id with 'wu_'
	 * e.g. an id checkout becomes the shortcode 'wu_checkout' and
	 * to generate the Gutenberg block by prefixing it with 'wp-ultimo/'
	 * e.g. checkout would become the block 'wp-ultimo/checkout'.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $id = 'my-sites';

	/**
	 * Controls if this is a public element to be used in pages/shortcodes by user.
	 *
	 * @since 2.0.24
	 * @var boolean
	 */
	protected $public = true;

	/**
	 * The icon of the UI element.
	 * e.g. return fa fa-search
	 *
	 * @since 2.0.0
	 * @param string $context One of the values: block, elementor or bb.
	 * @return string
	 */
	public function get_icon($context = 'block') {

		if ($context === 'elementor') {

			return 'eicon-info-circle-o';

		} // end if;

		return 'fa fa-search';

	} // end get_icon;

	/**
	 * The title of the UI element.
	 *
	 * This is used on the Blocks list of Gutenberg.
	 * You should return a string with the localized title.
	 * e.g. return __('My Element', 'wp-ultimo').
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_title() {

		return __('My Sites', 'wp-ultimo');

	} // end get_title;

	/**
	 * The description of the UI element.
	 *
	 * This is also used on the Gutenberg block list
	 * to explain what this block is about.
	 * You should return a string with the localized title.
	 * e.g. return __('Adds a checkout form to the page', 'wp-ultimo').
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_description() {

		return __('Adds a block to display the sites owned by the current customer.', 'wp-ultimo');

	} // end get_description;

	/**
	 * The list of fields to be added to Gutenberg.
	 *
	 * If you plan to add Gutenberg controls to this block,
	 * you'll need to return an array of fields, following
	 * our fields interface (@see inc/ui/class-field.php).
	 *
	 * You can create new Gutenberg panels by adding fields
	 * with the type 'header'. See the Checkout Elements for reference.
	 *
	 * @see inc/ui/class-checkout-element.php
	 *
	 * Return an empty array if you don't have controls to add.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function fields() {

		$fields = array();

		$fields['header'] = array(
			'title' => __('General', 'wp-ultimo'),
			'desc'  => __('General', 'wp-ultimo'),
			'type'  => 'header',
		);

		$fields['site_manage_type'] = array(
			'type'    => 'select',
			'title'   => __('Site Manage Type', 'wp-ultimo'),
			'desc'    => __('The page to manage a site.', 'wp-ultimo'),
			'tooltip' => '',
			'default' => 'default',
			'options' => array(
				'default'     => __('Same Page', 'wp-ultimo'),
				'wp_admin'    => __('WP Admin', 'wp-ultimo'),
				'custom_page' => __('Custom Page', 'wp-ultimo'),
			),
		);

		$pages = get_pages(array(
			'exclude' => array(get_the_ID()),
		));

		$pages = $pages ? $pages : array();

		$pages_list = array(0 => __('Current Page', 'wp-ultimo'));

		foreach ($pages as $page) {

			$pages_list[$page->ID] = $page->post_title;

		} // end foreach;

		$fields['custom_manage_page'] = array(
			'type'     => 'select',
			'title'    => __('Manage Redirect Page', 'wp-ultimo'),
			'value'    => 0,
			'desc'     => __('The page to redirect user after select a site.', 'wp-ultimo'),
			'tooltip'  => '',
			'required' => array(
				'site_manage_type' => 'custom_page',
			),
			'options'  => $pages_list,
		);

		$fields['columns'] = array(
			'type'    => 'number',
			'title'   => __('Columns', 'wp-ultimo'),
			'desc'    => __('How many columns to use.', 'wp-ultimo'),
			'tooltip' => '',
			'value'   => 4,
			'min'     => 1,
			'max'     => 5,
		);

		$fields['display_images'] = array(
			'type'    => 'toggle',
			'title'   => __('Display Site Screenshot?', 'wp-ultimo'),
			'desc'    => __('Toggle to show/hide the site screenshots on the element.', 'wp-ultimo'),
			'tooltip' => '',
			'value'   => 1,
		);

		return $fields;

	} // end fields;

	/**
	 * The list of keywords for this element.
	 *
	 * Return an array of strings with keywords describing this
	 * element. Gutenberg uses this to help customers find blocks.
	 *
	 * e.g.:
	 * return array(
	 *  'WP Ultimo',
	 *  'Site',
	 *  'Form',
	 *  'Cart',
	 * );
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function keywords() {

		return array(
			'WP Ultimo',
			'Site',
			'Form',
			'Cart',
		);

	} // end keywords;

	/**
	 * List of default parameters for the element.
	 *
	 * If you are planning to add controls using the fields,
	 * it might be a good idea to use this method to set defaults
	 * for the parameters you are expecting.
	 *
	 * These defaults will be used inside a 'wp_parse_args' call
	 * before passing the parameters down to the block render
	 * function and the shortcode render function.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function defaults() {

		return array(
			'columns'            => 4,
			'display_images'     => 1,
			'site_manage_type'   => 'default',
			'custom_manage_page' => 0,
		);

	} // end defaults;

	/**
	 * Loads the necessary scripts and styles for this element.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_scripts() {

		wp_enqueue_style('wu-admin');

	} // end register_scripts;

	/**
	 * Runs early on the request lifecycle as soon as we detect the shortcode is present.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function setup() {

		if (!is_user_logged_in() || WP_Ultimo()->currents->is_site_set_via_request()) {

			$this->set_display(false);

			return;

		} // end if;

		$this->customer = WP_Ultimo()->currents->get_customer();

		if (!$this->customer) {

			$this->sites = array();

			return;

		} // end if;

		$pending_sites = \WP_Ultimo\Models\Site::get_all_by_type('pending', array('customer_id' => $this->customer->get_id()));

		$this->sites = array_merge($pending_sites, $this->customer->get_sites());

	} // end setup;

	/**
	 * Allows the setup in the context of previews.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function setup_preview() {

		$this->customer = wu_mock_customer();

		$this->sites = array(
			wu_mock_site(1),
			wu_mock_site(2),
		);

	} // end setup_preview;

	/**
	 * The content to be output on the screen.
	 *
	 * Should return HTML markup to be used to display the block.
	 * This method is shared between the block render method and
	 * the shortcode implementation.
	 *
	 * @since 2.0.0
	 *
	 * @param array       $atts Parameters of the block/shortcode.
	 * @param string|null $content The content inside the shortcode.
	 * @return string
	 */
	public function output($atts, $content = null) {

		$atts['customer'] = $this->customer;

		$atts['sites'] = $this->sites;

		return wu_get_template_contents('dashboard-widgets/my-sites', $atts);

	} // end output;

	/**
	 * Returns the manage URL for sites, depending on the environment.
	 *
	 * @since 2.0.0
	 *
	 * @param int    $site_id     A Site ID.
	 * @param string $type        De redirection type (can be: default, wp_admin or custom_page).
	 * @param string $custom_page_id The path to redirect ir using custom_page type.
	 * @return string
	 */
	public function get_manage_url($site_id, $type = 'default', $custom_page_id = 0) {

		if ($type === 'wp_admin') {

			return get_admin_url($site_id);

		} // end if;

		if ($type === 'custom_page') {

			$custom_page = get_page_link($custom_page_id);

			$url_param = \WP_Ultimo\Current::param_key('site');

			$site_hash = \WP_Ultimo\Helpers\Hash::encode($site_id, 'site');

			return add_query_arg(array(
				$url_param => $site_hash,
			), $custom_page);

		} // end if;

		return \WP_Ultimo\Current::get_manage_url($site_id, 'site');

	} // end get_manage_url;

	/**
	 * Returns the new site URL for site creation.
	 *
	 * @since 2.0.21
	 *
	 * @return string
	 */
	public function get_new_site_url() {

		$membership = WP_Ultimo()->currents->get_membership();

		$checkout_pages = \WP_Ultimo\Checkout\Checkout_Pages::get_instance();

		$url = $checkout_pages->get_page_url('new_site');

		if ($membership) {

			if ($url) {

				return add_query_arg(array(
					'membership' => $membership->get_hash(),
				), $url);

			} // end if;

			if (is_main_site()) {

				$sites = $membership->get_sites(false);

				if (!empty($sites)) {

					return add_query_arg(array(
						'page' => 'add-new-site',
					), get_admin_url($sites[0]->get_id()));

				} // end if;

				return '';

			} // end if;

		} // end if;

		return admin_url('admin.php?page=add-new-site');

	} // end get_new_site_url;

} // end class My_Sites_Element;
