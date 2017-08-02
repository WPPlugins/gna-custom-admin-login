<?php
/* 
 * Inits the admin dashboard side of things.
 * Main admin file which loads all settings panels and sets up admin menus. 
 */
if (!class_exists('GNA_custom_AdminLogin_Admin_Init')) {
	class GNA_custom_AdminLogin_Admin_Init {
		var $main_menu_page;
		var $settings_menu;

		public function __construct() {
			//This class is only initialized if is_admin() is true
			$this->admin_includes();
			add_action('admin_menu', array(&$this, 'create_admin_menus'));

			if ( isset($_GET['page']) && (strpos($_GET['page'], GNA_CUSTOM_ADMINLOGIN_MENU_SLUG_PREFIX ) !== false) ) {
				add_action('admin_print_scripts', array(&$this, 'admin_menu_page_scripts'));
				add_action('admin_print_styles', array(&$this, 'admin_menu_page_styles'));
			}
		}

		public function admin_menu_page_scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('postbox');
			wp_enqueue_script('dashboard');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('gna-cal-script', GNA_CUSTOM_ADMINLOGIN_URL. '/assets/js/gna-custom-admin-login.js', array(), GNA_CUSTOM_ADMINLOGIN_VERSION);
		}

		function admin_menu_page_styles() {
			wp_enqueue_style('dashboard');
			wp_enqueue_style('thickbox');
			wp_enqueue_style('global');
			wp_enqueue_style('wp-admin');
			wp_enqueue_style('gna-custom-admin-login-admin-css', GNA_CUSTOM_ADMINLOGIN_URL. '/assets/css/gna-custom-admin-login.css');
		}

		public function admin_includes() {
			include_once('gna-custom-admin-login-admin-menu.php');
		}

		public function create_admin_menus() {
			$this->main_menu_page = add_menu_page( __('GNA Custom Admin Login', 'gna-custom-admin-login'), __('GNA Custom Admin Login', 'gna-custom-admin-login'), 'manage_options', 'gna-cal-settings-menu', array(&$this, 'handle_settings_menu_rendering'), GNA_CUSTOM_ADMINLOGIN_URL . '/assets/images/gna_20x20.png' );

			add_submenu_page('gna-cal-settings-menu', __('Settings', 'gna-custom-admin-login'),  __('Settings', 'gna-custom-admin-login'), 'manage_options', 'gna-cal-settings-menu', array(&$this, 'handle_settings_menu_rendering'));

			add_action( 'admin_init', array(&$this, 'register_gna_custom_adminlogin_settings') );
		}

		public function register_gna_custom_adminlogin_settings() {
			register_setting( 'gna-custom-admin-login-setting-group', 'g_customadminlogin_configs' );
		}

		public function handle_settings_menu_rendering() {
			include_once('gna-custom-admin-login-admin-settings-menu.php');
			$this->settings_menu = new GNA_Custom_AdminLogin_Settings_Menu();
		}
	}
}
