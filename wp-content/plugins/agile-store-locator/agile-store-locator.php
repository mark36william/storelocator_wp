<?php
/**
 *
 * @link              https://agilestorelocator.com/
 * @since             1.0.0
 * @package           AgileStoreLocator
 *
 * Plugin Name:       Agile Store Locator
 * Plugin URI:        https://agilestorelocator.com
 * Description:       Agile Store Locator (Pro Version 4.8.28) is a Premium Store Finder Plugin designed to offer you immediate access to all the best stores in your local area. It enables you to find the very best stores and their location thanks to the power of Google Maps.
 * Version:           4.8.28
 * Author:            AGILELOGIX
 * Author URI:        https://agilestorelocator.com/
 * License:           Copyrights 2023
 * Text Domain:       asl_locator
 * Domain Path:       /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}



if ( !class_exists( 'ASL_Store_locator' ) ) {

  class ASL_Store_locator {
        
    /**
     * Class constructor
     */          
    function __construct() {
                                
        $this->define_constants();
        $this->includes();

        register_activation_hook( __FILE__, array( $this, 'activate') );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate') );
    }
    
    /**
     * Setup plugin constants.
     *
     * @since 1.0.0
     * @return void
     */
    public function define_constants() {

      global $wpdb;

      $upload_dir  = wp_upload_dir();
      
      define( 'ASL_PLUGIN', 'agile-store-locator');
      define( 'ASL_URL_PATH', plugin_dir_url( __FILE__ ) );
      define( 'ASL_PLUGIN_PATH', plugin_dir_path(__FILE__) );
      define( 'ASL_BASE_PATH', dirname( plugin_basename( __FILE__ ) ) );
      define( 'ASL_PREFIX', $wpdb->prefix."asl_" );
      define( 'ASL_CVERSION', "4.8.28" );
      define( 'ASL_UPLOAD_DIR', $upload_dir['basedir'].'/'.ASL_PLUGIN.'/' );
      define( 'ASL_UPLOAD_URL', $upload_dir['baseurl'].'/'.ASL_PLUGIN.'/' );
      define( 'ASL_DEBUG', true );

      //  User Permission
      if ( ! defined( 'ASL_PERMISSION' ) ) {
        define('ASL_PERMISSION', 'edit_pages');
      }
    }
    
    /**
     * Include the required files.
     *
     * @since 1.0.0
     * @return void
     */
    public function includes() {

      require_once ASL_PLUGIN_PATH . 'includes/plugin.php';
      
      $asl_core = new \AgileStoreLocator\Plugin();
      $asl_core->run();
    }
    

    /**
     * The code that runs during plugin activation.
     */
    public function activate() {
      
      \AgileStoreLocator\Activator::activate();

      //  Copy the Assets to the uploads directory
      \AgileStoreLocator\Helper::copy_assets();
    }

    /**
     * The code that runs during plugin deactivation.
     */
    public function deactivate() {
      
      \AgileStoreLocator\Deactivator::deactivate();
    }
  }


  $asl_instance = new ASL_Store_locator();
}


/**
 * Should not redeclare 
 */
if(!function_exists('asl_esc_lbl')) {


  /**
   * [asl_get_lbl description]
   * @param  [type] $key   [description]
   * @return [type]        [description]
   */
  function asl_esc_lbl($key) {
  
    return \AgileStoreLocator\Model\Label::get_label($key);
  }
}
