<?php

namespace AgileStoreLocator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AgileStoreLocator autoloader.
 *
 * AgileStoreLocator autoloader handler class is responsible for loading the different
 * classes needed to run the plugin.
 *
 * @since 4.7.22
 */
class Autoloader {

	/**
	 * Classes map.
	 *
	 * Maps AgileStoreLocator classes to file names.
	 *
	 * @since 4.7.22
	 * @access private
	 * @static
	 *
	 * @var array Classes used by AgileStoreLocator.
	 */
	private static $classes_map;

	/**
	 * Classes aliases.
	 *
	 * Maps AgileStoreLocator classes to aliases.
	 *
	 * @since 4.7.22
	 * @access private
	 * @static
	 *
	 * @var array Classes aliases.
	 */
	private static $classes_aliases;

	/**
	 * Default path for autoloader.
	 *
	 * @var string
	 */
	private static $default_path;

	/**
	 * Default namespace for autoloader.
	 *
	 * @var string
	 */
	private static $default_namespace;

	/**
	 * Run autoloader.
	 *
	 * Register a function as `__autoload()` implementation.
	 *
	 * @param string$default_path
	 * @param string $default_namespace
	 *
	 * @since 4.7.22
	 * @access public
	 * @static
	 */
	public static function run($_path = '', $_namespace = '' ) {
		

		self::$default_path 			= ($_path)? $_path: ASL_PLUGIN_PATH;
		self::$default_namespace 	= ($_namespace)? $_namespace: __NAMESPACE__;

		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}


	public static function get_classes_map() {
		if ( ! self::$classes_map ) {
			self::init_classes_map();
		}

		return self::$classes_map;
	}

	private static function init_classes_map() {
		
		self::$classes_map = [
			//'Loader' => 'includes/loader.php'
		];
	}

	/**
	 * Normalize Class Name
	 *
	 * Used to convert control names to class names.
	 *
	 * @param $string
	 * @param string $delimiter
	 *
	 * @return mixed
	 */
	private static function normalize_class_name( $string, $delimiter = ' ' ) {
		return ucwords( str_replace( '-', '_', $string ), $delimiter );
	}

	

	/**
	 * Load class.
	 *
	 * For a given class name, require the class file.
	 *
	 * @since 4.7.22
	 * @access private
	 * @static
	 *
	 * @param string $relative_class_name Class name.
	 */
	private static function load_class( $relative_class_name ) {
		
		$classes_map = self::get_classes_map();

		if ( isset( $classes_map[ $relative_class_name ] ) ) {
			$filename = self::$default_path . '/' . $classes_map[ $relative_class_name ];
		} else {
			$filename = strtolower(
				preg_replace(
					[ '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$relative_class_name
				)
			);

			$filename = self::$default_path.'includes/' . $filename . '.php';
		}

		if ( is_readable( $filename ) ) {
			require $filename;
		}
	}

	/**
	 * Autoload.
	 *
	 * For a given class, check if it exist and load it.
	 *
	 * @since 4.7.22
	 * @access private
	 * @static
	 *
	 * @param string $class Class name.
	 */
	private static function autoload( $class ) {

		if ( 0 !== strpos( $class, self::$default_namespace . '\\' ) ) {
			return;
		}

		$relative_class_name = preg_replace( '/^' . self::$default_namespace . '\\\/', '', $class );

		$final_class_name = self::$default_namespace . '\\' . $relative_class_name;


		/*if(!in_array($class, ['AgileStoreLocator\Loader', 'AgileStoreLocator\i18n'])) {
		}*/

		if ( ! class_exists( $final_class_name ) ) {
			self::load_class( $relative_class_name );
		}
		//	Class not exist, log error todo
		else {


		}
	}
}
