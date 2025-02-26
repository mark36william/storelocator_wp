<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use AgileStoreLocator\Admin\Base;


/**
 * Handling all the assets to migrate from the previous version
 *
 * @link       https://agilestorelocator.com
 * @since      4.8.1
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Analytics
 */

class Analytics extends Base {

  /**
   * [__construct description]
   */
  public function __construct() {

    parent::__construct();
  }


  
  /**
   * [get_stats Get the Stats of the Analytics]
   * @return [type] [description]
   */
  public function get_stats() {

    global $wpdb;

    $start_date   = isset($_REQUEST['sl-start'])? ($_REQUEST['sl-start']): date('Y-m-d');
    $end_date     = isset($_REQUEST['sl-end'])? ($_REQUEST['sl-end']): date('Y-m-d');
    
    //  Trim dates
    $start_date = date('Y-m-d', strtotime((trim($start_date)))).' 00:00:00';
    $end_date   = date('Y-m-d', strtotime((trim($end_date)))).' 23:59:00';

    $days_count = $this->date_diff($start_date, $end_date);
    

    //  either month or day based
    $month_based = ($days_count > 31)? true: false;

    $group_by    = ($month_based)? 'MONTH':'DAY';

    $dur_format  = ($month_based)? 'M-Y': 'd-M';

    $data_key    = ($month_based)? 'n': 'd';


    //  For a single day, show by hours
    if($days_count == 1) {

      $group_by     = 'HOUR';
      $dur_format   = 'H';
      $data_key     = 'G';
    }

    ////////////////////
    //// Chart Values //
    ////////////////////
    $results = $wpdb->get_results($wpdb->prepare("SELECT {$group_by}(created_on) AS d, COUNT(*) AS c FROM `".ASL_PREFIX."stores_view` WHERE created_on between %s AND %s GROUP BY $group_by(created_on)", $start_date, $end_date));


    $begin  = new \DateTime($start_date);
    $end    = new \DateTime($end_date);


    //  Get all the labels
    $interval = \DateInterval::createFromDateString('1 '.$group_by);
    $period   = new \DatePeriod($begin, $interval, $end);


    $days_stats = array();

    foreach ($period as $dt) {

      //echo $dt->format($dur_format).'<br/>';
      $days_stats[(string)$dt->format($data_key)] = ['label' => $dt->format($dur_format), 'data' => 0];
    }


    //  Fill the data
    foreach($results as $row) {

      if(isset($days_stats[(string)$row->d]['data']))
        $days_stats[(string)$row->d]['data'] = $row->c;
    }


    /////////////
    //// Stats //
    /////////////
    $limit      = (isset($_REQUEST['len']) && $_REQUEST['len'])? intval($_REQUEST['len']): null;

    //  Top views
    $top_stores = $wpdb->get_results($wpdb->prepare("SELECT COUNT(*) AS views,".ASL_PREFIX."stores_view.`store_id`, title, city FROM `".ASL_PREFIX."stores_view` LEFT JOIN `".ASL_PREFIX."stores` ON ".ASL_PREFIX."stores_view.`store_id` = ".ASL_PREFIX."stores.`id` WHERE store_id IS NOT NULL AND ".ASL_PREFIX."stores_view.created_on between %s AND %s GROUP BY store_id ORDER BY views".(($limit)?'  DESC LIMIT '.$limit: ''), $start_date, $end_date));
    
    //  Top Searches    
    $top_search = $wpdb->get_results($wpdb->prepare("SELECT COUNT(*) AS views, search_str FROM `".ASL_PREFIX."stores_view` WHERE store_id IS NULL AND is_search = 1 AND created_on between %s AND %s GROUP BY search_str ORDER BY views".(($limit)?'  DESC LIMIT '.$limit: ''), $start_date, $end_date));


    echo json_encode(['stores' => $top_stores, 'searches' => $top_search, 'chart_data' => $days_stats]);die;
  }


  /**
   * [export_analytics Export the ASL Analytics]
   * @return [type] [description]
   */
  public function export_analytics() {

    global $wpdb;

    $start_date   = isset($_REQUEST['sl-start'])? ($_REQUEST['sl-start']): date('Y-m-d');
    $end_date     = isset($_REQUEST['sl-end'])? ($_REQUEST['sl-end']): date('Y-m-d');
    
    //  Trim dates
    $start_date = date('Y-m-d', strtotime((trim($start_date)))).' 00:00:00';
    $end_date   = date('Y-m-d', strtotime((trim($end_date)))).' 23:59:00';


    $days_count = $this->date_diff($start_date, $end_date);

    //dd("SELECT search_str as 'location', ip_address, created_on FROM `".ASL_PREFIX."stores_view` WHERE is_search = 1 AND created_on between %s AND %s");

    //  Searches Data
    $searches = $wpdb->get_results("SELECT search_str as 'location', ip_address, created_on FROM `".ASL_PREFIX."stores_view` WHERE is_search = 1 AND created_on between '$start_date' AND '$end_date';" );
    

    $csv = new \AgileStoreLocator\Admin\CSV\Reader();

    //  Rows to be exported
    $all_rows = [];

    //  Just send the headers for empty
    if(!$searches) {

      $searches = [['location' => '', 'ip_address' => '', 'created_on' => '']];
    }

    //  Loop over the stores data
    foreach ($searches as $value) {

      //  Push into rows collection
      $all_rows[] = $value;
    }


    ///////////////////////////////
    ////  Get all the top stores //
    ///////////////////////////////

    //  Stores Data
    $top_stores = $wpdb->get_results($wpdb->prepare("SELECT COUNT(*) AS 'Views',".ASL_PREFIX."stores_view.`store_id`, title, city FROM `".ASL_PREFIX."stores_view` LEFT JOIN `".ASL_PREFIX."stores` ON ".ASL_PREFIX."stores_view.`store_id` = ".ASL_PREFIX."stores.`id` WHERE store_id IS NOT NULL AND ".ASL_PREFIX."stores_view.created_on between %s AND %s GROUP BY store_id ORDER BY views DESC", $start_date, $end_date));
    
    //  Stores data
    if($top_stores) {

      // Add the empty row and the header
      $all_rows[] = ['', '', ''];

      $all_rows[] = ['Views', 'Store ID', 'Store Name', 'City'];

      foreach ($top_stores as $value) {

        //  Push into rows collection
        $all_rows[] = $value;
      }
    }

    $csv->setRows($all_rows);

    $csv->write(\AgileStoreLocator\Admin\CSV\Reader::DOWNLOAD, 'search-stats.csv');;
    die;

  }


  /**
   * [date_diff Return the difference between dates]
   * @param  [type] $start [description]
   * @param  [type] $end   [description]
   * @return [type]        [description]
   */
  private function date_diff($start, $end) {

    $datediff   = strtotime($end) - strtotime($start);
    return round($datediff / (60 * 60 * 24));
  }
}