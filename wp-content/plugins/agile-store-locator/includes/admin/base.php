<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}


/**
 * The base class for the admin-specific functionality of the plugin.
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Base
 */

class Base {

  /**
   * [$lang global lang attribute]
   * @var string
   */
  protected $lang = '';

  /**
   * [$max_img_width width of the logo]
   * @var integer
   */
  protected $max_img_width  = 450;

  /**
   * [$max_img_height height of the logo]
   * @var integer
   */
  protected $max_img_height = 450;


  /**
   * [$max_ico_width width of the icon]
   * @var integer
   */
  protected $max_ico_width  = 75;

  /**
   * [$max_ico_height height of the icon]
   * @var integer
   */
  protected $max_ico_height = 75;


  /**
   * [$max_image_size max upload size]
   * @var integer
   */
  protected $max_image_size = 5000000;

  /**
   * [$sub_upload_directory sub-directory upload]
   * @var [type]
   */
  public $sub_upload_directory;



  /**
   * [__construct]
   */
  public function __construct() {

    //  lang query parameter, called by ServerCall AJAX method
    $this->lang = (isset($_REQUEST['asl-lang']) && $_REQUEST['asl-lang'])? esc_sql(sanitize_text_field($_REQUEST['asl-lang'])): '';

    //  must be a valid lang code
    if(strlen($this->lang) >= 13 || $this->lang == 'en_US') {
      $this->lang = '';
    }

  }


  /**
   * [clean_input Clean the Input field]
   * @param  [type] $data [description]
   * @return [type]       [description]
   */
  protected function clean_input($data) {

    return sanitize_text_field($data);
  }

  /**
   * [clean_input_html Filter the HTML field for XSS]
   * @param  [type]  $data [description]
   * @param  boolean $html [description]
   * @return [type]        [description]
   */
  protected function clean_input_html($data) {

    // Define the allowed HTML tags and attributes
    $allowed_tags = array(
        'a'       => array(
          'href'  => array(),
          'title' => array(),
          '__target' => array('value' => '_blank')
        ),
        'strong'  => array(),
        'em'      => array(),
        'p'       => array(),
        'br'      => array(),
        'b'       => array()
    );

    // Use wp_kses() to sanitize any HTML in the value and allow only the specified tags and attributes
    return wp_kses($data, $allowed_tags);
  }


  /**
   * [clean_input_array Clean an array from XXS]
   * @param  [type] $input_array [description]
   * @return [type]              [description]
   */
  protected function clean_input_array($input_array) {

    
    // Loop through each element in the input array
    foreach($input_array as $key => $value) {

      // Sanitize the value using WordPress' built-in sanitize_text_field() function
      $input_array[$key] = sanitize_text_field($value);
    }

    // Return the sanitized input array
    return $input_array;
  }

  /**
   * [fixURL Add https:// to the URL]
   * @param  [type] $url    [description]
   * @param  string $scheme [description]
   * @return [type]         [description]
   */
  protected function fixURL($url, $scheme = 'http://') {

    if(!$url)
      return '';

    return parse_url($url, PHP_URL_SCHEME) === null ? $scheme . $url : $url;
  }



  /**
   * [_get_custom_fields Method to Get the Custom Fields]
   * @return [type] [description]
   */
  protected function _get_custom_fields() {

    global $wpdb;
    
    //  Fields
    $fields = $wpdb->get_results("SELECT content FROM ".ASL_PREFIX."settings WHERE `type` = 'fields'");
    $fields = ($fields && isset($fields[0]))? json_decode($fields[0]->content, true): [];

    if(!empty($fields)) {

      //  Filter the JSON for XSS
      $filter_fields = [];

      foreach($fields as $field_key => $field) {

        $field_key = strip_tags($field_key);

        $field['type']      = strip_tags($field['type']);
        $field['name']      = strip_tags($field['name']);
        $field['label']     = strip_tags($field['label']);
        $field['css_class'] = isset($field['css_class'])? strip_tags($field['css_class']): '';

        $filter_fields[$field_key] = $field;
      }

      $fields = $filter_fields;
    }

    return $fields;
  }




  /**
   * [uploadDirectory Set the upload directory for our plugin in uploads folder]
   * @param [type] $directory [description]
   */
  public function uploadDirectory($dir) {

    $plugin_directory = 'agile-store-locator';

    /*$dirs['subdir'] = '/'.$plugin_directory;
    $dirs['path']   = $dir['basedir'] . '/'.$plugin_directory;
    $dirs['url']    = $dir['baseurl'] . '/'.$plugin_directory;*/
   

    return array(
      'path'   => ASL_UPLOAD_DIR.$this->sub_upload_directory.'/',
      'url'    => ASL_UPLOAD_URL.$this->sub_upload_directory.'/',
      'subdir' => '/'.$plugin_directory.'/'.$this->sub_upload_directory.'/',
    ) + $dir;

    //return $dir;
  }


  /**
   * [_file_uploader description]
   * @param  [type] $source_file [description]
   * @return [type]              [description]
   */
  protected function _file_uploader($source, $folder) {

    if (!function_exists('media_handle_upload')) {
      require_once(ABSPATH . 'wp-admin/includes/image.php');
      require_once(ABSPATH . 'wp-admin/includes/file.php');
      require_once(ABSPATH . 'wp-admin/includes/media.php');
    }


    //  Make sure the upload Directories does exist
    \AgileStoreLocator\Helper::create_upload_dirs();

    //  File Name Generation
    $file_extension = pathinfo($source["name"], PATHINFO_EXTENSION);
    $real_file_name = substr(strtolower($source["name"]), 0, strpos(strtolower($source["name"]), '.'));
    $real_file_name = substr($real_file_name, 0, 15);
    $new_file_name  = $real_file_name.'-'.uniqid();
    
    //  Add File Extension
    $new_file_name .= '.'.$file_extension;

    
    //  When the file is an Image
    $is_image = ($folder == 'icon' || $folder == 'svg' || $folder == 'Logo')? true: false;
    
    
    //  For the images only
    if($is_image) {

      // Get the Size of the Image //
      $image_file = $source['tmp_name'];
      list($width, $height) = getimagesize($image_file);

      //  Too Big Size
      if ($source["size"] >  $this->max_image_size) {
        return array('error' => esc_attr__("Sorry, your file is too large.",'asl_locator'));
      }
      

      //  Supported Extensions
      $supported_extensions  = array('jpg','png','gif','jpeg');

      if($folder == 'svg' || $folder == 'icon')
        $supported_extensions[] = 'svg';

      // Not a Supported File Format
      if(!in_array(strtolower($file_extension), $supported_extensions)) {
        return array('error' => esc_attr__("Sorry, only JPG, JPEG, PNG & GIF files are allowed.",'asl_locator'));
      }
      
      $img_max_width  = ($folder == 'Logo')? $this->max_img_width: $this->max_ico_width;
      $img_max_height = ($folder == 'Logo')? $this->max_img_height: $this->max_ico_height;


      //  Width or Height Issue
      if($width > $img_max_width || $height > $img_max_height) {

        return array('error' => esc_attr__("Max image dimensions width and height is {$img_max_width} x {$img_max_height} px. Given image size is {$width} x {$height} px for {$folder}",'asl_locator'));
      }
    }
    //  For a KML File
    else if($folder == 'kml') {

      //  Support KML MIMES
      $supported_mime = array('application/vnd.google-earth.kmz', 'application/vnd.google-earth.kml+xml');
      //  $supported_mime = array('text/plain', 'text/kml', 'text/comma-separated-values');

      //  Only CSV file is allowed
      //if(strtolower($file_extension) != 'kml' || !in_array($source['type'], $supported_mime)) {
      if(strtolower($file_extension) != 'kml') {
        return array('error' => esc_attr__("Sorry, only KML files are allowed to import",'asl_locator'));
      }
    }
    else {
       return array('error' => esc_attr__("Error! unkown file is uploaded.",'asl_locator'));
    }

    //  Setup the sub-directory for the upload
    $this->sub_upload_directory = $folder;

    //  Change the Sourcer File name
    $source['name']   = $new_file_name;
    
    //  Upload Param
    $upload_overrides = array('test_form' => false);

    //  Add filter to change directory
    add_filter( 'upload_dir', array( $this, 'uploadDirectory' ));
    
    //  Move the File
    $movefile = wp_handle_upload( $source, $upload_overrides );

    // Add the saved file name
    if(isset($movefile['url'])) {

      $new_file_path = $movefile['url'];
      $new_file_path = explode('/', $new_file_path);
      $new_file_name = $new_file_path[count($new_file_path) - 1];
    }

    //  Remove that Filter
    remove_filter( 'upload_dir', array( $this, 'uploadDirectory' ));

    //  Validate the Moved File
    if ( $movefile && ! isset( $movefile['error'] ) ) {
      
      return ['success' => true, 'file_name' => $new_file_name, 'data' => $movefile];
    }
    else {
       
      return array('error' => $movefile['error']);
    }
  }

 
}