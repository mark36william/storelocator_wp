<?php


namespace AgileStoreLocator\Vendors;

/**
 *
 * @link       https://agilelogix.com
 * @since      1.0.0
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator//includes/vendors
 */

/**
 * Borlabs Plugin Class
 * 
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes
 * @author     AgileLogix <support@agilelogix.com>
 */
class Borlabs {


	private $blocker_id = 'agilestorelocator';

	/**
	 * Add the intialization
	 *
	 * @since    4.7.8
	 */
	public function initialize() {


		$borlabs_blocker = $this->get_the_blocker();

		if(!$borlabs_blocker) {
			
			$this->register_the_blocker();
		}
		
		add_filter( 'borlabsCookie/contentBlocker/modify/content/'.$this->blocker_id, [$this, 'blocker_content'], 10, 2 );		
	}

	/**
	 * [register_the_blocker Register the Blocker]
	 * @return [type] [description]
	 */
	public function register_the_blocker() {

		$contentBlockerId = $this->blocker_id;

		$name 						= 'Agile Store Locator WordPress';
		$description			= '';
		$privacyPolicyURL	= 'https://policies.google.com/privacy?hl=en';
		$hosts						= []; //maps.google.com


		//$thumbnail 		  = BORLABS_COOKIE_PLUGIN_URL . 'images/bct-google-maps.png';

		$thumbnail 				= ASL_URL_PATH.'public/images/google-maps.png'; 

		//	Add our own file
		/*
		if(!file_exists($thumbnail)) {
			$thumbnail 			= ASL_PLUGIN_PATH.'public/images/google-maps.png'; 
		}
		*/


		$previewHTML		  = '<div class="_brlbs-content-blocker">
												<div class="_brlbs-embed _brlbs-store-locator">
				                    <p class="_brlbs-thumbnail"><img src="'.$thumbnail.'" alt="'.$name.'"></p>
				                    <div class="_brlbs-caption">
				                        <p>' . __( 'Google Maps has been blocked.<br>Click on <strong>Load Store Locator</strong> to unblock Google Maps library.<br> further details about the <a href="https://policies.google.com/privacy?hl=en" target="_blank" rel="nofollow">Google Maps Privacy Policy</a> . ', 'asl_locator' ) . '</p>
				                        <a class="_brlbs-btn" data-borlabs-cookie-unblock role="button">'.__( 'Load Store Locator', 'asl_locator' ) . '</a></p>
				                    </div>
				                </div></div>';
		
		$previewCSS		  	= '';
		
		//	Register the blocker
		BorlabsCookieHelper()->addContentBlocker($contentBlockerId, $name, $description = '', $privacyPolicyURL = '', $hosts, $previewHTML, $previewCSS, $globalJS = '', $initJS = '', $settings = [], $status = true, $undeletable = false);
	}

	/**
	 * [blocker_content description]
	 * @param  [type] $content    [description]
	 * @param  array  $attributes [description]
	 * @return [type]             [description]
	 */
	public function blocker_content($content, $attributes = []) {

		$blocker = $this->get_the_blocker();

		
		if($blocker) {

			//	Initialize the Store Locator
			$initJS  = 'asl_gdpr(true);';

			BorlabsCookieHelper()->updateContentBlockerJavaScript($this->blocker_id, $globalJS = '', $initJS, $settings = []);

    	return $blocker['previewHTML'];
    }

    return null;
	}

	/**
	 * [get_the_blocker Get the Borlabs Blocker by ID]
	 * @return [type] [description]
	 */
	public function get_the_blocker() {

		return BorlabsCookieHelper()->getBlockedContentTypeDataByTypeId($this->blocker_id);
	}

}
