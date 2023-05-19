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

function getContactInfo($post_id) {
    $html = '<div class="contact-info">';
    if ( get_post_meta($post_id, 'phone_number', true) != '') {
        $html = $html . 
            '<div class="meta">' .
                '<i class="fa-solid fa-phone"></i>  ' . 
                get_post_meta($post_id, 'phone_number', true) . 
            '</div>';
    } 
    if ( get_post_meta($post_id, 'address', true) != '') {
        $html = $html . 
            '<div class="meta">' .
                '<i class="fa-solid fa-location-dot"></i> ' . 
                get_post_meta($post_id, 'address', true) . 
            '</div>';
    }
    if ( get_post_meta($post_id, 'email', true) != '') {
        $html = $html . 
            '<div class="meta">' .
                '<i class="fa-regular fa-envelope"></i> ' . 
                get_post_meta($post_id, 'email', true) . 
            '</div>';
    }
    $html = $html . '</div>';
    return $html;
}

add_shortcode('faculty_card', 'shortcode_faculty_card');
function shortcode_faculty_card($atts) {
    extract(shortcode_atts(array(
            'post_id' => NULL,
        ), $atts)
    );
    global $post;
    $post_id = (NULL === $post_id) ? $post->ID : $post_id;
    
    
    $html = '<div class="faculty-card">';

    // row 1a:
    $html = $html . 
        '<div>' .
            '<h2>' .
                get_post_meta($post_id, 'name', true) . 
            '</h2>' .
            '<p class="title">' .
                get_post_meta($post_id, 'title', true) . 
            '</p>';
    $html = $html . getContactInfo($post_id);
   
    $html = $html . '</div>';

    // row 1b:
    if ( has_post_thumbnail()) {
        $html = $html . get_the_post_thumbnail( $post_id, 'small' );
    } else {
        $html = $html . '<div></div>'; 
    }
    
    // row 2: 
    $html = $html . '<p class="span-2 interests">' .
        get_post_meta($post_id, 'interests', true) . 
    '</p>';
    
    // row 3: 
    $html = $html . 
        '<p class="span-2">' .
            '<a class="more-link" href="' . get_permalink($post) . '">More</a>' .
            // '<a class="more-link" href="/wp-json/wp/v2/people/' . $post->ID . '">More</a>' .
        '</p>';
    $html = $html . '</div>';
    return $html;
}

add_shortcode('faculty_card_list', 'shortcode_faculty_card_list');
function shortcode_faculty_card_list() {
    $args = array(
        'post_type' => 'person'
    );

    $post_query = new WP_Query($args);

    // return $post_query->found_posts;
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