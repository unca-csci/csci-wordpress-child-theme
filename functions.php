<?php

/******************************
 * Code for Custom Shortcodes *
 ******************************/
require_once( __DIR__ . '/inc/functions-people.php');
require_once( __DIR__ . '/inc/functions-cs-areas.php');
require_once( __DIR__ . '/inc/functions-class-schedule.php');
require_once( __DIR__ . '/inc/functions-courses.php');


/************************
 * Code for Child Theme *
 ************************/
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'inspiro-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION


/*********************************************
 * Code to Disable Comments / Unneeded Menus *
 *********************************************/


// Removes from admin menu
add_action( 'admin_menu', 'my_remove_admin_menus' );
function my_remove_admin_menus() {
    remove_menu_page( 'edit-comments.php' );
}
// Removes from post and pages
add_action('init', 'remove_comment_support', 100);

function remove_comment_support() {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );
}
// Removes from admin bar
function mytheme_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );

// End disable comments


/****************************
 * Code to Add a Body Class *
 ****************************/

// Add custom body class using Advanced Custom Fields:
add_filter( 'body_class', 'custom_body_class' );
/**
 * Add custom field body class(es) to the body classes.
 *
 * It accepts values from a per-page custom field, and only outputs when viewing a singular static Page.
 *
 * @param array $classes Existing body classes.
 * @return array Amended body classes.
 */
function custom_body_class( array $classes ) {
	$new_class = is_page() ? get_post_meta( get_the_ID(), 'body_class', true ) : null;

	if ( $new_class ) {
		$classes[] = $new_class;
	}

	return $classes;
}






// add_filter( 'acf/rest_api/recursive/types', function( $types ) {
//     print_r(get_post_types());
//     print_r($types);
// 	// if ( isset( $types['course-groupings'] ) ) {
// 	// 	unset( $types['course-groupings'] );
// 	// }
//     // if ( isset( $types['course'] ) ) {
// 	// 	unset( $types['course'] );
// 	// }
//     // if ( isset( $types['post'] ) ) {
// 	// 	unset( $types['post'] );
// 	// }

// 	return $types;
//     // return array();
// } );


// add_filter( 'acf/rest/get_fields', function( $data ) {
//     print_r($data);
//     // if ( $response instanceof WP_REST_Response ) {
//     //     $data = $response->get_data();
//     // }
//     foreach($data as $item){
//         //if( isset( $item['acf'] ) ) {
//             echo('!!!');
//             // $data['acf']['shipping_address']->acf = get_fields( $data['acf']['shipping_address']->ID );
//             $item['acf'] = 123;
//         ///} 
//     }
//     return $data;
// }, 10, 1 );









// add_filter( 'acf/rest_api/recursive/types', function( $types ) {
// 	if ( isset( $types['post'] ) ) {
// 		unset( $types['post'] );
// 	}

// 	return $types;
// } );