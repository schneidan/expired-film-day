<?php
/**
 * @package expired-film-day
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class("poste"); ?>> <a href="<?php the_permalink(); ?>">
<?php if ( has_post_thumbnail() ) {
    $medium_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium'); ?>
    <div class="cat-thumbnail" style="background-image:url('<?php echo $medium_image_url[0]; ?>');">
        <div class="cat-imgholder"></div>
    </div>
<?php } else { ?>
    <div class="cat-thumbnail" style="background-image:url('<?php echo esc_url( get_template_directory_uri() ); ?>/img/defaultthumb.png');">
        <div class="cat-imgholder"></div>
    </div>
<?php } ?>
</a>
<p class="postmeta">
<?php if ( is_singular( 'post' ) || is_home() ): ?>
<?php $categories_list = explode(',', get_the_category_list( __( ', ', 'gridster-lite' ) ) );
if ( $categories_list[0] ) : ?>
<?php echo trim( $categories_list[0] ); ?>
<?php endif; // End if categories ?>
<?php endif; // End if 'post' == get_post_type() ?>
</p>
<h2 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark">
<?php the_title(); ?>
</a></h2>
</div>
<!-- post -->
