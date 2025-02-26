<?php

namespace AgileStoreLocator;


/**
 * Register Store Type in the WordPress
 *
 * @link       https://agilelogix.com
 * @since      4.8.21
 *
 * @package    AgileStoreLocator
 * @subpackage AgileStoreLocator/includes
 */
class StoreType {


	/**
	 * [init main calle function]
	 * @return [type] [description]
	 */
	public static function init() {

		self::registerType();

		add_filter('posts_pre_query', ['\AgileStoreLocator\StoreType', 'preResultFilter'], 10, 2 );
	}


	/**
	 * [registerType Register as WP type]
	 * @return [type] [description]
	 */
	public static function registerType() {
			
		$labels = [
	    'name'                     => esc_html__( 'Stores', 'asl_locator' ),
	    'singular_name'            => esc_html__( 'Store', 'asl_locator' ),
	    'add_new'                  => esc_html__( 'Add New', 'asl_locator' ),
	    'add_new_item'             => esc_html__( 'Add New Store', 'asl_locator' ),
	  ];


	  $args = [
	    'label'               => esc_html__( 'Stores', 'asl_locator' ),
	    'labels'              => $labels,
	    'description'         => '',
	    'public'              => true,
	    'hierarchical'        => false,
	    'exclude_from_search' => false,
	    'publicly_queryable'  => true,
	    'show_ui'             => false,
	    'show_in_nav_menus'   => false,
	    'show_in_admin_bar'   => false,
	    'show_in_rest'        => true,
	    'query_var'           => true,
	    'can_export'          => true,
	    'delete_with_user'    => true,
	    'has_archive'         => true,
	    'rest_base'           => '',
	    'show_in_menu'        => true,
	    'menu_position'       => '',
	    'menu_icon'           => 'dashicons-store',
	    'capability_type'     => 'post',
	    'supports'            => ['title'],
	    'taxonomies'          => ['category', 'post_tag'],
	    'rewrite'             => [
	      'with_front' => false
	    ],
	  ];

	  register_post_type( 'asl_stores', $args );
	}


	/**
	 * [preResultFilter preResultFilter Add to insert the stores in the type from external table]
	 * @param  [type] $posts    [description]
	 * @param  [type] $wp_query [description]
	 * @return [type]           [description]
	 */
	public static function preResultFilter($posts, $wp_query) {


		if(isset($wp_query->query['post_type']) && $wp_query->query['post_type'] == 'asl_stores') {

	 		global $wpdb;

	 		$all_stores = \AgileStoreLocator\Model\Store::get_stores();

	 		$posts = [];


	 		foreach($all_stores as $store) {

	 			$dated = ($store->updated_on)? $store->updated_on: $store->created_on;

				$post = new \stdClass();
				$post->ID = $store->id;
				$post->post_author 		= 1;
				$post->post_date 			= $dated; //current_time( 'mysql' )
				$post->post_date_gmt 	= $dated; //current_time( 'mysql', 1 )
				$post->post_title 		= $store->title;
				$post->post_content 	= $store->description;
				$post->post_status 		= 'publish';
				$post->comment_status = 'closed';
				$post->ping_status 		= 'closed';
				$post->post_name 			= $store->slug; // append random number to avoid clash
				$post->post_type 			= 'asl_stores';
				$post->filter 				= 'raw'; // important!
			
				$posts[] = new \WP_Post($post);
	 		}
		}

		return $posts;
	}
}
