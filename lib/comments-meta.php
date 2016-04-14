<?php
// Adjusting meta and comments section of the theme


// Meta adjustments

// Adjust post meta in entry header AND change comments text in comments button
// http://wpspeak.com/remove-post-date-in-genesis-framework/ 
//

/* Adjust post meta in entry header AND change comments text in comments button
http://wpspeak.com/remove-post-date-in-genesis-framework/ */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
    $post_info = '[post_date][post_comments zero="Legg til en kommentar" one="1 Kommentar" more="% Kommentarer"] [post_edit]';
	return $post_info;
}

/* Read more used in category pages */
// Modify the Genesis content limit read more link - Genesis Settings page-> Display post content certain amount of characters. I selected 200.
add_filter( 'get_the_content_more_link', 'sp_read_more_link' );
function sp_read_more_link() {
	return '... <a class="more-link" href="' . get_permalink() . '">[Les videre...]</a>';
}





/*
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
    $post_info = '[post_date] [post_comments zero="Add a Comment" one="1 Comment" more="% Comments"] [post_edit]';
	return $post_info;
}}

// Customize the post meta "FILED UNDER: -category name-" categories text and below the post preview in a blog page and change it at the bottom of a post.
// http://wordpress.stackexchange.com/questions/50961/removing-post-meta-from-category-pages */
//


/*add_filter( 'genesis_post_meta', 'sp_post_meta_filter' );
function sp_post_meta_filter($post_meta) {
if ( !is_page() ) {
if (is_archive() ) $post_meta = '';
	else $post_meta = '[post_categories before="Kategorier: "] [post_tags before="Nøkkelord: "]';
return $post_meta;
}


// Comments adjustments

// Modify the speak your mind title in comments - Comment Box title -
//
add_filter( 'comment_form_defaults', 'sp_comment_form_defaults' );
function sp_comment_form_defaults( $defaults ) {
	$defaults['title_reply'] = __( 'Legg igjen en kommentar' );
	return $defaults;
}

// Changing the mini Comment title to something else http://wpsites.net/web-design/customize-comment-field-text-area-label/ 
//
function wpsites_modify_comment_form_text_area($arg) {
    $arg['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . _x( 'Takk for din tilbakemelding!', 'noun' ) . '</label><textarea id="comment" name="comment" cols="55" rows="7" aria-required="true"></textarea></p>';
    return $arg;
}
add_filter('comment_form_defaults', 'wpsites_modify_comment_form_text_area');



// Customize the submit button text in comments https://gist.github.com/studiopress/5708140
//
add_filter( 'comment_form_defaults', 'sp_comment_submit_button' );
function sp_comment_submit_button( $defaults ) {
        $defaults['label_submit'] = __( 'Send', 'custom' );
        return $defaults;
}