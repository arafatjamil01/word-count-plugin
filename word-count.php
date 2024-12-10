<?php
/**
* Plugin Name:       Word count simple plugin
* Plugin URI:        https://github.com/arafatjamil01/word-count-plugin
* Description:       Counts words of a post and renders that.
* Version:           1.0
* Author:            Arafat Jamil
* Author URI:        https://github.com/arafatjamil01
* License:           GPL v2 or later
* Text Domain:       word-count
* Domain Path:       /languages/
*/

// register_activation_hook( __FILE__, 'word_count_activate' );
// function word_count_activate() {}

// register_deactivation_hook( __FILE__, 'word_count_deactivate' );
// function word_count_deactivate() {}

add_action( 'plugins_loaded', 'wordcount_load_textdomain' );

function wordcount_load_textdomain() {
	load_plugin_textdomain( 'word-count', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_filter( 'the_content', 'wordcount_count_words' );

function wordcount_count_words( $content ) {
	$stripped_content = wp_strip_all_tags( $content );
	$word_count       = str_word_count( $stripped_content );
	$label            = __( 'Total word count', 'word-count' );
	$label            = apply_filters( 'wordcount_heading', $label );
	$tag              = apply_filters( 'wordcount_tag', 'p' );

	$content .= sprintf( '<%s>%s: %s</%s>', $tag, $label, $word_count, $tag );

	return $content;
}

add_filter(
	'wordcount_tag',
	function ( $tag ) {
		$tag = 'h3';
		return $tag;
	}
);

add_filter( 'the_content', 'wordcount_reading_time' );

function wordcount_reading_time( $content ) {
	$stripped_content = wp_strip_all_tags( $content );
	$word_count       = str_word_count( $stripped_content );
	$word_count       = 900;
	$reading_minute   = floor( $word_count / 200 ); // Because according to scientists, people can read 200-250 words per minute on average.
	$reading_seconds  = floor( ( ( $word_count / 200 ) * 60 ) % 60 ); // Module by 60 so that, the result doesn't cross 60

	/**
	 * Reading seconds could also be achieved by
	 * floor( $word_count % 200 / (200/60) )
	 * meaning, after doing the reading in minutes % 200 will bring out the remaining number of words, then dividing it by the average reading speed will give the remaining time in seconds.
	 */

	$reading_seconds  = floor( $reading_seconds );

	$is_visible = apply_filters( 'wordcount_reading_time_visibility', true );

	if ( $is_visible ) {
		$label = __( 'Total reading time', 'word-count' );
		$label = apply_filters( 'wordcount_reading_time_heading', $label );
		$tag   = apply_filters( 'wordcount_reading_time_tag', 'p' );

		$content .= sprintf( '<%s>%s: %s minutes %s seconds</%s>', $tag, $label, $reading_minute, $reading_seconds, $tag );
	}

	return $content;
}
