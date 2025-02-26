<?php
/**
 * Base Gateway.
 *
 * Base Gateway class. Should be extended to add new payment gateways.
 *
 * @package WP_Ultimo
 * @subpackage Managers/Site_Manager
 * @since 2.0.0
 */

namespace WP_Ultimo\Gateways;

use \WP_Ultimo\Database\Payments\Payment_Status;
use \WP_Ultimo\Database\Memberships\Membership_Status;
use \WP_Ultimo\Gateways\Base_Stripe_Gateway;
use \WP_Ultimo\Dependencies\Stripe;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Base Gateway class. Should be extended to add new payment gateways.
 *
 * @since 2.0.0
 */
class Stripe_Gateway extends Base_Stripe_Gateway {

	/**
	 * Holds the ID of a given gateway.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $id = 'stripe';

	/**
	 * Adds additional hooks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function hooks() {

		parent::hooks();

		add_filter('wu_customer_payment_methods', function($fields, $customer) {

			$this->customer = $customer;

			$extra_fields = $this->payment_methods();

			return array_merge($fields, $extra_fields);

		}, 10, 2);

	} // end hooks;

	/**
	 * Adds the Stripe Gateway settings to the settings screen.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function settings() {

		$error_message_wrap = '<span class="wu-p-2 wu-bg-red-100 wu-text-red-600 wu-rounded wu-mt-3 wu-mb-0 wu-block wu-text-xs">%s</span>';

		wu_register_settings_field('payment-gateways', 'stripe_header', array(
			'title'           => __('Stripe', 'wp-ultimo'),
			'desc'            => __('Use the settings section below to configure Stripe as a payment method.', 'wp-ultimo'),
			'type'            => 'header',
			'show_as_submenu' => true,
			'require'         => array(
				'active_gateways' => 'stripe',
			),
		));

		wu_register_settings_field('payment-gateways', 'stripe_public_title', array(
			'title'   => __('Stripe Public Name', 'wp-ultimo'),
			'tooltip' => __('The name to display on the payment method selection field. By default, "Credit Card" is used.', 'wp-ultimo'),
			'type'    => 'text',
			'default' => __('Credit Card', 'wp-ultimo'),
			'require' => array(
				'active_gateways' => 'stripe',
			),
		));

		wu_register_settings_field('payment-gateways', 'stripe_sandbox_mode', array(
			'title'     => __('Stripe Sandbox Mode', 'wp-ultimo'),
			'desc'      => __('Toggle this to put Stripe on sandbox mode. This is useful for testing and making sure Stripe is correctly setup to handle your payments.', 'wp-ultimo'),
			'type'      => 'toggle',
			'default'   => 1,
			'html_attr' => array(
				'v-model' => 'stripe_sandbox_mode',
			),
			'require'   => array(
				'active_gateways' => 'stripe',
			),
		));

		$pk_test_status = wu_get_setting('stripe_test_pk_key_status', '');

		wu_register_settings_field('payment-gateways', 'stripe_test_pk_key', array(
			'title'       => __('Stripe Test Publishable Key', 'wp-ultimo'),
			'desc'        => !empty($pk_test_status) ? sprintf($error_message_wrap, $pk_test_status) : '',
			'tooltip'     => __('Make sure you are placing the TEST keys, not the live ones.', 'wp-ultimo'),
			'placeholder' => __('pk_test_***********', 'wp-ultimo'),
			'type'        => 'text',
			'default'     => '',
			'capability'  => 'manage_api_keys',
			'require'     => array(
				'active_gateways'     => 'stripe',
				'stripe_sandbox_mode' => 1,
			),
		));

		$sk_test_status = wu_get_setting('stripe_test_sk_key_status', '');

		wu_register_settings_field('payment-gateways', 'stripe_test_sk_key', array(
			'title'       => __('Stripe Test Secret Key', 'wp-ultimo'),
			'desc'        => !empty($sk_test_status) ? sprintf($error_message_wrap, $sk_test_status) : '',
			'tooltip'     => __('Make sure you are placing the TEST keys, not the live ones.', 'wp-ultimo'),
			'placeholder' => __('sk_test_***********', 'wp-ultimo'),
			'type'        => 'text',
			'default'     => '',
			'capability'  => 'manage_api_keys',
			'require'     => array(
				'active_gateways'     => 'stripe',
				'stripe_sandbox_mode' => 1,
			),
		));

		$pk_status = wu_get_setting('stripe_live_pk_key_status', '');

		wu_register_settings_field('payment-gateways', 'stripe_live_pk_key', array(
			'title'       => __('Stripe Live Publishable Key', 'wp-ultimo'),
			'desc'        => !empty($pk_status) ? sprintf($error_message_wrap, $pk_status) : '',
			'tooltip'     => __('Make sure you are placing the LIVE keys, not the test ones.', 'wp-ultimo'),
			'placeholder' => __('pk_live_***********', 'wp-ultimo'),
			'type'        => 'text',
			'default'     => '',
			'capability'  => 'manage_api_keys',
			'require'     => array(
				'active_gateways'     => 'stripe',
				'stripe_sandbox_mode' => 0,
			),
		));

		$sk_status = wu_get_setting('stripe_live_sk_key_status', '');

		wu_register_settings_field('payment-gateways', 'stripe_live_sk_key', array(
			'title'       => __('Stripe Live Secret Key', 'wp-ultimo'),
			'desc'        => !empty($sk_status) ? sprintf($error_message_wrap, $sk_status) : '',
			'tooltip'     => __('Make sure you are placing the LIVE keys, not the test ones.', 'wp-ultimo'),
			'placeholder' => __('sk_live_***********', 'wp-ultimo'),
			'type'        => 'text',
			'default'     => '',
			'capability'  => 'manage_api_keys',
			'require'     => array(
				'active_gateways'     => 'stripe',
				'stripe_sandbox_mode' => 0,
			),
		));

		$webhook_message = sprintf('<span class="wu-p-2 wu-bg-blue-100 wu-text-blue-600 wu-rounded wu-mt-3 wu-mb-0 wu-block wu-text-xs">%s</span>', __('Whenever you change your Stripe settings, WP Ultimo will automatically check the webhook URLs on your Stripe account to make sure we get notified about changes in subscriptions and payments.', 'wp-ultimo'));

		wu_register_settings_field('payment-gateways', 'stripe_webhook_listener_explanation', array(
			'title'           => __('Webhook Listener URL', 'wp-ultimo'),
			'desc'            => $webhook_message,
			'tooltip'         => __('This is the URL Stripe should send webhook calls to.', 'wp-ultimo'),
			'type'            => 'text-display',
			'copy'            => true,
			'default'         => $this->get_webhook_listener_url(),
			'wrapper_classes' => '',
			'require'         => array(
				'active_gateways' => 'stripe',
			),
		));

		parent::settings();

	} // end settings;

	/**
	 * Run preparations before checkout processing.
	 *
	 * This runs during the checkout form validation
	 * and it is a great chance to do preflight stuff
	 * if the gateway requires it.
	 *
	 * If you return an array here, Ultimo
	 * will append the key => value of that array
	 * as hidden fields to the checkout field,
	 * and those get submitted with the rest of the form.
	 *
	 * As an example, this is how we create payment
	 * intents for Stripe to make the experience more
	 * streamlined.
	 *
	 * @since 2.0.0
	 * @return void|array
	 */
	public function run_preflight() {
		/*
		 * This is the stripe preflight code.
		 *
		 * Stripe requires us to create a payment intent
		 * or payment setup to be able to charge customers.
		 *
		 * This is done in order to comply with EU SCA
		 * and other such regulations.
		 *
		 * Before we get started, we need to get our stripe
		 * customer.
		 */
		$s_customer = $this->get_or_create_customer($this->customer->get_id(), $this->customer->get_user_id());

		/*
		 * Things can go wrong,
		 * check for WP_Error.
		 */
		if (is_wp_error($s_customer)) {

			// translators: %s is the error message.
			return new \WP_Error($s_customer->get_error_code(), sprintf(__('Error creating Stripe customer: %s', 'wp-ultimo'), $s_customer->get_error_message()));

		} // end if;

		$this->membership->set_gateway_customer_id($s_customer->id);
		$this->membership->set_gateway($this->get_id());

		$type = $this->order->get_cart_type();

		/*
		 * Lets deal with upgrades, downgrades and addons
		 *
		 * Here, we just need to make sure we process
		 * a membership swap.
		 */
		if ($type === 'upgrade' || $type === 'addon') {

			$this->membership->swap($this->order);

		} elseif ($type === 'downgrade') {

			$this->membership->schedule_swap($this->order);

		} // end if;

		$this->membership->save();

		$intent_args = array(
			'customer'    => $s_customer->id,
			'metadata'    => $this->get_customer_metadata(),
			'description' => $this->order->get_cart_descriptor(),
		);

		/*
		 * Maybe use an existing payment method.
		 */
		if (wu_request('payment_method', 'add-new') !== 'add-new') {

			$intent_args['payment_method'] = sanitize_text_field(wu_request('payment_method'));

		} // end if;

		/*
		 * Let's start with the intent options.
		 *
		 * We'll append the extra options as we go.
		 * This should also be filterable, to allow support
		 * for Stripe Connect in the future.
		 */
		$intent_options = array();

		/*
		 * Tries to retrieve an existing intent id,
		 * from the current payment.
		 */
		$payment_intent_id = $this->payment->get_meta('stripe_payment_intent_id');
		$existing_intent   = false;

		/**
		 * Ensure the correct api keys are set
		 */
		$this->setup_api_keys();

		/*
		 * Tries to retrieve an intent on Stripe.
		 *
		 * If we success, we update it, if we fail,
		 * we try to create a new one.
		 */
		try {
			/*
			 * Payment intents are used when we have an initial
			 * payment attached to the membership. Those start with a pi_
			 * id.
			 */
			if (!empty($payment_intent_id) && 'pi_' === substr($payment_intent_id, 0, 3)) {

				$existing_intent = Stripe\PaymentIntent::retrieve($payment_intent_id);

			/*
			 * Setup intents are created with the intent
			 * of future charging. This is what we use
			 * when we set up a subscription without a
			 * initial amount.
			 */
			} elseif (!empty($payment_intent_id) && 'seti_' === substr($payment_intent_id, 0, 5)) {

				$existing_intent = Stripe\SetupIntent::retrieve($payment_intent_id);

			} // end if;

			/*
			 * We can't use cancelled intents
			 * for obvious reasons...
			 */
			if (!empty($existing_intent) && 'canceled' === $existing_intent->status) {

				$existing_intent = false;

			} // end if;

			/*
			 * If we have a initial payment,
			 * we need to take care of that logic.
			 *
			 * If we have a trial, we need to deal with that via a setup intent.
			 */
			if ($this->order->get_total() && $this->order->has_trial() === false) {

				$intent_args = wp_parse_args($intent_args, array(
					'amount'              => $this->order->get_total() * wu_stripe_get_currency_multiplier(),
					'confirmation_method' => 'automatic',
					'setup_future_usage'  => 'off_session',
					'currency'            => strtolower(wu_get_setting('currency_symbol', 'USD')),
					'confirm'             => false,
				));

				/**
				 * Filters the payment intent arguments.
				 *
				 * @since 2.0
				 *
				 * @param array $intent_args The list of intent args.
				 * @param \WP_Ultimo\Gateways\Stripe_Gateway $this.
				 * @return array
				 */
				$intent_args = apply_filters('wu_stripe_create_payment_intent_args', $intent_args, $this);

				if (!empty($existing_intent) && 'payment_intent' === $existing_intent->object) {

					$idempotency_args           = $intent_args;
					$idempotency_args['update'] = true;

					/*
					 * Stripe allows us to send a key
					 * together with the arguments to prevent
					 * duplication in payment intents.
					 *
					 * Same parameters = same key,
					 * so Stripe knows what to ignore.
					 */
					$intent_options['idempotency_key'] = wu_stripe_generate_idempotency_key($idempotency_args);

					// Unset some options we can't update.
					$unset_args = array('confirmation_method', 'confirm');

					foreach ($unset_args as $unset_arg) {

						if (isset($intent_args[$unset_arg])) {

							unset($intent_args[$unset_arg]);

						} // end if;

					} // end foreach;

					/*
					 * Tries to update the payment intent.
					 */
					$intent = Stripe\PaymentIntent::update($existing_intent->id, $intent_args, $intent_options);

				} else {

					$intent_options['idempotency_key'] = wu_stripe_generate_idempotency_key($intent_args);

					$intent = Stripe\PaymentIntent::create($intent_args, $intent_options);

				} // end if;

			} else {
				/*
				 * Create a setup intent instead.
				 */
				$intent_args = wp_parse_args($intent_args, array(
					'usage' => 'off_session'
				));

				if (empty($existing_intent) || 'setup_intent' !== $existing_intent->object) {

					$intent_options['idempotency_key'] = wu_stripe_generate_idempotency_key($intent_args);

					/*
					 * Tries to create in Stripe.
					 */
					$intent = Stripe\SetupIntent::create($intent_args, $intent_options);

				} // end if;

			} // end if;

		} catch (Stripe\Stripe\Error\Base $e) {

			return $this->get_stripe_error($e);

		} catch (\Exception $e) {

			$error_code = $e->getCode();

			// WP Error did not handle empty error code
			if (empty($error_code)) {

				if (method_exists($e, 'getHttpStatus')) {

					$error_code = $e->getHttpStatus();

				} else {

					$error_code = 500;

				} // end if;

			} // end if;

			return new \WP_Error($error_code, $e->getMessage());

		} // end try;

		/*
		 * To prevent re-doing all this
		 * work again, we save the intent on
		 * the payment, so we can use it
		 * in cases a retry is needed.
		 */
		$this->payment->update_meta('stripe_payment_intent_id', sanitize_text_field($intent->id));

		/*
		 * Anything returned in this array
		 * gets added to the checkout form as hidden
		 * fields just before the form submission.
		 *
		 * Here we pass the data we need from the
		 * recently create intents.
		 *
		 * Using this info, we'll be able to process
		 * the Stripe payment on the next step: process_checkout
		 */
		return array(
			'stripe_client_secret' => sanitize_text_field($intent->client_secret),
			'stripe_intent_type'   => sanitize_text_field($intent->object),
		);

	} // end run_preflight;

	/**
	 * Process a checkout.
	 *
	 * It takes the data concerning
	 * a new checkout and process it.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Payment    $payment The payment associated with the checkout.
	 * @param \WP_Ultimo\Models\Membership $membership The membership.
	 * @param \WP_Ultimo\Models\Customer   $customer The customer checking out.
	 * @param \WP_Ultimo\Checkout\Cart     $cart The cart object.
	 * @param string                       $type The checkout type. Can be 'new', 'retry', 'upgrade', 'downgrade', 'addon'.
	 *
	 * @throws \Exception When a stripe API error is caught.
	 *
	 * @return void
	 */
	public function process_checkout($payment, $membership, $customer, $cart, $type) {
		/*
		 * Here's the general idea
		 * of how the Stripe integration works.
		 *
		 * Despite of the type, we'll need to
		 * cancel an existing subscription to create
		 * a new one.
		 *
		 * Then, after that's all said and done
		 * we can move our attention back to handling
		 * the membership, payment, and customer locally.
		 *
		 * For sanity reasons, stripe variants of data type
		 * such as a Stripe\Customer instance, will be
		 * hold by variables stating with s_ (e.g. s_customer)
		 *
		 * First, we need to check for a valid payment intent.
		 */
		$payment_intent_id = $payment->get_meta('stripe_payment_intent_id');

		if (empty($payment_intent_id)) {

			throw new \Exception(__('Missing Stripe payment intent, please try again or contact support if the issue persists.', 'wp-ultimo'), 'missing_stripe_payment_intent');

		} // end if;

		/**
		 * Ensure the correct api keys are set
		 */
		$this->setup_api_keys();

		/*
		 * To make our lives easier, let's
		 * set a couple of variables based on the order.
		 */
		$should_auto_renew = $cart->should_auto_renew();
		$is_recurring      = $cart->has_recurring();
		$is_setup_intent   = false;

		/*
		 * Get the correct intent
		 * type depending on the intent ID
		 */
		if (strpos($payment_intent_id, 'seti_') === 0) {

			$is_setup_intent = true;

			$payment_intent = Stripe\SetupIntent::retrieve($payment_intent_id);

		} else {

			$payment_intent = Stripe\PaymentIntent::retrieve($payment_intent_id);

		} // end if;

		/*
		 * Retrieves the Stripe Customer
		 * or create a new one!
		 */
		$s_customer = $this->get_or_create_customer($customer->get_id(), $customer->get_user_id(), $payment_intent->customer);

		// translators: first is the customer id, then the customer email.
		$description = sprintf(__('Customer ID: %1$d - User Email: %2$s', 'wp-ultimo'), $customer->get_id(), $customer->get_email_address());

		if (strlen($description) > 350) {

			$description = substr($description, 0, 350);

		} // end if;

		/*
		 * Updates the customer on Stripe
		 * to make sure it always has the most
		 * up-to-date info.
		 */
		Stripe\Customer::update($s_customer->id, array(
			'address'     => $this->convert_to_stripe_address($customer->get_billing_address()),
			'description' => sanitize_text_field($description),
			'metadata'    => array(
				'email'       => $customer->get_email_address(),
				'user_id'     => $customer->get_user_id(),
				'customer_id' => $customer->get_id(),
			),
		));

		/*
		 * Persist payment methods.
		 *
		 * This is not really THAT mission
		 * critical, but it is a nice-to-have
		 * that being said, we'll have it happen
		 * on the sidelines.
		 */
		$payment_method = $this->save_payment_method($payment_intent, $s_customer);

		$payment_completed = $is_setup_intent || (!empty($payment_intent->charges->data[0]['id']) && 'succeeded' === $payment_intent->charges->data[0]['status']);

		$subscription = false;

		if ($payment_completed && $should_auto_renew && $is_recurring) {

			$subscription = $this->create_recurring_payment($membership, $cart, $payment_method, $s_customer);

			if (!$subscription) {
				/**
				 * Another process is already taking care of this (webhook).
				 */
				return;

			} // end if;

		} // end if;

		if ($payment_completed) {

			$payment_id = $is_setup_intent ? $payment_intent->id : sanitize_text_field($payment_intent->charges->data[0]['id']);

			$payment->set_status(Payment_Status::COMPLETED);
			$payment->set_gateway($this->get_id());
			$payment->set_gateway_payment_id($payment_id);
			$payment->save();

			$this->trigger_payment_processed($payment, $membership);

		} // end if;

		if ($subscription) {

			$membership->set_gateway($this->get_id());
			$membership->set_gateway_customer_id($s_customer->id);
			$membership->set_gateway_subscription_id($subscription->id);
			$membership->add_to_times_billed(1);
			$membership->should_auto_renew(true);

			if ($type !== 'downgrade') {

				$membership_status = $cart->has_trial() ? Membership_Status::TRIALING : Membership_Status::ACTIVE;

				$renewal_date = new \DateTime();
				$renewal_date->setTimestamp($subscription->current_period_end);
				$renewal_date->setTime(23, 59, 59);

				$stripe_estimated_charge_timestamp = $subscription->current_period_end + (2 * HOUR_IN_SECONDS);

				if ($stripe_estimated_charge_timestamp > $renewal_date->getTimestamp()) {

					$renewal_date->setTimestamp($stripe_estimated_charge_timestamp);

				} // end if;

				$expiration = $renewal_date->format('Y-m-d H:i:s');

				$membership->renew(true, $membership_status, $expiration);

			} else {

				$membership->save();

			} // end if;

		} // end if;

	} // end process_checkout;

	/**
	 * Add credit card fields.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function fields() {

		$fields = array();

		$card_options = $this->get_saved_card_options();

		if ($card_options) {

			$card_options['add-new'] = __('Add new card', 'wp-ultimo');

			$fields = array(
				'payment_method' => array(
					'type'      => 'radio',
					'title'     => __('Saved Payment Methods', 'wp-ultimo'),
					'value'     => wu_request('payment_method'),
					'options'   => $card_options,
					'html_attr' => array(
						'v-model' => 'payment_method',
					),
				)
			);

		} // end if;

		$stripe_form = new \WP_Ultimo\UI\Form('billing-address-fields', $fields, array(
			'views'     => 'checkout/fields',
			'variables' => array(
				'step' => (object) array(
					'classes' => '',
				),
			),
		));

		ob_start();

		$stripe_form->render();

		// phpcs:disable

		?>

		<div v-if="payment_method == 'add-new'">

			<div id="card-element" class="wu-mb-4">
        <!-- A Stripe Element will be inserted here. -->
      </div>

      <div class="" id="ideal-bank-element">
        <!-- A Stripe iDEAL Element will be inserted here. -->
      </div>

      <!-- Used to display Element errors. -->
			<div id="card-errors" role="alert"></div>

		</div>

		<?php

		// phpcs:enable

		return ob_get_clean();

	} // end fields;

	/**
	 * Returns the payment methods.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function payment_methods() {

		$fields = array();

		$card_options = $this->get_saved_card_options();

		if ($card_options) {

			foreach ($card_options as $payment_method => $card) {

				$fields = array(
					"payment_method_{$payment_method}" => array(
						'type'          => 'text-display',
						'title'         => __('Saved Cards', 'wp-ultimo'),
						'display_value' => $card,
					)
				);

			} // end foreach;

		} // end if;

		return $fields;

	} // end payment_methods;

	/**
	 * Get the saved Stripe payment methods for a given user ID.
	 *
	 * @since 2.0.0
	 *
	 * @throws \Exception, When info is wrong.
	 * @throws \Exception When info is wrong 2.
	 * @return PaymentMethod[]|array
	 */
	public function get_user_saved_payment_methods() {

		$customer = wu_get_current_customer();

		if (!$customer) {

			return array();

		} // end if;

		$customer_id = $customer->get_id();

		try {
			/*
			 * Declare static to prevent multiple calls.
			 */
			static $existing_payment_methods;

			if (!is_null($existing_payment_methods) && array_key_exists($customer_id, $existing_payment_methods)) {

				return $existing_payment_methods[$customer_id];

			} // end if;

			$customer_payment_methods = array();

			$stripe_customer_id = \WP_Ultimo\Models\Membership::query(array(
				'customer_id' => $customer_id,
				'search'      => 'cus_*',
				'fields'      => array('gateway_customer_id'),
			));

			$stripe_customer_id = current(array_column($stripe_customer_id, 'gateway_customer_id'));

			/**
			 * Ensure the correct api keys are set
			 */
			$this->setup_api_keys();

			$payment_methods = Stripe\PaymentMethod::all(array(
				'customer' => $stripe_customer_id,
				'type'     => 'card'
			));

			foreach ($payment_methods->data as $payment_method) {

				$customer_payment_methods[$payment_method->id] = $payment_method;

			} // end foreach;

			$existing_payment_methods[$customer_id] = $customer_payment_methods;

			return $existing_payment_methods[$customer_id];

		} catch (\Throwable $e) {

			return array();

		} // end try;

	} // end get_user_saved_payment_methods;

} // end class Stripe_Gateway;
