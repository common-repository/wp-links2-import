<?php
/*
Plugin Name: Links Import
Plugin URI: http://cgito.net/wp/
Description: Import links 2.0 to wordpress.
Version: 1.0
Author: Truong Tan Dat
Author URI: http://cgito.net/
License: Copyright 2012  Links ImportE  (email : dat@cgito.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


//register_activation_hook( __FILE__, array('Links2Import', 'install') );
// For some reason __FILE__ works for the activation hook, even when the class is in another file.
//include_once plugin_dir_path( __FILE__ ).'/install.php';
//register_activation_hook( __FILE__, array( 'YourAdditionalClass', 'on_activate_function' ) );

// This is the easier readable version. I recommend using the following in your plugin:
// Install stuff
$plugin_prefix_root = plugin_dir_path( __FILE__ );

define('LINKS2IMPORT_LANG',  $plugin_prefix_root . 'languages');
define('LINKS2IMPORT_ADMIN',  $plugin_prefix_root . 'admin');

define('LINKS2IMPORT_DB_CATS',  $wpdb->prefix . 'links2_cats');
define('LINKS2IMPORT_DB_LINKS',  $wpdb->prefix . 'links2_links');
define('LINKS2IMPORT_DB_CATLINKS',  $wpdb->prefix . 'links2_catlinks');

$plugin_prefix_filename = "{$plugin_prefix_root}install.php";
require_once $plugin_prefix_filename;
//_e("plugin_prefix_filename:" . $plugin_prefix_filename);
//$installer = new Links2Import();
//register_activation_hook( $plugin_prefix_filename, $installer->install() );
//register_uninstall_hook( $plugin_prefix_filename, $installer->uninstall() );




//register_activation_hook( $plugin_prefix_filename, array( 'Links2Import', 'install' ) );
//register_deactivation_hook( $plugin_prefix_filename, array( 'Links2Import', 'uninstall' ) );
register_activation_hook( __FILE__, array('Links2Import', 'install') );
register_deactivation_hook( __FILE__, array('Links2Import', 'deinstall') );
register_uninstall_hook( __FILE__, array('Links2Import', 'uninstall') );

// admin stuff
require_once (LINKS2IMPORT_ADMIN . "/admin.php");
add_action('admin_menu', 'link2import_admin_page');

require_once (LINKS2IMPORT_ADMIN . "/admin_pages.php");
add_filter('the_content',array('Links2ImportPages', 'showhome'));




// other post/content stuff


// loading language if there is

load_plugin_textdomain('lang_en', false, LINKS2IMPORT_LANG );
 



?>