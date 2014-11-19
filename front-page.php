<?php

//* Remove all I don't need.
remove_action('genesis_loop', 'genesis_do_loop');

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );

remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

//* Pull all those sily exceptions.
function i_after_e_loop() {

    $args = array(
    	'post_type' => 'i_after_e',
    	'posts_per_page' => -1,
    	'order' => 'ASC',
    	'orderby' => 'title'
    );
    genesis_custom_loop( $args );

}
add_action( 'genesis_loop', 'i_after_e_loop' );



//* It's ok, we all make mistakes. Let us all know you do.
function i_after_e_upvote() {
global $post;

	// How many have messed up before you?
   	$votes = i_after_e_get_word_switchups_count( $post->ID );
   	$votes = ($votes == '') ? 0 : $votes;

	echo '<div class="switchup-count switchups-' . $post->ID . '">' . $votes . '</div>';

	?>

	<div class="custom-tweet-button">
	  <a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo urlencode( 'I before E except after C—but not in "' . get_the_title( $post->ID ) . '"' ) ?>. <?php echo $votes ?> others get it switched up too, do you?&url=<?php echo urlencode( 'http://iaftere.com/#' . get_the_title( $post->ID ) ) ?>" target="_blank">Tweet</a>
	</div>

	<?php

	$nonce = wp_create_nonce( 'ive_switched_up_nonce' );
	echo '<a class="button" href="#" update-count="' . $post->ID . '"  data-nonce="' . $nonce . '">I\'ve Switched This Up</a>';

}
add_action( 'genesis_entry_header', 'i_after_e_upvote' );


genesis();