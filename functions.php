<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'nombre-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

/** Add logo to admin page */
function admin_login_logo() {
	echo '
    <style type="text/css">
        #login h1 a, .login h1 a {
		width: 100%;
		height: 60px;
  		background-image: url( ' . wp_upload_dir()['url'] . '/logo.svg );
		background-size: contain
        }
    </style>';
}
function admin_login_url($url) {
    return site_url();
}
add_action( 'login_enqueue_scripts', 'admin_login_logo' );
add_filter( 'login_headerurl', 'admin_login_url' );

/** Disable wordpress comments */
// Disable support for comments and trackbacks in post types
function disable_comments_post_types_support() {
    $post_types = get_post_types();
 
    foreach ($post_types as $post_type) {
        if(post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'disable_comments_post_types_support');
 
// Close comments on the front-end
function disable_comments_status() {
    return false;
}
add_filter('comments_open', 'disable_comments_status', 20, 2);
add_filter('pings_open', 'disable_comments_status', 20, 2);
 
// Hide existing comments
function disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
}
add_filter('comments_array', 'disable_comments_hide_existing_comments', 10, 2);
 
// Remove comments page in menu
function disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'disable_comments_admin_menu');
 
// Redirect any user trying to access comments page
function disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url()); exit;
    }
}
add_action('admin_init', 'disable_comments_admin_menu_redirect');
 
// Remove comments metabox from dashboard
function disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'disable_comments_dashboard');

// Lazy Load
add_filter( 'media_library_infinite_scrolling', '__return_true' );

// Setting Default WordPress UI color 
function set_default_admin_color($user_id) {
    $args = array(
        'ID' => $user_id,
        'admin_color' => 'modern'
    );
    wp_update_user( $args );
}
add_action('user_register', 'set_default_admin_color');

// Make default Rankmath share image apppear instead of first image on page when not set
add_filter('rank_math/opengraph/pre_set_content_image', function() {
	return true;
});

// Turn off admin email verification
add_filter( 'admin_email_check_interval', '__return_false' );

// Disable Back to WordPress Editor button
add_action('admin_head', 'my_custom_style');
// admin_head is a hook my_custom_fonts is a function we are adding it to the hook
function my_custom_style() {
  echo '<style>
    #elementor-switch-mode .elementor-switch-mode-on{
        display: none;    
    }
	body.elementor-editor-active #elementor-switch-mode-button{
	visibility: hidden;
	}
	body.elementor-editor-active #elementor-switch-mode-button:hover{
	visibility: hidden;
	}
      </style>';
}

