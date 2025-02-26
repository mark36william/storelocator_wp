<?php
// namespace AgileStoreLocator\Vendors;

namespace RankMath\Sitemap\Providers;

defined( 'ABSPATH' ) || exit;

/**
 * Implement the MathRank sitemap 
 */
class ASLRankMath implements Provider {


	/**
	 * [get_asl_slug Get the asl slug]
	 * @return [type] [description]
	 */
	public function get_asl_slug(){
		
		$slug = \AgileStoreLocator\Helper::get_configs('rewrite_slug');

		if (!empty($slug)) {
			return $slug;
		}

		return 'stores';
	}
	

	/**
	 * [handles_type description]
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public function handles_type( $type ) {
		$this->get_asl_slug();

		return $this->get_asl_slug()  === $type;
	}


	/**
	 * [get_index_links Add the main stores node in the sitemap]
	 * @param  [type] $max_entries [description]
	 * @return [type]              [description]
	 */
	public function get_index_links( $max_entries ) {
	
		return [
			[
				'loc'     => \RankMath\Sitemap\Router::get_base_url( $this->get_asl_slug().'-sitemap.xml' ),
				'lastmod' => '',
			]
		];
	}

	/**
	 * [get_sitemap_links Add the sitemap links]
	 * @param  [type] $type         [description]
	 * @param  [type] $max_entries  [description]
	 * @param  [type] $current_page [description]
	 * @return [type]               [description]
	 */
	public function get_sitemap_links( $type, $max_entries, $current_page ) {

		  $post_type = 'asl_stores';
		  $link_urls = array();

			wp_reset_query();

			$args = array(
				'posts_per_page'   => -1,
				'orderby'          => 'post_date',
				'order'            => 'DESC',
				'post_type'        => $post_type,
				'post_status'      => 'publish',
				'suppress_filters' => true
			);
			query_posts( $args );

			wp_reset_postdata();

			$posts_array = get_posts( $args );

			$output = '';
			if( !empty( $posts_array ) ) {

				$chf 		= 'weekly';
				$pri 		= 1.0;


		    $page_url = apply_filters( 'wpml_home_url', home_url('/'));

		    // replace the double slash
		    $page_url = preg_replace('#(?<!:)/+#im', '/', $page_url);


		    //  must have a slash in the end
		    if(substr($page_url, -1) != '/') {
		      $page_url = $page_url.'/';
		    }

		    //  Get the detail page
		    $detail_page = $this->get_asl_slug();

				foreach ( $posts_array as $p ) {

					$p->post_type   = $post_type;
					$p->post_status = 'publish';
					
					$url = array();

					if ( isset( $p->post_modified_gmt ) && $p->post_modified_gmt != '0000-00-00 00:00:00' && $p->post_modified_gmt > $p->post_date_gmt ) {
						$url['mod'] = $p->post_modified_gmt;
					} 
		      else {
						if ( '0000-00-00 00:00:00' != $p->post_date_gmt ) {
							$url['mod'] = $p->post_date_gmt;
						} else {
							$url['mod'] = $p->post_date;
						}
					}		


					$url['loc'] = $page_url.$detail_page.'/'.$p->post_name.'/';

					if (!empty($url)) {
						$link_urls[] = $url;
					}

				}
			}

		$links     = $link_urls;


		return $links;
	}

}

