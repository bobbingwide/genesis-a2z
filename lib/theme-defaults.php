<?php
/**
 * Genesis A2Z.
 *
 * This file adds the default theme settings to the Genesis A2Z Theme.
 *
 * @package Genesis A2Z
 * @author  bobbingwide
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

add_filter( 'simple_social_default_styles', 'genesis_a2z_social_default_styles' );
/**
 * Set Simple Social Icon defaults.
 *
 * @since 1.0.0
 *
 * @param array $defaults Social style defaults.
 * @return array Modified social style defaults.
 */
function genesis_a2z_social_default_styles( $defaults ) {

	$args = genesis_get_config( 'simple-social-icons-settings' );

	return wp_parse_args( $args, $defaults );

}
