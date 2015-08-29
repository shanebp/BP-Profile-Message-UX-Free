<?php
/*
Plugin Name: BP Profile Message UX Free
Description: A BuddyPress plugin. Replaces the functionality for Public and Private buttons so that you stay on a profile page when sending.  
Author: shanebp
License: GPLv2 
Author URI: http://philogames.com/contact
Version: 1.4
*/

if ( !defined( 'ABSPATH' ) ) exit;  

define( 'BP_PROFILE_MESSAGE_UX', '1.4' );

function bp_profile_message_ux_init() {
    require( dirname( __FILE__ ) . '/bp-profile-message-ux.php' );
	load_plugin_textdomain( 'bp-profile-message-ux', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );	
}
add_action( 'bp_init', 'bp_profile_message_ux_init' );

