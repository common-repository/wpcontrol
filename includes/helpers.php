<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpcontrol_get_options() {
	return get_option('wpcontrol_settings');
}

function wpcontrol_get_option_name() {return 'wpcontrol_settings';}

function wpcontrol_get_option( $key = '', $default = false ) {
	global $wpcontrol_settings;
	$value = ! empty( $wpcontrol_settings[ $key ] ) ? $wpcontrol_settings[ $key ] : $default;
	$value = apply_filters( 'wpcontrol_get_option', $value, $key, $default );
	return apply_filters( 'wpcontrol_get_option' . $key, $value, $key, $default );
}

function wpcontrol_update_option ($key = '', $value = false ) {
	// If no key, exit
	if ( empty( $key ) ){
		return false;
	}

	if ( empty( $value ) ) {
		$remove_option = wpcontrol_delete_option( $key );
		global $wpcontrol_settings;
		unset($wpcontrol_settings[ $key ]);
		return $remove_option;
	}

	$option_name = wpcontrol_get_option_name();

	// First let's grab the current settings

	// if on network panel or if on single site using network settings
	//$settings              = get_site_option( $option_name );
	//$use_network_settings  = ! empty( $use_network_settings['use_network_settings'] ) ? true : false;
	//$is_network            = is_multisite();
	//$update_network_option = true;
	//if ( ! is_network_admin() && ! ( $is_network && $use_network_settings ) ) {
	   $settings = get_option( $option_name );
	//   $update_network_option = false;
	//}

	if ( ! is_array( $settings ) ) {
		$settings = array();
	}

	// Let's let devs alter that value coming in
	$value = apply_filters( 'wpcontrol_update_option', $value, $key );

	// Next let's try to update the value
	$settings[ $key ] = $value;
	$did_update = false;
	//if ( $update_network_option ) {
	//    $did_update = update_site_option( $option_name, $settings );
	//} else {
		$did_update = update_option( $option_name, $settings );
	//}

	// If it updated, let's update the global variable
	if ( $did_update ){
		global $wpcontrol_settings;
		$wpcontrol_settings[ $key ] = $value;
	}

	return $did_update;
}

 /**
 * Helper method for deleting a setting's value.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key.
 * @return boolean True if removed, false if not.
 */



function wpcontrol_delete_option ($key = '') {
	// If no key, exit
	if ( empty( $key ) ){
		return false;
	}

	$option_name = wpcontrol_get_option_name();

	// First let's grab the current settings

	// if on network panel or if on single site using network settings
	//$settings              = get_site_option( $option_name );
	//$use_network_settings  = ! empty( $use_network_settings['use_network_settings'] ) ? true : false;
	//$is_network            = is_multisite();
	//$update_network_option = true;
	//if ( ! is_network_admin() && ! ( $is_network && $use_network_settings ) ) {
	   $settings = get_option( $option_name );
	//   $update_network_option = false;
	//}

	// Next let's try to remove the key
	if( isset( $settings[ $key ] ) ) {
		unset( $settings[ $key ] );
	}

	$did_update = false;
	//if ( $update_network_option ) {
	//    $did_update = update_site_option( 'monsterinsights_settings', $settings );
	//} else {
		$did_update = update_option( $option_name, $settings );
	//}

	// If it updated, let's update the global variable
	if ( $did_update ){
		global $wpcontrol_settings;
		$wpcontrol_settings = $settings;
	}

	return $did_update;
}

 /**
 * Helper method for deleting multiple settings value.
 *
 * @since 6.0.0
 * @access public
 *
 * @param string $key   The setting key.
 * @return boolean True if removed, false if not.
 */



function wpcontrol_delete_options ($keys = array() ) {
	// If no keys, exit
	if ( empty( $keys ) || ! is_array( $keys ) ){
		return false;
	}

	$option_name = wpcontrol_get_option_name();

	// First let's grab the current settings

	// if on network panel or if on single site using network settings
	//$settings              = get_site_option( $option_name );
	//$use_network_settings  = ! empty( $use_network_settings['use_network_settings'] ) ? true : false;
	//$is_network            = is_multisite();
	//$update_network_option = true;
	//if ( ! is_network_admin() && ! ( $is_network && $use_network_settings ) ) {
	   $settings = get_option( $option_name );
	//   $update_network_option = false;
	//}

	// Next let's try to remove the keys
	foreach ( $keys as $key ) {
		if( isset( $settings[ $key ] ) ) {
			unset( $settings[ $key ] );
		}
	}

	$did_update = false;
	//if ( $update_network_option ) {
	//    $did_update = update_site_option( 'monsterinsights_settings', $settings );
	//} else {
		$did_update = update_option( $option_name, $settings );
	//}

	// If it updated, let's update the global variable
	if ( $did_update ){
		global $wpcontrol_settings;
		$wpcontrol_settings = $settings;
	}

	return $did_update;
}