<?php
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


// add_shortcode('field', 'shortcode_field');
// function shortcode_field($atts) {
//     return get_the_ID();
//     // global $post;
//     // return get_post_meta( $post->ID, 'phone_number', true );
//     //  extract(shortcode_atts(array(
//     //               'post_id' => NULL,
//     //            ), $atts));
//     // if(!isset($atts[0])) return;
//     // $field = esc_attr($atts[0]);
//     // global $post;
//     // $post_id = (NULL === $post_id) ? $post->ID : $post_id;
//     // return get_post_meta($post_id, $field, true);
// }

add_shortcode('faculty_card', 'shortcode_faculty_card');
function shortcode_faculty_card($atts) {
    // return get_the_ID();
    // global $post;
    // return get_post_meta( $post->ID, 'phone_number', true );
    extract(shortcode_atts(array(
            'post_id' => NULL,
        ), $atts)
    );
    // if(!isset($atts[0])) return;
    // $field = esc_attr($atts[0]);
    global $post;
    $post_id = (NULL === $post_id) ? $post->ID : $post_id;
    return '<div class="faculty-card">' . 
        '<div>' .
            '<h2>' .
                get_post_meta($post_id, 'name', true) . 
            '</h2>' .
            '<p>' .
                get_post_meta($post_id, 'title', true) . 
            '</p>' .
            '<div class="meta">' .
                '<i class="fa-solid fa-phone"></i>  ' . 
                get_post_meta($post_id, 'phone_number', true) . 
            '</div>' .
            '<div class="meta">' .
                '<i class="fa-solid fa-location-dot"></i> ' . 
                get_post_meta($post_id, 'address', true) . 
            '</div>' .
            '<div class="meta">' .
                '<i class="fa-regular fa-envelope"></i> ' . 
                get_post_meta($post_id, 'email', true) . 
            '</div>' .
        '</div>' .
        get_the_post_thumbnail( $post_id, 'small' ) .
        '<p class="span-2">' .
            get_post_meta($post_id, 'interests', true) . 
        '</p>' .
    '</div>';
}

add_shortcode('faculty_card_list', 'shortcode_faculty_card_list');
function shortcode_faculty_card_list() {
    $args = array(
        'post_type' => 'post'
    );

    $post_query = new WP_Query($args);
    $html_elements = array();
    if($post_query->have_posts() ) {
        while($post_query->have_posts() ) {
            $post_query->the_post();
            global $post;
            array_push($html_elements, shortcode_faculty_card([$post->ID]));
        }
        wp_reset_postdata();
    }

    return '<div class="faculty-list">' . 
        implode( '', $html_elements ) . 
    '</div>';
}



// may not need this:
function my_custom_scripts() {
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array( 'jquery' ),'',true );
}
add_action( 'wp_enqueue_scripts', 'my_custom_scripts' );