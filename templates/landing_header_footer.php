<?php
/**
 * This file adds the Landing Page template.
 *
 */

/*
Template Name: Landing Page - Header/Footer
*/

//* Add custom body class to the head
add_filter( 'body_class', 'baseline_add_body_class' );
function baseline_add_body_class( $classes ) {

   $classes[] = 'baseline-landing';
   return $classes;

}

//* Force full width content layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

//* Remove the header right widget area
unregister_sidebar( 'header-right' );

//* Remove navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_before_footer', 'genesis_do_subnav', 5 );

//* Remove page title
remove_action('genesis_entry_header', 'genesis_do_post_title');

//* Remove breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

//* Run the Genesis loop
genesis();
