<?php // (C) Copyright Bobbing Wide 2015,2016

/**
 * Until today 2015/12/17 this file contained _gob__FILE__();
 I must hve been trying to find out how the archive template was being displayed.
 */
 
//remove_action( "genesis_footer_widgets", 
remove_action( "genesis_entry_content", "genesis_do_post_content" );
remove_action( "genesis_entry_footer", 'genesis_oik_post_info' );
remove_action( "genesis_entry_footer", "genesis_entry_footer_markup_open", 5);
remove_action( "genesis_entry_footer", "genesis_entry_footer_markup_close", 15 );



genesis();
