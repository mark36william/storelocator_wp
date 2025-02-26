<?php

namespace AgileStoreLocator;

/**
 * Fired during plugin deactivation
 *
 * @link       https://agilelogix.com
 * @since      1.0.0
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes
 * @author     AgileLogix <support@agilelogix.com>
 */
class Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

    //  Clear the cron job
    wp_clear_scheduled_hook( 'asl_import_files' );
	}

	/**
	 * [feedback_box_html render the feedback box for the plugin]
	 * @return [type] [description]
	 */
	public function feedback_box_html() {

	}

}
