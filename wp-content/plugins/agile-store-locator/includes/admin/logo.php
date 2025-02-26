<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use AgileStoreLocator\Admin\Base;

/**
 * The logo manager functionality of the plugin.
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Logo
 */

class Logo extends Base {

  /**
   * [__construct description]
   */
  public function __construct() {
    
    parent::__construct();
  }

  /**
   * [upload_logo Upload the Logo]
   * @return [type] [description]
   */
  public function upload_logo() {

    $response = new \stdclass();
    $response->success = false;

    //  Validate if the Name isn't missing
    if(empty($_POST['data']['logo_name']) || !$_POST['data']['logo_name']) {
      $response->msg = __("Error! logo name is required.",'asl_locator');
      echo json_encode($response);die;
    }

    if(!empty($_POST['data']['img_id'])) {

      $img_path  = get_attached_file($_POST['data']['img_id'],'medium');
      $content   = file_get_contents($img_path);
      $pathinfo  = pathinfo($img_path);
      $put       = file_put_contents(ASL_UPLOAD_DIR.'Logo/'.$pathinfo['filename'].'.'.$pathinfo['extension'], $content);
    }
    
    //  Logo Name
    $logo_name   = isset($_POST['data']['logo_name'])? sanitize_text_field($_POST['data']['logo_name']):('Logo '.time());
    
    //   Parameters to Save
    $data_params = array('name' => $logo_name);


    //  Validate the Upload Success
    if(isset($put)) {

      $file_name    = $pathinfo['filename'].'.'.$pathinfo['extension'];

      //  Add the newly uploaded file
      $data_params['path'] = $file_name;
    }
    else {

      $response->msg      = ($upload_result['error'])? $upload_result['error']: __('Error! Failed to upload the image.','asl_locator');
      echo json_encode($response);die;
    }


    global $wpdb;

    //  Insert the Logo
    $wpdb->insert(ASL_PREFIX.'storelogos', $data_params);

    $response->list = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."storelogos ORDER BY id DESC");
    $response->msg  = __("Logo is uploaded successfully.",'asl_locator');
    $response->success = true;


    echo json_encode($response);
    die;
  }


  /**
   * [delete_logo Delete a Logo]
   * @return [type] [description]
   */
  public function delete_logo() {
    
    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $multiple = isset($_REQUEST['multiple'])? $_REQUEST['multiple']: null;
    $delete_sql;$mResults;

    if($multiple) {

      $item_ids      = implode(",", array_map( 'intval', $_POST['item_ids'] ));
      $delete_sql    = "DELETE FROM ".ASL_PREFIX."storelogos WHERE id IN (".$item_ids.")";
      $mResults      = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."storelogos WHERE id IN (".$item_ids.")");
    }
    else {

      $item_id       = intval($_REQUEST['logo_id']);
      $delete_sql    = "DELETE FROM ".ASL_PREFIX."storelogos WHERE id = ".$item_id;
      $mResults      = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."storelogos WHERE id = ".$item_id );
    }


    if(count($mResults) != 0) {
      
      if($wpdb->query($delete_sql)) {

          $response->success = true;

          foreach($mResults as $m) {

            $inputFileName = ASL_UPLOAD_DIR.'Logo/'.$m->path;
          
            if(file_exists($inputFileName) && $m->path != 'default.png') {  
                  
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
      $response->msg = ($multiple)?__('Logos deleted Successfully.','asl_locator'):esc_attr__('Logo deleted Successfully.','asl_locator');
    
    echo json_encode($response);die;
  }



  /**
   * [update_logo update logo with icon]
   * @return [type] [description]
   */
  public function update_logo() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $data = $_REQUEST['data'];

    //  Logo Update Parameter
    $data_params = array('name' => trim($data['logo_name']));

    // with icon
    if($data['action'] == "notsame") {

      if(!empty($_POST['data']['img_id'])){
        $img_path = get_attached_file($_POST['data']['img_id'],'medium');
        $content  = file_get_contents($img_path);
        $pathinfo = pathinfo($img_path);
        $put = file_put_contents(ASL_UPLOAD_DIR.'Logo/'.$pathinfo['filename'].'.'.$pathinfo['extension'], $content);
      }

      //  Validate the Upload Success
      if(isset($put)) {

        $file_name    = $pathinfo['filename'].'.'.$pathinfo['extension'];


        $response->file_name = $file_name;

        //  Add the newly uploaded file
        $data_params['path'] = $file_name;

        //  Delete the old icon if exist
        $old_icon     = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".ASL_PREFIX."storelogos WHERE id = %d", $data['logo_id']));

        //  Delete the old file, if exist
        if (file_exists(ASL_UPLOAD_DIR.'Logo/'.$old_icon[0]->path)) { 
          unlink(ASL_UPLOAD_DIR.'Logo/'.$old_icon[0]->path);
        }
      }
      else {

        $response->msg      = ($upload_result['error'])? $upload_result['error']: __('Error! Failed to upload the image.','asl_locator');
        echo json_encode($response);die;
      }
    }
    
    //  Execute Update Query
    $wpdb->update(ASL_PREFIX."storelogos", $data_params, array('id' => $data['logo_id']));    
    
    $response->msg      = __('Logo updated successfully.','asl_locator');
    $response->success  = true; 
    
    echo json_encode($response);die;
  }


  /**
   * [get_logo_by_id get logo by id]
   * @return [type] [description]
   */
  public function get_logo_by_id() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $store_id = isset($_REQUEST['logo_id'])? intval($_REQUEST['logo_id']): 0;
    

    $response->list = $wpdb->get_results( "SELECT * FROM ".ASL_PREFIX."storelogos WHERE id = ".$store_id);

    if(count($response->list)!=0){

      $response->success = true;

    }
    else{
      $response->error = esc_attr__('Error occurred while geting record','asl_locator');

    }
    echo json_encode($response);die;
  }


  /**
   * [get_logos GET the Logos]
   * @return [type] [description]
   */
  public function get_logos() {

    global $wpdb;
    $start = isset( $_REQUEST['iDisplayStart'])?$_REQUEST['iDisplayStart']:0;   
    $params  = isset($_REQUEST)?$_REQUEST:null;   

    $acolumns = array(
      'id','id','name','path'
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
    $sql =  "SELECT $fields FROM ".ASL_PREFIX."storelogos";

    $sqlCount = "SELECT count(*) 'count' FROM ".ASL_PREFIX."storelogos";

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

        $row->path   = '<img src="'.ASL_UPLOAD_URL.'Logo/'.$row->path.'"  style="max-width:100px"/>';
        $row->check  = '<div class="custom-control custom-checkbox"><input type="checkbox" data-id="'.$row->id.'" class="custom-control-input" id="asl-chk-'.$row->id.'"><label class="custom-control-label" for="asl-chk-'.$row->id.'"></label></div>';
        $row->action = '<div class="edit-options"><a data-id="'.$row->id.'" title="Edit" class="glyphicon-edit edit_logo"><svg width="14" height="14"><use xlink:href="#i-edit"></use></svg></a><a title="Delete" data-id="'.$row->id.'" class="glyphicon-trash delete_logo"><svg width="14" height="14"><use xlink:href="#i-trash"></use></svg></a></div>';
          $output['aaData'][] = $row;
      }

    echo json_encode($output);die;
  }

}