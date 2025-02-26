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
 * @subpackage AgileStoreLocator/Admin/Branch
 */

class Branch extends Base {


  /**
   * [__construct description]
   */
  public function __construct() {
    
    parent::__construct();
  }

 /**
   * [get_store_list GET List of Stores]
   * @since  4.8.21 [<description>]
   * @return [type] [description]
   */
  public function get_store_list_edit() {

    global $wpdb;

    // Get param
    $parent_id  = isset( $_REQUEST['parent_id'])?$_REQUEST['parent_id']:null;   
    

    // Select filter
    $select_filter = isset($_REQUEST['select_filter'])?$_REQUEST['select_filter']:null;

    $exist_store = [];

    // Retrieve all metas by store parent id 
    $get_meta = \AgileStoreLocator\Model\Meta::get_branch_meta($parent_id);
    // dd($get_meta);
    foreach ($get_meta as $key => $store) {
      
      $exist_store[] = $store->store_id;

    }
    

    $acolumns = array('s.id', 's.id', 's.id', 'title', 'state', 'city', 'postal_code');

    // Filetrs
    $clause = array();

    //  all the query parameters for the filters
    $prepare_params = [$parent_id];

    if(isset($_REQUEST['filter'])) {

      foreach($_REQUEST['filter'] as $key => $value) {

        //  Get all searchable columns
        $searchable_columns = \AgileStoreLocator\Model\Store::get_searchable_columns();

        if($value != '') {

          $value    = sanitize_text_field($value);
          $key      = sanitize_text_field($key);

          //  $key must be within the allowed attributes
          $key = in_array($key, $searchable_columns)? $key: 'id';

          $clause[] = "s.{$key} LIKE %s";

          $prepare_params[] = "%$value%";
        }
      } 
    }
    // dd($clause);

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
      

      //$sOrder = substr_replace( $sOrder, "", -2 );
    if ( $sOrder == "ORDER BY" )
    {
      $sOrder = "";
    }
    }


    // Select Filter
    if ($select_filter) {

      if ($select_filter == 'assigned') {
          // Selelected Filter
          $clause[] = 'sm.store_id IS NOT NULL';
      }
      if ($select_filter == 'unassigned') {
          // Unselect  Filter
           $clause[] = 'sm.store_id IS NULL';
      }

    }

    
    //  Add the lang Filter
    $clause[] = "s.lang = '{$this->lang}'";

    $sWhere = implode(' AND ', $clause);
    
    if($sWhere != '')$sWhere = ' WHERE '.$sWhere;
    

    // Main query
    $sql =   ("SELECT s.id, s.title,s.state,s.city,s.postal_code, GROUP_CONCAT(sm.store_id) AS s_id FROM ".ASL_PREFIX."stores s LEFT  JOIN (SELECT store_id FROM ".ASL_PREFIX."stores_meta WHERE option_name = 'p_id' AND option_value = %d)  sm ON s.`id` = sm.store_id");
    

    //  Count Stores
    $sqlCount = ("SELECT COUNT(DISTINCT(s.id)) 'count' FROM ".ASL_PREFIX."stores s LEFT JOIN (SELECT store_id FROM ".ASL_PREFIX."stores_meta WHERE option_name = 'p_id' AND option_value = '%d')  sm ON s.id = sm.store_id");
    

    /*
     * SQL queries
     * Get data to display
     */
    $dQuery = $sQuery = "{$sql} {$sWhere} AND s.id != %d GROUP BY s.id {$sOrder} {$sLimit}";

    // Query parameters
    $prepare_params[] = $parent_id;

    $data_output = $wpdb->get_results($wpdb->prepare($sQuery, $prepare_params));
    $wpdb->show_errors = true;
    $error = $wpdb->last_error;

    /* Data set length after filtering */
    $sQuery = "{$sqlCount} {$sWhere} AND s.id != %d";
    $r = $wpdb->get_results($wpdb->prepare($sQuery, $prepare_params));
    $iFilteredTotal = $r[0]->count;
    
    $iTotal = $iFilteredTotal;

    /*
     * Output
     */
    $sEcho  = isset($_REQUEST['sEcho'])?intval($_REQUEST['sEcho']):1;
    $output = array(
      "sEcho" => intval($_REQUEST['sEcho']),
      "iTotalRecords" => $iTotal,
      "query" => $dQuery,
      'orderby' => $sOrder,
      "iTotalDisplayRecords" => $iFilteredTotal,
      "aaData" => array()
    );

    if($error) {

      $output['error'] = $error;
      $output['query'] = $dQuery;
    }


      
    //  Loop over the stores
    foreach($data_output as $aRow) {

      $row = $aRow;

      $edit_url = 'admin.php?page=edit-agile-store&store_id='.$row->id;

      $row->action = '<div class="edit-options"><a class="row-cpy" title="Duplicate" data-id="'.$row->id.'"><svg width="14" height="14"><use xlink:href="#i-clipboard"></use></svg></a><a href="'.$edit_url.'"><svg width="14" height="14"><use xlink:href="#i-edit"></use></svg></a><a title="Delete" data-id="'.$row->id.'" class="glyphicon-trash"><svg width="14" height="14"><use xlink:href="#i-trash"></use></svg></a></div>';


      // Check Assign store
          $checked = '';
      if (in_array($row->id, $exist_store)) {
          
          $checked = 'checked';
       
      } 

      // Switches
      $row->check = '<div class="form-group custom-checkbox">
                      <div class="form-group-inner">
                        <label class="switch" for="asl-chk-'.$row->id.'"">
                        <input type="checkbox" data-id="'.$row->id.'" class="custom-control-input" id="asl-chk-'.$row->id.'" '.$checked.'>
                        <span class="slider round"></span>
                        </label>
                      </div>
                    </div>';

   
      $output['aaData'][] = $row;

 
    }

    echo wp_json_encode($output);
    die;

  }

 
 
  /**
   * [add_store_into_branch Save Custom Fields AJAX]
   * @since  4.8.21 
   * @return [type] [description]
   */
  public function add_store_into_branch() {


    global $wpdb;

    $prefix = ASL_PREFIX;
    $table = $prefix.'stores_meta';

    $response  = new \stdclass();
    $response->success = false;

    $toggle     = isset( $_REQUEST['toggle'])?$_REQUEST['toggle']:null;   
    $parent_id  = isset( $_REQUEST['parent_id'])?$_REQUEST['parent_id']:null;   
    $store_id   = isset( $_REQUEST['store_id'])?$_REQUEST['store_id']:null;


    if(isset($toggle) && $toggle == '1') {

      //  Is this branch already parent node?
      $count_branches = \AgileStoreLocator\Model\Store::count_branches($store_id);

      //  branch already parent node is not allowed!
      if($count_branches > 0) {
        
        $response->msg     = esc_attr__("Error! assigned store is already a parent store.",'asl_locator'); 
      }
      //  Assign parent
      else {

        // Update meta
        \AgileStoreLocator\Helper::set_option($store_id, 'p_id', $parent_id);

        $response->msg     = esc_attr__("Store has been updated into branch successfully.",'asl_locator');
        $response->success = true;
      }
    } 
    else {

      // Delete meta 
      $where = array('store_id' => $store_id);
      $wpdb->delete($table, $where);

      $response->msg     = esc_attr__("Store has been delete from branch successfully.",'asl_locator');
      $response->success = true;
    }
    
    echo json_encode($response);die;
  }

}