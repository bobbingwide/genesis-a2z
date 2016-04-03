<?php // (C) Copyrigght Bobbing Wide 2016

function genesis_a2z_create_all_post_type_menu_items() {

	$term_id = get_primary_menu(); 
	
	
	
	$post_types = bw_as_array( "oik-plugins,oik-themes,oik_shortcodes,oik_api,oik_file,oik_hook,oik_class,oik_request" );
	
	foreach ( $post_types as $post_type ) {
		$post_type_object = get_post_type_object( $post_type );
		$plural_label = $post_type_object->labels->name;
		
		echo "$post_type, $plural_label" . PHP_EOL;
		//print_r( $post_type_object );
		if ( false !== strpos( $plural_label, "oik" ) ) {
			list( $oik, $plural_label ) = explode( " ", $plural_label );
			$plural_label = ucfirst( $plural_label );
		}
		echo "$post_type, $plural_label" . PHP_EOL;
		
		genesis_a2z_insert_post_type_archive_menu_item( $term_id, $post_type, $plural_label ); 
		//gob();
		
																																							
	}
}

/**
 * Return the ID of the primary menu
 *
 * We could pass this in as an ID! 
 * 
 * 
 * For core.wp.a2z it's 2
 * For qw/oikcom it's ?
 * For qw/wordpress we want the a2z menu which is 321
 * 
 */

function get_primary_menu( $name="Primary" ) {
	$nav_menus = wp_get_nav_menus();
	print_r( $nav_menus );
	foreach ( $nav_menus as $key => $nav_menu ) {
		echo $nav_menu->term_id;
		echo " ";
		echo $nav_menu->name; 
		echo PHP_EOL;
	}
	$term_id = oikb_get_response( "Pick a menu", true );

	return( $term_id );
}
	
	
function genesis_a2z_insert_post_type_archive_menu_item( $term_id, $post_type, $plural_label ) {
  $menu_item_data = array( 'menu-item-type' => 'post_type_archive'
                         //, 'menu-item-object-id' => 
                         , 'menu-item-object' => $post_type
                         , 'menu-item-title' => $plural_label
                         , 'menu-item-status' => 'publish'
                         );
  $menu_item_db_id = wp_update_nav_menu_item( $term_id, 0, $menu_item_data );
	bw_trace2( $menu_item_db_id, "menu_item" );
  return( $menu_item_db_id);
}

genesis_a2z_create_all_post_type_menu_items();  
	
	
