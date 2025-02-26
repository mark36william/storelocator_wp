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
 * Class Agile Store Locator Elementor SearchWidget
 * @since 4.8.21 
 */
 
class SearchWidget extends \Elementor\Widget_Base {
	


	/**
	 * Retrieve the widget name.
	 * @access public
	 * @return string Widget name.
	 */
	
	public function get_name() {
		return 'agile-search-widget';
	}

	/**
	 * Retrieve the widget title.
	 * @access public
	 * @return string Widget title.
	 */
	
	public function get_title() {
		return __( 'Search Widget', 'asl_locator' );
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

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Agile Search Widget', 'asl_locator' ),
			]
		);	

		$this->add_control(
			'agileStoreLocator_notice',
			[
				'label' => __( '', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<strong style="color:red">It is only a shortcode builder. Kindly update/publish the page and check the actually search widget on front-end</strong>',
				'content_classes' => 'agileStoreLocator_notice',
			]
		);


		$this->add_control(
			'category',
			[
				'label' => esc_html__( 'Show Category', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'asl_locator' ),
				'label_off' => esc_html__( 'Hide', 'asl_locator' ),
				// 'return_value' => '1',
				'default' => '1',
			]
		);

		$this->add_control(
			'redirect',
			[
				'label' => esc_html__( 'Redirect', 'asl_locator' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => ''

			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => esc_html__( 'Background Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .your-class' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Button Color', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .your-class' => 'color: {{VALUE}}',
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
		

		// Category 
		((!empty($settings['category']) || $settings['category'] == 'yes') ? $shortcode_attr['category'] = 'category_control="1"' : $shortcode_attr['category'] = 'category_control="0"' );

		// Redirect Url
		((!empty($settings['redirect']) ) ? $shortcode_attr['redirect'] = 'redirect="'.$settings['redirect'].'"' : '' );

		// Backgound Color
		((!empty($settings['bg_color']) ) ? $shortcode_attr['bg_color'] = 'bg-color="'.$settings['bg_color'].'"' : '' );

		// Button Color
		((!empty($settings['btn_color']) ) ? $shortcode_attr['btn_color'] = 'btn-color="'.$settings['btn_color'].'"' : '' );


		$shortcode_attr = implode(' ', $shortcode_attr);
		$shortcode = '[ASL_SEARCH '.$shortcode_attr.']';


		echo'<div class="elementor-shortcode asl-free-addon">';
		echo $shortcode;
 		echo'</div>';
	}	
}
