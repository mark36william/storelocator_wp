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
 * Class Agile Store Locator Elementor StoreGrid
 * @since 4.8.21 
 */


class StoreGrid extends \Elementor\Widget_Base {

	 /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $AgileStoreLocator    The ID of this plugin.
     */
    private $AgileStoreLocator;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    
	

	/**
	 * Retrieve the widget name.
	 * @access public
	 * @return string Widget name.
	 */
	
	public function get_name() {
		return 'agile-store-grid';
	}

	/**
	 * Retrieve the widget title.
	 * @access public
	 * @return string Widget title.
	 */
	
	public function get_title() {
		return __( 'Store Grid', 'asl_locator' );
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
	 * 
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
				'label' => __( 'Agile Store Grid', 'asl_locator' ),
			]
		);	

		$this->add_control(
			'agileStoreLocator_notice',
			[
				'label' => __( '', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<strong style="color:red">It is only a shortcode builder. Kindly update/publish the page and check the actually store grid on front-end</strong>',
				'content_classes' => 'agileStoreLocator_notice',
			]
		);

		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Total items', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '10'

			]
		);


		$this->add_control(
			'category',
			[
				'label' => __( 'Select Categories', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'classes' => 'asl_ele_dropdown',

				'options' => $categories
			]
		);

		$this->add_control(
			'address',
			[
				'label' => esc_html__( 'Show Address', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'asl_locator' ),
				'label_off' => esc_html__( 'Hide', 'asl_locator' ),
				'return_value' => 'address',
				'default' => 'address',
			]
		);

		$this->add_control(
			'phone',
			[
				'label' => esc_html__( 'Show Phone', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'asl_locator' ),
				'label_off' => esc_html__( 'Hide', 'asl_locator' ),
				'return_value' => 'phone',
				'default' => 'phone',
			]
		);

		$this->add_control(
			'email',
			[
				'label' => esc_html__( 'Show Email', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'asl_locator' ),
				'label_off' => esc_html__( 'Hide', 'asl_locator' ),
				'return_value' => 'email',
				'default' => 'email',
			]
		);
		$this->add_control(
			'url_link',
			[
				'label' => esc_html__( 'Show URL Link', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'asl_locator' ),
				'label_off' => esc_html__( 'Hide', 'asl_locator' ),
				'return_value' => 'url_link',
				'default' => 'url_link',
			]
		);

		$this->add_control(
			'city',
			[
				'label' => esc_html__( 'Filter by City', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => ''

			]
		);

		$this->add_control(
			'state',
			[
				'label' => esc_html__( 'Filter by State', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => ''

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
		

		global $wpdb;

    //  FRONTEND Public 
		$settings = $this->get_settings();

		$hide = array();

		// Display Selection address , phone , email and url_link
		((empty($settings['address']) ) ? $hide['address']  = 'address' : ''  );
		((empty($settings['phone']) )   ? $hide['phone']    = 'phone' : ''  );
		((empty($settings['email']) )   ? $hide['email']    = 'email' : ''   );
		((empty($settings['url_link']) )? $hide['url_link'] = 'url_link' : ''  );

		$data = array();
		
		//  Main array
		((!empty($settings['limit']))    ? $data['limit']  	 = ''.$settings['limit'].'' : '' );
		((!empty($settings['category']))  ? $data['category'] = ''.$settings['category'].'' : '' );
		((!empty($settings['city']) )    ? $data['city']     = ''.$settings['city'].'' : '' );
		((!empty($settings['state']))    ? $data['state']    = ''.$settings['state'].'' : '' );
	

		$hide = implode(',', $hide);
		$hide = array( 'hide' => $hide);

		// Filter data
		$results = array_merge($data,$hide);
	
		//  app instance to create a grid
    	$app = new App($this->AgileStoreLocator, $this->version);
    	echo $app->storeGrid($results);

	}	
}
