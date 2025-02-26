<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use AgileStoreLocator\Admin\Base;

/**
 * The marker manager functionality of the plugin.
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Marker
 */

class Marker extends Base {

  /**
   * [__construct description]
   */
  public function __construct() {
    
    parent::__construct();
  }


  /**
   * [add_marker Add Marker Method]
   */
  public function add_marker() {

    global $wpdb;

    $response = new \stdclass();
    $response->success = false;

    
    //  Upload the Icon File
    $upload_result  = $this->_file_uploader($_FILES["files"], 'icon');

    //  is upload file successful?
    if(isset($upload_result['success']) && $upload_result['success']) {

      $form_data = $_REQUEST['data'];
      $file_name = $upload_result['file_name'];

      if($wpdb->insert(ASL_PREFIX.'markers', array( 'marker_name' => $form_data['marker_name'], 'icon' => $file_name), array('%s', '%s'))) {
        
        $response->msg      = esc_attr__("Marker added successfully",'asl_locator');
        $response->success  = true;
        $response->list     = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."markers ORDER BY id DESC");
      }
      else
        $response->msg = esc_attr__('Error occurred while saving record','asl_locator');
    }
    else
      $response->msg = ($upload_result['error'])?$upload_result['error']: esc_attr__('Error occurred while uploading image.','asl_locator');//$form_data

    echo json_encode($response);
    die;
  }

  /**
   * [delete_marker delete marker/markers]
   * @return [type] [description]
   */
  public function delete_marker() {
    
    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $multiple = isset($_REQUEST['multiple'])? $_REQUEST['multiple']: null;
    $delete_sql;$mResults;

    if($multiple) {

      $item_ids      = implode(",", array_map( 'intval', $_POST['item_ids'] ));
      $delete_sql    = "DELETE FROM ".ASL_PREFIX."markers WHERE id IN (".$item_ids.")";
      $mResults      = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."markers WHERE id IN (".$item_ids.")");
    }
    else {

      $item_id       = intval($_REQUEST['marker_id']);
      $delete_sql    = "DELETE FROM ".ASL_PREFIX."markers WHERE id = ".$item_id;
      $mResults      = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."markers WHERE id = ".$item_id );
    }


    if(count($mResults) != 0) {
      
      if($wpdb->query($delete_sql)) {

          $response->success = true;

          foreach($mResults as $m) {

            $inputFileName = ASL_UPLOAD_DIR.'icon/'.$m->icon;
          
            if(file_exists($inputFileName) && $m->icon != 'default.png' && $m->icon != 'active.png') {  
                  
              unlink($inputFileName);
            }
          }             
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
      $response->msg = ($multiple)?__('Markers deleted successfully.','asl_locator'):esc_attr__('Marker deleted successfully.','asl_locator');
    
    echo json_encode($response);die;
  }



  /**
   * [update_marker update marker with icon]
   * @return [type] [description]
   */
  public function update_marker() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $data        = $_REQUEST['data'];
    

    //  Marker Update Parameter
    $data_params = array('marker_name' => trim(sanitize_text_field($data['marker_name'])));
    
    // Have Icon Updated?
    if($data['action'] == "notsame") {

      //  Upload the Icon File
      $upload_result  = $this->_file_uploader($_FILES["files"], 'icon');

      //  Validate the Upload Success
      if(isset($upload_result['success']) && $upload_result['success']) {

        $file_name    = $upload_result['file_name'];

        //  Add the newly uploaded file
        $data_params['icon'] = $file_name;

        //  Delete the old icon if exist
        $old_icon     = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".ASL_PREFIX."markers WHERE id = %d", $data['marker_id']));

        //  Delete the old file, if exist
        if (file_exists(ASL_UPLOAD_DIR.'icon/'.$old_icon[0]->icon)) { 
          unlink(ASL_UPLOAD_DIR.'icon/'.$old_icon[0]->icon);
        }
      }
      else {

        $response->msg      = ($upload_result['error'])? $upload_result['error']: esc_attr__('Error! Failed to upload the image.','asl_locator');
        echo json_encode($response);die;
      }

    }

    //  Execute the Update Query
    $wpdb->update(ASL_PREFIX."markers", $data_params, array('id' => sanitize_text_field($data['marker_id'])));

    $response->msg      = esc_attr__('Marker Updated Successfully.','asl_locator');
    $response->success  = true; 
    
    echo json_encode($response);die;
  }

  
  /**
   * [get_markers GET the Markers List]
   * @return [type] [description]
   */
  public function get_markers() {

    global $wpdb;
    $start = isset( $_REQUEST['iDisplayStart'])?$_REQUEST['iDisplayStart']:0;   
    $params  = isset($_REQUEST)?$_REQUEST:null;   

    $acolumns = array(
      'id','id','marker_name','icon'
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
    $sql =  "SELECT $fields FROM ".ASL_PREFIX."markers";

    $sqlCount = "SELECT count(*) 'count' FROM ".ASL_PREFIX."markers";

    /*
     * SQL queries
     * Get data to display
     */
    $sQuery = "{$sql} {$sWhere} {$sOrder} {$sLimit}";
    $data_output = $wpdb->get_results($sQuery);
    
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


        $row->icon   = "<img  src='".ASL_UPLOAD_URL."icon/".$row->icon."' alt=''  style='width:20px'/>";  
        $row->check  = '<div class="custom-control custom-checkbox"><input type="checkbox" data-id="'.$row->id.'" class="custom-control-input" id="asl-chk-'.$row->id.'"><label class="custom-control-label" for="asl-chk-'.$row->id.'"></label></div>';
        $row->action = '<div class="edit-options"><a data-id="'.$row->id.'" title="Edit" class="glyphicon-edit edit_marker"><svg width="14" height="14"><use xlink:href="#i-edit"></use></svg></a><a title="Delete" data-id="'.$row->id.'" class="glyphicon-trash delete_marker"><svg width="14" height="14"><use xlink:href="#i-trash"></use></svg></a></div>';
          $output['aaData'][] = $row;
      }

    echo json_encode($output);die;
  }

  /**
   * [get_marker_by_id get marker by id]
   * @return [type] [description]
   */
  public function get_marker_by_id() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $store_id = isset($_REQUEST['marker_id'])? intval($_REQUEST['marker_id']): 0;
    

    $response->list = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."markers WHERE id = ".$store_id);

    if(count($response->list)!=0){

      $response->success = true;

    }
    else{
      $response->error = esc_attr__('Error occurred while geting record','asl_locator');

    }
    echo json_encode($response);die;
  }
}