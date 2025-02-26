<?php

namespace AgileStoreLocator\Schema;


class Slug {



  /**
   * [slugify Create Slug]
   * @since  4.8.21         [<description>]
   * @param  [type] $store  [description]
   * @return [type]         [description]
   */
  public static function slugify($store, $custom_fields) {

    global $wpdb;

    //  All the fields for the slug
    $all_fields   = ($custom_fields && is_array($custom_fields))? array_merge($custom_fields, $store): $store;

    //  Slug Attributes
    $slug_fields  = \AgileStoreLocator\Helper::get_setting('slug_attr_ddl');

    //  Default values
    if(!$slug_fields) {
      $slug_fields = 'title,city';
    }

    //  Exploded in array
    $slug_fields  = explode(',', $slug_fields);
    // dd($slug_fields);
      
    $slug_value  = [];

    //  Make Slug String
    foreach ($slug_fields as $slug_chunk) {

      if(isset($all_fields[$slug_chunk]) && $all_fields[$slug_chunk]) {

        $slug_value[] = $all_fields[$slug_chunk];
      }
    }

    //  When slug data fields are empty, make it title and city
    if(empty($slug_value)) {
      $slug_value[] = $all_fields['title'];
      $slug_value[] = $all_fields['city'];
    }


    $slug_value   = implode('-', $slug_value);

    //  Filter the string
    $slug_value   = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug_value), '-'));

    $count_slug   = self::count_slug($slug_value);

    if($count_slug > 0) {


        $slug_value  .= '-'.$count_slug ;

        return $slug_value; 

    }


    return preg_replace('/-+/', '-', $slug_value);
  }


  /**
   * [check slug already exist or not]
   * @since  4.8.21 [<description>]
   * @param  array   $slug [description]
   */
  public static function check_slug($slug='') {

    global $wpdb;  

    $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".ASL_PREFIX."stores WHERE slug =  %s", $slug));
    return $results;

  }



  /**
   * [check slug already exist get next count]
   * @since  4.8.21 [<description>]
   * @param  array   $slug [description]
   */
  public static function count_slug($slug='') {

   global $wpdb;

   $results = $wpdb->get_var("SELECT COUNT(*) AS counter FROM ".ASL_PREFIX."stores WHERE  `slug`  LIKE '$slug%'");
   return $results;

  }


}