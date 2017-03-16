<?php
/**
 * A child theme of Gridster-Lite
 *
 * @package expired-film-day
 */

// this code loads the parent's stylesheet (leave it in place unless you know what you're doing)

function theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

/*  Add your own functions below this line.
    ======================================== */

function my_home_category( $query ) { if ( $query->is_home() && $query->is_main_query() ) { $query->set( 'cat', '4'); } } add_action( 'pre_get_posts', 'my_home_category' ); 

function wpdocs_dequeue_script() {
   wp_dequeue_script( 'gridster-navigation' );
}
add_action( 'wp_print_scripts', 'wpdocs_dequeue_script', 100 );

// AnotherGoogle Font
function theme_enqueue_more_styles() {
    wp_enqueue_style( 'josefin-slab', 'http://fonts.googleapis.com/css?family=Josefin+Slab|Josephin+Sans', 'style' );
    wp_enqueue_style( 'genericons', get_stylesheet_directory_uri() . '/genericons.css', 'style' );
}
add_action('wp_enqueue_scripts', 'theme_enqueue_more_styles');

// Disable all WP-Emoticons crap
function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}
add_filter( 'emoji_svg_url', '__return_false' );
function disable_wp_emojicons() {
  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );

/**
 * Widget Custom Classes
 */
function sd_widget_form_extend( $instance, $widget ) {
    if ( !isset($instance['classes']) )
    $instance['classes'] = null;
    $row = "<p>\n";
    $row .= "\t<label for='widget-{$widget->id_base}-{$widget->number}-classes'>Class:\n";
    $row .= "\t<input type='text' name='widget-{$widget->id_base}[{$widget->number}][classes]' id='widget-{$widget->id_base}-{$widget->number}-classes' class='widefat' value='{$instance['classes']}'/>\n";
    $row .= "</label>\n";
    $row .= "</p>\n";
    echo $row;
    return $instance;
}
add_filter('widget_form_callback', 'sd_widget_form_extend', 10, 2);

function sd_widget_update( $instance, $new_instance ) {
    $instance['classes'] = $new_instance['classes'];
        return $instance;
    }
add_filter( 'widget_update_callback', 'sd_widget_update', 10, 2 );

function sd_dynamic_sidebar_params( $params ) {
    global $wp_registered_widgets;
    $widget_id    = $params[0]['widget_id'];
    $widget_obj    = $wp_registered_widgets[$widget_id];
    $widget_opt    = get_option($widget_obj['callback'][0]->option_name);
    $widget_num    = $widget_obj['params'][0]['number'];
    if ( isset($widget_opt[$widget_num]['classes']) && !empty($widget_opt[$widget_num]['classes']) )
        $params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$widget_opt[$widget_num]['classes']} ", $params[0]['before_widget'], 1 );
    return $params;
}
add_filter( 'dynamic_sidebar_params', 'sd_dynamic_sidebar_params' );

/**
 *
 * DESTROY ALL COMMENTS ARRRRR!
 * 
 */

// Disable support for comments and trackbacks in post types
function rvrb_disable_comments_post_types_support() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if(post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'rvrb_disable_comments_post_types_support');

// Close comments on the front-end
function rvrb_disable_comments_status() {
    return false;
}
add_filter('comments_open', 'rvrb_disable_comments_status', 20, 2);
add_filter('pings_open', 'rvrb_disable_comments_status', 20, 2);

// Hide existing comments
function rvrb_disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
}
add_filter('comments_array', 'rvrb_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function rvrb_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'rvrb_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function rvrb_disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url()); exit;
    }
}
add_action('admin_init', 'rvrb_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function rvrb_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'rvrb_disable_comments_dashboard');

// Remove comments links from admin bar
function rvrb_disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('init', 'rvrb_disable_comments_admin_bar');


// Disable those annoying pingbacks from our own posts
function disable_self_trackback( &$links ) {
  foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, get_option( 'home' ) ) )
            unset($links[$l]);
}
add_action( 'pre_ping', 'disable_self_trackback' );

// Suppress 'Uncategorized' in category widget
function my_categories_filter( $cat_args ){
    $cat_args['title_li'] = '';
    $cat_args['exclude_tree'] = 1;
    $cat_args['exclude'] = 1;
    $cat_args['use_desc_for_title'] = 0;
    return $cat_args;
}
add_filter( 'widget_categories_args', 'my_categories_filter', 10, 2 );

// Hide Jetpack's Feedback menu item
function jp_rm_menu() {
    if( class_exists( 'Jetpack' ) ) {
        remove_menu_page( 'edit.php?post_type=feedback' );
    }
}
add_action( 'admin_init', 'jp_rm_menu' );

/**
 * Remove jquery migrate and move jquery to footer
 */
add_filter( 'wp_default_scripts', 'remove_jquery_migrate' );

function remove_jquery_migrate( &$scripts)
{
    if(!is_admin())
    {
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
    }
}

/**
 * deregister stupid wP emoji BS
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * deregister unused Jetpack CSS
 */
function jeherve_remove_all_jp_css() {
  wp_deregister_style( 'AtD_style' ); // After the Deadline
  wp_deregister_style( 'jetpack_likes' ); // Likes
  wp_deregister_style( 'jetpack_related-posts' ); //Related Posts
  wp_deregister_style( 'jetpack-carousel' ); // Carousel
  wp_deregister_style( 'the-neverending-homepage' ); // Infinite Scroll
  wp_deregister_style( 'infinity-twentyten' ); // Infinite Scroll - Twentyten Theme
  wp_deregister_style( 'infinity-twentyeleven' ); // Infinite Scroll - Twentyeleven Theme
  wp_deregister_style( 'infinity-twentytwelve' ); // Infinite Scroll - Twentytwelve Theme
  wp_deregister_style( 'noticons' ); // Notes
  wp_deregister_style( 'post-by-email' ); // Post by Email
  wp_deregister_style( 'publicize' ); // Publicize
  wp_deregister_style( 'sharedaddy' ); // Sharedaddy
  wp_deregister_style( 'sharing' ); // Sharedaddy Sharing
  wp_deregister_style( 'stats_reports_css' ); // Stats
  wp_deregister_style( 'jetpack-widgets' ); // Widgets
  wp_deregister_style( 'jetpack-slideshow' ); // Slideshows
  wp_deregister_style( 'presentations' ); // Presentation shortcode
  wp_deregister_style( 'tiled-gallery' ); // Tiled Galleries
  wp_deregister_style( 'widget-conditions' ); // Widget Visibility
  wp_deregister_style( 'jetpack_display_posts_widget' ); // Display Posts Widget
  wp_deregister_style( 'gravatar-profile-widget' ); // Gravatar Widget
  wp_deregister_style( 'widget-grid-and-list' ); // Top Posts widget
  wp_deregister_style( 'jetpack-widgets' ); // Widgets
}
if ( ! is_admin() ) {
    add_filter( 'jetpack_implode_frontend_css', '__return_false' );
    add_action('wp_print_styles', 'jeherve_remove_all_jp_css' );
}
class follow_me_widget extends WP_Widget
{
    public function __construct()
    {
            parent::__construct(
                'follow_me_widget',
                __('Follow Me Widget', 'follow_me_widget'),
                array('description' => __('People must follow me on social media for success!', 'follow_me_widget'), )
            );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo '
            <div class="social-widget sidebarwidget">
                <ul>
                    <li><a title="View expiredfilmday’s profile on Facebook" href="https://www.facebook.com/expiredfilmday/" class="genericon genericon-facebook" target="_blank"><span class="screen-reader-text">View expiredfilmday’s profile on Facebook</span></a></li>
                    <li><a title="View expiredfilmday’s profile on Twitter" href="https://twitter.com/expiredfilmday/" class="genericon genericon-twitter" target="_blank"><span class="screen-reader-text">View expiredfilmday’s profile on Twitter</span></a></li>
                    <li><a title="View expiredfilmday’s profile on Instagram" href="https://instagram.com/expiredfilmday/" class="genericon genericon-instagram" target="_blank"><span class="screen-reader-text">View expiredfilmday’s profile on Instagram</span></a></li>
                    <li><a title="View expiredfilmday’s group on Flickr" href="https://www.flickr.com/groups/expiredfilmday/" class="genericon genericon-flickr" target="_blank"><span class="screen-reader-text">View expiredfilmday’s gorup on Flickr</span></a></li>
                </ul>
            </div>';
            echo $args['after_widget'];
    }
}
function registerfollow_me_widget() { register_widget('follow_me_widget'); }
add_action( 'widgets_init', 'registerfollow_me_widget' );