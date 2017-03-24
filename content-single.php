<?php
/**
 * @package Gridster
 */
?>

<div id="main">
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full'); ?>
    <div class="catgrid-thumbnail">
        <img src="<?php echo $large_image_url[0]; ?>"/ >
    </div>
<div id="content">
<div id="postheading">
<h1>
<?php the_title(); ?>
</h1>
</div>
<ul id="meta">
<li class="datemeta"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></li>
<?php if ( is_singular( 'post' ) ): ?>
<li class="categorymeta">
<?php _e('Posted to: ','gridster-lite') ?>
<?php the_category(', ') ?>
</li>
<?php endif; ?>
<li class="tagmeta">
<?php the_tags('Tags:  ',', ',''); ?>
</li>
</ul>
<?php $usp_author = get_post_meta( $post->ID, 'usp-author', true ); if ( $usp_author !='' ): ?>
    <p><i>Submitted by:</i> <strong><?php echo $usp_author; ?></strong></p>
<?php endif;
$usp_url = get_post_meta( $post->ID, 'usp-url', true ); if ( $usp_url != '' ): 
$usp_urlh = ( ! ( substr( $usp_url, 0, 7 ) == 'http://' || substr( $usp_url, 0, 8 ) == 'https://' ) ) ? 'http://'.$usp_url : $usp_url; ?>
    <p><i>Link:</i> <strong><a href="<?php echo $usp_urlh; ?>"><?php echo $usp_url; ?></a></strong></p>
<?php endif;
$usp_twitter_raw = get_post_meta( $post->ID, 'usp-custom-twitter', true ); if ( $usp_twitter_raw != '' ): 
$usp_twitter = str_replace( 'https://twitter.com/', '', str_replace( 'http://twitter.com/', '', str_replace( '@', '', $usp_twitter_raw ) ) ) ?>
    <p><i>Twitter:</i> <strong><a href="http://twitter.com/<?php echo $usp_twitter; ?>">@<?php echo $usp_twitter; ?></a></strong></p>
<?php endif;
$usp_instagram_raw = get_post_meta( $post->ID, 'usp-custom-instagram', true ); if ( $usp_instagram_raw != '' ): 
$usp_instagram = str_replace( 'https://twitter.com/', '', str_replace( 'http://twitter.com/', '', str_replace( '@', '', $usp_instagram_raw ) ) ) ?>
    <p><i>Instagram:</i> <strong><a href="http://instagram.com/<?php echo $usp_instagram; ?>">@<?php echo $usp_instagram; ?></a></strong></p>
<?php endif;?>
<?php if ($usp_author !=''): ?>
	<p><i>About the photo and the film:</i></p>
<?php endif;?>
<?php the_content(); ?>
<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'gridster-lite' ),
				'after'  => '</div>',
			) );
?>
<?php edit_post_link( __( 'Edit', 'gridster-lite' ), '<span class="edit-link">', '</span>' ); ?>
<div id="comments">
<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
?>
</div>
<!-- #post-## -->
</div>
<!-- comments -->
<?php gridster_content_nav( 'nav-below' ); ?>
</div>
<!-- content -->
