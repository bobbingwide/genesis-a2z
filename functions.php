<?php // (C) Copyright Bobbing Wide 2015, 2016

//* Child theme (do not remove) - is this really necessary? 
define( 'CHILD_THEME_NAME', 'Genesis a2z' );
define( 'CHILD_THEME_URL', 'http://www.bobbingwide.com/oik-themes/genesis-a2z' );
define( 'CHILD_THEME_VERSION', '1.0.6' );

genesis_a2z_functions_loaded();

/**
 * Implement 'wp_ajax_send-attachment-to-editor' to not attach an unattached media item
 * 
 * In WordPress TRAC 22085 there was a change
 * that caused unattached media files (images) to be attached to posts if they are inserted into the post
 *
 * https://core.trac.wordpress.org/ticket/22085
 *
 * If you don't like this strategy you can disable it using this simple, rather hacky, action hook.
 * 
 * It relies on the following code being in wp_ajax_send_attachment_to_editor()
 *
 * `
 * if ( 0 == $post->post_parent && $insert_into_post_id = intval( $_POST['post_id'] ) ) {
 *     wp_update_post( array( 'ID' => $id, 'post_parent' => $insert_into_post_id ) );
 *  }
 * `
 *
 * It also relies on there being no other code that requires the post_id value.
 * If this were not the case we'd have to
 * - reset it in a later hook, which doesn't look particularly possible
 * - or implement a different solution to cause current_user_can() to fail
 * - or apply a pretend setting of $post->post_parent at the end of get_post()
 * 
 */
function dont_attach( $blah ) {
	//$_POST['post_id'] = 0;
}

/**
 * Display footer credits for the oik theme
 *
 * @TODO Reinstate link to bw when site migrated to Genesis theme framework
 * 
 * @param string $text Standard Genesis footer credits to override
 * @return string What we actually want
 */	
function oik_footer_creds_text( $text ) {
	do_action( "oik_add_shortcodes" );
	$text = "[bw_wpadmin]";
  $text .= '<br />';
	$text .= "[bw_copyright]"; 
	$text .= '<hr />';
	$text .= 'Website designed and developed by [bw_link text="Herb Miller" herbmiller.me] [bw_follow_me theme=gener]';
	//$text .= ' of <a href="//www.bobbingwide.com" title="Bobbing Wide - web design, web development">[bw]</a>';
	$text .= '<br />';
	$text .= '[bw_power]';
	$text .= ' and <a href="//oik-plugins.com" title="oik plugins">oik plugins</a>';
  return( $text );
}

/**
 * Register special sidebars 
 *
 * We support special sidebars for
 *   "oik-plugins"
 *   "oik_shortcodes"
 *   "oik_pluginversion"
 *   "shortcode_example"
 *   "download"
 *   "oik-themes"
 *   "archive" pages - where we can't really show the "Information" widget
 *
 * We don't display sidebars for
 * 
 * Everything else may have the default sidebar 
   `
	 'before_widget' => '<widget id="%1$s" name="%1$s" class="widget %2$s">',
      'before_title' => '<title>',
      'after_title' => '</title>',
      'after_widget' => '</widget>'
	 `
 */
function genesis_a2z_register_sidebars() {
  //bw_backtrace();
  $cpts = array( "oik-plugins", "oik_shortcodes", "shortcode_example", "download", "oik_pluginversion", "oik-themes", "archive" );
  $theme_widget_args = array( );
  foreach ( $cpts as $cpt ) {
    $theme_widget_args['group'] = 'default';
    $theme_widget_args['id'] = "$cpt-widget-area";
    $theme_widget_args['name'] = "$cpt widget area";
    $theme_widget_args['description'] = "sidebar for $cpt";  
    genesis_register_sidebar( $theme_widget_args );
  }
}

/**
 * Set EDD checkout image size
 *
 * @TODO Do we need this for genesis-a2z?
 */
function genesis_a2z_edd() {
	add_filter( "edd_checkout_image_size", "goik_edd_checkout_image_size", 10, 2 );
}

function goik_edd_checkout_image_size( $dimensions ) {
	return( array( "auto", "auto" ) );
}

/**
 * Display the post info in our style
 *
 * We only want to display the post date and post modified date plus the post_edit link. 
 * 
 * Note: On some pages the post edit link appeared multiple times - so we had to find a way of fancy way
 * of turning it off, except when we really wanted it. 
 * Solution was to not use "genesis_post_info" but to expand shortcodes ourselves  
 *
 *
 */
function genesis_oik_post_info() {
	remove_filter( "genesis_edit_post_link", "__return_false" );
	$output = genesis_markup( array(
    'html5'   => '<p %s>',
    'xhtml'   => '<div class="post-info">',
    'context' => 'entry-meta-before-content',
    'echo'    => false,
	) );
	$string = sprintf( __( 'Published: %1$s', 'genesis-oik' ), '[post_date]' );
	$string .= '<span class="splitbar">';
	$string .= ' | ';
	$string .= '</span>';
	$string .= '<span class="lastupdated">';
	$string .= sprintf( __( 'Last updated: %1$s', 'genesis-oik' ), '[post_modified_date]' );
	$string .= '</span>';
  $string .= ' [post_edit]';
	//$output .= apply_filters( 'do_shortcodes', $string);
	$output .= do_shortcode( $string );
	$output .= genesis_html5() ? '</p>' : '</div>';  
	echo $output;
	add_filter( "genesis_edit_post_link", "__return_false" );
}

/**
 * Display the sidebar for the given post type
 *
 * Normally we just append -widget-area but for some post types we override it 
 * For the archive page it's archive-widget-area
 *
 * Post type  | Sidebar used
 * ---------- | -------------
 * oik_premiumversion | oik_pluginversion-widget-area
 * oik_sc_param | sidebar-alt
 * attachment | sidebar-alt
 * 
 */
function genesis_oik_get_sidebar() {
	//* Output primary sidebar structure
	genesis_markup( array(
		'html5'   => '<aside %s>',
		'xhtml'   => '<div id="sidebar" class="sidebar widget-area">',
		'context' => 'sidebar-primary',
	) );
	do_action( 'genesis_before_sidebar_widget_area' );
	if ( is_archive() )	{
		$post_type = "archive";
	} else {
		$post_type = get_post_type();
	}	
	$cpts = array( "oik_premiumversion" => "oik_pluginversion-widget-area" 
							 , "oik_sc_param" => "sidebar-alt"
							 , "attachment" => "sidebar-alt"
							 );
	$dynamic_sidebar = bw_array_get( $cpts, $post_type, "$post_type-widget-area" ); 
	dynamic_sidebar( $dynamic_sidebar );
	do_action( 'genesis_after_sidebar_widget_area' );
	genesis_markup( array(
		'html5' => '</aside>', //* end .sidebar-primary
		'xhtml' => '</div>', //* end #sidebar
	) );
} 

/**
 * Implement 'genesis_a2z_pre_get_option_site_layout' filter 
 *
 * The _genesis_layout has not been defined so we need to decide based on the 
 * previous setting for the Artisteer theme.
 *
 * @param string $layout originally null
 * @param string $setting the current default setting 
 * @return string $layout which is either to have a sidebar or not
 */
function genesis_a2z_pre_get_option_site_layout( $layout, $setting ) {
	//bw_trace2();
	$artisteer_sidebar = genesis_get_custom_field( "_theme_layout_template_default_sidebar" );
	if ( $artisteer_sidebar ) {	
		$layout = __genesis_return_content_sidebar();
	} else {
		// $layout = __genesis_return_full_width_content();
	}
	return( $layout );
}


/**
 * Register the hooks for this theme
 */
function genesis_a2z_functions_loaded() {
	// Start the engine	- @TODO Is this necessary?
	include_once( get_template_directory() . '/lib/init.php' );
	
	//* Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	//* Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );
	
	// Add support for structural wraps
	add_theme_support( 'genesis-structural-wraps', array(
	 'header',
	 'site-inner',
	 'footer-widgets'
		
	) );

	//* Add support for custom background
	add_theme_support( 'custom-background' );

	//* Add support for 4-column footer widgets - requires extra CSS
	
	add_theme_support( 'genesis-footer-widgets', 2 );

	add_filter( 'genesis_footer_creds_text', "oik_footer_creds_text" );
	
  add_filter( 'genesis_pre_get_option_site_layout', 'genesis_a2z_pre_get_option_site_layout', 10, 2 );
	
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	
	// Remove post info
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
	add_action( 'genesis_entry_footer', 'genesis_oik_post_info' );
	add_filter( "genesis_edit_post_link", "__return_false" );
	
  genesis_a2z_register_sidebars();
	
	genesis_a2z_edd();
	
	
	/*
	 * Hook into the AJAX request before WordPress, using priority 0
	 */ 
	add_action( 'wp_ajax_send-attachment-to-editor', 'dont_attach', 0 );

	add_action( 'wp_title', "genesis_a2z_wp_title", 16, 3 );
	add_filter( "genesis_seo_description", "genesis_a2z_seo_description", 10, 3 );
	add_filter( "option_blogdescription", "genesis_a2z_option_blogdescription", 10, 2 );
	
	remove_action( "genesis_site_description", "genesis_seo_site_description" );
	add_action( "genesis_site_description", "genesis_a2z_site_description" );
	
	// Hide the genesis header
	remove_action( "genesis_header", "genesis_header_markup_open", 5 );
	remove_action( "genesis_header", "genesis_header_markup_close", 15 );
	remove_action( "genesis_header", "genesis_do_header", 10 );
	
	add_filter( "genesis_breadcrumb_args", "genesis_a2z_breadcrumb_args" );
	
	add_action( "wp", "genesis_a2z_wp_query" );

}

/**
 * Implement 'wp' action for genesis-a2z
 * 
 * Adjust filters when we know what's what.
 *
 * When the theme's functions.php file is first loaded it's too early to make a decision about
 * certain hooks. 
 *
	// Add our own for singular but not for archive
	// How do we do this most efficiently
 */
function genesis_a2z_wp_query() { 
	global $wp_query;
	bw_trace2( $wp_query, "wp_query", false );
	bw_backtrace();
	
	if ( !is_archive() ) {
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	}
}
	



/** 
 * Implement 'wp_title' filter after WPSEO_Frontend::title
 * 
 * If strip_tags is not enough we'll use wp_strip_all_tags
 */
function genesis_a2z_wp_title( $title, $sep, $seplocation ) {
	$title = genesis_a2z_expand_shortcodes( $title, true );
	return( $title );
}


function genesis_a2z_seo_description( $description, $inside, $wrap ) {

	//bw_trace2();
	$description = genesis_a2z_expand_shortcodes( $description );
	return( $description );
}

/**
 * Force shortcode expansion if we can
 *
 * @param string $content which may contain shortcodes
 * @param bool $strip_tags true if HTML tags are not allowed
 */
function genesis_a2z_expand_shortcodes( $content, $strip_tags=false ) {
	bw_trace2( null, null, true, BW_TRACE_VERBOSE );
	if ( false !== strpos( $content, "[" ) && did_action( "oik_loaded" ) ) {
		do_action( "oik_add_shortcodes" );
		$content = bw_do_shortcode( $content );
	}
	if ( $strip_tags ) {
		$content = strip_tags( $content );
	}
	return( $content );
}

function genesis_a2z_option_blogdescription( $blogdescription, $option ) {
	$blogdescription = genesis_a2z_expand_shortcodes( $blogdescription );
	return( $blogdescription );
}

	

/**
 * Implement our own SEO site description... if we think we need it 
 */	
function genesis_a2z_site_description() {

	//* Set what goes inside the wrapping tags
	$inside = get_bloginfo( 'description' );

	//* Determine which wrapping tags to use
	$wrap = genesis_is_root_page() && 'description' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';

	//* Wrap homepage site description in p tags if static front page
	$wrap = is_front_page() && ! is_home() ? 'p' : $wrap;

	//* And finally, $wrap in h2 if HTML5 & semantic headings enabled
	$wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h2' : $wrap;

	/**
	 * Site description wrapping element
	 *
	 * The wrapping element for the site description.
	 *
	 * @since 2.2.3
	 *
	 * @param string $wrap The wrapping element (h1, h2, p, etc.).
	 */
	$wrap = apply_filters( 'genesis_site_description_wrap', $wrap );

	//* Build the description
	$description  = genesis_html5() ? sprintf( "<{$wrap} %s>", genesis_attr( 'site-description' ) ) : sprintf( '<%s id="description">%s</%s>', $wrap, $inside, $wrap );
	$description .= genesis_html5() ? "{$inside}</{$wrap}>" : '';

	//* Output (filtered)
	$output = $inside ? apply_filters( 'genesis_seo_description', $description, $inside, $wrap ) : '';

	echo $output;

}

/**
 * Implement "genesis_breadcrumb_args" for Genesis a2z
 *
 * We don't want a prefix.
 * 
 * @param array $args
 * @return array updated args array
 */
function genesis_a2z_breadcrumb_args( $args ) {
	$args['labels']['prefix'] = "";
	return( $args );
}

/**
 * Echo a comment
 *
 * @param string $string the text to echo inside the comment
 */
if ( !function_exists( "_e_c" ) ) { 
function _e_c( $string ) {
	echo "<!--\n";
	echo $string;
	echo "-->";
}
}


