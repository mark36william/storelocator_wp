<?php

namespace AgileStoreLocator\Schema;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://agilelogix.com
 * @since      1.0.0
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes
 * @author     AgileLogix <support@agilelogix.com>
 */

class Feed {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $AgileStoreLocator    The ID of this plugin.
   */
  private $AgileStoreLocator;


  /**
   * [$instance description]
   * @var [type]
   */
  private static $instance;

  /**
   * [__construct description]
   */
  private function __construct(){
    $this->setupActions();
  }


  /**
   * [getInstance description]
   * @return [type] [description]
   */
  public static function getInstance(){
    
    if ( !isset(self::$instance) ){
      $c = __CLASS__;
      self::$instance = new $c;
    }
    
    return self::$instance;
  }

  /**
   * [__clone description]
   * @return [type] [description]
   */
  public function __clone(){
    trigger_error('Clone is not allowed.', E_USER_ERROR);
  }

  /**
   * [setupActions description]
   * @return [type] [description]
   */
  private function setupActions(){
    add_action('init', array($this, 'addFeed'));
    add_action('init', array($this, 'registerImageSizes'));
  }

  /**
   * [registerImageSizes description]
   * @return [type] [description]
   */
  public function registerImageSizes(){
    add_image_size('mobile-small', 120, 80, true);
    add_image_size('mobile-normal', 300, 200, true);
  }

  /**
   * [displayStores description]
   * @return [type] [description]
   */
  public function displayStores(){

    global $wpdb;
    

    header('Content-Type: application/xml; charset=utf-8');

    //  Get the config
    $configs = \AgileStoreLocator\Helper::get_configs(['rewrite_slug']);
    
    $xml                = new \DOMDocument();
    $xml->formatOutput  = true;
    $xml->preserveWhiteSpace = false;
    $xml->encoding      = 'utf-8';
    $root               = $xml->appendChild( $xml->createElement('stores') );
    
    $ASL_PREFIX = ASL_PREFIX;


    // ddl_fields in the query
    $ddl_fields_str = \AgileStoreLocator\Model\Attribute::sql_query_fields();


    $query   = "SELECT s.`id`, `title`,  `description`, `street`,  `city`,  `state`, `postal_code`, `lat`,`lng`,`phone`,  `fax`,`email`,`website`,`logo_id`,{$ASL_PREFIX}storelogos.`path`,`marker_id`,`description_2`,`open_hours`, `ordr`,$ddl_fields_str, `custom`,`slug`,
          group_concat(category_id) as categories FROM {$ASL_PREFIX}stores as s 
          LEFT JOIN {$ASL_PREFIX}storelogos ON logo_id = {$ASL_PREFIX}storelogos.id
          LEFT JOIN {$ASL_PREFIX}stores_categories ON s.`id` = {$ASL_PREFIX}stores_categories.store_id
          
          WHERE (is_disabled is NULL || is_disabled = 0) AND (`lat` != '' AND `lng` != '') 
          GROUP BY s.`id` ORDER BY `title` ";

    $query .= " LIMIT 10000";

    
    $all_results = $wpdb->get_results($query);
    

    $site_url    = site_url('/');

    //  Loop over the results
    foreach ($all_results as $post) {
    
      $entry = $root->appendChild( $xml->createElement('store') );
      $entry->appendchild( $xml->createElement('title', htmlspecialchars($post->title)) );
      //$entry->appendChild( $this->createTextElement($xml, 'title', $this->sanitizeText( $post->title) ) );
        

      //if($post->street)$entry->appendchild( $xml->createElement('street', $this->sanitizeText($post->street)) );
      $entry->appendchild( $xml->createElement('city', $post->city) );
      $entry->appendchild( $xml->createElement('state', $post->state) );
      $entry->appendchild( $xml->createElement('postal_code', $post->postal_code) );
      $entry->appendchild( $xml->createElement('lat', $post->lat) );
      $entry->appendchild( $xml->createElement('lng', $post->lng) );

      if($post->phone)
        $entry->appendChild( $xml->createElement('phone', $post->phone ) );

      if($post->email)
        $entry->appendChild( $xml->createElement('email', $post->email ) );
      
      if($post->website)
        $entry->appendChild( $this->createTextElement($xml, 'website', $post->website  ) );

      if($post->description)
        $entry->appendChild( $this->createTextElement($xml, 'description', $post->description) );    

      if($post->description_2)
        $entry->appendChild( $this->createTextElement($xml, 'description_2', $post->description_2) );


      //  Detail Pages
      if(isset($configs['rewrite_slug']) && $configs['rewrite_slug']) {

        $entry->appendchild( $xml->createElement('url', $site_url.$configs['rewrite_slug'].'/'.$post->slug) );
      }

    }

    echo $xml->saveXML();
    exit;
  }

  /**
   * [createTextElement description]
   * @param  [type] $document [description]
   * @param  [type] $key      [description]
   * @param  [type] $value    [description]
   * @return [type]           [description]
   */
  private function createTextElement( $document, $key, $value ){
    $element = $document->createElement( $key );
    $element->appendChild( $document->createCDATASection( $value ) );
    return $element;
  }

  /**
   * [getThumbUrl description]
   * @param  [type] $size [description]
   * @return [type]       [description]
   */
  public function getThumbUrl( $size ){
    global $post;
    $thumb = get_post_thumbnail_id( $post->ID );
    if ( ! $thumb )
      return '';
    $img = wp_get_attachment_image_src( $thumb, $size );
    return $img[0];
  }


  /**
   * [sanitizeText description]
   * @param  [type] $text [description]
   * @return [type]       [description]
   */
  public function sanitizeText( $text ){
    $text = str_replace('&nbsp;', '', $text);
    $text = html_entity_decode( $text );
    $text = strip_shortcodes( $text );
    $text = strip_tags( $text );
    $text = trim( $text );
    // $text = utf8_encode( $text );
    return $text;
  }

  /**
   * [theContentFilter description]
   * @param  [type] $text [description]
   * @return [type]       [description]
   */
  public function theContentFilter( $text ){
    return $this->sanitizeText( $text );
  }

  /**
   * [addFeed description]
   */
  public function addFeed(){

    add_feed('mobile-news', array($this, 'displayStores'));
  }

  /**
   * [rewriteRules description]
   * @param  [type] $wp_rewrite [description]
   * @return [type]             [description]
   */
  public function rewriteRules( $wp_rewrite ){
    $new_rules = array(
      'feed/(.+)' => 'index.php?feed='. $wp_rewrite->preg_index( 1 )
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
  }

  /**
   * [installationHook description]
   * @return [type] [description]
   */
  public function installationHook(){
    
    if ( function_exists('add_feed') )
      $this->addFeed();
    
    flush_rewrite_rules();
  }


}

