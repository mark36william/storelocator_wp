<?php
/**
 * Membership Functions
 *
 * @package WP_Ultimo\Functions
 * @since   2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

use \WP_Ultimo\Models\Membership;
use \WP_Ultimo\Models\Payment;
use \WP_Ultimo\Database\Payments\Payment_Status;
use \WP_Ultimo\Checkout\Cart;

/**
 * Returns a membership.
 *
 * @since 2.0.0
 *
 * @param int $membership_id The ID of the membership.
 * @return Membership|false
 */
function wu_get_membership($membership_id) {

	return Membership::get_by_id($membership_id);

} // end wu_get_membership;

/**
 * Returns a single membership defined by a particular column and value.
 *
 * @since 2.0.0
 *
 * @param string $column The column name.
 * @param mixed  $value The column value.
 * @return Membership|false
 */
function wu_get_membership_by($column, $value) {

	return Membership::get_by($column, $value);

} // end wu_get_membership_by;

/**
 * Gets a membership based on the hash.
 *
 * @since 2.0.0
 *
 * @param string $hash The hash for the membership.
 * @return Membership|false
 */
function wu_get_membership_by_hash($hash) {

	return Membership::get_by_hash($hash);

} // end wu_get_membership_by_hash;

/**
 * Queries memberships.
 *
 * @since 2.0.0
 *
 * @param array $query Query arguments.
 * @return Membership[]
 */
function wu_get_memberships($query = array()) {

	if (!empty($query['search'])) {

		$customer_ids = wu_get_customers(array(
			'search' => $query['search'],
			'fields' => 'ids',
		));

		if (!empty($customer_ids)) {

			$query['customer_id__in'] = $customer_ids;

			unset($query['search']);

		} // end if;

	} // end if;

	return Membership::query($query);

} // end wu_get_memberships;

/**
 * Creates a new membership.
 *
 * @since 2.0.0
 *
 * @param array $membership_data Membership data.
 * @return \WP_Error|Membership
 */
function wu_create_membership($membership_data) {
	/*
	* Why do we use shortcode atts here?
	* Shortcode atts clean the array from not-allowed keys, so we don't need to worry much.
	*/
	$membership_data = shortcode_atts(array(
		'customer_id'             => false,
		'user_id'                 => false,
		'migrated_from_id'        => 0,
		'plan_id'                 => false,
		'addon_products'          => false,
		'currency'                => false,
		'initial_amount'          => false,
		'recurring'               => false,
		'duration'                => 1,
		'duration_unit'           => 'month',
		'amount'                  => false,
		'auto_renew'              => false,
		'times_billed'            => 0,
		'billing_cycles'          => 0,
		'gateway_customer_id'     => false,
		'gateway_subscription_id' => false,
		'gateway'                 => '',
		'signup_method'           => '',
		'upgraded_from'           => false,
		'disabled'                => false,
		'status'                  => 'pending',
		'date_created'            => wu_get_current_time('mysql', true),
		'date_activate'           => null,
		'date_trial_end'          => null,
		'date_renewed'            => null,
		'date_modified'            => wu_get_current_time('mysql', true),
		'date_expiration'         => wu_get_current_time('mysql', true),
		'skip_validation'         => false,
	), $membership_data);

	$membership_data['migrated_from_id'] = is_numeric($membership_data['migrated_from_id']) ? $membership_data['migrated_from_id'] : 0;

	$membership = new Membership($membership_data);

	$saved = $membership->save();

	return is_wp_error($saved) ? $saved : $membership;

} // end wu_create_membership;

/**
 * Get all customers with a specific membership using the product_id as reference.
 *
 * @since 2.0.0
 *
 * @param array $product_id Membership product.
 * @return array    With all users within the membership.
 */
function wu_get_membership_customers($product_id) {

	global $wpdb;

	is_multisite() && switch_to_blog(get_main_site_id());

	$product_id = (int) $product_id;

	$regex = "(\\\\{i:$product_id;i)|(\\\\{((i:[0-9]*;){2})+(i:$product_id;i:))";

	$table = "{$wpdb->prefix}wu_memberships";

	$query = "SELECT `customer_id` FROM $table WHERE `addon_products` REGEXP '$regex' OR `plan_id` = $product_id";

	$results = $wpdb->get_results($query); // phpcs:ignore

	$results = array_map(function($result) {

		return (int) $result->customer_id;

	}, $results);

	is_multisite() && restore_current_blog();

	return $results;

} // end wu_get_membership_customers;

/**
 * Returns a membership based on the customer gateway ID.
 *
 * This is NOT a very reliable way of retrieving memberships
 * as the same customer can have multiple memberships using
 * the same gateway.
 *
 * As this is only used as a last ditch effort, mostly when
 * trying to process payment-related webhooks,
 * we always get pending memberships, and the last one
 * created (order by ID DESC).
 *
 * @since 2.0.0
 *
 * @param string  $customer_gateway_id The customer gateway id. E.g. cus_***.
 * @param array   $allowed_gateways List of allowed gateways.
 * @param boolean $amount The amount. Increases accuracy.
 * @return Membership|false
 */
function wu_get_membership_by_customer_gateway_id($customer_gateway_id, $allowed_gateways = array(), $amount = false) {

	$search_data = array(
		'gateway__in'             => $allowed_gateways,
		'number'                  => 1,
		'gateway_customer_id__in' => array($customer_gateway_id),
		'status__in'              => array('pending'),
		'orderby'                 => 'id',
		'order'                   => 'DESC',
	);

	if (!empty($amount)) {

		$search_data['initial_amount'] = $amount;

	} // end if;

	$memberships = wu_get_memberships($search_data);

	return !empty($memberships) ? current($memberships) : false;

} // end wu_get_membership_by_customer_gateway_id;

/**
 * Returns the price for a product in a specific membership.
 * This allow us to calculate the values for a product change considering taxes.
 *
 * @since 2.1.3
 *
 * @param Membership $membership     The membership.
 * @param int        $product_id     The product ID.
 * @param int        $quantity       The amount of products.
 * @param bool       $only_recurring Whether to only get the recurring price.
 * @return float|\WP_Error The price or error.
 */
function wu_get_membership_product_price($membership, $product_id, $quantity, $only_recurring = true) {

	$address = $membership->get_billing_address();

	// Create a Cart with this product
	$cart = new Cart(array(
		'duration'      => $membership->get_duration(),
		'duration_unit' => $membership->get_duration_unit(),
		'country'       => $address->billing_country,
		'state'         => $address->billing_state,
		'city'          => $address->billing_city,
	));

	$discount_code = $membership->get_discount_code();

	if ($discount_code) {

		$cart->add_discount_code($discount_code);

	} // end if;

	$added = $cart->add_product($product_id, $quantity);

	if (!$added) {

		return $cart->errors;

	} // end if;

	$payment_data = array_merge($cart->to_payment_data(), array(
		'customer_id'   => $membership->get_customer_id(),
		'membership_id' => $membership->get_id(),
		'gateway'       => $membership->get_gateway(),
	));

	// create a temporary payment to see the price.
	$temp_payment = wu_create_payment($payment_data, false);

	if (is_wp_error($temp_payment)) {

		return $temp_payment;

	} // end if;

	if ($only_recurring) {

		$temp_payment->remove_non_recurring_items();

	} // end if;

	$temp_payment->recalculate_totals();

	return $temp_payment->get_total();

} // end wu_get_membership_product_price;

/**
 * Creates a new payment for a membership.
 *
 * This is used by gateways to create a new payment when necessary.
 *
 * @since 2.0.0
 *
 * @param Membership $membership The membership object.
 * @param bool       $should_cancel_pending_payments If we should cancel pending payments.
 * @param bool       $remove_non_recurring If we should remove the non recurring items.
 * @param bool       $save If we should save the created payment.
 * @return Payment|\WP_Error
 */
function wu_membership_create_new_payment($membership, $should_cancel_pending_payments = true, $remove_non_recurring = true, $save = true) {
	/*
	 * If we should cancel the previous
	 * pending payment, do that.
	 */
	if ($should_cancel_pending_payments) {

		$pending_payment = $membership->get_last_pending_payment();

		/*
		 * Change pending payment to cancelled.
		 */
		if ($pending_payment) {

			$pending_payment->set_status(Payment_Status::CANCELLED);
			$pending_payment->save();

		} // end if;

	} // end if;

	$cart = wu_get_membership_new_cart($membership);

	$payment_data = array_merge($cart->to_payment_data(), array(
		'customer_id'   => $membership->get_customer_id(),
		'membership_id' => $membership->get_id(),
		'gateway'       => $membership->get_gateway(),
	));

	// We will save the payment after we recalculate the totals.
	$new_payment = wu_create_payment($payment_data, false);

	if (is_wp_error($new_payment)) {

		return $new_payment;

	} // end if;

	if ($remove_non_recurring) {

		$new_payment->remove_non_recurring_items();

	} // end if;

	$new_payment->recalculate_totals();

	if (!$save) {

		return $new_payment;

	} // end if;

	$status = $new_payment->save();

	if (is_wp_error($status)) {

		return $status;

	} // end if;

	return $new_payment;

} // end wu_membership_create_new_payment;

/**
 * Creates a full cart based on a membership.
 *
 * @since 2.1.3
 *
 * @param Membership $membership The membership object.
 * @return Cart
 */
function wu_get_membership_new_cart($membership) {

	$address = $membership->get_billing_address();

	$cart = new Cart(array(
		'duration'      => $membership->get_duration(),
		'duration_unit' => $membership->get_duration_unit(),
		'country'       => $address->billing_country,
		'state'         => $address->billing_state,
		'city'          => $address->billing_city,
	));

	$discount_code = $membership->get_discount_code();

	if ($discount_code) {

		$cart->add_discount_code($discount_code);

	} // end if;

	foreach ($membership->get_all_products() as $key => $product) {

		$cart->add_product($product['product']->get_id(), $product['quantity']);

	} // end foreach;

	$difference = $membership->get_amount() - $cart->get_recurring_total();

	if (round(abs($difference), wu_currency_decimal_filter()) > 0) {

		$type_translate = $difference < 0 ? __('credit', 'wp-ultimo') : __('debit', 'wp-ultimo');

		$line_item_params = array(
			'hash'          => 'ADJUSTMENT',
			'type'          => $difference < 0 ? 'credit' : 'fee',
			'title'         => sprintf(__('Adjustment %s', 'wp-ultimo'), $type_translate),
			'description'   => __('Amount adjustment based on previous deal.', 'wp-ultimo'),
			'unit_price'    => $difference,
			'discountable'  => false,
			'taxable'       => false,
			'recurring'     => true,
			'quantity'      => 1,
			'duration'      => $membership->get_duration(),
			'duration_unit' => $membership->get_duration_unit(),
		);

		$adjustment_line_item = new \WP_Ultimo\Checkout\Line_Item($line_item_params);

		$cart->add_line_item($adjustment_line_item);

	} // end if;

	if ($membership->get_initial_amount() !== $cart->get_total()) {

		$t = $membership->get_initial_amount();
		$y = $cart->get_total();

		$difference     = $membership->get_initial_amount() - $cart->get_total();
		$type_translate = $difference < 0 ? __('credit', 'wp-ultimo') : __('debit', 'wp-ultimo');

		$line_item_params = array(
			'hash'          => 'INITADJUSTMENT',
			'type'          => $difference < 0 ? 'credit' : 'fee',
			'title'         => sprintf(__('Adjustment %s', 'wp-ultimo'), $type_translate),
			'description'   => __('Initial amount adjustment based on previous deal.', 'wp-ultimo'),
			'unit_price'    => $difference,
			'discountable'  => false,
			'taxable'       => false,
			'recurring'     => false,
			'quantity'      => 1,
		);

		$adjustment_line_item = new \WP_Ultimo\Checkout\Line_Item($line_item_params);

		$cart->add_line_item($adjustment_line_item);

	} // end if;

	$y = $cart->get_total();
	$t = $cart->get_recurring_total();

	return $cart;

} // end wu_get_membership_new_cart;

/**
 * Generate the modal link to search for an upgrade path.
 *
 * @since 2.1
 *
 * @param Membership $membership The membership to get the url.
 * @return string
 */
function wu_get_membership_update_url($membership) {

	$checkout_pages = \WP_Ultimo\Checkout\Checkout_Pages::get_instance();

	$url = $checkout_pages->get_page_url('update');

	$membership_hash = $membership->get_hash();

	if ($url) {

		return add_query_arg(array(
			'membership' => $membership_hash,
		), $url);

	} // end if;

	if (!is_main_site()) {

		return admin_url('admin.php?page=wu-checkout&membership=' . $membership_hash);

	} // end if;

	$sites = $membership->get_sites(false);

	if (count($sites) > 0) {

		return add_query_arg(array(
			'page'       => 'wu-checkout',
			'membership' => $membership_hash,
		), get_admin_url($sites[0]->get_id()));

	} // end if;

	// In last case we use the default register form
	$url = $checkout_pages->get_page_url('register');

	return add_query_arg(array(
		'membership' => $membership_hash,
		'wu_form'    => 'wu-checkout',
	), $url);

} // end wu_get_membership_update_url;
