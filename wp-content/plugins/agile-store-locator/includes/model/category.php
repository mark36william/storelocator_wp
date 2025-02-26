<?php

namespace AgileStoreLocator\Model;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
*
* To access the categories database table
*
* @package    AgileStoreLocator
* @subpackage AgileStoreLocator/elements/category
* @author     AgileStoreLocator Team <support@agilelogix.com>
*/
class Category {


    /**
    * [Get the all store categories for vc]
    * @since  4.8.21
    * @return [type]          [description]
    */
  public  static function get_all_categories( $addon = null) {
   
    global $wpdb;

    $ASL_PREFIX   = ASL_PREFIX;
    
    $categories   = [];
    
    $orde_by      = " `category_name` ;";
    $where_clause = "`lang` = ''";
    
    //  Get the results
    $results = $wpdb->get_results("SELECT * FROM {$ASL_PREFIX}categories WHERE {$where_clause} ORDER BY {$orde_by}");
    
    //  Loop over
    foreach ($results as $key => $value) {

        if ($addon === 'asl_vc') {

            $categories[$value->category_name] =  $value->id;

        } elseif ($addon === 'asl_ele') {

            $categories[$value->id] = $value->category_name;

        } else {

             $categories[$value->category_name] =  $value->id;
        }

    }

    
    return $categories;
 }

}
