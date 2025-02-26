<?php
/**
 * Development Toolkit
 *
 * @package WP_Ultimo
 * @subpackage Development
 * @since 2.0.11
 */

namespace WP_Ultimo\Development;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Toolkit API Helpers
 *
 * @since 2.0.11
 */
class Toolkit {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * The parameter used to activate sandbox.
	 */
	const LISTENER_PARAM = 'development';

	/**
	 * List of registered WordPress hooks.
	 *
	 * @since 2.0.11
	 * @var array
	 */
	protected $wp_hooks = array();

	/**
	 * Listeners registered.
	 *
	 * @since 2.0.11
	 * @var array
	 */
	protected $listeners = array();

	/**
	 * Keeps track of number of listeners run.
	 *
	 * @since 2.0.11
	 * @var integer
	 */
	protected $run = 0;

	/**
	 * If we should die or not.
	 *
	 * @since 2.0.11
	 * @var boolean
	 */
	protected $should_die = false;

	/**
	 * Keeps track if we already displayed the footer.
	 *
	 * @since 2.0.11
	 * @var boolean
	 */
	protected $displayed_footer = false;

	/**
	 * Initialize the development hooks and the sandbox.
	 *
	 * @since 2.0.11
	 * @return void
	 */
	public function init() {

		$this->register_default_listeners();

		$this->load_sandbox();

		add_filter('qm/collectors', array($this, 'register_collector_overview'), 1, 2);

		add_filter('qm/outputter/html', array($this, 'add_overview_panel'), 50, 2);

	} // end init;

	/**
	 * Registers the default listeners.
	 *
	 * @since 2.0.11
	 * @return void
	 */
	protected function register_default_listeners() {

		/**
		 * In the case of an empty listener name (?development)
		 * adds the listener link.
		 */
		$this->listen('index', array($this, 'render_listeners_menu'), 'init');

		/**
		 * Saves the rest endpoints to the mpb data folder
		 * on the listener save-rest-arguments.
		 */
		$this->listen('save-rest-arguments', '__return_false', 'wu_rest_get_endpoint_schema_use_cache');

		$this->listen('save-rest-arguments', array($this, 'save_route_arguments'), 'wu_rest_register_routes_general');

		$this->listen('save-rest-arguments', array($this, 'save_route_arguments'), 'wu_rest_register_routes_with_id');

	} // end register_default_listeners;

	/**
	 * Save route arguments to files to deal with opcache.
	 *
	 * @since 2.0.11
	 *
	 * @param array                      $routes Routes passed to WordPress.
	 * @param string                     $path The rest api route path.
	 * @param string                     $context The context. Can be either 'create' or 'update'.
	 * @param \WP_Ultimo\Traits\Rest_Api $manager The model manager instance.
	 * @return void
	 */
	public function save_route_arguments($routes, $path, $context, $manager) {

		$class_name = str_replace('_', '-', strtolower($path));

		$args = $manager->get_arguments_schema($context === 'update');

		file_put_contents(wu_path("/mpb/data/endpoint/.endpoint-$class_name-$context"), json_encode($args)); // phpcs:ignore

	} // end save_route_arguments;

	/**
	 * Adds a listener for development purposes.
	 *
	 * @since 2.0.11
	 *
	 * @param string   $hook The name of the listener hook.
	 * @param callable $callback The callback to be run.
	 * @param string   $wp_hook The WordPress hook to add this listener to.
	 * @param integer  $order The order to be run.
	 * @return mixed
	 */
	public function listen($hook, $callback, $wp_hook = 'wp_ultimo_load', $order = 1) {

		$this->listeners[$hook] = ($this->listeners[$hook] ?? 0) + 1;

		$this->wp_hooks[$wp_hook] = 1;

		$action = $this->get_action($hook, $wp_hook);

		$order = $this->get_order($hook, $order);

		add_action($action, function(...$arguments) use ($callback, $action, $order) {

			$timing_id = sprintf('%s_%s_%s', $action, $this->run + 1, $order);

			// phpcs:ignore
			do_action('qm/start', $timing_id); 

			$result = call_user_func_array($callback, $arguments);

			 // phpcs:ignore
			do_action('qm/stop', $timing_id);

			$this->run++;

			return $result;

		}, $order, 100);

		return $this;

	} // end listen;

	/**
	 * Sets flags for the development environment.
	 *
	 * @since 2.0.11
	 *
	 * @param array $configs The config constants.
	 * @return void
	 */
	public function config($configs = array()) {

		$allowed_configs = array(
			'QM_DARK_MODE',
			'QM_DB_EXPENSIVE',
			'QM_DISABLED',
			'QM_DISABLE_ERROR_HANDLER',
			'QM_ENABLE_CAPS_PANEL',
			'QM_HIDE_CORE_ACTIONS',
			'QM_HIDE_SELF',
			'QM_NO_JQUERY',
			'QM_SHOW_ALL_HOOKS',
		);

		foreach ($configs as $constant_name => $constant_value) {

			if (in_array($constant_name, $allowed_configs, true)) {

				// phpcs:ignore
				defined($constant_name) === false && define($constant_name, $constant_value);

			} // end if;

		} // end foreach;

	} // end config;

	/**
	 * Marks the development environment to finish execution after all listeners are run.
	 *
	 * @since 2.0.11
	 *
	 * @param string|false $should_die False to prevent it from dying, or a WordPress hook to wait before dying.
	 * @return void
	 */
	public function die($should_die = true) {

		if ($should_die === true) {

			$should_die = is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

		} // end if;

		$this->should_die = $should_die;

	} // end die;

	/**
	 * Run a registered listener.
	 *
	 * @since 2.0.11
	 *
	 * @param string $wp_hook The WordPress hook to run.
	 * @param array  $arguments The arguments passed to the WordPress hook.
	 * @return mixed
	 */
	public function run_listener($wp_hook, $arguments = array()) {

		$listener = wu_request(self::LISTENER_PARAM, 'no-dev-param');

		if ($listener === 'no-dev-param') {

			return current($arguments);

		} elseif ($listener === '') {

			$listener = 'index';

		} // end if;

		$action = $this->get_action($listener, $wp_hook);

		return do_action_ref_array($action, $arguments); // phpcs:ignore

	} // end run_listener;

	/**
	 * Loads the sandbox environment.
	 *
	 * Checks for the existence of a development.php file
	 * inside the root folder and loads it if it does.
	 *
	 * @since 2.0.11
	 * @return void
	 */
	protected function load_sandbox() {

		$dev_file = wu_path_join(dirname(WP_ULTIMO_SUNRISE_FILE, 2), 'development.php');

		if (file_exists($dev_file)) {

			/**
			 * Make the $toolkit variable available
			 * to the development.php file.
			 */
			$toolkit = $this;

			include $dev_file;

		} // end if;

		$wp_hooks = array_keys($this->wp_hooks);

		foreach ($wp_hooks as $wp_hook) {

			add_action($wp_hook, function(...$arguments) use ($wp_hook) {

				return $this->run_listener($wp_hook, $arguments);

			}, 0, 100);

		} // end foreach;

		add_action('shutdown', array($this, 'setup_query_monitor'));

		if ($this->should_die) {

			$this->dump_and_die(end($wp_hooks));

		} // end if;

	} // end load_sandbox;

	/**
	 * Setups the query monitor integration.
	 *
	 * @since 2.0.11
	 * @return void
	 */
	public function setup_query_monitor() {

		if (class_exists('\QM_Dispatchers')) {

			// phpcs:ignore
			do_action('qm/debug', sprintf('Actions with listeners: %s', $this->get_wp_hooks_list()));

			// phpcs:ignore
			do_action('qm/debug', sprintf('Listeners: %s', $this->get_listeners_list()));

			// phpcs:ignore
			$dispatcher = \QM_Dispatchers::get('html');

			if (!$dispatcher) {

				return;

			} // end if;

			$dispatcher->did_footer = true;

			if ($this->should_die && $this->run) {

				$this->enqueue_scripts($dispatcher);

			} // end if;

		} // end if;

	} // end setup_query_monitor;

	/**
	 * Registers the collector overview.
	 *
	 * @since 2.0.11
	 *
	 * @param array         $collectors The collectors array.
	 * @param \QueryMonitor $qm The Query Monitor instance.
	 * @return array
	 */
	public function register_collector_overview(array $collectors, \QueryMonitor $qm) {

		$collectors['wp-ultimo'] = new Query_Monitor\Collectors\Collector_Overview();

		return $collectors;

	} // end register_collector_overview;

	/**
	 * Adds the overview panel.
	 *
	 * @since 2.0.11
	 *
	 * @param array $output Array containing all registered output-generators.
	 * @return array
	 */
	public function add_overview_panel($output) {

		$collector = \QM_Collectors::get('wp-ultimo');

		$output['wp-ultimo'] = new Query_Monitor\Panel\Overview($collector);

		return $output;

	} // end add_overview_panel;

	/**
	 * Manually enqueues query monitor and WP Ultimo styles.
	 *
	 * @since 2.0.11
	 *
	 * @param \QM_Dispatcher $dispatcher The Query Monitor dispatcher object.
	 * @return void
	 */
	protected function enqueue_scripts($dispatcher) {

		echo sprintf('<link rel="stylesheet" id="toolkit" href="%s" type="text/css" media="all">', wu_url('inc/development/assets/development.css'));

		wp_print_styles(array(
			'wu-admin',
		));

		$dispatcher->manually_print_assets(); // phpcs:ignore

	} // end enqueue_scripts;

	/**
	 * Returns a comma-separated list of listeners.
	 *
	 * @since 2.0.11
	 * @return string
	 */
	protected function get_listeners_list() {

		$listener_names = array_keys($this->listeners);

		return implode(', ', $listener_names);

	} // end get_listeners_list;

	/**
	 * Returns a comma-separated list of WordPress hooks.
	 *
	 * @since 2.0.11
	 * @return string
	 */
	protected function get_wp_hooks_list() {

		$wp_hook_names = array_keys($this->wp_hooks);

		return implode(', ', $wp_hook_names);

	} // end get_wp_hooks_list;

	/**
	 * Dumps the development content and kill the execution.
	 *
	 * @since 2.0.11
	 *
	 * @param string $hook Hook to die on.
	 * @return void
	 */
	protected function dump_and_die($hook) {

		add_action($hook, function() use ($hook) {

			if (did_action($this->should_die) && $this->run) {

				$this->render_listeners_menu();

				do_action('shutdown'); // phpcs:ignore

				$message = sprintf('Execution killed on %s.', $hook);

				do_action('qm/info', $message); // phpcs:ignore

				die();

			} else {

				return $this->dump_and_die($this->should_die);

			} // end if;

		}, 110);

	} // end dump_and_die;

	/**
	 * Get the order of a newly added listener.
	 *
	 * @since 2.0.11
	 *
	 * @param string  $hook The listener action name.
	 * @param integer $order The order of execution.
	 * @return integer
	 */
	protected function get_order($hook, $order = 1) {

		return 10 + (absint($this->listeners[$hook]) * $order * 10) + 5;

	} // end get_order;

	/**
	 * Get the action name based on the listener hook and WP action.
	 *
	 * @since 2.0.11
	 *
	 * @param string $hook The listener action name.
	 * @param string $wp_hook The WordPress hook.
	 * @return string
	 */
	protected function get_action($hook, $wp_hook) {

		$hook = str_replace('-', '_', $hook);

		return sprintf('wu_sandbox_run_%s_%s', $hook, $wp_hook);

	} // end get_action;

	/**
	 * Render the list of listeners with links.
	 *
	 * @since 2.0.11
	 * @return void
	 */
	public function render_listeners_menu() {

		/**
		 * Make sure we display it only once.
		 */
		if ($this->displayed_footer) {

			return;

		} // end if;

		// phpcs:disable
		echo '
			<div class="wu-styling">
				<strong class="wu-block wu-mb-2 wu-mt-10">Listeners</strong>
					<ul id="listeners">';

						foreach (array_keys($this->listeners) as $listener) {

							echo sprintf(
								'<li><a href="%s">→ Listener "%s"</a></li>',
								add_query_arg(self::LISTENER_PARAM, $listener),
								$listener
							);

						} // end foreach;

				echo '
			</ul>
		</div>'; // phpcs: enable

		$this->displayed_footer = true;

	} // end render_listeners_menu;

} // end class Toolkit;
