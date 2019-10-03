<?php
/**
 * Genesis A2Z appearance settings.
 *
 * @package Genesis A2Z
 * @author  bobbingwide
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

$genesis_a2z_default_colors = array(
	'link'   => '#0073e5',
	'accent' => '#0073e5',
);

$genesis_a2z_link_color = get_theme_mod(
	'genesis_a2z_link_color',
	$genesis_a2z_default_colors['link']
);

$genesis_a2z_accent_color = get_theme_mod(
	'genesis_a2z_accent_color',
	$genesis_a2z_default_colors['accent']
);

$genesis_a2z_link_color_contrast   = genesis_a2z_color_contrast( $genesis_a2z_link_color );
$genesis_a2z_link_color_brightness = genesis_a2z_color_brightness( $genesis_a2z_link_color, 35 );

$genesis_a2z_default_palette = array(
	array(
		'name' => __( 'Pale pink', 'genesis-a2z' ),
		'slug' => 'pale-pink',
		'color' => '#f78da7',
	),
	array(
		'name' => __( 'Vivid red', 'genesis-a2z' ),
		'slug' => 'vivid-red',
		'color' => '#cf2e2e',
	),
	array(
		'name' => __( 'Luminous vivid orange', 'genesis-a2z' ),
		'slug' => 'luminous-vivid-orange',
		'color' => '#ff6900',
	),
	array(
		'name' => __( 'Luminous vivid amber', 'genesis-a2z' ),
		'slug' => 'luminous-vivid-amber',
		'color' => '#fcb900',
	),
	array(
		'name' => __( 'Light green cyan', 'genesis-a2z' ),
		'slug' => 'light-green-cyan',
		'color' => '#7bdcb5',
	),
	array(
		'name' => __( 'Vivid green cyan', 'genesis-a2z' ),
		'slug' => 'vivid-green-cyan',
		'color' => '#00d084',
	),
	array(
		'name' => __( 'Pale cyan blue', 'genesis-a2z' ),
		'slug' => 'pale-cyan-blue',
		'color' => '#8ed1fc',
	),
	array(
		'name' => __( 'Vivid cyan blue', 'genesis-a2z' ),
		'slug' => 'vivid-cyan-blue',
		'color' => '#0693e3',
	),
	array(
		'name' => __( 'Vivid purple', 'genesis-a2z' ),
		'slug' => 'vivid-purple',
		'color' => '#9b51e0',
	),
	array(
		'name' => __( 'Very light gray', 'genesis-a2z' ),
		'slug' => 'very-light-gray',
		'color' => '#eeeeee',
	),
	array(
		'name' => __( 'Cyan bluish gray', 'genesis-a2z' ),
		'slug' => 'cyan-bluish-gray',
		'color' => '#abb8c3',
	),
	array(
		'name' => __( 'Very dark gray', 'genesis-a2z' ),
		'slug' => 'very-dark-gray',
		'color' => '#313131',
	),
	array(
		'name' => __( 'White', 'genesis-a2z' ),
	    'slug' => 'white',
	    'color' => '#ffffff', )
);

return array(
	'fonts-url'            => 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700&display=swap',
	'content-width'        => 1062,
	'button-bg'            => $genesis_a2z_link_color,
	'button-color'         => $genesis_a2z_link_color_contrast,
	'button-outline-hover' => $genesis_a2z_link_color_brightness,
	'link-color'           => $genesis_a2z_link_color,
	'default-colors'       => $genesis_a2z_default_colors,
	'editor-color-palette' => array_merge( $genesis_a2z_default_palette, 	array(
		array(
			'name'  => __( 'Link colour', 'genesis-a2z' ),
			'slug'  => 'theme-primary',
			'color' => $genesis_a2z_link_color,
		),
		array(
			'name'  => __( 'Accent colour', 'genesis-a2z' ),
			'slug'  => 'theme-secondary',
			'color' => $genesis_a2z_accent_color,
		),
	)),
	'editor-font-sizes'    => array(
		array(
			'name' => __( 'Small', 'genesis-a2z' ),
			'size' => 12,
			'slug' => 'small',
		),
		array(
			'name' => __( 'Normal', 'genesis-a2z' ),
			'size' => 18,
			'slug' => 'normal',
		),
		array(
			'name' => __( 'Large', 'genesis-a2z' ),
			'size' => 20,
			'slug' => 'large',
		),
		array(
			'name' => __( 'Larger', 'genesis-a2z' ),
			'size' => 24,
			'slug' => 'larger',
		),
	),
);
