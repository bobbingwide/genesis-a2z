<?php // (C) Copyright Bobbing Wide 2015,2016

/**
 * Until today 2015/12/17 this file contained _gob__FILE__();
 I must have been trying to find out how the archive template was being displayed.
 */
 
//remove_action( "genesis_footer_widgets", 
remove_action( "genesis_entry_content", "genesis_do_post_content" );


/**
 * <!--
action genesis_entry_content genesis_loop,genesis_entry_content

: 8   genesis_do_post_image;1
: 10   genesis_do_post_content;1
: 12   genesis_do_post_content_nav;1
: 14   genesis_do_post_permalink;1--> 
*/


//add_action( "genesis_entry_content", "genesis_do_post_image", 4 );
remove_action( "genesis_entry_content", "genesis_do_post_content", 10 );
remove_action( "genesis_entry_content", "genesis_do_post_content_nav", 12 ); 
 
// Not necessary to remove if we don't invoke the action
 
//remove_action( "genesis_entry_footer", 'genesis_oik_post_info' );
//remove_action( "genesis_entry_footer", "genesis_entry_footer_markup_open", 5);
//remove_action( "genesis_entry_footer", "genesis_entry_footer_markup_close", 15 );

//remove_action( "genesis_entry_header", "genesis_do_post_format_image", 4 );
//remove_action( "genesis_before_entry_conten", "genesis

remove_action( "genesis_loop", "genesis_do_loop" );
add_action( "genesis_loop", "genesis_a2z_do_loop" );

//add_action( "genesis_a2z_entry_content", "genesis_a2z_entry_content" );

//function genesis_a2z_entry_content() {
//
//}


/**
 * Implement a tighter loop for archives
 * 
 * Basically we don't want any content except the featured image
 * 
 * BUT 
 * one day we might look at {@link https://github.com/desandro/masonry}
 * 
 */
function genesis_a2z_do_loop() {
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			do_action( 'genesis_before_entry' );
			printf( '<article %s>', genesis_attr( 'entry' ) );
			//do_action( 'genesis_before_entry_content' );
			//printf( '<div %s>', genesis_attr( 'entry-content' ) );
			do_action( 'genesis_entry_header' );
			do_action( 'genesis_entry_content' );
			//echo '</div>';
			//do_action( 'genesis_after_entry_content' );
			//do_action( 'genesis_entry_footer' );
			echo '</article>';
			do_action( 'genesis_after_entry' );
		}
		do_action( 'genesis_after_endwhile' );
	} else {
		do_action( 'genesis_loop_else' );
	}
}



genesis();
