<?php
/**
 * Template Name: Custom Faculty Template
 * Template Post Type: post, page, product
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Inspiro
 * @subpackage Inspiro_Lite
 * @since Inspiro 1.0.0
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main container-fluid" role="main">
dasdsadasa
	<?php
	// Start the Loop.
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/post/content', get_post_format() );
		
	endwhile; // End the loop.
    
	?>

    <?php the_meta(); ?>

</main><!-- #main -->

<?php
get_footer();
