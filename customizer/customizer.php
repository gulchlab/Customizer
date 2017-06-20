<?php
/**
 * gulch Theme Customizer
 *
 * @package gulch
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function gulch_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.site-title a',
		'render_callback' => 'gulch_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.site-description',
		'render_callback' => 'gulch_customize_partial_blogdescription',
	) );

	/**
	 * Front Page Section
	 */
	$wp_customize->add_panel( 'gulch_front_page_panel', array(
		'priority' => 125,
		'title'    => esc_html__( 'Frontpage sections', 'gulch' ),
	) );

	// $wp_customize->add_panel( 'gulch_footer_page_panel', array(
	// 	'priority' => 126,
	// 	'title'    => esc_html__( 'Footer sections', 'gulch' ),
	// ) );

	
	/**
	 * Theme options.
	 */
	$wp_customize->add_section( 'theme_options', array(
		'title'    => __( 'Theme Options', 'gulch' ),
		'priority' => 130, // Before Additional CSS.
	) );

	/**
	 * Filter number of front page sections in Twenty Seventeen.
	 *
	 * @since Twenty Seventeen 1.0
	 *
	 * @param $num_sections integer
	 */
	$num_sections = apply_filters( 'gulch_front_page_sections', 4 );

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {

		$wp_customize->add_setting( 'category_dropdown_setting' . $i, array(
			'default'           => false,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( new Category_Dropdown_Custom_Control( $wp_customize, 'category_dropdown_setting' . $i, array(
		// $wp_customize->add_control( 'panel_' . $i, array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Content', 'gulch' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Select pages to feature in each area from the dropdowns. Add an image to a section by setting a featured image in the page editor. Empty sections will not be displayed.', 'gulch' ) ),
			'section'        => 'theme_options',
			'settings'   => 'category_dropdown_setting' . $i,
			// 'allow_addition' => true,
			'active_callback' => 'gulch_is_static_front_page',
		) ));

		$wp_customize->selective_refresh->add_partial( 'category_dropdown_setting' . $i, array(
			'selector'            => '#panel' . $i,
			'render_callback'     => 'gulch_front_page_section',
			'container_inclusive' => true,
		) );
	}

}
add_action( 'customize_register', 'gulch_customize_register' );

/**
 * Repeater Sanitization function
 *
 * @param string $input Input.
 * @return mixed|string|void
 */
function gulch_sanitize_repeater( $input ) {

	$input_decoded = json_decode( $input, true );
	$allowed_html  = array(
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'a'      => array(
			'href'   => array(),
			'class'  => array(),
			'id'     => array(),
			'target' => array(),
		),
		'button' => array(
			'class' => array(),
			'id'    => array(),
		),
	);

	if ( ! empty( $input_decoded ) ) {
		foreach ( $input_decoded as $boxk => $box ) {
			foreach ( $box as $key => $value ) {
				if ( $key == 'text' ) {
					$value                          = html_entity_decode( $value );
					$input_decoded[ $boxk ][ $key ] = wp_kses( $value, $allowed_html );
				} else {
					$input_decoded[ $boxk ][ $key ] = wp_kses_post( force_balance_tags( $value ) );
				}
			}
		}

		return json_encode( $input_decoded );
	}

	return $input;
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Twenty Seventeen 1.0
 * @see gulch_customize_register()
 *
 * @return void
 */
function gulch_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Twenty Seventeen 1.0
 * @see gulch_customize_register()
 *
 * @return void
 */
function gulch_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Return whether we're previewing the front page and it's a static page.
 */
function gulch_is_static_front_page() {
	return ( is_front_page() && ! is_home() );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function gulch_customize_preview_js() {
	wp_enqueue_script( 'gulch_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'gulch_customize_preview_js' );
