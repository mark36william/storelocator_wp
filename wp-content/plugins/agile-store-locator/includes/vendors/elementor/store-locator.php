<?php


namespace AgileStoreLocator\Vendors\Elementor;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed direptly.
}



/**
 * /**
 * Class Agile Store Locator Elementor StoreLocator
 * @since 1.0.0 
 */

class StoreLocator extends \Elementor\Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'agile-store-locator-addon';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Store Locator', 'asl_locator' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
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
	 * @since 1.0.0
	 *
	 * @access public
	 *
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
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [];
	}


	/**
	 * Check for empty values and return provided default value if required
	 */
	protected function set_default( $value, $default ){
		if( isset($value) && $value!="" ){
			return $value;
		}else{
			return $default;
		}
	}


	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {


		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Agile Store Locator Shortcode', 'asl_locator' ),
			]
		);	

		$this->add_control(
			'agileStoreLocator_notice',
			[
				'label' => __( '', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<strong style="color:red">It is only a shortcode builder. Kindly update/publish the page and check the actually Agile Store Locator on front-end</strong>',
				'content_classes' => 'agileStoreLocator_notice',
			]
		);

		$this->add_control(
			'template',
			[
				'label' => __( 'Select Template', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '0',
				'options' => array(
					'0' => esc_attr__('Template 0','asl_locator'),
					'1' => esc_attr__('Template 1','asl_locator'),
					'2' => esc_attr__('Template 2','asl_locator'),
					'3' => esc_attr__('Template 3','asl_locator'),
					'4' => esc_attr__('Template 4','asl_locator'),
					'list' => esc_attr__('Template List','asl_locator'),
				),
			]
		);

		$this->add_control(
			'search_type',
			[
				'label' => __( 'Search Type', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '0',
				'options' => array(
					'0' => esc_attr__('Search By Address (Google)','asl_locator'),
					'1' => esc_attr__('Search By Store Name (Database)','asl_locator'),
					'2' => esc_attr__('Search By Stores Cities, States (Database)','asl_locator'),
					'3' => esc_attr__('Geocoding on Enter key (Google Geocoding API)','asl_locator'),
				),
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Select Layout', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '0',
				'options' => array(
					'0' => esc_attr__('List Format','asl_locator'),
					'1' => esc_attr__('Accordion (States, Cities, Countries)','asl_locator'),
					'2' => esc_attr__('Accordion (Categories)','asl_locator'),
				),
			]
		);

		$this->add_control(
			'distance_control',
			[
				'label' => esc_html__( 'Distance Control', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'Slider', 'asl_locator' ),
					'1' => esc_html__( 'Dropdown', 'asl_locator' ),
					'2' => esc_html__( 'Boundary Box', 'asl_locator' ),
				],

			]
		);

		$this->add_control(
			'head_title',
			[
				'label' => esc_html__( 'Head Title', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'No. of Stores'

			]
		);


		
		$this->end_controls_section();

		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings();

		$shortcode_attr = array();

		// Template Selection
		((!empty($settings['template']) || $settings['template'] == '0') ? $shortcode_attr['template'] = 'template="'.$settings['template'].'"' : '' );


		// Select Search Type
		((!empty($settings['search_type']) || $settings['search_type'] == '0') ? $shortcode_attr['search_type'] = 'search_type="'.$settings['search_type'].'"' : '' );

		// Layout Selection
		((!empty($settings['layout'])  || $settings['layout'] == '0') ? $shortcode_attr['layout'] = 'layout="'.$settings['layout'].'"' : '' );

		// Select Distance Control
		((!empty($settings['distance_control']) || $settings['distance_control'] == '0') ? $shortcode_attr['distance_control'] = 'distance_control="'.$settings['distance_control'].'"' : '' );


		$shortcode_attr = implode(' ', $shortcode_attr);
		$shortcode = '[ASL_STORELOCATOR '.$shortcode_attr.']';


		echo'<div class="elementor-shortcode asl-free-addon">';
		echo $shortcode;
 		echo'</div>';
	}	
}
