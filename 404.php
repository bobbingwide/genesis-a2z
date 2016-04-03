<?php	// (C) Copyright Bobbing Wide 2016

/**
 * Do something special on the 404 page
 *
 */
function genesis_a2z_404_entry_content( $text ) {
	$text = "That's odd. " . $text;
	return( $text );
}

/**
 * 
 
/**
 * This function outputs a 404 "Not Found" error message
 *
 * @since 1.6
 */
function genesis_a2z_404() {

	echo genesis_html5() ? '<article class="entry">' : '<div class="post hentry">';

		printf( '<h1 class="entry-title">%s</h1>', apply_filters( 'genesis_404_entry_title', __( 'Not found, error 404', 'genesis' ) ) );
		echo '<div class="entry-content">';

			if ( genesis_html5() ) :

				echo apply_filters( 'genesis_404_entry_content', '<p>' . sprintf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it by using the search form below.', 'genesis' ), trailingslashit( home_url() ) ) . '</p>' );

				get_search_form();

			else :
	?>

			<p><?php printf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it with the information below.', 'genesis' ), trailingslashit( home_url() ) ); ?></p>



	<?php
			endif;

			if ( ! genesis_html5() ) {
				genesis_sitemap( 'h4' );
			} elseif ( genesis_a11y( '404-page' ) ) {
				echo '<h2>' . __( 'Sitemap', 'genesis' ) . '</h2>';
				genesis_sitemap( 'h3' );
			}

			echo '</div>';

		echo genesis_html5() ? '</article>' : '</div>';

}


// Replace default loop with the 404 page
// Then do the default loop anyway?
//remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'genesis_a2z_404', 8 );
add_filter( "genesis_404_entry_content", "genesis_a2z_404_entry_content" );

//add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links', 'rems' ) );

//add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

genesis();
