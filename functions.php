<?php
//* vroom vroom
include_once( get_template_directory() . '/lib/init.php' );

define( 'CHILD_THEME_NAME', 'I After E' );
define( 'CHILD_THEME_URL', 'http://iaftere.com/' );
define( 'CHILD_THEME_VERSION', '0.1.0' );

//* Enqueue Lato Google font
function i_after_e_google_fonts() {
	wp_enqueue_style( 'google-font-lato', '//fonts.googleapis.com/css?family=Lato:300,700', array(), CHILD_THEME_VERSION );
}
add_action( 'wp_enqueue_scripts', 'i_after_e_google_fonts' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Magical code stuff
function i_after_e_enqueue_responsive_script() {

	wp_enqueue_style( 'iae-css', get_stylesheet_directory_uri() . "/assets/css/i_after_e{$postfix}.css", null, CHILD_THEME_VERSION );

	wp_enqueue_script( 'iae-js', get_stylesheet_directory_uri() .  "/assets/js/i_after_e{$postfix}.js", array( 'jquery' ), CHILD_THEME_VERSION );
	wp_localize_script( 'iae-js', 'SwitchUps', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}
add_action( 'wp_enqueue_scripts', 'i_after_e_enqueue_responsive_script' );

 function i_after_e_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_stylesheet_directory_uri() . '/humans.txt" />';

	echo apply_filters( 'i_after_e_humans', $humans );
 }
 add_action( 'wp_head', 'i_after_e_header_meta' );

//* Please like this, makes me feel better about myself.
function i_after_e_fb_root() { ?>
	<div id='fb-root'></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = '//connect.facebook.net/en_US/all.js#xfbml=1&appId=178169442206520';
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
<?php }
add_action( 'genesis_before', 'i_after_e_fb_root', 1 );


//* To show the world you've messed up, and yourself, immediately.
function i_after_e_ive_switched_up() {

   	if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'ive_switched_up_nonce' ) ) {
      	exit( "You're being as bad as the i before e rule, maybe even worse. ;)" );
   	}

   	$vote_count = get_post_meta( $_REQUEST['post_id'], 'i_after_e_switched_up_count', true );
   	$vote_count = ( $vote_count == '' ) ? 0 : $vote_count;
   	$new_vote_count = $vote_count + 1;

   	$vote = update_post_meta( $_REQUEST['post_id'], 'i_after_e_switched_up_count', $new_vote_count );

   	if ( $vote === false ) {
      	$result['type'] = "error";
      	$result['vote_count'] = $vote_count;
   	} else {
     	$result['type'] = "success";
      	$result['vote_count'] = $new_vote_count;
   	}

   	if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    	$result = json_encode($result);
		echo $result;
   	} else {
      	header('Location: '.$_SERVER['HTTP_REFERER']);
   	}

   die();

}
add_action( 'wp_ajax_ive_switched_up', 'i_after_e_ive_switched_up' );
add_action( 'wp_ajax_nopriv_ive_switched_up', 'i_after_e_ive_switched_up' );


//* Get all who messed up.
function i_after_e_get_word_switchups_count( $post_id ){

    $count = get_post_meta( $post_id, 'i_after_e_switched_up_count', true );

	// No count has started, so make it zero.
	// I could possibly do this a better way, this feels hacky
	// But I thought Nah, forget it. Yo, homes to Bel Air.
    if ( $count == '' ) {
        delete_post_meta( $post_id, 'i_after_e_switched_up_count' );
        add_post_meta( $post_id, 'i_after_e_switched_up_count', '0');
        return 0;
    }

    return $count;

}
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );


// * Set up the post type to hold all the exceptions.
function i_after_e_create_post_type(){
    $dpt = 'i_after_e';

    register_post_type(
        'i_after_e',
        array(
            'labels' => array(
                'name'               => __( 'Exceptions', $dpt ),
                'singular_name'      => __( 'Exception', $dpt ),
                'add_new'            => __( 'Add New', $dpt ),
                'add_new_item'       => __( 'Add New Exception', $dpt ),
                'edit_item'          => __( 'Edit Exception', $dpt ),
                'new_item'           => __( 'New Exception', $dpt ),
                'view_item'          => __( 'View Exception', $dpt ),
                'search_items'       => __( 'Search Exception', $dpt ),
                'not_found'          => __( 'No definitions found', $dpt ),
                'not_found_in_trash' => __( 'No definitions found in trash', $dpt ),
            ),
        'public' => true,
        'has_archive' => false,
        'menu_position' => 20,
        'hierarchical' => true,
        'supports' => array(
                'title',
                'editor',
                'author',
                'comments',
                'revisions',
                'page-attributes'
            ),
        'taxonomies'=> array(
                'exception_family'
            ),
        )
    );

    // There are rules within the 'I' Before 'E' that make all these words ok.
    register_taxonomy( 'exception_family', $dpt, array(
            'labels' => array(
                'name'                       => __( 'Families', $dpt ),
                'singular_name'              => __( 'Family', $dpt ),
                'search_items'               => __( 'Search Families', $dpt ),
                'popular_items'              => __( 'Popular Families', $dpt ),
                'all_items'                  => __( 'All Families', $dpt ),
                'parent_item'                => __( 'Parent Family', $dpt ),
                'parent_item_colon'          => __( 'Parent Family:', $dpt ),
                'edit_item'                  => __( 'Edit Family', $dpt ),
                'update_item'                => __( 'Update Family', $dpt ),
                'add_new_item'               => __( 'Add New Family', $dpt ),
                'new_item_name'              => __( 'New Family Name', $dpt ),
                'seperate_items_with_commas' => __( 'Separate Families with commas', $dpt ),
                'add_or_remove_items'        => __( 'Add or remove Families', $dpt ),
                'choose_from_most_used'      => __( 'Choose from the most used Families', $dpt ),
            ),
            'public' => true,
            'hierarchical' => true,
            'rewrite' => array(
                'slug' => __( 'family', $dpt )
            )
        )
    );
}
add_action( 'init','i_after_e_create_post_type');


//* #Hashtag (Anchor link)
function i_after_e_hash_link( $link, $post ) {

	if ( $post->post_type == 'i_after_e' )
		$link = get_site_url() . '/#' . sanitize_title( $post->post_title );

	return $link;

}
add_action( 'post_type_link', 'i_after_e_hash_link', 10, 2 );

//* The Anchor
function i_after_e_post_title_hash_anchor() {
global $post;

	$title = sprintf( '<a href="%s" title="%s" rel="bookmark">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), get_the_title() );
	echo '<h2 class="entry-title" itemprop="headline" id="' . sanitize_title( get_the_title() )  . '">' . $title . '</h2>';

}
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_action( 'genesis_entry_header', 'i_after_e_post_title_hash_anchor' );

//* I put my name on this cause I did this. Why I did this, I don't know. Genesis is cool though, so you should download that.
function sp_footer_creds_filter() {
	return '<p>[footer_copyright] &middot; <a href="http://iaftere.com">I After E</a> &middot; Built on the <a target="_blank" href="http://christophercochran.me/go/genesis" title="Genesis Framework">Genesis Framework</a><p/><p>Created by <a href="http://christophercochran.me">Christopher Cochran</a> for no apparent reason.</p><div class="twitter-share-button"><a href="https://twitter.com/share" class="twitter-share-button" data-dnt="true" data-count="none" data-via="tweetsfromchris">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div><div class="fb-like" data-href="http://iaftere.com/" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>';
}
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');