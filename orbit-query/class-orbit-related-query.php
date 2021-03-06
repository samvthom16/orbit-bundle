<?php

class ORBIT_RELATED_QUERY extends ORBIT_QUERY_BASE{

	function __construct(){

		$this->shortcode = 'orbit_related_query';

		parent::__construct();

	}

	function get_default_atts() {
		return array(
			'taxonomy'					=> '',
			'posts_per_page'		=> '',
			'style'							=> '',
			'style_id'					=> '',
			'random'						=> '0'
		);
	}


	function plain_shortcode( $atts, $content = false ){

		$orbit_util = ORBIT_UTIL::getInstance();

		ob_start();

		// GET ATTRIBUTES FROM THE SHORTCODE
		$atts = $this->get_atts($atts);

		global $post;

		$post_type = get_post_type();

		$query = "[orbit_query ";

		if( $atts['random'] == 1 ){
			$query .= "post_type='".$post_type."' posts_per_page='".$atts['posts_per_page']."' orderby='rand' ";
		}

		$query .= "post_type='".$post_type."' posts_per_page='".$atts['posts_per_page']."' ";

		if( $atts['taxonomy'] ){
			$post_terms = array();
			$get_terms = wp_get_post_terms( $post->ID, $atts['taxonomy'] );

			foreach ( $get_terms as $key => $value ) {
				array_push( $post_terms , $value->slug);
			}

			if( count( $post_terms ) ){
				$post_terms = implode( ",", $post_terms );
				$query .= "tax_query='".$atts['taxonomy'].":$post_terms' ";
			}

		}

		$query .= "post__not_in=".$post->ID." style='".$atts['style']."' style_id='".$atts['style_id']."' ";
		$query .= "]";

		//echo $query;

		echo do_shortcode( $query );

		return ob_get_clean();
	}



}

global $orbit_related_query;
$orbit_related_query = new ORBIT_RELATED_QUERY;
