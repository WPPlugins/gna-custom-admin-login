<?php
if (!class_exists('GNA_Custom_AdminLogin_Settings_Menu')) {
	class GNA_Custom_AdminLogin_Settings_Menu extends GNA_Custom_AdminLogin_Admin_Menu {
		var $menu_page_slug = 'gna-cal-settings-menu';

		/* Specify all the tabs of this menu in the following array */
		var $menu_tabs;

		var $menu_tabs_handler = array(
			'tab1' => 'render_tab1', 
			'tab2' => 'render_tab2', 
			);

		public function __construct() {
			$this->render_menu_page();
		}

		public function set_menu_tabs() {
			$this->menu_tabs = array(
				'tab1' => __('General Settings', 'gna-custom-asdminlogin'),
				'tab2' => __('Message Settings', 'gna-custom-asdminlogin'),
			);
		}

		public function get_current_tab() {
			$tab_keys = array_keys($this->menu_tabs);
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $tab_keys[0];
			return $tab;
		}

		/*
		 * Renders our tabs of this menu as nav items
		 */
		public function render_menu_tabs() {
			$current_tab = $this->get_current_tab();

			echo '<h2 class="nav-tab-wrapper">';
			foreach ( $this->menu_tabs as $tab_key => $tab_caption ) 
			{
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->menu_page_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}

		/*
		 * The menu rendering goes here
		 */
		public function render_menu_page() {
			echo '<div class="wrap">';
			echo '<h2>'.__('Settings','gna-custom-asdmin-login').'</h2>';//Interface title
			$this->set_menu_tabs();
			$tab = $this->get_current_tab();
			$this->render_menu_tabs();
			?>
			<div id="poststuff"><div id="post-body">
			<?php 
				//$tab_keys = array_keys($this->menu_tabs);
				call_user_func(array(&$this, $this->menu_tabs_handler[$tab]));
			?>
			</div></div>
			</div><!-- end of wrap -->
			<?php
		}

		public function render_tab1() {
			global $g_customadminlogin;
			if ( isset($_POST['gna_cal_save_settings']) ) {
				$nonce = $_REQUEST['_wpnonce'];
				if ( !wp_verify_nonce($nonce, 'n_gna-cal-save-settings') ) {
					die("Nonce check failed on save settings!");
				}

				$g_customadminlogin->configs->set_value('g_adminlogin_logo', isset($_POST["g_adminlogin_logo"]) ? $_POST["g_adminlogin_logo"] : '');
				$g_customadminlogin->configs->set_value('g_adminlogin_custom_css', isset($_POST["g_adminlogin_custom_css"]) ? $_POST["g_adminlogin_custom_css"] : '');
				$g_customadminlogin->configs->save_config();
				$this->show_msg_settings_updated();
			}
			?>
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('GNA Custom Admin Login Page', 'gna-custom-asdmin-login'); ?></label></h3>
				<div class="inside">
					<p><?php _e('Thank you for using our GNA Custom Admin Login Page plugin.', 'gna-custom-asdmin-login'); ?></p>
				</div>
			</div> <!-- end postbox-->

			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('Admin Login Page Setting', 'gna-custom-asdmin-login'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-cal-save-settings'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Logo', 'gna-custom-admin-login')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<button class="upload_logo_button button">Upload/Select your Logo</button>
										<?php
											$adminlogin_logo = $g_customadminlogin->configs->get_value('g_adminlogin_logo');
										?>
										<div>
											<input class="g_adminlogin_logo large-text" id="g_adminlogin_logo" name="g_adminlogin_logo" type="text" value="<?php echo $adminlogin_logo; ?>" />
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Logo Preview', 'gna-custom-admin-login')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<div class="gna_logo_preview_wrapper">
											<?php
												if ( empty($adminlogin_logo) ) {
													$adminlogin_logo = admin_url().'images/wordpress-logo.svg';
												}
											?>
											<img style="max-width:100%;" class="gna_logo_preview" id="gna_logo_preview" src="<?php echo esc_url( $adminlogin_logo ); ?>" />
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Custom CSS Styles', 'gna-custom-admin-login')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<?php
											$adminlogin_custom_css = $g_customadminlogin->configs->get_value('g_adminlogin_custom_css');
										?>
										<div>
											<textarea class="g_adminlogin_custom_css large-text" id="g_adminlogin_custom_css" name="g_adminlogin_custom_css"><?php echo $adminlogin_custom_css; ?></textarea>
										</div>
										<div class="gna_grey_box">
											<p>
												Enter your custom css style for your logo. There is nothing to see at the beginning because the login page logo is styled by default.	
											</p>
											<p>
												You also may load an example css to start and customize it.
											</p>
										</div>
										<a class="load_example_css_button button" id="load_example_css_button">Load Example CSS</a>
									</div>
									<div class="gna_red_box">
										<p>
											Note: There is no need to input an background-image value here, it will be automatically added by default to the final output.
										</p>
										<p>
											The final CSS output is displayed below.
										</p>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Preview CSS Output', 'gna-custom-admin-login')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<div>
											<pre class="gna_green_box" id="gna_preview_css_output"><?php echo $adminlogin_custom_css; ?></pre>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<input type="submit" name="gna_cal_save_settings" value="<?php _e('Save Settings', 'gna-custom-asdmin-login')?>" class="button button-primary" />
					</form>
				</div>
			</div> <!-- end postbox-->
			<?php
		}
		
		public function render_tab2() {
			global $g_customadminlogin;
			if ( isset($_POST['gna_cal2_save_settings']) ) {
				$nonce = $_REQUEST['_wpnonce'];
				if ( !wp_verify_nonce($nonce, 'n_gna-cal2-save-settings') ) {
					die("Nonce check failed on save settings!");
				}

				$g_customadminlogin->configs->set_value('g_adminlogin_resetpw_msg', isset($_POST["g_adminlogin_resetpw_msg"]) ? $_POST["g_adminlogin_resetpw_msg"] : '');
				$g_customadminlogin->configs->save_config();
				$this->show_msg_settings_updated();
			}
			?>
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('GNA Custom Admin Login Page', 'gna-custom-asdmin-login'); ?></label></h3>
				<div class="inside">
					<p><?php _e('Thank you for using our GNA Custom Admin Login Page plugin.', 'gna-custom-asdmin-login'); ?></p>
				</div>
			</div> <!-- end postbox-->

			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('Rest Password Form', 'gna-custom-asdmin-login'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-cal2-save-settings'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Message', 'gna-custom-admin-login')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<?php
											$adminlogin_resetpw_msg = $g_customadminlogin->configs->get_value('g_adminlogin_resetpw_msg');
										?>
										<div>
											<textarea class="g_adminlogin_resetpw_msg large-text" id="g_adminlogin_resetpw_msg" name="g_adminlogin_resetpw_msg"><?php echo stripslashes_deep($adminlogin_resetpw_msg); ?></textarea>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<input type="submit" name="gna_cal2_save_settings" value="<?php _e('Save Settings', 'gna-custom-asdmin-login')?>" class="button button-primary" />
					</form>
				</div>
			</div> <!-- end postbox-->
			<?php
		}
	} //end class
}
