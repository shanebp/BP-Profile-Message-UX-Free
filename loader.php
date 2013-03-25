<?php
/*
Plugin Name: BP Profile Message UX
Description: A BuddyPress plugin. Replaces the functionality for Public and Private buttons so that you stay on a profile page.  
Author: shanebp
Version: 1.0
Author URI: http://philogames.com/contact
*/

/**
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
*/


if ( !defined( 'ABSPATH' ) ) exit;  

define( 'BP_PROFILE_MESSAGE_UX', '1.0' );

function bp_profile_message_ux_init() {
    require( dirname( __FILE__ ) . '/bp-profile-message-ux.php' );
	load_plugin_textdomain( 'bp-profile-message-ux', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );	
}
add_action( 'bp_init', 'bp_profile_message_ux_init' );

?>