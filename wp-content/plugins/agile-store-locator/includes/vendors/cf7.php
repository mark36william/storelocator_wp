<?php

namespace AgileStoreLocator\Vendors;


/**
 *
 * This class defines all the codes of the CF7 Merger with the Agile Store Locator
 *
 * @link       https://agilelogix.com
 * @since      4.7.21
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes\vendors
 * @author     Your Name <support@agilelogix.com>
 */
class CF7 {
	
	/**
	 * [__construct description]
	 */
	public function __construct() {

	}


	/**
	 * [form_field Return the CF7 form fields]
	 * @return [type] [description]
	 */
	public function form_field() {


	}


	/**
	 * [before_mail_hook description]
	 * @return [type] [description]
	 */
	public function before_mail_hook() {

		add_action( 'wpcf7_before_send_mail', array($this, 'before_mail_hook_execute'), 10, 3);
	}


	/**
	 * [before_mail_hook_execute Method executes when hook event is fired]
	 * @param  [type] $cf7 					[description]
	 * @param  [type] &$abort       [description]
	 * @param  [type] $submission   [description]
	 * @return [type]               [description]
	 */
	public function before_mail_hook_execute($cf7, &$abort, $submission) {

		// $submission = WPCF7_Submission::get_instance();
    
    if ($submission) {
      
      $data = $submission->get_posted_data();

      //$cc_email = get_field('job_contact_email_address', $submission->get_meta('container_post_id'));
      $form_data  = $submission->get_posted_data();

			//	Get the field name for the postal code
			$sl_configs = \AgileStoreLocator\Helper::get_configs(['cf7_field', 'admin_notify', 'notify_email', 'server_key', 'country_restrict', 'lead_follow_up']);

			$field_name   = $sl_configs['cf7_field'];

			//	When field name is not empty
			if($field_name && is_array($form_data)) {

				//	Explode the field names
				$field_names	=  explode(',', $field_name);

				//	Field matched?
				$matched = false;

				//	Get the correct field name for the submitted form
				foreach ($field_names as $field) {
						
					//	When a field is matched
					if(isset($form_data[$field])) {

						$field_name = $field;
						$matched 		= true;
						break;
					}
				}


				//	When we have a valid zip code
				if(isset($form_data[$field_name]) && $form_data[$field_name]) {

					//$your_email = $submission->get_posted_data( 'your-email' );

					//	Got the postal code
					$postal_code 			= $form_data[$field_name];

					//	Restrict the Postal Code Geo Search
					$restrict_country = ($sl_configs['country_restrict'])? strtoupper($sl_configs['country_restrict']): 'US';

			 		//	1- Get the Coordinates for the provided zipcode
			 		$coordinates   		= \AgileStoreLocator\Helper::getCoordinates('', '', '', $postal_code, $restrict_country, $sl_configs['server_key']);

			 		//	2- Calc the closest Store to the fetched zipcode, 2000 miles
					$closest_store 		= \AgileStoreLocator\Helper::get_closest_store($coordinates['lat'], $coordinates['lng'], 25);

					//	Save the lead through CF7
					$this->save_lead($form_data, $postal_code, $closest_store, $sl_configs);

			 		//	3- Send notification to the dealer
			 		if(($closest_store && $closest_store->email)) {

			 			//	cc email
			 			$cc_email    = $closest_store->email;

						$mail = $cf7->get_properties();

						$mail['mail']['recipient'] = $cc_email;
						$mail['mail']['body'] 		 = $mail['mail']['body'].'
						Dealer: '.$closest_store->title;
			
			      //$mail['mail']['additional_headers'] .= "\r\nBcc: $cc_email";

			      $cf7->set_properties($mail);
			 		}
				}
			}
    }

    return $cf7;
	}


	/**
	 * [save_lead Save the lead with the closest store]
	 * @param  [type] $form_data     [description]
	 * @param  [type] $postal_code   [description]
	 * @param  [type] $closest_store [description]
	 * @return [type]                [description]
	 */
	public function save_lead($form_data, $postal_code, $closest_store, $sl_configs) {

		global $wpdb;

		//	Lead Data
		$lead_data = ['name' => '', 'email' => '', 'postal_code' => ''];

		$cf7_keys = array_keys($form_data);

		//	map the data into these fields
		$mapping_fields = ['name' => ['title','name'], 'email' => ['email', 'mail'], 'postal_code' => ['zip', 'postal'], 'phone' => ['phone', 'number'], 'message'=> ['message', 'textarea']];

		//	Mapping fields
		foreach($mapping_fields as  $field_key => $field) {

			$matched = null;

			$search_chunks = $field; 

			//	loop over chunks
			foreach($search_chunks as $search_text) {

				//	match it?
				$match_field = preg_grep('/.*'.$search_text.'.*/i', $cf7_keys);

				//	found match?
				if($match_field) {
					$matched = $match_field;
					break;
				}
			}

			//	set the field to the value
			$matched_field = (!empty($matched))? array_shift($matched): null;

			//	When field is matched
			if($matched_field) {

				$lead_data[$field_key] = sanitize_text_field($form_data[$matched_field]);
			}
		}

		//	Insert into the leads
		if(!empty($lead_data)) {

			$lead_data['store_id'] 		= ($closest_store)?$closest_store->id: null;
			$lead_data['follow_up'] 	= ($closest_store)?$closest_store->id: $sl_configs['lead_follow_up'];
			$lead_data['postal_code'] = $postal_code;

			$wpdb->insert(ASL_PREFIX."leads", $lead_data);
		}
	}

}
