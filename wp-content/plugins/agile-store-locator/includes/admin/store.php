<?php

namespace AgileStoreLocator\Admin;

use AgileStoreLocator\Admin\Base;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * The store manager functionality of the plugin.
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Store
 */

class Store extends Base {


  /**
   * [__construct description]
   */
  public function __construct() {
    
    parent::__construct();
  }

  /**
   * [admin_delete_all_stores Delete All Stores, Logos and Category Relations]
   * @return [type] [description]
   */
  public function admin_delete_all_stores() {
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    global $wpdb;
    $prefix = ASL_PREFIX;
    
    $wpdb->query("TRUNCATE TABLE `{$prefix}stores_categories`");
    $wpdb->query("TRUNCATE TABLE `{$prefix}stores`");
    $wpdb->query("TRUNCATE TABLE `{$prefix}stores_meta`");

    apply_filters('asl_store_delete_all', []);
  
    
    $response = new \stdclass();
    $response->success = false;
      
    $response->success  = true;
    $response->msg      = esc_attr__('All Stores are deleted','asl_locator');

    echo json_encode($response);die;
  }


  /**
   * [get_store_list GET List of Stores]
   * @return [type] [description]
   */
  public function get_store_list() {
    
    global $wpdb;

    $asl_prefix = ASL_PREFIX;

    $start      = isset( $_REQUEST['iDisplayStart'])?$_REQUEST['iDisplayStart']:0;   
    $params     = isset($_REQUEST)?$_REQUEST:null;
    $categories = isset($_REQUEST['categories'])?intval($_REQUEST['categories']):null;

    
    $acolumns = array(
      ASL_PREFIX.'stores.id', ASL_PREFIX.'stores.id ',ASL_PREFIX.'stores.id ',ASL_PREFIX.'stores.id ','title','description',
      'lat','lng','street','state','city',
      'phone','email','website','postal_code','is_disabled',
      ASL_PREFIX.'stores.id','marker_id', 'logo_id', 'pending',
      ASL_PREFIX.'stores.created_on'/*,'country_id'*/
    );

    $columnsFull = array(
      ASL_PREFIX.'stores.id as id',ASL_PREFIX.'stores.id as id',ASL_PREFIX.'stores.id as id',ASL_PREFIX.'stores.id as id','title','description','lat','lng','street','state', ASL_PREFIX.'countries.country','city','phone','email','website','postal_code',ASL_PREFIX.'stores.is_disabled',ASL_PREFIX.'stores.created_on', 'pending'
    );


    //  All the prepare parameters  
    $prepare_params = [];

    $clause = array();

    if(isset($_REQUEST['filter'])) {

      //  Get all searchable columns
      $searchable_columns = \AgileStoreLocator\Model\Store::get_searchable_columns();

      //  Loop over the filters
      foreach($_REQUEST['filter'] as $key => $value) {

        //  When we have a value
        if($value != '') {

          $value    = $this->clean_input($value);
          $key      = $this->clean_input($key);

          //  $key must be within the allowed attributes
          $key = in_array($key, $searchable_columns)? $key: 'id';

          //  Scheduled Filtering
          if($key == 'scheduled') {

            switch($value) {

              // Scheduled  
              case '1':


                $clause[] = ASL_PREFIX."stores_meta.option_name = 's_date' AND option_value >= DATE_FORMAT(NOW(),'%d/%m/%Y %H:%i')";
              break;

              //  Running
              case '2':

                $clause[] = ASL_PREFIX."stores_meta.option_name = 's_date' AND option_value < DATE_FORMAT(NOW(),'%d/%m/%Y %H:%i') 
                            AND ".ASL_PREFIX."stores.id IN (SELECT store_id FROM ".ASL_PREFIX."stores_meta WHERE option_name = 'e_date' AND option_value >= DATE_FORMAT(NOW(),'%d/%m/%Y %H:%i'))";
              break;

              //  Expired
              case '3':

                $clause[] = ASL_PREFIX."stores_meta.option_name = 'e_date' AND option_value < DATE_FORMAT(NOW(),'%d/%m/%Y %H:%i') AND ".ASL_PREFIX."stores_meta.is_exec = 1";
              break;
            }

            continue;
          }
                    
          //  Disabled
          if($key == 'is_disabled')
          {
            $value = ($value == 'yes')? 1: 0;
            $clause[] = ASL_PREFIX."stores.{$key} = 1";
            continue;
          }
          //  Marker ID
          elseif($key == 'marker_id' || $key == 'logo_id')
          {
            
            $clause[] = ASL_PREFIX."stores.{$key} = %s";
            $prepare_params[] = $value;
            continue;
          }
          // Country Clause
          elseif($key == 'country')
          {
            
            $clause[] = ASL_PREFIX."countries.{$key} LIKE %s";
            $prepare_params[] = "%$value%";
            continue;
          }
          
          //  Other columns
          $prepare_params[] = "%$value%";
          $clause[]         = ASL_PREFIX."stores.{$key} LIKE %s";
        }
      } 
    }
    

    //iDisplayStart::Limit per page
    $sLimit = "";
    $displayStart = isset($_REQUEST['iDisplayStart'])?intval($_REQUEST['iDisplayStart']):0;
    
    if ( isset( $_REQUEST['iDisplayStart'] ) && $_REQUEST['iDisplayLength'] != '-1' )
    {
      $sLimit = "LIMIT ".$displayStart.", ".
        intval( $_REQUEST['iDisplayLength'] );
    }
    else
      $sLimit = "LIMIT ".$displayStart.", 20 ";

    /*
     * Ordering
     */
    $sOrder = "";
    if ( isset( $_REQUEST['iSortCol_0'] ) )
    {
      $sOrder = "ORDER BY  ";

      for ( $i=0 ; $i < intval( $_REQUEST['iSortingCols'] ) ; $i++ )
      {
        if (isset($_REQUEST['iSortCol_'.$i]))
        {
          $sort_dir = (isset($_REQUEST['sSortDir_0']) && $_REQUEST['sSortDir_0'] == 'asc')? 'ASC': 'DESC';
          $sOrder .= $acolumns[ intval( $_REQUEST['iSortCol_'.$i] )  ]." ".$sort_dir;
          break;
        }
      }
      

      if ( $sOrder == "ORDER BY" )
      {
        $sOrder = "";
      }
    }


    //  When Pending isn't required, filter the pending stores
    if(!(isset($_REQUEST['filter']) && isset($_REQUEST['filter']['pending']))) {

      $clause[] = '('.ASL_PREFIX."stores.pending IS NULL OR ".ASL_PREFIX."stores.pending = 0)";
    }

    //  When Categories filter is applied
    if($categories) {
      $clause[]    = ASL_PREFIX.'stores_categories.category_id = '.$categories;
    }
    
    //  Add the lang Filter
    $clause[] = ASL_PREFIX."stores.lang = '{$this->lang}'";

    $sWhere = implode(' AND ', $clause);
    
    if($sWhere != '')$sWhere = ' WHERE '.$sWhere;
    
    $fields = implode(',', $columnsFull);
    

    $fields  .= ',marker_id,logo_id,group_concat(category_id) as categories,'.ASL_PREFIX.'countries.country';

    //  Get the fields
    $sql      =   ("SELECT $fields FROM ".ASL_PREFIX."stores LEFT JOIN ".ASL_PREFIX."stores_categories ON ".ASL_PREFIX."stores.id = ".ASL_PREFIX."stores_categories.store_id LEFT JOIN ".ASL_PREFIX."countries ON ".ASL_PREFIX."stores.country = ".ASL_PREFIX."countries.id LEFT JOIN ".ASL_PREFIX."stores_meta ON ".ASL_PREFIX."stores .id = ".ASL_PREFIX."stores_meta.store_id");
    // $sql   =   ("SELECT $fields FROM ".ASL_PREFIX."stores LEFT JOIN ".ASL_PREFIX."stores_categories ON ".ASL_PREFIX."stores.id = ".ASL_PREFIX."stores_categories.store_id LEFT JOIN ".ASL_PREFIX."countries ON ".ASL_PREFIX."stores.country = ".ASL_PREFIX."countries.id ");

    //  Count Stores
    $sqlCount = "SELECT COUNT(DISTINCT(".ASL_PREFIX."stores.id)) 'count' FROM ".ASL_PREFIX."stores LEFT JOIN ".ASL_PREFIX."stores_categories ON ".ASL_PREFIX."stores.id = ".ASL_PREFIX."stores_categories.store_id LEFT JOIN ".ASL_PREFIX."countries ON ".ASL_PREFIX."stores.country = ".ASL_PREFIX."countries.id LEFT JOIN ".ASL_PREFIX."stores_meta ON ".ASL_PREFIX."stores .id = ".ASL_PREFIX."stores_meta.store_id";
    // $sqlCount = "SELECT COUNT(DISTINCT(".ASL_PREFIX."stores.id)) 'count' FROM ".ASL_PREFIX."stores LEFT JOIN ".ASL_PREFIX."stores_categories ON ".ASL_PREFIX."stores.id = ".ASL_PREFIX."stores_categories.store_id LEFT JOIN ".ASL_PREFIX."countries ON ".ASL_PREFIX."stores.country = ".ASL_PREFIX."countries.id ";
    
    /*
     * SQL queries
     * Get data to display
     */
    $sQuery = "{$sql} {$sWhere} GROUP BY ".ASL_PREFIX."stores.id {$sOrder} {$sLimit}";
    
    
    //  When we have prepare parameters run the prepare
    if(count($prepare_params) > 0) {
      $sQuery = $wpdb->prepare($sQuery, $prepare_params);
    }

    //  backup of query for debug
    $dQuery = $sQuery;

    $data_output = $wpdb->get_results($sQuery);
    
    //$wpdb->show_errors = true;
    //$error = $wpdb->last_error;
      
    /* Data set length after filtering */
    $sQuery = "{$sqlCount} {$sWhere}";

    //  When we have prepare parameters run the prepare
    if(count($prepare_params) > 0) {
      $sQuery = $wpdb->prepare($sQuery, $prepare_params);
    }

    $r = $wpdb->get_results($sQuery);
    $iFilteredTotal = $r[0]->count;
    
    $iTotal = $iFilteredTotal;

    /*
     * Output
     */
    $sEcho  = isset($_REQUEST['sEcho'])?intval($_REQUEST['sEcho']):1;
    $output = array(
      "sEcho" => intval($_REQUEST['sEcho']),
      "iTotalRecords" => $iTotal,
      //"query" => $dQuery,
      'orderby' => $sOrder,
      "iTotalDisplayRecords" => $iFilteredTotal,
      "aaData" => array()
    );

    /*
    if($error) {

      $output['error'] = $error;
      $output['query'] = $dQuery;
    }
    */
    
    // Check feature is enable  or not
    $store_schedule   = \AgileStoreLocator\Helper::get_configs('store_schedule');
         

    //  Loop over the stores
    foreach($data_output as $aRow) {
        
     $row = $aRow;
         
     $edit_url = 'admin.php?page=edit-agile-store&store_id='.$row->id;
     
      // When Scheduling is enabled
      if ($store_schedule && $store_schedule == '1'){

        // Check store is schedule or not
         $get_schedule_store = \AgileStoreLocator\Model\Meta::get_schedule_store($row->id);
         
         if (!empty($get_schedule_store)) {
             
            $row->is_scheduled = '1';
         }
    
         $row->scheduled = '<div class="edit-options">
             <a title="Schedule" data-target="#sl-schedule-store" class="sl-schedule-store_id" data-toggle="smodal" data-id="'.$row->id.'">
             <svg width="14" height="14">
             <use xlink:href="#i-clock"></use>
             </svg>
             </a>
           </div>';

      }  
      else {

        $row->scheduled = 'Store schedule option if off';
      }

      //  Action Row
      $row->action = '<div class="edit-options">
       <a class="row-cpy" title="Duplicate" data-id="'.$row->id.'"><svg width="14" height="14"><use xlink:href="#i-clipboard"></use></svg></a>
       <a href="'.$edit_url.'"><svg width="14" height="14"><use xlink:href="#i-edit"></use></svg></a>
       <a title="Delete" data-id="'.$row->id.'" class="glyphicon-trash"><svg width="14" height="14"><use xlink:href="#i-trash"></use></svg></a>
       </div>';

      //  Show a approve button
      if(isset($row->pending) && $row->pending == '1') {

        $row->action .= '<button data-id="'.$row->id.'" data-loading-text="'.esc_attr__('Approving...','asl_locator').'" class="btn btn-approve btn-success" type="button">'.esc_attr__('Approve','asl_locator').'</button>';
      }

      $row->check  = '<div class="custom-control custom-checkbox"><input type="checkbox" data-id="'.$row->id.'" class="custom-control-input" id="asl-chk-'.$row->id.'"><label class="custom-control-label" for="asl-chk-'.$row->id.'"></label></div>';

      //Show country with state
      /*if($row->state && isset($row->iso_code_2))
        $row->state = $row->state.', '.$row->iso_code_2;*/

      $output['aaData'][] = $row;

      //  Get the categories
      if($aRow->categories) {

        $categories_ = $wpdb->get_results("SELECT category_name FROM ".ASL_PREFIX."categories WHERE id IN ($aRow->categories)");

        $cnames = array();
        foreach($categories_ as $cat_)
          $cnames[] = $cat_->category_name;

        $aRow->categories = implode(', ', $cnames);
      }
    }

    echo wp_json_encode($output);
    die;
    
    /*switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }*/
  }


  /**
   * [validate_coordinates Validate that all the coordinates are Valid]
   * @return [type] [description]
   */
  public function validate_coordinates() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false; 

    //  initial message
    $message = esc_attr__('Success! All coordinates looks correct values', 'asl_locator');

    //  get the stores
    $invalid_stores = $wpdb->get_results("SELECT id FROM ".ASL_PREFIX."stores WHERE (lat = '' AND lng = '') OR (lat IS NULL AND lng IS NULL) OR !(lat BETWEEN -90.10 AND 90.10) OR !(lng BETWEEN -180.10 AND 180.10) OR !(lat REGEXP '^[+-]?[0-9]*([0-9]\\.|[0-9]|\\.[0-9])[0-9]*(e[+-]?[0-9]+)?$') OR !(lng REGEXP '^[+-]?[0-9]*([0-9]\\.|[0-9]|\\.[0-9])[0-9]*(e[+-]?[0-9]+)?$')");

    //  Validate the Count difference
    if($invalid_stores) {

      $coord_with_err = count($invalid_stores);

      //  When less than 10, show the numbers
      if($coord_with_err < 10) {

        //  get the store IDs
        $store_ids = array_map(function($value) { return $value->id;}, $invalid_stores);

        $store_ids = implode(',', $store_ids);

        $coord_with_err .= ' ('.$store_ids.')';
      }

      //  prepare the message
      if($coord_with_err)
        $message = esc_attr__("Error! Wrong coordinates of {$coord_with_err} stores", 'asl_locator');
    }

    // Check the Default Coordinates
    $sql = "SELECT `key`,`value` FROM ".ASL_PREFIX."configs WHERE `key` = 'default_lat' || `key` = 'default_lng'";
    $all_configs_result = $wpdb->get_results($sql);


    $all_configs = array();

    foreach($all_configs_result as $c) {
      $all_configs[$c->key] = $c->value;
    }

    $is_valid  = \AgileStoreLocator\Helper::validate_coordinate($all_configs['default_lat'], $all_configs['default_lng']);

    //  Default Lat/Lng are invalid
    if(!$is_valid) {

      $message .= '<br>'.esc_attr__('Default Lat & Default Lng values are invalid!', 'asl_locator');
    }

    //  All Passed
    if(!$invalid_stores && $is_valid) {

      $response->success = true;
    }

    $response->msg = $message;
    
    echo json_encode($response);die;
  }


  /**
   * [remove_duplicates Remove all the duplicate rows]
   * @return [type] [description]
   */
  public function remove_duplicates() {

    global $wpdb;

    $response           = new \stdclass();
    $response->success  = false;

    $asl_prefix   = ASL_PREFIX; 

    $remove_query = "DELETE s1 FROM {$asl_prefix}stores s1
                    INNER JOIN {$asl_prefix}stores s2 
                    WHERE s1.id < s2.id AND s1.title = s2.title AND s1.lat = s2.lat AND s1.lng = s2.lng AND s1.lang = s2.lang;";

    //  All Count
    $all_count   = $wpdb->get_results("SELECT COUNT(*) AS c FROM ".ASL_PREFIX."stores");

    //  Previous count
    $all_count   = $all_count[0];

    //  Remove the duplicates
    if($wpdb->query($remove_query)) {
      
      //  All Count
      $new_count     = $wpdb->get_results("SELECT COUNT(*) AS c FROM ".ASL_PREFIX."stores");

      //  Previous count
      $new_count     = $new_count[0];

      $removed       = $all_count->c - $new_count->c;

      $response->msg = $removed.' '.esc_attr__('Duplicate stores removed','asl_locator');

      $response->success = true;
    }
    else {
     
      $response->error = esc_attr__('No Duplicate deleted!','asl_locator');//$form_data
      $response->msg   = $wpdb->show_errors();
    }


    echo json_encode($response);die;
  }
  
  /**
   * [duplicate_store to  Duplicate the store]
   * @return [type] [description]
   */
  public function duplicate_store() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $store_id = isset($_REQUEST['store_id'])? intval($_REQUEST['store_id']): 0;


    $result = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."stores WHERE id = ".$store_id);   

    if($result && $result[0]) {

      $result = (array)$result[0];

      unset($result['id']);
      unset($result['created_on']);
      unset($result['updated_on']);

      //  Get Custom fields for the store to duplicate
      $custom_fields     = ($result['custom'])? json_decode($result['custom'], true): null;

      // Create a new slug
      $result['slug']    = \AgileStoreLocator\Schema\Slug::slugify($result, $custom_fields);
    

      //insert into stores table
      if($wpdb->insert( ASL_PREFIX.'stores', $result)){
        
        $response->success = true;
        $new_store_id = $wpdb->insert_id;

        //get categories and copy them
        $s_categories = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."stores_categories WHERE store_id = ".$store_id);

        /*Save Categories*/
        foreach ($s_categories as $_category) { 

          $wpdb->insert(ASL_PREFIX.'stores_categories', 
            array('store_id'=>$new_store_id,'category_id'=>$_category->category_id),
            array('%s','%s'));      
        }

        
        //SEnd the response
        $response->msg = esc_attr__('Store duplicated successfully.','asl_locator');
      }
      else
      {
        $response->error = esc_attr__('Error occurred while saving Store','asl_locator');//$form_data
        $response->msg   = $wpdb->show_errors();
      } 

    }

    echo json_encode($response);die;
  }
  
  /**
   * [add_new_store POST METHODS for Add New Store]
   */
  public function add_new_store() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $form_data    = stripslashes_deep($_REQUEST['data']);


    //  ddl controls
    $ddl_controls = \AgileStoreLocator\Model\Attribute::get_controls();
    
    // ddl fields data
    $ddl_fields   = array_column($ddl_controls, 'field');
    
    foreach($ddl_fields as $ddl_field) {

      if(isset($form_data[$ddl_field]) && is_array($form_data[$ddl_field])) {
        $form_data[$ddl_field] = implode(',', $form_data[$ddl_field]);
      }
    }
      
    //  lang
    $form_data['lang']    = $this->lang;

    //  These two fields are allowed limited HTML tags
    $description_field    = $this->clean_input_html($form_data['description']);
    $description_field_2  = $this->clean_input_html($form_data['description_2']);

    //  Custom Field
    $custom_fields        = (isset($_REQUEST['asl-custom']) && $_REQUEST['asl-custom'])? stripslashes_deep($_REQUEST['asl-custom']): null;
    $custom_fields        = ($custom_fields && is_array($custom_fields) && count($custom_fields) > 0)? $this->clean_input_array($custom_fields): null;

    //  Clean the array
    $form_data = $this->clean_input_array($form_data);


    //  Add them again after the input cleaning
    $form_data['description']   = $description_field;
    $form_data['description_2'] = $description_field_2;

    //  Add the Custom Fields, already cleaned
    $form_data['custom']  = ($custom_fields)? json_encode($custom_fields): null;

    // Prevent duplication of slug (update function)
    $form_data['slug']    = \AgileStoreLocator\Schema\Slug::slugify($form_data, $custom_fields);
    
    // Insert into stores table
    if($wpdb->insert( ASL_PREFIX.'stores', $form_data)) {

      $response->success = true;

      $store_id   = $wpdb->insert_id;
      $categories = (isset($_REQUEST['sl-category']) && $_REQUEST['sl-category'])? ($_REQUEST['sl-category']): null;

      // Save Categories
      if($categories)
        foreach ($categories as $category) {

        $wpdb->insert(ASL_PREFIX.'stores_categories', 
          array(
            'store_id'    => $store_id,
            'category_id' => $category
          ),
          array('%s','%s')
        );
      }

      //  Add a filter for asl-wc to modify the data
      if(isset($_REQUEST['sl_wc'])){
        apply_filters( 'asl_woocommerce_store_settings', $_REQUEST['sl_wc'], $store_id);
      }

      //  Add a filter for asl-grr to modify the data
      if(isset($_REQUEST['grr'])){
        apply_filters( 'asl_google_grr_store_data', $_REQUEST['grr'], $store_id);
      }


      $response->msg = esc_attr__('Store added successfully.','asl_locator');
    }
    else {

      $wpdb->show_errors  = true;
      $response->error    = esc_attr__('Error occurred while saving Store','asl_locator');
      $response->msg      = $wpdb->print_error();
    }
    
    echo json_encode($response);die;  
  }

  /**
   * [update_store update Store]
   * @return [type] [description]
   */
  public function update_store() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $form_data = stripslashes_deep($_REQUEST['data']);
    // dd($_REQUEST);
    $update_id = isset($_REQUEST['updateid'])? intval($_REQUEST['updateid']) : 0;

    //  Custom Field
    $custom_fields        = (isset($_REQUEST['asl-custom']) && $_REQUEST['asl-custom'])? stripslashes_deep($_REQUEST['asl-custom']): null;
    $custom_fields        = ($custom_fields && is_array($custom_fields) && count($custom_fields) > 0)? $this->clean_input_array($custom_fields): null;
    

    //  When Update Id is there
    if($update_id && is_numeric($update_id)) {


      //  These two fields are allowed limited HTML tags
      $description_field    = $this->clean_input_html($form_data['description']);
      $description_field_2  = $this->clean_input_html($form_data['description_2']);

      $store_data = array(
        'title'         => $form_data['title'],
        'phone'         => $form_data['phone'],
        'fax'           => $form_data['fax'],
        'email'         => $form_data['email'],
        'street'        => $form_data['street'],
        'postal_code'   => $form_data['postal_code'],
        'city'          => $form_data['city'],
        'state'         => $form_data['state'],
        'lat'           => $form_data['lat'],
        'lng'           => $form_data['lng'],
        'website'       => $this->fixURL($form_data['website']),
        'country'       => $form_data['country'],
        'is_disabled'   => (isset($form_data['is_disabled']) && $form_data['is_disabled'])?'1':'0',
        'logo_id'     => $form_data['logo_id'],
        'marker_id'   => $form_data['marker_id'],
        'logo_id'   => $form_data['logo_id'],
        'open_hours'  => $form_data['open_hours'],
        'ordr'      => $form_data['ordr'],
        'updated_on'  => date('Y-m-d H:i:s')
      );


      //  Include the dropdown control values
      $ddl_controls = \AgileStoreLocator\Model\Attribute::get_controls();
      
      // ddl fields data
      $ddl_fields   = array_column($ddl_controls, 'field');
      
      foreach($ddl_fields as $ddl_field) {

        if(isset($form_data[$ddl_field]) && is_array($form_data[$ddl_field])) {
          $form_data[$ddl_field] = implode(',', $form_data[$ddl_field]);
        }

        //  Must have a valid value
        $store_data[$ddl_field] = isset($form_data[$ddl_field])? $form_data[$ddl_field]: '';
      }
      
      //  Clean the array
      $store_data = $this->clean_input_array($store_data);

      //  Add them again after the input cleaning
      $store_data['description']   = $description_field;
      $store_data['description_2'] = $description_field_2;

      //  Add the Custom Fields, already cleaned
      $store_data['custom'] = ($custom_fields)? json_encode($custom_fields): null;

      //  Update into stores table
      $wpdb->update(ASL_PREFIX."stores", $store_data, array('id' => $update_id));

      
      $sql = "DELETE FROM ".ASL_PREFIX."stores_categories WHERE store_id = ".$update_id;
      $wpdb->query($sql);

      $categories = (isset($_REQUEST['sl-category']) && $_REQUEST['sl-category'])? ($_REQUEST['sl-category']): null;

      // Save Categories
      if($categories) {

        foreach ($categories as $category) {

          $wpdb->insert(ASL_PREFIX.'stores_categories', 
            array(
              'store_id'    => $update_id,
              'category_id' => $category
            ),
            array('%s','%s'));  
        }
      }
      

      
      // Add a filter for the Multi-Store Addons for WooCommerce
      if(isset($_REQUEST['sl_wc'])) {
         apply_filters( 'asl_woocommerce_store_settings', $_REQUEST['sl_wc'], $update_id);
      }
       

       // Add a filter for the Agile Google Reviews Rating
       if(isset($_REQUEST['grr'])){
        // dd('yes');
          apply_filters( 'asl_google_grr_store_data', $_REQUEST['grr'], $update_id);
       }

    
      
      $response->msg      = esc_attr__('Store updated successfully.','asl_locator');
      $response->success  = true;
    }
    else {

      $response->msg      = esc_attr__('Error! update id not found.','asl_locator');
    }


    echo json_encode($response);die;
  }


  /**
   * [delete_store To delete the store/stores]
   * @return [type] [description]
   */
  public function delete_store() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $multiple = isset($_REQUEST['multiple'])? $_REQUEST['multiple']: null;
    
    $delete_sql;

    //  For Multiple rows
    if($multiple) {

      $store_id      = implode(",", array_map( 'intval', $_POST['item_ids'] ));
      $delete_sql    = "DELETE FROM ".ASL_PREFIX."stores WHERE id IN (".$store_id.")";
    }
    else {

      $store_id      = intval($_REQUEST['store_id']);
      $delete_sql    = "DELETE FROM ".ASL_PREFIX."stores WHERE id = ".$store_id;
    }

    //  Delete Store?
    if($wpdb->query($delete_sql)) {

      apply_filters( 'asl_store_delete', $multiple, $store_id);
      
      $response->success = true;
      $response->msg = ($multiple)?__('Stores deleted successfully.','asl_locator'):esc_attr__('Store deleted successfully.','asl_locator');
    }
    else {
      $response->error = esc_attr__('Error occurred while saving record','asl_locator');//$form_data
      $response->msg   = $wpdb->show_errors();
    }
    
    echo json_encode($response);die;
  }


  /**
   * [store_status To Change the Status of Store]
   * @return [type] [description]
   */
  public function store_status() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $status = (isset($_REQUEST['status']) && $_REQUEST['status'] == '1')?'0':'1';
    
    $status_title  = ($status == '1')? esc_attr__('Disabled','asl_locator'): esc_attr__('Enabled','asl_locator'); 
    $delete_sql;

    $item_ids      = implode(",", array_map( 'intval', $_POST['item_ids'] ));
    $update_sql    = "UPDATE ".ASL_PREFIX."stores SET is_disabled = {$status} WHERE id IN (".$item_ids.")";

    if($wpdb->query($update_sql)) {

      $response->success  = true;
      $response->msg      = esc_attr__('Selected Stores','asl_locator').' '.$status_title;
    }
    else {
      $response->error = esc_attr__('Error occurred while Changing Status','asl_locator');
      $response->msg   = $wpdb->show_errors();
    }
    
    echo json_encode($response);die;
  }

  /**
   * [approve_stores Approve Stores]
   * @return [type] [description]
   */
  public function approve_stores() {

    global $wpdb;

    $response          = new \stdclass();
    $response->success = false;

    //  store to approve
    $store_id = intval($_REQUEST['store_id']);

    //  Approve the store
    if(Store::approve_store($store_id)) {

      $response->pending_count = Store::pending_store_count();

      $response->success = true;
      $response->msg     = esc_attr__('Success! Store is approved and registered into the listing.','asl_locator');
    }
    else if (!$response->error) {
      $response->error = esc_attr__('Error occurred while approving the records','asl_locator');//$form_data
    }
    
    echo json_encode($response);die;
  }

  /**
   * [approve_store Approve the store that is pending to be live]
   * @param  [type] $store_id [description]
   * @return [type]           [description]
   */
  public static function approve_store($store_id) {

    global $wpdb;

    $store   = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".ASL_PREFIX."stores WHERE id = %d", $store_id));

    //  Store is found?
    if(!$store || !isset($store[0])) {

      return ['msg' => esc_attr__('Error! Store not found.','asl_locator'), 'success' => false];
    }

    //  First index of the store
    $store = $store[0];

    //  Has valid coordinates?
    $is_valid    = \AgileStoreLocator\Helper::validate_coordinate($store->lat, $store->lng);
    $api_key     = \AgileStoreLocator\Helper::get_configs('server_key');


    //  Already approved?
    if($store->pending != '1') {
      return ['msg' => $store->title.' '.esc_attr__('Store is already approved.','asl_locator'), 'success' => false];
    }

    //  Validate the API
    if(!$is_valid && !$api_key) {
      return ['msg' => esc_attr__('Google Server API key is missing.','asl_locator'), 'success' => false];
    }


    //  Get the right coordinates
    $coordinates = ($is_valid)? ['lat' => $store->lat, 'lng' => $store->lng]: \AgileStoreLocator\Helper::getCoordinates($store->street, $store->city, $store->state, $store->postal_code, $store->country, $api_key);

    //  When we have coordinates
    if($coordinates) {

      if($wpdb->update( ASL_PREFIX.'stores', array('pending' => null, 'lat' => $coordinates['lat'], 'lng' => $coordinates['lng']), array('id'=> $store->id ))){

        //  Send a notification
        self::send_approved_email($store);

        return ['success' => true];
      }
    }
    
    //  Failed for the coordinates
    return ['msg' => esc_attr__('Error! Failed to validate for the coordinates by the Google API, validate the Server API key.','asl_locator'), 'success' => false];
  }


  /**
   * [pending_store_count Return the count of pending stores]
   * @return [type] [description]
   */
  public static function pending_store_count() {

    global $wpdb;

    //  Get the Count of the Pendng Stores
    $pending_stores = $wpdb->get_results("SELECT COUNT(*) AS c FROM ".ASL_PREFIX."stores WHERE pending = 1");

    $pending_stores = ($pending_stores && isset($pending_stores[0]))? $pending_stores[0]->c: 0;

    return $pending_stores;
  }


  /**
   * [register_notification Send the notification to the owner about registeration of the new store]
   * @param  [type] $form_data [description]
   * @param  [type] $store_id  [description]
   * @return [type]            [description]
   */
  public static function register_notification($form_data, $store_id) {

    global $wpdb;

    $all_configs = \AgileStoreLocator\Helper::get_configs(['admin_notify', 'notify_email']);
      
    //   Validate the admin notification checkbox is enabled
    if(isset($all_configs['admin_notify']) && $all_configs['admin_notify'] == '1') {

      $admin_email = (isset($all_configs['notify_email']) && $all_configs['notify_email'])? $all_configs['notify_email']: null;
      $user_email  = $form_data['email'];

      //  Check if the admin email is there
      if($admin_email) {

        //  When no-email is provided
        if(!$user_email) {
          $user_email = $admin_email;
        }

        //  Prepare the store details
        $locality = implode(', ', array($form_data['city'], $form_data['state'], $form_data['postal_code']));
        $address  = [$form_data['street'], $locality];

        if(is_array($address)) {
          $address = implode(', ', $address);
        }
        
        $address  = strip_tags(trim($address));

        $subject  = esc_attr__("Store Locator Updates! New Store Registered",'asl_locator');

        //  Rest of the fields
        $content_html = '';

        foreach ($form_data as $key => $value) {

          if(!in_array($key, array('title', 'description_2', 'country', 'is_disabled', 'logo_id', 'marker_id', 'custom', 'open_hours', 'ordr', 'pending', 'updated_on', 'lat', 'lng')) && $value) {

            $content_html .= '<p>'.self::get_field_label(sanitize_text_field($key)).': '.sanitize_text_field($value).' </p>';
          }
        }

        $message  = '<p>'.esc_attr__('New store is registered with these details.','asl_locator'). '</p><br>'.
                    '<p>'.esc_attr__('Title: ','asl_locator').strip_tags($form_data['title']).'</p>'.
                    '<p>'.esc_attr__('Address: ','asl_locator').sanitize_text_field($address).'</p>'.$content_html.
                    __('<p><a href="%verification_url%" target="_blank">Approve Store</a> to adding it in listing.</p>', 'asl_locator');


        //  Generate the code
        $activation_code = md5(uniqid());

        //  Save it as meta
        \AgileStoreLocator\Helper::set_option($store_id, 'activation_code', $activation_code);

        $message  = str_replace( '%verification_url%', self::store_activation_link($store_id, $activation_code), $message );
        
        //  Send a email notification
        \AgileStoreLocator\Helper::send_email($admin_email, $subject, $message);
      }
    }
  }



  /**
   * [send_approved_email Send a notification when store is approved]
   * @param  [type] $store [description]
   * @return [type]        [description]
   */
  public static function send_approved_email($store) {

    //  check the notification status
    $all_configs = \AgileStoreLocator\Helper::get_configs(['admin_notify']);

    if(isset($all_configs['admin_notify']) && $all_configs['admin_notify'] == '1' && $store->email) {


        $subject  = get_bloginfo().' :: '.esc_attr__("Store Approved Successfully",'asl_locator');


        $message  = '<p>'.esc_attr__('Congratulations! your registered store has been approved and is live now!.','asl_locator'). '</p><br>'.
                    '<p>'.esc_attr__('Title: ','asl_locator').sanitize_text_field($store->title).'</p>';

        //  Send a email notification
        \AgileStoreLocator\Helper::send_email($store->email, $subject, $message);
    }
  }

  /**
   * [verify_store_link Verify the link for the store and approve it]
   * @param  [type] $store_id        [description]
   * @param  [type] $validation_code [description]
   * @return [type]                  [description]
   */
  public static function verify_store_link($store_id, $validation_code) {

    $activation_code = \AgileStoreLocator\Helper::get_option($store_id, 'activation_code');

    //  When the code match, remove it from pending state
    if($activation_code && $activation_code == $validation_code) {
      
      $results = self::approve_store($store_id);

      if($results['success']) {

        echo esc_attr__('Success! Store has been approved.','asl_locator');
      }
      else
        echo $results['msg'];

      die;
    }
  }


  /**
   * [store_activation_link Generate a link to activate the store]
   * @param  [type] $store_id        [description]
   * @param  [type] $activation_code [description]
   * @return [type]                  [description]
   */
  public static function store_activation_link($store_id, $activation_code){

    return admin_url( 'admin-ajax.php' ).'?action=asl_approve_store&sl-store='.$store_id.'&sl-verify='.$activation_code;
  }


  /**
   * [get_field_label Return the field label]
   * @param  [type] $name [description]
   * @return [type]       [description]
   */
  private static function get_field_label($name) {

    $label_text = $name;

    switch ($name) {
      
      case 'title':
        
        $label_text = esc_attr__('Title','asl_locator');
        break;

      case 'description':
        
        $label_text = esc_attr__('Description','asl_locator');
        break;

      case 'phone':
        
        $label_text = esc_attr__('Phone','asl_locator');
        break;

      case 'fax':
        
        $label_text = esc_attr__('Fax','asl_locator');
        break;

      case 'email':
        
        $label_text = esc_attr__('Email','asl_locator');
        break;

      case 'street':
        
        $label_text = esc_attr__('Street','asl_locator');
        break;

      case 'postal_code':
        
        $label_text = esc_attr__('Postal Code','asl_locator');
        break;

      case 'city':
        
        $label_text = esc_attr__('City','asl_locator');
        break;

      case 'state':
        
        $label_text = esc_attr__('State','asl_locator');
        break;

      case 'website':
        
        $label_text = esc_attr__('Website','asl_locator');
        break;
    }

    return $label_text;
  }
  

  /**
       * [schedule_the_store Schedule the store]
       */
      public function schedule_the_store() {

        global $wpdb;
        $prefix = ASL_PREFIX;

        $data = [];
        // Data
        $data['s_date']       = isset($_POST['sdate'])? $this->clean_input($_POST['sdate']): null;
        $data['e_date']       = isset($_POST['edate'])? $this->clean_input($_POST['edate']): null;
        $disable_switch       = isset($_POST['disable_switch'])? $this->clean_input($_POST['disable_switch']): null;
        $store_id             = isset($_POST['store_id'])? $this->clean_input($_POST['store_id']): null;
        
  
        //  Response
        $response  = new \stdclass();
        $response->success = false;


        foreach ($data as $option_key => $option_value) {

          // Set store meta
          \AgileStoreLocator\Helper::set_option_alter($store_id, $option_key, $option_value, 0);
        }

        // Enable / Disable store status db update 
        $data_params  = array('is_disabled' => $disable_switch);
        $where_clause = array('id' => $store_id);
        $wpdb->update($prefix."stores", $data_params, $where_clause);

        $response->msg     = esc_attr__("Store has been scheduled successfully.",'asl_locator');
        $response->success = true;


        echo json_encode($response);die;    
      }
}