<?php

namespace AgileStoreLocator\Admin;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}


use AgileStoreLocator\Admin\Base;


/**
 * The settings manager including UI, templates, cache etc functionality of the plugin.
 *
 * @link       https://agilestorelocator.com
 * @since      4.7.32
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/Admin/Setting
 */

class Setting extends Base {


  /**
   * [__construct description]
   */
  public function __construct() {
    
    parent::__construct();
  }


  /**
   * [backup_template Backup the Template into theme Root Directory]
   * @return [type] [description]
   */
  public function backup_template() {

    $template  = isset($_REQUEST['template'])? sanitize_text_field($_REQUEST['template']): null;
    $response  = \AgileStoreLocator\Helper::backup_template($template);

    echo json_encode($response);die;
  }


  /**
   * [remove_template Remove the template file from the Theme Directory]
   * @return [type] [description]
   */
  public function remove_template() {

    $template  = isset($_REQUEST['template'])? sanitize_text_field($_REQUEST['template']): null;
    $response  = \AgileStoreLocator\Helper::remove_template($template);

    echo json_encode($response);die;
  }


  /**
   * [expertise_level description]
   * @return [type] [description]
   */
  public function expertise_level() {

    $level_status = (isset($_REQUEST['status']) && $_REQUEST['status'] == '1')?'1':'0';
    
    //  Update the expertise level
    update_option('asl-expertise', $level_status);

    echo json_encode(['success' => true, 'msg' => esc_attr__("Level has been changed.",'asl_locator')]);die;
  }

  /**
   * [change_options Save the Settings in the Settings table]
   */
  public function change_options($json_return = false) {

    global $wpdb;
    $prefix = ASL_PREFIX;

    // Data
    $content = isset($_POST['content'])? stripslashes_deep($_POST['content']): null;
    $type    = isset($_POST['stype'])? stripslashes_deep($_POST['stype']): null;

    //  Response
    $response  = new \stdclass();
    $response->success = false;

    //  When type is hidden
    if(in_array($type, ['hidden', 'cache'])) {

      $c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}settings WHERE `type` = '{$type}'");

      $data_params = array('content' => json_encode($content), 'type'=> $type);


      if($c[0]->count  >= 1) {
        $wpdb->update($prefix."settings", $data_params, array('type'=> $type));
      }
      else {
        $wpdb->insert($prefix."settings", $data_params);
      }

      $response->msg     = esc_attr__("Settings has been updated.",'asl_locator');
      $response->success = true;
    }

    //  return as JSON
    if($json_return) {
      return $response;
    }

  
    echo json_encode($response);die;    
  }




  /**
   * [save_setting save ASL Setting]
   * @return [type] [description]
   */
  public function save_setting() {

    global $wpdb;

    $response  = new \stdclass();

    //  Settings data
    $data_     = stripslashes_deep($_POST['data']);

    //  Remove Script tag will be saved in wp_options
    $remove_script_tag = $data_['remove_maps_script'];
    unset($data_['remove_maps_script']);


    //  Config keys
    $keys     =  array_keys($data_);

    //  Loop over the setting items
    foreach($keys as $key) {

      $wpdb->update(ASL_PREFIX."configs",
        array('value' => $data_[$key]),
        array('key' => $key)
      );
    }

    //  register/de-register the lead cron job for the follow-ups
    if(isset($data_['lead_follow_up']))
      \AgileStoreLocator\Cron\LeadCron::schedule_cron($data_['lead_follow_up'], $data_);



    //  register/de-register the schedule post jobs
    if(isset($data_['store_schedule'])) {

      $schedule_status = ($data_['store_schedule'] == '1')? true: false;

      //  Either enable or disable the job
      \AgileStoreLocator\Admin\Schedule::schedule_stores_job($schedule_status);
    }

    ///////////////////////////
    //  Save Custom Settings //
    ///////////////////////////
    $custom_map_style = $_POST['map_style'];

    //  Custom Map Style
    \AgileStoreLocator\Helper::set_setting(stripslashes($custom_map_style), 'map_style', 'map_style');


    $custom_slug_fields = $_POST['slug_attr_ddl'];

    //  Slug Attributes
    \AgileStoreLocator\Helper::set_setting(stripslashes($custom_slug_fields), 'slug_attr_ddl');
    

    update_option('asl-remove_maps_script', $remove_script_tag);

    $response->msg     = esc_attr__("Setting has been updated successfully.",'asl_locator');
    $response->success = true;

    //  Valid the Default Coordinates
    $is_valid  = \AgileStoreLocator\Helper::validate_coordinate($data_['default_lat'], $data_['default_lng']);

    //  is invalid?
    if(!$is_valid) {

      $response->msg     .= '<br>'.esc_attr__("Error! Default Lat & Lng are invalid values, please try to swap them.",'asl_locator');
      $response->success  = false;
    }

    echo json_encode($response);die;
  }  


    /**
   * [manage_cache Refresh the JSON]
   * @return [type] [description]
   */
  public function manage_cache() {

    global $wpdb;

    $response = new \stdclass();
    $response->success = false;


    $cache_status = (isset($_REQUEST['status']) && $_REQUEST['status'] == '1')?'1':'0';
    $cache_lang   = (isset($_REQUEST['asl-lang']))? sanitize_text_field($_REQUEST['asl-lang']): null;

    //  Todo, Make sure the folder exist?
    if(!file_exists(ASL_UPLOAD_DIR)) {
      mkdir( ASL_UPLOAD_DIR, 0775, true );
    }

    if(!$cache_lang) {
      $response->error = esc_attr__('Error! Lang is not defined.','asl_locator');;
    }

    //  en_US is default
    if($cache_lang == 'en_US')
      $cache_lang = '';


    //  JSON file
    $json_file = 'locator-data'.(($cache_lang)?'-'.$cache_lang: '').'.json';

    //  Generate the JSON file when enabled
    if($cache_status == '1') {

      //  Generate the Output
      $public_request = new \AgileStoreLocator\Frontend\Request();
      $output_result  = $public_request->load_stores(true, $cache_lang);

      //  Save the output
      $response->output   = file_put_contents(ASL_UPLOAD_DIR.$json_file, json_encode($output_result));

      //  When fails
      if(!$response->output) {
        $response->path   = ASL_UPLOAD_DIR.$json_file;
      }

      $response->msg      = esc_attr__('Cache JSON has been generated successfully for language '.$cache_lang,'asl_locator');

    }
    else
      $response->msg      = esc_attr__('Cache JSON is disabled for language '.$cache_lang,'asl_locator');


    //  Save the cache settings
    $this->change_options(true);

    //  Show as success
    $response->success  = true;
  
    echo json_encode($response);die;
  }



  /**
   * [load_custom_template Load ASL Custom Template]
   * @return [type] [description]
   */
  public function load_custom_template() {

    global $wpdb;

    $response          = new \stdclass();
    $response->success = false;

    $data_ = stripslashes_deep($_POST);


    //  List template doesn't have any infobox
    if(in_array($data_['template'], ['template-list', 'store-grid']) && $data_['section'] == 'infobox') {
      
      $response->error = esc_attr__("Template has no infobox.",'asl_locator');
      echo json_encode($response);
      die;
    }

    $html  = '';
    $count = $wpdb->get_results($wpdb->prepare("SELECT COUNT('name') as 'count' FROM ".ASL_PREFIX."settings WHERE `name` = %s AND `type` = %s", $data_['template'], $data_['section']));

    if($count[0]->count  >= 1) {

      //  Template Query 
       $results = $wpdb->get_results($wpdb->prepare("SELECT `content` FROM ".ASL_PREFIX."settings WHERE `name` = %s AND `type` = %s", $data_['template'],$data_['section'])  ,ARRAY_A );

       if ($results)
        $html = $results[0]['content'];

    }
    else {

        //  open stream
        ob_start();
        // include simple products HTML
        include ASL_PLUGIN_PATH.'public/views/'.$data_['template'].'-'.$data_['section'].'.html';

        $html = ob_get_contents();
        
        //  clean it
        ob_end_clean();
        
    }

      
    if (!empty( $html)) {

      $response->html = $html;

      $response->msg     = esc_attr__("HTML added in TextEditor",'asl_locator');
      $response->success = true;
    }


    echo json_encode($response);die;
  }


  /**
   * [save_custom_template Load ASL Custom Template]
   * @return [type] [description]
   */
  public function save_custom_template() {

    global $wpdb;

    $response   = new \stdclass();
    $response->success = false;

    $data_ = stripslashes_deep($_POST);

    //  get previous quantity
    $count = $wpdb->get_results($wpdb->prepare("SELECT COUNT('name') as 'count' FROM ".ASL_PREFIX."settings WHERE `name` = %s AND `type` = %s", $data_['template'],  $data_['section']));
    
    if (!empty($data_['html'])) {

      $data_params = array('name' =>  $data_['template'] ,'type' => $data_['section'],'content' => $data_['html'] );

      if($count[0]->count  >= 1) {
        
        //  Execute the Update Query
        $wpdb->update(ASL_PREFIX."settings", $data_params, array('name'=> $data_['template'] ,'type' => $data_['section']));

      }
      else{
        //  Execute the Insert Query
        $wpdb->insert(ASL_PREFIX."settings", $data_params);
      }

      $response->msg     = esc_attr__("Template Updated",'asl_locator');
      $response->success = true;
    }
        



    echo json_encode($response);die;
  }

  /**
   * [reset_custom_template Load ASL Custom Template]
   * @return [type] [description]
   */
  public function reset_custom_template() {

    global $wpdb;

    $response  = new \stdclass();
    $response->success = false;

    $data_ = stripslashes_deep($_POST);

    //  open stream
    ob_start();

    // include simple products HTML
    include ASL_PLUGIN_PATH.'public/views/'.$data_['template'].'-'.$data_['section'].'.html';
    
    $html = ob_get_contents();
    
    //  clean it
    ob_end_clean();

    $response->html    = $html;
    $response->msg     = esc_attr__("Default template is loaded",'asl_locator');
    $response->success = true;

    echo json_encode($response);die;
  }


    /**
   * [load_ui_settings Load ASL Custom Template]
   * @return [type] [description]
   */
  public function load_ui_settings() {

    global $wpdb;

    $response          = new \stdclass();
    $response->success = false;

    $template = $_POST['template'];

    $colors   = array(
      'template-0'  => array(
        'primary'   => 'clr-primary',
        'header'    => '',
        'header-color'  => '',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'      => '',
        'list-sub-title'  => '',
        'highlighted'     => ''
      ),
      'template-1' => array(
        'primary'   => 'clr-primary',
        'secondary' => '',
        'header'    => 'clr-copy',
        'header-color'  => '',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'      => 'clr-copy',
        'list-sub-title'  => '',
        'highlighted'     => ''
      ),
      'template-2' => array(
        'primary'   => 'clr-primary',
        'header'    => 'clr-copy',
        'header-color'  => '',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'      => 'clr-copy',
        'list-sub-title'  => '',
        'highlighted'     => '',
        'highlighted-list-color' => 'clr-copy'
      ),
      'template-3' => array(
        'primary'   => 'clr-primary',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'              => '',
        'list-sub-title'          => '',
        'highlighted'             => ''
      ),
      'template-4'  => array(
        'primary'   => 'clr-primary',
        'header'    => '',
        'header-color'  => '',
        'infobox-color' => '',
        'infobox-bg'    => '',
        'infobox-a'     => 'clr-copy',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'      => '',
        'list-sub-title'  => '',
        'highlighted'     => ''
      ),
      'template-list' => array(
        'primary'   => 'clr-primary',
        'action-btn-color'  => '',
        'action-btn-bg'     => 'clr-copy',
        'color'   => '',
        'list-bg' => '',
        'list-title'              => ''
      ),
      'template-wc'  => array(
        'primary'        => 'clr-primary',
        'button-color'   => '',
        'button-background'   => 'clr-copy',
        'label-color'   => '',
        'control-color'   => '',
        'control-background'   => '',
        'control-border-color'   => '',
        'input-color'   => '',
        'input-background'   => ''
      ),
    );


    $white                  = '#FFFFFF';
    $black                  = '#000000';

    $tmpl_0_primary         = '#cb2800';
    $tmpl_0_title_color     = '#32373c';
    $tmpl_0_sub_title_color = '#6a6a6a';
    $tmpl_0_list_color      = '#555d66';
    $tmpl_0_header_bg       = '#F7F7F7';
    $tmpl_0_header_color    = '#32373c';
    $tmpl_0_highlighted     = '#F7F7F7';

    $tmpl_1_primary         = '#000000';
    $tmpl_1_secondary       = '#EF5A28';
    $tmpl_1_title_color     = '#32373c';
    $tmpl_1_sub_title_color = '#6a6a6a';
    $tmpl_1_list_color      = '#555d66';
    $tmpl_1_header_bg       = $tmpl_1_primary;
    $tmpl_1_highlighted     = '#F7F7F7';

    $tmpl_2_primary         = '#cb2800';
    $tmpl_2_secondary       = '#cb2800';
    $tmpl_2_title_color     = '#32373c';
    $tmpl_2_sub_title_color = '#6a6a6a';
    $tmpl_2_list_color      = '#555d66';
    $tmpl_2_header_bg       = '#F7F7F7';
    $tmpl_2_highlighted     = '#F7F7F7';

    $tmpl_3_primary         = '#cb2800';
    $tmpl_3_title_color     = '#32373c';
    $tmpl_3_sub_title_color = '#6a6a6a';
    $tmpl_3_list_color      = '#555d66';
    $tmpl_3_header_bg       = '#F7F7F7';
    $tmpl_3_highlighted     = '#F7F7F7';

    //  the default colors that will load with the customizer
    $default_colors = array(
      'template-0'  => array(
        'primary'   => $tmpl_0_primary,
        'header'    => $tmpl_0_header_bg,
        'header-color'  => $tmpl_0_header_color,
        'infobox-color' => $tmpl_0_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_0_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_0_primary,
        'color'   => $tmpl_0_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_0_title_color,
        'list-sub-title'  => $tmpl_0_sub_title_color,
        'highlighted'     => $tmpl_0_highlighted,
        'highlighted-list-color' => $tmpl_0_primary
      ),
      'template-1' => array(
        'primary'   => $tmpl_1_primary,
        'secondary' => $tmpl_1_secondary,
        'header'    => $tmpl_1_header_bg,
        'header-color'  => $tmpl_1_list_color,
        'infobox-color' => $tmpl_1_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_1_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_1_primary,
        'color'   => $tmpl_1_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_1_title_color,
        'list-sub-title'  => $tmpl_1_sub_title_color,
        'highlighted'     => $tmpl_1_highlighted,
        'highlighted-list-color' => $tmpl_1_primary
      ),
      'template-2' => array(
        'primary'   => $tmpl_2_primary,
        'header'    => $tmpl_2_header_bg,
        'header-color'  => $tmpl_2_list_color,
        'infobox-color' => $tmpl_2_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_2_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_2_primary,
        'color'   => $tmpl_2_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_2_title_color,
        'list-sub-title'  => $tmpl_2_sub_title_color,
        'highlighted'     => $tmpl_2_highlighted,
        'highlighted-list-color' => $tmpl_2_primary
      ),
      'template-3'  => array(
        'primary'   => $tmpl_3_primary,
        'header'    => $tmpl_3_header_bg,
        'header-color'  => $tmpl_3_primary,
        'infobox-color' => $tmpl_0_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_3_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_3_primary,
        'color'   => $tmpl_3_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_3_title_color,
        'list-sub-title'  => $tmpl_3_sub_title_color,
        'highlighted'     => $tmpl_3_highlighted,
        'highlighted-list-color' => $tmpl_3_primary
      ),
      'template-4'  => array(
        'primary'   => $tmpl_0_primary,
        'header'    => $tmpl_0_header_bg,
        'header-color'  => $tmpl_0_header_color,
        'infobox-color' => $tmpl_0_list_color,
        'infobox-bg'    => $white,
        'infobox-a'     => $tmpl_0_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_0_primary,
        'color'   => $tmpl_0_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_0_title_color,
        'list-sub-title'  => $tmpl_0_sub_title_color,
        'highlighted'     => $tmpl_0_highlighted,
        'highlighted-list-color' => $tmpl_0_primary
      ),
      'template-list'  => array(
        'primary'   => $tmpl_3_primary,
        'action-btn-color'  => $white,
        'action-btn-bg'     => $tmpl_3_primary,
        'color'   => $tmpl_3_list_color,
        'list-bg' => $white,
        'list-title'      => $tmpl_3_title_color,
        'highlighted'     => $tmpl_3_highlighted
      ),
      'template-wc'  => array(
        'primary'   => $tmpl_0_primary,
        'button-color'   => $white,
        'button-background'   => $tmpl_0_primary,
        'label-color'   => '#010a10',
        'control-color'   => '#010a10',
        'control-background'   => $white,
        'control-border-color'   => '#dee2e6',
        'input-color'   => $black,
        'input-background'   => $white
      )
    );


    $default_fonts  = array(
      'template-0'  => array(
        'font-size'   => 13,
        'title-size'  => 15,
        'btn-size'  => 13
      ),
      'template-1'  => array(
        'font-size'   => 13,
        'title-size'  => 18,
        'btn-size'  => 13
      ),
      'template-2'  => array(
        'font-size'   => 13,
        'title-size'  => 15,
        'btn-size'  => 13
      ),
      'template-3'  => array(
        'font-size'   => 13,
        'title-size'  => 15,
        'btn-size'  => 13
      ),
      'template-list'  => array(
        'font-size'   => 13,
        'title-size'  => 18,
        'list-title-size'  => 18,
        'btn-size'  => 13
      ),
      'template-wc'  => array(
        'font-size'   => 13,
        'font-small-size'   => 10,
        'title-size'  => 16,
        'btn-size'  => 13
      )
    );

    $font_labels = [
      'font-size'   => 'Content Font',
      'title-size'  => 'Title Font',
      'list-title-size'  => 'List Title Font',
      'btn-size'    => 'Button Font'
    ];

    
    $html     = '';
    $fields   = '';


    //  Only get the array of active default color
    $default_colors  = $default_colors[$template];
    $default_fonts   = $default_fonts[$template];

    $fields_settings = \AgileStoreLocator\Helper::get_setting('ui-template', $template);

    if($fields_settings) {

      $fields = json_decode($fields_settings);
    }

    //  Start Stream
    ob_start();

    // include ui customizer fields products HTML
    include ASL_PLUGIN_PATH.'admin/partials/ui-customizer-fields.php';
  
    $html = ob_get_contents();

    //  Clean it
    ob_end_clean();

    $response->html     = $html;
    $response->msg      = esc_attr__("Template UI settings updated",'asl_locator');
    $response->success  = true;

    echo json_encode($response);die;
  }

    /**
   * [save_ui_settings Save ASL UI Settings]
   * @return [type] [description]
   */
  public function save_ui_settings() {

    global $wpdb;


    $response  = new \stdclass();
    $response->success = false;

    $data_    = stripslashes_deep($_POST['formData']);
    $template = sanitize_text_field($_POST['template']);

    $data     = json_encode($data_);
      
    \AgileStoreLocator\Helper::set_setting($data, 'ui-template', $template);

    $response->msg     = esc_attr__("Template updated",'asl_locator');
    $response->success = true;

    echo json_encode($response);die;
  }

  

  /**
   * [save_custom_fields Save Custom Fields AJAX]
   * @return [type] [description]
   */
  public function save_custom_fields() {

    global $wpdb;
    $prefix = ASL_PREFIX;

    $response  = new \stdclass();
    $response->success = false;

    $fields = isset($_POST['fields'])? ($_POST['fields']): [];

    //  Filter the JSON for XSS
    $filter_fields = [];

    foreach($fields as $field_key => $field) {

      $field_key = strip_tags($field_key);

      $field['type']  = strip_tags(sanitize_text_field($field['type']));
      $field['name']  = strip_tags(sanitize_text_field($field['name']));
      $field['label'] = strip_tags(sanitize_text_field($field['label']));

      $filter_fields[$field_key] = $field;
    }

    $c = $wpdb->get_results("SELECT count(*) AS 'count' FROM {$prefix}settings WHERE `type` = 'fields'");

    $data_params = array('content' => json_encode($filter_fields), 'type'=> 'fields');


    if($c[0]->count  >= 1) {
      $wpdb->update($prefix."settings", $data_params, array('type'=> 'fields'));
    }
    else {
      $wpdb->insert($prefix."settings", $data_params);
    }

    /*$wpdb->show_errors = true;
    $response->error = $wpdb->print_error();
    $response->error1 = $wpdb->last_error;*/

    

    $response->msg     = esc_attr__("Fields has been updated successfully.",'asl_locator');
    $response->success = true;

        
    echo json_encode($response);die;
  }




  /**
   * [Reset all store slug  AJAX]
   * @return [type] [description]
   */
  public function reset_all_slugs() {

    global $wpdb;

    $prefix = ASL_PREFIX;

    $response  = new \stdclass();
    $response->success = false;

    // Get all stores
    $stores      = \AgileStoreLocator\Model\Store::get_stores();

    foreach ($stores as $key => $store) {

      // Convert object to array
      $store = (array) $store;

      // Generate new slug, todo add the custom field
      $new_slug  = \AgileStoreLocator\Schema\Slug::slugify($store, null);

      // update all slugs
      $data = [ 'slug' => $new_slug ];
      $where = [ 'id' => $store['id'] ];

      //Count and update all slugs 
      $counter += $wpdb->update( $prefix . 'stores', $data, $where );
    
    }

      $response->msg     = esc_attr__(" $counter Slugs has been updated successfully.",'asl_locator');
      $response->success = true;

      echo json_encode($response);die;

   
  }

}