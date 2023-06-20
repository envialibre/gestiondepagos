<?php

/**
 * Include Theme Customizer.
 *
 * @since v1.0
 */
$theme_customizer = __DIR__ . '/inc/customizer.php';
if ( is_readable( $theme_customizer ) ) {
	require_once $theme_customizer;
}

if ( ! function_exists( 'gpagos_setup_theme' ) ) {
	/**
	 * General Theme Settings.
	 *
	 * @since v1.0
	 *
	 * @return void
	 */
	function gpagos_setup_theme() {
		// Make theme available for translation: Translations can be filed in the /languages/ directory.
		load_theme_textdomain( 'gpagos', __DIR__ . '/languages' );

		/**
		 * Set the content width based on the theme's design and stylesheet.
		 *
		 * @since v1.0
		 */
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 800;
		}

		// Theme Support.
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
				'navigation-widgets',
			)
		);

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );
		// Add support for full and wide alignment.
		add_theme_support( 'align-wide' );
		// Add support for Editor Styles.
		add_theme_support( 'editor-styles' );
		// Enqueue Editor Styles.
		add_editor_style( 'style-editor.css' );

		// Default attachment display settings.
		update_option( 'image_default_align', 'none' );
		update_option( 'image_default_link_type', 'none' );
		update_option( 'image_default_size', 'large' );

		// Custom CSS styles of WorPress gallery.
		add_filter( 'use_default_gallery_style', '__return_false' );
	}
	add_action( 'after_setup_theme', 'gpagos_setup_theme' );

	// Disable Block Directory: https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/filters/editor-filters.md#block-directory
	remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
	remove_action( 'enqueue_block_editor_assets', 'gutenberg_enqueue_block_editor_assets_block_directory' );
}

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Fire the wp_body_open action.
	 *
	 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
	 *
	 * @since v2.2
	 *
	 * @return void
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if ( ! function_exists( 'gpagos_add_user_fields' ) ) {
	/**
	 * Add new User fields to Userprofile:
	 * get_user_meta( $user->ID, 'facebook_profile', true );
	 *
	 * @since v1.0
	 *
	 * @param array $fields User fields.
	 *
	 * @return array
	 */
	function gpagos_add_user_fields( $fields ) {
		// Add new fields.
		$fields['facebook_profile'] = 'Facebook URL';
		$fields['twitter_profile']  = 'Twitter URL';
		$fields['linkedin_profile'] = 'LinkedIn URL';
		$fields['xing_profile']     = 'Xing URL';
		$fields['github_profile']   = 'GitHub URL';

		return $fields;
	}
	add_filter( 'user_contactmethods', 'gpagos_add_user_fields' );
}

/**
 * Test if a page is a blog page.
 * if ( is_blog() ) { ... }
 *
 * @since v1.0
 *
 * @return bool
 */
function is_blog() {
	global $post;
	$posttype = get_post_type( $post );

	return ( ( is_archive() || is_author() || is_category() || is_home() || is_single() || ( is_tag() && ( 'post' === $posttype ) ) ) ? true : false );
}

/**
 * Disable comments for Media (Image-Post, Jetpack-Carousel, etc.)
 *
 * @since v1.0
 *
 * @param bool $open    Comments open/closed.
 * @param int  $post_id Post ID.
 *
 * @return bool
 */
function gpagos_filter_media_comment_status( $open, $post_id = null ) {
	$media_post = get_post( $post_id );

	if ( 'attachment' === $media_post->post_type ) {
		return false;
	}

	return $open;
}
add_filter( 'comments_open', 'gpagos_filter_media_comment_status', 10, 2 );

/**
 * Style Edit buttons as badges: https://getbootstrap.com/docs/5.0/components/badge
 *
 * @since v1.0
 *
 * @param string $link Post Edit Link.
 *
 * @return string
 */
function gpagos_custom_edit_post_link( $link ) {
	return str_replace( 'class="post-edit-link"', 'class="post-edit-link badge bg-secondary"', $link );
}
add_filter( 'edit_post_link', 'gpagos_custom_edit_post_link' );

/**
 * Style Edit buttons as badges: https://getbootstrap.com/docs/5.0/components/badge
 *
 * @since v1.0
 *
 * @param string $link Comment Edit Link.
 */
function gpagos_custom_edit_comment_link( $link ) {
	return str_replace( 'class="comment-edit-link"', 'class="comment-edit-link badge bg-secondary"', $link );
}
add_filter( 'edit_comment_link', 'gpagos_custom_edit_comment_link' );

/**
 * Responsive oEmbed filter: https://getbootstrap.com/docs/5.0/helpers/ratio
 *
 * @since v1.0
 *
 * @param string $html Inner HTML.
 *
 * @return string
 */
function gpagos_oembed_filter( $html ) {
	return '<div class="ratio ratio-16x9">' . $html . '</div>';
}
add_filter( 'embed_oembed_html', 'gpagos_oembed_filter', 10 );

if ( ! function_exists( 'gpagos_content_nav' ) ) {
	/**
	 * Display a navigation to next/previous pages when applicable.
	 *
	 * @since v1.0
	 *
	 * @param string $nav_id Navigation ID.
	 */
	function gpagos_content_nav( $nav_id ) {
		global $wp_query;

		if ( $wp_query->max_num_pages > 1 ) {
	?>
			<div id="<?php echo esc_attr( $nav_id ); ?>" class="d-flex mb-4 justify-content-between">
				<div><?php next_posts_link( '<span aria-hidden="true">&larr;</span> ' . esc_html__( 'Older posts', 'gpagos' ) ); ?></div>
				<div><?php previous_posts_link( esc_html__( 'Newer posts', 'gpagos' ) . ' <span aria-hidden="true">&rarr;</span>' ); ?></div>
			</div><!-- /.d-flex -->
	<?php
		} else {
			echo '<div class="clearfix"></div>';
		}
	}

	/**
	 * Add Class.
	 *
	 * @since v1.0
	 *
	 * @return string
	 */
	function posts_link_attributes() {
		return 'class="btn btn-secondary btn-lg"';
	}
	add_filter( 'next_posts_link_attributes', 'posts_link_attributes' );
	add_filter( 'previous_posts_link_attributes', 'posts_link_attributes' );
}

/**
 * Init Widget areas in Sidebar.
 *
 * @since v1.0
 *
 * @return void
 */
function gpagos_widgets_init() {
	// Area 1.
	register_sidebar(
		array(
			'name'          => 'Primary Widget Area (Sidebar)',
			'id'            => 'primary_widget_area',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	// Area 2.
	register_sidebar(
		array(
			'name'          => 'Secondary Widget Area (Header Navigation)',
			'id'            => 'secondary_widget_area',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	// Area 3.
	register_sidebar(
		array(
			'name'          => 'Third Widget Area (Footer)',
			'id'            => 'third_widget_area',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'gpagos_widgets_init' );

if ( ! function_exists( 'gpagos_article_posted_on' ) ) {
	/**
	 * "Theme posted on" pattern.
	 *
	 * @since v1.0
	 */
	function gpagos_article_posted_on() {
		printf(
			wp_kses_post( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author-meta vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'gpagos' ) ),
			esc_url( get_the_permalink() ),
			esc_attr( get_the_date() . ' - ' . get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() . ' - ' . get_the_time() ),
			esc_url( get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'gpagos' ), get_the_author() ),
			get_the_author()
		);
	}
}

/**
 * Template for Password protected post form.
 *
 * @since v1.0
 *
 * @return string
 */
function gpagos_password_form() {
	global $post;
	$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );

	$output = '<div class="row">';
		$output .= '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">';
		$output .= '<h4 class="col-md-12 alert alert-warning">' . esc_html__( 'This content is password protected. To view it please enter your password below.', 'gpagos' ) . '</h4>';
			$output .= '<div class="col-md-6">';
				$output .= '<div class="input-group">';
					$output .= '<input type="password" name="post_password" id="' . esc_attr( $label ) . '" placeholder="' . esc_attr__( 'Password', 'gpagos' ) . '" class="form-control" />';
					$output .= '<div class="input-group-append"><input type="submit" name="submit" class="btn btn-primary" value="' . esc_attr__( 'Submit', 'gpagos' ) . '" /></div>';
				$output .= '</div><!-- /.input-group -->';
			$output .= '</div><!-- /.col -->';
		$output .= '</form>';
	$output .= '</div><!-- /.row -->';

	return $output;
}
add_filter( 'the_password_form', 'gpagos_password_form' );


if ( ! function_exists( 'gpagos_comment' ) ) {
	/**
	 * Style Reply link.
	 *
	 * @since v1.0
	 *
	 * @param string $class Link class.
	 *
	 * @return string
	 */
	function gpagos_replace_reply_link_class( $class ) {
		return str_replace( "class='comment-reply-link", "class='comment-reply-link btn btn-outline-secondary", $class );
	}
	add_filter( 'comment_reply_link', 'gpagos_replace_reply_link_class' );

	/**
	 * Template for comments and pingbacks:
	 * add function to comments.php ... wp_list_comments( array( 'callback' => 'gpagos_comment' ) );
	 *
	 * @since v1.0
	 *
	 * @param object $comment Comment object.
	 * @param array  $args    Comment args.
	 * @param int    $depth   Comment depth.
	 */
	function gpagos_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback':
			case 'trackback':
	?>
		<li class="post pingback">
			<p>
				<?php
					esc_html_e( 'Pingback:', 'gpagos' );
					comment_author_link();
					edit_comment_link( esc_html__( 'Edit', 'gpagos' ), '<span class="edit-link">', '</span>' );
				?>
			</p>
	<?php
				break;
			default:
	?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php
							$avatar_size = ( '0' !== $comment->comment_parent ? 68 : 136 );
							echo get_avatar( $comment, $avatar_size );

							/* Translators: 1: Comment author, 2: Date and time */
							printf(
								wp_kses_post( __( '%1$s, %2$s', 'gpagos' ) ),
								sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
								sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									get_comment_time( 'c' ),
									/* Translators: 1: Date, 2: Time */
									sprintf( esc_html__( '%1$s ago', 'gpagos' ), human_time_diff( (int) get_comment_time( 'U' ), current_time( 'timestamp' ) ) )
								)
							);

							edit_comment_link( esc_html__( 'Edit', 'gpagos' ), '<span class="edit-link">', '</span>' );
						?>
					</div><!-- .comment-author .vcard -->

					<?php if ( '0' === $comment->comment_approved ) { ?>
						<em class="comment-awaiting-moderation">
							<?php esc_html_e( 'Your comment is awaiting moderation.', 'gpagos' ); ?>
						</em>
						<br />
					<?php } ?>
				</footer>

				<div class="comment-content"><?php comment_text(); ?></div>

				<div class="reply">
					<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'reply_text' => esc_html__( 'Reply', 'gpagos' ) . ' <span>&darr;</span>',
									'depth'      => $depth,
									'max_depth'  => $args['max_depth'],
								)
							)
						);
					?>
				</div><!-- /.reply -->
			</article><!-- /#comment-## -->
		<?php
				break;
		endswitch;
	}

	/**
	 * Custom Comment form.
	 *
	 * @since v1.0
	 * @since v1.1: Added 'submit_button' and 'submit_field'
	 * @since v2.0.2: Added '$consent' and 'cookies'
	 *
	 * @param array $args    Form args.
	 * @param int   $post_id Post ID.
	 *
	 * @return array
	 */
	function gpagos_custom_commentform( $args = array(), $post_id = null ) {
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		$commenter     = wp_get_current_commenter();
		$user          = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';

		$args = wp_parse_args( $args );

		$req      = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true' required" : '' );
		$consent  = ( empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"' );
		$fields   = array(
			'author'  => '<div class="form-floating mb-3">
							<input type="text" id="author" name="author" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . esc_html__( 'Name', 'gpagos' ) . ( $req ? '*' : '' ) . '"' . $aria_req . ' />
							<label for="author">' . esc_html__( 'Name', 'gpagos' ) . ( $req ? '*' : '' ) . '</label>
						</div>',
			'email'   => '<div class="form-floating mb-3">
							<input type="email" id="email" name="email" class="form-control" value="' . esc_attr( $commenter['comment_author_email'] ) . '" placeholder="' . esc_html__( 'Email', 'gpagos' ) . ( $req ? '*' : '' ) . '"' . $aria_req . ' />
							<label for="email">' . esc_html__( 'Email', 'gpagos' ) . ( $req ? '*' : '' ) . '</label>
						</div>',
			'url'     => '',
			'cookies' => '<p class="form-check mb-3 comment-form-cookies-consent">
							<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" class="form-check-input" type="checkbox" value="yes"' . $consent . ' />
							<label class="form-check-label" for="wp-comment-cookies-consent">' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'gpagos' ) . '</label>
						</p>',
		);

		$defaults = array(
			'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
			'comment_field'        => '<div class="form-floating mb-3">
											<textarea id="comment" name="comment" class="form-control" aria-required="true" required placeholder="' . esc_attr__( 'Comment', 'gpagos' ) . ( $req ? '*' : '' ) . '"></textarea>
											<label for="comment">' . esc_html__( 'Comment', 'gpagos' ) . '</label>
										</div>',
			/** This filter is documented in wp-includes/link-template.php */
			'must_log_in'          => '<p class="must-log-in">' . sprintf( wp_kses_post( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'gpagos' ) ), wp_login_url( esc_url( get_the_permalink( get_the_ID() ) ) ) ) . '</p>',
			/** This filter is documented in wp-includes/link-template.php */
			'logged_in_as'         => '<p class="logged-in-as">' . sprintf( wp_kses_post( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'gpagos' ) ), get_edit_user_link(), $user->display_name, wp_logout_url( apply_filters( 'the_permalink', esc_url( get_the_permalink( get_the_ID() ) ) ) ) ) . '</p>',
			'comment_notes_before' => '<p class="small comment-notes">' . esc_html__( 'Your Email address will not be published.', 'gpagos' ) . '</p>',
			'comment_notes_after'  => '',
			'id_form'              => 'commentform',
			'id_submit'            => 'submit',
			'class_submit'         => 'btn btn-primary',
			'name_submit'          => 'submit',
			'title_reply'          => '',
			'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'gpagos' ),
			'cancel_reply_link'    => esc_html__( 'Cancel reply', 'gpagos' ),
			'label_submit'         => esc_html__( 'Post Comment', 'gpagos' ),
			'submit_button'        => '<input type="submit" id="%2$s" name="%1$s" class="%3$s" value="%4$s" />',
			'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
			'format'               => 'html5',
		);

		return $defaults;
	}
	add_filter( 'comment_form_defaults', 'gpagos_custom_commentform' );
}

if ( function_exists( 'register_nav_menus' ) ) {
	/**
	 * Nav menus.
	 *
	 * @since v1.0
	 *
	 * @return void
	 */
	register_nav_menus(
		array(
			'main-menu'   => 'Main Navigation Menu',
			'footer-menu' => 'Footer Menu',
		)
	);
}

// Custom Nav Walker: wp_bootstrap_navwalker().
$custom_walker = __DIR__ . '/inc/wp-bootstrap-navwalker.php';
if ( is_readable( $custom_walker ) ) {
	require_once $custom_walker;
}

$custom_walker_footer = __DIR__ . '/inc/wp-bootstrap-navwalker-footer.php';
if ( is_readable( $custom_walker_footer ) ) {
	require_once $custom_walker_footer;
}

/**
 * Loading All CSS Stylesheets and Javascript Files.
 *
 * @since v1.0
 *
 * @return void
 */
function gpagos_scripts_loader() {
	$theme_version = wp_get_theme()->get( 'Version' );

	// 1. Styles.
	wp_enqueue_style( 'style', get_theme_file_uri( 'style.css' ), array(), $theme_version, 'all' );
	wp_enqueue_style( 'main', get_theme_file_uri( 'assets/dist/main.css' ), array(), $theme_version, 'all' ); // main.scss: Compiled Framework source + custom styles.
	wp_enqueue_style( 'icons', get_theme_file_uri( 'assets/css/bootstrap-icons.css' ), array(), $theme_version, 'all' ); // icons styles.
	if ( is_rtl() ) {
		wp_enqueue_style( 'rtl', get_theme_file_uri( 'assets/dist/rtl.css' ), array(), $theme_version, 'all' );
	}

	// 2. Scripts.
	wp_enqueue_script( 'mainjs', get_theme_file_uri( 'assets/dist/main.bundle.js' ), array(), $theme_version, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gpagos_scripts_loader' );


/******************Login*******************/
function enforce_login() {
    if (!is_user_logged_in()) {
        auth_redirect();
    }
}
add_action('template_redirect', 'enforce_login');


function custom_login_redirect( $redirect_to, $request, $user ) {
    // Check the user role after login
    if ( is_wp_error( $user ) || empty( $user ) ) {
        return $redirect_to;
    }

    $user_roles = $user->roles;

    // Redirect administrators to the "panel-de-control" page
    if ( in_array( 'administrator', $user_roles ) ) {
        return home_url( '/panel-de-control' );
    }

    // Redirect subscribers to the "cliente" page
    if ( in_array( 'subscriber', $user_roles ) ) {
        return home_url( '/cliente' );
    }

    // For other roles or if no role is matched, redirect to the default dashboard
    return $redirect_to;
}
add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );

function my_login_logo_url_title() {
    return 'Bienvenido';
}
add_filter( 'login_headertext', 'my_login_logo_url_title' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo() { ?>
    <style type="text/css">
		#login {
			width: 260px !important;
			min-height: 83vh !important;
		}		
        #login h1 a, .login h1 a {
        background: none;
		height:65px;
		width:320px;
		background-size: 320px 65px;
		background-repeat: no-repeat;
        padding-bottom: 30px;
        }
		.language-switcher,
		#backtoblog{
			display: none;
		}
		#login .wp-pwd [type=password],
		#login .wp-pwd [type=text] {
    		padding-right: 2.5rem;
		}	
		#login .wp-pwd{
			position: relative;
		}				
		#login form .input, 
		#login input[type=password], 
		#login input[type=text] {
			font-size: 24px;
			line-height: 1.33333333;
			width: 100%;
			border-width: 0.0625rem;
			padding: 0.1875rem 0.3125rem;
			margin: 0 6px 16px 0;
			min-height: 40px;
			max-height: none;
		}		

		#login .button.wp-hide-pw {
			background: 0 0;
			border: 1px solid transparent;
			box-shadow: none;
			font-size: 14px;
			line-height: 2;
			width: 2.5rem;
			height: 2.5rem;
			min-width: 40px;
			min-height: 40px;
			margin: 0;
			padding: 5px 9px;
			position: absolute;
			right: 0;
			top: 0;
		}

		#login .button.wp-hide-pw .dashicons {
			width: 1.25rem;
			height: 1.25rem;
			top: 0.25rem;
		}

		#login #wp-submit {
			padding: 0.6rem 1rem;
			width: 100%;
			margin: 23px 0;
		}

    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );


// Add header to the login page
function add_header_to_login_page() {
    get_header('2');
}
add_action('login_head', 'add_header_to_login_page');

// Add footer to the login page
function add_footer_to_login_page() {
    get_footer();
}
add_action('login_footer', 'add_footer_to_login_page');


function hide_admin_bar_except_webmaster($show) {
    $current_user = wp_get_current_user();
    $webmaster_username = 'webmaster';

    if ($current_user->user_login === $webmaster_username) {
        return true; // Show admin bar for the webmaster
    }

    return false; // Hide admin bar for all other users
}
add_filter('show_admin_bar', 'hide_admin_bar_except_webmaster');


class Custom_Menu_Walker extends Walker_Nav_Menu {

    // Add classes to the top-level <ul> element
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start'>\n";
    }

    // Add classes to the menu item
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= $indent . '<li class="' . esc_attr($item->classes[1]) .' '. esc_attr($item->classes[2]) .' '. esc_attr($item->classes[3]) .' '. esc_attr($item->classes[4]) . '">';

        // Add the menu item contents
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $item_output = $args->before;

        // Add the custom structure
        $item_output .= '<a' . $attributes . ' class="nav-link px-0 align-middle d-flex align-items-center">';
        $item_output .= '<i class="fs-4 ' . esc_attr($item->classes[0]) . '"></i>';
        $item_output .= '<span class="ms-1 d-none d-sm-inline">' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
        $item_output .= '</a>';

        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}


function enqueue_custom_scripts() {
	wp_enqueue_script('jquery');
  }
  add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
  


  function localize_ajax_url() {
	wp_localize_script('jquery', 'ajax_obj', array('ajax_url' => admin_url('admin-ajax.php')));
  }
  add_action('wp_enqueue_scripts', 'localize_ajax_url');
  


function delete_proyecto_ajax_handler() {
	if (!empty($_POST['proyecto_id'])) {
	  $proyecto_id = intval($_POST['proyecto_id']);
	  wp_delete_post($proyecto_id, true);
	  echo 'success';
	}
	wp_die();
  }
  add_action('wp_ajax_delete_proyecto', 'delete_proyecto_ajax_handler');
  add_action('wp_ajax_nopriv_delete_proyecto', 'delete_proyecto_ajax_handler');
  
/***********Show bradcrumb*********** */

function get_breadcrumb() {
    echo '<a href="'.home_url().'" rel="nofollow">Home</a>';

    // Check if it's a custom post type
    if (is_singular('proyectos')) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        echo '<a href="'.home_url().'/proyectos">'.ucwords(str_replace('_', ' ', get_post_type())).'</a>';
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        the_title();
    }
    // Continue with the existing conditions
    elseif (is_category() || is_single()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        the_category(' &bull; ');
            if (is_single()) {
                echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
                the_title();
            }
    } elseif (is_page()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        echo the_title();
    } elseif (is_search()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Search Results for... ";
        echo '"<em>';
        echo the_search_query();
        echo '</em>"';
    }
}


/*******Update custom proyectos************ */

function update_post_ajax_handler() {
	$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0; // Get the post ID from the AJAX request and ensure it's an integer
	if ($post_id) {
	  // Update the post title
	  $new_title = sanitize_text_field($_POST['post_title']);
	  wp_update_post(array(
		'ID' => $post_id,
		'post_title' => $new_title
	  ));
  
	  // Update the custom fields
	  update_post_meta($post_id, 'direccion_del_proyecto', sanitize_text_field($_POST['direccion_del_proyecto']));
	  update_post_meta($post_id, 'metros', sanitize_text_field($_POST['metros']));
	  update_post_meta($post_id, 'cantidad_de_lotes', sanitize_text_field($_POST['cantidad_de_lotes']));
	  update_post_meta($post_id, 'areas_comunes', sanitize_text_field($_POST['areas_comunes']));
	  update_post_meta($post_id, 'fecha_de_entrega', sanitize_text_field($_POST['fecha_de_entrega']));
	  update_post_meta($post_id, 'servicios_que_se_entregan', sanitize_text_field($_POST['servicios_que_se_entregan']));
	  update_post_meta($post_id, 'anotaciones', sanitize_textarea_field($_POST['anotaciones']));
  
	  // Prepare the response
	  $response_data = array(
		'success' => true,
		'data' => array(
		  'message' => 'Proyecto actualizado con éxito',
		  'title' => $new_title,
		  'direccion_del_proyecto' => $_POST['direccion_del_proyecto'],
		  'metros' => $_POST['metros'],
		  'cantidad_de_lotes' => $_POST['cantidad_de_lotes'],
		  'areas_comunes' => $_POST['areas_comunes'],
		  'fecha_de_entrega' => $_POST['fecha_de_entrega'],
		  'servicios_que_se_entregan' => $_POST['servicios_que_se_entregan'],
		  'anotaciones' => $_POST['anotaciones']
		)
	  );
  
	  wp_send_json_success($response_data);
	} else {
	  // Handle the error if the post ID is missing or invalid
	  $response_data = array(
		'success' => false,
		'data' => array(
		  'message' => 'Invalid post ID'
		)
	  );
	  wp_send_json_error($response_data);
	}
  }
  add_action('wp_ajax_update_post', 'update_post_ajax_handler');
  add_action('wp_ajax_nopriv_update_post', 'update_post_ajax_handler');
  

  function create_post_callback() {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	  // Retrieve the form data
	  $titulo = sanitize_text_field($_POST['post_title']);
	  $direccion_del_proyecto = sanitize_text_field($_POST['direccion_del_proyecto']);
	  $metros = sanitize_text_field($_POST['metros']);
	  $cantidad_de_lotes = sanitize_text_field($_POST['cantidad_de_lotes']);
	  $areas_comunes = sanitize_text_field($_POST['areas_comunes']);
	  $fecha_de_entrega = sanitize_text_field($_POST['fecha_de_entrega']);
	  $servicios_que_se_entregan = sanitize_text_field($_POST['servicios_que_se_entregan']);
	  $anotaciones = sanitize_textarea_field($_POST['anotaciones']);
  
	  // Perform form validation
	  $errors = array();
  
	  if (empty($titulo)) {
		$errors[] = 'Title is required.';
	  }
  
	  // Add more validation rules for other fields if needed
  
	  if (empty($errors)) {
		// Create a new post
		$post_data = array(
		  'post_title' => $titulo,
		  'post_content' => '',
		  'post_status' => 'publish',
		  'post_type' => 'proyecto',
		);
  
		$post_id = wp_insert_post($post_data);
  
		// Set the custom field values for the new post
		update_post_meta($post_id, 'direccion_del_proyecto', $direccion_del_proyecto);
		update_post_meta($post_id, 'metros', $metros);
		update_post_meta($post_id, 'cantidad_de_lotes', $cantidad_de_lotes);
		update_post_meta($post_id, 'areas_comunes', $areas_comunes);
		update_post_meta($post_id, 'fecha_de_entrega', $fecha_de_entrega);
		update_post_meta($post_id, 'servicios_que_se_entregan', $servicios_que_se_entregan);
		update_post_meta($post_id, 'anotaciones', $anotaciones);
  
		// Prepare the response data
		$response_data = array(
		  'success' => true,
		  'message' => 'Post created successfully!',
		  'data' => array(
			'title' => $titulo,
			'direccion_del_proyecto' => $direccion_del_proyecto,
			'metros' => $metros,
			'cantidad_de_lotes' => $cantidad_de_lotes,
			'areas_comunes' => $areas_comunes,
			'fecha_de_entrega' => $fecha_de_entrega,
			'servicios_que_se_entregan' => $servicios_que_se_entregan,
			'anotaciones' => $anotaciones,
		  )
		);
  
		wp_send_json_success($response_data);
	  } else {
		// Prepare the response data for error
		$response_data = array(
		  'success' => false,
		  'message' => 'Validation error.',
		  'data' => array(
			'errors' => $errors
		  )
		);
  
		wp_send_json_error($response_data);
	  }
	}
  }
  add_action('wp_ajax_create_post', 'create_post_callback');
  add_action('wp_ajax_nopriv_create_post', 'create_post_callback');
  