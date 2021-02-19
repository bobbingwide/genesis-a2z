<?php // (C) Copyright Bobbing Wide 2015-2021

genesis_a2z_functions_loaded();

/**
 * Display footer credits for the oik theme
 *
 * @TODO Reinstate link to bw when site migrated to Genesis theme framework
 * 
 * @param string $text Standard Genesis footer credits to override
 * @return string What we actually want
 */	
function genesis_a2z_footer_creds_text( $text ) {
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
  $cpts = array( "oik-plugins", "oik_shortcodes", "shortcode_example", "download", "oik_pluginversion", "oik-themes", "archive", "search", "block", "block_example" );
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
	add_filter( "edd_checkout_image_size", "ga2z_edd_checkout_image_size", 10, 2 );
	//remove_action( "edd_user_register", 'edd_process_register_form' ); 
	add_action( "edd_process_register_form", "genesis_a2z_edd_process_register_form" );
}

/**
 * Implements 'edd_checkout_image_size'
 */
function ga2z_edd_checkout_image_size( $dimensions ) {
	return( array( "auto", "auto" ) );
}

function genesis_a2z_edd_process_register_form() {
	edd_set_error( 'dummy-registration', __( 'You do realise this is just a demo site.', 'genesis-a2z' ) );
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
function genesis_a2z_post_info() {
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
 * post | sidebar-alt
 * 
 */
function genesis_a2z_get_sidebar() {
	//* Output primary sidebar structure
	genesis_markup( array(
		'html5'   => '<aside %s>',
		'xhtml'   => '<div id="sidebar" class="sidebar widget-area">',
		'context' => 'sidebar-primary',
	) );
	do_action( 'genesis_before_sidebar_widget_area' );
	if ( is_archive() )	{
		$post_type = "archive";
	} elseif ( is_search() ) {
		$post_type = "search";
	} else {
		$post_type = get_post_type();
		bw_trace2( $post_type, "post_type", false, BW_TRACE_VERBOSE );
	}	
	$cpts = array( "oik_premiumversion" => "oik_pluginversion-widget-area" 
							 , "oik_sc_param" => "sidebar-alt"
							 , "attachment" => "sidebar-alt"
							 , "post" => "sidebar-alt"
							 );
	$dynamic_sidebar = bw_array_get( $cpts, $post_type, "$post_type-widget-area" ); 
	bw_trace2( $dynamic_sidebar, "dynamic sidebar", false, BW_TRACE_VERBOSE );
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

	genesis_a2z_version3();

	
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
	// Adds custom logo in Customizer > Site Identity.
//	add_theme_support( 'custom-logo', genesis_get_config( 'custom-logo' ) );
	// Displays custom logo.
	//add_action( 'genesis_site_title', 'the_custom_logo', 0 );
	add_action( 'genesis_site_title', 'the_custom_logo', 6);

	//* Add support for 4-column footer widgets - requires extra CSS
	
	add_theme_support( 'genesis-footer-widgets', 2 );

	add_filter( 'genesis_pre_get_option_footer_text', "genesis_a2z_footer_creds_text" );
	
  add_filter( 'genesis_pre_get_option_site_layout', 'genesis_a2z_pre_get_option_site_layout', 10, 2 );
	
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	
	// Remove post info
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
	add_action( 'genesis_entry_footer', 'genesis_a2z_post_info' );
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
	//remove_action( "genesis_header", "genesis_header_markup_open", 5 );
	//remove_action( "genesis_header", "genesis_header_markup_close", 15 );
	//remove_action( "genesis_header", "genesis_do_header", 10 );
	// Repositions primary navigation menu.
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	add_action( 'genesis_header', 'genesis_do_nav', 12 );
	// Repositions the secondary navigation menu.
	remove_action( 'genesis_after_header', 'genesis_do_subnav' );
	add_action( 'genesis_footer', 'genesis_do_subnav', 10 );
	
	add_filter( "genesis_breadcrumb_args", "genesis_a2z_breadcrumb_args" );
	
	add_action( "wp", "genesis_a2z_wp_query" );
  add_theme_support( 'woocommerce' );
	add_filter( "the_title", "genesis_a2z_the_title", 9, 2 );
	add_theme_support( 'align-wide');
	// Load regular editor styles into the new block-based editor.
	//add_theme_support( 'editor-styles' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	add_filter( "posts_orderby", "genesis_a2z_posts_orderby", 10, 2 );

	add_filter( 'jetpack_contact_form_is_spam', "genesis_a2z_return_error", 20, 2 );

	add_action( 'enqueue_block_editor_assets', 'genesis_a2z_enqueue_block_editor_assets' );
	add_editor_style();

    /**
     * Remove the block based widget editor - which didn't appear to work at all in blocks.wp.a2z
     * on 2020/10/27 with Gutenberg 9.2.2
     */
    remove_theme_support( 'widgets-block-editor' );

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

/**
 * Implement 'the_title' for genesis-a2z
 * 
 * We want to wrap the Summary in a span tag with class="summary"
 * 
 * We cater for the fact that the following filters may be applied 
 * by implementing our filter before these.
 * `
 * : 10   wptexturize;1 convert_chars;1 trim;1 do_shortcode;1
 * : 11   capital_P_dangit;1
 * `
 * 
 * Find   | Convert to
 * ------ | ----------
 * func() - blah | func() span - blah espan 
 * this - that | this span- that espan
 *
 * 
 * Note: We don't expect both '()' and '-' in the text 
 * 	
 * @param string $text the post title being filtered
 * @param ID $id post ID 
 * @return string
 */
function genesis_a2z_the_title( $text, $id ) {
	bw_trace2();
	bw_backtrace();
	if ( doing_filter( 'pre_render_block') ) {
	    return $text;
    }
	if ( doing_filter( 'parse_request') ) {
	   return $text;
    }
    if ( doing_filter( 'the_content') ) {
        return $text;
    }

    $pos = strpos( $text, "() " );
	if ( $pos ) {
		$text = str_replace( "() ", '() <span class="summary">', $text );
		$text .= "</span>";
	}	else {
		$pos = strpos( $text, " - " );
		if ( $pos ) {
			$text = str_replace( " - ", ' <span class="summary">- ', $text );
			$text .= "</span>";
		}
	}
	$text = str_replace( "Wordpress", "Word&#112;ress", $text );
	return( $text );
}

 

/**
 * Displays the A to Z pagination
 */
function genesis_a2z_a2z() {
	$args = genesis_a2z_a2z_display_args();
	$taxonomy = genesis_a2z_a2z_query_letter_taxonomy( "letters", $args );
	do_action( "oik_a2z_display", $taxonomy, $args );
}

/**
 * Determines the args to pass to oik_a2z_display
 */
function genesis_a2z_a2z_display_args() {
	$args = array();
	if ( is_archive() ) {
		$post_type = get_query_var( "post_type" );
		$args['post_type'] = $post_type;
	}
	if ( is_tax() ) {
		$post_type = get_query_var( 'taxonomy' );
		$args['taxonomy'] = $post_type;
	}
	return( $args );
}

/**
 * Returns the Letter taxonomy associated to the post type or taxonomy
 * 
 * If post_type or taxonomy is not set then we return the taxonomy passed
 */ 
function genesis_a2z_a2z_query_letter_taxonomy( $letter_taxonomy, $args ) {
	$post_type = bw_array_get( $args, "post_type", null );
	if ( $post_type ) {
		$oik_letters = array( "oik_shortcodes" => "letters"
												, "oik_api" => "oik_letters"
												, "oik_class" => "oik_letters"
												, "oik_file" => "oik_letters"
												, "oik_hook" => "oik_letters"
			, "shortcode_example" => "letters"
			, "block" => "block_letters"
			, "block_example" => "block_letters"
												);
		$letter_taxonomy = bw_array_get( $oik_letters, $post_type, $letter_taxonomy );
	}
	$taxonomy = bw_array_get( $args, "taxonomy", null );
	if ( $taxonomy ) {
		$oik_letters = array( "block_category" => "block_letters"
			, "block_keyword" => "block_letters"
			, "block_classification" => "block_letters"
			, "block_letters" => "block_letters"
		);
		$letter_taxonomy = bw_array_get( $oik_letters, $taxonomy, $letter_taxonomy);

	}

	return( $letter_taxonomy );
}

/**
 * Displays the A to Z pagination for oik_letters
 */
function genesis_a2z_a2z_letters() {
	$args = genesis_a2z_a2z_display_args();
	$taxonomy = genesis_a2z_a2z_query_letter_taxonomy( "oik_letters", $args );
	do_action( "oik_a2z_display", $taxonomy, $args );
}

/**
 * Implement "posts_orderby" for taxonomies
 *
 * Excluding categories and tags.
 *
 * @param string $orderby - current value of orderby
 * @param object $query - a WP_Query object
 * @return string the orderby we want
 */
function genesis_a2z_posts_orderby( $orderby, $query  ) {
	global $wpdb;
	if ( !is_admin()  ) {
		if ( $query->is_archive() ) {
		     if ( $query->is_category() || $query->is_tag() ) {
			     // continue to display blog posts by post_date DESC
		     } else {
			     $orderby = "$wpdb->posts.post_title asc";
		     }
		}
	}
	return $orderby;
}

function genesis_a2z_return_error( $spam, $akismet ) {
	return new WP_Error( 'feedback-discarded', __( 'Test form ignored', 'genesis-a2z' ) );
}

function genesis_a2z_version3() {
	// Start the engine. Yes. It is necessary.
	//include_once( get_template_directory() . '/lib/init.php' );
	require_once get_template_directory() . '/lib/init.php';
	// Sets up the Theme.
	// Can't see a need for theme-defaults at present
	require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

	// Adds helper functions.
	require_once get_stylesheet_directory() . '/lib/helper-functions.php';

	// Adds image upload and color select to Customizer.
	require_once get_stylesheet_directory() . '/lib/customize.php';

	// Includes Customizer CSS.
	require_once get_stylesheet_directory() . '/lib/output.php';

	// Adds WooCommerce support.
	//require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

	// Adds the required WooCommerce styles and Customizer CSS.
	//require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

	// Adds the Genesis Connect WooCommerce notice.
	//require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';
	add_action( 'after_setup_theme', 'genesis_a2z_localization_setup' );

	add_action( 'after_setup_theme', 'genesis_a2z_gutenberg_support' );
	add_action( 'after_setup_theme', 'genesis_a2z_theme_support', 9 );
	//add_action( 'after_setup_theme', 'genesis_rngs_oik_clone_support' );


	// Registers the responsive menus.
	if ( function_exists( 'genesis_register_responsive_menus' ) ) {
		genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
	}

	add_action( 'wp_enqueue_scripts', 'genesis_a2z_enqueue_scripts_styles' );

}

/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_a2z_localization_setup() {

	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );

}
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * Renamed from genesis_child_gutenberg_support
 *
 * @since 2.7.0
 */
function genesis_a2z_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}


/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_a2z_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}
}

/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_a2z_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style(
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		array(),
		genesis_get_theme_version()
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			array( genesis_get_theme_handle() ),
			genesis_get_theme_version()
		);
	}

}

/**
 * Gutenberg scripts and styles
 * @link https://www.billerickson.net/block-styles-in-gutenberg/
 */
function genesis_a2z_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'a2z-editor',
		get_stylesheet_directory_uri() . '/assets/js/editor.js',
		array( 'wp-blocks', 'wp-dom' ),
		filemtime( get_stylesheet_directory() . '/assets/js/editor.js' ),
		true
	);

}








