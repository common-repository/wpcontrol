<?php 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



//class WPControl_Frontend_Settings extends WPControl_Settings {

//	public function __construct() {

//content
		add_action( 'init' , 'wpcontrol_disable_comments_everywhere');
		add_action( 'init' , 'wpcontrol_disable_comments_posts');
		add_action( 'init' , 'wpcontrol_disable_comments_pages');
		add_action( 'init' , 'wpcontrol_disable_comments_media');

		add_action( 'admin_init' , 'wpcontrol_disable_gutenberg');
			//add_action( 'admin_enqueue_scripts' , 'wpcontrol_disable_gutenberg_wp_enqueue_scripts');
		add_action( 'admin_init' , 'wpcontrol_disable_gutenberg_nags');


//performance
		add_action( 'init' , 'wpcontrol_disable_emojis');
		add_action( 'init' , 'wpcontrol_disable_rss_feed');
		add_action( 'init' , 'wpcontrol_disable_shortlinks');
		add_action( 'init' , 'wpcontrol_disable_rsd_link');
		add_action( 'init' , 'wpcontrol_disable_xfn_profile_link');
		add_action( 'init' , 'wpcontrol_disable_wlwmanifest_link');
		add_action( 'wp_head' , 'wpcontrol_disable_previous_next_post_link');


//security
		add_action( 'init' , 'wpcontrol_disable_xmlrpc_pingback');
		add_action( 'init' , 'wpcontrol_disable_user_gravatar_frontend');
		add_action( 'init' , 'wpcontrol_disable_rest_api');
		add_action( 'init' , 'wpcontrol_hide_author_login');
		add_action( 'init' , 'wpcontrol_hide_login_errors');
		add_action( 'init' , 'wpcontrol_remove_html_comments');
		add_action( 'init' , 'wpcontrol_remove_meta_generator');

		add_action( 'init' , 'wpcontrol_disable_right_click_everywhere');
		add_action( 'init' , 'wpcontrol_disable_right_click_homepage');
		add_action( 'init' , 'wpcontrol_disable_right_click_posts');
		add_action( 'init' , 'wpcontrol_disable_right_click_pages');




//notifications
		add_action( 'admin_enqueue_scripts' , 'wpcontrol_disable_admin_notices');
		add_action( 'init' , 'wpcontrol_disable_new_user_emails');


//miscellaneous
		add_action( 'init' ,  'wpcontrol_remove_category_url');	
		add_action( 'init' ,  'wpcontrol_disable_search');		
		add_action( 'init' ,  'wpcontrol_disable_lazy_loading');
		add_action( 'init' ,  'wpcontrol_hide_admin_toolbar');

		add_action( 'admin_init' ,  'wpcontrol_disable_dashboard_widget_welcome');
		add_action( 'load-index.php' ,  'wpcontrol_disable_dashboard_widget_browse_happy');
		add_action( 'load-index.php' ,  'wpcontrol_disable_dashboard_widget_php_update');
		add_action( 'admin_init' ,  'wpcontrol_disable_dashboard_widget_activity');
		add_action( 'admin_init' ,  'wpcontrol_disable_dashboard_widget_at_a_glance');
		add_action( 'admin_init' ,  'wpcontrol_disable_dashboard_widget_quick_draft');
		add_action( 'admin_init' ,  'wpcontrol_disable_dashboard_widget_site_health');
		add_action( 'admin_init' ,  'wpcontrol_disable_dashboard_widget_events_and_news');



		//add_action( 'init' , 'wpcontrol_disable_emojis');
		//add_action( 'template_redirect' ,  'wpcontrol_test');




//	}


//==============================================================================================================================================================
//Content Page
//==============================================================================================================================================================




	function wpcontrol_disable_comments_everywhere() {
		if(wpcontrol_get_option('wpcontrol-disable-comments-everywhere')) {

			add_filter('comments_array', 'wpcontrol_hide_existing_comments', 10, 2); // Hide existing comments
			add_filter('comments_open', 'wpcontrol_status', 20, 2); // Close comments on the front-end
			add_filter('pings_open', 'wpcontrol_status', 20, 2);

			add_action('admin_init', 'wpcontrol_admin_menu_redirect'); // Redirect any user trying to access comments page
			add_action('admin_init', 'wpcontrol_dashboard'); // Remove comments metabox from dashboard
			add_action('admin_menu', 'wpcontrol_admin_menu'); // Remove comments page in menu

			add_action('admin_init', 'wpcontrol_post_types_support'); // Disable support for comments and trackbacks in post types
			add_action('pre_comment_on_post', 'wpcontrol_no_wp_comments'); // Disables comments API
			add_action('wp_before_admin_bar_render', 'wpcontrol_admin_bar_render');  // Remove comments links from admin bar
		}
	}
		function wpcontrol_post_types_support() {
		    $post_types = get_post_types();
		    foreach ($post_types as $post_type) {
		        if (post_type_supports($post_type, 'comments')) {
		            remove_post_type_support($post_type, 'comments');
		            remove_post_type_support($post_type, 'trackbacks');
		        }
		    }
		}

		function wpcontrol_status() {
		    return false;
		}

		function wpcontrol_hide_existing_comments($comments) {
		    $comments = array();
			return $comments;
		}

		function wpcontrol_admin_menu() {
		    remove_menu_page('edit-comments.php');
		}

		function wpcontrol_admin_menu_redirect() {
		    global $pagenow;
		    if ($pagenow === 'edit-comments.php') {
		        wp_redirect(admin_url());
		        exit();
		    }
		}

		function wpcontrol_dashboard() {
		    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
		}

		function wpcontrol_admin_bar_render() {
		    global $wp_admin_bar;
		    $wp_admin_bar->remove_menu('comments');
		}

		function wpcontrol_no_wp_comments() {
		    wp_die('No comments');
		}




	function wpcontrol_disable_comments_posts() {
		if (wpcontrol_get_option('wpcontrol-disable-comments-posts')) {
			$type = 'post';
				// we need to know what native support was for later.
				if (post_type_supports($type, 'comments')) {
					remove_post_type_support($type, 'comments');
					remove_post_type_support($type, 'trackbacks');
				}
					
				add_filter('comments_array', 'wpcontrol_filter_existing_comments', 20, 2);
				add_filter('comments_open', 'wpcontrol_filter_comment_status_posts', 20, 2);
				add_filter('pings_open', 'wpcontrol_filter_comment_status_posts', 20, 2);
				add_filter('get_comments_number', 'wpcontrol_filter_comments_number', 20, 2);	
		}
	}

		function wpcontrol_filter_comment_status_posts( $open, $post_id ) {
			 $post = get_post( $post_id );
	    	if ( 'post' == $post->post_type ) {
	        	$open = false;
	    	}
	    	return $open;
		}



	function wpcontrol_disable_comments_pages() {
		if (wpcontrol_get_option('wpcontrol-disable-comments-pages')) {

			$type = 'page';
				// we need to know what native support was for later.
				if (post_type_supports($type, 'comments')) {
					remove_post_type_support($type, 'comments');
					remove_post_type_support($type, 'trackbacks');
				}
					
				add_filter('comments_array', 'wpcontrol_filter_existing_comments', 20, 2);
				add_filter('comments_open', 'wpcontrol_filter_comment_status_pages', 20, 2);
				add_filter('pings_open', 'wpcontrol_filter_comment_status_pages', 20, 2);
				add_filter('get_comments_number', 'wpcontrol_filter_comments_number', 20, 2);
		}
	}
		function wpcontrol_filter_comment_status_pages( $open, $post_id ) {
			 $post = get_post( $post_id );
	    	if ( 'page' == $post->post_type ) {
	        	$open = false;
	    	}
	    	return $open;
		}



	function wpcontrol_disable_comments_media() {
		if (wpcontrol_get_option('wpcontrol-disable-comments-media')) {
			$type = 'attachment';
				// we need to know what native support was for later.
				if (post_type_supports($type, 'comments')) {
					remove_post_type_support($type, 'comments');
					remove_post_type_support($type, 'trackbacks');
				}
					
				add_filter('comments_array', 'wpcontrol_filter_existing_comments', 20, 2);
				add_filter('comments_open', 'wpcontrol_filter_comment_status_media', 20, 2);
				add_filter('pings_open', 'wpcontrol_filter_comment_status_media', 20, 2);
				add_filter('get_comments_number', 'wpcontrol_filter_comments_number', 20, 2);
					
		}
	}

		function wpcontrol_filter_comment_status_media( $open, $post_id ) {
			 $post = get_post( $post_id );
	    	if ( 'attachment' == $post->post_type ) {
	        	$open = false;
	    	}
	    	return $open;
		}


		function wpcontrol_filter_existing_comments( $comments, $post_id ) {
			return array();			
		}

	
		function wpcontrol_filter_comments_number( $count, $post_id ) {
			return 0;
		}









	function wpcontrol_disable_gutenberg() {
		if (wpcontrol_get_option('wpcontrol-disable-gutenberg')) {
			add_filter('use_block_editor_for_post_type', '__return_false', 100);
		}
	}




	function wpcontrol_disable_gutenberg_nags() {
		if (wpcontrol_get_option('wpcontrol-disable-gutenberg-nag')) {
			remove_filter('try_gutenberg_panel', 'wp_try_gutenberg_panel');	
		}
	}







//==============================================================================================================================================================
//Performance Page
//==============================================================================================================================================================

	function wpcontrol_disable_emojis() {
		if (wpcontrol_get_option('wpcontrol-disable-emojis')) {
			function wpcontrol_disable_emojis_function() {
				remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
				remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
				remove_action( 'wp_print_styles', 'print_emoji_styles' );
				remove_action( 'admin_print_styles', 'print_emoji_styles' );	
				remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
				remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
				remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
				add_filter( 'tiny_mce_plugins', 'wpcontrol_disable_emojis_tinymce' );
				add_filter( 'wp_resource_hints', 'wpcontrol_disable_emojis_remove_dns_prefetch', 10, 2 );
			}
			add_action( 'init', 'wpcontrol_disable_emojis_function' );
		}
	}

		/**
		 * Filter function used to remove the tinymce emoji plugin.
		 * 
		 * @param    array  $plugins  
		 * @return   array             Difference betwen the two arrays
		 */
		function wpcontrol_disable_emojis_tinymce( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			}

			return array();
		}
		
		/**
		 * Remove emoji CDN hostname from DNS prefetching hints.
		 *
		 * @param  array  $urls          URLs to print for resource hints.
		 * @param  string $relation_type The relation type the URLs are printed for.
		 * @return array                 Difference betwen the two arrays.
		 */
		function wpcontrol_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
			if ( 'dns-prefetch' == $relation_type ) {
				// Strip out any URLs referencing the WordPress.org emoji location
				$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
				foreach ( $urls as $key => $url ) {
					if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
						unset( $urls[$key] );
					}
				}
				return $urls;
			}
		}


	

	function wpcontrol_disable_rss_feed() {
		if (wpcontrol_get_option('wpcontrol-disable-rss-feed')) {
			add_action('do_feed', 'wpcontrol_disable_feed', 1);
			add_action('do_feed_rdf', 'wpcontrol_disable_feed', 1);
			add_action('do_feed_rss', 'wpcontrol_disable_feed', 1);
			add_action('do_feed_rss2', 'wpcontrol_disable_feed', 1);
			add_action('do_feed_atom', 'wpcontrol_disable_feed', 1);
			add_action('do_feed_rss2_comments', 'wpcontrol_disable_feed', 1);
			add_action('do_feed_atom_comments', 'wpcontrol_disable_feed', 1);
		}
	}
		function wpcontrol_disable_feed() {
			wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
		}



	function wpcontrol_disable_shortlinks() {
		if(wpcontrol_get_option('wpcontrol-disable-shortlinks')){
			remove_action('wp_head', 'wp_shortlink_wp_head');
			remove_action('template_redirect', 'wp_shortlink_header', 11);
		} 
	}



	function wpcontrol_disable_rsd_link() {
		if(wpcontrol_get_option('wpcontrol-disable-rsd-link')){
			remove_action('wp_head', 'rsd_link');
		} 
	}




	function wpcontrol_disable_xfn_profile_link() {
		if(wpcontrol_get_option('wpcontrol-disable-xfn-profile-link')){
			add_filter('avf_profile_head_tag', '__return_false');
			add_action('wp_loaded' , 'html_compressor');	
		}
	}
		function html_compressor() {
			ob_start( 'html_compressor_main' );
		}
		function html_compressor_main($content) {
			$old_content = $content;
			$content = preg_replace('/<link[^>]+href=(?:\'|")https?:\/\/gmpg.org\/xfn\/11(?:\'|")(?:[^>]+)?>/', '', $content);
			if( empty($content) ) {
				$content = $old_content;
			}
			return $content;
		}


	function wpcontrol_disable_wlwmanifest_link() {
		if(wpcontrol_get_option('wpcontrol-disable-wlwmanifest-link')){
			remove_action('wp_head', 'wlwmanifest_link');
		} 
	}



	function wpcontrol_disable_previous_next_post_link() {
		if(wpcontrol_get_option('wpcontrol-disable-previous-next-post-link')){
			remove_action('wp_head', 'adjacent_posts_rel_link');
			remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
			?>
			<style type="text/css">
			.pagination-single-inner {
  		 		display: none;
			}
			</style>
			<?php
		} 
	}




//==============================================================================================================================================================
//Security Page
//==============================================================================================================================================================




	function wpcontrol_disable_xmlrpc_pingback() {
		if(wpcontrol_get_option('wpcontrol-disable-xml-rpc-pingback')) {
			add_filter( 'xmlrpc_methods', 'wpcontrol_sar_block_xmlrpc_attacks' );
			/**
			 * Unset XML-RPC Methods.
			 *
			 * @param array $methods Array of current XML-RPC methods.
			 */
			/**
			 * Check WP version.
			 */
			if ( version_compare( $wp_version, '4.4' ) >= 0 ) {
				add_action( 'wp', 'wpcontrol_sar_remove_x_pingback_header_44', 9999 );
				/**
				 * Remove X-Pingback from Header for WP 4.4+.
				 */
			} else {

				add_filter( 'wp_headers', 'wpcontrol_sar_remove_x_pingback_header' );
				/**
				 * Remove X-Pingback from Header for older WP versions.
				 *
				 * @param array $headers Array with current headers.
				 */
			}
		}	
	} 
		function wpcontrol_sar_block_xmlrpc_attacks( $methods ) {
			unset( $methods['pingback.ping'] );
			unset( $methods['pingback.extensions.getPingbacks'] );
			return $methods;
		}

		function wpcontrol_sar_remove_x_pingback_header_44() {
			header_remove( 'X-Pingback' );
		}

		function wpcontrol_sar_remove_x_pingback_header( $headers ) {
			unset( $headers['X-Pingback'] );
			return $headers;
		}




	function wpcontrol_disable_user_gravatar_frontend() {
		//fix when moving code to one helper file, make one plugin instead of two for front and back end ////////////////////////////////////////////////////
		if (wpcontrol_get_option('wpcontrol-disable-user-gravatar')) {
			//general gravatar stuff
			add_filter('get_avatar', 'wpcontrol_wp_avatar_frontend', 1, 5);
			add_filter('user_profile_picture_description', '__return_empty_string');
			if( is_admin() ){
				add_action( 'init' , 'wpcontrol_init_frontend');
			}
			//buddypress
			if( get_option('avatar_default') == 'disable_gravatar_buddypress' ){
				add_filter('bp_core_fetch_avatar_no_grav', '__return_true');
			}else{
				add_filter('bp_core_fetch_avatar', 'wpcontrol_bp_avatar_frontend', 1, 2);
				add_filter('bp_core_fetch_avatar_url', 'wpcontrol_bp_avatar_frontend', 1, 2);
			}
		}
		$email_template = "member.%USER%@somerandomdomain.com";
	}

		function wpcontrol_init_frontend(){
			add_filter('avatar_defaults', 'wpcontrol_avatar_defaults_frontend', 1000000); //last one
			add_filter('default_avatar_select', 'wpcontrol_default_avatar_select_frontend', 1000000); //last one
			add_action('admin_init', 'wpcontrol_admin_init_frontend');
		}

		function wpcontrol_admin_init_frontend(){
			register_setting('discussion', 'gravatar_substitute', array(
				'description' => __('If your default avatar is "Disabled", the following image URL will be used for all avatars by default.', 'disable-user-avatar'),
				'sanitize_callback' => 'esc_url',
				)
			);
			add_settings_field('gravatar_substitute', __('Default Avatar Image', 'disable-user-gravatar'), 'wpcontrol_substitute_image_field_frontend', 'discussion', 'avatars');
		}

		function wpcontrol_default_avatar_select_frontend( $default_avatar_select ){
			$disable_gravatar_warning = __("Gravatars are disabled by the 'Disable User Gravatar' plugin. All user emails will be anonymized when sent to gravatar.com and therefore will always produce generated avatars.", 'disable-user-avatar');
			$default_avatar_select = '<p class="description" style="color:#cc0000;">' . $disable_gravatar_warning . '</p>' . $default_avatar_select;
			return $default_avatar_select;
		}

		function wpcontrol_avatar_defaults_frontend( $avatar_defaults ){
			$avatar_defaults['disable_gravatar_substitute'] = esc_html__('Disable Gravatar (Use Default Avatar Image)', 'disable-user-gravatar');
			if( function_exists('buddypress') ){
				$avatar_defaults['disable_gravatar_buddypress'] = esc_html__('Disable Gravatar via BuddyPress', 'disable-user-gravatar');
			}
			return $avatar_defaults;
		}

		function wpcontrol_substitute_image_field_frontend(){
			//add custom field
			$gravatar_substitute = get_option('gravatar_substitute', false);
			if( empty($gravatar_substitute) ) $gravatar_substitute = plugins_url('default-gravatar-wpcontrol.png', __FILE__);
			echo '<input type="text" name="gravatar_substitute" value="' . esc_attr($gravatar_substitute) . '" class="regular-text" placeholder="https://domain.com/path/to/image.jpg">';
			echo '<br><p class="description">'. esc_html__('If your default avatar is "Disable Gravatar", the following image URL will be used for all avatars by default.', 'disable-user-avatar'). '</p>';
		}

		function wpcontrol_wp_avatar_frontend( $content, $id_or_email, $size = '', $default = ''){
			//check default gravatar replacement
			if( $default == 'disable_gravatar_substitute' ){
				$gravatar_substitute = get_option('gravatar_substitute', false);
				if( empty($gravatar_substitute) ) $gravatar_substitute = plugins_url('default-gravatar-wpcontrol.png', __FILE__);
				return preg_replace("/'(https?:)?\/\/.+?'/", $gravatar_substitute, $content);
			}
			//replace gravatar itself
			if( preg_match( "/gravatar.com\/avatar/", $content ) ){
				//get user login
				if ( is_numeric($id_or_email) ) {
					$id = (int) $id_or_email;
					$user = get_userdata($id);
				} elseif ( is_object($id_or_email) ) {
					if ( !empty($id_or_email->user_id) ) {
						$id = (int) $id_or_email->user_id;
						$user = get_userdata($id);
					}elseif( !empty( $id_or_email->post_author) ){
						$user = get_user_by( 'id', (int) $id_or_email->post_author );
					}elseif ( !empty($id_or_email->comment_author_email) ) {
						return $content; //Commenters not logged in don't need filtering
					}
				} else {
					$user = get_user_by('email', $id_or_email);
				}
				if(!$user) return $content;
				$username = $user->user_login;
				//replace the email template with username and md5 it for gravatar
				$email = md5( str_replace('%USER%', $username, $email_template) );
				//replace the image url
				$avatar = preg_replace("/gravatar.com\/avatar\/[a-zA-Z0-9]+/", "gravatar.com/avatar/{$email}", $content);
				return $avatar;
			}
			return $content;
		}
		
		function wpcontrol_bp_avatar_frontend( $content, $params ){
			if( is_array($params) && $params['object'] == 'user' ){
				$default = !empty($params['default']) ? $params['default'] : '';
				return wpcontrol_wp_avatar_frontend($content, $params['item_id'], '', $default);
			}
			return $content;
		}








	function wpcontrol_disable_rest_api() {
		// choose end points to be enabled. keep or delete? //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 if (wpcontrol_get_option('wpcontrol-disable-rest-api')) {
			/*
				Disable REST API link in HTTP headers
				Link: <https://example.com/wp-json/>; rel="https://api.w.org/"
			*/
			remove_action('template_redirect', 'rest_output_link_header', 11);
			/*
				Disable REST API links in HTML <head>
				<link rel='https://api.w.org/' href='https://example.com/wp-json/' />
			*/
			remove_action('wp_head', 'rest_output_link_wp_head', 10);
			remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
			/*
				Disable REST API
			*/
			if( version_compare(get_bloginfo('version'), '4.7', '>=') ) {
				add_filter( 'rest_authentication_errors', 'wpcontrol_disable_wp_rest_api' );
			
			} else {
				// REST API 1.x
				add_filter('json_enabled', '__return_false');
				add_filter('json_jsonp_enabled', '__return_false');
				// REST API 2.x
				add_filter('rest_enabled', '__return_false');
				add_filter('rest_jsonp_enabled', '__return_false');
			}	
		}
	}

		function wpcontrol_disable_wp_rest_api($access) {
			if (!is_user_logged_in() && !wpcontrol_disable_wp_rest_api_allow_access()) {
				$message = apply_filters('disable_wp_rest_api_error', __('REST API restricted to authenticated users.', 'wpcontrol'));
				return new WP_Error('rest_login_required', $message, array('status' => rest_authorization_required_code()));
			}
			return $access;
		}

		function wpcontrol_disable_wp_rest_api_allow_access() {
			$post_var = apply_filters('disable_wp_rest_api_post_var', false);
			if (!empty($post_var)) {
				if (isset($_POST[$post_var]) && !empty($_POST[$post_var])) return true;	
			}
			return false;	
		}



	function wpcontrol_hide_author_login() {
//dont know if this works or not , how to check clearfy ////////////////////////////////////////////////////////////////////////////////////////
		if( isset($_GET['author']) ) {
			wp_redirect(home_url(), 401);
			die();
		}
	}



	function wpcontrol_hide_login_errors() {
		if (wpcontrol_get_option('wpcontrol-hide-login-errors')) {
			add_filter( 'login_errors' , 'wpcontrol_change_login_error' );
		}
	}

		function wpcontrol_change_login_error() {
			if( !in_array($GLOBALS['pagenow'], array('wp-login.php')) ) {
					return $errors;
			}
			return __('<strong>ERROR</strong>: Wrong login or password', 'wpcontrol');
		}



	function wpcontrol_remove_html_comments() {
		if (wpcontrol_get_option('wpcontrol-remove-html-comments')) {
			add_action( 'wp_loaded' , 'clean_html_comments' );
		}
	}
		function clean_html_comments() {
			ob_start(  'replace_html_comments' );
		}

		function replace_html_comments( $html ) {
			$raw_html = $html;
			//CLRF-166 issue fix bug with noindex (\s?\/?noindex)
			$html = preg_replace( '#<!--(?!<!|\s?ngg_resource|\s?\/?noindex)[^\[>].*?-->#s', '', $html );
			// If replacement is completed with an error, user will receive a white screen.
			// We have to prevent it.
			if ( empty( $html ) ) {
				return $raw_html;
			}
			return $html;
		}



	function wpcontrol_remove_meta_generator() {
	//no clue if this will work, code just copied and pasted, dont understand it ////////////////////////////////////////////////////////////////////////////////////////
		if (wpcontrol_get_option('wpcontrol-remove-meta-generator')) {
			if ( class_exists( 'WooCommerce' ) ) {
				remove_action( 'wp_head', 'woo_version' );
			}
			// Clean meta generator for SitePress
			if ( class_exists( 'SitePress' ) ) {
				global $sitepress;
				remove_action( 'wp_head', [ $sitepress, 'meta_generator_tag' ] );
			}

			// Clean meta generator for Wordpress core
			remove_action( 'wp_head', 'wp_generator' );
			add_filter( 'the_generator', '__return_empty_string' );

			// Clean all meta generators
			add_action( 'wp_head', 'clean_meta_generators' , 100 );
		}
	}
		function clean_meta_generators() {
			ob_start( 'replace_meta_generators' );
		}

		function replace_meta_generators( $html ) {
			$raw_html = $html;
			$pattern = '/<meta[^>]+name=["\']generator["\'][^>]+>/i';
			$html    = preg_replace( $pattern, '', $html );
			// If replacement is completed with an error, user will receive a white screen.
			// We have to prevent it.
			if ( empty( $html ) ) {
				return $raw_html;
			}
			return $html;
		}


	function wpcontrol_disable_right_click_everywhere() {
		if (wpcontrol_get_option('wpcontrol-disable-right-click-all')) {
			if (!wpcontrol_get_option('wpcontrol-disable-right-click-admin')) {
				add_action('wp_head', 'wpcontrol_disable_rightclick_js');
			} else {
				if( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
					//Silence Is Golden
				} else {
					add_action('wp_head', 'wpcontrol_disable_rightclick_js');
				}
			}
		}
	}



	function wpcontrol_disable_right_click_homepage() {
		if (wpcontrol_get_option('wpcontrol-disable-right-click-homepage')) {
			if (!wpcontrol_get_option('wpcontrol-disable-right-click-admin')) {
				add_action('template_redirect', 'wpcontrol_homepage_test');	
			} else {
				if( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
					//Silence Is Golden
				} else {
					add_action('template_redirect', 'wpcontrol_homepage_test');	
				}
			}
		}
	}

		function wpcontrol_homepage_test() {
			if (is_home() || is_front_page() || is_archive() || is_post_type_archive() ||  is_404() || is_attachment() || is_author() || is_category() || is_feed() || is_search()) {
				add_action('wp_head', 'wpcontrol_disable_rightclick_js');	
			}
		}



	function wpcontrol_disable_right_click_posts() {
		if (wpcontrol_get_option('wpcontrol-disable-right-click-posts')) {
			if (!wpcontrol_get_option('wpcontrol-disable-right-click-admin')) {
				add_action('template_redirect', 'wpcontrol_post_test');	
			} else {
				if( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
					//Silence Is Golden
				} else {
					add_action('template_redirect', 'wpcontrol_post_test');	
				}
			}
		}
	}
		function wpcontrol_post_test() {
			if (is_single()) {
				add_action('wp_head', 'wpcontrol_disable_rightclick_js');	
			}
		}



	function wpcontrol_disable_right_click_pages() {
		if (wpcontrol_get_option('wpcontrol-disable-right-click-pages')) {
			if (!wpcontrol_get_option('wpcontrol-disable-right-click-admin')) {
				add_action('template_redirect', 'wpcontrol_page_test');	
			} else {
				if( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
					//Silence Is Golden
				} else {
					add_action('template_redirect', 'wpcontrol_page_test');	
				}
			}
		}
	}

		function wpcontrol_page_test() {
			if (is_page()) {
				add_action('wp_head', 'wpcontrol_disable_rightclick_js');	
			}
		}


	function wpcontrol_disable_right_click_alert() {
	    ?>
	    <div id="wpcontrol_right_click_alert"></div>
	<?php } 

	add_action('wp_footer', 'wpcontrol_disable_right_click_alert');



		
		function wpcontrol_disable_rightclick_js() {
			//if( current_user_can('editor') || current_user_can('administrator') ) {
				//Silence Is Golden
			//}else{
				$disablerightclickalert = (wpcontrol_get_option('wpcontrol-disable-right-click-alert')? '1' : '0');
				$show_msg_on_off = esc_html($disablerightclickalert);
				?>
				<script type="text/javascript">
					//<![CDATA[
					var show_msg = '<?php echo esc_js($show_msg_on_off) ?>';
					if (show_msg !== '0') {
						var options = {view_src: "<?php esc_html_e('View Source is disabled!' , 'wpcontrol')?>", inspect_elem: "<?php esc_html_e('Inspect Element is disabled!' , 'wpcontrol')?>", right_click: "<?php esc_html_e('Right click is disabled!' , 'wpcontrol')?>", copy_cut_paste_content: "<?php esc_html_e('Cut/Copy/Paste is disabled!' , 'wpcontrol')?>", image_drop: "<?php esc_html_e('Image Drag-n-Drop is disabled!' , 'wpcontrol')?>" }
					} else {
						var options = '';
					}

		         	function wpcontrol_nocontextmenu(e) { return false; }
		         	document.oncontextmenu = wpcontrol_nocontextmenu;
		         	document.ondragstart = function() { return false;}

					document.onmousedown = function (event) {
						event = (event || window.event);
						if (event.keyCode === 123) {
							if (show_msg !== '0') {show_toast('inspect_elem');}
							return false;
						}
					}
					document.onkeydown = function (event) {
						event = (event || window.event);
						//alert(event.keyCode);   return false;
						if (event.keyCode === 123 ||
								event.ctrlKey && event.shiftKey && event.keyCode === 73 ||
								event.ctrlKey && event.shiftKey && event.keyCode === 75) {
							if (show_msg !== '0') {show_toast('inspect_elem');}
							return false;
						}
						if (event.ctrlKey && event.keyCode === 85) {
							if (show_msg !== '0') {show_toast('view_src');}
							return false;
						}
					}
					function wpcontrol_addMultiEventListener(element, eventNames, listener) {
						var events = eventNames.split(' ');
						for (var i = 0, iLen = events.length; i < iLen; i++) {
							element.addEventListener(events[i], function (e) {
								e.preventDefault();
								if (show_msg !== '0') {
									show_toast(listener);
								}
							});
						}
					}
					wpcontrol_addMultiEventListener(document, 'contextmenu', 'right_click');
					wpcontrol_addMultiEventListener(document, 'cut copy paste print', 'copy_cut_paste_content');
					wpcontrol_addMultiEventListener(document, 'drag drop', 'image_drop');
					function show_toast(text) {
						var x = document.getElementById("wpcontrol_right_click_alert");
						x.innerHTML = eval('options.' + text);
						x.className = "show";
						setTimeout(function () {
							x.className = x.className.replace("show", "")
						}, 3000);
					}
				//]]>
				</script>
				<style type="text/css">body * :not(input):not(textarea){user-select:none !important; -webkit-touch-callout: none !important;  -webkit-user-select: none !important; -moz-user-select:none !important; -khtml-user-select:none !important; -ms-user-select: none !important;}#wpcontrol_right_click_alert{visibility:hidden;min-width:250px;margin-left:-125px;background-color:#333;color:#fff;text-align:center;border-radius:2px;padding:16px;position:fixed;z-index:999;left:50%;bottom:30px;font-size:17px}#wpcontrol_right_click_alert.show{visibility:visible;-webkit-animation:fadein .5s,fadeout .5s 2.5s;animation:fadein .5s,fadeout .5s 2.5s}@-webkit-keyframes fadein{from{bottom:0;opacity:0}to{bottom:30px;opacity:1}}@keyframes fadein{from{bottom:0;opacity:0}to{bottom:30px;opacity:1}}@-webkit-keyframes fadeout{from{bottom:30px;opacity:1}to{bottom:0;opacity:0}}@keyframes fadeout{from{bottom:30px;opacity:1}to{bottom:0;opacity:0}}</style>

				
				<?php
			//}
		}





//========================================================================================================================================================
//Notifications Page
//========================================================================================================================================================




	function wpcontrol_disable_admin_notices() {
	// incomplete ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if (wpcontrol_get_option('wpcontrol-disable-admin-notices') === "wpcontrol-keep-all-notices") {
			
		}

		if (wpcontrol_get_option('wpcontrol-disable-admin-notices') === "wpcontrol-disable-all-notices") {
			?> 
					<style type="text/css">
						body.wp-admin:not(.theme-editor-php) .notice:not(.updated),
						body.wp-admin .update-nag,
						body.wp-admin #adminmenu .awaiting-mod, 
						#adminmenu .update-plugins,
						#message.woocommerce-message,
						body.wp-admin .plugin-update.colspanchange,
						.notice.elementor-message.elementor-message-dismissed
						{display: none !important;}

						body.wp-admin #display-notifications .notice,
						body.wp-admin #display-notifications .update-nag,
						#display-notifications #message.woocommerce-message 
						{
							display: block !important;
						}
					</style>
					<?php
		}
		
		if (wpcontrol_get_option('wpcontrol-disable-admin-notices') === "wpcontrol-disable-specific-notices") {
			# code...
		}

	}





	function wpcontrol_disable_new_user_emails() {
	// look at code from Thomas' Disable New User Notification Emails plugin, code below is from webstite called smartwp //////////////////////////////////////////////////
		if (wpcontrol_get_option('wpcontrol-disable-new-user-emails')) {
				//Remove original use created emails
				remove_action( 'register_new_user', 'wp_send_new_user_notifications' );
				remove_action( 'edit_user_created_user', 'wp_send_new_user_notifications', 10, 2 );
				
				//Add new function to take over email creation
				add_action( 'register_new_user', 'wpcontrol_send_new_user_notifications' );
				add_action( 'edit_user_created_user', 'wpcontrol_send_new_user_notifications', 10, 2 );
		}
	}
		function wpcontrol_send_new_user_notifications( $user_id, $notify = 'user' ) {
			if ( empty($notify) || $notify == 'admin' ) {
				return;
			} elseif ( $notify == 'both' ){
		    	//Only send the new user their email, not the admin
				$notify = 'user';
			}
			wp_send_new_user_notifications( $user_id, $notify );
		}






//==============================================================================================================================================================
//Miscellaneuos Page
//==============================================================================================================================================================


	function wpcontrol_remove_category_url() {
		if (wpcontrol_get_option('wpcontrol-remove-category-url')) {
			/* actions */
			add_action( 'created_category', 'wpcontrol_remove_category_url_refresh_rules' );
			add_action( 'delete_category', 'wpcontrol_remove_category_url_refresh_rules' );
			add_action( 'edited_category', 'wpcontrol_remove_category_url_refresh_rules' );
			add_action( 'init', 'wpcontrol_remove_category_url_permastruct' );

			/* filters */
			add_filter( 'category_rewrite_rules', 'wcontrol_remove_category_url_rewrite_rules' );
			add_filter( 'query_vars', 'wcontrol_remove_category_url_query_vars' );    // Adds 'category_redirect' query variable
			add_filter( 'request', 'wcontrol_remove_category_url_request' );       // Redirects if 'category_redirect' is set
		}
	}
		function wpcontrol_remove_category_url_refresh_rules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}


		/**
		 * Removes category base.
		 *
		 * @return void
		 */
		function wpcontrol_remove_category_url_permastruct() {
			global $wp_rewrite, $wp_version;

			if ( 3.4 <= $wp_version ) {
				$wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
			} else {
				$wp_rewrite->extra_permastructs['category'][0] = '%category%';
			}
		}

		/**
		 * Adds our custom category rewrite rules.
		 *
		 * @param array $category_rewrite Category rewrite rules.
		 *
		 * @return array
		 */
		function wcontrol_remove_category_url_rewrite_rules( $category_rewrite ) {
			global $wp_rewrite;

			$category_rewrite = array();

			/* WPML is present: temporary disable terms_clauses filter to get all categories for rewrite */
			if ( class_exists( 'Sitepress' ) ) {
				global $sitepress;

				remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10 );
				$categories = get_categories( array( 'hide_empty' => false, '_icl_show_all_langs' => true ) );
				add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
			} else {
				$categories = get_categories( array( 'hide_empty' => false ) );
			}

			foreach ( $categories as $category ) {
				$category_nicename = $category->slug;
				if ( $category->parent == $category->cat_ID ) {
					$category->parent = 0;
				} elseif ( 0 != $category->parent ) {
					$category_nicename = get_category_parents( $category->parent, false, '/', true ) . $category_nicename;
				}
				$category_rewrite[ '(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$' ] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
				$category_rewrite[ '(' . $category_nicename . ')/page/?([0-9]{1,})/?$' ]                  = 'index.php?category_name=$matches[1]&paged=$matches[2]';
				$category_rewrite[ '(' . $category_nicename . ')/?$' ]                                    = 'index.php?category_name=$matches[1]';
			}

			// Redirect support from Old Category Base
			$old_category_base                                 = get_option( 'category_base' ) ? get_option( 'category_base' ) : 'category';
			$old_category_base                                 = trim( $old_category_base, '/' );
			$category_rewrite[ $old_category_base . '/(.*)$' ] = 'index.php?category_redirect=$matches[1]';

			return $category_rewrite;
		}

		function wcontrol_remove_category_url_query_vars( $public_query_vars ) {
			$public_query_vars[] = 'category_redirect';

			return $public_query_vars;
		}

		/**
		 * Handles category redirects.
		 *
		 * @param $query_vars Current query vars.
		 *
		 * @return array $query_vars, or void if category_redirect is present.
		 */
		function wcontrol_remove_category_url_request( $query_vars ) {
			if ( isset( $query_vars['category_redirect'] ) ) {
				$catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['category_redirect'], 'category' );
				status_header( 301 );
				header( "Location: $catlink" );
				exit;
			}

			return $query_vars;
		}






	function wpcontrol_disable_search() {
		if (wpcontrol_get_option('wpcontrol-disable-search')) {
			add_action( 'widgets_init' , 'wpcontrol_disable_search_widget' , 1 );
			if ( ! is_admin() ) {
				add_action( 'parse_query' , 'wpcontrol_parse_query' , 5 );
			}
			add_filter( 'get_search_form', 'wpcontrol_get_search_form' , 999 );
			add_action( 'admin_bar_menu', 'wpcontrol_admin_bar_menu' , 11 );
			add_filter( 'disable_wpseo_json_ld_search', '__return_true' );
		}
	}

		function wpcontrol_disable_search_widget() {
			unregister_widget( 'WP_Widget_Search' );
		}

		function wpcontrol_parse_query( $obj ) {
			if ( $obj->is_search && $obj->is_main_query() ) {
				unset( $_GET['s'] );
				unset( $_POST['s'] );
				unset( $_REQUEST['s'] );
				unset( $obj->query['s'] );
				$obj->set( 's', '' );
				$obj->is_search = false;
				$obj->set_404();
				status_header( 404 );
				nocache_headers();
			}
		}

		function wpcontrol_get_search_form( $form ) {
			return '';
		}

		function wpcontrol_admin_bar_menu( $wp_admin_bar ) {
			$wp_admin_bar->remove_menu( 'search' );
		}




	function wpcontrol_disable_lazy_loading() {
		if(wpcontrol_get_option('wpcontrol-disable-lazy-loading')) {
			add_filter('wp_lazy_loading_enabled', '__return_false');
		} 
	}


	function wpcontrol_hide_admin_toolbar() {
		if (wpcontrol_get_option('wpcontrol-hide-admin-toolbar')  && is_user_logged_in()) {
			add_filter( 'show_admin_bar', '__return_false', 999999 );
		}
	}




	function wpcontrol_disable_dashboard_widget_welcome() {
		if (wpcontrol_get_option('wpcontrol-disable-dashboard-welcome')) {
			remove_action( 'welcome_panel', 'wp_welcome_panel' );
		}
	}



	function wpcontrol_disable_dashboard_widget_browse_happy() {
		if (wpcontrol_get_option('wpcontrol-disable-dashboard-browse-happy')) {
			$key = md5( $_SERVER['HTTP_USER_AGENT'] );
			add_filter( 'pre_site_transient_browser_' . $key, '__return_null' );
		}
	}



	function wpcontrol_disable_dashboard_widget_php_update() {
		if (wpcontrol_get_option('wpcontrol-disable-dashboard-php-update')) {
			$key = md5( phpversion() );
			add_filter( 'pre_site_transient_php_check_' . $key, '__return_null' );
		}
	}



	function wpcontrol_disable_dashboard_widget_activity() {
		if (wpcontrol_get_option('wpcontrol-disable-dashboard-activity')) {
			remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		}
	}



	function wpcontrol_disable_dashboard_widget_at_a_glance() {
		if (wpcontrol_get_option('wpcontrol-disable-dashboard-at-a-glance')) {
			remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		}
	}



	function wpcontrol_disable_dashboard_widget_quick_draft() {
		if (wpcontrol_get_option('wpcontrol-disable-dashboard-quick-draft')) {
			remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		}
	}



	function wpcontrol_disable_dashboard_widget_site_health() {
		if (wpcontrol_get_option('wpcontrol-disable-dashboard-site-health')) {
			remove_meta_box( 'dashboard_site_health', 'dashboard', 'side' );
		}
	}



	function wpcontrol_disable_dashboard_widget_events_and_news() {
		if (wpcontrol_get_option('wpcontrol-disable-dashboard-events-and-news')) {
			remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
		}
	}