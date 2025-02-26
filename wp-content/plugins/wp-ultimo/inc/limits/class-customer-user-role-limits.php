<?php
/**
 * Handles limitations to the customer user role.
 *
 * @todo We need to move posts on downgrade.
 * @package WP_Ultimo
 * @subpackage Limits
 * @since 2.0.10
 */

namespace WP_Ultimo\Limits;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Handles limitations to the customer user role.
 *
 * @since 2.0.0
 */
class Customer_User_Role_Limits {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * Runs on the first and only instantiation.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init() {

		add_action('in_admin_header', array($this, 'block_new_user_page'));

		add_action('wu_async_after_membership_update_products', array($this, 'update_site_user_roles'));

		add_filter('editable_roles', array($this, 'filter_editable_roles'));

		if (!wu_get_current_site()->has_module_limitation('customer_user_role')) {

			return;

		} // end if;

	} // end init;

	/**
	 * Block new user page if limit has reached.
	 *
	 * @since 2.0.20
	 */
	public function block_new_user_page() {

		if (is_super_admin()) {

			return;

		} // end if;

		$screen = get_current_screen();

		if (!$screen || $screen->id !== 'user') {

			return;

		} // end if;

		if (!empty(get_editable_roles())) {

			return;

		} // end if;

		$message = __('You reached your membership users limit.', 'wp-ultimo');

		/**
		 * Allow developers to change the message about the membership users limit
		 *
		 * @param string                      $message    The message to print in screen.
		 */
		$message = apply_filters('wu_users_membership_limit_message', $message);

		wp_die($message, __('Limit Reached', 'wp-ultimo'), array('back_link' => true));

	} // end block_new_user_page;

	/**
	 * Filters editable roles offered as options on limitations.
	 *
	 * @since 2.0.10
	 *
	 * @param array $roles The list of available roles.
	 * @return array
	 */
	public function filter_editable_roles($roles) {

		if (!wu_get_current_site()->has_module_limitation('users') || is_super_admin()) {

			return $roles;

		} // end if;

		$users_limitation = wu_get_current_site()->get_limitations()->users;

		foreach ($roles as $role => $details) {

			$limit = $users_limitation->{$role};

			if (property_exists($limit, 'enabled') && $limit->enabled) {

				$user_list = get_users(array('role' => $role));

				$count = (int) count($user_list);

				$limit = (int) wu_get_current_site()->get_limitations()->users->{$role}->number;

				if (0 !== $limit && $count >= $limit) {

					unset($roles[$role]);

				} // end if;

			} else {

				unset($roles[$role]);

			} // end if;

		} // end foreach;

		return $roles;

	} // end filter_editable_roles;

	/**
	 * Updates the site user roles after a up/downgrade.
	 *
	 * @since 2.0.10
	 *
	 * @param int $membership_id The membership upgraded or downgraded.
	 * @return void
	 */
	public function update_site_user_roles($membership_id) {

		$membership = wu_get_membership($membership_id);

		if ($membership) {

			$customer = $membership->get_customer();

			if (!$customer) {

				return;

			} // end if;

			$sites = $membership->get_sites(false);

			$role = $membership->get_limitations()->customer_user_role->get_limit();

			foreach ($sites as $site) {

				add_user_to_blog($site->get_id(), $customer->get_user_id(), $role);

			} // end foreach;

		} // end if;

	} // end update_site_user_roles;

} // end class Customer_User_Role_Limits;
