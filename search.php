<?php // (C) Copyright Bobbing Wide 2016

/**
 * Implement the loop for the search page
 * 
 * Basically we don't want any content except the title.
 * 
 * 
 */
function genesis_a2z_search_do_loop() {
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			//do_action( 'genesis_before_entry' );
			printf( '<article %s>', genesis_attr( 'entry' ) );
			//do_action( 'genesis_before_entry_content' );
			//printf( '<div %s>', genesis_attr( 'entry-content' ) );
			do_action( 'genesis_entry_header' );
			//do_action( 'genesis_entry_content' );
			//echo '</div>';
			//do_action( 'genesis_after_entry_content' );
			//do_action( 'genesis_entry_footer' );
			echo '</article>';
			//do_action( 'genesis_after_entry' );
		}
		do_action( 'genesis_after_endwhile' );
	} else {
		do_action( 'genesis_loop_else' );
	}
}

/**
 * Enqueue special styles for search
 */
function genesis_a2z_search_after_footer() {
 bw_trace2();
 bw_backtrace();
 wp_enqueue_style( "search-css", get_stylesheet_directory_uri() . '/search.css', array() );
}

/*
 * Output from genesistant
 *
 * We don't want either post_content nor post_content_nav
 * but we do want the image and may need the post permalink
 * but this should be before the image
 * 
 * `
 * <!--
 * action genesis_entry_content genesis_loop,genesis_entry_content
 *
 * : 8   genesis_do_post_image;1
 * : 10   genesis_do_post_content;1
 * : 12   genesis_do_post_content_nav;1
 * : 14   genesis_do_post_permalink;1--> 
 */
//add_action( "genesis_entry_content", "genesis_do_post_content", 10 );
//add_action( "genesis_entry_content", "genesis_do_post_content_nav", 12 ); 
add_action( "genesis_entry_content", "genesis_do_post_permalink", 14 );

add_action( "genesis_entry_content", "genesis_do_post_permalink", 6 );
 
// Not necessary to remove these hooks if we don't invoke the action
 
//remove_action( "genesis_entry_footer", 'genesis_a2z_post_info' );
//remove_action( "genesis_entry_footer", "genesis_entry_footer_markup_open", 5);
//remove_action( "genesis_entry_footer", "genesis_entry_footer_markup_close", 15 );
//remove_action( "genesis_entry_header", "genesis_do_post_format_image", 4 );

remove_action( "genesis_loop", "genesis_do_loop" );
add_action( "genesis_loop", "genesis_a2z_search_do_loop" );


//add_action( 'genesis_entry_header', 'genesis_post_info', 12 );


//add_action( 'genesis_entry_footer', 'genesis_post_meta' );

add_action( 'genesis_entry_header', 'genesis_do_post_title' );

/*
 * Use our own sidebar for search
 */
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar' );
remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
add_action( 'genesis_after_content', 'genesis_a2z_get_sidebar' );

//add_action( "genesis_after_footer", "genesis_a2z_after_footer" );
add_action( "wp_enqueue_scripts", "genesis_a2z_search_after_footer" );
genesis();
