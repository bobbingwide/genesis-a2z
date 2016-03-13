<?php // (C) Copyright Bobbing Wide 2015
/**
 * Template file for the home page
 *
 * We don't want:
 * - Title
 * - Sidebar - this is chosen using the "Layout settings"
 * - Breadcrumbs - chosen using Genesis > Theme settings > Breadcrumbs: Front page checkbox
 *
 * Found out how to do this by using {@link genesis.wp-a2z.org}
 * and oik-bwtrace and good old grep
 * and most recently... genesis_all()
 */
if ( !is_home() ) {
	remove_action( "genesis_entry_header", "genesis_do_post_title" );
}
genesis();

