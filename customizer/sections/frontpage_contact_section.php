<?php
function gulch_frontpage_control_customize_register( $wp_customize ) {
$wp_customize->add_section( 'gulch_front_page_Contact', array(
	'title'    => __( 'Contact Setion', 'gulch' ),
	// 'panel'		=> 'gulch_front_page_panel',
	'priority' => 5,
) );

/* Address Title  */
$wp_customize->add_setting( 'gulch_frontpage_contact_address_title', array(
	'default'           => __( 'Work with us', 'gulch' ),
	'transport'         => 'postMessage',
	'sanitize_callback' => 'gulch_sanitize_text',
) );

$wp_customize->add_control( 'gulch_frontpage_contact_address_title', array(
	'label'           => __( 'Contact Title', 'gulch' ),
	'section'         => 'gulch_front_page_Contact',
	'priority'        => 1,
) );

/* Address text  */
$wp_customize->add_setting( 'gulch_frontpage_contact_address_text', array(
	'default'           => __( "We understand trust comes with communication, so let's talk!", "gulch" ),
	'transport'         => 'postMessage',
	'sanitize_callback' => 'gulch_sanitize_text',
) );

$wp_customize->add_control( 'gulch_frontpage_contact_address_text', array(
	'label'           => __( 'Contact Text', 'gulch' ),
	'section'         => 'gulch_front_page_Contact',
	'type'     => 'textarea',
	'priority'        => 2,
) );

/* Contact Info */
	$wp_customize->add_setting( 'gulch_frontpage_contact_info', array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'gulch_sanitize_repeater',
		'default'           => json_encode( array(
			array(
				'text'      => __( 'Address', 'gulch' ),
				'subtext'   => __( '243 S Allen St. State College, 16801 PA', 'gulch' ),
			),
			array(
				'text'      => __( 'Phone', 'gulch' ),
				'subtext'   => __( '1-888-844-1415', 'gulch' ),
			),
			array(
				'text'      => __( 'E-Mail', 'gulch' ),
				'subtext'   => __( 'karkimadan80@gmail.com', 'gulch' ),
			),
		) ),
	) );
	$wp_customize->add_control( new gulch_Repeater_Controler( $wp_customize, 'gulch_frontpage_contact_info', array(
		'label'                         => __( 'Add new Contact Info', 'gulch' ),
		'section'                       => 'gulch_front_page_Contact',
		'priority'                      => 3,
		'gulch_image_control'       => false,
		'gulch_link_control'        => false,
		'gulch_text_control'        => true,
		'gulch_subtext_control'     => true,
		'gulch_label_control'       => false,
		'gulch_icon_control'        => false,
		'gulch_description_control' => false,
		'gulch_box_label'           => __( 'Contact Info', 'gulch' ),
		'gulch_box_add_label'       => __( 'Add new Contact Info', 'gulch' ),
	) ) );

	$wp_customize->get_section( 'gulch_front_page_Contact' )->panel = 'gulch_front_page_panel';

}

add_action( 'customize_register', 'gulch_frontpage_control_customize_register' );
