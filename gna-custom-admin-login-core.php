<?php
if (!class_exists('GNA_Custom_AdminLogin')) {
	class GNA_Custom_AdminLogin {
		var $plugin_url;
		var $admin_init;
		var $configs;

		public function init() {
			$class = __CLASS__;
			new $class;
		}

		public function __construct() {
			$this->load_configs();
			$this->define_constants();
			$this->define_variables();
			$this->includes();
			$this->loads();

			add_action('init', array(&$this, 'plugin_init'), 0);
			add_filter('plugin_row_meta', array(&$this, 'filter_plugin_meta'), 10, 2);
		}

		public function load_configs() {
			include_once('inc/gna-custom-admin-login-config.php');
			$this->configs = GNA_Custom_AdminLogin_Config::get_instance();
		}

		public function define_constants() {
			define('GNA_CUSTOM_ADMINLOGIN_VERSION', '0.9.3');

			define('GNA_CUSTOM_ADMINLOGIN_BASENAME', plugin_basename(__FILE__));
			define('GNA_CUSTOM_ADMINLOGIN_URL', $this->plugin_url());

			define('GNA_CUSTOM_ADMINLOGIN_MENU_SLUG_PREFIX', 'gna-cal-settings-menu');
		}

		public function define_variables() {
		}

		public function includes() {
			if(is_admin()) {
				include_once('admin/gna-custom-admin-login-admin-init.php');
			}
		}

		public function loads() {
			if(is_admin()){
				$this->admin_init = new GNA_Custom_Adminlogin_Admin_Init();
			}
		}

		public function plugin_init() {
			load_plugin_textdomain('gna-custom-admin-login', false, dirname(plugin_basename(__FILE__ )) . '/languages/');
			
			add_filter( 'login_message', array(&$this, 'gna_loginform_message') );
			add_filter( 'login_enqueue_scripts', array(&$this, 'gna_custom_login_logo') );
			add_filter( 'retrieve_password_message', array(&$this, 'gna_retrieve_password_message'), 10, 4 );
		}

		public function plugin_url() {
			if ($this->plugin_url) return $this->plugin_url;
			return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		}

		public function filter_plugin_meta($links, $file) {
			if( strpos( GNA_CUSTOM_ADMINLOGIN_BASENAME, str_replace('.php', '', $file) ) !== false ) { /* After other links */
				$links[] = '<a target="_blank" href="https://profiles.wordpress.org/chris_dev/" rel="external">' . __('Developer\'s Profile', 'gna-custom-adminlogin') . '</a>';
			}

			return $links;
		}

		public function install() {
		}

		public function uninstall() {
		}

		public function activate_handler() {
		}

		public function deactivate_handler() {
		}
		
		public function gna_loginform_message($message) {
			$action = $_REQUEST['action'];
			if( $action == 'rp' ) {
				global $g_customadminlogin;
				$adminlogin_resetpw_msg = $g_customadminlogin->configs->get_value('g_adminlogin_resetpw_msg');
				if ( isset($adminlogin_resetpw_msg) && !empty($adminlogin_resetpw_msg) ) {
					$message = stripslashes_deep($adminlogin_resetpw_msg);
				}

				return $message;
			} else {
				return $message;
			}
		}

		public function gna_custom_login_logo() {
			global $g_customadminlogin;
			$adminlogin_logo = $g_customadminlogin->configs->get_value('g_adminlogin_logo');
			$customCSS = $g_customadminlogin->configs->get_value('g_adminlogin_custom_css');
			if ( isset($adminlogin_logo) && !empty($adminlogin_logo) ) {
		?>
<style type="text/css">
	body.login div#login h1 a {
		background-image: url(<?php echo $adminlogin_logo; ?>);
	}
	
	<?php echo $customCSS; ?>
</style>
		<?php
			}
		}
		
		public function gna_retrieve_password_message($message, $key, $user_login, $user_data) {
			$message = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
			$message .= network_home_url( '/' ) . "\r\n\r\n";
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
			$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
			$message .= __('To reset your password, click the following link:') . "\r\n\r\n";
			$message .= '' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login) . '', 'login') . "\r\n";
			
			return $message;
		}
	}
}
$GLOBALS['g_customadminlogin'] = new GNA_Custom_AdminLogin();
