<?php
/**
 * Plugin Name: Lazy SIGN IN
 * Plugin URI: https://wordpress.org/plugins/lazy-sign-in/
 * Description: LAZY SING-IN lets you easily create a fully customizable AJAX powered responsive login and sign-up form for your website.
 * Version: 2.0
 * Author: KrishaWeb
 * Author URI: http://www.krishaweb.com 
 * Text Domain: lazy-sign-in
 * Domain path: /languages
 * License: GPL2
 */
define( 'LAZY_SIGN_IN_VERSION', '2.0' );
define( 'LAZY_SIGN_IN_REQUIRED_WP_VERSION', '4.3' );
define( 'LAZY_SIGN_IN', __FILE__ );
define( 'LAZY_SIGN_IN_BASENAME', plugin_basename( LAZY_SIGN_IN ) );
define( 'LAZY_SIGN_IN_PLUGIN_DIR', plugin_dir_path( LAZY_SIGN_IN ) );
define( 'LAZY_SIGN_IN_PLUGIN_URL', plugin_dir_url( LAZY_SIGN_IN ) );
function lazy_sign_in_activate() {	
}
register_activation_hook( __FILE__, 'lazy_sign_in_activate' );
add_action( 'admin_menu', 'lazy_sign_in_menu' );
function lazy_sign_in_menu() {
    add_menu_page( __( 'Lazy Sign In', 'lazy-sign-in' ), __( 'Lazy Sign In', 'lazy-sign-in' ), 'activate_plugins', 'lazy_sign_in_options', 'lazy_sign_in_options' , LAZY_SIGN_IN_PLUGIN_URL.'images/leftbar-logo.png' , '40');
}
function lazy_sign_in_options() { 
    include "admin/form.php";
}
function lazy_sign_in_deactivate() {
}
register_deactivation_hook( __FILE__, 'lazy_sign_in_deactivate' );
require_once( LAZY_SIGN_IN_PLUGIN_DIR . '/functions.php' );