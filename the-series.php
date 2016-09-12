<?php
/**
 * Plugin Name: The Series
 * Plugin URI:
 * Description: Adds a Shortcode that lists all Posts of a given Term in chronological Order
 * Version: 1.0
 * Author: Benjamin Birkenhake <ben@grim.rocks>
 * Author URI: http://ben.grim.rocks
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * __
 * Falling Water
 *
 */
 
 function the_series_shortcode( $atts ) {
	$output  = ""; 
	
	// Load Term
	if(isset($atts['term'])){
		$term = get_term($atts['term']);
		if(isset($term->term_id)){
			$output .= "<hr/>\n";
			
			$args = array();
			$args["tax_query"] = array(array());
			$args["tax_query"][0]['taxonomy'] = $term->taxonomy;
			$args["tax_query"][0]['field'] = "term_id";
			$args["tax_query"][0]['terms'] = $term->term_id;
			$args["order"] = "ASC";
			$args["posts_per_page"] = -1;
			$posts = get_posts($args);
			if(is_array($posts) and count($posts)>0){
				$output .= "<h3>".__("Alle")." ".count($posts)." ".__("Beiträge der Serie")." '<a href='".get_term_link($term)."'>".$term->name."</a>'</h3>";
				$output .= "<ol>\n";
				foreach($posts as $mypost){
					if(get_the_permalink() != get_the_permalink($mypost)){
						$output .= "<li><a href='".get_the_permalink($mypost)."'>".$mypost->post_title."</a></li>\n";
					}else{
						$output .= "<li class='active'><strong>".$mypost->post_title."</strong></li>\n";
					}
				}
				$output .= "</ol>\n";
			}else{
				$output .= "<!-- Given Term-ID ".$atts['term']." has no Posts. -->";
			}
		}else{
			$output .= "<!-- Given Term-ID ".$atts['term']." is no existing Term. -->";
		}
	}else{
		$output .= "<!-- No Term-ID given. -->";
	}	
	return $output;
}
add_shortcode( 'the-series', 'the_series_shortcode' );