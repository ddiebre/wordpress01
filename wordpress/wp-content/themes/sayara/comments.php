<?php

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h4 class="comments-title">
			<?php
			$sayara_comment_count = get_comments_number();
			if ( '1' === $sayara_comment_count ) {
				printf(
					esc_html__( 'One comment on &ldquo;%1$s&rdquo;', 'sayara' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf(
					esc_html( _nx( '%1$s comments', '%1$s comments', $sayara_comment_count, 'comments title', 'sayara' ) ),
					number_format_i18n( $sayara_comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h4><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ul class="comment-list">
			<?php
			wp_list_comments( array(
				'style'      => 'ul',
				'short_ping' => true,
				'avatar_size' => 95,
				'callback' => 'sayara_comment_list',
			) );
			?>
		</ul><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php echo esc_html__( 'Comments are closed.', 'sayara' ); ?></p>
			<?php
		endif;

	endif; // Check for have_comments().

	comment_form( array(
	  'id_form'           => 'commentform',
	  'class_form'        => 'comment-form row',  
	  'submit_field' 	  => '<div class="col-lg-12">%1$s %2$s</div>',
	  'submit_button' 	  => '<button type="submit">%4$s</button>',
	  'label_submit'      => esc_html__( 'Post Comment','sayara' ),
	  'title_reply' 	  => esc_html__( 'Leave a Reply' , 'sayara' ),
	  'format'            => 'html5',
	  'comment_notes_before' => false,
	  'comment_field' =>  '
		  	<div class="col-xl-12">
		  		<textarea id="comment" name="comment" placeholder="'. esc_attr__( 'Type your comment....' ,'sayara' ) .'" aria-required="true">' . '</textarea>
		 	 </div>',

	  		'fields' => apply_filters( 'sayara_comment_form_default_fields', array(
				'author' =>
				'<div class="col-lg-6">
				    <input id="author" name="author" type="text" placeholder="'. esc_attr__( 'Type your name....' ,'sayara' ) .'" value="' . esc_attr( $commenter['comment_author'] ) . '" aria-required="true" />
				</div>',

				'email' =>
				'<div class="col-lg-6">
				    <input id="email" name="email" type="text" placeholder="'. esc_attr__( 'Type your email....' ,'sayara' ) .'" value="' . esc_attr(  $commenter['comment_author_email'] ) .
				'" aria-required="true" />
				</div>',
			)
		),
	));
	?>

</div><!-- #comments -->
