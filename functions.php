<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Custom Genesis Sample Theme' );
define( 'CHILD_THEME_AUTHOR', 'Paal Joachim Romdahl' );
define( 'CHILD_THEME_AUTHOR_URL', 'http://www.easywebdesigntutorials.com/' );
define( 'CHILD_THEME_URL', 'http://www.easywebdesigntutorials.com/' );
define( 'CHILD_THEME_VERSION', '1.0.0' );
define( 'TEXT_DOMAIN', 'startertheme' );

//* Enqueue Google Fonts
add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
function genesis_sample_google_fonts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links', 'rems' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );



/*****--------- CUSTOM CODE ----------*****/

/* Bigger embed size http://cantonbecker.com/work/musings/2011/how-to-change-automatic-wordpress-youtube-embed-size-width/ */
add_filter( 'embed_defaults', 'bigger_embed_size' );
function bigger_embed_size()
{ 
 return array( 'width' => 910, 'height' => 590 );
}

// Remove the site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

// Remove archive title and archive description in the blog page - https://wpbeaches.com/remove-archive-description-title-from-blog-page-in-genesis/
remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );

//* Remove page titles from all single posts & pages (requires HTML5 theme support)
add_action( 'get_header', 'child_remove_titles' );
function child_remove_titles() {
   if ( is_singular() ){
       remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
   }
}

//* Remove comments - https://github.com/ryanrudolph/prometheus/blob/master/functions.php
remove_action( 'genesis_after_post', 'genesis_get_comments_template' );

// Show kitchen sink code


//* Remove edit link - at bottom of frontend pages and posts.
add_filter( 'genesis_edit_post_link', '__return_false' );  


// Add except to pages - https://github.com/jaredatch/Genesis-Starter-Theme/blob/master/functions.php
add_post_type_support( 'page', 'excerpt' );

// Add support for editor stylesheet - using twenty Sixteens editor stylesheet.
add_editor_style( 'css/editor-style.css' );


// INCLUDE - external php pages!
// http://code.tutsplus.com/articles/how-to-include-and-require-files-and-templates-in-wordpress--wp-26419
// When I create a new post category it does not show up at once but I have to refresh for it to show up in the category listing. I do not know why.
include_once( CHILD_DIR . '/lib/widgets.php' );
include_once( CHILD_DIR . '/lib/comments-meta.php' );
include_once( CHILD_DIR . '/lib/movie-custom-post.php' );



// Add custom CSS stylesheets 
add_action( 'wp_enqueue_scripts', 'load_custom_style_sheet' );
function load_custom_style_sheet() {
	wp_enqueue_style( 'widgets-stylesheet', CHILD_URL . '/widgets.css', array(), PARENT_THEME_VERSION );
	wp_enqueue_style( 'custom-stylesheet', CHILD_URL . '/custom.css', array(), PARENT_THEME_VERSION );	
	wp_enqueue_style( 'custom-post-type-stylesheet', CHILD_URL . '/movie.css', array(), PARENT_THEME_VERSION );	
}


// Enqueue Javascript files
add_action( 'wp_enqueue_scripts', 'custom_enqueue_scripts' );
function custom_enqueue_scripts() {
	// Sidr slide out menu
	wp_enqueue_script( 'custom-global', get_stylesheet_directory_uri() . '/assets/js/global.js', array( 'sidr' ), '1.0.0', true );
	
	// Sidr slide out menu
	wp_enqueue_script( 'sidr',  get_stylesheet_directory_uri() . '/js/jquery.sidr.min.js', array( 'jquery' ), '1.2.1', true );
	
	wp_enqueue_style( 'dashicons' );
	
	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700', array(), CHILD_THEME_VERSION );
	
	wp_enqueue_script( 'executive-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
}


//* Sticky Footer Functions
add_action( 'genesis_before_header', 'stickyfoot_wrap_begin');
function stickyfoot_wrap_begin() {
	echo '<div class="page-wrap">';
}
 
add_action( 'genesis_before_footer', 'stickyfoot_wrap_end');
function stickyfoot_wrap_end() {
	echo '</div><!-- page-wrap -->';
}



/* -------- NAV ------ */

// Add a new Footer Menu - https://wpbeaches.com/add-footer-menu-genesis-child-theme/
function themprefix_footer_menu () {
 echo '<div class="footer-menu-container">';
 $args = array(
 'theme_location' => 'footer',
 'container' => 'nav',
 'container_class' => 'wrap',
 'menu_class' => 'menu genesis-nav-menu menu-footer',
 'depth' => 1, // For drop down menus change to 0
 );
 wp_nav_menu( $args );
 echo '</div>';
}

add_theme_support ( 'genesis-menus' , array ( 'primary' => 'Primary Navigation Menu' , 'secondary' => 'Secondary Navigation Menu' ,'footer' => 'Footer Navigation Menu' ) );

add_action('genesis_footer', 'themprefix_footer_menu', 5); /* NB! Changed before footer to in footer and changed priority to 5 so it comes before the copy right info text */




/* ------- Customize pagination - https://sridharkatakam.com/genesis-starter-child-theme/ ------ */

//* Customize the previous page link
add_filter ( 'genesis_prev_link_text' , 'sp_previous_page_link' );
function sp_previous_page_link ( $text ) {
	return g_ent( '&laquo; ' ) . __( 'Forrige Side', CHILD_DOMAIN );
}

// Customize the next page link
add_filter ( 'genesis_next_link_text' , 'sp_next_page_link' );
function sp_next_page_link ( $text ) {
	return __( 'Neste Side', CHILD_DOMAIN ) . g_ent( ' &raquo; ' );
}

// Add extra previous and next post in a post - https://wpbeaches.com/add-post-navigation-links-in-genesis/
add_action( 'genesis_entry_footer', 'genesis_prev_next_post_nav' );


/*--------- Footer copyright info--------*/

// Change the footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
	$creds = '[footer_copyright] &middot; <a href="http://easywebdesign.no">By Easy Web Design </a> &middot; [footer_loginout] &middot;';
	return $creds;
}



/*------ Remove unused Genesis profile options ------*/

// Remove Genesis widgets
//add_action( 'widgets_init', 'gregr_remove_genesis_widgets', 20 );

// User Permissions
remove_action( 'show_user_profile', 'genesis_user_options_fields' );
remove_action( 'edit_user_profile', 'genesis_user_options_fields' );

// Author Archive Settings
remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );

// Author Archive SEO Settings
remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );

// Layout Settings
remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );

// Remove Genesis layout options
//genesis_unregister_layout( 'sidebar-content' );
//genesis_unregister_layout( 'content-sidebar-sidebar' );
//genesis_unregister_layout( 'sidebar-sidebar-content' );
//genesis_unregister_layout( 'sidebar-content-sidebar' );
//genesis_unregister_layout( 'content-sidebar' );
//genesis_unregister_layout( 'full-width-content' );

// Remove Genesis menu link
//remove_theme_support( 'genesis-admin-menu' );




/* --------- Bottom of backend Admin screen -  Custom admin footer credits https://github.com/gregreindel/greg_html5_starter -----*/

add_filter( 'admin_footer_text', create_function( '$a', 'return \'<span id="footer-thankyou">Site managed by <a href="http://www.easywebdesigntutorials.com" target="_blank">Paal Joachim Romdahl </a><span> | Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a>\';' ) );


//add_action( 'admin_print_styles', 'genesis_child_load_admin_styles' );



/* ---------- REMOVE page archive and page blog -----*/

/**
 * Remove Genesis Page Templates
 * @author Bill Erickson
 * @link http://www.billerickson.net/remove-genesis-page-templates
 * @param array $page_templates
 * @return array
 */
function be_remove_genesis_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}
add_filter( 'theme_page_templates', 'be_remove_genesis_page_templates' );


// CHANGES the WordPress login image for another image....
//
// https://github.com/JiveDig/baseline/blob/master/functions.php
/**
 * Change login logo
 * Max image width should be 320px
 * @link http://andrew.hedges.name/experiments/aspect_ratio/
 */
add_action('login_head',  'tsm_custom_dashboard_logo');
function tsm_custom_dashboard_logo() {
	echo '<style  type="text/css">
		.login h1 a {
			background-image:url(' . get_stylesheet_directory_uri() . '/images/osf-logo.jpg)  !important;
			background-size: 300px auto !important;
			width: 100% !important;
			height: 120px !important;
		}
	</style>';
}
// Change login link
add_filter('login_headerurl','tsm_loginpage_custom_link');
function tsm_loginpage_custom_link() {
	return get_site_url();
}

