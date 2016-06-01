<?php

// load thickbox if on a profile page
function bp_profile_message_ux_thickbox() {

	if( is_user_logged_in() && bp_is_user() && !bp_is_my_profile() )	
		add_thickbox();

}
add_action('bp_head', 'bp_profile_message_ux_thickbox');


// send a Private Message
function bp_profile_message_ux_send_private_message() { 

	if ( !is_user_logged_in() || !isset($_POST['private-message-hidden']) ) 
		return;
	
	check_admin_referer("private_message_check");
	
	$content_feedback_empty = 	__( 'Please enter some content in the Private Message form.', 'bp-profile-message-ux' );
	$content_feedback_success = __( 'Private Message was sent!', 'bp-profile-message-ux' );
	$content_feedback_error =	__( 'There was an error sending that Private Message. Please make sure you fill out both fields.', 'bp-profile-message-ux' );

	// No private message so provide feedback and redirect
	if ( empty( $_POST['private_message_content'] ) ) {
		bp_core_add_message( $content_feedback_empty, 'error' );
		bp_core_redirect( bp_displayed_user_domain() );
	}	
	
	
	$sender_id = bp_loggedin_user_id();
	$recip_id = bp_displayed_user_id();

		
	if ( $thread_id = messages_new_message( array('sender_id' => $sender_id, 'subject' => $_POST['private_message_subject'], 'content' => $_POST['private_message_content'], 'recipients' => $recip_id ) ) ) 
			bp_core_add_message( $content_feedback_success );
			
	else 
		bp_core_add_message( $content_feedback_error, 'error' );
		
	bp_core_redirect( bp_displayed_user_domain() );
	
}
add_action( 'wp', 'bp_profile_message_ux_send_private_message', 3 );


// replace the Private Message button
function bp_profile_private_message_ux_button( $button ) {

	if( !is_user_logged_in() || bp_is_my_profile() )	
		return;

	$button_divs = '';
	
	$button_title = 		__('Send a private message to', 'bp-profile-message-ux');
	$button_label = 		__('Private Message', 'bp-profile-message-ux');	
	$send_button_label = 	__('Send Message', 'bp-profile-message-ux');
	$subject_label =		__('Subject', 'bp-profile-message-ux');
	$message_label = 		__('Message', 'bp-profile-message-ux');
					
	$button_divs .= '<div class="generic-button" id="send-private-message">';
	
	$button_divs .= '<a href="#TB_inline?width=300&height=420&inlineId=create-private-message-ux" class="thickbox" 		id="private-button-id" title="';
	
	$button_divs .= $button_title . ' ' . bp_get_displayed_user_username() . '">';
	
	$button_divs .= $button_label . '</a></div>';
	
	$button_divs .=	'<div id="create-private-message-ux" style="display:none">';
	
	$button_divs .=	'<form action="' . bp_profile_message_ux_send_private_message() . '" name="private-message-form-ux" id="private-message-form-ux"  method="post" class="standard-form">';
					
	$button_divs .=	'<label for="private_message_subject">' . $subject_label . '</label>&nbsp;';
	
	$button_divs .=	'<input type="text" size="41" maxlength="50" name="private_message_subject" id="private_message_subject" /><br /><br />';
	
	$button_divs .=	'<label for="private_message_content">' . $message_label . '</label><br />';
	
	$button_divs .=	'<textarea name="private_message_content" id="private_message_content" rows="10"  cols="52"></textarea><br /><br />';				

	$button_divs .=	'<input type="hidden" name="private-message-hidden" value="1"/>';

	$button_divs .=	'<input name="private_message_send" id="private_message_send" type="submit" name="submit" onclick="this.disabled=true;this.parentNode.submit();" class="button button-primary" value="';
	
	$button_divs .=	$send_button_label . '"/>' . wp_nonce_field( 'private_message_check' ) . '</form></div>';
	
	return $button_divs;

}
add_filter( 'bp_get_send_message_button', 'bp_profile_private_message_ux_button', 1 , 1 );



// send a Public Message
function bp_profile_message_ux_send_public_message() { 

	if ( !is_user_logged_in() || !isset( $_POST['whats-new-profile-ux'] ) )
		return false;

	// Check the nonce
	check_admin_referer( 'public_message_check' );

	$content_feedback_empty = 	__( 'Please enter some content in the Public Message form.', 'bp-profile-message-ux' );
	$content_feedback_success = __( 'Your Public Message has been posted!', 'bp-profile-message-ux' );
	$content_feedback_error =	__( 'There was an error when posting your Public Message, please try again.', 'bp-profile-message-ux' );
	
	// Get public message content
	$content = $_POST['whats-new-profile-ux'];

	// No public message so provide feedback and redirect
	if ( empty( $content ) ) {
		bp_core_add_message( $content_feedback_empty, 'error' );
		bp_core_redirect( bp_displayed_user_domain() );
	}

	$activity_id = bp_activity_post_update( array( 'content' => $content ) );
		
	// Provide user feedback
	if ( !empty( $activity_id ) )
		bp_core_add_message( $content_feedback_success );
	else
		bp_core_add_message( $content_feedback_error, 'error' );

	// Redirect
	bp_core_redirect( bp_displayed_user_domain() );	
		
}
add_action( 'wp', 'bp_profile_message_ux_send_public_message', 3 );


// replace the Public Message button
function bp_profile_public_message_ux_button( $button ) {
	global $bp;

	if( !is_user_logged_in() || bp_is_my_profile() )	
		return;

	$button_title = 		__('Send a public message to', 'bp-profile-message-ux');
	$button_label = 		__('Public Message', 'bp-profile-message-ux');	
	$send_button_label = 	__('Send Message', 'bp-profile-message-ux');
	$message_label = 		__('Message', 'bp-profile-message-ux');		
		
	$button_divs = '';

	$button_divs .= '<div class="generic-button" id="send-public-message">';	

	$button_divs .= '<a href="#TB_inline?width=300&height=320&inlineId=create-public-message-ux" class="thickbox" id="public-button-id" title="';
	
	$button_divs .= $button_title . ' ' . bp_get_displayed_user_username() . '">';
	
	$button_divs .= $button_label . '</a></div>';	
	
	$button_divs .=	'<div id="create-public-message-ux" style="display:none">';	
	
	$button_divs .= '<form action="' . bp_profile_message_ux_send_public_message() . '" name="public-message-form-ux" id="public-message-form-ux"  method="post" class="standard-form">';
	
	$button_divs .=	'<label for="public_message_content">' . $message_label . '</label><br />';	
	
	$button_divs .=	'<textarea name="whats-new-profile-ux" id="whats-new-profile-ux" cols="52" rows="10">';
	
	$button_divs .=	'@' . $bp->displayed_user->userdata->user_login . '</textarea><br />';

	$button_divs .=	'<input type="hidden" name="public-message-hidden" value="1"/>';

	$button_divs .=	'<input name="public_message_send" id="public_message_send" type="submit" name="submit" onclick="this.disabled=true;this.parentNode.submit();" class="button button-primary" value="';
	
	$button_divs .=	$send_button_label . '"/>' . wp_nonce_field( 'public_message_check' ) . '</form></div>';

	echo $button_divs;
	//return $button_divs;
	return NULL;
		
}
add_filter( 'bp_get_send_public_message_button', 'bp_profile_public_message_ux_button', 1, 1 );
