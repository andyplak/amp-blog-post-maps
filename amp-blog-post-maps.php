<?php

/*
Plugin Name: Blog post maps
Plugin URI: http://andyplace.co.uk
Description: Customise WordPress with powerful, professional and intuitive fields
Version: 0.1
Author: Andy Place
Author URI: http://www.andyplace.co.uk
*/
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function amp_blog_post_map_shortcode() {
	global $wpdb;

	// Lookup all locations (custom query)
	$rows = $wpdb->get_results($wpdb->prepare(
		"
		SELECT pm.*, p.post_title, p.post_date
		FROM {$wpdb->prefix}postmeta pm, {$wpdb->prefix}posts p
		WHERE meta_key LIKE %s
		AND pm.post_id = p.id
		AND p.post_status = %s
		",
		'locations_%_location', // format stored by ACF
		'publish'
	));

	// output as data attributes within map div
	if( count( $rows ) ) {
		echo '<div class="amp-map">';
		foreach( $rows as $row ) {
			$location = unserialize( $row->meta_value );
			echo '<div class="marker" data-post-id="'.$row->post_id.'" data-title="'.$row->post_title.'"
					data-lat="'.$location['lat'].'" data-lng="'.$location['lng'].'"></div>';
		}
		echo '</div>';
	}else{
		echo '<p class="warning">No location data found</p>';
	}
}
add_shortcode( 'blog-post-map', 'amp_blog_post_map_shortcode' );

