<?php

/*--------- REMOVE WIDGETS ------*/

/* Removing default widgets
*  https://thomasgriffin.io/remove-default-widgets-wordpress/
*/

add_action( 'widgets_init', 'cwwp_unregister_default_widgets' );
function cwwp_unregister_default_widgets() {
	
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Calendar' );
	//unregister_widget( 'WP_Widget_Categories' );
	//unregister_widget( 'WP_Nav_Menu_Widget' );
	unregister_widget( 'WP_Widget_Meta' );
	//unregister_widget( 'WP_Widget_Pages' );
	//unregister_widget( 'WP_Widget_Recent_Comments' );
	//unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_RSS' );
	//unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	//unregister_widget( 'WP_Widget_Text' );
}  


/*--------- WIDGET SECTIONS -------*/
	

//Position the PreHeader Area
function genesischild_preheader_widget() {
	echo '<section class="preheadercontainer"><div class="wrap">';
	genesis_widget_area ( 'preheaderleft' );
	genesis_widget_area ( 'preheaderright' );
	echo '</div></section>';
}


/* ----- Pre Header Widgets ------ */
genesis_register_widget_area(
	array(
		'id'          => 'preheader-left',
		'name'        => __( 'Preheader Left', 'custom-genesis-sample' ),
		'description' => __( 'This is the pre header left widget area', 'custom-genesis-sample' ),
	)
);
genesis_register_widget_area(
	array(
		'id'          => 'preheader-right',
		'name'        => __( 'Preheader Right', 'custom-genesis-sample' ),
		'description' => __( 'This is the pre header right widget area', 'custom-genesis-sample' ),
	)
);

/* Adding a container to contain both preheader left and right */
add_action( 'genesis_before_header', 'my_pre_header' );
function my_pre_header() {

	echo '<div class="preheader-container"><div class="wrap">';
		genesis_widget_area( 'preheader-left', array(
			'before'	=> '<div class="preheader-left widget-area one-half first">',
			'after'		=> '</div>',
		) );
		genesis_widget_area( 'preheader-right', array(
			'before'	=> '<div class="preheader-right widget-area one-half">',
			'after'		=> '</div>',
		) );
	echo '</div></div>';

}


// Home page widgets
genesis_register_sidebar( array(
	'id'			=> 'home-featured-full',
	'name'			=> __( 'Home Featured Full', 'custom-genesis-sample' ),
	'description'	=> __( 'This is the featured section if you want full width.', 'custom-genesis-sample' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-featured-left',
	'name'			=> __( 'Home Featured Left', 'custom-genesis-sample' ),
	'description'	=> __( 'This is the featured section left side.', 'custom-genesis-sample' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-featured-right',
	'name'			=> __( 'Home Featured Right', 'custom-genesis-sample' ),
	'description'	=> __( 'This is the featured section right side.', 'custom-genesis-sample' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle-1',
	'name'			=> __( 'Home Middle 1', 'custom-genesis-sample' ),
	'description'	=> __( 'This is the home middle left section.', 'custom-genesis-sample' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle-2',
	'name'			=> __( 'Home Middle 2', 'custom-genesis-sample' ),
	'description'	=> __( 'This is the home middle center section.', 'custom-genesis-sample' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle-3',
	'name'			=> __( 'Home Middle 3', 'custom-genesis-sample' ),
	'description'	=> __( 'This is the home middle right section.', 'custom-genesis-sample' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-bottom',
	'name'			=> __( 'Home Bottom', 'custom-genesis-sample' ),
	'description'	=> __( 'This is the home bottom section.', 'custom-genesis-sample' ),
) );

/*---- Before Entry -------*/
genesis_register_sidebar( array(
'id'            => 'before-entry',
'name'          => __( 'Before Entry', 'custom-genesis-sample' ),
'description'   => __( 'This is the before content area', 'custom-genesis-sample' ),
'before_widget' => '<div class="before-entry">',
'after_widget'  => '</div>',
) );

//* Hooks before - entry widget area to single posts
add_action( 'genesis_before_entry', 'before_post'  ); 
function before_post() {
    if ( ! is_singular( 'post' ) )
    	return;
    genesis_widget_area( 'before-entry', array(
		'before' => '<div class="before-entry widget-area"><div class="wrap">',
		'after'  => '</div></div>',
    ) ); 

}

/* ----- After Entry ------*/
genesis_register_sidebar( array(
	'id'          => 'after-entry',
	'name'        => __( 'After Entry', 'custom-genesis-sample' ),
	'description' => __( 'This is the after entry section.', 'custom-genesis-sample' ),
) );

//* Hooks after - entry widget area to single posts
add_action( 'genesis_entry_footer', 'after_post'  ); 
function after_post() {
    if ( ! is_singular( 'post' ) )
    	return;
    genesis_widget_area( 'after-entry', array(
		'before' => '<div class="after-entry widget-area"><div class="wrap">',
		'after'  => '</div></div>',
    ) ); 

}



/*------- Footer Widget Header --------*/
genesis_register_sidebar( array(
  'id' => 'footer-header',
  'name' => __( 'Footer Header', 'custom-genesis-sample' ),
  'description' => __( 'This is the Footer Widget Headline.', 'custom-genesis-sample' ),
) );


//* Footer Widget Header - the number 5 has to do with priority order of widgets
add_action( 'genesis_before_footer', 'add_genesis_footer_header', 5 );
function add_genesis_footer_header() {
      genesis_widget_area( 'footer-header', array(
  'before' => '<div class="footer-header widget-area"><div class="wrap">',
  'after' => '</div></div>',
 ) );
}
