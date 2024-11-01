<?php
/**
* Plugin Name:			WPControl
* Plugin URI: 			https://www.wpcontrol.com/?utm_source=liteplugin&utm_medium=pluginheader&utm_campaign=pluginurl&utm_content=7%2E0%2E0
* Description: 			The all in one plugin to optimize your WordPress website.
* Version:				1.0.1
* Requries at least: 	5.0.0
* Requires PHP: 		5.6
* Author:				WPControl Team
* License: 				GPL V3
* Text Domain:			wpcontrol
* Domain Path:			/languages
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 *
 * @since 1.0.0
 *
 * @package WPControl
 * @author  Zain Balkhi
 * @access public
 */
final class WPControl_Lite {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var object Instance of instantiated WPControl class.
	 */
	public static $instance;

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string $version Plugin version.
	 */
	public $version = '1.0.1';

	/**
	 * Plugin file.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string $file PHP File constant for main file.
	 */
	public $file;

	/**
	 * The name of the plugin.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string $plugin_name Plugin name.
	 */
	public $plugin_name = 'WPControl Lite';

	/**
	 * Unique plugin slug identifier.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var string $plugin_slug Plugin slug.
	 */
	public $plugin_slug = 'wpcontrol-lite';

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		// We don't use this
	}

	/**
	 * Returns the singleton instance of the class.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return object The WPControl_Lite object.
	 */
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WPControl_Lite ) ) {
			self::$instance = new WPControl_Lite();
			self::$instance->file = __FILE__;

			global $wp_version;

			// Detect non-supported WordPress version and return early
			if ( version_compare( $wp_version, '5.0', '<' ) ) {
				add_action( 'admin_notices', array( self::$instance, 'wpcontrol_wp_notice' ) );
				return;
			}

			// Detect non-complaint PHP versions
			if ( version_compare(PHP_VERSION, '5.6.0', '<') ) {
				add_action( 'admin_notices', array( self::$instance, 'wpcontrol_php_notice' ) );
				return;
			}

			// Define constants
			self::$instance->define_globals();

			// Load in settings
			self::$instance->load_settings();

			// Load files
			self::$instance->require_files();

			// This does the version to version background upgrade routines and initial install
			$wpc_version = get_option( 'wpcontrol_current_version', '1.0.0' );
			if ( version_compare( $wpc_version, '1.0.0', '<' ) ) {
				wpcontrol_lite_call_install_and_upgrade();
			}

			// Load the plugin textdomain.
			add_action( 'plugins_loaded', array( self::$instance, 'load_plugin_textdomain' ), 15 );

			// Load admin only components.
			if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
				self::$instance->settings           = new WPControl_Settings();
			}
		}

		return self::$instance;

	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'wpcontrol' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * Attempting to wakeup an WPControl instance will throw a doing it wrong notice.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'wpcontrol' ), '1.0.0' );
	}

	/**
	 * Magic get function.
	 *
	 * We use this to lazy load certain functionality. 
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __get( $key ) {	
			return self::$instance->$key;
	}

	/**
	 * Define WPControl constants.
	 *
	 * This function defines all of the WPControl PHP constants.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function define_globals() {

		if ( ! defined( 'WPCONTROL_VERSION' ) ) {
			define( 'WPCONTROL_VERSION', $this->version );
		}

		if ( ! defined( 'WPCONTROL_LITE_VERSION' ) ) {
			define( 'WPCONTROL_LITE_VERSION', WPCONTROL_VERSION );
		}

		if ( ! defined( 'WPCONTROL_PLUGIN_NAME' ) ) {
			define( 'WPCONTROL_PLUGIN_NAME', $this->plugin_name );
		}

		if ( ! defined( 'WPCONTROL_PLUGIN_SLUG' ) ) {
			define( 'WPCONTROL_PLUGIN_SLUG', $this->plugin_slug );
		}

		if ( ! defined( 'WPCONTROL_PLUGIN_FILE' ) ) {
			define( 'WPCONTROL_PLUGIN_FILE', $this->file );
		}

		if ( ! defined( 'WPCONTROL_PLUGIN_DIR' ) ) {
			define( 'WPCONTROL_PLUGIN_DIR', plugin_dir_path( $this->file )  );
		}

		if ( ! defined( 'WPCONTROL_PLUGIN_URL' ) ) {
			define( 'WPCONTROL_PLUGIN_URL', plugin_dir_url( $this->file )  );
		}
	}

	/**
	 * Loads the plugin textdomain for translation.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {

		$wpc_locale = get_locale();
		if ( function_exists( 'get_user_locale' ) ) {
			$wpc_locale = get_user_locale();
		}

		// Traditional WordPress plugin locale filter.
		$wpc_locale  = apply_filters( 'plugin_locale',  $wpc_locale, 'wpcontrol' );
		$wpc_mofile  = sprintf( '%1$s-%2$s.mo', 'wpcontrol', $wpc_locale );

		// Look for wp-content/languages/wpcontrol/wpcontrol-{lang}_{country}.mo
		$wpc_mofile1 = WP_LANG_DIR . '/wpcontrol/' . $wpc_mofile;

		// Look in wp-content/languages/plugins/wpcontrol/wpcontrol-{lang}_{country}.mo
		$wpc_mofile2 = WP_LANG_DIR . '/plugins/wpcontrol/' . $wpc_mofile;

		// Look in wp-content/languages/plugins/wpcontrol-{lang}_{country}.mo
		$wpc_mofile3 = WP_LANG_DIR . '/plugins/' . $wpc_mofile;

		// Look in wp-content/plugins/wpcontrol/languages/wpcontrol-{lang}_{country}.mo
		$wpc_mofile4 = dirname( plugin_basename( WPCONTROL_PLUGIN_FILE ) ) . '/languages/';
		$wpc_mofile4 = apply_filters( 'wpcontrol_lite_languages_directory', $wpc_mofile4 );

		if ( file_exists( $wpc_mofile1 ) ) {
			load_textdomain( 'wpcontrol', $wpc_mofile1 );
		} elseif ( file_exists( $wpc_mofile2 ) ) {
			load_textdomain( 'wpcontrol', $wpc_mofile2 );
		} elseif ( file_exists( $wpc_mofile3 ) ) {
			load_textdomain( 'wpcontrol', $wpc_mofile3 );
		} else {
			load_plugin_textdomain( 'wpcontrol', false, $wpc_mofile4 );
		}

	}

	/**
	 * Output a nag notice if the user has an out of date WP version installed
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return 	void
	 */
	public function wpcontrol_wp_notice() {
		$url = admin_url( 'plugins.php' );
		// Check for MS dashboard
		if( is_network_admin() ) {
			$url = network_admin_url( 'plugins.php' );
		}
		?>
		<div class="error">
			<p><?php echo sprintf( esc_html__( 'Sorry, but your version of WordPress does not meet WPControl\'s required version of %1$s5.0.0%2$s to run properly. The plugin not been activated. %3$sClick here to return to the Dashboard%4$s.', 'wpcontrol' ), '<strong>', '</strong>', '<a href="' . $url . '">', '</a>' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Output a notice if the user PHP version is less than 5.6
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return 	void
	 */
	public function wpcontrol_php_notice() {
		$url = admin_url( 'plugins.php' );
		// Check for MS dashboard
		if( is_network_admin() ) {
			$url = network_admin_url( 'plugins.php' );
		}
		?>
		<div class="error">
			<p><?php echo sprintf( esc_html__( 'Sorry, but your version of PHP does not meet WPControl\'s required version of %1$s5.6.0%2$s to run properly. The plugin not been activated. %3$sClick here to return to the Dashboard%4$s.', 'wpcontrol' ), '<strong>', '</strong>', '<a href="' . $url . '">', '</a>' ); ?></p>
		</div>
		<?php

	}

	/**
	 * Loads WPControl settings
	 *
	 * Adds the items to the base object, and adds the helper functions.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function load_settings() {
		global $wpcontrol_settings;
		require_once WPCONTROL_PLUGIN_DIR . 'includes/helpers.php';
		$wpcontrol_settings  = wpcontrol_get_options();
	}


	/**
	 * Loads all files into scope.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return 	void
	 */
	public function require_files() {

		if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
			require_once WPCONTROL_PLUGIN_DIR . 'includes/admin/settings.php';
		}

		require_once WPCONTROL_PLUGIN_DIR . 'includes/frontend/frontend.php';

	}
}

/**
 * Fired when the plugin is activated.
 *
 * @access public
 * @since 1.0.0
 *
 * @global int $wp_version      The version of WordPress for this install.
 * @global object $wpdb         The WordPress database object.
 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false otherwise.
 *
 * @return void
 */
function wpcontrol_lite_activation_hook( $network_wide ) {

	global $wp_version;

	$url = admin_url( 'plugins.php' );
	// Check for MS dashboard
	if ( is_network_admin() ) {
		$url = network_admin_url( 'plugins.php' );
	}

	if ( version_compare( $wp_version, '5.0.0', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( sprintf( esc_html__( 'Sorry, but your version of WordPress does not meet WPControl\'s required version of %1$s5.0.0%2$s to run properly. The plugin not been activated. %3$sClick here to return to the Dashboard%4$s.', 'wpcontrol' ), '<strong>', '</strong>', '<a href="' . $url . '">', '</a>' ) );
	}

	if ( version_compare(PHP_VERSION, '5.6.0', '<') ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( sprintf( esc_html__( 'Sorry, but your version of PHP does not meet WPControl\'s required version of %1$s5.6.0%2$s to run properly. The plugin not been activated. %3$sClick here to return to the Dashboard%4$s.', 'wpcontrol' ), '<strong>', '</strong>', '<a href="' . $url . '">', '</a>' ) );
	}

	// Add transient to trigger redirect.
	set_transient( '_wpcontrol_activation_redirect', 1, 30 );
}
register_activation_hook( __FILE__, 'wpcontrol_lite_activation_hook' );




// Plugin Page settings link //
function wpcontrol_plugin_settings_link( $links ) {
	// Build and escape the URL.
	$url = esc_url( add_query_arg(
		'page',
		'wpcontrol-settings',
		get_admin_url() . 'admin.php'
	) );
	// Create the link.
	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
	// Adds the link to the end of the array.
	array_unshift(
		$links,
		$settings_link
	);
	return $links;
}//end nc_settings_link()

add_filter('plugin_action_links_wp-control/wp-control.php', 'wpcontrol_plugin_settings_link'  , 10 , 2);





// Admin Notice after Activation //

register_activation_hook( __FILE__, 'wpcontrol_activation_notice' );

function wpcontrol_activation_notice() {
    set_transient( 'wpcontrol-admin-notice-activation', true, 5 );
}

add_action( 'admin_notices', 'wpcontrol_adnin_notice_activation' );

function wpcontrol_adnin_notice_activation(){
	$url = esc_url( add_query_arg(
		'page',
		'wpcontrol-settings',
		get_admin_url() . 'admin.php'
	) );
    /* Check transient, if available display notice */
    if( get_transient( 'wpcontrol-admin-notice-activation' ) ){
        ?>
        <div class="updated notice is-dismissible">
            <p>
            	<?php 
	            	$text = sprintf(
						wp_kses(
							/* translators: %1$s - WP.org link; %2$s - same WP.org link. */
							__( '<strong>Go to <a href="%1$s" rel="noopener noreferrer">WPControl</a> to configurate settings.</strong>', 'wpcontrol' ),
							array(
								'strong' => array(),
								'a'      => array(
									'href'   => array(),
									'rel'    => array(),
								),
							)
						),
						$url,
						$url
					);
					echo($text);
            	?>
            </p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'wpcontrol-admin-notice-activation' );
    }
}



/**
 * Fired when the plugin is uninstalled.
 *
 * @access public
 * @since 1.0.0
 *
 * @return 	void
 */
function wpcontrol_lite_uninstall_hook() {
	wp_cache_flush();

	$instance = WPControl();

	// If uninstalling via wp-cli load admin-specific files only here.
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		define( 'WP_ADMIN', true );
		$instance->require_files();
	}

	if ( is_multisite() ) {
		$site_list = get_sites();
		foreach ( (array) $site_list as $site ) {
			switch_to_blog( $site->blog_id );
			restore_current_blog();
		}
	} else {
	}
}
register_uninstall_hook( __FILE__, 'wpcontrol_lite_uninstall_hook' );

/**
 * The main function responsible for returning the one true WPControl_Lite
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $wpcontrol = WPControl_Lite(); ?>
 *
 * @since 1.0.0
 *
 * @uses WPControl_Lite::get_instance() Retrieve WPControl_Lite instance.
 *
 * @return WPControl_Lite The singleton WPControl_Lite instance.
 */
function WPControl_Lite() {
	return WPControl_Lite::get_instance();
}

/**
 * WPControl Install and Updates.
 *
 * This function is used install and upgrade WPControl. This is used for upgrade routines
 * that can be done automatically, behind the scenes without the need for user interaction
 * (for example pagination or user input required), as well as the initial install.
 *
 * @since 1.0.0
 * @access public
 *
 * @global string $wp_version WordPress version (provided by WordPress core).
 * @uses WPControl_Lite::load_settings() Loads WPControl settings
 * @uses WPControl_Install::init() Runs upgrade process
 *
 * @return void
 */
function wpcontrol_lite_install_and_upgrade() {
	global $wp_version;

	// If the WordPress site doesn't meet the correct WP version requirements, don't activate WPControl
	if ( version_compare( $wp_version, '5.0.0', '<' ) ) {
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			return;
		}
	}

	// Don't run if PHP < 5.6
	if ( version_compare(PHP_VERSION, '5.6.0', '<') ) {
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			return;
		}
	}


	// Load settings and globals (so we can use/set them during the upgrade process)
	WPControl_Lite()->define_globals();
	WPControl_Lite()->load_settings();

	// Load upgrade file
	require_once WPCONTROL_PLUGIN_DIR . 'includes/install.php';

	// Run the WPControl upgrade routines
	$updates = new WPControl_Install();
	$updates->init();
}

/**
 * WPControl check for install and update processes.
 *
 * This function is used to call the WPControl automatic upgrade class, which in turn
 * checks to see if there are any update procedures to be run, and if
 * so runs them. Also installs WPControl for the first time.
 *
 * @since 1.0.0
 * @access public
 *
 * @uses WPControl_Install() Runs install and upgrade process.
 *
 * @return void
 */
function wpcontrol_lite_call_install_and_upgrade(){
	add_action( 'wp_loaded', 'wpcontrol_lite_install_and_upgrade' );
}

/**
 * Returns the WPControl combined object that you can use for both
 * WPControl Lite and Pro Users. When both plugins active, defers to the
 * more complete Pro object.
 *
 * Warning: Do not use this in Lite or Pro specific code (use the individual objects instead).
 * Also do not use in the WPControl Lite/Pro upgrade and install routines.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Prevents the need to do conditional global object logic when you have code that you want to work with
 * both Pro and Lite.
 *
 * Example: <?php $wpcontrol = WPControl(); ?>
 *
 * @since 1.0.0
 *
 * @uses WPControl::get_instance() Retrieve WPControl Pro instance.
 * @uses WPControl_Lite::get_instance() Retrieve WPControl Lite instance.
 *
 * @return WPControl The singleton WPControl instance.
 */
if ( ! function_exists( 'WPControl' ) ) {
	function WPControl() {
		return ( class_exists( 'WPControl' ) ? WPControl() : WPControl_Lite() );
	}
	add_action( 'plugins_loaded', 'WPControl' );
}


