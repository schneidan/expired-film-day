<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Gridster
 */
?>

<div id="footer">
<?php do_action( 'gridster_credits' ); ?>
<?php echo get_theme_mod( 'themefurnacefooter_footer_text' ); ?><br />
<?php _e('&copy; Copyright','gridster-lite') ?> 
<?php the_time('Y') ?> 
<?php bloginfo('name'); ?> &amp; <a href="http://schneidan.com">Daniel J. Schneider</a></div>
</div>
<!-- main -->
<?php wp_footer(); ?>
</body></html>