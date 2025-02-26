<?php

namespace AgileStoreLocator\Frontend;

use AgileStoreLocator\Activator;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * The public-facing functionality of the plugin is for the AJAX Requests.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/frontend
 * @author     AgileLogix <support@agilelogix.com>
 */

class Request {

	/**
	 * [search_log Capture analytics logs]
	 * @return [type] [description]
	 */
	public function search_log() {

		global $wpdb;

		$nonce = isset($_GET['nonce'])?$_GET['nonce']:null;
		
		/*
		if ( ! wp_verify_nonce( $nonce, 'asl_remote_nonce' ))
 			die ( 'CRF check error.');
 		*/

 		if(!isset($_POST['is_search'])) {
 			die ( 'CRF check error.');
 		}

		$is_search 	  = ($_POST['is_search'] == '1')?1:0;
		$ip_address   = $_SERVER['REMOTE_ADDR'];


		$ASL_PREFIX = ASL_PREFIX;

		if($is_search == 1) {
			
			$search_str   = isset($_POST['search_str'])? sanitize_text_field($_POST['search_str']): null;
			$place_id     = isset($_POST['place_id'])? sanitize_text_field($_POST['place_id']): '';

			if(!$search_str) {
				echo die('[]');
			}

			//To avoid multiple creations
			$count = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT(*) AS c FROM `{$ASL_PREFIX}stores_view` WHERE (created_on > NOW() - INTERVAL 15 MINUTE) AND place_id = %s",
				$place_id
			));

			if($count[0]->c < 1) {

				$wpdb->query( $wpdb->prepare( "INSERT INTO {$ASL_PREFIX}stores_view (search_str, place_id, is_search, ip_address ) VALUES ( %s, %s, %d, %s )", 
			    	$search_str, $place_id, $is_search ,$ip_address 
				));
			}
		}
		else {

			$store_id   = isset($_POST['store_id'])? sanitize_text_field($_POST['store_id']): null;

			if(!$store_id) {
				echo die('[]');	
			}

			//To avoid multiple creations
			$count = $wpdb->get_results( $wpdb->prepare( "SELECT COUNT(*) AS c FROM `{$ASL_PREFIX}stores_view` WHERE (created_on > NOW() - INTERVAL 15 MINUTE) AND store_id = %s",
				$store_id
			));

			if($count[0]->c < 1) {
				
				$wpdb->query( $wpdb->prepare( "INSERT INTO {$ASL_PREFIX}stores_view (store_id, is_search, ip_address ) VALUES ( %s, %d, %s )", 
			    	$store_id, $is_search ,$ip_address
				));
			}

		}

		echo die('[]');
	}


	/**
	 * [load_stores Load the Stores using AJAX Request]
	 * @return [type] [description]
	 */
	public function load_stores($output_return = false, $_lang = null) {

		global $wpdb;

		$nonce = isset($_GET['nonce'])? $_GET['nonce']: null;
		
		/*
		if ( ! wp_verify_nonce( $nonce, 'asl_remote_nonce' ))
 			die ( 'CRF check error.');
 		*/
		$load_all 	 = (isset($_REQUEST['load_all']) && $_REQUEST['load_all'] == '1' || $output_return)?	true:false;
		$accordion   = (isset($_REQUEST['layout']) && $_REQUEST['layout'] == '1')?true:false;
		$category    = (isset($_REQUEST['category']))? sanitize_text_field($_REQUEST['category']):null;
		$stores      = (isset($_REQUEST['stores']))? sanitize_text_field($_REQUEST['stores']):null;
		$lang      	 = (isset($_REQUEST['asl_lang']))? sanitize_text_field($_REQUEST['asl_lang']): '';
		$branches    = (isset($_REQUEST['branches']))? true: false;


		//	Get the fields
		$ddl_fields  = \AgileStoreLocator\Model\Attribute::get_fields();
		
		$ddl_filters = [];

		foreach($ddl_fields as $ddl_field) {

			$ddl_filters[$ddl_field] = (isset($_REQUEST[$ddl_field]))? sanitize_text_field($_REQUEST[$ddl_field]):null;	
		}

		// ddl_fields in the query
    $ddl_fields_str = \AgileStoreLocator\Model\Attribute::sql_query_fields();
		

		$address_filter = [
			'state'     	=> (isset($_REQUEST['state']))? sanitize_text_field($_REQUEST['state']): null,
			'postal_code'	=> (isset($_REQUEST['postal_code']))? sanitize_text_field($_REQUEST['postal_code']): null,
			'city' 				=> (isset($_REQUEST['city']))? sanitize_text_field($_REQUEST['city']): null
		];

		//	Link type we replace the website with the slug
		$slug_link   = (isset($_GET['slug_link']))?true:false;

		$ASL_PREFIX  = ASL_PREFIX;

		$bound   				= '';

		$extra_sql 			= '';
		$country_field 	= '';

		//	Cache Lang
		if($_lang) {
			$lang = $_lang;
		}
		

		//Load on bound :: no Load all
		if(!$load_all && isset($_GET['nw']) && isset($_GET['se'])) {
			
			$nw     =  $_GET['nw'];
      $se     =  $_GET['se'];

      $a      = floatval($nw[0]);
      $b      = floatval($nw[1]);

      $c      = floatval($se[0]);
      $d      = floatval($se[1]);
	    

			$bound   = "AND (($a < $c AND s.lat BETWEEN $a AND $c) OR ($c < $a AND s.lat BETWEEN $c AND $a))
                  AND (($b < $d AND s.lng BETWEEN $b AND $d) OR ($d < $b AND s.lng BETWEEN $d AND $b))";
    }
    //else if($accordion) {
    else {

   		$country_field = " {$ASL_PREFIX}countries.`country`,";
   		$extra_sql 		 = "LEFT JOIN {$ASL_PREFIX}countries ON s.`country` = {$ASL_PREFIX}countries.id";
    }
    

    $clause = '';

    if($category) {

			$load_categories = explode(',', $category);
			$the_categories  = array();

			foreach($load_categories as $_c) {

				//	Clean it
				if(is_numeric($_c)) {
					$the_categories[] = $_c;
				}
			}

			if(count($the_categories) > 0) {

				$the_categories  = implode(',', $the_categories);
				$category_clause = " AND id IN (".$the_categories.')';
				$clause 		     = " AND {$ASL_PREFIX}stores_categories.`category_id` IN (".$the_categories.")";
			}
		}


    // If marker param exist
		if($stores) {

			$stores = explode(',', $stores);

			//only number
			$store_ids = array();
			foreach($stores as $m) {

				if(is_numeric($m)) {
					$store_ids[] = $m;
				}
			}

			if($store_ids) {

				$store_ids = implode(',', $store_ids);
				$clause    .= " AND s.`id` IN ({$store_ids})";				
			}
		}


		//	Apply the where clause for the ddl_filter
		foreach($ddl_filters as $filter_key => $filter_value) {

			if($filter_value) {

				//  Clean the values
	      $filter_value = explode(',', $filter_value);
	      $filter_value = array_map( 'absint', $filter_value );
	      
	      //	When we have values
	      if($filter_value) {

	      	$conditions 	 = array_map(function($value) use ($filter_key) { return "FIND_IN_SET('$value', s.`$filter_key`)"; }, $filter_value);
					$clause 			 = " AND (".implode(' OR ', $conditions).')';

					//$clause 			 = " AND FIND_IN_SET('{$filter_value}',s.`$filter_key`)";
	      }
			}
		}

		//	Add the branch Clauses in the query
		$branch_field = '';
		$branch_join 	= '';
			
		//	When we have branches enabled
		if($branches) {

			$branch_field = "GROUP_CONCAT(DISTINCT m.`store_id`) AS 'childs',";
			$branch_join  = "LEFT JOIN (SELECT option_value, store_id  FROM `{$ASL_PREFIX}stores_meta` WHERE  option_name = 'p_id') m ON s.id = m.option_value";
		}

		$query   = "SELECT s.`id`, `title`, {$branch_field} `description`, `street`,  `city`,  `state`, `postal_code`, {$country_field} `lat`,`lng`,`phone`,  `fax`,`email`,`website`,`logo_id`,{$ASL_PREFIX}storelogos.`path`,`marker_id`,`description_2`,`open_hours`, `ordr`, `custom`,`slug`,$ddl_fields_str,
					group_concat(DISTINCT category_id) as categories FROM {$ASL_PREFIX}stores as s 
					$branch_join
					LEFT JOIN {$ASL_PREFIX}storelogos ON logo_id = {$ASL_PREFIX}storelogos.id
					LEFT JOIN {$ASL_PREFIX}stores_categories ON s.`id` = {$ASL_PREFIX}stores_categories.store_id
					$extra_sql
					WHERE (s.`pending` IS NULL OR s.`pending` = '') AND s.`lang` = '$lang' AND (is_disabled is NULL || is_disabled = 0) AND (`lat` != '' AND `lng` != '') {$bound} {$clause}";

		///	Address Filter Clause
		$addr_prepare_values = [];			
		
		foreach ($address_filter as $addr_attr => $addr_value) {
				
			if($addr_value) {
				$query  .= " AND `$addr_attr` = %s";

				$addr_prepare_values[] = sanitize_text_field($addr_value); 
			}
		}


		//	call the prepare for the address filter values, as they are strings
		if(count($addr_prepare_values) > 0) {

			$query = $wpdb->prepare($query, $addr_prepare_values);
		}

		//	add a limit of 25K
		$query .= " GROUP BY s.`id` ORDER BY `title` LIMIT 25000;";
	
		$all_results = $wpdb->get_results($query);


		$debug_error = true;

		if($debug_error) {

			$err_message = isset($wpdb->last_error)? $wpdb->last_error: null;
			
			if(!$all_results && $err_message) {

				$database = $wpdb->dbname;

				//  Check if the new columns are there or not
	      $sql  = "SELECT count(*) as c FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$ASL_PREFIX}stores' AND COLUMN_NAME = 'lang' AND TABLE_SCHEMA = '{$database}'";
	      $col_check_result = $wpdb->get_results($sql);
	      
	      if($col_check_result[0]->c == 0) {
	          
	          Activator::activate();
	      }

				echo json_encode([$err_message]);die;
			}
		}


		$days_in_words 	= array('sun'=> asl_esc_lbl('sun'), 'mon'=> asl_esc_lbl('mon'), 'tue'=> asl_esc_lbl('tue'), 'wed'=> asl_esc_lbl('wed'),'thu'=> asl_esc_lbl('thu'), 'fri'=> asl_esc_lbl('fri'), 'sat'=> asl_esc_lbl('sat'));
		$days 		   		= array('mon','tue','wed','thu','fri','sat','sun');


		//	Only fetch the config when link type is set to rewrite
		$slug_url = '';

		if($slug_link) {

			$rewrite_config = \AgileStoreLocator\Helper::get_configs(['rewrite_slug', 'rewrite_id']);

			if(isset($rewrite_config['rewrite_slug']) && $rewrite_config['rewrite_slug'] && $rewrite_config['rewrite_id']) {

				$slug_url = '/'.$rewrite_config['rewrite_slug'].'/';
			}
			//	rewrite data is incomplete
			else {

				$slug_link = null;
			}
		}


		//	Loop over the rows
		foreach($all_results as $aRow) {

			if($aRow->open_hours) {

				$days_are 	= array();
				$open_hours = json_decode($aRow->open_hours);

				foreach($days as $day) {

					if(!empty($open_hours->$day)) {

						$days_are[] = $days_in_words[$day];
					}
				}

				$aRow->days_str = implode(', ', $days_are);
			}


			//	Decode the Custom Fields
			if($aRow->custom) {

				$custom_fields = json_decode($aRow->custom, true);

				if($custom_fields && is_array($custom_fields) && count($custom_fields) > 0) {

					foreach ($custom_fields as $custom_key => $custom_value) {
						
						$aRow->$custom_key = $custom_value;
					}
				}
			}

			if($aRow->country) {
				$aRow->country = esc_attr__($aRow->country, 'asl_locator');
			}

			unset($aRow->custom);
	  }

	  //	apply the filter before JSON is sent
		$all_results   = apply_filters( 'asl_filter_stores_result', $all_results);

	  //	To Return the output object
	  if($output_return) {
	  	return $all_results;
	  }

		echo json_encode($all_results);die;
	}


	/**
	 * [add_test_stores Not Used]
	 */
	private function add_test_stores() {
		
		global $wpdb;

		$file_ = '/home/zubair/projects/wordpress_language/lawyer.json';

		if(!file_exists($file_)) {

			die('file not found');
		}

		$content = file_get_contents($file_);
		$stores  = json_decode($content, true);

		
		foreach($stores as $store) {

			$store 		  = $store;
			$categories = $store['categories'];

			unset($store['id']);
			unset($store['days_str']);
			unset($store['categories']);
			unset($store['path']);
			/*unset($store['website_text']);
			unset($store['product']);
			unset($store['whatsapp']);*/

			$countries     = $wpdb->get_results("SELECT id,country FROM ".ASL_PREFIX."countries");
			$all_countries = array();

			foreach($countries as $_country) {

				$all_countries[$_country->country] = $_country->id;
			}

			$store['country'] = (isset($all_countries[$store['country']]))?$all_countries[$store['country']]:'222';
			
			unset($store['url']);

			
			if($wpdb->insert( ASL_PREFIX.'stores', $store)) {

				$store_id = $wpdb->insert_id;

				$categories = explode(',', $categories);

				foreach ($categories as $category) {

					$wpdb->insert(ASL_PREFIX.'stores_categories', 
					 	array('store_id'=>$store_id,'category_id'=>$category),
					 	array('%s','%s'));			
				}
			}
			else {

				$wpdb->show_errors = true;

				die($wpdb->print_error());
			}
		}

		die('all done');
	}


	/**
	 * [lead_request Recieve a lead form details, that will be sent to the dealer]
	 * @return [type] [description]
	 */
	public function lead_request() {

		global $wpdb;		

		$response 					= new \stdclass();
    $response->success 	= false;

		
		$nonce = isset($_POST['vkey'])?$_POST['vkey']:null;

		//	Get the Form Data
 		$lead_details  = isset($_POST['form_params'])? stripslashes_deep($_POST['form_params']): null;

 		//	Separate handling for the store locator contact form
		if($lead_details && isset($lead_details['asl_request_type']) && $lead_details['asl_request_type'] == 'sl-contact') {

			return $this->_contact_form_request($lead_details);
		}


		//	verify it
		if (!$nonce || !wp_verify_nonce( $nonce, 'asl_store_form_nonce' )) {

 			echo json_encode(['success' => false, 'message' => esc_attr__('Error! Invalid lead contact request.','asl_locator')]);die;
		}

 		/*$lead_details = [
 			'name' => 'John Wick',
 			'email' => 'john@wick.com',
 			'postal_code' => '80204',
 			'phone' => '111-222-3333',
 			'message' => 'Just living!'
 		];*/
 		

 		//	Return false, Must be an array
 		if(!is_array($lead_details)) {

 			echo json_encode(['success' => false, 'message' => esc_attr__('Error! Invalid contact details.','asl_locator')]);die;
 		}

 		//	Validate the request parameter
 		if(!$lead_details['name'] || !$lead_details['email'] || !$lead_details['phone'] || !$lead_details['postal_code']) {

 			$response->message = esc_attr__('Error! Please fill up the stores parameters.','asl_locator');
			echo json_encode($response);die;
 		}

 		$lead_config = isset($_POST['config'])? stripslashes_deep($_POST['config']): null;

 		//	Maximum radius to search the store
 		$area_radius  = (isset($lead_config['radius']) && $lead_config['radius'])? floatval($lead_config['radius']): 25;

 		//	Country code to suffix the geocoding request
 		$lead_country = (isset($lead_config['country']) && $lead_config['country'])? $lead_config['country']: '';

 		//	Add the WP filter for the lead user data & config
    apply_filters( 'asl_lead_email_recieved', $lead_details, $lead_config);

 		//	Get the fields data
		$full_name   = strip_tags(sanitize_text_field($lead_details['name']));
		$user_email  = strip_tags(sanitize_text_field($lead_details['email']));
		$postal_code = strip_tags(sanitize_text_field($lead_details['postal_code']));
		$user_phone  = strip_tags(sanitize_text_field($lead_details['phone']));
		$msg_text 	 = strip_tags(sanitize_text_field($lead_details['message']));


 		//1- Get the Coordinates for the provided zipcode
 		$coordinates = \AgileStoreLocator\Helper::getCoordinates('', '', '', $postal_code, $lead_country, \AgileStoreLocator\Helper::get_configs('server_key'));

		$closest_store = null;

 		//2- Calc the closest store to the fetched zipcode WITHIN 25 miles
 		if(isset($coordinates['lat']) && isset($coordinates['lng'])) {

			$closest_store 	 = \AgileStoreLocator\Helper::get_closest_store($coordinates['lat'], $coordinates['lng'], $area_radius);

			// Closest store id
			$response->s_id  = $closest_store->id;
 		}
 		else {

 			$response->s_id  = -1;
 		}

		//	Mail config
		$mail_configs  = \AgileStoreLocator\Helper::get_configs(['admin_notify', 'notify_email', 'lead_follow_up']);

 		//3- Send notification to the dealer, make sure each dealer to recieve only 1 email per day
 		if($full_name && $user_email) {

 			//	Insert into the leads
			if($wpdb->insert(ASL_PREFIX."leads", array(
				'name' 	=> $full_name,
				'email' => $user_email,
				'postal_code' => $postal_code,
				'phone' 		=> $user_phone,
				'message' 	=> $msg_text,
				'store_id' 	=> ($closest_store)?$closest_store->id: null,
				'follow_up' => $mail_configs['lead_follow_up']
			))) {

				
				$subject  = esc_attr__("Store Locator Lead Request",'asl_locator');

			  $content 	= '<p>'.esc_attr__('A new lead is requesting details about the store: ', 'asl_locator').(($closest_store)?$closest_store->title:''). '</p><br>'.
			  						'<p>'.esc_attr__('Full Name: ','asl_locator'). $full_name.'</p>'.
			  						'<p>'.esc_attr__('Email Address: ','asl_locator').'<a href="mailto:'.$user_email.'">'.$user_email.'</a>'.'</p>'.
			  						'<p>'.esc_attr__('Phone: ','asl_locator').'<a href="tel:'.$user_phone.'">'.$user_phone.'</a>'.'</p>'.
			  						'<p>'.esc_attr__('Postal Code: ','asl_locator'). $postal_code.'</p>';

			  //	When we have message text
			 	if($msg_text) {

			 		$content 	.= '<p>'.esc_attr__('Message: ','asl_locator'). $msg_text.'</p>';
			 	}


			 	// Add the Store address
			 	if($closest_store) {

					$locality = trim(implode(', ', array_filter(array($closest_store->city, $closest_store->state, $closest_store->postal_code, $closest_store->country))), ', ');
					$address  = [$closest_store->street, $locality];
					$address  = trim(implode(', ', $address));

					$content 	.= '<p>'.esc_attr__('Store Address: ','asl_locator'). $address.'</p>';
			 	}


			 	// CC headers
			 	$headers  = [];

			 	//	main email sent to the email
			 	$to_email = ($closest_store && $closest_store->email)? $closest_store->email: $mail_configs['notify_email'];

			 	//	when we have recepient send the email
			 	if($to_email && $mail_configs['admin_notify']) {

			 		//	Add CC to the admin email when we have a two recievers
				 	if($mail_configs['notify_email'] && $to_email != $mail_configs['notify_email']) {
				 		$headers[] = 'Cc: '.$mail_configs['notify_email'];
				 	}

				  //	Send a email notification
					$response->s = \AgileStoreLocator\Helper::send_email($to_email, $subject, $content, $headers);
			 	}


				$response->message = esc_attr__('Thank you! we have received your request.','asl_locator');
				$response->success = true;
			}
			else {

				$response->message = esc_attr__('Error! fail to send your email to the store.','asl_locator');
			}
 		}
 		else {

 			$response->message = esc_attr__('Error! Sorry there are no close store to your region.','asl_locator');
 		}

 		echo json_encode($response);
		die;
	}


	/**
	 * [_contact_form_request Handle the request by the contact form in store locator, email, message, rating, id]
	 * @param  [type] $lead_details [description]
	 * @return [type]               [description]
	 */
	private function _contact_form_request($lead_details) {

		global $wpdb;

		$response 					= new \stdclass();
    $response->success 	= false;

		$nonce = isset($_POST['vkey'])?$_POST['vkey']:null;

		//	verify the request
		if (!$nonce || !wp_verify_nonce( $nonce, 'asl_remote_nonce' )) {

 			echo json_encode(['success' => false, 'message' => esc_attr__('Error! Invalid contact request.','asl_locator')]);die;
		}

		//	Return false, Must be an array
 		if(!is_array($lead_details)) {

 			echo json_encode(['success' => false, 'message' => esc_attr__('Error! Invalid contact details.','asl_locator')]);die;
 		}

 		//	Validate the request parameter
 		if(!$lead_details['email'] || !$lead_details['id']) {

 			$response->message = esc_attr__('Error! Please fill up the stores parameters.','asl_locator');
			echo json_encode($response);die;
 		}

 	
		//	Get the fields data
		$store_id    = strip_tags(sanitize_text_field($lead_details['id']));
		$full_name   = strip_tags(sanitize_text_field($lead_details['name']));
		$user_email  = strip_tags(sanitize_text_field($lead_details['email']));
		$user_rating = (isset($lead_details['rating']))? strip_tags(sanitize_text_field($lead_details['rating'])): null;
		$msg_text 	 = strip_tags(sanitize_text_field($lead_details['message']));


		//	Once in 24 hours
		$count = $wpdb->get_results( $wpdb->prepare('SELECT COUNT(*) AS c FROM `'.ASL_PREFIX.'leads` WHERE (created_on > NOW() - INTERVAL 24 HOUR) AND email = %s',
			$user_email
		));

		//	Already submited?
		if(isset($count[0]->c) && $count[0]->c > 0) {

			echo json_encode(['success' => false, 'message' => esc_attr__('Sorry! you have already submited the form.','asl_locator')]);die;
		}

		//	Get the store
		$selected_store = \AgileStoreLocator\Helper::get_store(intval($store_id));

		//	Mail config
		$mail_configs 	= \AgileStoreLocator\Helper::get_configs(['admin_notify', 'notify_email']);

 		//3- Send notification to the dealer, make sure each dealer to recieve only 1 email per day
 		if(($selected_store && $selected_store->email) || $mail_configs['notify_email']) {

 			//	Insert into the leads
			if($wpdb->insert(ASL_PREFIX."leads", array(
				'name' 	=> $full_name,
				'email' => $user_email,
				'store_id' => $store_id,
				'rating' 	=> $user_rating,
				'message' => $msg_text
			))) {


				//	Send the lead email notification, when notification is enabled
				if($mail_configs['admin_notify']) {


					$subject  = esc_attr__("Store Locator Lead Request",'asl_locator');

				  $content 	= '<p>'.esc_attr__('A new lead is requesting details about the store: ', 'asl_locator').(($selected_store)? $selected_store->title:''). '</p><br>'.
				  						'<p>'.esc_attr__('Full Name: ','asl_locator'). $full_name.'</p>'.
				  						'<p>'.esc_attr__('Email Address: ','asl_locator').'<a href="mailto:'.$user_email.'">'.$user_email.'</a>'.'</p>'.
				  						'<p>'.esc_attr__('Rating: ','asl_locator'). $user_rating.'</p>';

				  //	When we have message text
				 	if($msg_text) {

				 		$content 	.= '<p>'.esc_attr__('Message: ','asl_locator'). $msg_text.'</p>';
				 	}


				 	// CC headers
				 	$headers  = [];

				 	//	main email sent to the email
				 	$to_email = $mail_configs['notify_email'];
				 	$cc_email = ($selected_store && $selected_store->email)? $selected_store->email: null;


				 	//	Send to store when notifer is missing
				 	if(!$to_email) {

				 		$to_email = $cc_email;
				 	}
				 	//	Add CC to the admin email when we have a two recievers
				 	else if($cc_email) {
				 		$headers[] = 'Cc: '.$cc_email;
				 	}

				  //	Send a email notification
					$response->s = \AgileStoreLocator\Helper::send_email($to_email, $subject, $content, $headers);
				}


				$response->message = esc_attr__('Thank you! we have received your request.','asl_locator');
				$response->success = true;
			}
 		}
 		else {

 			$response->message = esc_attr__('Error! Sorry there are no close store to your region.','asl_locator');
 		}

 		echo json_encode($response);
		die;
	}

	/**
	 * [register_store Register a Store for the Pending State]
	 * @return [type] [description]
	 */
	public function register_store() {

		global $wpdb;
		
		$nonce = isset($_POST['vkey'])?$_POST['vkey']:null;

		//	verify it
		if (!$nonce || !wp_verify_nonce( $nonce, 'asl_store_form_nonce' )) {

			//	is nounce verified? why it failed
			$is_verified = null;

			if($nonce) {
				$is_verified = wp_verify_nonce( $nonce, 'asl_store_form_nonce' );
			}

 			echo json_encode(['success' => false, 'nonce'=> $nonce, 'verify' => $is_verified, 'message' => esc_attr__('Error! Invalid register request.','asl_locator')]);die;
		}
 		

 		//	Get the Form Data
 		$form_data  = isset($_POST['form_params'])? stripslashes_deep($_POST['form_params']): null;


 		//	Return false, Must be an array
 		if(!is_array($form_data)) {

 			echo json_encode(['success' => false, 'message' => esc_attr__('Error! Invalid parameters provided.','asl_locator')]);die;
 		}

 		$fields = \AgileStoreLocator\Helper::get_custom_fields();


 		$custom_field_data = [];

		// Custom Field Data validation
		if($fields) {

			//	Loop over the fields to clean
			foreach ($fields as $cf_key => $cf) {
					
					if(isset($form_data[$cf_key])) {

						$custom_field_data[$cf_key] = strip_tags(sanitize_text_field($form_data[$cf_key]));	
					}
			}
		}
		
		//	Validate if the email is already registered
		$email_address = sanitize_text_field($form_data['email']);

		/*
		$email_exist   = $wpdb->get_var($wpdb->prepare("SELECT count(*) as 'c' FROM `".ASL_PREFIX."stores` WHERE email = %s", $email_address));

		if($email_exist > 0) {

			echo json_encode(['success' => false, 'message' => esc_attr__('Error! the provided email address already exist.','asl_locator')]);die;
		}
		*/


		// JSON encode
		$custom_field_data 	= json_encode($custom_field_data);

 		//	Use Description as Title as there must be a Title
 		if(!$form_data['title']) {
 			$form_data['title'] 			= $form_data['description'];
 			$form_data['description'] = '';
 		}

 		$store_attrs = array( 
        'title' 				=> isset($form_data['title'])? sanitize_text_field($form_data['title']): null,
        'description' 	=> isset($form_data['description'])? sanitize_text_field($form_data['description']): null,
        'phone' 				=> isset($form_data['phone'])? sanitize_text_field($form_data['phone']): null,  
        'fax' 					=> isset($form_data['fax'])? sanitize_text_field($form_data['fax']): null,
        'email' 				=> isset($form_data['email'])? sanitize_text_field($form_data['email']): null,
        'street' 				=> isset($form_data['street'])? sanitize_text_field($form_data['street']): null,
        'postal_code' 	=> isset($form_data['postal_code'])? sanitize_text_field($form_data['postal_code']): null,
        'city' 					=> isset($form_data['city'])? sanitize_text_field($form_data['city']): null,
        'state' 				=> isset($form_data['state'])? sanitize_text_field($form_data['state']): null,
        'lat' 					=> isset($form_data['lat'])? sanitize_text_field($form_data['lat']): null,
        'lng' 					=> isset($form_data['lng'])? sanitize_text_field($form_data['lng']): null,
        'website' 			=> isset($form_data['website'])? $this->fixURL(sanitize_text_field($form_data['website'])): null,
        'country' 			=> isset($form_data['country'])? sanitize_text_field($form_data['country']): null,
        'description_2' => isset($form_data['description_2'])? sanitize_text_field($form_data['description_2']): null
      );

 		//	Get the values of the dropdowns
 		$ddl_fields = \AgileStoreLocator\Model\Attribute::get_fields();
		
		foreach($ddl_fields as $ddl_field) {

			$store_attrs[$ddl_field] = isset($form_data[$ddl_field])? sanitize_text_field($form_data[$ddl_field]):'';
		}


 		//	Validate the parameters
 		if(!$store_attrs['title'] || !$store_attrs['city'] || !$store_attrs['country']) {

			echo json_encode(['success' => false, 'message' => esc_attr__('Error! Please fill up the stores parameters.','asl_locator')]);die;
 		}

 		
 		$default_attrs = array(
 			'is_disabled' => '0',
      'logo_id' 		=> '0',
      'marker_id'   => '1',
      'custom'			=> $custom_field_data,
      'open_hours'	=> '{"mon":"1","tue":"1","wed":"1","thu":"1","fri":"1","sat":"1","sun":"1"}',
      'ordr'				=> '0',
      'pending'			=> '1',
      // 'slug'				=> \AgileStoreLocator\Helper::slugify($form_data),
      'slug'				=> \AgileStoreLocator\Schema\Slug::slugify($form_data, $custom_field_data),
      'updated_on' 	=> date('Y-m-d H:i:s')
 		);

 		//	Merge Data
 		$store_attrs = array_merge($store_attrs, $default_attrs);


 		//	Valid Parameters to match
		if($wpdb->insert(ASL_PREFIX."stores", $store_attrs)) {

			$store_id = $wpdb->insert_id;

			//	Add the categories
 			if(isset($form_data['categories'])) {
 				
 				//	Make it string to array explode with comma
 				$form_data['categories'] = explode(',', $form_data['categories']);

				foreach ($form_data['categories'] as $category) {	

					$wpdb->insert(ASL_PREFIX.'stores_categories', 
					 	array('store_id' => $store_id, 'category_id' => intval($category)),
					 	array('%s','%s'));			
				}
			}

			//	Send a notification
			\AgileStoreLocator\Admin\Store::register_notification($store_attrs, $store_id);

			echo json_encode(['success' => true, 'message' => esc_attr__('Success! Store is registered successfully.','asl_locator')]);die;				
		}
		else {

			echo json_encode(['success' => false, 'message' => esc_attr__('Error! Invalid Store data provided.','asl_locator')]);die;
		}
	}


	/**
	 * [store_ratings get the stores ratings]
	 * @return [type] [description]
	 */
	public function store_ratings() {

		global $wpdb;

		$query   = 'SELECT SUM(rating)AS rating, store_id, COUNT(*) AS c
								FROM '.ASL_PREFIX.'leads 
								WHERE rating > 0
								GROUP BY store_id
								ORDER BY rating DESC;';
		
		$all_results = $wpdb->get_results($query);

		$all_ratings = [];

		foreach($all_results as $row) {

			$all_ratings[$row->store_id] = $row->rating;
		}

		echo json_encode($all_ratings);die;
	}


	/**
   * [fixURL Add https:// to the URL]
   * @param  [type] $url    [description]
   * @param  string $scheme [description]
   * @return [type]         [description]
   */
  private function fixURL($url, $scheme = 'http://') {

    if(!$url)
      return '';

    return parse_url($url, PHP_URL_SCHEME) === null ? $scheme . $url : $url;
  }


	/**
	 * [debug Private debug]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function debug($data) {

		echo '<pre>';
		print_r($data);
		echo '</pre>';
		die;
	}

}
