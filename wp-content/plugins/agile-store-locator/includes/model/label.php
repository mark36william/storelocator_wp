<?php

namespace AgileStoreLocator\Model;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use AgileStoreLocator\Admin\Base;

/**
 * The class to access the labels
 *
 * @link       https://agilestorelocator.com
 * @since      4.8.28
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Labels
 */

class Label {

    /**
     * [$all_labels the singleton of the labels]
     * @var null
     */
    private static $all_labels = null;

    /**
     * [$enabled To check if the functionality is enabled or disabled, once disabled it will be WPML translation]
     * @var null
     */
    private static $enabled = null;

    private function __construct($text) {
        
        $this->text = $text;
    }

    /**
    * [asl_default_labels]
    * @return [type] [description]
    */
    public static  function asl_default_labels(){

      $labels = array(
        'all'               => esc_attr__('ALL', 'asl_locator'), 
        'load'              => esc_attr__( 'Load Store Locator','asl_locator'), 
        'label_gdpr'        => esc_attr__( 'Due to the GDPR, we need your consent to load data from Google, more information in our privacy policy.', 'asl_locator'),
        'search_loc'        => esc_attr__( 'Search Location', 'asl_locator'), 
        'enter_loc'         => esc_attr__( 'Enter a Location ', 'asl_locator'), 
        'search_name'       => esc_attr__( 'Search Name', 'asl_locator'), 
        'radius'            => esc_attr__( 'Radius','asl_locator'), 
        'loading'           => esc_attr__('Loading...', 'asl_locator'), 
        'bck_to_list'       => esc_attr__('Back to List', 'asl_locator'), 
        'print'             => esc_attr__('PRINT', 'asl_locator'),
        'store_direc'       => esc_attr__( 'Store Direction', 'asl_locator'),
        //app.php
        'website'           => esc_attr__('Website','asl_locator'),
        'select_option'     => esc_attr__('Select Option','asl_locator'),
        'search'            => esc_attr__('Search','asl_locator'),
        'all_selected'      => esc_attr__('All selected','asl_locator'),
        'none'              => esc_attr__('None','asl_locator'),
        'all_categories'    => esc_attr__('All Categories','asl_locator'),
        'none_selected'     => esc_attr__('None Selected','asl_locator'),
        'selected'          => esc_attr__('selected','asl_locator'),
        'current_location'  => esc_attr__('Current Location','asl_locator'),
        'select_category'   => esc_attr__('Select a category','asl_locator'),
        'geo'               => esc_attr__('Your Geo Location','asl_locator'),
        'category'          => esc_attr__('Select Category','asl_locator'),
        'none_selected'     => esc_attr__('Select','asl_locator'),
        'fill_form'         => esc_attr__('Please fill up the form.','asl_locator'),
        'label_country'     => esc_attr__('Country','asl_locator'),
        'label_state'       => esc_attr__('State','asl_locator'),
        'label_city'        => esc_attr__('City','asl_locator'),
        'ph_countries'      => esc_attr__('All Countries','asl_locator'),
        'ph_states'         => esc_attr__('All States','asl_locator'),
        'ph_cities'         => esc_attr__('All Cities','asl_locator'),
        'pickup'            => esc_attr__('Pickup Here','asl_locator'),
        'ship_from'         => esc_attr__('Select Store','asl_locator'),
        'direction'         => esc_attr__('Direction','asl_locator'),
        'directions'        => esc_attr__('Directions','asl_locator'),
        'zoom'              => esc_attr__('Zoom','asl_locator'),
        'all_brand'         => esc_attr__('All Brands','asl_locator'),
        'all_special'       => esc_attr__('All Specialities','asl_locator'),
        'reset_map'         => esc_attr__('Reset Map','asl_locator'),
        'reload_map'        => esc_attr__('Scan Area','asl_locator'),
        'your_cur_loc'      => esc_attr__('Your Current Location','asl_locator'),
        /*Template words*/
        'miles'             => esc_attr__('Miles','asl_locator'),
        'km'                => esc_attr__('Km','asl_locator'),
        'phone'             => esc_attr__('Phone','asl_locator'),
        'fax'               => esc_attr__('Fax','asl_locator'),

        'app_directions'    => esc_attr__('Fax','asl_locator'),

        'email'             => esc_attr__('Email','asl_locator'),
        'read_more'         => esc_attr__('Read more','asl_locator'),
        'hide_more'         => esc_attr__('Hide Details','asl_locator'),
        'select_distance'   => esc_attr__('Select Distance','asl_locator'),
        'cur_dir'           => esc_attr__('Current+Location','asl_locator'),
        'radius_circle'     => esc_attr__('Radius Circle','asl_locator'),
        //  Tmpl-3
        'back_to_store'     => esc_attr__('Back to stores','asl_locator'),
        'categories_tab'    => esc_attr__('Categories','asl_locator'),
        'distance_title'    => esc_attr__('Distance','asl_locator'),
        'distance_tab'      => esc_attr__('Distance Range','asl_locator'),
        'geo_location_error'=> esc_attr__('User denied geo-location, check preferences.','asl_locator'),
        'no_found_head'     => esc_attr__('Search!','asl_locator'),
        'brand'             => esc_attr__('Brand','asl_locator'),
        'special'           => esc_attr__('Speciality','asl_locator'),
        'brands'            => esc_attr__('Brands','asl_locator'),
        'specials'          => esc_attr__('Specialities','asl_locator'),
        'manage_brand'      => esc_attr__('Manage Brands','asl_locator'),
        'manage_special'    => esc_attr__('Manage Speciality','asl_locator'),
        'specials'          => esc_attr__('Specialities','asl_locator'),
        'region'            => esc_attr__('Region','asl_locator'),
        'regions'           => esc_attr__('Regions','asl_locator'),
        'category'          => esc_attr__('Category','asl_locator'),
        'within'            => esc_attr__('Within','asl_locator'),
        'country'           => esc_attr__('Select Country','asl_locator'),
        'state'             => esc_attr__('Select State','asl_locator'),
        'in'                => esc_attr__('In','asl_locator'),
        'desc_title'        => esc_attr__('Store Details','asl_locator'),
        'add_desc_title'    => esc_attr__('Additional Details','asl_locator'),
        'closed'            => esc_attr__('Closed','asl_locator'),
        'opened'            => esc_attr__('Open','asl_locator'),
        'open'              => esc_attr__('OPEN','asl_locator'),
        'perform_search'    => esc_attr__('Search an address to see the nearest stores.','asl_locator'),
        'sun'               => esc_attr__('Sun','asl_locator'), 
        'mon'               => esc_attr__('Mon','asl_locator'), 
        'tue'               => esc_attr__('Tues','asl_locator'), 
        'wed'               => esc_attr__('Wed','asl_locator' ), 
        'thu'               => esc_attr__('Thur','asl_locator'), 
        'fri'               => esc_attr__('Fri','asl_locator' ), 
        'sat'               => esc_attr__('Sat','asl_locator'),
        'status'            => esc_attr__('Status','asl_locator'),
        'back'              => esc_attr__('Back','asl_locator'),
        'find_store'        => esc_attr__('Find A Store','asl_locator'),
        'enter_add'         => esc_attr__('Enter your address','asl_locator'),
        'accpt'             => esc_attr__('Accept','asl_locator'),
        'search_near'       => esc_attr__('Search Your Nearest Location','asl_locator'),
        'search_loc1'       => esc_attr__('Search your Location','asl_locator'),
        'sort_by'           => esc_attr__('Sort by','asl_locator'),
        'title'             => esc_attr__('Title','asl_locator'),
        'cities'            => esc_attr__('Cities','asl_locator'),
        'states'            => esc_attr__('States','asl_locator'),
        // asl-store-form
        'reg_store'         => esc_attr__('Register your Store!','asl_locator'),
        'reg_store_ins'     => esc_attr__('Fill up the form of your Store to register it for the approval by the administrator and it will list down in the Store Locator listing.','asl_locator'),
        'reg_store_info'     => esc_attr__('STORE INFORMATION','asl_locator'),
        'reg_company'        => esc_attr__('Company','asl_locator'),
        'reg_name'           => esc_attr__('Name','asl_locator'),
        'reg_web_url'        => esc_attr__('Website URL','asl_locator'),
        'reg_web_url'        => esc_attr__('Website URL','asl_locator'),
        'reg_email_cor'      => esc_attr__('Enter correct email address','asl_locator'),
        'reg_brands'         => esc_attr__('Brands','asl_locator'),
        'reg_specialities'   => esc_attr__('Specialities','asl_locator'),
        'reg_add_loc'        => esc_attr__('ADDRESS LOCATION','asl_locator'),
        'reg_street'         => esc_attr__('Street','asl_locator'),
        'reg_post_code'      => esc_attr__('Postal Code','asl_locator'),
        'reg_lat'            => esc_attr__('Latitude','asl_locator'),
        'reg_long'           => esc_attr__('Longitude','asl_locator'),
        'reg_add_data'       => esc_attr__('Additional Data','asl_locator'),
        'reg_add_desc'       => esc_attr__('Additional Description','asl_locator'),
        'view_desc'          => esc_attr__('View Description','asl_locator'),
        'reg_agree'          => esc_attr__('I agree to terms and conditions and all the provided information is correct','asl_locator'),
        'reg_agree2'         => esc_attr__('Please agree to register for store in the listing.','asl_locator'),
        'reg_registering'    => esc_attr__('Registering...','asl_locator'),
        'reg_register'       => esc_attr__('Register','asl_locator'),

        'missing_dest'       => esc_attr__('Destination missing or invalid','asl_locator'),
        // asl-lead
        'find_dealer'        => esc_attr__('Find a Dealer','asl_locator'),
        'lead_agree'         => esc_attr__('Are you ready to Experience the Difference? Just fill out the form below and one of our helpful Bintelli Representatives will find your nearest dealer and get you in contact!','asl_locator'),
        'lead_enter_name'    => esc_attr__('Please enter your name','asl_locator'),
        'lead_ful_name'      => esc_attr__('Full Name','asl_locator'),
        'lead_valid_email'   => esc_attr__('Please enter valid email','asl_locator'),
        'lead_email'         => esc_attr__('Email','asl_locator'),
        'lead_enter_phone'   => esc_attr__('Please enter phone number','asl_locator'),
        'lead_phone'         => esc_attr__('Phone Name','asl_locator'),
        'lead_enter_zip'     => esc_attr__('Please enter zip code','asl_locator'),
        'lead_zip'           => esc_attr__('Zip Code','asl_locator'),
        'lead_message'       => esc_attr__('Message','asl_locator'),
        'lead_submitting'    => esc_attr__('Submitting...','asl_locator'),
        'lead_submit'        => esc_attr__('Submit','asl_locator'),
        // _agile_modal
        'modal_get_direc'    => esc_attr__('Get Your Directions','asl_locator'),
        'modal_from'         => esc_attr__('From','asl_locator'),
        'modal_to'           => esc_attr__('To','asl_locator'),
        'modal_pre_des_add'  => esc_attr__('Prepopulated Destination Address','asl_locator'),
        'modal_get_direc'    => esc_attr__('GET DIRECTIONS','asl_locator'),
        'modal_geo_pos'      => esc_attr__('LOCATE YOUR GEOPOSITION','asl_locator'),
        'modal_your_add'     => esc_attr__('Your Address','asl_locator'),
        'modal_locate'       => esc_attr__('LOCATE','asl_locator'),
        'modal_use_my_loc'   => esc_attr__('Use my location to find the closest Service Provider near me','asl_locator'),
        'modal_use_loc'      => esc_attr__('USE LOCATION'), 
        // _agile_contact_modal
        'contact_form'     => esc_attr__('Contact Form','asl_locator'),
        'contact_start'      => esc_attr__('stars','asl_locator'),
        'contact_err_name'   => esc_attr__('Please enter your name','asl_locator'),
        'contact_name'       => esc_attr__('Enter your name','asl_locator'),
        'contact_err_email'  => esc_attr__('Please enter valid email','asl_locator'),
        'contact_email'      => esc_attr__('Enter your email','asl_locator'),
        'contact_err_msg'    => esc_attr__('Please enter message','asl_locator'),
        'contact_msg'        => esc_attr__('Message','asl_locator'),
        'contact_submit'     => esc_attr__('Submitting...','asl_locator'),
        );


        return $labels;
   }


   /**
    * [is_enabled Either the functionality is enabled or not?]
    * @return boolean [description]
    */
   public static function is_enabled() {

        return \AgileStoreLocator\Helper::get_configs('tran_lbl');
   }


    /**
    * [get_label GET List of Stores]
    * @return [type] [description]
    */
    public static function get_label($key, $language = '') {

        // Get label by key
        $label = '';

        // Get all labels
        $get_all_labels = self::load_labels();

        if (isset($get_all_labels[$key])) {
            $label = $get_all_labels[$key];
        }
        //  Not found
        else
            $label = $key;
        

        return $label;
    }
  

    /**
    * [load_labels GET List of Stores]
    * @return [type] [description]
    */
    public static function load_labels() {
            
        //  When we have it! send it
        if(self::$all_labels) {

            return self::$all_labels;
        }

        // Store labels in object
        $default_labels = self::asl_default_labels();

        //  Add the DB translation if enabled
        if(self::is_enabled()) {

            global $wpdb;

            // Fetch labels from custom table
            $results = $wpdb->get_results( $wpdb->prepare("SELECT `key`, `value` FROM ".ASL_PREFIX."configs WHERE `type` = %s", 'label'));
            
            foreach ($results as $result) {

                // Set value from database
               $default_labels[$result->key] = $result->value;
            }
        }

        //  keep it
        self::$all_labels = $default_labels;

        return $default_labels;
    }
}