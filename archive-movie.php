<?php
/**
* movie archive page
*
 */
//* Force full width content layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
//* Remove the breadcrumb navigation
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
/**
 * Movie Archive Template
 *
 * Note that the hooks and genesis_get_image() function used in this file are specific to the Genesis Framework
 * 
 * @author Ren Ventura <EngageWP.com>
 * @link http://www.engagewp.com/nested-loops-custom-wordpress-queries
 */
 
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'movie_loop' );
function movie_loop() {
	$term_args = array(
	    'orderby'           => 'slug',
	    'order'             => 'DESC'
	);
	//* Retrieve every year with a movie post
	$terms = get_terms( 'movie_years', $term_args );
	$years = array();
	//* Loop through each term and assemble an array of term slugs
	foreach ( $terms as $term ) {
		$years[] = $term->name;   /* changed from $term->slug to $term->name to show the name of the tag instead of the slug*/
	}
	/**
	 * Loop through the years array and instantiate a new WP_Query with each iteration
	 */
	foreach ( $years as $year ) {
		$year_args = array(
			'post_type' => 'movie',
			'orderby' => 'date',			/* CHANGED orderby name to date */
			'order' => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'movie_years',
					'field'    => 'slug',
					'terms'    => $year,
				),
			),
		);
		$loop = null;
		$loop = new WP_Query( $year_args );
		if ( $loop->have_posts() ) {
			$count = 0;
			//* New <section> for each year
			printf( '<section class="%1$s" id="%2$s">', 'movies clearfix', 'movies-' . $year );
			//* Year header (i.e. 2015, 2014, etc.)
			echo '<h2 style="CSS moved to Stylesheet">' . $year . '</h2>';
			/* echo tag_description(); not working. I can not get the tags description */
			while ( $loop->have_posts() ) {
				$loop->the_post();
				//* Get the featured image
				$img = genesis_get_image( array(
	                   'format'  => 'html',
	                   'size'    => 'full',
	                   'context' => 'archive',
	                   'attr'    => genesis_parse_attr( 'entry-image' ),
				) );
				//* Uses a five column grid (standard Genesis/Bootstrap columns)
				if ( 0 == $count || 0 == $count % 5 ) {
					$classes = 'one-fith first';
				} else $classes = 'one-fith';
				printf( '<article class="%s">', implode( ' ', get_post_class( $classes ) ) );
				printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), $img );
				/* echo 'Title: ' . get_the_excerpt() . '<br/>Year: ' . $year . '<br/>'; */
				echo '<h3 style="CSS moved to Stylesheet"> ' . get_the_excerpt() . ' </h3>';
				echo '</article>';
				$count++;
			}
			echo '</section>';
			wp_reset_query();
		} else echo 'No movies';
	}
}
genesis();