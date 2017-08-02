<?php
/*
Plugin Name: GNA Custom Admin Login Page
Version: 0.9.3
Plugin URI: http://wordpress.org/plugins/gna-custom-admin-login/
Author: Chris Mok
Author URI: http://webgna.com/
Description: Customize your admin login page: change the default logo and add your own logo. You also can add a custom text, load a css that is matched to your image's dimensions and edit the css for you needs.
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gna-custom-admin-login
*/

if(!defined('ABSPATH'))exit; //Exit if accessed directly

include_once('gna-custom-admin-login-core.php');

register_activation_hook(__FILE__, array('GNA_Custom_AdminLogin', 'activate_handler'));		//activation hook
register_deactivation_hook(__FILE__, array('GNA_Custom_AdminLogin', 'deactivate_handler'));	//deactivation hook
