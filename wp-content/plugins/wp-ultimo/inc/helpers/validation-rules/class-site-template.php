<?php
/**
 * Adds a validation rules that allows us to check if a given parameter is unique.
 *
 * @package WP_Ultimo
 * @subpackage Helpers/Validation_Rules
 * @since 2.0.4
 */

namespace WP_Ultimo\Helpers\Validation_Rules;

use \WP_Ultimo\Dependencies\Rakit\Validation\Rule;
use \WP_Ultimo\Checkout\Checkout;
use \WP_Ultimo\Database\Sites\Site_Type;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Validates template sites.
 *
 * @since 2.0.4
 */
class Site_Template extends Rule {

	/**
	 * Error message to be returned when this value has been used.
	 *
	 * @since 2.0.4
	 * @var string
	 */
	protected $message = '';

	/**
	 * Parameters that this rule accepts.
	 *
	 * @since 2.0.4
	 * @var array
	 */
	protected $fillableParams = array(); // phpcs:ignore

	/**
	 * Performs the actual check.
	 *
	 * @since 2.0.4
	 *
	 * @param mixed $template_id Value being checked.
	 * @return boolean
	 */
	public function check($template_id) : bool { // phpcs:ignore

		$template_id = absint($template_id);

		if (!$template_id) {

			return true;

		} // end if;

		$site = wu_get_site($template_id);

		if (!$site || ($site->get_type() !== Site_Type::SITE_TEMPLATE && $site->get_type() !== Site_Type::CUSTOMER_OWNED)) {

			$this->message = __('The Template ID does not correspond to a valid Template', 'wp-ultimo');

			return false;

		} // end if;

		if ($site->get_type() === Site_Type::CUSTOMER_OWNED) {

			if (!wu_get_setting('allow_own_site_as_template')) {

				$this->message = __('You can not use your sites as template', 'wp-ultimo');

				return false;

			} // end if;

			$customer = wu_get_current_customer();

			if (!$customer || $site->get_customer_id() !== $customer->get_id()) {

				$this->message = __('The selected template is not available.', 'wp-ultimo');

				return false;

			} // end if;

			return true;

		} // end if;

		$allowed_templates = false;

		$product_ids_or_slugs = Checkout::get_instance()->request_or_session('products', array());

		$product_ids_or_slugs = array_unique($product_ids_or_slugs);

		if ($product_ids_or_slugs) {

			$products = array_map('wu_get_product', $product_ids_or_slugs);

			$limits = new \WP_Ultimo\Objects\Limitations();

			list($plan, $additional_products) = wu_segregate_products($products);

			$products = array_merge(array($plan), $additional_products);

			foreach ($products as $product) {

				$limits = $limits->merge($product->get_limitations());

			} // end foreach;

			$allowed_templates = $limits->site_templates->get_available_site_templates();

		} // end if;

		if (is_array($allowed_templates) && !in_array($template_id, $allowed_templates)) { // phpcs:ignore

			$this->message = __('The selected template is not available for this product.', 'wp-ultimo');

			return false;

		} // end if;

		return true;

	} // end check;

} // end class Site_Template;
