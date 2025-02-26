<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use AgileStoreLocator\Admin\Base;

/**
 * Attribute Manager for Brand & Special or any other dropdown
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Attribute
 */

class Attribute extends Base {


  private $attr_tables;

  /**
   * [__construct description]
   */
  public function __construct() {
      
    $this->attr_tables = \AgileStoreLocator\Model\Attribute::get_controls_keys();
    
    parent::__construct();
  }

  /**
   * [delete_attribute Delete Attribute]
   * @return [type] [description]
   */
  public function delete_attribute() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $table  = isset( $_REQUEST['name'])?  sanitize_text_field($_REQUEST['name']):null;
    $title  = isset( $_REQUEST['title'])? sanitize_text_field($_REQUEST['title']):null;
    $value  = isset( $_REQUEST['value'])? sanitize_text_field($_REQUEST['value']):null;

    $multiple = isset($_REQUEST['multiple'])? $_REQUEST['multiple']: null;
    $delete_sql;
    $cResults;

    //  To filter the table name
    $table = (in_array($table, $this->attr_tables))? $table: $this->attr_tables[0];

    if($multiple) {

      //  Clean it
      $item_ids      = implode(",", array_map( 'intval', $_POST['item_ids'] ));

      $delete_sql    = "DELETE FROM ".ASL_PREFIX.$table." WHERE id IN (".$item_ids.")";
      $cResults      = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX.$table." WHERE id IN (".$item_ids.")");
    }
    else {

      $category_id   = intval($_REQUEST['category_id']);

      $delete_sql    = "DELETE FROM ".ASL_PREFIX.$table." WHERE id = ".$category_id;
      $cResults      = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX.$table." WHERE id = ".$category_id );
    }


    if(count($cResults) != 0) {
      
      if($wpdb->query($delete_sql))
      {
          $response->success = true;
      }
      else
      {
        $response->error = esc_attr__('Error occurred while deleting record','asl_locator');
        $response->msg   = $wpdb->show_errors();
      }
    }
    else
    {
      $response->error = esc_attr__('Error occurred while deleting record','asl_locator');
    }

    if($response->success)
      $response->msg = $title." ".esc_attr__("deleted successfully",'asl_locator');
    
    echo json_encode($response);die;
  }
  

 
  /**
   * [add_attribute description]
   */
  public function add_attribute() {

    global $wpdb;

    $response = new \stdclass();
    $response->success = false;

    $table  = isset( $_REQUEST['name'])?  sanitize_text_field($_REQUEST['name']):null;
    $title  = isset( $_REQUEST['title'])? sanitize_text_field($_REQUEST['title']):null;
    $value  = isset( $_REQUEST['value'])? sanitize_text_field($_REQUEST['value']):null;
    $ordr   = isset( $_REQUEST['ordr']) && is_numeric($_REQUEST['ordr'])? $_REQUEST['ordr']:0;

    //  Filter the Table Name
    $table = (in_array($table, $this->attr_tables))? $table: $this->attr_tables[0];
    
    $value = stripslashes($value);
    
    if($value && $wpdb->insert(ASL_PREFIX.$table, array('name' => $this->clean_input($value), 'ordr' => $ordr, 'lang' => $this->lang))) {

      $response->msg     = $title.esc_attr__(" added successfully",'asl_locator');
      $response->success = true;
    }
    else {

      $response->msg = esc_attr__('Error occurred while saving record','asl_locator');
    }
          
    echo json_encode($response);
    die;
  }

  /**
   * [update_attribute description]
   * @return [type] [description]
   */
  public function update_attribute() {

    global $wpdb;

    $response = new \stdclass();
    $response->success = false;

    $table  = isset( $_REQUEST['name'])?sanitize_text_field($_REQUEST['name']):null;
    $title  = isset( $_REQUEST['title'])?sanitize_text_field($_REQUEST['title']):null;
    $value  = isset( $_REQUEST['value'])?sanitize_text_field($_REQUEST['value']):null;
    $at_id  = isset( $_REQUEST['id'])?sanitize_text_field($_REQUEST['id']):null;
    $ordr   = isset( $_REQUEST['ordr']) && is_numeric($_REQUEST['ordr'])?sanitize_text_field($_REQUEST['ordr']):0;
    

    //  Filter the Table Name
    $table = (in_array($table, $this->attr_tables))? $table: $this->attr_tables[0];

    $value = stripslashes($value);

    if($at_id & $value && $wpdb->update(ASL_PREFIX.$table, array('name' => $this->clean_input($value), 'ordr' => $ordr), array('id' => $at_id)))
    {
      $response->msg     = $title." ".esc_attr__("Updated Successfully",'asl_locator');
      $response->success = true;
    }
    else {
      $response->msg = esc_attr__('Error occurred while saving record','asl_locator');
    }
          
  

    echo json_encode($response);
    die;
  }

  /**
   * [get_attributes Get the Attribute]
   * @return [type] [description]
   */
  public function get_attributes() {

    global $wpdb;
    $start    = isset( $_REQUEST['iDisplayStart'])?sanitize_text_field($_REQUEST['iDisplayStart']):0;
    $table    = isset( $_REQUEST['type'])?sanitize_text_field($_REQUEST['type']):null;
    $params   = isset($_REQUEST)?$_REQUEST:null;

    if(!$table) {
      return;
    }

    //  Filter the Table Name
    $table = (in_array($table, $this->attr_tables))? $table: $this->attr_tables[0];



    $acolumns = array(
      'id','id','name', 'ordr', 'created_on'
    );

    $columnsFull = $acolumns;

    $clause = array();

    if(isset($_REQUEST['filter'])) {

      foreach($_REQUEST['filter'] as $key => $value) {

        if($value != '') {

          $value    = sanitize_text_field($value);
          $key      = sanitize_text_field($key);

          $clause[] = "$key like '%{$value}%'";
        }
      } 
    } 
    
    //  Add the lang Filter
    $clause[] = "lang = '{$this->lang}'";

    //iDisplayStart::Limit per page
    $sLimit = "";
    if ( isset( $_REQUEST['iDisplayStart'] ) && $_REQUEST['iDisplayLength'] != '-1' )
    {
      $sLimit = "LIMIT ".intval( $_REQUEST['iDisplayStart'] ).", ".
        intval( $_REQUEST['iDisplayLength'] );
    }

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
          $sOrder .= "`".$acolumns[ intval( $_REQUEST['iSortCol_'.$i] )  ]."` ".$sort_dir;
          break;
        }
      }
      

      //$sOrder = substr_replace( $sOrder, "", -2 );
      if ( $sOrder == "ORDER BY" )
      {
        $sOrder = "";
      }
    }


    $sWhere = implode(' AND ',$clause);
    
    if($sWhere != '')$sWhere = ' WHERE '.$sWhere;
    
    $fields = implode(',', $columnsFull);
    
    ###get the fields###
    $sql =  "SELECT $fields FROM ".ASL_PREFIX.$table;

    $sqlCount = "SELECT count(*) 'count' FROM ".ASL_PREFIX.$table;

    /*
     * SQL queries
     * Get data to display
     */
    $sQuery = "{$sql} {$sWhere} {$sOrder} {$sLimit}";
    $data_output = $wpdb->get_results($sQuery);


    //  Call the activator when error is received
    if(!$data_output) {

      $err_message = isset($wpdb->last_error)? $wpdb->last_error: null;

      if($err_message) {

        \AgileStoreLocator\Activator::activate();
      }
    }


    
    /* Data set length after filtering */
    $sQuery = "{$sqlCount} {$sWhere}";
    $r = $wpdb->get_results($sQuery);
    $iFilteredTotal = $r[0]->count;
    
    $iTotal = $iFilteredTotal;

   /*
     * Output
     */
    $sEcho = isset($_REQUEST['sEcho'])?intval($_REQUEST['sEcho']):1;
    $output = array(
      "sEcho" => intval($_REQUEST['sEcho']),
      //"test" => $test,
      "iTotalRecords" => $iTotal,
      "iTotalDisplayRecords" => $iFilteredTotal,
      "aaData" => array()
    );
    
    foreach($data_output as $aRow)
    {
      $row = $aRow;

      $row->action = '<div class="edit-options"><a data-ordr="'.$row->ordr.'"  data-value="'.$row->name.'" data-id="'.$row->id.'" title="Edit" class="edit_attr"><svg width="14" height="14"><use xlink:href="#i-edit"></use></svg></a><a title="Delete" data-id="'.$row->id.'" class="delete_attr g-trash"><svg width="14" height="14"><use xlink:href="#i-trash"></use></svg></a></div>';
      $row->check  = '<div class="custom-control custom-checkbox"><input type="checkbox" data-id="'.$row->id.'" class="custom-control-input" id="asl-chk-'.$row->id.'"><label class="custom-control-label" for="asl-chk-'.$row->id.'"></label></div>';
      $output['aaData'][] = $row;
    }

    echo json_encode($output);die;
  }

}