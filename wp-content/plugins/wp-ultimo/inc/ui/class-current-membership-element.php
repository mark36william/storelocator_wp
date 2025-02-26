<?php
/**
 * Adds the Current_Membership_Element UI to the Admin Panel.
 *
 * @package WP_Ultimo
 * @subpackage UI
 * @since 2.0.0
 */

namespace WP_Ultimo\UI;

use \WP_Ultimo\UI\Base_Element;
use \WP_Ultimo\Checkout\Cart;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Adds the Checkout Element UI to the Admin Panel.
 *
 * @since 2.0.0
 */
class Current_Membership_Element extends Base_Element {

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
	public $id = 'current-membership';

	/**
	 * Controls if this is a public element to be used in pages/shortcodes by user.
	 *
	 * @since 2.0.24
	 * @var boolean
	 */
	protected $public = true;

	/**
	 * Overload the init to add site-related forms.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init() {

		parent::init();

		wu_register_form('see_product_details', array(
			'render'     => array($this, 'render_product_details'),
			'capability' => 'exist',
		));

		wu_register_form('edit_membership_product_modal', array(
			'render'     => array($this, 'render_edit_membership_product_modal'),
			'handler'    => array($this, 'handle_edit_membership_product_modal'),
			'capability' => 'exist',
		));

	} // end init;

	/**
	 * Loads the required scripts.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_scripts() {

		add_wubox();

	} // end register_scripts;

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

		return __('Membership', 'wp-ultimo');

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

		return __('Adds a checkout form block to the page.', 'wp-ultimo');

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

		$fields['title'] = array(
			'type'    => 'text',
			'title'   => __('Title', 'wp-ultimo'),
			'value'   => __('Your Membership', 'wp-ultimo'),
			'desc'    => __('Leave blank to hide the title completely.', 'wp-ultimo'),
			'tooltip' => '',
		);

		$fields['display_images'] = array(
			'type'    => 'toggle',
			'title'   => __('Display Product Images?', 'wp-ultimo'),
			'desc'    => __('Toggle to show/hide the product images on the element.', 'wp-ultimo'),
			'tooltip' => '',
			'value'   => 1,
		);

		$fields['columns'] = array(
			'type'    => 'number',
			'title'   => __('Columns', 'wp-ultimo'),
			'desc'    => __('How many columns to use.', 'wp-ultimo'),
			'tooltip' => '',
			'value'   => 2,
			'min'     => 1,
			'max'     => 5,
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
	 *  'Membership',
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
			'Membership',
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
			'title'          => __('Your Membership', 'wp-ultimo'),
			'display_images' => 1,
			'columns'        => 2,
		);

	} // end defaults;

	/**
	 * Runs early on the request lifecycle as soon as we detect the shortcode is present.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function setup() {

		$this->membership = WP_Ultimo()->currents->get_membership();

		if (!$this->membership) {

			$this->set_display(false);

			return;

		} // end if;

		$this->plan = $this->membership ? $this->membership->get_plan() : false;

	} // end setup;

	/**
	 * Allows the setup in the context of previews.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function setup_preview() {

		$this->membership = wu_mock_membership();

		$this->plan = wu_mock_product();

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

		$atts['membership'] = $this->membership;
		$atts['plan']       = $this->plan;
		$atts['element']    = $this;

		$atts['pending_change'] = false;

		if ($this->membership) {

			$pending_swap_order = $this->membership->get_scheduled_swap();

			$atts['pending_products'] = false;

			if ($pending_swap_order) {

				$atts['pending_change']      = $pending_swap_order->order->get_cart_descriptor();
				$atts['pending_change_date'] = wu_date($pending_swap_order->scheduled_date)->format(get_option('date_format'));

				$swap_membership = (clone $this->membership)->swap($pending_swap_order->order);

				$pending_products = array_map(function($product) {

					return array(
						'id'       => $product['product']->get_id(),
						'quantity' => $product['quantity'],
					);

				}, $swap_membership->get_all_products());

				// add the id as key
				$atts['pending_products'] = array_combine(array_column($pending_products, 'id'), $pending_products);

			} // end if;

			return wu_get_template_contents('dashboard-widgets/current-membership', $atts);

		} // end if;

		return '';

	} // end output;

	/**
	 * Renders the product details modal window.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function render_product_details() {

		$product = wu_get_product_by_slug(wu_request('product'));

		if (!$product) {

			return;

		} // end if;

		$atts['product'] = $product;

		wu_get_template('dashboard-widgets/current-membership-product-details', $atts);

	} // end render_product_details;

	/**
	 * Renders the add/edit line items form.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function render_edit_membership_product_modal() {

		$membership = wu_get_membership_by_hash(wu_request('membership'));

		$error = '';

		if (!$membership) {

			$error = __('Membership not selected.', 'wp-ultimo');

		} // end if;

		$product = wu_get_product_by_slug(wu_request('product'));

		if (!$product) {

			$error = __('Product not selected.', 'wp-ultimo');

		} // end if;

		$customer = wu_get_current_customer();

		if (empty($error) && !is_super_admin() && (!$customer || $customer->get_id() !== $membership->get_customer_id())) {

			$error = __('You are not allowed to do this.', 'wp-ultimo');

		} // end if;

		if (!empty($error)) {

			$error_field = array(
				'error_message' => array(
					'type' => 'note',
					'desc' => $error,
				),
			);

			$form = new \WP_Ultimo\UI\Form('cancel_payment_method', $error_field, array(
				'views'                 => 'admin-pages/fields',
				'classes'               => 'wu-modal-form wu-widget-list wu-striped wu-m-0 wu-mt-0',
				'field_wrapper_classes' => 'wu-w-full wu-box-border wu-items-center wu-flex wu-justify-between wu-p-4 wu-m-0 wu-border-t wu-border-l-0 wu-border-r-0 wu-border-b-0 wu-border-gray-300 wu-border-solid',
			));

			$form->render();

			return;

		} // end if;

		/**
		 * If there is a scheduled swap, we need to swap the membership before
		 * removing the product to ensure the full change for next billing cycle.
		 */
		$existing_swap = $membership->get_scheduled_swap();

		if ($existing_swap) {

			$membership = $membership->swap($existing_swap->order);

		} // end if;

		$gateway_message = false;

		if (!empty($membership->get_gateway())) {

			$gateway = wu_get_gateway($membership->get_gateway());

			$gateway_message = $gateway ? $gateway->get_amount_update_message(true) : '';

		} // end if;

		$existing_quantity = array_filter($membership->get_addon_products(), function($item) use ($product) {

			return $item['product']->get_id() === $product->get_id();

		})[0]['quantity'];

		$fields = array(
			'membership'    => array(
				'type'  => 'hidden',
				'value' => wu_request('membership'),
			),
			'product'       => array(
				'type'  => 'hidden',
				'value' => wu_request('product'),
			),
			'quantity'      => array(
				'type'            => 'number',
				'title'           => __('Quantity to Cancel', 'wp-ultimo'),
				'value'           => 1,
				'placeholder'     => 1,
				'wrapper_classes' => 'wu-w-1/2',
				'html_attr'       => array(
					'min'      => 1,
					'max'      => $existing_quantity,
					'required' => 'required',
				),
				'wrapper_html_attr' => array(
					'v-show'  => $existing_quantity > 1 ? 'true' : 'false',
					'v-cloak' => '1',
				),
			),
			'confirm'       => array(
				'type'      => 'toggle',
				'title'     => __('Confirm Product Cancellation', 'wp-ultimo'),
				'desc'      => __('This action can not be undone.', 'wp-ultimo'),
				'html_attr' => array(
					'v-model' => 'confirmed',
				),
			),
			'update_note'   => array(
				'type'              => 'note',
				'desc'              => $gateway_message,
				'classes'           => 'sm:wu-p-2 wu-bg-red-100 wu-text-red-600 wu-rounded wu-w-full',
			),
			'submit_button' => array(
				'type'            => 'submit',
				'title'           => __('Cancel Product Subscription', 'wp-ultimo'),
				'placeholder'     => __('Cancel Product Subscription', 'wp-ultimo'),
				'value'           => 'save',
				'classes'         => 'wu-w-full button button-primary',
				'wrapper_classes' => 'wu-items-end',
				'html_attr'       => array(
					'v-bind:disabled' => '!confirmed',
				),
			),
		);

		if (!$gateway_message) {

			unset($fields['update_note']);

		} // end if;

		$form = new \WP_Ultimo\UI\Form('edit_membership_product', $fields, array(
			'views'                 => 'admin-pages/fields',
			'classes'               => 'wu-modal-form wu-widget-list wu-striped wu-m-0 wu-mt-0',
			'field_wrapper_classes' => 'wu-w-full wu-box-border wu-items-center wu-flex wu-justify-between wu-p-4 wu-m-0 wu-border-t wu-border-l-0 wu-border-r-0 wu-border-b-0 wu-border-gray-300 wu-border-solid',
			'html_attr'             => array(
				'data-wu-app' => 'edit_membership_product',
				'data-state'  => wu_convert_to_state(array(
					'confirmed' => false,
				)),
			),
		));

		$form->render();

	} // end render_edit_membership_product_modal;

	/**
	 * Handles the membership product remove.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function handle_edit_membership_product_modal() {

		if (!wu_request('confirm')) {

			$error = new \WP_Error('not-confirmed', __('Please confirm the cancellation.', 'wp-ultimo'));

			wp_send_json_error($error);

		} // end if;

		$membership = wu_get_membership_by_hash(wu_request('membership'));

		if (!$membership) {

			$error = new \WP_Error('membership-not-found', __('Membership not found.', 'wp-ultimo'));

			wp_send_json_error($error);

		} // end if;

		$product = wu_get_product_by_slug(wu_request('product'));

		if (!$product) {

			$error = new \WP_Error('product-not-found', __('Product not found.', 'wp-ultimo'));

			wp_send_json_error($error);

		} // end if;

		$customer = wu_get_current_customer();

		if (!is_super_admin() && (!$customer || $customer->get_id() !== $membership->get_customer_id())) {

			$error = __('You are not allowed to do this.', 'wp-ultimo');

			wp_send_json_error($error);

		} // end if;

		// Get the existing quantity by filtering the products array.
		$existing_quantity = array_filter($membership->get_addon_products(), function($item) use ($product) {

			return $item['product']->get_id() === $product->get_id();

		})[0]['quantity'];

		$original_quantity = $existing_quantity;

		/**
		 * If there is a scheduled swap, we need to swap the membership before
		 * removing the product to ensure the full change for next billing cycle.
		 */
		$existing_swap = $membership->get_scheduled_swap();

		if ($existing_swap) {

			$membership = $membership->swap($existing_swap->order);

			$existing_quantity = array_filter($membership->get_addon_products(), function($item) use ($product) {

				return $item['product']->get_id() === $product->get_id();

			})[0]['quantity'];

		} // end if;

		$quantity = (int) wu_request('quantity', 1);
		$quantity = $quantity > $existing_quantity ? $existing_quantity : $quantity;

		$membership->remove_product($product->get_id(), $quantity);

		$value_to_remove = wu_get_membership_product_price($membership, $product->get_id(), $quantity);

		if (is_wp_error($value_to_remove)) {

			wp_send_json_error($value_to_remove);

		} // end if;

		$plan_price = wu_get_membership_product_price($membership, $membership->get_plan()->get_id(), 1);

		// do not allow remove more than the plan price
		if ($plan_price < $value_to_remove) {

			$value_to_remove = $membership->get_amount() - $plan_price;
			$value_to_remove = $value_to_remove < 0 ? 0 : $value_to_remove;

		} // end if;

		$membership->set_amount($membership->get_amount() - $value_to_remove);

		$cart = wu_get_membership_new_cart($membership);

		$existing_difference = $original_quantity - $existing_quantity;

		$removed_quantity = $quantity + $existing_difference;

		$description = sprintf(__('remove %1$s %2$s from membership', 'wp-ultimo'), $removed_quantity, $product->get_name());

		$cart->set_cart_descriptor($description);

		$schedule_swap = $membership->schedule_swap($cart);

		// Lets schedule this change as the customer already paid for this period.
		if (is_wp_error($schedule_swap)) {

			wp_send_json_error($schedule_swap);

		} // end if;

		// Now we trigger the gateway update so the customer is charged for the new amount.
		$gateway = wu_get_gateway($membership->get_gateway());

		if ($gateway) {

			$gateway->process_membership_update($membership, $customer);

		} // end if;

		wp_send_json_success(array(
			'redirect_url' => add_query_arg('updated', 1, $_SERVER['HTTP_REFERER']),
		));

	} // end handle_edit_membership_product_modal;

} // end class Current_Membership_Element;
