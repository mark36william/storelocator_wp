<?php

namespace AgileStoreLocator;


/**
 * Helper Class for the Store Locator
 */
class Helper {

  /**
   * [third_party_hooks Add the third party hooks to create the association with those plugins]
   * @param  [type] $configs [description]
   * @return [type]          [description]
   */
  public static function third_party_hooks($configs) {

    // Contact Form 7
    if(isset($configs['cf7_hook']) && $configs['cf7_hook'] == '1') {
      
      //  Initialize the class
      $cf7_class = new \AgileStoreLocator\Vendors\CF7();

      //  Add the hook
      $cf7_class->before_mail_hook();
    }

    //  When Yoast is enabled?
    if(defined('WPSEO_VERSION')) {

      //  Initialize the class
      $yoast_class = new \AgileStoreLocator\Vendors\Yoast();

      //  Add the hook
      $yoast_class->register_hook();
    }

      
    /**
     * Code for the MathRank
     */
    if(class_exists('RankMath\Sitemap\Sitemap') && interface_exists('RankMath\Sitemap\Providers\Provider')) {

      require_once ASL_PLUGIN_PATH.'includes/vendors/rank-math.php';
    
      add_filter( 'rank_math/sitemap/enable_caching', '__return_false');
      
      add_filter('rank_math/sitemap/providers', function( $external_providers ) {
        $external_providers['custom'] = new \RankMath\Sitemap\Providers\ASLRankMath();
        return $external_providers;
      });
      
    } 
  }


  /**
   * [send_email Send a email notification]
   * @param  [type] $to_email [description]
   * @param  [type] $subject  [description]
   * @param  [type] $message  [description]
   * @return [type]           [description]
   */
  public static function send_email($to_email, $subject, $message, $add_headers = null) {

    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    //  Merge headers
    if($add_headers && !empty($add_headers)) {
      $headers = array_merge($headers, $add_headers);
    } 
       
    return wp_mail($to_email, $subject, $message, $headers);
  }


  /**
   * [get_kml_files Get the list of the KML files]
   * @return [type] [description]
   */
  public static function get_kml_files() {

    $dir   = ASL_UPLOAD_DIR.'kml/';

    $files = [];

    //  scan the files
    $dir_files = scandir($dir);

    //  Must be an array
    if(is_array($dir_files))
      $files = array_slice($dir_files, 2); 

    return $files;
  }

  /**
   * [set_option set attributes for the stores as meta]
   * @param [type] $store_id     [description]
   * @param [type] $option_key   [description]
   * @param [type] $option_value [description]
   */
  public static function set_option($store_id, $option_key, $option_value) {

    global $wpdb;

    $prefix       = $wpdb->prefix."asl_";
    $option_value = sanitize_text_field($option_value);

    $result = $wpdb->query( $wpdb->prepare( "INSERT INTO {$prefix}stores_meta (`option_name`, `option_value`, `store_id`) VALUES (%s, %s, %d) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `store_id` = VALUES(`store_id`)", $option_key, $option_value, $store_id ) );

    if ( ! $result ) {
      return false;
    }
  }

  // =============================================================================
      /**
     * [set_option_alter set attributes for the stores as meta]
     * @param [type] $store_id     [description]
     * @param [type] $option_key   [description]
     * @param [type] $option_value [description]
     */
    public static function set_option_alter($store_id, $option_key, $option_value, $is_exec) {

      global $wpdb;

      $prefix       = $wpdb->prefix."asl_";
      $option_value = sanitize_text_field($option_value);

      $result = $wpdb->query( $wpdb->prepare( "INSERT INTO {$prefix}stores_meta (`option_name`, `option_value`, `store_id`, `is_exec`) VALUES (%s, %s, %d , %d) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `store_id` = VALUES(`store_id`), `is_exec` = VALUES(`is_exec`)", $option_key, $option_value, $store_id, $is_exec ) );

      if ( ! $result ) {
        return false;
      }
    }
    // =============================================================================


  /**
   * [getMarkerPath Return the Marker Path by the ID]
   * @param  [type] $marker_id [description]
   * @return [type]            [description]
   */
  public static function getMarkerPath($marker_id) {

    global $wpdb;
    
    $prefix     = $wpdb->prefix."asl_";
    $marker_id  = ($marker_id)? intval($marker_id): '1';
    $row        = $wpdb->get_row( $wpdb->prepare( "SELECT id, marker_name as name, icon FROM {$prefix}markers WHERE id = %d LIMIT 1", $marker_id ) );
    
    if($row) {
      return $row->icon;
    }

    return 'default.png';
  }


  /**
   * [get_option Get the store options]
   * @param  [type] $store_id   [description]
   * @param  [type] $option_key [description]
   * @param  [type] $default    [description]
   * @return [type]             [description]
   */
  public static function get_option($store_id, $option_key, $default = null) {

    global $wpdb;

    //  When store is null or empty
    if(!$store_id) {
      return $default;
    }

    $prefix   = $wpdb->prefix."asl_";
    $row      = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM {$prefix}stores_meta WHERE option_name = %s AND store_id = %d LIMIT 1", $option_key, $store_id ) );
  
    if(!$row) {
      return $default;
    }

    return $row->option_value;
  }

  /**
   * [get_all_meta Get all the meta of the store]
   * @param  [type] $store_id [description]
   * @return [type]           [description]
   */
  public static function get_all_meta($store_id) {

    global $wpdb;

    //  When store is null or empty
    if(!$store_id) {
      return [];
    }

    $prefix   = $wpdb->prefix."asl_";
    $all_rows = $wpdb->get_results( $wpdb->prepare( "SELECT option_name, option_value FROM {$prefix}stores_meta WHERE store_id = %d", $store_id ) );
    
    $all_metas = array();

    if($all_rows) {      
      
      foreach($all_rows as $meta)
        $all_metas[$meta->option_name] = $meta->option_value;
    }
  
    return $all_metas;    
  }



  /**
   * [get_store Get the store by the store id or where clause]
   * @param  [type] $origLat [description]
   * @param  [type] $origLon [description]
   * @param  [type] $dist    [description]
   * @return [type]          [description]
   */
  public static function get_store($store_id, $_where_clause = null) {

    global $wpdb;
    
    $ASL_PREFIX   = ASL_PREFIX;

    $store_id     = ($store_id)? intval($store_id): '';

    $where_clause = ($_where_clause)? $_where_clause: "s.`id` = {$store_id}";

    // ddl_fields in the query
    $ddl_fields_str = \AgileStoreLocator\Model\Attribute::sql_query_fields();

    //  Query the get the closest store
    $query   = "SELECT s.`id`, `title`,  `description`, `street`,  `city`,  `state`, `postal_code`, `lat`,`lng`,`phone`,  `fax`,`email`,`website`,`logo_id`,{$ASL_PREFIX}storelogos.`path`,`marker_id`,`description_2`,`open_hours`, `ordr`,$ddl_fields_str, `custom`,`slug` FROM {$ASL_PREFIX}stores as s 
          LEFT JOIN {$ASL_PREFIX}storelogos ON logo_id = {$ASL_PREFIX}storelogos.id
          LEFT JOIN {$ASL_PREFIX}stores_categories ON s.`id` = {$ASL_PREFIX}stores_categories.store_id
          WHERE ".$where_clause.';';

    $result = $wpdb->get_results($query);

    //  When we have a store in result
    if($result && isset($result[0])) {

      //  Set the row
      $aRow = $result[0];      
      
      //  Decode the Custom Fields
      if($aRow->custom) {

        $custom_fields = json_decode($aRow->custom, true);

        if($custom_fields && is_array($custom_fields) && count($custom_fields) > 0) {

          foreach ($custom_fields as $custom_key => $custom_value) {
            
            $aRow->$custom_key = $custom_value;
          }
        }
      }
      
      return $aRow;
    }

    return null;
  }



  /**
   * [show_schema Show the schema of the Store table::Debug only]
   * @return [type] [description]
   */
  public static function show_schema($table = 'stores') {

    global $wpdb;

    $prefix = $wpdb->prefix."asl_";

    $result = $wpdb->get_results("SHOW COLUMNS FROM {$prefix}".$table);

    echo '<pre>';
    print_r($result);
    die;
  }

  /**
   * [get_closest_store Get the closest store within the given distance]
   * @param  [type] $origLat [description]
   * @param  [type] $origLon [description]
   * @param  [type] $dist    [description]
   * @return [type]          [description]
   */
  public static function get_closest_store($origLat, $origLon, $dist) {

    global $wpdb;

    $prefix  = $wpdb->prefix."asl_";

    $clause  = 'HAVING distance < '.$dist;

    $query = "SELECT id,title, lat, lng, email,phone, 3956 * 2 * 
              ASIN(SQRT( POWER(SIN(($origLat - lat)*pi()/180/2),2)
              +COS($origLat*pi()/180 )*COS(lat*pi()/180)
              *POWER(SIN(($origLon-lng)*pi()/180/2),2))) 
              as distance, `street`, `city`, `state`, `postal_code` FROM {$prefix}stores WHERE 
              lng between ($origLon-$dist/cos(radians($origLat))*69) 
              and ($origLon+$dist/cos(radians($origLat))*69) 
              and lat between ($origLat-($dist/69)) 
              and ($origLat+($dist/69)) $clause
              ORDER BY distance limit 1";
    
    
    /*
    //  Query the get the closest store
    $query = "SELECT `id`,`title`, `lat`, `lng`, `email`, `phone`, `city`, `state`, `postal_code`, `street`, (6371 * ACOS( 
                COS( RADIANS({$origLat}) ) 
              * COS( RADIANS( lat ) ) 
              * COS( RADIANS( lng ) - RADIANS({$origLon}) ) 
              + SIN( RADIANS({$origLat}) ) 
              * SIN( RADIANS( lat ) )
                ) ) AS distance FROM {$prefix}stores $clause
              ORDER BY distance limit 1";
    */

    $result = $wpdb->get_results($query);

    //  When we have a store in result
    if($result) {
      return $result[0];
    }

    return null;
  }

  /**
   * [get_category_name Get the category name by the id]
   */
  public static function get_category_name($category_id) {

    global $wpdb;
    
    $ASL_PREFIX   = ASL_PREFIX;

    $category_id  = ($category_id)? intval($category_id): '0';

    $query   = "SELECT * FROM ".ASL_PREFIX."categories WHERE id = ".$category_id.';';

    $result  = $wpdb->get_results($query);

    //  When we have a category as result
    if($result && isset($result[0])) {
      return $result[0]->category_name;
    }

    return '';
  }

  /**
   * [_get_custom_fields Method to Get the Custom Fields]
   * @return [type] [description]
   */
  public static function get_custom_fields() {

    global $wpdb;
    
    //  Fields
    $fields = $wpdb->get_results("SELECT content FROM ".ASL_PREFIX."settings WHERE `type` = 'fields'");
    $fields = ($fields && isset($fields[0]))? json_decode($fields[0]->content, true): [];

    return $fields;
  }

  /**
   * [generate_tmpl_css Generate the CSS codes for the template]
   * @param  [type] $template [description]
   * @return [type]           [description]
   */
  public static function generate_tmpl_css($template) {

    $codes_json = self::get_setting('ui-template', 'template-'.$template);    

    
    if($codes_json) {

      $color_codes  = json_decode($codes_json, true);
      $class_prefix = ($template == 'wc')? '.asl-cont': 'body #asl-storelocator.asl-cont.asl-template';
      $color_html   = $class_prefix."-{$template} {";

      foreach ($color_codes as $key => $code) {
        
        if($code) {

          if(is_numeric($code)) {
            $code = $code.'px';
          }

          $color_html  .= "--sl-{$key}: {$code};";
        }
      }
      $color_html .= "}";
      
      return $color_html;
    }

    return '';
  }

  /**
   * [set_setting Set the setting value]
   * @param [type] $_value [description]
   * @param [type] $type   [description]
   */
  public static function set_setting($_value, $type, $name = '') {

    global $wpdb;

    $prefix = ASL_PREFIX;

    $type   = esc_sql($type);

    $sql    = "SELECT count(*) AS 'count' FROM {$prefix}settings WHERE `type` = '{$type}'";

    //  if  have name?
    if($name) {

      $name = esc_sql($name);

      $sql .= " AND `name` = '$name'";
    }

    // To check if the settings exist?  
    $c = $wpdb->get_results($sql);

    $data_params = array('content' => $_value, 'type'=> $type, 'name' => $name);

    //  if exist? Update it
    if($c[0]->count  >= 1) {

      $where_clause  = array('type' => $type);

      //  Add name in where clause
      if($name) {
        $where_clause['name'] = $name;        
      }

      return $wpdb->update($prefix."settings", $data_params, $where_clause);
    }
    //  Create new value
    else {
      return $wpdb->insert($prefix."settings", $data_params);
    }

    return null;
  }

  /**
   * [get_setting Get the setting value]
   * @param  [type] $type [description]
   * @return [type]       [description]
   */
  public static function get_setting($type, $name = '') {

    global $wpdb;

    $prefix = ASL_PREFIX;

    $type   = esc_sql($type);

    $sql    = "SELECT * FROM {$prefix}settings WHERE `type` = '{$type}'";

    //  if  have name?
    if($name) {

      $name = esc_sql($name);

      $sql .= " AND `name` = '$name'";
    }

    $setting_row = $wpdb->get_results($sql);

    //  Return the value if exist
    if(isset($setting_row) && isset($setting_row[0])) {

      return $setting_row[0]->content;
    }

    return 0;
  }



  /**
   * [get_config Get the Config value]
   * @param  [type] $type [description]
   * @return [type]       [description]
   */
  public static function get_configs($_columns = '*') {

    global $wpdb;

    $columns      = $_columns;
    $prefix       = ASL_PREFIX;

    //  Where clause
    $where_clause = '';

    //  For limited columns
    if(is_array($columns)) {

      array_walk($columns, function(&$x) {$x = "'$x'";});
      
      $columns      = implode(',', $columns);

      $where_clause = " WHERE `key` IN ({$columns});";
    }
    else if($columns != '*') {

      $columns      = esc_sql($columns);

      $where_clause = " WHERE `key` = '$columns';";
    }
    //  Dont call the labels
    else {

      $where_clause = " WHERE `type` NOT IN ('label');";
    }

    //  Fetch the configs
    $configs     = $wpdb->get_results("SELECT * FROM {$prefix}configs".$where_clause);

    //  Config to return
    $all_configs = array();
    
    //  Loop over the config
    foreach($configs as $_config)
      $all_configs[$_config->key] = $_config->value;


    //  Single value?
    if(is_string($_columns) && $_columns != '*') {
      return $all_configs[$columns];
    }

    return $all_configs;
  }


  /**
   * [getSettings Get the Settings]
   * @param  [type] $type [description]
   * @return [type]       [description]
   */
  public static function getSettings($type) {

    global $wpdb;


    $prefix  = $wpdb->prefix."asl_";

    $results = $wpdb->get_results("SELECT * FROM {$prefix}settings WHERE `type` = '$type'");

    if($results && isset($results[0])) {

      $results = ($results && isset($results[0]))? json_decode($results[0]->content, true): [];

      return $results;
    } 

    return null;
  }


  /**
   * [getCacheFileName Return the string of the cache files]
   * @return [type] [description]
   */
  public static function getCacheFileName() {

    $cache_settings = \AgileStoreLocator\Helper::getSettings('cache');

    $cache_names = [];

    if($cache_settings)
      foreach($cache_settings as $cache_key => $cache_value) {

        if(strpos($cache_key, '-ver') === false) {

          $cache_names[] = $cache_key;
        }
      }


    $cache_names = implode(', ', $cache_names);

    return $cache_names;
  }

  /**
   * [extensionStats Get Missing Extensions]
   * @return [type] [description]
   */
  public static function extensionStats() {

    return (extension_loaded('mbstring'))? true: false;
  }


  /**
   * [expertise_level Get the expertise level simple or expert]
   * @return [type] [description]
   */
  public static function expertise_level() {

    $level = get_option('asl-expertise');
    
    return (!$level || $level == '0')? false: true;
  }


  /**
   * [getBackupTemplates Get all the templates that are backuped in the theme root directory]
   * @return [type] [description]
   */
  public static function getBackupTemplates() {

    $files = [
      ['title' => 'Template 0', 'file' => 'template-frontend-0.php', 'image' => 'tmpl-0.png'],
      ['title' => 'Template 1', 'file' => 'template-frontend-1.php', 'image' => 'tmpl-1.png'],
      ['title' => 'Template 2', 'file' => 'template-frontend-2.php', 'image' => 'tmpl-2.png'],
      ['title' => 'Template 3', 'file' => 'template-frontend-3.php', 'image' => 'tmpl-3.png'],
      ['title' => 'Template 4', 'file' => 'template-frontend-4.php', 'image' => 'tmpl-4.png'],
      ['title' => 'Template List',      'file' => 'template-frontend-list.php', 'image' => 'tmpl-list.png'],
      ['title' => 'Search Widget',      'file' => 'asl-search.php', 'image' => 'tmpl-search.png'],
      ['title' => 'Registration Form',  'file' => 'asl-store-form.php', 'image' => 'tmpl-form.png'],
      ['title' => 'Store Detail',       'file' => 'asl-store-page.php', 'image' => 'tmpl-form.png'],
      ['title' => 'Lead Form',          'file' => 'asl-lead-form.php', 'image' => 'tmpl-lead.png'],
      ['title' => 'Store Grid',         'file' => 'asl-store-grid.php', 'image' => 'store-grid.png']
    ];

    $back_files = [];

    foreach($files as $backup_file) {
  
      if(locate_template( array ($backup_file['file']) )) {
        $back_files[] = $backup_file;
      }
    }

    return $back_files;
  }


  /**
   * [getLangControl Render a Lang Control to select lang]
   * @return [type] [description]
   */
  public static function getLangControl($json = false) {    

    $langs       = get_available_languages();


    //  is Polylang Installed?
    if(function_exists('pll_languages_list')) {

      $langs = pll_languages_list(array('fields' => 'locale'));
    }
    //  For WPML
    else {
        
      $wpml_langs  = apply_filters( 'wpml_active_languages', NULL, array( 'skip_missing' => 0 ) );

      //  is WPML installed?
      if($wpml_langs && is_array($wpml_langs)) {

        $langs = array_column($wpml_langs, 'default_locale');
      }
    }

    
    //  Must have en_US
    if(!in_array('en_US', $langs)) {

      //  Add it
      array_unshift($langs, 'en_US');
    }

    if($json) {

      return $langs;
    }

    $locale = self::get_configs(['locale']);
    
    if(!isset($locale['locale']) || !$locale['locale']) {
      return '';
    }

    $html  = '<div class="form-group asl-lang"><select id="asl-lang-ctrl" class="custom-select">';

    foreach ($langs as $lang) {

      $lang_code = ($lang == 'en_US')? '': $lang;
      $html     .= '<option value="'.$lang_code.'">'.$lang.'</option>'; 
    }

    $html .= '</select></div>';

    return $html;
  }

  /**
   * [slugify Create Slug]
   * @param  [type] $string [description]
   * @return [type]         [description]
   */
  public static function slugify($store) {

  
    $string = $store['title'].'-'.$store['city'];

    $string = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));

    return preg_replace('/-+/', '-', $string);
  }

  /**
   * [getLnt Get the Coordinates]
   * @param  [type]  $_address [description]
   * @param  [type]  $key      [description]
   * @param  boolean $debug    [description]
   * @return [type]            [description]
   */
  public static function getLnt($_address,$key,$debug = false) {

    $response = new \stdclass();
    $response->success = false;

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($_address);

    if($key) {
      $url .= '&key='.$key;
    }

    

    $error_message = null;
    $result        = wp_remote_request($url);
    

    //Debug
    if($debug) {

      return $result;
    }

    if($result) {

      if(is_object($result)) {
        //  Failed to fetch records
        return array();
      }
      else if(isset($result['body'])) {

        $result   = json_decode($result['body'], true);

        if(isset($result['results'][0])) {

          $result1=$result['results'][0];

          $result1 = array(
            'address'=> $result1['formatted_address'],
            'lat' => $result1['geometry']['location']['lat'],
            'lng' => $result1['geometry']['location']['lng']
          );
          return $result1;
        }
        else
          return array();
      }
    }

    return array();
  }


  /**
   * [validate_coordinate Check if the Coordinates are correct]
   * @return [type] [description]
   */
  public static function validate_coordinate($lat, $lng) {

    if($lat && $lng && is_numeric($lat) && is_numeric($lng)) {

        if ($lat < -90 || $lat > 90) {
          return false;
        }


        if ($lng < -180 || $lng > 180) {
          return false;
        }

        return true;
    }

    return false;
  }


  /**
   * [create_upload_dirs Create the upload directories if not exist]
   * @return [type] [description]
   */
  public static function create_upload_dirs() {

    //  CREATE DIRECTORY IF NOT EXISTS
    if(!file_exists(ASL_UPLOAD_DIR)) {
      mkdir( ASL_UPLOAD_DIR, 0775, true );
    }

    //  4 folders to copy
    $folders_to_copy = array('icon', 'Logo', 'svg', 'kml');

    //  Create sub-directories
    foreach ($folders_to_copy as $folder) {

      //  CREATE DIRECTORY IF NOT EXISTS
      if(!file_exists(ASL_UPLOAD_DIR.$folder.'/')) {

        mkdir( ASL_UPLOAD_DIR.$folder.'/', 0775, true );
      }
    }
  }


  /**
   * [tmpl_name Get the full name of the template by id]
   * @param  [type] $template [description]
   * @return [type]           [description]
   */
  private static function tmpl_name($template) {

    switch ($template) {
      
      case '0':
      case '1':
      case '2':
      case '3':
      case 'list':
        
        $template = 'template-frontend-'.$template.'.php';
        break;

      case 'search':

        $template = 'asl-search.php';
        break;

      case 'form':

        $template = 'asl-store-form.php';
        break;

      case 'store':

        $template = 'asl-store-page.php';
        break;

      case 'lead':

        $template = 'asl-lead-form.php';
        break;


      case 'grid':

        $template = 'asl-store-grid.php';
        break;

      default:
        
        $template = null;
        break;
    }


    return $template;
  }

  /**
   * [backup_template Copy the template file into theme Directory]
   * @param  [type] $template_id [description]
   * @return [type]              [description]
   */
  public static function backup_template($template_id) {
    
    $template_name      = self::tmpl_name($template_id);

    //  Validate the name
    if(!$template_name) {
      return ['success' => false, 'msg' => esc_attr__('Error! Template file is not valid.', 'asl_locator')];
    }

    //$theme_directory = get_template_directory();
    $theme_directory = get_stylesheet_directory();

    $template_file_path = ASL_PLUGIN_PATH.'public/partials/'.$template_name;

    
    if (@copy($template_file_path, $theme_directory.'/'.$template_name)) {

      return ['success' => true, 'msg' => esc_attr__('Template file moved to theme directory.', 'asl_locator')];
    }

    return ['success' => false, 'msg' => esc_attr__('Error! fail to move the file, check permisions.', 'asl_locator')];
  }


  /**
   * [backup_template Remove the template file from theme Directory]
   * @param  [type] $template_id [description]
   * @return [type]              [description]
   */
  public static function remove_template($template_id) {
    
    $template_name      = self::tmpl_name($template_id);

    //  Validate the name
    if(!$template_name) {
      return ['success' => false, 'msg' => esc_attr__('Error! Template file is not valid.', 'asl_locator')];
    }

    //$theme_directory = get_template_directory();
    $theme_directory = get_stylesheet_directory();

    
    if(!file_exists($theme_directory.'/'.$template_name)) {

      return ['success' => false, 'msg' => esc_attr__("Error! Template file doesn't exist.", 'asl_locator')];
    }

    //$template_file_path = ASL_PLUGIN_PATH.'public/partials/'.$template_name;

    if (@unlink($theme_directory.'/'.$template_name)) {

      return ['success' => true, 'msg' => esc_attr__('Template file removed from the theme directory.', 'asl_locator')];
    }

    return ['success' => false, 'msg' => esc_attr__('Error! fail to move the file, check permisions.', 'asl_locator')];
  }


  /**
   * [migrate_assets Migrate the Assets]
   * @return [type] [description]
   */
  public static function migrate_assets() {

    WP_Filesystem();

    $is_copied = false;

    //  Create Directories
    self::create_upload_dirs();

    //  Check for the dependency
    if (!function_exists('copy_dir')) {
      require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    // Check if get_plugins() function exists. This is required on the front end of the
    if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $plugins = get_plugins();


    //  Current version
    $active_version = explode('/', trim(ASL_PLUGIN_PATH, '/'));
    $active_version = $active_version[count($active_version) - 1];

    foreach ($plugins as $key => $plugin) {
      
      if($plugin['TextDomain']  == 'asl_locator' || $plugin['TextDomain']  == 'agile-store-locator') {

        $sl_path_bath = explode('/', $key);
        $sl_path_bath = $sl_path_bath[0];
        

        if($active_version != $sl_path_bath) {

          //  Copy the Folders
          $folders_to_copy = array('icon', 'Logo', 'svg');

          foreach ($folders_to_copy as $folder) {
            
            $target_dir  = ASL_UPLOAD_DIR.$folder.'/';
            $plugin_path = WP_PLUGIN_DIR.'/'.$sl_path_bath;
            $source_dir  = $plugin_path.'/public/'.$folder.'/';

            @copy_dir($source_dir, $target_dir);

            $is_copied = true;
          }
          
        }
      }
    }

    return $is_copied;
  }


  /**
   * [copy_assets Copy the assets directory to the uploads folder]
   * @return [type] [description]
   */
  public static function copy_assets() {

    WP_Filesystem();

    //  Create Directories
    self::create_upload_dirs();

    //  Check for the dependency
    if (!function_exists('copy_dir')) {
      require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    //  4 folders to copy
    $folders_to_copy = array('icon', 'Logo', 'svg');

    foreach ($folders_to_copy as $folder) {
      
      $target_dir = ASL_UPLOAD_DIR.$folder.'/';
      $source_dir = ASL_PLUGIN_PATH.'public/'.$folder.'/';

      @copy_dir($source_dir, $target_dir);
    }
  }


  /**
   * [openHours Provide string of Opening Hours]
   * @param  [type] $store [description]
   * @return [type]        [description]
   */
  public static function openHours($store, $format, $show_closed = true) {

    $timings     = '';

    $days_str    = array('sun'=> asl_esc_lbl('sun'), 'mon'=> asl_esc_lbl('mon'), 'tue'=> asl_esc_lbl('tue'), 'wed'=> asl_esc_lbl('wed'),'thu'=> asl_esc_lbl('thu'), 'fri'=> asl_esc_lbl('fri'), 'sat'=> asl_esc_lbl('sat'));

    $grouped     = ($format && $format == '2')? true: false;

    // Check format
    $time_format = \AgileStoreLocator\Helper::get_configs('time_format');

    if($store->open_hours) {

        if($grouped)
          return self::_openHourGrouped($store->open_hours);

        $open_hours = json_decode($store->open_hours);

        $timings    = '';

        if(is_object($open_hours)) {

          $open_details = array();

          foreach($open_hours as $key => $_day) {

            $key_value = '';

            if($_day && is_array($_day)) {

              $key_value = implode(',', $_day);

              $timings  .= '<div class="sl-day">';
              $timings  .= '<div class="sl-day-lbl">'.$days_str[$key].'</div><div class="sl-timings">';


              //  Loop Over the Timing
              foreach ($_day as $time) {
                
                 // Check hour format and convert it  
                $time     = self::_convertHours($time,$time_format);
                
                $timings .= '<span class="sl-time">'.$time.'</span>';
              }

              $timings  .= '</div></div>';

            }
            else if($_day == '1') {

              $key_value = $_day;
            }
            else if($show_closed)  {
              $key_value = '0';
              $timings  .= '<div class="sl-day">';
              $timings  .= '<div class="sl-day-lbl">'.$days_str[$key].'</div><div class="sl-timings">';
              $timings  .= '<span class="sl-time">'.esc_attr__( 'Closed','asl_locator').'</span>';
              $timings  .= '</div></div>';
            }

            $open_details[] = $key.'='.$key_value;
          }

          $open_hours_value = implode('|', $open_details);
        }

    }


    return $timings;
  }

  /**
   * [_convert_Hours return the storeshours in 12/24 format]
   * @param  [type] $time               [description]
   * @param  [type] $format             [description]
   * @return [type] $converted_time     [description]
   */
  private static function  _convertHours($time, $format) {

    // Check  hours format
    if ($format == '0') {
        // For 12 hours
        $time_parts = explode(" - ", $time);
        $start_time = date("g:i a", strtotime($time_parts[0]));
        $end_time = date("g:i a", strtotime($time_parts[1]));
        $converted_time = $start_time . ' - ' . $end_time;

    } else {

      // For 24 hours
      $time_parts = explode(' - ', $time);
      $start_time = date('H:i', strtotime($time_parts[0]));
      $end_time   = date('H:i', strtotime($time_parts[1]));
      $converted_time = $start_time . ' - ' . $end_time;
    }
  
      return $converted_time;
  }
  

  /**
   * [_openHourGrouped return the stores in grouped format]
   * @param  [type] $open_hours [description]
   * @return [type]             [description]
   */
  private static function _openHourGrouped($open_hours) {

    $all_days_hours = [];


    $open_hours = json_decode($open_hours, true);

    $days_words = array('sun'=> asl_esc_lbl('sun'), 'mon'=> asl_esc_lbl('mon'), 'tue'=> asl_esc_lbl('tue'), 'wed'=> asl_esc_lbl('wed'),'thu'=> asl_esc_lbl('thu'), 'fri'=> asl_esc_lbl('fri'), 'sat'=> asl_esc_lbl('sat'));

    $days      = ['1'=> 'mon', '2'=> 'tue', '3'=> 'wed', '4'=> 'thu', '5'=> 'fri', '6'=> 'sat', '0'=> 'sun' ];
    $day_keys  = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    
    $current_day  = intval(date('d'));

    $open_days = [];

    foreach($day_keys as $_kd => $k_day) {

      $hours = $open_hours[$k_day];


      $day_hours    = [];

      $is_today     = ($k_day == $current_day) ? true : false;

      // off day
      if($hours == '0')
        continue;

      //  Push the open day
      $open_days[] = $days_words[$k_day];
      
      if(is_array($hours)) {

        foreach($hours as $slot) {
  
          $day_hours[] = $slot;
        }
      }


      $all_days_hours[$k_day] = $day_hours;
    }


    // End of For loop
    $slots        = [];
    $tmp_slot     = null;


    //  empty/off day with no timings
    $empty_day = false;



    for($d = 0; $d < count($day_keys); $d++) {


      //  A new slot for the times
      if(!$tmp_slot || $empty_day || !isset($all_days_hours[$day_keys[$d]]) || json_encode($tmp_slot->hours) != json_encode($all_days_hours[$day_keys[$d]])) {
        
        
        //  make sure the times exist
        if(isset($all_days_hours[$day_keys[$d]])) {
          
          $tmp_slot = new \stdClass();

          $tmp_slot->start = $day_keys[$d];
          $tmp_slot->hours = $all_days_hours[$day_keys[$d]];

          //  add the slot
          $slots[] = $tmp_slot;

          $empty_day  = false;
        }
        //  empty day occurred
        else {
          $empty_day  = true;
        }
      }
      else {
        $tmp_slot->end = $day_keys[$d];
      }
    }


    $timings  = '';


    if(!empty($slots) && is_array($slots)) {


      foreach($slots as $item) {

        $slot_hours = '';

        foreach($item->hours as $h) {

          $slot_hours .= '<span class="sl-time">' . $h . '</span>';
        }


        $timings .= '<span class="sl-day"><span class="sl-day-lbl">'.$days_words[$item->start] . (isset($item->end)? (' - ' . $days_words[$item->end]): '') .': </span><span class="sl-timings">'. $slot_hours .'</span></span>';
      
      }

    }

    return $timings;
  }


  /**
   * [dayFullName Convert the symbol to full name]
   * @param  [type] $day [description]
   * @return [type]      [description]
   */
  public static function dayFullName($day) {

    $days_full = [
      'mon' => 'Monday',
      'tue' => 'Tuesday',
      'wed' => 'Wednesday',
      'thu' => 'Thursday',
      'fri' => 'Friday',
      'sat' => 'Saturday',
      'sun' => 'Sunday'
    ];

    return $days_full[$day];
  }

  /**
   * [googleSchema description]
   * @param  [type] $store_data [description]
   * @return [type]             [description]
   */
  public static function googleSchema($store_data) {

    //  Main instance
    $store = new \AgileStoreLocator\Schema\Generator('Store');

    //  Add the additional type
    if(isset($store_data->AdditionalType) && $store_data->AdditionalType) {

      $store->setProperty('AdditionalType', $store_data->AdditionalType);
    }

    $store->name($store_data->title);

    if($store_data->email)
      $store->email($store_data->email);

    if(isset($store_data->path) && $store_data->path)
      $store->setProperty('images', [ASL_UPLOAD_URL.'Logo/'.$store_data->path]);


    //  Address
    $address = new \AgileStoreLocator\Schema\Generator('PostalAddress', false);

    if($store_data->street)
      $address->setProperty('streetAddress', $store_data->street);

    if($store_data->city)
      $address->setProperty('addressLocality', $store_data->city);

    if($store_data->state)
      $address->setProperty('addressRegion', $store_data->state);

    if($store_data->postal_code)
      $address->setProperty('postalCode', $store_data->postal_code);

    if($store_data->country)
      $address->setProperty('addressCountry', $store_data->country);


    //  Geo
    $geo = new \AgileStoreLocator\Schema\Generator('GeoCoordinates', false);
    $geo->setProperty('latitude', $store_data->lat);
    $geo->setProperty('longitude', $store_data->lng);



    $store->setProperty('address', $address);
    $store->setProperty('geo', $geo);

    //  URL
    if(isset($store_data->website) && $store_data->website)
      $store->setProperty('url', $store_data->website);

    //  Phone
    if($store_data->phone)
      $store->setProperty('telephone', $store_data->phone);

    
    //  Opening hours
    if(isset($store_data->hours) && $store_data->hours) {

      $all_open_hours = [];

      $week_hours = json_decode($store_data->hours);
      

      foreach($week_hours as $day => $day_hours) {

        $day_full = self::dayFullName($day);
        
        //  only process array
        if(!$day_hours || !is_array($day_hours))continue;

        $hours_spec = new \AgileStoreLocator\Schema\Generator('OpeningHoursSpecification', false);
        $hours_spec->setProperty('dayOfWeek', $day_full);
        $hours_spec->setProperty('opens', $day_full);
  
        //  explode to get hours          
        $open_close = explode('-', $day_hours[0]);

        $open_close = array_map(function($o) { return trim($o); }, $open_close);

        $hours_spec->setProperty('opens', $open_close[0]);
        $hours_spec->setProperty('closes', $open_close[1]);

        $all_open_hours[] = $hours_spec;
      }

      if(count($all_open_hours) > 1) {

        $store->setProperty('openingHoursSpecification', $all_open_hours);
      }
    }


    return $store->toScript();
  }

  /**
   * [fix_backward_compatible Fix the Backward Compatibility]
   * @return [type] [description]
   */
  public static function fix_backward_compatible()
  {
    
    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    $prefix    = $wpdb->prefix."asl_";
    $table_name  = ASL_PREFIX."stores_timing";
    $store_table = ASL_PREFIX."stores";
    $database    = $wpdb->dbname;

    //Add Open Hours Column   
    $sql  = "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$store_table}' AND COLUMN_NAME = 'open_hours';";// AND TABLE_SCHEMA = '{$database}'
    $result = $wpdb->get_results($sql);
    
    if($result[0]->c == 0) {

      $wpdb->query("ALTER TABLE {$store_table} ADD open_hours text;");
    }
    else {
      
      return;
    }


    //Check if Exist
    /*
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
      return;
    }
    */
    


    //Convert All Timings
    $stores = $wpdb->get_results("SELECT s.`id` , s.`start_time`, s.`time_per_day` , s.`end_time`, t.* FROM {$store_table} s LEFT JOIN {$table_name} t ON s.`id` = t.`store_id`");
    
    foreach($stores as $timing) {

      $time_object = new \stdClass();
      $time_object->mon = array();
      $time_object->tue = array();
      $time_object->wed = array();
      $time_object->thu = array();
      $time_object->fri = array();
      $time_object->sat = array();
      $time_object->sun = array();
      

      if($timing->time_per_day == '1') {

        if($timing->start_time_0 && $timing->end_time_0) {

          $time_object->sun[] = $timing->start_time_0 .' - '. $timing->end_time_0;
        }

        if($timing->start_time_1 && $timing->end_time_1) {

          $time_object->mon[] = $timing->start_time_1 .' - '. $timing->end_time_1;
        }

        if($timing->start_time_2 && $timing->end_time_2) {

          $time_object->tue[] = $timing->start_time_2 .' - '. $timing->end_time_2;
        }


        if($timing->start_time_3 && $timing->end_time_3) {

          $time_object->wed[] = $timing->start_time_3 .' - '. $timing->end_time_3;
        }

        if($timing->start_time_4 && $timing->end_time_4) {

          $time_object->thu[] = $timing->start_time_4 .' - '. $timing->end_time_4;
        }

        if($timing->start_time_5 && $timing->end_time_5) {

          $time_object->fri[] = $timing->start_time_5 .' - '. $timing->end_time_5;
        }

        if($timing->start_time_6 && $timing->end_time_6) {

          $time_object->sat[] = $timing->start_time_6 .' - '. $timing->end_time_6;
        }
      }
      else if(trim($timing->start_time) && trim($timing->end_time)) {

        $time_object->mon[] = $time_object->sun[] = $time_object->tue[] = $time_object->wed[] = $time_object->thu[] =$time_object->fri[] = $time_object->sat[] = trim($timing->start_time) .' - '. trim($timing->end_time);
      }
      else {

        $time_object->mon = $time_object->tue = $time_object->wed = $time_object->thu = $time_object->fri = $time_object->sat = $time_object->sun = '1';
      }
      
      $time_object = json_encode($time_object);

      //Update new timings
      $wpdb->update(ASL_PREFIX."stores",
        array('open_hours'  => $time_object),
        array('id' => $timing->id)
      );
    }


    $sql = "DROP TABLE IF EXISTS `".$table_name."`;";
    $wpdb->query( $sql );
  }

  /**
   * [getaddress Get the Address]
   * @param  [type] $lat [description]
   * @param  [type] $lng [description]
   * @return [type]      [description]
   */
  public static function getaddress($lat,$lng) {

    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng);

    $json = @file_get_contents($url);
    $data=json_decode($json);
    $status = $data->status;
    if($status=="OK")
    return $data->results[0]->formatted_address;
    else
    return false;
  }


  /**
   * [getCoordinates Get the Coordinates]
   * @param  [type] $street  [description]
   * @param  [type] $city    [description]
   * @param  [type] $state   [description]
   * @param  [type] $zip     [description]
   * @param  [type] $country [description]
   * @param  [type] $key     [description]
   * @return [type]          [description]
   */
  public static function getCoordinates($street,$city,$state,$zip,$country,$key)
  {
    $params = array(
      'address' => $street,'city'=> $city, 'state'=> $state,'postcode'=> $zip, 'country' => $country
    );

    if($params['postcode'] || $params['city'] || $params['state']) {

      //$_address = $params['address'].', '.$params['postcode'].'  '.$params['city'].' '.$params['state'].' '.$params['country'];
      $_address = (($params['address'])? $params['address'].', ':'').(($params['postcode'])? $params['postcode'].', ':'').(($params['city'])? $params['city'].', ':'').' '.(($params['state'])? $params['state'].', ':'').$params['country'];
      
      $response = self::getLnt($_address, $key);
      
      if(/*$response['address'] && */isset($response['lng']) && $response['lng'] && isset($response['lat']) && $response['lat']) {
        
        return $response;
      }
      else {
        return null;
      }
    }
    else
    {
      return null;
    }
    
    return true;
  }


  /**
   * [create_zip Create a Zip File]
   * @param  array   $files       [description]
   * @param  string  $destination [description]
   * @param  boolean $overwrite   [description]
   * @return [type]               [description]
   */
  public static function create_zip($files = array(),$destination = '',$overwrite = false) {
    
    //if the zip file already exists and overwrite is false, return false
    if(file_exists($destination) && !$overwrite) { return false; }
    

    //vars
    $valid_files = array();
    //if files were passed in...
    if(is_array($files)) {
      //cycle through each file
      foreach($files as $file) {

        //make sure the file exists
        if(file_exists($file)) {
          $valid_files[] = $file;
        }
      }
    }

    //if we have good files...
    if(count($valid_files)) {
      //create the archive
      $zip = new \ZipArchive();
      //if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
      if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
        
        return false;
      }

      //add the files
      foreach($valid_files as $file) {

        $relativePath = str_replace(ASL_UPLOAD_DIR, '', $file);
        $zip->addFile($file,$relativePath);
      }
      //debug
      //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
      
      //close the zip -- done!
      $zip->close();
      
      //check to make sure the file exists
      return file_exists($destination);
    }
    else
    {
      return false;
    }
  }


  /**
   * [extract_assets Extract a Zip FIle]
   * @param  [type] $zip_path [description]
   * @return [type]           [description]
   */
  public static function extract_assets($zip_path) {

    if(!file_exists($zip_path)) {
      return false;
    }

    $zip = new \ZipArchive();
    
    if ($zip->open($zip_path) === true) {

        $allow_exts = array('jpg','png','jpeg','JPG','gif','svg','SVG');  
        
        for($i = 0; $i < $zip->numFiles; $i++) {
            
          $a_file = $zip->getNameIndex($i);
          
          $extension  = explode('.', $a_file);
          $extension  = $extension[count($extension) - 1];

          //Extract only allowed extension
          if(in_array($extension, $allow_exts)) {

            //$zip->extractTo(ASL_PLUGIN_PATH.'public', array($a_file));
            $zip->extractTo(ASL_UPLOAD_DIR, array($a_file));
          }
        }  

        //Close the connection                 
        $zip->close();   

        return true;                
    }

    return false;
  }


  /**
   * [social_icons Include the social Icons HTML]
   * @return [type] [description]
   */
  public static function social_icons() {

    // Get the custom fields
    $fields = self::get_custom_fields();

    $icons  = [];

    $html   = '';

    foreach($fields as $social_field) {
        
      //   have _url?
      if(strpos($social_field['name'], '_url')) {

        $icons[] = $social_field;
      }
    }

    //  When have icons
    if(count($icons) > 0) {

      $html .= '<ul class="sl-social-icon mt-3">';
      
      foreach($icons as $icon) {

        $icon_name  = esc_attr($icon['name']);
        $icon_label = esc_attr($icon['label']);
        $css_class  = (isset($icon['css_class']) && $icon['css_class'])? esc_attr($icon['css_class']): '';

        //  CSS Class
        if(!$css_class) {

          //  Pre-built icons
          switch ($icon_name) {
            
            case 'facebook_url':
              
              $css_class = 'icon-facebook';
              break;
            
            case 'twitter_url':
              
              $css_class = 'icon-twitter';
              break;

            case 'instagram_url':
              
              $css_class = 'icon-instagram';
              break;
          }
        }

        $html .= '{{if '.$icon_name.'}}<li><a target="_blank" href="{{:'.$icon_name.'}}"><i class="'.$css_class.'"></i><span class="sr-only">'.$icon_label.'</span></a></li>{{/if}}';
      }

      $html .= '</ul>';
    }

    return $html;    
  }


  /**
   * [get_template_views Will return both infobox and list in array]
   * @param  [type] $template_id [description]
   * @return [type]              [description]
   */
  public static function get_template_views($template_id) {

    global $wpdb;

    //  when id is missing
    if(!$template_id)
      $template_id = '0';

    //  Template ID
    $template_name = 'template-'.$template_id;

    $results       = $wpdb->get_results($wpdb->prepare("SELECT `type`,`content` FROM ".ASL_PREFIX."settings WHERE `name` = %s", $template_name), ARRAY_A );

    $contents = array('list' => null , 'infobox' => null);

    //  Get the templates
    foreach($results as $row) {
      $contents[$row['type']] = $row['content'];
    }

    //  default labels for the translation
    $listing_labels = ['view_desc' => 'View Description', 'directions' => 'Directions', 'website' => 'Website'];

    //  Loop over the types for operations
    foreach (['list','infobox'] as $tmpl_type) {

      //  No infobox for List layout
      if($template_id == 'list' && $tmpl_type == 'infobox')
        continue;
      
      $content = $contents[$tmpl_type];

      //  When HTML is missing from the data, include from files
      if(empty($contents[$tmpl_type])) {

        ob_start();
        // include simple products HTML
        include ASL_PLUGIN_PATH.'public/views/template-'.$template_id.'-'.$tmpl_type.'.html';

        $content = ob_get_contents();
        
        //  clean it
        ob_end_clean();
      }

      //  Add the Social Icons
      $content    = str_replace('{{:social_icons}}', self::social_icons() , $content);


      //  Add the translation
      preg_match_all("/\[[^\]]*\]/", $content, $translate_words);

      //  Brackets to replace
      $brackets = array('[',']');

      //  Get the first index
      if($translate_words && isset($translate_words[0]) && is_array($translate_words[0])) {
        $translate_words = $translate_words[0];
      }

      //  Loop for Translation Words
      foreach ($translate_words as $word) {
          
        $word_text  = str_replace($brackets, '', $word);

        if($word_text) {

          //  default labels
          if(in_array($word_text, $listing_labels)) {

            $word_text = asl_esc_lbl(array_search($word_text, $listing_labels));
          }
          else
            $word_text = esc_attr__($word_text, 'asl_locator');


          $content    = str_replace($word, $word_text, $content);
        }
      }


      //  Replace the Path URL
      $content    = str_replace('{{:URL}}', ASL_UPLOAD_URL , $content);

      //  put it back to array
      $contents[$tmpl_type] = $content;
    }
    

    return $contents;
  }


  /**
   * [removeFile Remove a file]
   * @param  [type] $file      [description]
   * @param  [type] $directory [description]
   * @return [type]            [description]
   */
  public static function removeFile($file, $directory) {

    $response = new \stdclass();

    //  make file path
    $file_path = $directory.$file;

    //  Validate the file?
    if(file_exists($file_path)) { 
          
      unlink($file_path);
    
      $response->success  = true;
      $response->msg      = esc_attr__('File deleted successfully.','asl_locator');
    }
    else
      $response->error = esc_attr__('Error! fail to delete the file.','asl_locator');

    return $response;
  }

}

//  Create the Alias for the ASL-WC
class_alias('\AgileStoreLocator\Helper', 'AgileStoreLocator_Helper');

?>
