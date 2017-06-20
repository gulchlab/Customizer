<?php
function gulch_footer_control_customize_registers( $wp_customize ) {
$wp_customize->add_section( 'gulch_footer_section', array(
	'title'    => __( 'Footer Setion', 'gulch' ),
	// 'panel'		=> 'gulch_front_page_panel',
	'priority' => 126,
) );

/* Address Title  */
$wp_customize->add_setting( 'gulch_footer_logo_control', array(
	'default'           => get_template_directory_uri() . '/assets/images/gulchlab-logo.png',
	'transport'         => 'postMessage',
	// 'sanitize_callback' => 'gulch_sanitize_text',
) );

$wp_customize->add_control(
   new WP_Customize_Image_Control(
       $wp_customize,
       'gulch_footer_logo_control',
       array(
           'label'      => __( 'Upload Image/Logo', 'theme_name' ),
           'section'    => 'gulch_footer_section',
           'settings'   => 'gulch_footer_logo_control',
           'priority'        => 1,
       )
   )
);

/* Social */
$wp_customize->add_setting( 'gulch_footer_social', array(
	'transport'         => 'postMessage',
	'sanitize_callback' => 'gulch_sanitize_repeater',
	'default'           => json_encode( array(
		array(
			'icon_value'      => 'social_twitter',
			'link'   => '#',
		),
		array(
			'icon_value'      => 'social_facebook',
			'link'   => '#',
		),
		array(
			'icon_value'      => 'social_twitter',
			'link'   => '#',
		),
	) ),
) );
$wp_customize->add_control( new gulch_Repeater_Controler( $wp_customize, 'gulch_footer_social', array(
	'label'                         => __( 'Add new Social', 'gulch' ),
	'section'                       => 'gulch_footer_section',
	'priority'                      => 3,
	'gulch_image_control'       => false,
	'gulch_link_control'        => true,
	'gulch_text_control'        => false,
	'gulch_subtext_control'     => false,
	'gulch_label_control'       => false,
	'gulch_icon_control'        => true,
	'gulch_description_control' => false,
	'gulch_box_label'           => __( 'Social Link', 'gulch' ),
	'gulch_box_add_label'       => __( 'Add new Social', 'gulch' ),
) ) );

}

add_action( 'customize_register', 'gulch_footer_control_customize_registers' );
