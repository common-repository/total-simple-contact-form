<?php

/*
 * Plugin Name: Total Simple Contact Form
 * Plugin URI:
 * Description: a total simple contact form, support multilinguality.
 * Text Domain: total-simple-contact-form
 * Version: 1.1.1
 * Author: ZHU,Qing
 * Author URI: http://www.zhu-qing.com
 * License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


add_shortcode('tscf-contact-form','tscf_contact_form');
function tscf_contact_form($tscf_var){
	$tscf_var = shortcode_atts( array( 
		"beginn_text" 			=>__('Please enter your contact details and a short message below and we will try to answer your query as soon as possible.', 'total-simple-contact-form'),
		"name_label"			=>__('Name: *','total-simple-contact-form'),
		"email_label"           =>__('Email Address: *','total-simple-contact-form'), 
		"message_label"         =>__('Message: *','total-simple-contact-form'), 	
		"retype_label"          =>__('Re-type the following text with capital letter *','total-simple-contact-form'), 	
		"refresh_label"         =>__('Refresh Captcha','total-simple-contact-form'), 	
		"sent_me"               =>__('You need a copy: ','total-simple-contact-form'),
		"sent_message"          =>__('Send Message','total-simple-contact-form'),
		
		"your_name"             =>__('Your name','total-simple-contact-form'),
		"your_email"            =>__('Your email','total-simple-contact-form'),
		"your_message"          =>__('Your message','total-simple-contact-form'),
		"captcha"               =>__('Captcha','total-simple-contact-form'),
		
		"sent_success"          =>__('Your message was sent successfully.','total-simple-contact-form'),
		"thanks_text"           =>__('Thank you for your submission! We will contact you very shortly.','total-simple-contact-form'),
		"required_error"        =>__('Please complete the required field.', 'total-simple-contact-form'),
		"captcha_wrong"         =>__('Captcha code is wrong. Try to get it right or reload it to get a new captcha code.','total-simple-contact-form'),
		"time_out"              =>__('Captcha timed out. Please refresh captcha to get a new captcha code.','total-simple-contact-form'),
		), $tscf_var);
	
	$output = '';
	$output .= '<div id="tscf-contact-form">';
	
	// Display confirmation message to users who submit a message 
	if ( isset ( $_GET['success_message'] ) && $_GET['success_message']) {

		$output .= '<div id="tscf_thanks">';
		$output .= '<b>' . $tscf_var['sent_success'] . '</b><br>';
		$output .= $tscf_var['thanks_text'];
		$output .= '</div>';
	}		
	else{
		//session_start();
		$name_session="";
		$email_session="";
		$message_session="";
		
		if ( isset ( $_GET['required_missing'] ) && $_GET['required_missing']) { 
			$output .= '<div class="error_text">';
			$output .= $tscf_var['required_error'];
			$output .= '</div>';
		}		
		if ( isset ( $_GET['captcha_wrong'] ) && $_GET['captcha_wrong']) { 
			$output .= '<div class="error_text">';
			$output .= $tscf_var['captcha_wrong'];
			$output .= '</div>';
		}
		if ( isset ( $_GET['time_out'] ) && $_GET['time_out']) { 
			$output .= '<div class="error_text">';
			$output .= $tscf_var['time_out'];
			$output .= '</div>';
		}
		if ( isset($_SESSION['name_session'])){
			$name_session=$_SESSION['name_session'];
		}
		if(isset($_SESSION['email_session'])){
			$email_session=$_SESSION['email_session'];
		}
		if(isset($_SESSION['message_session'])){
			$message_session=$_SESSION['message_session'];
		}

	// Display form
	$output .=  '<p>' . $tscf_var['beginn_text'] .'</p>';
	$output .= '<form method="post"  action="">';
	$output .=	wp_nonce_field( 'add_tscf_form', 'br_tscf_form' ); 
	$output .= '<input type="hidden" name="tscf_default_contact_form" value="1">';
	
	$output .=  $tscf_var['name_label'];
	$output .= '<br>';
	$output .= '<input type="text" id="tscf_contact_name" name="tscf_name"  value="' .  $name_session .'" placeholder="' .$tscf_var['your_name'] . '">';
	$output .= '<br><br>';
	
	$output .= $tscf_var['email_label'];
	$output .= '<br>';
	$output .= '<input type="email" id="tscf_contact_email" name="tscf_email" value="' . $email_session .'" placeholder="' . $tscf_var['your_email'] . '"><br><br>';
	
	$output .=$tscf_var['message_label'];
	$output .= '<br>';
	$output .= '<textarea id="tscf_contact_message" name="tscf_message" rows="10"  placeholder="' . $tscf_var['your_message'] . '">' . $message_session. '</textarea><br><br>';
			
	$output .= '<label>' . $tscf_var['retype_label'];
	$output .= '<br>';
			
	$output .= '<img id="tscf_captcha" src="'. plugins_url( 'tscf_captcha/tscf_captcha.php', __FILE__ ) . '" name="tscf_img" />';

	$output .= '&nbsp' . '<a class="refresh_captcha">'. $tscf_var['refresh_label'] .'</a>';

	$output .= '<div id="captcha_input">';
	$output .= '<input type="text" id="tscf_contact_captcha" name="tscf_captcha" rows="10" placeholder="' . $tscf_var['captcha'] . '"/>	';
	$output .= '</div>';
	$output .= '</label> <br><br>';
	
	$output .= '<label>' . $tscf_var['sent_me'];
	$output .= '<input type="checkbox" name="sent_copy" ><br><br></label>';	
	$output .= '<input type="submit"  value=" ' . $tscf_var["sent_message"]  .'" >';
	$output .= '</form>';
	$output .= '</div>'; // ende tscf contact form
	
	$output .= "<script type='text/javascript'>";
	$output .= "jQuery( document ).ready( function() {";
	$output .= "jQuery( '.refresh_captcha' ).click( function()
										{ document.getElementById('tscf_captcha').src='".plugins_url('tscf_captcha/tscf_captcha.php',__FILE__) ."?t='+new Date().getTime();} ";
	$output .= ")});";	
	
	$output .= session_destroy();
	
	$output .= "</script>";
	}
	return $output;
}

function register_my_session()
{
  if( !session_id() )
  {
    session_start();
  }
}
add_action('init', 'register_my_session');

add_action('wp_enqueue_scripts','tscf_load_jquery');
function tscf_load_jquery(){
	wp_enqueue_script('jquery');
}

add_action( 'init', 'tscf_match_contact_form' );
function tscf_match_contact_form( $template ) {	
	if ( !empty( $_POST['tscf_default_contact_form'] ) ) {
		tscf_process_contact_form();
	} else {
		return $template;
	}		
}

function tscf_process_contact_form() {	
	$tscf_name = strip_tags($_POST["tscf_name"]);
	$tscf_email = sanitize_email($_POST["tscf_email"]);
	$tscf_message = strip_tags($_POST["tscf_message"]);
	$tscf_captcha = strip_tags($_POST["tscf_captcha"]);
	$_SESSION['name_session'] = $tscf_name;
	$_SESSION['email_session'] = $tscf_email;
	$_SESSION['message_session'] = $tscf_message;
		
	$email_from = __('Email from: ', 'total-simple-contact-form');
	$email_address = __('Email Address: ', 'total-simple-contact-form');
	$email_message = __('Message: ', 'total-simple-contact-form');
	$message_from =__('Message from ', 'total-simple-contact-form');
	$copy_from = __('Copy from Message to ', 'total-simple-contact-form');
		
	// Check that all required fields are present and non-empty
	if ( wp_verify_nonce( $_POST['br_tscf_form'], 'add_tscf_form' ) ) {
		// Variable used to determine if submission is valid
		$form_valid = false;
		if(empty($tscf_name) || empty($tscf_email) || empty($tscf_message) || empty( $tscf_captcha )){
			$redirectaddress = ( empty( $_POST['_wp_http_referer'] ) ? site_url() : $_POST['_wp_http_referer'] );
			wp_redirect( add_query_arg( array('success_message'=>'0','required_missing'=>'1','captcha_wrong'=>'','time_out'=>''),$redirectaddress ) );
			exit;
		}
		else{
			$captcha_wert=implode("", $_SESSION['captcha_wert']);		
			if($tscf_captcha != $captcha_wert){
				$redirectaddress = ( empty( $_POST['_wp_http_referer'] ) ? site_url() : $_POST['_wp_http_referer'] );
				wp_redirect( add_query_arg( array('success_message'=>'0','required_missing'=>'0','captcha_wrong'=>'1','time_out'=>''),$redirectaddress ) );
				exit;
			}
			elseif ( ( time() - 5 * 60 ) > $_SESSION['captcha_time'] ) {
				$redirectaddress = ( empty( $_POST['_wp_http_referer'] ) ? site_url() : $_POST['_wp_http_referer'] );
				wp_redirect( add_query_arg( array('success_message'=>'0','required_missing'=>'0','captcha_wrong'=>'0','time_out'=>'1'),$redirectaddress ) );
				exit;
				} 
			else{
				$form_valid = true;
			}
		}		
		if($form_valid == true){
			$email_send_info = $email_from . $tscf_name . "\n" . $email_address. $tscf_email . "\n" . $email_message. "\n" . $tscf_message;
			$email_to = get_bloginfo('admin_email');
			$blog_title = get_bloginfo( 'name' );
			wp_mail($email_to, $message_from . $blog_title, $email_send_info);
			if ( $_POST['sent_copy'] == true){
				wp_mail($tscf_email, $copy_from . $blog_title, $email_send_info);
			}
			// Redirect browser to contact submission page
			$redirectaddress = ( empty( $_POST['_wp_http_referer'] ) ? site_url() : $_POST['_wp_http_referer'] );
			wp_redirect( add_query_arg( array('success_message'=> '1','required_missing'=>'0','captcha_wrong'=>'0','time_out'=>'0'), $redirectaddress ) );
			session_destroy();
			exit;
		}

	}
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );
function register_plugin_styles() {
	wp_register_style( 'total_simple_contact_form', plugins_url( 'tscf_style.css',__FILE__ ) );
	wp_enqueue_style( 'total_simple_contact_form' );
}

// Load the plugin's text domain
function tscf_load_domain() { 
	load_plugin_textdomain( 'total-simple-contact-form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action('plugins_loaded', 'tscf_load_domain');



?>