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

function amp_blog_post_map_shortcode( $args ) {
	global $wpdb;

	if( !isset( $args['height'] ) ) {
		$args['height'] = '400px';
	}

	if( !isset( $args['width'] ) ) {
		$args['width'] = '100%';
	}

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
		echo '<div id="amp-map" style="height: '.$args['height'].'; width: '.$args['width'].'"></div>';
		foreach( $rows as $row ) {
			$location = unserialize( $row->meta_value );
			echo '<div class="map-marker" data-post-id="'.$row->post_id.'" data-title="'.$row->post_title.'"
					data-lat="'.$location['lat'].'" data-lng="'.$location['lng'].'"></div>';
		}
	}else{
		echo '<p class="warning">No location data found</p>';
	}
}
add_shortcode( 'blog-post-map', 'amp_blog_post_map_shortcode' );

function amp_map_enqueue_scripts() {

	wp_register_script( 'amp-maps', plugin_dir_url( __FILE__ ) . '/assets/amp-maps.js', array( 'jquery' ));
	wp_enqueue_script( 'amp-maps' );
	wp_enqueue_script( 'amp-google-maps', 'https://maps.googleapis.com/maps/api/js');
}
add_action( 'wp_enqueue_scripts', 'amp_map_enqueue_scripts' );
