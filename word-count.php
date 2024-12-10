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
	$stripped_content = strip_tags( $content );
	$word_count       = str_word_count( $stripped_content );
	$label            = __( 'Total word count', 'word-count' );
	$content         .= sprintf( '<p>%s: %s</p>', $label, $word_count );

	return $content;
}
