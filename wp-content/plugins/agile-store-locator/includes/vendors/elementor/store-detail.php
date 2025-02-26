<?php


namespace AgileStoreLocator\Vendors\Elementor;

use AgileStoreLocator\Frontend\App;
use AgileStoreLocator\Helper;
use AgileStoreLocator\Model\Store;
use AgileStoreLocator\Model\Category;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed direptly.
}


/**
 * /**
 * Class Agile Store Locator Elementor StoreDetail
 * @since 4.8.21 
 */

class StoreDetail extends \Elementor\Widget_Base {
	
	/**
	 * Retrieve the widget name.
	 * @access public
	 * @return string Widget name.
	 */
	
	public function get_name() {
		return 'agile-store-detail';
	}

	/**
	 * Retrieve the widget title.
	 * @access public
	 * @return string Widget title.
	 */
	
	public function get_title() {
		return __( 'Store Detail', 'asl_locator' );
	}

	/**
	 * Retrieve the widget icon.
	 * @access public
	 * @return string Widget icon.
	 */
	
	public function get_icon() {
		return 'eicon-map-pin';
	}


	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 * @access public
	 * @return array Widget categories.
	 */
	
	public function get_categories() {
		return [ 'asl_locator' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 * @return array Widget scripts dependencies.
	 */
	
	public function get_script_depends() {
		return [];
	}


	/**
	 * Check for empty values and return provided default value if required
	 */
	protected function set_default( $value, $default ){
		
		if( isset($value) && $value!="" ) {
			return $value;
		}
		else {
			return $default;
		}
	}


	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 * @access protected
	 */
	
	protected function register_controls() {


		// All Categories List
	    $categories = Category::get_all_categories('asl_ele');
	   

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Agile Search Grid', 'asl_locator' ),
			]
		);	

		$this->add_control(
			'agileStoreLocator_notice',
			[
				'label' => __( '', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<strong style="color:red">It is only a shortcode builder. Kindly update/publish the page and check the actually search grid on front-end</strong>',
				'content_classes' => 'agileStoreLocator_notice',
			]
		);


		$this->add_control(
			'asl_store_id',
			[
				'label' 	=> esc_html__( 'Store ID', 'asl_locator' ),
				'type' 		=> \Elementor\Controls_Manager::NUMBER,
				'min' 		=> 1,
				'default'  	=> 1,
			]
		);

		$this->add_control(
			'field',
			[
				'label'   => esc_html__( 'Display Option', 'asl_locator' ),
				'type' 	  => \Elementor\Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title'  		=> esc_html__( 'Title', 'asl_locator' ),
					'address' 		=> esc_html__( 'address', 'asl_locator' ),
					'city' 			=> esc_html__( 'city', 'asl_locator' ),
					'state' 		=> esc_html__( 'state', 'asl_locator' ),
					'country' 		=> esc_html__( 'country', 'asl_locator' ),
					'open_hours' 	=> esc_html__( 'open_hours', 'asl_locator' ),
					'lat' 			=> esc_html__( 'lat', 'asl_locator' ),
					'lng' 			=> esc_html__( 'lng', 'asl_locator' ),
				],

				
			]
		);

		$this->end_controls_section();


		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 * @access protected
	 */
	
	protected function render($atts = null, $content = null) {
		
		$settings = $this->get_settings_for_display();
		// $settings = $this->get_settings();
		$shortcode_attr = array();
		
		// // Store id 
		((!empty($settings['asl_store_id']) ) ? $shortcode_attr['asl_store_id'] = 'sl-store="'.$settings['asl_store_id'].'"' : '' );

		// // fields
		((!empty($settings['field']) ) ? $shortcode_attr['field'] = 'field="'.$settings['field'].'"' : '' );


		$shortcode_attr = implode(' ', $shortcode_attr);
		$shortcode = '[ASL_STORE '.$shortcode_attr.']';
		

		echo'<div class="elementor-shortcode asl-free-addon">';
		echo $shortcode;
 		echo'</div>';
	}	
}
