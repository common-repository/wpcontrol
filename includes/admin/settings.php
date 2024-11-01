<?php


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPControl_Settings {
	public function __construct() {

		add_action( 'admin_menu', array ($this, 'create_menu') );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueues') );
		add_action( 'wp_ajax_wpcontrol_save_settings_checkbox', array($this, 'wpcontrol_save_settings_checkbox'));
		add_action( 'wp_ajax_wpcontrol_save_settings_radio', array($this, 'wpcontrol_save_settings_radio'));
		add_action( 'wp_ajax_wpcontrol_save_settings_text', array($this, 'wpcontrol_save_settings_text'));

		add_filter( 'admin_footer_text', array($this, 'get_admin_footer'), 1, 2 );

	}


	public function create_menu() {

		$wpcontrol_icon = base64_encode('<svg width="36" height="34" viewBox="0 0 36 34" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M24.4035 4H11.5965C7.95335 4 5 6.95335 5 10.5965V23.4035C5 27.0466 7.95335 30 11.5965 30H24.4035C28.0466 30 31 27.0466 31 23.4035V10.5965C31 6.95335 28.0466 4 24.4035 4ZM11.5965 2C6.84878 2 3 5.84878 3 10.5965V23.4035C3 28.1512 6.84878 32 11.5965 32H24.4035C29.1512 32 33 28.1512 33 23.4035V10.5965C33 5.84878 29.1512 2 24.4035 2H11.5965Z" fill="#F3F2F1"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M16.0004 20H23.8534C25.5103 20 26.8534 21.3431 26.8534 23C26.8534 24.6569 25.5103 26 23.8534 26H16.0004C16.6281 25.1643 17 24.1256 17 23C17 21.8744 16.6281 20.8357 16.0004 20ZM12 20C10.3431 20 9 21.3431 9 23C9 24.5533 10.1805 25.8309 11.6933 25.9845C11.7941 25.9948 11.8964 26 12 26C13.6569 26 15 24.6569 15 23C15 21.3431 13.6569 20 12 20ZM7 23C7 20.2386 9.23858 18 12 18H23.8534C26.6148 18 28.8534 20.2386 28.8534 23C28.8534 25.7614 26.6148 28 23.8534 28H12C11.8274 28 11.6569 27.9913 11.4888 27.9742C8.9675 27.7181 7 25.5888 7 23Z" fill="#F3F2F1"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M24 8C22.3431 8 21 9.34315 21 11C21 12.6569 22.3431 14 24 14C25.6569 14 27 12.6569 27 11C27 9.34315 25.6569 8 24 8ZM19.9996 14C19.3719 13.1643 19 12.1256 19 11C19 9.87439 19.3719 8.83566 19.9996 8H12C10.3431 8 9 9.34315 9 11C9 12.6569 10.3431 14 12 14H19.9996ZM7 11C7 8.23858 9.23858 6 12 6H24C26.7614 6 29 8.23858 29 11C29 13.7614 26.7614 16 24 16H12C9.23858 16 7 13.7614 7 11Z" fill="#F3F2F1"/>
</svg>
'
				);

    	add_menu_page(
        	esc_html__('WPControl', 'wpcontrol'), esc_html__('WPControl', 'wpcontrol'), 'manage_options', 'wpcontrol-settings', array($this, 'wpcontrol_settings_page'), 'data:image/svg+xml;base64,' . $wpcontrol_icon , 81
        );
	} 



	public function enqueues() {
		wp_enqueue_script( 'wpcontrol_admin', plugin_dir_url( WPCONTROL_PLUGIN_FILE ) . 'assets/js/admin.js', array(), time() );
		wp_enqueue_style('wpcontrol-admin', plugin_dir_url( WPCONTROL_PLUGIN_FILE ) . 'assets/css/admin.css', array(), time() );
	}

	public function wpcontrol_settings_page() {
		?>
		
			<script type="text/javascript">

// AJAX save functions to save on the fly //

				jQuery(document).ready(function(){
					jQuery(".wpcontrol-input-checkbox").change(function(){
						var value = jQuery(this).is(":checked");
						var title = jQuery(this).prop("name");
						var wpcontrol_nonce = '<?php echo wp_create_nonce("wpcontrol-save-settings-nonce"); ?>';
						jQuery.ajax({
							type : "post",
							dataType : "json",
							url : ajaxurl,
							data : {action: "wpcontrol_save_settings_checkbox", title: title, value: value, nonce: wpcontrol_nonce},
							success: function(response) { }
						})
					});							    
				});

				jQuery(document).ready(function(){
					jQuery(".wpcontrol-input-radio").change(function(){
						var value = jQuery(this).is(":checked");
					   	var title = jQuery(this).prop("name");
				    	var setting = jQuery(this).prop("value");
					  	var wpcontrol_nonce = '<?php echo wp_create_nonce("wpcontrol-save-settings-nonce"); ?>';
					 	jQuery.ajax({
					   		type : "post",
							dataType : "json",
							url : ajaxurl,
							data : {action: "wpcontrol_save_settings_radio", title: title, value: value, nonce: wpcontrol_nonce, setting: setting},
							success: function(response) { }
						})				   
					});						    
				});

				jQuery(document).ready(function(){
					jQuery(".wpcontrol-input-text").change(function(){
						var value = jQuery(this).prop("value");
				    	var title = jQuery(this).prop("name");
					   	var wpcontrol_nonce = '<?php echo wp_create_nonce("wpcontrol-save-settings-nonce"); ?>';
				    	jQuery.ajax({
				        	type : "post",
						    dataType : "json",
							url : ajaxurl,
							data : {action: "wpcontrol_save_settings_text", title: title, value: value, nonce: wpcontrol_nonce},
							success: function(response) { }
						})				   
					});									    
				});	
				  		


// Switching tabs instantly, and saving the tab after reload //

				jQuery(document).ready(function() {
				    jQuery("a.wpcontrol-header-menu-item").click(function() {
				    	if (jQuery(this).hasClass("wpcontrol-header-menu-selected")) {
				    		return;
				    	}
				    	var current_menu = jQuery.find("a.wpcontrol-header-menu-selected");

				    	var current_tab = jQuery(current_menu).data("attrMenu");

				    	jQuery('#' + current_tab).addClass("wpcontrol-settings-tab-hidden");
				    	jQuery(current_menu).removeClass("wpcontrol-header-menu-selected");
				    	var new_tab_id = jQuery(this).data("attrMenu");

				    	jQuery('#' + new_tab_id).removeClass("wpcontrol-settings-tab-hidden");
				    	jQuery(this).addClass("wpcontrol-header-menu-selected");
				    });
				});	

				jQuery(document).ready(function() {

					    var currenturl = window.location.href;
					    // if there's no # in the url, we should return
					    if ( -1 == currenturl.indexOf("#") ) {
					    	return;
					    }

					    // get the hash value
					    var new_tab = currenturl.substring(currenturl.indexOf("#") +1 );

					    // if the hashed value, not one of ours, return
					   	var tabs = [
					    	'general',
					    	'content',
					    	'performance',
					    	'security',
					    	'updates',
					    	'notifications',
					    	'miscellaneous'
					    ];

					    if ( jQuery.inArray( new_tab , tabs ) === -1 ) {
					    	return;
					    }
					    // We shall hide current open item
						var current_menu = jQuery.find("a.wpcontrol-header-menu-selected");
						var current_tab = jQuery(current_menu).data("attrMenu");
				    	jQuery('#' + current_tab).addClass("wpcontrol-settings-tab-hidden");
				    	jQuery(current_menu).removeClass("wpcontrol-header-menu-selected");
				
				    	// Now we shall open new tab
				    	jQuery("#wpcontrol-settings-menu-" + new_tab ).addClass("wpcontrol-header-menu-selected");
				    	jQuery("#wpcontrol-settings-tab-" + new_tab ).removeClass("wpcontrol-settings-tab-hidden");
				});	

// "Saving... -> Saved" functions //

				jQuery(document).ready(function() {
				    jQuery("a.wpcontrol-header-controls-item-text").click(function() {
				    	var save_popup = document.getElementById("wpcontrol-save-popup");

				    	jQuery(save_popup).css("font-weight", "");
				    	jQuery(save_popup).text("<?php esc_html_e('Saving...' , 'wpcontrol')?>")
				    	jQuery(save_popup).fadeIn("1500");
				    	
				    	setTimeout(function() {
				    		jQuery(save_popup).css("font-weight", "bold"); 
				    		jQuery(save_popup).text("<?php esc_html_e('Saved!' , 'wpcontrol')?>")
				    		setTimeout(function() { 
				    			location.reload();
					    		jQuery(save_popup).fadeOut("1500");
					    	}, 200);	
				    	}, 2000);
				    });
				});	

				jQuery(document).ready(function() {
				    jQuery(".wpcontrol-input-checkbox").click(function() {
				    	var save_popup = document.getElementById("wpcontrol-save-popup");

				    	jQuery(save_popup).css("font-weight", "");
				    	jQuery(save_popup).text("<?php esc_html_e('Saving...' , 'wpcontrol')?>")
				    	jQuery(save_popup).fadeIn("1500");
				    	
				    	setTimeout(function() {
				    		jQuery(save_popup).css("font-weight", "bold"); 
				    		jQuery(save_popup).text("<?php esc_html_e('Saved!' , 'wpcontrol')?>")
				    		setTimeout(function() { 
					    		jQuery(save_popup).fadeOut("1500");
					    	}, 2000);	
				    	}, 3000);
				    });
				});	

				jQuery(document).ready(function() {
				    jQuery(".wpcontrol-input-radio").click(function() {
				    	var save_popup = document.getElementById("wpcontrol-save-popup");

				    	jQuery(save_popup).css("font-weight", "");
				    	jQuery(save_popup).text("<?php esc_html_e('Saving...' , 'wpcontrol')?>")
				    	jQuery(save_popup).fadeIn("1500");
				    	
				    	setTimeout(function() {
				    		jQuery(save_popup).css("font-weight", "bold"); 
				    		jQuery(save_popup).text("<?php esc_html_e('Saved!' , 'wpcontrol')?>")
				    		setTimeout(function() { 
					    		jQuery(save_popup).fadeOut("1500");
					    	}, 2000);	
				    	}, 3000);
				    });
				});	



// Disable Comments check all logic //

				jQuery(document).ready(function() {
					jQuery("#wpcontrol-disable-comments-everywhere").click(function() {
						var checked = this.checked;
						jQuery(".wpcontrol-disable-comments-select-all").each(function(){
							this.checked = checked;
						})
					})
				});

				jQuery(document).ready(function() {
					jQuery(".wpcontrol-disable-comments-select-all").click(function() {
						var posts = jQuery("#wpcontrol-disable-comments-posts").is(':checked');
						var pages = jQuery("#wpcontrol-disable-comments-pages").is(':checked');
						var media = jQuery("#wpcontrol-disable-comments-media").is(':checked');
						var comments_everywhere = document.getElementById("wpcontrol-disable-comments-everywhere");
				        if (posts && pages && media) {
				        	comments_everywhere.checked = true;
				        }
				        else {
				        	comments_everywhere.checked = false;
				        }
					})
				});



// Disable Right Click check all logic //

				jQuery(document).ready(function() {
					jQuery("#wpcontrol-disable-right-click-all").click(function() {
						var checked = this.checked;
						jQuery(".wpcontrol-disable-right-click-select-all").each(function(){
							this.checked = checked;
						})
					})
				});

				jQuery(document).ready(function() {
					jQuery(".wpcontrol-disable-right-click-select-all").click(function() {
						var front_page = jQuery("#wpcontrol-disable-right-click-homepage").is(':checked');
						var posts = jQuery("#wpcontrol-disable-right-click-posts").is(':checked');
						var pages = jQuery("#wpcontrol-disable-right-click-pages").is(':checked');
						var right_click_everywhere = document.getElementById("wpcontrol-disable-right-click-all");
				        if (front_page && posts && pages) {
				        	right_click_everywhere.checked = true;
				        }
				        else {
				        	right_click_everywhere.checked = false;
				        }
					})
				});





			</script>
<?php 


// Default Tab // (Switched from general to content since general is phase 2 now)

$active_menu = " wpcontrol-settings-menu-content";
$active_tab = " wpcontrol-settings-tab-content";
global $wpcontrol_settings;



										// Variables that hold the values of the inputs //
//content page
	$disable_comments_everywhere = wpcontrol_get_option("wpcontrol-disable-comments-everywhere", false ) ? ' checked="checked"' : '';
	$disable_comments_posts = wpcontrol_get_option("wpcontrol-disable-comments-posts", false ) ? ' checked="checked"' : '';
	$disable_comments_pages = wpcontrol_get_option("wpcontrol-disable-comments-pages", false ) ? ' checked="checked"' : '';
	$disable_comments_media = wpcontrol_get_option("wpcontrol-disable-comments-media", false ) ? ' checked="checked"' : '';

	$disable_gutenberg = wpcontrol_get_option("wpcontrol-disable-gutenberg", false ) ? ' checked="checked"' : '';
	$disable_gutenberg_nag = wpcontrol_get_option("wpcontrol-disable-gutenberg-nag", false ) ? ' checked="checked"' : '';
//performance page
	$disable_emojis = wpcontrol_get_option("wpcontrol-disable-emojis", false ) ? ' checked="checked"' : '';
	$disable_rssfeed = wpcontrol_get_option("wpcontrol-disable-rss-feed", false ) ? ' checked="checked"' : '';
	$disable_jquery_migrate = wpcontrol_get_option("wpcontrol-disable-jquery-migrate", false ) ? ' checked="checked"' : '';
	$disable_sitemaps = wpcontrol_get_option("wpcontrol-disable-sitemaps", false ) ? ' checked="checked"' : '';
	$disable_embeds = wpcontrol_get_option("wpcontrol-disable-embeds", false ) ? ' checked="checked"' : '';
	$disable_shortlinks = wpcontrol_get_option("wpcontrol-disable-shortlinks", false ) ? ' checked="checked"' : '';
	$disable_rsd_link = wpcontrol_get_option("wpcontrol-disable-rsd-link", false ) ? ' checked="checked"' : '';
	$disable_xfn_profile = wpcontrol_get_option("wpcontrol-disable-xfn-profile-link", false ) ? ' checked="checked"' : '';
	$disable_wlwmanifest = wpcontrol_get_option("wpcontrol-disable-wlwmanifest-link", false ) ? ' checked="checked"' : '';
	$disable_previousnextlink = wpcontrol_get_option("wpcontrol-disable-previous-next-post-link", false ) ? ' checked="checked"' : '';
//security page
	$disable_xmlrpc_pingback = wpcontrol_get_option("wpcontrol-disable-xml-rpc-pingback", false ) ? ' checked="checked"' : '';
	$disable_googlefonts = wpcontrol_get_option("wpcontrol-disable-google-fonts", false ) ? ' checked="checked"' : '';
	$disable_gravatar = wpcontrol_get_option("wpcontrol-disable-user-gravatar", false ) ? ' checked="checked"' : '';
	$disable_restapi = wpcontrol_get_option("wpcontrol-disable-rest-api", false ) ? ' checked="checked"' : '';
	$disable_normal_endpoints = wpcontrol_get_option("wpcontrol-disable-rest-api-normal-endpoints", false ) ? ' checked="checked"' : '';
	$hide_authorlogin = wpcontrol_get_option("wpcontrol-hide-author-login", false ) ? ' checked="checked"' : '';
	$hide_login_errors = wpcontrol_get_option("wpcontrol-hide-login-errors", false ) ? ' checked="checked"' : '';
	$remove_html_comments = wpcontrol_get_option("wpcontrol-remove-html-comments", false ) ? ' checked="checked"' : '';
	$remove_meta_generator = wpcontrol_get_option("wpcontrol-remove-meta-generator", false ) ? ' checked="checked"' : '';

	$disable_rightclick_everywhere = wpcontrol_get_option("wpcontrol-disable-right-click-all", false ) ? ' checked="checked"' : '';
	$disable_rightclick_homepage = wpcontrol_get_option("wpcontrol-disable-right-click-homepage", false ) ? ' checked="checked"' : '';
	$disable_rightclick_posts = wpcontrol_get_option("wpcontrol-disable-right-click-posts", false ) ? ' checked="checked"' : '';
	$disable_rightclick_pages = wpcontrol_get_option("wpcontrol-disable-right-click-pages", false ) ? ' checked="checked"' : '';
	$disable_rightclick_alert = wpcontrol_get_option("wpcontrol-disable-right-click-alert", false ) ? ' checked="checked"' : '';
	$disable_rightclick_exclude_admin = wpcontrol_get_option("wpcontrol-disable-right-click-admin", false ) ? ' checked="checked"' : '';
//updates page

	$disable_core_updates_all = wpcontrol_get_option("wpcontrol-core-updates", "wpcontrol-core-updates-disable-all" ) === "wpcontrol-core-updates-disable-all" ? ' checked="checked"' : '';
	$allow_core_updates_all = wpcontrol_get_option("wpcontrol-core-updates", false ) === "wpcontrol-core-updates-auto-all" ? ' checked="checked"' : '';
	$allow_core_updates_major = wpcontrol_get_option("wpcontrol-core-updates", false ) === "wpcontrol-core-updates-auto-major" ? ' checked="checked"' : '';
	$allow_core_updates_minor = wpcontrol_get_option("wpcontrol-core-updates", false ) === "wpcontrol-core-updates-auto-minor" ? ' checked="checked"' : '';
	$allow_core_updates_development = wpcontrol_get_option("wpcontrol-core-updates", false ) === "wpcontrol-core-updates-auto-development" ? ' checked="checked"' : '';

	$auto_plugin_updates_all = wpcontrol_get_option("wpcontrol-plugin-updates", false ) === "wpcontrol-plugin-updates-auto-all" ? ' checked="checked"' : '';
	$disable_plugin_updates = wpcontrol_get_option("wpcontrol-plugin-updates", false ) === "wpcontrol-plugin-updates-disable-all" ? ' checked="checked"' : '';
	$manual_plugin_updates = wpcontrol_get_option("wpcontrol-plugin-updates", "wpcontrol-plugin-updates-manual" ) === "wpcontrol-plugin-updates-manual" ? ' checked="checked"' : ''; 

	$auto_theme_updates_all = wpcontrol_get_option("wpcontrol-theme-updates", false ) === "wpcontrol-theme-updates-auto-all" ? ' checked="checked"' : '';
	$disable_theme_updates = wpcontrol_get_option("wpcontrol-theme-updates", false ) === "wpcontrol-theme-updates-disable-all" ? ' checked="checked"' : '';
	$manual_theme_updates = wpcontrol_get_option("wpcontrol-theme-updates", "wpcontrol-theme-updates-manual" ) === "wpcontrol-theme-updates-manual" ? ' checked="checked"' : '';

	$disable_update_nags = wpcontrol_get_option("wpcontrol-disable-update-nags", false ) ? ' checked="checked"' : '';
//notifications page
	$keep_admin_notices = wpcontrol_get_option("wpcontrol-disable-admin-notices", "wpcontrol-keep-all-notices" ) === "wpcontrol-keep-all-notices" ? ' checked="checked"' : '';
	$disable_admin_notices_all = wpcontrol_get_option("wpcontrol-disable-admin-notices", false ) === "wpcontrol-disable-all-notices" ? ' checked="checked"' : '';
	$disable_admin_notices_specific = wpcontrol_get_option("wpcontrol-disable-admin-notices", false ) === "wpcontrol-disable-specific-notices" ? ' checked="checked"' : '';
	
	$enable_hidden_admin_notices = wpcontrol_get_option("wpcontrol-enable-hidden-notices", false ) ? ' checked="checked"' : '';

	$disable_new_user_email = wpcontrol_get_option("wpcontrol-disable-new-user-emails", false ) ? ' checked="checked"' : '';
	$disable_wordpress_core_update_email = wpcontrol_get_option("wpcontrol-disable-wordpress-core-update-email", false ) ? ' checked="checked"' : '';
	$disable_plugin_update_email = wpcontrol_get_option("wpcontrol-disable-plugin-update-email", false ) ? ' checked="checked"' : '';
	$disable_theme_update_email = wpcontrol_get_option("wpcontrol-disable-theme-update-email", false ) ? ' checked="checked"' : '';
	$disable_update_release_email = wpcontrol_get_option("wpcontrol-disable-wordpress-update-release-emails", false ) ? ' checked="checked"' : '';
	$disable_emails = wpcontrol_get_option("wpcontrol-disable-wordpress-emails", false ) ? ' checked="checked"' : '';
		$disable_emails_toolbar_indicator = wpcontrol_get_option("wpcontrol-disable-email-indicator", false ) === "wpcontrol-disable-email-toolbar-indicator" ? ' checked="checked"' : ''; 
		$disable_emails_adminpages_indicator = wpcontrol_get_option("wpcontrol-disable-email-indicator", false ) === "wpcontrol-disable-email-adminpages-indicator" ? ' checked="checked"' : '';
		$disable_emails_toolbar_adminpages_indicator = wpcontrol_get_option("wpcontrol-disable-email-indicator", false ) === "wpcontrol-disable-email-toolbar-adminpages-indicator" ? ' checked="checked"' : '';
		$disable_emails_no_indicator = wpcontrol_get_option("wpcontrol-disable-email-indicator", "wpcontrol-disable-email-no-indicator" ) === "wpcontrol-disable-email-no-indicator" ? ' checked="checked"' : ''; 
	$disable_wp_mail = wpcontrol_get_option("wpcontrol-disable-wp-mail", false ) ? ' checked="checked"' : '';
	$disable_wp_mail_from = wpcontrol_get_option("wpcontrol-disable-wp-mail-from", false ) ? ' checked="checked"' : '';
	$disable_wp_mail_from_name = wpcontrol_get_option("wpcontrol-disable-wp-mail-from-name", false ) ? ' checked="checked"' : '';
	$disable_wp_mail_content_type = wpcontrol_get_option("wpcontrol-disable-wp-mail-content-type", false ) ? ' checked="checked"' : '';
	$disable_phpmailer_init = wpcontrol_get_option("wpcontrol-disable-phpmailer-init", false ) ? ' checked="checked"' : '';
	$force_buddypress = wpcontrol_get_option("wpcontrol-disable-buddypress", false ) ? ' checked="checked"' : '';
	$force_eventsmanager = wpcontrol_get_option("wpcontrol-force-events-mamanger", false ) ? ' checked="checked"' : '';
//miscellaneous page
	$remove_category_url = wpcontrol_get_option("wpcontrol-remove-category-url", false ) ? ' checked="checked"' : '';
	$disable_search = wpcontrol_get_option("wpcontrol-disable-search", false ) ? ' checked="checked"' : '';
	$disable_lazy_loading = wpcontrol_get_option("wpcontrol-disable-lazy-loading", false ) ? ' checked="checked"' : '';
	$disable_blog = wpcontrol_get_option("wpcontrol-disable-blog", false ) ? ' checked="checked"' : '';
	$disable_big_image_threshold = wpcontrol_get_option("wpcontrol-disable-big-image-threshold", false ) ? ' checked="checked"' : '';
	$remove_powered_by_wordpress = wpcontrol_get_option("wpcontrol-remove-powered-by-wordpress", false ) ? ' checked="checked"' : '';
		$remove_powered_by_wordpress_replacement = wpcontrol_get_option("wpcontrol-remove-powered-by-wordpress-replacement", false ) ?  wpcontrol_get_option("wpcontrol-remove-powered-by-wordpress-replacement")  : '';
	$hide_admin_toolbar = wpcontrol_get_option("wpcontrol-hide-admin-toolbar", false ) ? ' checked="checked"' : '';

	$disable_sidebar_audio = wpcontrol_get_option("wpcontrol-disable-sidebar-audio", false ) ? ' checked="checked"' : '';
	$disable_sidebar_custom = wpcontrol_get_option("wpcontrol-disable-sidebar-custom", false ) ? ' checked="checked"' : '';
	$disable_sidebar_gallery = wpcontrol_get_option("wpcontrol-disable-sidebar-gallery", false ) ? ' checked="checked"' : '';
	$disable_sidebar_image = wpcontrol_get_option("wpcontrol-disable-sidebar-image", false ) ? ' checked="checked"' : '';
	$disable_sidebar_video = wpcontrol_get_option("wpcontrol-disable-sidebar-video", false ) ? ' checked="checked"' : '';

	$disable_dashboard_welcome = wpcontrol_get_option("wpcontrol-disable-dashboard-welcome", false ) ? ' checked="checked"' : '';
	$disable_dashboard_browse_happy = wpcontrol_get_option("wpcontrol-disable-dashboard-browse-happy", false ) ? ' checked="checked"' : '';
	$disable_dashboard_php_update = wpcontrol_get_option("wpcontrol-disable-dashboard-php-update", false ) ? ' checked="checked"' : '';
	$disable_dashboard_activity = wpcontrol_get_option("wpcontrol-disable-dashboard-activity", false ) ? ' checked="checked"' : '';
	$disable_dashboard_glance = wpcontrol_get_option("wpcontrol-disable-dashboard-at-a-glance", false ) ? ' checked="checked"' : '';
	$disable_dashboard_quick_draft = wpcontrol_get_option("wpcontrol-disable-dashboard-quick-draft", false ) ? ' checked="checked"' : '';
	$disable_dashboard_site_health = wpcontrol_get_option("wpcontrol-disable-dashboard-site-health", false ) ? ' checked="checked"' : '';
	$disable_dashboard_wordpress_news_events = wpcontrol_get_option("wpcontrol-disable-dashboard-events-and-news", false ) ? ' checked="checked"' : '';



?>
		<!-- 														HTML for Plugin -->
		<div class="wrap">

	  		<div class="wpcontrol-header">
	  			<div class="wpcontrol-header-logo-row-container">
		  			<div class="wpcontrol-header-logo-row">
		  				<div class="wpcontrol-header-logo-item"> <svg width="249" height="49" viewBox="0 0 249 49" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect x="1.6228" y="2" width="45" height="45" rx="12.2544" stroke="#595959" stroke-width="3"/>
<rect x="8.6228" y="27" width="31" height="13" rx="6.5" fill="white" stroke="#595959" stroke-width="3"/>
<circle cx="14.8948" cy="33.5" r="6.5" fill="white" stroke="#595959" stroke-width="3"/>
<rect x="8.6228" y="9" width="31" height="13" rx="6.5" fill="#338EEF" stroke="#595959" stroke-width="3"/>
<circle cx="32.8948" cy="15.5" r="6.5" fill="white" stroke="#595959" stroke-width="3"/>
<path d="M90.6372 18.5H95.2772L88.5572 38.5H83.8772L78.8372 23.66L74.0772 38.5H69.3972L62.7172 18.5H67.3172L71.8772 34.3L76.8372 18.5H81.1172L86.0772 34.3L90.6372 18.5ZM97.8159 46.9V18.5H101.616L101.936 24.1L101.376 23.42C101.696 22.38 102.189 21.46 102.856 20.66C103.523 19.86 104.349 19.2333 105.336 18.78C106.349 18.3267 107.456 18.1 108.656 18.1C110.283 18.1 111.763 18.5133 113.096 19.34C114.429 20.1667 115.483 21.3533 116.256 22.9C117.029 24.42 117.416 26.2733 117.416 28.46C117.416 30.6467 117.016 32.5267 116.216 34.1C115.443 35.6467 114.376 36.8333 113.016 37.66C111.683 38.4867 110.189 38.9 108.536 38.9C106.829 38.9 105.376 38.46 104.176 37.58C102.976 36.6733 102.123 35.5267 101.616 34.14L102.096 33.46V46.9H97.8159ZM107.616 35.42C109.349 35.42 110.696 34.8067 111.656 33.58C112.643 32.3533 113.136 30.66 113.136 28.5C113.136 26.34 112.656 24.6467 111.696 23.42C110.736 22.1667 109.403 21.54 107.696 21.54C106.576 21.54 105.589 21.8333 104.736 22.42C103.909 22.98 103.256 23.78 102.776 24.82C102.323 25.8333 102.096 27.06 102.096 28.5C102.096 29.9133 102.323 31.14 102.776 32.18C103.229 33.22 103.869 34.02 104.696 34.58C105.523 35.14 106.496 35.42 107.616 35.42Z" fill="#3D3D3D"/>
<path d="M129.974 18.1C131.361 18.1 132.574 18.3133 133.614 18.74C134.681 19.1667 135.601 19.78 136.374 20.58C137.147 21.38 137.774 22.3267 138.254 23.42L135.214 24.86C134.787 23.58 134.134 22.6067 133.254 21.94C132.401 21.2467 131.281 20.9 129.894 20.9C128.561 20.9 127.414 21.2067 126.454 21.82C125.494 22.4067 124.761 23.2733 124.254 24.42C123.747 25.54 123.494 26.9 123.494 28.5C123.494 30.0733 123.747 31.4333 124.254 32.58C124.761 33.7267 125.494 34.6067 126.454 35.22C127.414 35.8067 128.561 36.1 129.894 36.1C130.934 36.1 131.827 35.94 132.574 35.62C133.347 35.3 133.974 34.8333 134.454 34.22C134.961 33.6067 135.307 32.86 135.494 31.98L138.454 33.02C138.001 34.2467 137.374 35.3 136.574 36.18C135.801 37.06 134.854 37.74 133.734 38.22C132.641 38.6733 131.387 38.9 129.974 38.9C128.107 38.9 126.441 38.4867 124.974 37.66C123.507 36.8333 122.361 35.6467 121.534 34.1C120.707 32.5267 120.294 30.66 120.294 28.5C120.294 26.34 120.707 24.4867 121.534 22.94C122.361 21.3667 123.507 20.1667 124.974 19.34C126.441 18.5133 128.107 18.1 129.974 18.1ZM150.757 18.1C152.65 18.1 154.317 18.5133 155.757 19.34C157.224 20.1667 158.37 21.3667 159.197 22.94C160.024 24.4867 160.437 26.34 160.437 28.5C160.437 30.66 160.024 32.5267 159.197 34.1C158.37 35.6467 157.224 36.8333 155.757 37.66C154.317 38.4867 152.65 38.9 150.757 38.9C148.89 38.9 147.224 38.4867 145.757 37.66C144.29 36.8333 143.144 35.6467 142.317 34.1C141.49 32.5267 141.077 30.66 141.077 28.5C141.077 26.34 141.49 24.4867 142.317 22.94C143.144 21.3667 144.29 20.1667 145.757 19.34C147.224 18.5133 148.89 18.1 150.757 18.1ZM150.757 20.86C149.397 20.86 148.237 21.1667 147.277 21.78C146.317 22.3933 145.57 23.2733 145.037 24.42C144.53 25.54 144.277 26.9 144.277 28.5C144.277 30.0733 144.53 31.4333 145.037 32.58C145.57 33.7267 146.317 34.6067 147.277 35.22C148.237 35.8333 149.397 36.14 150.757 36.14C152.117 36.14 153.277 35.8333 154.237 35.22C155.197 34.6067 155.93 33.7267 156.437 32.58C156.97 31.4333 157.237 30.0733 157.237 28.5C157.237 26.9 156.97 25.54 156.437 24.42C155.93 23.2733 155.197 22.3933 154.237 21.78C153.277 21.1667 152.117 20.86 150.757 20.86ZM164.861 38.5V18.5H167.661L167.941 22.98L167.421 22.54C167.848 21.4733 168.421 20.62 169.141 19.98C169.861 19.3133 170.688 18.8333 171.621 18.54C172.581 18.2467 173.568 18.1 174.581 18.1C176.021 18.1 177.328 18.38 178.501 18.94C179.675 19.4733 180.608 20.3 181.301 21.42C182.021 22.54 182.381 23.9667 182.381 25.7V38.5H179.181V26.82C179.181 24.6067 178.701 23.0733 177.741 22.22C176.808 21.34 175.648 20.9 174.261 20.9C173.301 20.9 172.341 21.1267 171.381 21.58C170.421 22.0333 169.621 22.7667 168.981 23.78C168.368 24.7933 168.061 26.1533 168.061 27.86V38.5H164.861ZM192.473 12.82V32.98C192.473 33.9667 192.753 34.7 193.313 35.18C193.873 35.66 194.593 35.9 195.473 35.9C196.247 35.9 196.913 35.7667 197.473 35.5C198.033 35.2333 198.54 34.8733 198.993 34.42L200.113 37.1C199.5 37.66 198.767 38.1 197.913 38.42C197.087 38.74 196.127 38.9 195.033 38.9C194.02 38.9 193.073 38.7267 192.193 38.38C191.313 38.0067 190.607 37.4333 190.073 36.66C189.567 35.86 189.3 34.82 189.273 33.54V13.7L192.473 12.82ZM199.753 18.5V21.26H185.273V18.5H199.753ZM202.911 38.5V18.5H205.511L205.951 21.78C206.511 20.5533 207.325 19.6333 208.391 19.02C209.485 18.4067 210.805 18.1 212.351 18.1C212.698 18.1 213.071 18.1267 213.471 18.18C213.898 18.2333 214.258 18.34 214.551 18.5L213.991 21.42C213.698 21.3133 213.378 21.2333 213.031 21.18C212.685 21.1267 212.191 21.1 211.551 21.1C210.725 21.1 209.885 21.34 209.031 21.82C208.205 22.3 207.511 23.0333 206.951 24.02C206.391 24.98 206.111 26.2067 206.111 27.7V38.5H202.911ZM225.294 18.1C227.188 18.1 228.854 18.5133 230.294 19.34C231.761 20.1667 232.908 21.3667 233.734 22.94C234.561 24.4867 234.974 26.34 234.974 28.5C234.974 30.66 234.561 32.5267 233.734 34.1C232.908 35.6467 231.761 36.8333 230.294 37.66C228.854 38.4867 227.188 38.9 225.294 38.9C223.428 38.9 221.761 38.4867 220.294 37.66C218.828 36.8333 217.681 35.6467 216.854 34.1C216.028 32.5267 215.614 30.66 215.614 28.5C215.614 26.34 216.028 24.4867 216.854 22.94C217.681 21.3667 218.828 20.1667 220.294 19.34C221.761 18.5133 223.428 18.1 225.294 18.1ZM225.294 20.86C223.934 20.86 222.774 21.1667 221.814 21.78C220.854 22.3933 220.108 23.2733 219.574 24.42C219.068 25.54 218.814 26.9 218.814 28.5C218.814 30.0733 219.068 31.4333 219.574 32.58C220.108 33.7267 220.854 34.6067 221.814 35.22C222.774 35.8333 223.934 36.14 225.294 36.14C226.654 36.14 227.814 35.8333 228.774 35.22C229.734 34.6067 230.468 33.7267 230.974 32.58C231.508 31.4333 231.774 30.0733 231.774 28.5C231.774 26.9 231.508 25.54 230.974 24.42C230.468 23.2733 229.734 22.3933 228.774 21.78C227.814 21.1667 226.654 20.86 225.294 20.86ZM242.439 9.3V33.42C242.439 34.4333 242.625 35.14 242.999 35.54C243.372 35.9133 243.972 36.1 244.799 36.1C245.332 36.1 245.772 36.06 246.119 35.98C246.465 35.9 246.932 35.7533 247.519 35.54L246.999 38.34C246.572 38.5267 246.105 38.66 245.599 38.74C245.092 38.8467 244.572 38.9 244.039 38.9C242.412 38.9 241.199 38.46 240.399 37.58C239.625 36.7 239.239 35.3533 239.239 33.54V9.3H242.439Z" fill="#707070"/>
</svg> </div>
		  				<div class="wpcontrol-header-controls">
		  					<div class="wpcontrol-header-controls-item"> 
		  						<span class="wpcontrol-save-popup wpcontrol-save-popup-hide" id="wpcontrol-save-popup"></span>
		  					</div>
		  					<div class="wpcontrol-header-controls-item"> <a class="wpcontrol-header-controls-item-text"> <?php esc_html_e('Save Changes' , 'wpcontrol')?> </a> </div>
		  				</div>
		  			</div>
		  		</div>
		  		<div class="wpcontrol-header-menu-row-container">
		  			<div class="wpcontrol-header-menu-row">
		  				<div class="wpcontrol-header-menu-item-container">

		  					<a href="#content" id="wpcontrol-settings-menu-content" 
		  					   data-attr-menu="wpcontrol-settings-tab-content" 
		  					   class="wpcontrol-header-menu-item<?php echo $active_menu === ' wpcontrol-settings-menu-content' ? ' wpcontrol-header-menu-selected' : ''?>"><?php esc_html_e('Content' , 'wpcontrol')?></a>
		  					<a href="#performance" id="wpcontrol-settings-menu-performance" 
		  					   data-attr-menu="wpcontrol-settings-tab-performance" 
		  					   class="wpcontrol-header-menu-item<?php echo $active_menu === ' wpcontrol-settings-menu-performance' ? 'wpcontrol-header-menu-selected' : ''?>"><?php esc_html_e('Performance' , 'wpcontrol')?></a>
		  					<a href="#security" id="wpcontrol-settings-menu-security" 
		  					   data-attr-menu="wpcontrol-settings-tab-security" 
		  					   class="wpcontrol-header-menu-item<?php echo $active_menu === ' wpcontrol-settings-menu-security' ? ' wpcontrol-header-menu-selected' : ''?>"><?php esc_html_e('Security' , 'wpcontrol')?></a>
		  					<a href="#notifications" id="wpcontrol-settings-menu-notifications" 
		  					   data-attr-menu="wpcontrol-settings-tab-notifications" 
		  					   class="wpcontrol-header-menu-item<?php echo $active_menu === ' wpcontrol-settings-menu-notifications' ? 'wpcontrol-header-menu-selected' : ''?>"><?php esc_html_e('Notifications' , 'wpcontrol')?></a>
		  					<a href="#miscellaneous" id="wpcontrol-settings-menu-miscellaneous" 
		  					   data-attr-menu="wpcontrol-settings-tab-miscellaneous" 
		  					   class="wpcontrol-header-menu-item<?php echo $active_menu === ' wpcontrol-settings-menu-miscellaneous' ? 'wpcontrol-header-menu-selected' : ''?>"><?php esc_html_e('Miscellaneous' , 'wpcontrol')?></a>
		  				</div>
		  			</div>
		  		</div>
	  		</div>

	  		<div class="wpcontrol-body-row">
	  			<div class="wpcontrol-body-row-container">

														<!-- class="wpcontrol-settings-tab-hidden" removed so content can be default class -->
	  				<div id="wpcontrol-settings-tab-content" class="<?php echo $active_tab === ' wpcontrol-settings-tab-content' ? ' wpcontrol-settings-tab-active' : ''?>">
	  					<div class="wpcontrol-settings-container">
		  					<div class="wpcontrol-settings-title"><?php esc_html_e('Disable Comments' , 'wpcontrol')?></div>
		  					<div class="wpcontrol-settings-content">



		  						<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" id="wpcontrol-disable-comments-everywhere" name="wpcontrol-disable-comments-everywhere"<?php echo esc_attr($disable_comments_everywhere) ?>> <?php esc_html_e('Everywhere' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip">
			  							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg>
										<span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disables comments globally on the site.' , 'wpcontrol')?></span>
									</div>
			  					</div>
			  					<?php esc_html_e('By Post Type' , 'wpcontrol')?>
			  					<div class="wpcontrol-settings-content-indent">
				  					<div>
				  						<label><input type="checkbox" id="wpcontrol-disable-comments-posts" class="wpcontrol-input-checkbox wpcontrol-disable-comments-select-all" name="wpcontrol-disable-comments-posts"<?php echo esc_attr($disable_comments_posts) ?>>  <?php esc_html_e('Posts' , 'wpcontrol')?></label>
				  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disables comments only on posts.' , 'wpcontrol')?></span></div>
				  					</div>
				  					<div>
				  						<label><input type="checkbox" id="wpcontrol-disable-comments-pages" class="wpcontrol-input-checkbox wpcontrol-disable-comments-select-all" name="wpcontrol-disable-comments-pages"<?php echo esc_attr($disable_comments_pages) ?>>  <?php esc_html_e('Pages' , 'wpcontrol')?></label> <div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disables comments only on pages.' , 'wpcontrol')?></span></div>
				  					</div>
				  					<div>
				  						<label><input type="checkbox" id="wpcontrol-disable-comments-media" class="wpcontrol-input-checkbox wpcontrol-disable-comments-select-all" name="wpcontrol-disable-comments-media"<?php echo esc_attr($disable_comments_media) ?>>  <?php esc_html_e('Media' , 'wpcontrol')?></label>
				  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disables comments only on media.' , 'wpcontrol')?></span></div>
									</div>
				  				</div>




			  				</div>
	  					</div>
	  					<div class="wpcontrol-settings-container">
	  						<div class="wpcontrol-settings-title"><?php esc_html_e('Gutenberg' , 'wpcontrol')?></div>
	  						<div class="wpcontrol-settings-content">
	  							<div>
		  							<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-gutenberg"<?php echo esc_attr($disable_gutenberg) ?>> <?php esc_html_e('Disable Gutenberg' , 'wpcontrol')?></label>
		  							<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disables the Gutenberg block editor and reverts it to the Classic Editor' , 'wpcontrol')?></span></div>
		  						</div>
		  						<div>
		  							<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-gutenberg-nag"<?php echo esc_attr($disable_gutenberg_nag) ?>> <?php esc_html_e('Disable "Try Gutenberg" Nag' , 'wpcontrol')?></label>
		  							<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('If you are on a WordPress version before 5.0 then you will get this nag. Just check the box to get rid of it' , 'wpcontrol')?></span></div>
		  						</div>
		  					</div>
	  					</div>
	  				</div>



	  				<div id="wpcontrol-settings-tab-performance" class="wpcontrol-settings-tab-hidden<?php echo $active_tab === ' wpcontrol-settings-tab-performance' ? ' wpcontrol-settings-tab-active' : ''?>">
	  					<div class="wpcontrol-settings-container">
	  						<div class="wpcontrol-settings-content">
	  						
		  						<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-shortlinks"<?php echo esc_attr($disable_shortlinks) ?>> <?php esc_html_e('Disable Shortlinks' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('The tag is auto generated by WordPress and is used to create shortlinks. If you are already using pretty permalinks, such as the PrettyLinks plugin. Then there is no need for this unnecessary tag.' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-rsd-link"<?php echo esc_attr($disable_rsd_link) ?>> <?php esc_html_e('Disable RSD Link' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('RSD Links are used by blog clients and some 3rd parties that utilize XML-RPC requests. If you edit your site through your browser, then you do not need it. Most of the time, it is just unnecessary code.' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-xfn-profile-link"<?php echo esc_attr($disable_xfn_profile) ?>> <?php esc_html_e('Disable XFN Profile Link' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('The XFN Profile Link is used to add semantic data to links to be used by browsers to assign relationships between profiles. Basically it tells browsers that the site contains links that use XFN Specification.' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-wlwmanifest-link"<?php echo esc_attr($disable_wlwmanifest) ?>> <?php esc_html_e('Disable wlwmanifest Link' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('The wlwmanifest link is used by Windows Live Writer. If you do not use Windows Live Writer then disable the link as it is unnecessary code.' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-previous-next-post-link"<?php echo esc_attr($disable_previousnextlink) ?>> <?php esc_html_e('Disable Links to Previous and Next Post' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('If your site is not a blog and is used as a CMS, then this feature will remove the previous and next post links in your WordPress theme.' , 'wpcontrol')?></span></div>
			  					</div>
		  					</div>
	  					</div>
	  				</div>



	  				<div id="wpcontrol-settings-tab-security" class="wpcontrol-settings-tab-hidden<?php echo $active_tab === ' wpcontrol-settings-tab-security' ? ' wpcontrol-settings-tab-active' : ''?>">
	  					<div class="wpcontrol-settings-container">
	  						<div class="wpcontrol-settings-content">
	  							<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-xml-rpc-pingback"<?php echo esc_attr($disable_xmlrpc_pingback) ?>> <?php esc_html_e('Disable XML-RPC Pingback' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Removes XML-RPC method to prevent abuse of sites pingback while you can use the rest of the XML-RPC Pingback method.' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-user-gravatar"<?php echo esc_attr($disable_gravatar) ?>> <?php esc_html_e('Disable User Gravatar' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Blocks users WordPress from getting user Gravatar from their email to add privacy for the users or prevent innappropriate avatars.' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-rest-api"<?php echo esc_attr($disable_restapi) ?>> <?php esc_html_e('Disable Rest-API' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disables the REST-API to prevent abuse of Rest/JSON API.' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-hide-login-errors"<?php echo esc_attr($hide_login_errors) ?>> <?php esc_html_e('Hide Login Errors' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('WordPress shows whether a wrong password or wrong username was typed allowing attacekrs to narrow down usernames and passwords, this setting changes the error to say "incorrect username or password".' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-remove-html-comments"<?php echo esc_attr($remove_html_comments) ?>> <?php esc_html_e('Remove HTML Comments' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Removes HTML comments in source code to add a layer of defense from attackers trying to find the version of plugins.' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-remove-meta-generator"<?php echo esc_attr($remove_meta_generator) ?>> <?php esc_html_e('Remove Meta Generator' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('This meta tag allows attackers to see the version of WordPress, it serves no useful purpose.' , 'wpcontrol')?></span></div>
			  					</div>
		  					</div>
	  					</div>
	  					<div class="wpcontrol-settings-container">
	  						<div class="wpcontrol-settings-title"><?php esc_html_e('Right Click' , 'wpcontrol')?></div>
	  						<div class="wpcontrol-settings-content">
	  							<div>
		  							<label><input type="checkbox" id="wpcontrol-disable-right-click-all" class="wpcontrol-input-checkbox" name="wpcontrol-disable-right-click-all"<?php echo esc_attr($disable_rightclick_everywhere) ?>> <?php esc_html_e('Disable Everywhere' , 'wpcontrol')?></label>
		  							<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disables right click globally on your site.' , 'wpcontrol')?></span></div>
		  						</div>
									<div class="wpcontrol-settings-content-indent">
										<div>
				  							<label><input type="checkbox" id="wpcontrol-disable-right-click-homepage" class="wpcontrol-input-checkbox wpcontrol-disable-right-click-select-all" name="wpcontrol-disable-right-click-homepage"<?php echo esc_attr($disable_rightclick_homepage) ?>> <?php esc_html_e('Disable on Front Page Only' , 'wpcontrol')?></label>
				  							<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disable right click only on the front page.' , 'wpcontrol')?></span></div>
				  						</div>
				  						<div>
					  						<label><input type="checkbox" id="wpcontrol-disable-right-click-posts" class="wpcontrol-input-checkbox wpcontrol-disable-right-click-select-all" name="wpcontrol-disable-right-click-posts"<?php echo esc_attr($disable_rightclick_posts) ?>> <?php esc_html_e('Disable on Posts Only' , 'wpcontrol')?></label>
					  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disable right click only on posts.' , 'wpcontrol')?></span></div>
					  					</div>
					  					<div>
					  						<label><input type="checkbox" id="wpcontrol-disable-right-click-pages" class="wpcontrol-input-checkbox wpcontrol-disable-right-click-select-all" name="wpcontrol-disable-right-click-pages"<?php echo esc_attr($disable_rightclick_pages) ?>> <?php esc_html_e('Disable on Pages Only' , 'wpcontrol')?></label>
					  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disable right click only on pages.' , 'wpcontrol')?></span></div>
					  					</div>
				  					</div>
				  				<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" id="wpcontrol-disable-right-click-alert" name="wpcontrol-disable-right-click-alert"<?php echo esc_attr($disable_rightclick_alert) ?>>  <?php esc_html_e('Show "Right click is disabled!" Alert' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('When a user tries to right click, an alert pops up that says "Right click is disabled!".' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-right-click-admin"<?php echo esc_attr($disable_rightclick_exclude_admin) ?>> <?php esc_html_e('Exclude Admin from Right Click Disable' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('If the admin is logged in, they can right click anywhere on the site while visitors cannot.' , 'wpcontrol')?></span></div>
			  					</div>
	  						</div>
	  					</div>
	  				</div>



	  				<div id="wpcontrol-settings-tab-notifications" class="wpcontrol-settings-tab-hidden<?php echo $active_tab === ' wpcontrol-settings-tab-notifications' ? ' wpcontrol-settings-tab-active' : ''?>">
	  					<div class="wpcontrol-settings-container">
	  						<div class="wpcontrol-settings-title"><?php esc_html_e('Admin Notices' , 'wpcontrol')?></div>
	  						<div class="wpcontrol-settings-content">
	  							<div>
		  							<label><input type="radio" class="wpcontrol-input-radio" name="wpcontrol-disable-admin-notices" value="wpcontrol-keep-all-notices"<?php echo esc_attr($keep_admin_notices) ?>> <?php esc_html_e('Keep All Notices' , 'wpcontrol')?></label>
		  							<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Default setting; all admin notices are visible.' , 'wpcontrol')?></span></div>
		  						</div>
		  						<div>
			  						<label><input type="radio" class="wpcontrol-input-radio" name="wpcontrol-disable-admin-notices" value="wpcontrol-disable-all-notices"<?php echo esc_attr($disable_admin_notices_all) ?>> <?php esc_html_e('Disable All Notices' , 'wpcontrol')?></label>
			  							<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('All admin notices are hidden and none will display.' , 'wpcontrol')?></span></div>
			  					</div>
	  						</div>
	  					</div>
	  					<div class="wpcontrol-settings-container">
	  						<div class="wpcontrol-settings-title"><?php esc_html_e('Emails' , 'wpcontrol')?></div>
	  						<div class="wpcontrol-settings-content">
	  							<div>
	  								<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-new-user-emails"<?php echo esc_attr($disable_new_user_email) ?>> <?php esc_html_e('Disable New User Emails' , 'wpcontrol')?></label>
	  								<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Stops WordPress from sending new user notification emails to admin.' , 'wpcontrol')?></span></div>
	  							</div>
		  					</div>
	  					</div>
	  				</div>

					

	  				<div id="wpcontrol-settings-tab-miscellaneous" class="wpcontrol-settings-tab-hidden<?php echo $active_tab === ' wpcontrol-settings-tab-miscellaneous' ? ' wpcontrol-settings-tab-active' : ''?>">
	  					<div class="wpcontrol-settings-container">
	  						<div class="wpcontrol-settings-content">
	  							<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-search"<?php echo esc_attr($disable_search) ?>> <?php esc_html_e('Disable Search' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Disable the front-end search bar in WordPress.' , 'wpcontrol')?></span></div>
			  					</div>
			  					<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-lazy-loading"<?php echo esc_attr($disable_lazy_loading) ?>> <?php esc_html_e('Disable Lazy Loading' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Removes the lazy loading functionality that was added in WordPress 5.3.' , 'wpcontrol')?></span></div>
			  					</div>
				  				<div>
			  						<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-hide-admin-toolbar"<?php echo esc_attr($hide_admin_toolbar) ?>> <?php esc_html_e('Hide Admin Toolbar' , 'wpcontrol')?></label>
			  						<div class="wpcontrol-tooltip"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="wpcontrol-question-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
										</svg> <span class="wpcontrol-tooltiptext"> <?php esc_html_e('Hides the admin toolbar when the admin is on the front-end.' , 'wpcontrol')?></span></div>
			  					</div>
		  					</div>
	  					</div>
						<div class="wpcontrol-settings-container">
							<div class="wpcontrol-settings-title"><?php esc_html_e('Disable Dashboard Widgets' , 'wpcontrol')?></div>
							<div class="wpcontrol-settings-content">
								<div>
									<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-dashboard-welcome"<?php echo esc_attr($disable_dashboard_welcome) ?>> <?php esc_html_e('Welcome Panel' , 'wpcontrol')?></label>
								</div>
								<div>
									<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-dashboard-browse-happy"<?php echo esc_attr($disable_dashboard_browse_happy) ?>> <?php esc_html_e('Browse Happy' , 'wpcontrol')?></label>
								</div>
								<div>
									<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-dashboard-php-update"<?php echo esc_attr($disable_dashboard_php_update) ?>> <?php esc_html_e('PHP Update Required' , 'wpcontrol')?></label>
								</div>
								<div>
									<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-dashboard-activity"<?php echo esc_attr($disable_dashboard_activity) ?>> <?php esc_html_e('Acticity' , 'wpcontrol')?></label>
								</div>
								<div>
									<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-dashboard-at-a-glance"<?php echo esc_attr($disable_dashboard_glance) ?>> <?php esc_html_e('At a Glance' , 'wpcontrol')?></label>
								</div>
								<div>
									<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-dashboard-quick-draft"<?php echo esc_attr($disable_dashboard_quick_draft) ?>> <?php esc_html_e('Quick Draft' , 'wpcontrol')?></label>
								</div>
								<div>
									<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-dashboard-site-health"<?php echo esc_attr($disable_dashboard_site_health) ?>> <?php esc_html_e('Site Health' , 'wpcontrol')?></label>
								</div>
								<div>
									<label><input type="checkbox" class="wpcontrol-input-checkbox" name="wpcontrol-disable-dashboard-events-and-news"<?php echo esc_attr($disable_dashboard_wordpress_news_events) ?>> <?php esc_html_e('WordPress Events and News' , 'wpcontrol')?></label>
								</div>
							<!--	<input type="checkbox" name="wpcontrol-disable-dashboard-"> Other Plugins <br>	--> 
							</div>
	  					</div>
	  				</div>
	  			</div>
	  		</div>
		</div>
		<?php


	}




	// Change footer to display a text to ask users to review the plugin on WP.org.
	public function get_admin_footer( $text ) {
		$query = $_SERVER['QUERY_STRING'];
		//echo $query; // Outputs: Query String
		if ( is_admin() && strpos($query, 'wpcontrol') !== false ) {

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			/////////////			CHANGE THIS $url 			//////////////////////////////////////////////////////////////////////////////
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
			$url = 'https://wordpress.org/support/plugin/wpcontrol/reviews/?filter=5#new-post';

			$text = sprintf(
				wp_kses(
					/* translators: %1$s - WP.org link; %2$s - same WP.org link. */
					__( 'Please rate <strong>WPControl</strong> <a href="%1$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%2$s" target="_blank" rel="noopener noreferrer">WordPress.org</a> to help us spread the word. Thank you from the WPControl team!', 'wpcontrol' ),
					array(
						'strong' => array(),
						'a'      => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				$url,
				$url
			);
		}
		return $text;
	}
 





// 																save functions for inputs
	public function wpcontrol_save_settings_checkbox() {

		$wpcontrol_settings = array(
			//content page
				//comments
			"wpcontrol-disable-comments-everywhere",
			"wpcontrol-disable-comments-posts",
			"wpcontrol-disable-comments-pages",
			"wpcontrol-disable-comments-media",
				//gutenberg
			"wpcontrol-disable-gutenberg",
			"wpcontrol-disable-gutenberg-nag",

			//performance page
			"wpcontrol-disable-emojis",
			"wpcontrol-disable-rss-feed",
			"wpcontrol-disable-jquery-migrate",
			"wpcontrol-disable-sitemaps",
			"wpcontrol-disable-embeds",
			"wpcontrol-disable-shortlinks",
			"wpcontrol-disable-rsd-link",
			"wpcontrol-disable-xfn-profile-link",
			"wpcontrol-disable-wlwmanifest-link",
			"wpcontrol-disable-previous-next-post-link",

			//security page
			"wpcontrol-disable-xml-rpc-pingback",
			"wpcontrol-disable-google-fonts",
			"wpcontrol-disable-user-gravatar",
			"wpcontrol-disable-rest-api",
			"wpcontrol-disable-rest-api-normal-endpoints",
			"wpcontrol-hide-author-login",
			"wpcontrol-hide-login-errors",
			"wpcontrol-remove-html-comments",
			"wpcontrol-remove-meta-generator",
				//right-click
			"wpcontrol-disable-right-click-all",
			"wpcontrol-disable-right-click-homepage",
			"wpcontrol-disable-right-click-posts",
			"wpcontrol-disable-right-click-pages",
			"wpcontrol-disable-right-click-alert",
			"wpcontrol-disable-right-click-admin",

			//updates page
			"wpcontrol-disable-update-nags",

			//notification page
				//admin notices
			"wpcontrol-enable-hidden-notices",
				//emails
			"wpcontrol-disable-new-user-emails",
			"wpcontrol-disable-wordpress-core-update-email",
			"wpcontrol-disable-plugin-update-email",
			"wpcontrol-disable-theme-update-email",
			"wpcontrol-disable-wordpress-update-release-emails",
			"wpcontrol-disable-wordpress-emails",
		
			"wpcontrol-disable-wp-mail",
			"wpcontrol-disable-wp-mail-from",
			"wpcontrol-disable-wp-mail-from-name",
			"wpcontrol-disable-wp-mail-content-type",
			"wpcontrol-disable-phpmailer-init",
				
			"wpcontrol-disable-buddypress",
			"wpcontrol-force-events-mamanger",

			//miscellaneous page
			"wpcontrol-remove-category-url",
			"wpcontrol-disable-search",
			"wpcontrol-disable-lazy-loading",
			"wpcontrol-disable-blog",
			"wpcontrol-disable-big-image-threshold",
			"wpcontrol-remove-powered-by-wordpress",
			"wpcontrol-hide-admin-toolbar",
				//sidebar widgets
			"wpcontrol-disable-sidebar-audio",
			"wpcontrol-disable-sidebar-custom",
			"wpcontrol-disable-sidebar-gallery",
			"wpcontrol-disable-sidebar-image",
			"wpcontrol-disable-sidebar-video",
				//dashboard widgets
			"wpcontrol-disable-dashboard-welcome",
			"wpcontrol-disable-dashboard-browse-happy",
			"wpcontrol-disable-dashboard-php-update",
			"wpcontrol-disable-dashboard-activity",
			"wpcontrol-disable-dashboard-at-a-glance",
			"wpcontrol-disable-dashboard-quick-draft",
			"wpcontrol-disable-dashboard-site-health",
			"wpcontrol-disable-dashboard-events-and-news",
		);


		if ( empty($_POST['nonce'] ) || empty( $_POST['value'])) {
			die();
		}

		if ( !current_user_can('manage_options')) {
			die();
		}

		if ( !wp_verify_nonce( $_POST['nonce'], 'wpcontrol-save-settings-nonce')) {
      		die();				
		}

		if ( !in_array($_POST['title'], $wpcontrol_settings)) {
			die();
		} 

		$value = false;
		if ( $_POST['value'] === 'true' ) {
			$value = true;
		} else if ( $_POST['value'] === 'false' ) {
			$value = false;
		} else {
			wp_die();
		}

		$title = sanitize_text_field($_POST['title']);
		wpcontrol_update_option($title, $value);

	// Logic for Disable Comments check all

		if ($_POST['title'] === "wpcontrol-disable-comments-everywhere") {
			if (wpcontrol_get_option("wpcontrol-disable-comments-everywhere")) {
				wpcontrol_update_option('wpcontrol-disable-comments-posts', true);
				wpcontrol_update_option('wpcontrol-disable-comments-pages', true);
				wpcontrol_update_option('wpcontrol-disable-comments-media', true);
			} else {
				wpcontrol_update_option('wpcontrol-disable-comments-posts', false);
				wpcontrol_update_option('wpcontrol-disable-comments-pages', false);
				wpcontrol_update_option('wpcontrol-disable-comments-media', false);
			}	
		}

		if ($_POST['title'] === "wpcontrol-disable-comments-posts" || "wpcontrol-disable-comments-pages" || "wpcontrol-disable-comments-media") {
			if (wpcontrol_get_option('wpcontrol-disable-comments-posts') && wpcontrol_get_option('wpcontrol-disable-comments-pages') && wpcontrol_get_option('wpcontrol-disable-comments-media')) {
				wpcontrol_update_option('wpcontrol-disable-comments-everywhere', true);
			} else {
				wpcontrol_update_option('wpcontrol-disable-comments-everywhere', false);
			}	
		}

	// Logic for Disable Right Click check all

		if ($_POST['title'] === "wpcontrol-disable-right-click-all") {
			if (wpcontrol_get_option("wpcontrol-disable-right-click-all")) {
				wpcontrol_update_option('wpcontrol-disable-right-click-homepage', true);
				wpcontrol_update_option('wpcontrol-disable-right-click-posts', true);
				wpcontrol_update_option('wpcontrol-disable-right-click-pages', true);
			} else {
				wpcontrol_update_option('wpcontrol-disable-right-click-homepage', false);
				wpcontrol_update_option('wpcontrol-disable-right-click-posts', false);
				wpcontrol_update_option('wpcontrol-disable-right-click-pages', false);
			}	
		}

		if ($_POST['title'] === "wpcontrol-disable-right-click-homepage" || $_POST['title'] === "wpcontrol-disable-right-click-posts" || $_POST['title'] === "wpcontrol-disable-right-click-pages") {
			if (wpcontrol_get_option("wpcontrol-disable-right-click-homepage") && wpcontrol_get_option("wpcontrol-disable-right-click-posts") && wpcontrol_get_option("wpcontrol-disable-right-click-pages")) {
				wpcontrol_update_option('wpcontrol-disable-right-click-all', true);
			} else {
				wpcontrol_update_option('wpcontrol-disable-right-click-all', false);
			}	
		}

		die();			    					    
	}





	public function wpcontrol_save_settings_radio() {

		$wpcontrol_settings = array(
			"wpcontrol-core-updates" => [
				"wpcontrol-core-updates-disable-all",
				"wpcontrol-core-updates-auto-all",
				"wpcontrol-core-updates-auto-major",
				"wpcontrol-core-updates-auto-minor",
				"wpcontrol-core-updates-auto-development"
			],
			"wpcontrol-plugin-updates" => [
				"wpcontrol-plugin-updates-auto-all",
				"wpcontrol-plugin-updates-disable-all",
				"wpcontrol-plugin-updates-manual"
			],
			"wpcontrol-theme-updates" => [
				"wpcontrol-theme-updates-auto-all",
				"wpcontrol-theme-updates-disable-all",
				"wpcontrol-theme-updates-manual"
			],
			"wpcontrol-disable-admin-notices" => [
				"wpcontrol-keep-all-notices",
				"wpcontrol-disable-all-notices",
				"wpcontrol-disable-specific-notices"
			],
			"wpcontrol-disable-email-indicator" => [
				"wpcontrol-disable-email-toolbar-indicator",
				"wpcontrol-disable-email-adminpages-indicator",
				"wpcontrol-disable-email-toolbar-adminpages-indicator",
				"wpcontrol-disable-email-no-indicator"
			]
			
		);


		if ( empty($_POST['nonce'] ) || empty( $_POST['value'])) {
			die();
		}

		if ( !current_user_can('manage_options')) {
			die();
		}

		if ( !wp_verify_nonce( $_POST['nonce'], 'wpcontrol-save-settings-nonce')) {
      		die();				
		}

		if ( empty($wpcontrol_settings[$_POST['title']])) {
			die();
		} 	

		if ( !in_array($_POST['setting'], $wpcontrol_settings[$_POST['title']])) {
			die();
		} else {
			$value = sanitize_text_field($_POST['setting']);
		}
		$title = sanitize_text_field($_POST['title']);


		wpcontrol_update_option($title, $value);
		die();				    					    
	}

	public function wpcontrol_save_settings_text() {

		if ( empty($_POST['nonce'] ) || empty( $_POST['value'])) {
			die();
		}

		if ( !current_user_can('manage_options')) {
			die();
		}

		if ( !wp_verify_nonce( $_POST['nonce'], 'wpcontrol-save-settings-nonce')) {
      		die();				
		}

		$sanitized_input = sanitize_text_field($_POST['value']);

		if ( strlen($sanitized_input) > 250) {
			die();
		}
		
		esc_html($sanitized_input);

		$value = $sanitized_input;

		$title = sanitize_text_field($_POST['title']);

		wpcontrol_update_option($title, $value);
		die();
	}
}