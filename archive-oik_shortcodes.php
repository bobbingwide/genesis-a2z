<?php // (C) Copyright Bobbing Wide 2015

_e_c( __FILE__ );
/**
 *
 */
remove_action( "genesis_loop_else", "genesis_do_noposts" );

add_action( "genesis_loop_else", "genesis_oik_shortcode_not_defined" );

function genesis_oik_shortcode_not_defined() {
	e( "Sorry, we're unable to display information for the selected shortcode." );
	br( "The shortcode is not yet registered to this site." );
	
	$oik_shortcode = get_query_var( "oik-shortcode" );
	if ( $oik_shortcode ) {
		$p = "Shortcode: ";
		$p .= esc_html( $oik_shortcode );
		p( $p );
	}
	
	$oik_function = get_query_var( "oik-function" );
	if ( $oik_function ) {
	
		$p = "Function: ";
		$p .= esc_html( $oik_function );
		p( $p );
		
	}
	bw_flush();
}


genesis();

