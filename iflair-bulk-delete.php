<?php  
/*
Plugin Name: All In One Bulk Delete
Description: The iFlair Bulk Delete plugin allows administrators to quickly and efficiently manage and delete large volumes of content from their website, all through an intuitive, AJAX-powered interface.
Plugin URI: https://profiles.wordpress.org/iflairwebtechnologies
Version: 1.1.0
Author: The iFlair Team
Text Domain: bulk-delete-all-in-one
Author URI: https://www.iflair.com/
License: GPLv2 or later
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) { 
	die(); 
}

require_once plugin_dir_path(__FILE__) . 'includes/class-bulk-delete.php';

if ( ! defined( 'IFBDP_VERSION' ) ) {
	define('IFBDP_VERSION', '1.1.0');
}

if ( ! defined( 'IFBDP_PLUGIN_PATH' ) ) {
	define('IFBDP_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
}

if ( ! defined( 'IFBDP_PLUGIN_DIR' ) ) {
	define('IFBDP_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
}

function ifbdp_activate() {
    // Activation code here, like initializing default plugin options
}
register_activation_hook(__FILE__, 'ifbdp_activate');

// Correct Placement for Deactivation Hook
function ifbdp_deactivate() {
    // Deactivation code here, like cleaning up options or temporary data
}
register_deactivation_hook(__FILE__, 'ifbdp_deactivate');

// Instantiate the IFBDP_bulk_delete class
$ifbdp_bulk_delete = new IFBDP_bulk_delete();

// Initialize the plugin
$ifbdp_bulk_delete->ifbdp_init();