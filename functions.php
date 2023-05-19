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
                get_the_title() .
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
            // '<a class="more-link" href="' . get_permalink($post) . '">More</a>' .
            // '<a class="more-link" href="/wp-json/wp/v2/people/' . $post->ID .
            '<button class="more-link" onclick="showLightbox(' . $post->ID . ')">More</button>' . 
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
    '</div>' . 
    '<section class="" id="lightbox" onclick="hideLightbox(event)">
        <button id="close" class="close" onclick="hideLightbox(event)">
            <i id="close-icon" class="fas fa-times"></i>
        </button>
        <div class="content"></div>
    </section>
    <style>
    #lightbox {
        box-sizing: border-box;
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: -100vw;
        width: 100vw;
        height: 100vh;
        overflow-y: scroll;
        z-index: 2000000;
        background-color: rgba(50, 50, 50, 0.9);
    }
    
    #lightbox .content {
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 65vw;
        min-height: 100vh;
        padding: 50px;
        background-color: rgba(255, 255, 255);
        box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);
        transition: all 0.2s ease-in;
        margin-left: -65vw;
    }
    
    #lightbox.show {
        left: 0;
    }
    
    #lightbox.show .content {
        margin-left: 0vw;
    }
    
    #lightbox .close {
        align-self: flex-end;
        background: transparent;
        border: none;
        font-size: 30px;
        cursor: pointer;
        position: absolute;
        right: 35vw;
        top: 0px;
    }
    #lightbox.show .close {
        transition: all 0.2s ease-in;
    }
    @media screen and (max-width: 1000px) {
        #lightbox .content {
            margin-left: 0vw;
            width: 100%;
            padding: 20px;
        }
    
        #lightbox.show .close {
            transition: none;
            top: 0px;
            right: 0;
        }
    }
    </style>
    
    <script>
    delete window.showLightbox;
    delete window.showLightboxFaculty;
    delete window.hideLightbox;
    
    
    window.showLightbox = async postID => {
        const lightboxEl = document.querySelector("#lightbox");
        const html = await fetch(`/wp-json/wp/v2/people/${postID}?_embed`)
            .then(response => response.json());
        lightboxEl.querySelector(".content").innerHTML = showInfo(html);
        lightboxEl.classList.add("show");
        document.body.style.overflowY = "hidden";
        lightboxEl.querySelector("#close").focus();
        lightboxEl.classList.remove("people-detail");
    };
    
    window.showLightboxPeople = async fileName => {
        await window.showLightbox(fileName);
        const lightboxEl = document.querySelector("#lightbox");
        lightboxEl.classList.add("people-detail");
    };
    
    window.hideLightbox = ev => {
        const classList = ev.target.classList;
        let doClose = false;
        classList.forEach(className => {
            if (["fa-times", "close", "close-icon", "show"].includes(className)) {
                doClose = true;
                return;
            }
        })
        if (!doClose) {return};
        const lightboxEl = document.querySelector("#lightbox");
        lightboxEl.classList.remove("show");
        document.body.style.overflowY = "scroll";
    };

    function showInfo(data) {
        console.log(data);
        let html = `
            <h2 class="person-header">${data.title.rendered}</h2>
            ${getFeaturedImage(data)}
            <h3>${data.acf.title}</h3>
            ${getContactInfo(data)}
        `;
        return html;
    }

    function getFeaturedImage(data) {
        if (data._embedded && data._embedded["wp:featuredmedia"] && data._embedded["wp:featuredmedia"].length > 0) {
            return `<img class="people-thumb" src="${data._embedded["wp:featuredmedia"][0].media_details.sizes.medium.source_url}" />`;
        }
        return "";
    }

    function getContactInfo(data) {
        let html = `<div class="contact-info">`;
        if (data.acf.phone_number) {
            html += `
                <div class="meta">
                    <i class="fa-solid fa-phone"></i>
                    ${data.acf.phone_number}
                </div>`;
        } 
        if (data.acf.email) {
            html += `
                <div class="meta">
                    <i class="fa-regular fa-envelope"></i>
                    ${data.acf.email}
                </div>`;
        } 
        if (data.acf.address) {
            html += `
                <div class="meta">
                <i class="fa-solid fa-location-dot"></i>
                    ${data.acf.address}
                </div>`;
        } 
        return html;
    }
    </script>';
}



// may not need this:
function my_custom_scripts() {
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array( 'jquery' ),'',true );
}
add_action( 'wp_enqueue_scripts', 'my_custom_scripts' );