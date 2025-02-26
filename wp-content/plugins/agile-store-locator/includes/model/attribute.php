<?php

namespace AgileStoreLocator\Model;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
*
* To access the Attribute database table
*
* @package    AgileStoreLocator
* @subpackage AgileStoreLocator/includes/attribute
* @author     AgileStoreLocator Team <support@agilelogix.com>
*/
class Attribute {

  private static $options   = ['brands', 'specials', 'regions'];

  private static $controls;



  /**
   * [get_controls Return the controls array]
   * @return [type] [description]
   */
  public static function get_controls() {

     //  Add the controls
    if(!self::$controls) {

      self::$controls = [
        'brands'    => ['label' => asl_esc_lbl('brand'),   'plural' => asl_esc_lbl('brands'), 'field' => 'brand'],
        'specials'  => ['label' => asl_esc_lbl('special'), 'plural'  => asl_esc_lbl('specials'), 'field' => 'special']
      ];
    }

    return self::$controls;
  }


  /**
   * [get_controls_keys Return the table names]
   * @return [type] [description]
   */
  public static function get_controls_keys() {

    return array_keys(self::get_controls());
  }

  /**
   * [get_fields Return the field names in the store table]
   * @return [type] [description]
   */
  public static function get_fields() {

    return array_column(self::get_controls(), 'field');
  }


  /**
   * [sql_query_fields Return the Query fields]
   * @return [type] [description]
   */
  public static function sql_query_fields() {

    //  Get the fields
    $ddl_fields  = self::get_fields();
    
    $ddl_filters = [];

    foreach($ddl_fields as $ddl_field) {

      $ddl_filters[$ddl_field] = (isset($_REQUEST[$ddl_field]))? sanitize_text_field($_REQUEST[$ddl_field]):null; 
    }

    // ddl_fields in the query
    return implode(', ', array_map(function($f) { return "`$f`";}, $ddl_fields));
  }


  /**
   * [get_all_by_id Return data as the list format index by the ID]
   * @param  [type] $type [description]
   * @param  [type] $lang [description]
   * @param  [type] $id   [description]
   * @return [type]       [description]
   */
  public static function get_all_by_id($type, $lang = '', $ids = null) {

    global $wpdb;


    //  Where clause
    $clauses        = [];
    $clauses_values = [];

    //  Lang
    $clauses[]        = 'lang = %s';
    $clauses_values   = trim($lang);

    //  Filter by the ID
    if($ids) {
      
      $ids_array = explode(',' , $ids);
      $ids_array = array_map( 'absint', $ids_array );


      $placeholders = implode(',', array_fill(0, count(explode(',', $ids)), '%d'));
      
      $clauses[]    = "id IN ($placeholders)";
      $clauses_values = array_merge($clauses_values, $ids_array);
    }

    //  must be a valid attribute
    $attr_name   = (in_array($type, self::$options))? $type: self::$options[0]; 


    //  Where clause string
    $sql_query = "SELECT id, name, ordr FROM ".ASL_PREFIX.$attr_name;

    //  Add the clause with prepare stmt
    if(!empty($clauses)) {

      $where_clause = implode(' AND ', $clauses);      
      $sql_query    = $wpdb->prepare($sql_query." WHERE ".$where_clause, $clauses_values);
    }


    $results     = $wpdb->get_results($sql_query.' ORDER BY name ASC');

    $list        = [];

    foreach ($results as $r) {  
      $list[$r->id] = $r;
    }

    return $list;
  }

  /**
   * [get_list Get the list of attributes]
   * @param  [type] $type [description]
   * @param  [type] $lang [description]
   * @return [type]       [description]
   */
  public static function get_list($type, $lang) {

    global $wpdb;

    //  must be a valid attribute
    $attr_name  = (in_array($type, self::$options))? $type: self::$options[0]; 

    $results    = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".ASL_PREFIX.$attr_name." WHERE lang = %s ORDER BY name ASC", $lang));

    return $results;
  }

  /**
   * [get_id_by_name Return the ID if exist!]
   * @param  [type] $type [description]
   * @param  [type] $name [description]
   * @param  [type] $lang [description]
   * @return [type]       [description]
   */
  public static function get_id_by_name($type, $name, $lang) {

    global $wpdb;

    //  must be a valid attribute
    $attr_name  = (in_array($type, self::$options))? $type: self::$options[0]; 

    $item_row   = $wpdb->get_row($wpdb->prepare("SELECT id FROM ".ASL_PREFIX.$attr_name." WHERE name = %s AND lang = %s", $name, $lang));

    // is it found?
    if($item_row) {
      return $item_row->id;
    }

    return null;
  }

  /**
   * [get_names_by_ids Return the names by the ID]
   * @return [type] [description]
   */
  public static function get_names_by_ids($type, $attr_ids) {

    global $wpdb;

    //  No attributes
    if(!$attr_ids) {
      return [];
    }

    $attr_ids   = array_map( 'absint', $attr_ids );
    $attr_ids   = implode(',', $attr_ids);
    
    //  must be a valid attribute
    $attr_name  = (in_array($type, self::$options))? $type: self::$options[0]; 

    $names      = $wpdb->get_col("SELECT name FROM ".ASL_PREFIX.$attr_name." WHERE id IN ($attr_ids)");

    return ($names)? $names: [];
  }



  /**
   * [get_id_with_insert Return the ID of the attribute in any case]
   * @param  [type] $type  [description]
   * @param  [type] $value [description]
   * @param  [type] $lang  [description]
   * @return [type]        [description]
   */
  public static function get_id_with_insert($type, $value, $lang) {

    global $wpdb;

    $attr_ids     = [];
    $new_created  = [];

    //  trim it
    $value        = trim($value);

    if($value) {

      //  explode by bar
      $attr_values  = explode("|", $value);

      //  Loop over
      foreach($attr_values as $attr_value) {

        //  check if id is already there
        $attribute_id = self::get_id_by_name($type, $attr_value, $lang);

        if(!$attribute_id) {

          //  must be a valid attribute
          $attr_name  = (in_array($type, self::$options))? $type: self::$options[0]; 

          //  Insert it as doesn't exist
          $wpdb->insert(ASL_PREFIX.$attr_name, array('name' => $attr_value, 'lang' => $lang), array('%s', '%s'));

          //  Get the inserted ID
          $attribute_id   = $wpdb->insert_id;

          //  keep the name of newly created attribute
          $new_created[]  = $attr_value;
        }

        //  add the attribute id
        $attr_ids[] = $attribute_id;
      }  
    }
    

    return [implode(',', $attr_ids), implode(',', $new_created)];
  }


  /**
   * [get_all_attributes_list Return the list of all the attributes]
   * @param  [type] $lang [description]
   * @param  [type] $atts [description]
   * @return [type]       [description]
   */
  public static function get_all_attributes_list($lang, $atts) {

    //  List of all attributes
    $all_attributes = [];

    //  ddl controls
    $ddl_controls = self::get_controls();

    
    //  Loop over the controls
    foreach($ddl_controls as $control_key => $ddl_control) {

      $ddl_filter_id = null;
      $field_name    = $ddl_control['field'];

      //  when ddl is in the attr filter
      if(isset($atts[$field_name])) {

        //  use it to filter the list
        $ddl_filter_id            = $atts[$field_name];

        // Add that in the config as well
        $all_configs[$field_name] = $ddl_filter_id;
      }

      $all_attributes[$ddl_control['field']] = self::get_all_by_id($control_key, $lang, $ddl_filter_id);
    }

    return $all_attributes;
  }


}