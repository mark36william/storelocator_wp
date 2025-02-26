<?php

namespace AgileStoreLocator\Cron;

/**
*
* Store cron events handling,
*
* @package    AgileStoreLocator
* @subpackage AgileStoreLocator/cron/Store
* @author     AgileStoreLocator Team <support@agilelogix.com>
*/
class StoreCron {

  /**
   * [execute_the_cron Execute the Store Cron Job]
   * @return [type] [description]
   */
  public static function execute_the_cron() {

    global $wpdb;
    
    ini_set('memory_limit', '256M');
    ini_set('max_execution_time', 0);

    $type   = 'cron_timestamp';

    //  Check the cron import directory
    $dir    = ASL_UPLOAD_DIR.'cron/';
    
    //  CREATE the CRON Directory
    if(!file_exists($dir)) {
      
      mkdir( $dir, 0775, true );
      return;
    }

    $files      = scandir($dir);

    //  Get the last run timestamp of cronjob
    $cron_stamp = \AgileStoreLocator\Helper::get_setting($type);


    //  When doesn't exist, give a starting value
    if(!$cron_stamp)
      $cron_stamp = '1627295426';

    //  Summaries of the processed files
    $import_file_counts = 0;
    $summaries          = [];
      
    //  Loop over the files
    foreach($files as $file) {

      //  not the directories
      if($file != '.' && $file != '..') {

        // These are the default files and should not be processed
        //if($file == 'demo-import.csv' || $file == 'import-url-demo.csv') {continue;}

        //  Verify file must be a CSV
        $file_extension  = pathinfo($dir.$file, PATHINFO_EXTENSION);
        
        // Only the CSV file
        if($file_extension != 'csv') {continue;}

        //  When this file is modified
        $file_modified_date = filectime($dir.$file);

        //dd(($file_modified_date > $cron_stamp)? 'True': 'False');
        //  When the timestamp of the file is greater, process it
        if($file_modified_date > $cron_stamp) {

          //  Create the class instance for the import_store
          $importer   = new \AgileStoreLocator\Admin\ImportExport;
          $summary = $importer->import_store($file, true);

          $summary->file  = $file;

          $summaries[]    = $summary;

          $import_file_counts++;
        }
      }
    }

    //  Update the Last Run timestamp of cronjob
    if($cron_stamp && $cron_stamp > 0){
      
      \AgileStoreLocator\Helper::set_setting(time(), $type);
    }

    //  Process the summaries when we have imports
    if($import_file_counts > 0) {
      self::_cron_notification($summaries);
    }

    return;
  }


  /**
   * [_cron_notification Send the notification to the owner about the cron import]
   * @param  [type] $summaries [description]
   * @return [type]            [description]
   */
  private static function _cron_notification($summaries) {

    global $wpdb;

    $configs = $wpdb->get_results("SELECT * FROM ".ASL_PREFIX."configs WHERE `key` = 'admin_notify' OR `key` = 'notify_email'");

    $all_configs = array();
    
    foreach($configs as $_config)
      $all_configs[$_config->key] = $_config->value;

    
    //   Validate the admin notification checkbox is enabled
    if(isset($all_configs['admin_notify']) && $all_configs['admin_notify'] == '1') {


      $admin_email = (isset($all_configs['notify_email']) && $all_configs['notify_email'])? $all_configs['notify_email']: null;


      //  Check if the admin email is there
      if($admin_email) {

        // Prepare the summary
        $HTML = '';
        $total_imports = count($summaries);

        foreach($summaries as $summary) {

          // Check the rows imported
          $HTML .= '<div class="summary-row"><p><b>Import File Name: '.$summary->file.'</b></p><p>Total rows imported: '.$summary->imported_rows.'</p>';

          //  When we have a summary
          if(!empty($summary->summary)) {
            $HTML .= '<p>Summary: '.implode(', ', $summary->summary).'</p>';
          }
        }

        $subject = esc_attr__("Store Locator Cron Imported Files: ", 'asl_locator').$total_imports;
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($admin_email, $subject, $HTML, $headers);
      }

    }
  }
}

