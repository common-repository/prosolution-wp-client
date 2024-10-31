<?php
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
    error_reporting(0);
	
	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       https://www.prosolution.com
	 * @since      1.0.0
	 *
	 * @package    Prosolwpclient
	 * @subpackage Prosolwpclient/admin
	 */

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Prosolwpclient
	 * @subpackage Prosolwpclient/admin
	 * @author     ProSolution <helpdesk@prosolution.com>
	 */
	class CBXProSolWpClient_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 *
		 * @param      string $plugin_name The name of this plugin.
		 * @param      string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

			$this->settings_api = new CBXProSolWpClient_Settings_API( $this->plugin_name, $this->version );

		}

		/**
		 * Show Admin Pages
		 */
		public function proSol_adminPages() {
			//overview page
			$overview_page_hook = add_menu_page( esc_html__( 'Pro Solution Overview', 'prosolwpclient' ), esc_html__( 'Pro Solution', 'prosolwpclient' ), 'manage_options', 'prosolutionoverview', array(
				$this,
				'proSol_displayAdminOverviewMenuPage'
			), 'dashicons-chart-line', '6' );

			//add screen option save option
			add_action( "load-$overview_page_hook", array( $this, 'proSol_pswpTablelisting' ) );


			//add settings for this plugin
			$setting_page_hook = add_submenu_page(
				'prosolutionoverview', esc_html__( 'Setting', 'prosolwpclient' ), esc_html__( 'Setting', 'prosolwpclient' ),
				'manage_options', 'prosolwpclientsettings', array( $this, 'proSol_displayPluginAdminSettings' )
			);
			global $menu;
    		global $submenu;
    		
    		$searchPlugin = "Pro Solution"; // Use the unique plugin name
		    $replaceName = "ProSolution";

		    $menuItem = "";
		    foreach($menu as $key => $item){
		    	
		        if ( $item[0] === $searchPlugin ){
		            $menuItem = $key;
		        }
		    }
			$menu[$menuItem][0] = $replaceName;
		    
		    foreach($submenu['prosolutionoverview'] as $key => $items){
		    		
		    	if ( $items[0] === $searchPlugin ){
		    		
		            $menuItem = $key;
		        } 
		    }
		   	$submenu['prosolutionoverview'][$menuItem][0] = "Data Sync"; 			
		}

		/**
		 * Display settings
		 * @global type $wpdb
		 */
		public function proSol_displayPluginAdminSettings() {
			global $wpdb;global $prosol_prefix;

			include( 'templates/admin-settings-display.php' );
		}

		/**
		 * Set options for log listing result
		 *
		 * @param $new_status
		 * @param $option
		 * @param $value
		 *
		 * @return mixed
		 */
		public function proSol_pswp_table_results_per_page( $new_status, $option, $value ) {
			if ( 'proSol_pswp_table_results_per_page' == $option ) {
				return $value;
			}

			return $new_status;
		}

		/**
		 * Add screen option for table listing
		 */
		public function proSol_pswpTablelisting() {

			$table_view = isset( $_GET['table_view'] ) ? intval( $_GET['table_view'] ) : 0;
			if ( $table_view == 1 ) {
				$option = 'per_page';
				$args   = array(
					'label'   => esc_html__( 'Number of logs per page:', 'prosolwpclient' ),
					'default' => 50,
					'option'  => 'proSol_pswp_table_results_per_page'
				);
				add_screen_option( $option, $args );
			}
		}

		/**
		 * Print admin message for this plugin
		 */
		function proSol_adminNotices() {

			$messages = CBXProSolWpClient_Helper::proSol_getSessionData();

			if ( array( $messages ) && sizeof( $messages ) > 0 ) {
				foreach ( $messages as $message ) {

					$class   = 'notice notice-' . $message['class'];
					$message = $message['message'];

					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
				}
			}
		}


		/**
		 * Initialize setting
		 */
		public function proSol_adminInitCallback() {
			if ( ! session_id() ) {
				session_start();
			}

			$page_name  = isset( $_GET['page'] ) ? filter_var( $_GET['page'], FILTER_SANITIZE_STRING ) : '';
			$table_view = isset( $_GET['table_view'] ) ? intval( $_GET['table_view'] ) : 0;
			$table_name = isset( $_GET['table'] ) ? filter_var( $_GET['table'], FILTER_SANITIZE_STRING ) : '';
			$task       = isset( $_GET['task'] ) ? filter_var( $_GET['task'], FILTER_SANITIZE_STRING ) : '';

			$issite = CBXProSolWpClient_Helper::proSol_getSitecookie();
			$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
			$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);

			if ( $page_name == 'prosolutionoverview' && $task == 'syncall' && $is_api_setup ) {
				$this->proSol_allTableSync(); //after done redirect or die from ajax
			}

			
			$sync_enabled = $is_api_setup;

			if ( $page_name == 'prosolutionoverview' && $table_view = 1 ) {
				if ( $task == 'sync' ) {
					if ( $sync_enabled ) {
						

						$response_data = CBXProSolWpClient_TableHelper::proSol_apiActivity( $table_name );

						if ( is_object( $response_data ) ) {
							CBXProSolWpClient_TableHelper::proSol_allTablesInsertion( $table_name, $response_data );
							CBXProSolWpClient_Helper::proSol_setSessionData( esc_html__( 'Existing data truncated and new data synced successfully.' ), 'success' );
						} else {
							
							CBXProSolWpClient_Helper::proSol_setSessionData( $response_data, 'info' );
						}
					} else {
						CBXProSolWpClient_Helper::proSol_setSessionData( sprintf( __( 'Api key is missing, sync can not be done, <a href="%s">please update setting</a>', 'prosolwpclient' ), admin_url( 'admin.php?page=prosolwpclientsettings' ) ), 'info' );
					}

					wp_safe_redirect( 'admin.php?page=prosolutionoverview&table_view=1&table=' . $table_name );
					exit();

				} else {
					if ( $table_name == 'setting' ) {
						CBXProSolWpClient_TableHelper::proSol_allTablesInsertion( $table_name, array() );
					}
				}
			}				
			
			//set the settings
			$this->settings_api->proSol_set_sections( $this->proSol_getSettingsSections() );
			$this->settings_api->proSol_set_fields( $this->proSol_getSettingsFields() );

			//initialize settings
			$this->settings_api->proSol_admin_init();
		}

		/**
		 * sync all table
		 */
		public function proSol_allTableSync( $redirect = true ) {
			
			$issite = CBXProSolWpClient_Helper::proSol_getSitecookie();
			$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
			$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);
			
			$sync_enabled = $is_api_setup;
			
			if ( $sync_enabled ) {
				$header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig($issite);

				$all_tables_arr = CBXProSolWpClient_Helper::proSol_allTablesArr();

				if ( sizeof( $all_tables_arr ) > 0 && array_key_exists( 'setting', $all_tables_arr ) ) {
					unset( $all_tables_arr['setting'] );
				}

				$done_table_arr   = array();
				$failed_table_arr = array();
				foreach ( $all_tables_arr as $table_key => $table_name ) {
					$response_data = CBXProSolWpClient_TableHelper::proSol_apiActivity( $table_key );
					if ( is_object( $response_data ) ) {
						CBXProSolWpClient_TableHelper::proSol_allTablesInsertion( $table_key, $response_data);
						$done_table_arr[] = $table_name;
					} else { 
						if($table_name !='Setting' && $table_name !='Jobstamp'){ // Project 1440, Jobstamp should not directly call in syncAll
							$failed_table_arr[] = $table_name;
							CBXProSolWpClient_Helper::proSol_setSessionData( $response_data, 'error' );	
							break;
						}
					}
				}

				if ( sizeof( $done_table_arr ) == sizeof( $all_tables_arr ) ) {
					CBXProSolWpClient_Helper::proSol_setSessionData( esc_html__( 'All tables synced successfully.', 'prosolwpclient' ), 'success' );
				}
				if ( sizeof( $done_table_arr ) > 0 ) {
					CBXProSolWpClient_Helper::proSol_setSessionData( sprintf( __( '%s tables synced successfully', 'prosolwpclient' ), implode( ',', $done_table_arr ) ), 'success' );
				}

				if ( sizeof( $failed_table_arr ) > 0 ) {
					
					CBXProSolWpClient_Helper::proSol_setSessionData( sprintf( __( '%s tables synced failed.', 'prosolwpclient' ), implode( ',', $failed_table_arr ) ), 'error' );
				}
			} else {
				CBXProSolWpClient_Helper::proSol_setSessionData( sprintf( __( 'Api key is missing, sync can not be done, <a href="%s">please update setting</a>', 'prosolwpclient' ), admin_url( 'admin.php?page=prosolwpclientsettings' ) ), 'info' );
			}

			if ( $redirect ) {
				wp_safe_redirect( 'admin.php?page=prosolutionoverview' );
				exit();
			} else {
				echo esc_html__( 'Sync from cron done', 'prosolwpclient' );
				if ( isset( $_SESSION['prosolwpclient_notices'] ) ) {
					unset( $_SESSION['prosolwpclient_notices'] );
				}
			}


		}

		/**
		 * Ajax table sync
		 */
		public function proSol_ajaxTablesync() {
			
			//security check
			check_ajax_referer( 'prosolwpclient', 'security' );

			$table_name = isset( $_POST['table'] ) ? filter_var( $_POST['table'], FILTER_SANITIZE_STRING ) : '';
			$synctype = isset($_POST['synctype']) ? filter_var( $_POST['synctype'], FILTER_SANITIZE_STRING ) : '';	

			$issite = CBXProSolWpClient_Helper::proSol_getSitecookie();
			$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
			$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);
			
			$sync_enabled = $is_api_setup;
			$output       = array();
			
			if ( $sync_enabled ) {

				$response_data = CBXProSolWpClient_TableHelper::proSol_apiActivity( $table_name, $synctype );
				 
				if ( is_object( $response_data ) ) {
					CBXProSolWpClient_TableHelper::proSol_allTablesInsertion( $table_name, $response_data);
					$output['error']    = 0;
					$output['message']  = esc_html__( 'Existing data truncated and new data synced successfully.', 'prosolwpclient' );
					$output['synctime'] = current_time( 'mysql' );
				} else {
					$output['error']   = 1;
					$output['message'] = $response_data;
				}
			} else {
				$output['error']   = 1;
				$output['message'] = esc_html__( 'Sorry, sync is not possible as api key is missing in setting', 'prosolwpclient' );
			}
			
			echo wp_json_encode( $output );
			die();
		}

		/**
		 * Ajax clear log
		 */
		public function proSol_ajaxClearlog() {
			//security check
			check_ajax_referer( 'prosolwpclient', 'security' );

			$clear  = isset( $_POST['cleartype'] ) ? intval( $_POST['cleartype'] ) : 1;
			$output = CBXProSolWpClient_Helper::proSol_clearLog( $clear );
			echo wp_json_encode( $output );
			die();
		}
		
		/* used in proSol_url_validate() */
		function proSol_checkDataAPI($urlval,$userval,$passval) {
			if ($urlval=='' || $userval=='' || $passval=='') {
				return false;
			}
			return true;
		}

		/* used in proSol_url_validate() */
		function proSol_checkHeaderInfo( $api_url, $api_user, $api_pass ) {
			if ( $api_user != '' && $api_pass != '' && $api_url != '' ) {
				$stamp = gmDate( "Y-m-d H:i:s O" );

				// SHA-Hash erstellen aus Zeitstempel und dem Access-Key
				$signature = hash( 'sha256', $stamp . '::' . $api_pass );

				$header_info = array(
					'prosol-date'   => $stamp,
					'Authorization' => 'WorkExpertAPI ' . $api_user . ':' . $signature,
				);
			}
			return $header_info;
		}	

		/** 
		*Ajax URL validate
		*/
		public function proSol_url_validate() { 
			$response_data = new stdClass();
			$urlval = isset( $_POST['urlval'] ) ? filter_var( $_POST['urlval'], FILTER_SANITIZE_STRING ) : '';
			$userval = isset( $_POST['userval'] ) ? filter_var( $_POST['userval'], FILTER_SANITIZE_STRING ) : '';
			$passval = isset( $_POST['passval'] ) ? filter_var( $_POST['passval'], FILTER_SANITIZE_STRING ) : '';

			$is_api_setup = CBXProSolWpClient_Admin::proSol_checkDataAPI($urlval,$userval,$passval);
			$header_info = CBXProSolWpClient_Admin::proSol_checkHeaderInfo($urlval,$userval,$passval);
			
			$output['error']    = 1;
			$output['message']  = esc_html__( 'URL is invalid', 'prosolwpclient' );
			if ( is_array( $header_info ) && sizeof( $header_info ) > 0 && $is_api_setup ) {
				$response_data = wp_remote_get( $urlval . '/go/api/system/list/maritalstatus', array('headers'=>$header_info));
			}
			
			if ( $is_api_setup && !is_wp_error( $response_data ) ) {
				if( is_object($response_data['body']) ){
					$check_body=json_decode( $response_data['body'] ) -> data ; // key object is case-sensitive
					if ( is_object($check_body) ) {
						$output['error']    = 0;
						$output['message']  = esc_html__( 'URL is valid', 'prosolwpclient' );	
					} else { //header is correct but return empty data
						$output['error']    = 1;
						$output['message']  = esc_html__( 'response timeout', 'prosolwpclient' );
					}
				} else { //header is correct
					$check_err=json_decode( $response_data['body'] ) -> ERROR ; // key object is case-sensitive
					$check_err2=json_decode( $response_data['response']['code']) ; // key object is case-sensitive				
					
					if ($check_err || $check_err2 != "200"){
						$output['error']    = 1;
						$output['message']  = esc_html__( 'response is invalid', 'prosolwpclient' );
					} else {
						$output['error']    = 0;
						$output['message']  = esc_html__( 'URL is valid', 'prosolwpclient' );	
					}
					
				}
			}  else {
				$output['error']    = 2;
				$output['message']  = esc_html__( 'failed to connect URL. Please try again in a few minutes', 'prosolwpclient' );	
			}
			
			echo wp_json_encode( $output );
			die();
		}	

		/**
		 * Global Setting Sections and titles
		 *
		 * @return type
		 */
		public function proSol_getSettingsSections() {
			$settings_sections = array(

				array(
					'id'    => 'prosolwpclient_api_config',
					'title' => esc_html__( 'API Config', 'prosolwpclient' )
				),
				array(
					'id'    => 'prosolwpclient_frontend',
					'title' => esc_html__( 'General Settings', 'prosolwpclient' )
				),
				array(
					'id'    => 'prosolwpclient_tools',
					'title' => esc_html__( 'Tools', 'prosolwpclient' )
				),
				array(
					'id'    => 'prosolwpclient_languages',
					'title' => esc_html__( 'Languages', 'prosolwpclient' )
				),
				array(
					'id'    => 'prosolwpclient_applicationform',
					'title' => esc_html__( 'Application Form', 'prosolwpclient' )
				),
				array(
					'id'    => 'prosolwpclient_privacypolicy',
					'title' => esc_html__( 'Privacy Policy', 'prosolwpclient' )
				),
				array(
					'id'    => 'prosolwpclient_designtemplate',
					'title' => esc_html__( 'Design Template', 'prosolwpclient' )
				),	
				array(
					'id'    => 'prosolwpclient_additionalsite',
					'title' => esc_html__( 'Additional Site', 'prosolwpclient' )
				)			
			);

			return apply_filters( 'prosolwpclient_setting_sections', $settings_sections );
		}

		/**
		 * Global Setting Fields
		 *
		 * @return array
		 */
		public function proSol_getSettingsFields() {
			global $wpdb;global $prosol_prefix;

			$table_names = CBXProSolWpClient_Helper::proSol_allTablesArr();
			$table_html  = '<p id="prosolwpclient_plg_gfig_info"><strong>' . esc_html__( 'Following database tables will be reset/deleted.', 'prosolwpclient' ) . '</strong></p>';

			$table_counter = 1;

			foreach ( $table_names as $key => $value ) {
				$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $prosol_prefix . $key . ' - (<code>' . $value . '</code>)</p>';
				$table_counter ++;
			}

			$table_html .= '<p><strong>' . esc_html__( 'Following option values created by this plugin will be deleted from wordpress option table', 'prosolwpclient' ) . '</strong></p>';


			$option_values = CBXProSolWpClient_Helper::proSol_getAllOptionNames();
			$table_counter = 1;
			foreach ( $option_values as $key => $value ) {
				$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $value['option_name'] . ' - ' . $value['option_id'] . ' - (<code style="overflow-wrap: break-word; word-break: break-all;">' . $value['option_value'] . '</code>)</p>';

				$table_counter ++;
			}


			$pages         = get_pages();

			$pages_options = array();
			if ( $pages ) {
				foreach ( $pages as $page ) {

					$pages_options[ $page->ID ] = $page->post_title;

				}
			}

			$issite = $_COOKIE['selsite']; 
			$selsite= CBXProSolWpClient_Helper::proSol_getSitecookie();
			$urlsite='';
			if(isset($_COOKIE['selsite'])){
				if($_COOKIE['selsite']!=0){
					$addsite= get_option('prosolwpclient_additionalsite');
					$urlsite= 'addsite'.$issite.'_urlid';
					$urlsite='?siteid='.$addsite['addsite'.$issite.'_urlid'];
				} else{
					$issite=0;
				}
			}else{
				$issite=0;
			}			

			$page_view_link   = '#';
			$page_edit_link   = '#';
			$frontend_setting = get_option( 'prosolwpclient_frontend' ); 
			if ( $frontend_setting !== false && isset( $frontend_setting[ $selsite.'frontend_pageid'] ) && intval( $frontend_setting[ $selsite.'frontend_pageid'] ) > 0 ) {
				$page_view_link = get_permalink( $frontend_setting[ $selsite.'frontend_pageid'] ).$urlsite;
				$page_edit_link = get_edit_post_link( $frontend_setting[ $selsite.'frontend_pageid'] );
			} else{
				$page_view_link = get_permalink( $pages[0]->ID ).$urlsite;
				$page_edit_link = get_edit_post_link( $pages[0]->ID ); 
			}
			$sync_key = $this->settings_api->proSol_get_option( 'sync_key', 'prosolwpclient_tools', 'na7wg36kqx42huc5' );

			$cron_url = '';
			if ( $sync_key != '' ) {
				$cron_url = add_query_arg( 'prosolwpclientsync', $sync_key, home_url() );
			} else {
				$cron_url = esc_html__( 'Please set cron secret key first', 'prosolwpclient' );
			}
			
			global $wpdb;global $prosol_prefix;
			$table_ps_country = $prosol_prefix . 'country';
			$country_arr      = $wpdb->get_results( "SELECT * FROM $table_ps_country as country WHERE site_id=$issite ORDER BY country.name ASC", ARRAY_A );
			
			$default_nation_arr = array();
			foreach ( $country_arr as $index => $country_info ) {
				$default_nation_arr[ $country_info['countryCode'] ] = $country_info['name'];
			}
			
			$table_ps_office = $prosol_prefix . 'office';
			$office_arr      = $wpdb->get_results( "SELECT * FROM $table_ps_office as office WHERE site_id=$issite ORDER BY office.officeId ASC", ARRAY_A );
			$default_office_arr = array();

			$isrec = $frontend_setting[ $selsite.'enable_recruitment'];
			if($isrec != 'on'){
				$default_office_arr[ "" ] = " - ";
			}
			foreach ( $office_arr as $index => $office_info ) {
				$default_office_arr[ $office_info['officeId'] ] = $office_info['name'];
			}

			$settings_builtin_fields =
				array(
					'prosolwpclient_api_config' => array(
						'api_url'  => array(
							'name'              => 'api_url',
							'label'             => esc_html__( 'API Domain Url', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Customer domain url. e.g. https://customer.workexpert.online', 'prosolwpclient' ),
							'type'              => 'url',
							'default'           => '',
							'desc_tip'          => true,
							'label_selector'    => false,
							'value_selector'    => false,
							'required'          => true,
							'max'               => 256,
							'sanitize_callback' => array( 'CBXProSolWpClient_Settings_API', 'sanitize_text_field' ),
						),
						'api_user' => array(
							'name'              => 'api_user',
							'label'             => esc_html__( 'API User', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Please put API User here, required field.', 'prosolwpclient' ),
							'type'              => 'text',
							'default'           => '',
							'desc_tip'          => true,
							'label_selector'    => false,
							'value_selector'    => false,
							'required'          => true,
							'max'               => 30,
							'sanitize_callback' => array( 'CBXProSolWpClient_Settings_API', 'sanitize_text_field' ),
						),
						'api_pass' => array(
							'name'              => 'api_pass',
							'label'             => esc_html__( 'API Pass', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Please put API Pass here, required field.', 'prosolwpclient' ),
							'type'              => 'password',
							'default'           => '',
							'desc_tip'          => true,
							'label_selector'    => false,
							'value_selector'    => false,
							'required'          => true,
							'max'               => 500,
							'sanitize_callback' => array( 'CBXProSolWpClient_Settings_API', 'sanitize_text_field' ),
						),
						'btn_url_check'  => array(
							'name'              => 'btn_url_check',
							'label'             => esc_html__( 'Check connection', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Please fill API user and pass before pressed the button', 'prosolwpclient' ),
							'type'              => 'btn_url_check',
							'default'           => '',
							'desc_tip'          => true,
							'label_selector'    => false,
							'value_selector'    => false,
							'required'          => false,
							'max'               => 256,
							'sanitize_callback' => array( 'CBXProSolWpClient_Settings_API', 'sanitize_text_field' ),
						)
					),

					'prosolwpclient_tools' => array(
						'sync_key'             => array(
							'name'              => 'sync_key',
							'label'             => esc_html__( 'Sync Key', 'prosolwpclient' ),
							'desc'              => sprintf( __( 'Default key <code>%s</code>', 'prosolwpclient' ), 'na7wg36kqx42huc5' ) . sprintf( __( 'Cron url is <code>%s</code>', 'prosolwpclient' ), $cron_url ),
							'type'              => 'text',
							'default'           => 'na7wg36kqx42huc5',
							'desc_tip'          => true,
							'label_selector'    => false,
							'value_selector'    => false,
							'required'          => true,
							'max'               => 80,
							'sanitize_callback' => array( 'CBXProSolWpClient_Settings_API', 'sanitize_text_field' ),
						),
						'delete_global_config' => array(
							'name'     => 'delete_global_config',
							'label'    => esc_html__( 'On Uninstall delete plugin data', 'prosolwpclient' ),
							'desc'     => '<p>Yes.</p><p>' . __( 'Delete Global Config data and custom table created by this plugin on uninstall.', 'prosolwpclient' ) . '</p>' . '<p>' . __( '<strong>Please note that this process can not be undone and it is recommended to keep full database backup before doing this.</strong>', 'prosolwpclient' ) . '</p>' . $table_html,
							'type'     => 'html',
							'default'  => 'yes',
							/* 'options'  => array(
							 	'yes' => esc_html__( 'Yes', 'prosolwpclient' ),
							 	'no'  => esc_html__( 'No', 'prosolwpclient' ),
							 ), in case change back to radio type */
							'desc_tip' => true
						)
					),

					'prosolwpclient_frontend' => array(
						'frontend_pageid' => array(
							'name'    => 'frontend_pageid',
							'label'   => esc_html__( 'ProSolution Frontend Page', 'prosolwpclient' ),
							'desc'    => sprintf( __( 'ProSolution Frontend page, shortcode <code>[prosolfrontend type="search"]</code> added automatically. <a class="button button-primary" href="%s" target="_blank">Visit Page</a> <a class="button button-default" href="%s" target="_blank">Edit Page</a>', 'prosolwpclient' ), $page_view_link, $page_edit_link ),
							'type'    => 'select',
							'default' => 0,
							'options' => $pages_options
						),
						'default_nation'  => array(
							'name'    => 'default_nation',
							'label'   => esc_html__( 'Default Nation', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Select the nation to be used as Country, Nationality and Country of Birth.', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '',
							'required'=> true,
							'options' => $default_nation_arr
						),
						'default_office'  => array(
							'name'    => 'default_office',
							'label'   => esc_html__( 'Default Office', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Select the Office to be used as default.', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '',
							'options' => $default_office_arr
						),
						'enable_recruitment' => array(
							'name'              => 'enable_recruitment',
							'label'             => esc_html__( 'Enable Recruitment', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Enable feature Recruitment', 'prosolwpclient' ),
							'desc_tip'          => true,
							'type'    			=> 'chkbox_recruitment',
							'required' 			=> false,
							'default' 			=> 'on' 
						),
						'client_list' => array(
							'name'              => 'client_list',
							'label'             => esc_html__( 'Client List', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Insert list of clients from which webjobs should be shown in the frontend (max. 5 client IDs, comma-seperated)', 'prosolwpclient' ),
							'desc_tip'          => true,
							'type'    			=> 'text',
							'required' 			=> false,
							'default' 			=> '' 
						),
					),
					
					'prosolwpclient_languages' => array(
						'default_language'  => array(
							'name'    => 'default_language',
							'label'   => esc_html__( 'Language', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Select the language as default.', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '',
							'required' => true,
							'options'  => array(
								'en' => esc_html__( 'English', 'prosolwpclient' ),
								'de'  => esc_html__( 'German', 'prosolwpclient' ),
								'es'  => esc_html__( 'Spanish', 'prosolwpclient' )
							)
						)
					),
					
					'prosolwpclient_applicationform' => array(
						'one_pager'  => array(
							'name'    => 'one_pager',
							'label'   => esc_html__( 'Application form steps', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Change the step view of the application form', 'prosolwpclient' ),
							'type'    => 'select',
							'required' => true,
							'default' => '1',
							'options'  => array(
								'0' => esc_html__( 'One Pager', 'prosolwpclient' ),
								'1'  => esc_html__( 'Step-by-Step', 'prosolwpclient' )
							)
						),

						'step_label'  => array(
							'name'    => 'step_label',
							'label'   => esc_html__( 'Step Name', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Change Step Label', 'prosolwpclient' ),
							'type'    => 'text',
							'required' => true,
							'default' => esc_html__( 'Step', 'prosolwpclient' )
						),

						'personaldata'  => array(
							'name'    => 'personaldata',
							'label'   => esc_html__( '1st Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Personal Data' , 'prosolwpclient' )	
						),
						'personaldata_act'  => array('name'=>'personaldata_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_title_act'  => array('name'=>'personaldata_title_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_title_man'  => array('name'=>'personaldata_title_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_federal_act'  => array('name'=>'personaldata_federal_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_federal_man'  => array('name'=>'personaldata_federal_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_phone_act'  => array('name'=>'personaldata_phone_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_phone_man'  => array('name'=>'personaldata_phone_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_mobile_act'  => array('name'=>'personaldata_mobile_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_mobile_man'  => array('name'=>'personaldata_mobile_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_email_act'  => array('name'=>'personaldata_email_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_email_man'  => array('name'=>'personaldata_email_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_nationality_act'  => array('name'=>'personaldata_nationality_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_nationality_man'  => array('name'=>'personaldata_nationality_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_marital_act'  => array('name'=>'personaldata_marital_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_marital_man'  => array('name'=>'personaldata_marital_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_gender_act'  => array('name'=>'personaldata_gender_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_gender_man'  => array('name'=>'personaldata_gender_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_diverse_act'  => array('name'=>'personaldata_diverse_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_expectedsalary_act'  => array('name'=>'personaldata_expectedsalary_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_expectedsalary_man'  => array('name'=>'personaldata_expectedsalary_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_countrybirth_act'  => array('name'=>'personaldata_countrybirth_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_countrybirth_man'  => array('name'=>'personaldata_countrybirth_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_availfrom_act'  => array('name'=>'personaldata_availfrom_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_availfrom_man'  => array('name'=>'personaldata_availfrom_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_notes_act'  => array('name'=>'personaldata_notes_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'personaldata_notes_man'  => array('name'=>'personaldata_notes_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText1_act'  => array('name'=>'personaldata_Title_profileText1_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText1_man'  => array('name'=>'personaldata_Title_profileText1_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText2_act'  => array('name'=>'personaldata_Title_profileText2_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText2_man'  => array('name'=>'personaldata_Title_profileText2_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText3_man'  => array('name'=>'personaldata_Title_profileText3_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText3_act'  => array('name'=>'personaldata_Title_profileText3_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText4_man'  => array('name'=>'personaldata_Title_profileText4_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText4_act'  => array('name'=>'personaldata_Title_profileText4_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText5_man'  => array('name'=>'personaldata_Title_profileText5_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText5_act'  => array('name'=>'personaldata_Title_profileText5_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText6_man'  => array('name'=>'personaldata_Title_profileText6_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText6_act'  => array('name'=>'personaldata_Title_profileText6_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText7_man'  => array('name'=>'personaldata_Title_profileText7_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText7_act'  => array('name'=>'personaldata_Title_profileText7_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText8_man'  => array('name'=>'personaldata_Title_profileText8_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText8_act'  => array('name'=>'personaldata_Title_profileText8_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText9_man'  => array('name'=>'personaldata_Title_profileText9_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText9_act'  => array('name'=>'personaldata_Title_profileText9_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText10_man'  => array('name'=>'personaldata_Title_profileText10_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileText10_act'  => array('name'=>'personaldata_Title_profileText10_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileOption1_man'  => array('name'=>'personaldata_Title_profileOption1_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileOption1_act'  => array('name'=>'personaldata_Title_profileOption1_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileOption2_man'  => array('name'=>'personaldata_Title_profileOption2_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileOption2_act'  => array('name'=>'personaldata_Title_profileOption2_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileOption3_man'  => array('name'=>'personaldata_Title_profileOption3_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileOption3_act'  => array('name'=>'personaldata_Title_profileOption3_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileOption4_man'  => array('name'=>'personaldata_Title_profileOption4_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_Title_profileOption4_act'  => array('name'=>'personaldata_Title_profileOption4_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_max_distance_man'  => array('name'=>'personaldata_max_distance_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_max_distance_act'  => array('name'=>'personaldata_max_distance_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_empgroup_ID_man'  => array('name'=>'personaldata_empgroup_ID_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_empgroup_ID_act'  => array('name'=>'personaldata_empgroup_ID_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_tagid_man'  => array('name'=>'personaldata_tagid_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'personaldata_tagid_act'  => array('name'=>'personaldata_tagid_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						
						'education'  => array(
							'name'    => 'education',
							'label'   => esc_html__( '2nd Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Education' , 'prosolwpclient' )
						),
						'education_act'  => array('name'=>'education_act','type'=>'hidden','default'=>'1','class'=>'hidden'),
						'education_postcode_act'  => array('name'=>'education_postcode_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'education_postcode_man'  => array('name'=>'education_postcode_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'education_country_act'  => array('name'=>'education_country_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'education_country_man'  => array('name'=>'education_country_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'education_level_act'  => array('name'=>'education_level_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'education_level_man'  => array('name'=>'education_level_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'education_description_act'  => array('name'=>'education_description_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'education_description_man'  => array('name'=>'education_description_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						
						'workexperience'  => array(
							'name'    => 'workexperience',
							'label'   => esc_html__( '3rd Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' =>  esc_html__( 'Work Experience' , 'prosolwpclient' )
						),
						'workexperience_act'  => array('name'=>'workexperience_act','type'=>'hidden','default'=>'1','class'=>'hidden'),
						'workexperience_job_act'  => array('name'=>'workexperience_job_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'workexperience_job_man'  => array('name'=>'workexperience_job_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'workexperience_gendesc_act'  => array('name'=>'workexperience_gendesc_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'workexperience_gendesc_man'  => array('name'=>'workexperience_gendesc_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'workexperience_company_act'  => array('name'=>'workexperience_company_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'workexperience_company_man'  => array('name'=>'workexperience_company_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'workexperience_postcode_act'  => array('name'=>'workexperience_postcode_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'workexperience_postcode_man'  => array('name'=>'workexperience_postcode_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'workexperience_country_act'  => array('name'=>'workexperience_country_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'workexperience_country_man'  => array('name'=>'workexperience_country_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'workexperience_federal_act'  => array('name'=>'workexperience_federal_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'workexperience_federal_man'  => array('name'=>'workexperience_federal_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'workexperience_experience_act'  => array('name'=>'workexperience_experience_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'workexperience_experience_man'  => array('name'=>'workexperience_experience_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'workexperience_contract_act'  => array('name'=>'workexperience_contract_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'workexperience_contract_man'  => array('name'=>'workexperience_contract_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'workexperience_employment_act'  => array('name'=>'workexperience_employment_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'workexperience_employment_man'  => array('name'=>'workexperience_employment_man','type'=>'hidden','default' =>'0','class'=>'hidden'),
						
						'expertise'  => array(
							'name'    => 'expertise',
							'label'   => esc_html__( '4th Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Expertise' , 'prosolwpclient' )
						),
						'expertise_act'  => array('name'=>'expertise_act','type'=>'hidden','default'=>'1'),
						'expertise_skillgroup_sort'  => array('name'=>'expertise_skillgroup_sort','type'=>'hidden','default'=>'1','class'=>'hidden'),
						'expertise_furtherskill_act'  => array('name'=>'expertise_furtherskill_act','type'=>'hidden','default'=>'1','class'=>'hidden'),
						
						'sidedishes'  => array(
							'name'    => 'sidedishes',
							'label'   => esc_html__( '5th Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Side Dishes' , 'prosolwpclient' )
						),
						'sidedishes_act'  => array('name'=>'sidedishes_act','type'=>'hidden','default'=>'1'),
						'sidedishes_man'  => array('name'=>'sidedishes_man','type'=>'hidden','default'=>'1'),
												
						'others'  => array(
							'name'    => 'others',
							'label'   => esc_html__( '6th Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Others' , 'prosolwpclient' )
						),
						'others_act'  => array('name'=>'others_act','type'=>'hidden','default'=>'1','class'=>'hidden'),
						'others_source_act'  => array('name'=>'others_source_act','type'=>'hidden','default'=>'1','class'=>'hidden'),
						'others_source_man'  => array('name'=>'others_source_man','type'=>'hidden','default'=>'0','class'=>'hidden'),
						'others_apply_act'  => array('name'=>'others_apply_act','type'=>'hidden','default'=>'1','class'=>'hidden'),
						'others_apply_man'  => array('name'=>'others_apply_man','type'=>'hidden','default'=>'0','class'=>'hidden'),
						'others_message_act'  => array('name'=>'others_message_act','type'=>'hidden','default'=>'1','class'=>'hidden'),
						'others_message_man'  => array('name'=>'others_message_man','type'=>'hidden','default'=>'0','class'=>'hidden')
					),

					'prosolwpclient_privacypolicy' => array(
						'policy1'  => array(
							'name'    => 'policy1',
							'label'   => esc_html__( 'First Policy', 'prosolwpclient' ),
							'desc'    => esc_html__( 'This checkbox will be mandatory in the frontend.', 'prosolwpclient' ),
							'type'    => 'textarea_policy',
							'default' => esc_html__( 'Details are correct. I would like to continue the application process!', 'prosolwpclient' ) 	
						),
						'policy1_act'  => array('name'=>'policy1_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						
						'policy2'  => array(
							'name'    => 'policy2',
							'label'   => esc_html__( 'Second Policy', 'prosolwpclient' ),
							'type'    => 'textarea_policy'	
						),
						'policy2_act'  => array('name'=>'policy2_act','type'=>'hidden','default' =>'1','class'=>'hidden'),

						'policy3'  => array(
							'name'    => 'policy3',
							'label'   => esc_html__( 'Third Policy', 'prosolwpclient' ),
							'type'    => 'textarea_policy'	
						),
						'policy3_act'  => array('name'=>'policy3_act','type'=>'hidden','default' =>'1','class'=>'hidden'),

						'policy4'  => array(
							'name'    => 'policy4',
							'label'   => esc_html__( 'Fourth Policy', 'prosolwpclient' ),
							'type'    => 'textarea_policy'	
						),
						'policy4_act'  => array('name'=>'policy4_act','type'=>'hidden','default' =>'1','class'=>'hidden'),

						'policy5'  => array(
							'name'    => 'policy5',
							'label'   => esc_html__( 'Fifth Policy', 'prosolwpclient' ),
							'type'    => 'textarea_policy'	
						),
						'policy5_act'  => array('name'=>'policy5_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						
						'policy6'  => array(
							'name'    => 'policy6',
							'label'   => esc_html__( 'ppvc date', 'prosolwpclient' ),
							'desc'    => esc_html__( 'This checkbox will be mandatory in the frontend.', 'prosolwpclient' ),
							'type'    => 'textarea_policy',
							'default' => esc_html__( 'I give my consent to the saving, examination and electronic processing of my data based on data protection laws.', 'prosolwpclient' ) 	
						),
						'policy6_act'  => array('name'=>'policy6_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
					) ,
					'prosolwpclient_designtemplate' => array(
						'destemplate'  => array(
							'name'    => 'destemplate',
							'label'   => esc_html__( 'Select template', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '1',
							'options'  => array(
								'1'  => esc_html__( 'No Template', 'prosolwpclient' ),
								'0' => esc_html__( 'ProSolution Template', 'prosolwpclient' )
							)	
						),
												
						'desfont'  => array(
							'name'    => 'desfont',
							'label'   => esc_html__( 'Font', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '0',
							'options'  => array(
								'0' => esc_html__( 'default Font', 'prosolwpclient' ),
								'1' => esc_html__( 'Georgia', 'prosolwpclient' ),
								'2' => esc_html__( 'Palatino', 'prosolwpclient' ),
								'3' => esc_html__( 'Times New Roman', 'prosolwpclient' ),
								'4' => esc_html__( 'Arial', 'prosolwpclient' ),
								'5' => esc_html__( 'Arial Black', 'prosolwpclient' ),
								'6' => esc_html__( 'Comic Sans MS', 'prosolwpclient' ),
								'7' => esc_html__( 'Impact', 'prosolwpclient' ),
								'8' => esc_html__( 'Lucida Sans Unicode', 'prosolwpclient' ),
								'9' => esc_html__( 'Tahoma', 'prosolwpclient' ),
								'10' => esc_html__( 'Trebuchet', 'prosolwpclient' ),
								'11' => esc_html__( 'Verdana', 'prosolwpclient' ),
								'12' => esc_html__( 'Courier New', 'prosolwpclient' ),
								'13' => esc_html__( 'Lucida Console', 'prosolwpclient' )
							)	
						),

						'desmaincolor'  => array(
							'name'    => 'desmaincolor',
							'label'   => esc_html__( 'Main color', 'prosolwpclient' ),
							'type'    => 'color'	
						),

						'deslogo'  => array(
							'name'    => 'deslogo',
							'label'   => esc_html__( 'Logo', 'prosolwpclient' ),
							'type'    => 'file_with_thumbnail'	
						),

						'desperpage'  => array(
							'name'    => 'desperpage',
							'label'   => esc_html__( 'Jobs per page', 'prosolwpclient' ),
							'type'    => 'number',
							'default' => '10',
							'min'     => '1',
							'max'	  => '50',
							'step'    => '1'
						),
						
						'dessearchfrontend' => array(
							'name'    => 'dessearchfrontend',
							'label'   => esc_html__( 'Frontend jobsearch', 'prosolwpclient' ),
							'type'    => 'html'
						),

						'dessearchheading'  => array(
							'name'    => 'dessearchheading',
							'label'   => esc_html__( 'Heading', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'JOBBORSE', 'prosolwpclient' ) 	
						),	

						'dessearchjobtitle'  => array(
							'name'    => 'dessearchjobtitle',
							'label'   => esc_html__( 'JobTitle', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'What?', 'prosolwpclient' ) 	
						),	

						'dessearchplace'  => array(
							'name'    => 'dessearchplace',
							'label'   => esc_html__( 'Place', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'Where?', 'prosolwpclient' ) 	
						),	

						'dessearchsearchbtn'  => array(
							'name'    => 'dessearchsearchbtn',
							'label'   => esc_html__( 'Search button', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'Search', 'prosolwpclient' ) 	
						),	

						'dessearchjobidbtn'  => array(
							'name'    => 'dessearchjobidbtn',
							'label'   => esc_html__( 'Unsolicited application', 'prosolwpclient' ),
							'type'    => 'text_des_template',
							'default' => esc_html__( 'Unsolicited application', 'prosolwpclient' ) 	
						),	
						'dessearchjobidbtn_act'  => array('name'=>'dessearchjobidbtn_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						
						'desresultfrontend' => array(
							'name'    => 'desresultfrontend',
							'label'   => esc_html__( 'Frontend joblist', 'prosolwpclient' ),
							'type'    => 'text_des_template'
						),
						'desresultzipcode_act'  => array('name'=>'desresultzipcode_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desresultplaceofwork_act'  => array('name'=>'desresultplaceofwork_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desresultworktime_act'  => array('name'=>'desresultworktime_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desresultagentname_act'  => array('name'=>'desresultagentname_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'desresultjobprojectid_act'  => array('name'=>'desresultjobprojectid_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'desresultcustomer_act'  => array('name'=>'desresultcustomer_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						
						'desresultcustomer_text'  => array(
							'name'    => 'desresultcustomer_text',
							'type'    => 'hidden',
							'default' => esc_html__( 'We are recruiting for following customers:', 'prosolwpclient'),
							'class'	  => 'hidden' 	
						),	

						'desbtnresulttojob'  => array(
							'name'    => 'desbtnfrontendback',
							'label'   => esc_html__( 'To job button', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'to job', 'prosolwpclient' ) 	
						),	
						
						'FrontendDetails' => array(
							'name'    => 'desdetailsfrontend',
							'label'   => esc_html__( 'Frontend jobdetails', 'prosolwpclient' ),
							'type'    => 'html'
						),

						'desbtndetailsback'  => array(
							'name'    => 'desbtndetailsback',
							'label'   => esc_html__( 'back button', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back to search', 'prosolwpclient' ) 	
						),	

						'desbtndetailsapplywhatsapp'  => array(
							'name'    => 'desbtndetailsapplywhatsapp',
							'label'   => esc_html__( 'Apply with WhatsApp', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'Apply with WhatsApp', 'prosolwpclient' ) 	
						),	

						'desbtndetailsapply'  => array(
							'name'    => 'desbtndetailsapply',
							'label'   => esc_html__( 'apply button', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'apply !', 'prosolwpclient' ) 	
						),	

						'desdetailsfrontend' => array(
							'name'    => 'desdetailsfrontend',
							'label'   => esc_html__( 'Frontend jobdetails', 'prosolwpclient' ),
							'type'    => 'text_des_template'
						),

						'desdetailszipcode_act'  => array('name'=>'desdetailszipcode_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailsplaceofwork_act'  => array('name'=>'desdetailsplaceofwork_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailsworktime_act'  => array('name'=>'desdetailsworktime_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailssalary_act'  => array('name'=>'desdetailssalary_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailsqualification_act'  => array('name'=>'desdetailsqualification_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailsprofession_act'  => array('name'=>'desdetailsprofession_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailsagentname_act'  => array('name'=>'desdetailsagentname_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'desdetailsjobprojectid_act'  => array('name'=>'desdetailsjobprojectid_act','type'=>'hidden','default' =>'0','class'=>'hidden'),
						'desdetailscustomer_act'  => array('name'=>'desdetailscustomer_act','type'=>'hidden','default' =>'0','class'=>'hidden'),

						'desdetailstextfield1_act'  => array('name'=>'desdetailstextfield1_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield2_act'  => array('name'=>'desdetailstextfield2_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield3_act'  => array('name'=>'desdetailstextfield3_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield4_act'  => array('name'=>'desdetailstextfield4_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield5_act'  => array('name'=>'desdetailstextfield5_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield6_act'  => array('name'=>'desdetailstextfield6_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield7_act'  => array('name'=>'desdetailstextfield7_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield8_act'  => array('name'=>'desdetailstextfield8_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield9_act'  => array('name'=>'desdetailstextfield9_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield10_act'  => array('name'=>'desdetailstextfield10_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield11_act'  => array('name'=>'desdetailstextfield11_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield12_act'  => array('name'=>'desdetailstextfield12_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield13_act'  => array('name'=>'desdetailstextfield13_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield14_act'  => array('name'=>'desdetailstextfield14_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield15_act'  => array('name'=>'desdetailstextfield15_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield16_act'  => array('name'=>'desdetailstextfield16_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield17_act'  => array('name'=>'desdetailstextfield17_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield18_act'  => array('name'=>'desdetailstextfield18_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield19_act'  => array('name'=>'desdetailstextfield19_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield20_act'  => array('name'=>'desdetailstextfield20_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield21_act'  => array('name'=>'desdetailstextfield21_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield22_act'  => array('name'=>'desdetailstextfield22_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield23_act'  => array('name'=>'desdetailstextfield23_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield24_act'  => array('name'=>'desdetailstextfield24_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield25_act'  => array('name'=>'desdetailstextfield25_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield26_act'  => array('name'=>'desdetailstextfield26_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield27_act'  => array('name'=>'desdetailstextfield27_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield28_act'  => array('name'=>'desdetailstextfield28_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield29_act'  => array('name'=>'desdetailstextfield29_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						'desdetailstextfield30_act'  => array('name'=>'desdetailstextfield30_act','type'=>'hidden','default' =>'1','class'=>'hidden'),
						
						'desdetailscustomer_text'  => array(
							'name'    => 'desdetailscustomer_text',
							'type'    => 'hidden',
							'default' => esc_html__( 'We are recruiting for following customers:', 'prosolwpclient'),
							'class'	  => 'hidden' 
						),

						'dessortby'  => array(
							'name'    => 'dessortby',
							'label'   => esc_html__( 'Sort By', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '0',
							'options'  => array(
								'0' => esc_html__( 'publish date', 'prosolwpclient' ),
								'1' => esc_html__( 'jobproject id', 'prosolwpclient' ),
								'2' => esc_html__( 'name', 'prosolwpclient' )
							)	
						),
						
						'ApplyFrom' => array(
							'name'    => 'ApplyFrom',
							'label'   => esc_html__( 'Frontend apply form', 'prosolwpclient' ),
							'type'    => 'html'
						),
						
						'desbtnappformtodetails'  => array(
							'name'    => 'desbtnappformtodetails',
							'label'   => esc_html__( 'Button to details', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back to details', 'prosolwpclient' ) 	
						),	
						
						'desbtnappformtosearch'  => array(
							'name'    => 'desbtnappformtosearch',
							'label'   => esc_html__( 'Button to search', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back to search', 'prosolwpclient' ) 	
						),	
						
						'desbtnappformtohome'  => array(
							'name'    => 'desbtnappformtohome',
							'label'   => esc_html__( 'Button to home', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back to home', 'prosolwpclient' ) 	
						),	
						
						'desbtnappformnext'  => array(
							'name'    => 'desbtnappformnext',
							'label'   => esc_html__( 'Button next step', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'next', 'prosolwpclient' ) 	
						),	
						
						'desbtnappformback'  => array(
							'name'    => 'desbtnappformback',
							'label'   => esc_html__( 'Button back', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back', 'prosolwpclient' ) 	
						)
					)
				);
				
				//site fields
				//delete_option('prosolwpclient_languages');
				if( get_option('prosolwpclient_additionalsite')===false || get_option('prosolwpclient_additionalsite')['valids']=="0"){
					$totalsite=0;
				}else{					
					$totalsite=intval(get_option('prosolwpclient_additionalsite')['valids']);
				}		
				
				if($totalsite!=0){
					for($x=1;$x<=$totalsite;$x++){
						$settings_builtin_fields['prosolwpclient_api_config']['site'.$x.'_api_url']= array(
							'name'              => 'site'.$x.'_api_url',
							'label'             => esc_html__( 'API Domain Url', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Customer domain url. e.g. https://customer.workexpert.online', 'prosolwpclient' ),
							'type'              => 'url',
							'default'           => '',
							'desc_tip'          => true,
							'label_selector'    => false,
							'value_selector'    => false,
							'required'          => true,
							'max'               => 256,
							'sanitize_callback' => array( 'CBXProSolWpClient_Settings_API', 'sanitize_text_field' )
						); 
						$settings_builtin_fields['prosolwpclient_api_config']['site'.$x.'_api_user']= array(
							'name'              => 'site'.$x.'_api_user',
							'label'             => esc_html__( 'API User', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Please put API User here, required field.', 'prosolwpclient' ),
							'type'              => 'text',
							'default'           => $x,
							'desc_tip'          => true,
							'label_selector'    => false,
							'value_selector'    => false,
							'required'          => true,
							'max'               => 30,
							'sanitize_callback' => array( 'CBXProSolWpClient_Settings_API', 'sanitize_text_field' )
						);
						$settings_builtin_fields['prosolwpclient_api_config']['site'.$x.'_api_pass']= array(
							'name'              => 'site'.$x.'_api_pass',
							'label'             => esc_html__( 'API Pass', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Please put API Pass here, required field.', 'prosolwpclient' ),
							'type'              => 'password',
							'default'           => '',
							'desc_tip'          => true,
							'label_selector'    => false,
							'value_selector'    => false,
							'required'          => true,
							'max'               => 500,
							'sanitize_callback' => array( 'CBXProSolWpClient_Settings_API', 'sanitize_text_field' )
						);
						//$settings_builtin_fields['prosolwpclient_api_config']['site'.$x.'_btn_url_check']= array(
	
						$settings_builtin_fields['prosolwpclient_frontend']['site'.$x.'_frontend_pageid'] =	array(
							'name'    => 'site'.$x.'_frontend_pageid',
							'label'   => esc_html__( 'ProSolution Frontend Page', 'prosolwpclient' ),
							'desc'    => sprintf( __( 'ProSolution Frontend page, shortcode <code>[prosolfrontend type="search"]</code> added automatically. <a class="button button-primary" href="%s" target="_blank">Visit Page</a> <a class="button button-default" href="%s" target="_blank">Edit Page</a>', 'prosolwpclient' ), $page_view_link, $page_edit_link ),
							'type'    => 'select',
							'default' => 0,
							'options' => $pages_options
						);
						$settings_builtin_fields['prosolwpclient_frontend']['site'.$x.'_default_nation'] = array(
							'name'    => 'site'.$x.'_default_nation',
							'label'   => esc_html__( 'Default Nation', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Select the nation to be used as Country, Nationality and Country of Birth.', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '',
							'required'=> true,
							'options' => $default_nation_arr
						);
						$settings_builtin_fields['prosolwpclient_frontend']['site'.$x.'_default_office'] = array(
							'name'    => 'site'.$x.'_default_office',
							'label'   => esc_html__( 'Default Office', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Select the Office to be used as default.', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '',
							'options' => $default_office_arr
						);
						$settings_builtin_fields['prosolwpclient_frontend']['site'.$x.'_enable_recruitment'] = array(
							'name'              => 'site'.$x.'_enable_recruitment',
							'label'             => esc_html__( 'Enable Recruitment', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Enable feature Recruitment', 'prosolwpclient' ),
							'desc_tip'          => true,
							'type'    			=> 'chkbox_recruitment',
							'required' 			=> false,
							'default' 			=> 'on' 
						);
						$settings_builtin_fields['prosolwpclient_frontend']['site'.$x.'_client_list'] = array(
							'name'              => 'site'.$x.'_client_list',
							'label'             => esc_html__( 'Client List', 'prosolwpclient' ),
							'desc'              => esc_html__( 'Insert list of clients from which webjobs should be shown in the frontend (max. 5 client IDs, comma-seperated)', 'prosolwpclient' ),
							'desc_tip'          => true,
							'type'    			=> 'text',
							'required' 			=> false,
							'default' 			=> '' 
						);
						$settings_builtin_fields['prosolwpclient_languages']['site'.$x.'_default_language'] = array(
							'name'    => 'site'.$x.'_default_language',
							'label'   => esc_html__( 'Language', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Select the language as default.', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '',
							'required' => true,
							'options'  => array(
								'en' => esc_html__( 'English', 'prosolwpclient' ),
								'de'  => esc_html__( 'German', 'prosolwpclient' ),
								'es'  => esc_html__( 'Spanish', 'prosolwpclient' )
							)
						);
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_one_pager'] = array(
							'name'    => 'site'.$x.'_one_pager',
							'label'   => esc_html__( 'Application form steps', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Change the step view of the application form', 'prosolwpclient' ),
							'type'    => 'select',
							'required' => true,
							'default' => '1',
							'options'  => array(
								'0' => esc_html__( 'One Pager', 'prosolwpclient' ),
								'1'  => esc_html__( 'Step-by-Step', 'prosolwpclient' )
							)
						);
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_step_label'] = array(
							'name'    => 'site'.$x.'_step_label',
							'label'   => esc_html__( 'Step Name', 'prosolwpclient' ),
							'desc'    => esc_html__( 'Change Step Label', 'prosolwpclient' ),
							'type'    => 'text',
							'required' => true,
							'default' => esc_html__( 'Step', 'prosolwpclient' )
						);
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata'] = array(
							'name'    => 'site'.$x.'_personaldata',
							'label'   => esc_html__( '1st Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Personal Data' , 'prosolwpclient' )	
						);
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_act']=array('name'=> 'site'.$x.'_personaldata_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_title_act']=array('name'=> 'site'.$x.'_personaldata_title_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_title_man']=array('name'=> 'site'.$x.'_personaldata_title_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_federal_act']=array('name'=> 'site'.$x.'_personaldata_federal_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_federal_man']=array('name'=> 'site'.$x.'_personaldata_federal_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_phone_act']=array('name'=> 'site'.$x.'_personaldata_phone_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_phone_man']=array('name'=> 'site'.$x.'_personaldata_phone_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_mobile_act']=array('name'=> 'site'.$x.'_personaldata_mobile_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_mobile_man']=array('name'=> 'site'.$x.'_personaldata_mobile_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_email_act']=array('name'=> 'site'.$x.'_personaldata_email_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_email_man']=array('name'=> 'site'.$x.'_personaldata_email_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_nationality_act']=array('name'=> 'site'.$x.'_personaldata_nationality_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_nationality_man']=array('name'=> 'site'.$x.'_personaldata_nationality_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_marital_act']=array('name'=> 'site'.$x.'_personaldata_marital_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_marital_man']=array('name'=> 'site'.$x.'_personaldata_marital_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_gender_act']=array('name'=> 'site'.$x.'_personaldata_gender_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_gender_man']=array('name'=> 'site'.$x.'_personaldata_gender_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_diverse_act']=array('name'=> 'site'.$x.'_personaldata_diverse_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_expectedsalary_act']=array('name'=> 'site'.$x.'_personaldata_expectedsalary_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_expectedsalary_man']=array('name'=> 'site'.$x.'_personaldata_expectedsalary_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_countrybirth_act']=array('name'=> 'site'.$x.'_personaldata_countrybirth_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_countrybirth_man']=array('name'=> 'site'.$x.'_personaldata_countrybirth_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_availfrom_act']=array('name'=> 'site'.$x.'_personaldata_availfrom_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_availfrom_man']=array('name'=> 'site'.$x.'_personaldata_availfrom_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_notes_act']=array('name'=> 'site'.$x.'_personaldata_notes_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_notes_man']=array('name'=> 'site'.$x.'_personaldata_notes_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText1_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText1_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText1_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText1_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText2_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText2_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText2_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText2_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText3_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText3_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText3_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText3_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText4_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText4_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText4_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText4_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText5_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText5_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText5_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText5_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText6_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText6_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText6_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText6_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText7_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText7_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText7_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText7_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText8_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText8_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText8_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText8_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText9_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText9_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText9_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText9_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText10_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileText10_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileText10_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileText10_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileOption1_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileOption1_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileOption1_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileOption1_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileOption2_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileOption2_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileOption2_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileOption2_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileOption3_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileOption3_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileOption3_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileOption3_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileOption4_act']=array('name'=> 'site'.$x.'_personaldata_Title_profileOption4_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_Title_profileOption4_man']=array('name'=> 'site'.$x.'_personaldata_Title_profileOption4_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_max_distance_man']=array('name'=> 'site'.$x.'_personaldata_max_distance_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_max_distance_act']=array('name'=> 'site'.$x.'_personaldata_max_distance_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_empgroup_ID_man']=array('name'=> 'site'.$x.'_personaldata_empgroup_ID_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_empgroup_ID_act']=array('name'=> 'site'.$x.'_personaldata_empgroup_ID_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_tagid_man']=array('name'=> 'site'.$x.'_personaldata_tagid_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_personaldata_tagid_act']=array('name'=> 'site'.$x.'_personaldata_tagid_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
	
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education']=array(
							'name'=> 'site'.$x.'_education', 
							'label'   => esc_html__( '2nd Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Education' , 'prosolwpclient' )
						);
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education_act']=array('name'=> 'site'.$x.'_education_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education_postcode_act']=array('name'=> 'site'.$x.'_education_postcode_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education_postcode_man']=array('name'=> 'site'.$x.'_education_postcode_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education_country_act']=array('name'=> 'site'.$x.'_education_country_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education_country_man']=array('name'=> 'site'.$x.'_education_country_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education_level_act']=array('name'=> 'site'.$x.'_education_level_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education_level_man']=array('name'=> 'site'.$x.'_education_level_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education_description_act']=array('name'=> 'site'.$x.'_education_description_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_education_description_man']=array('name'=> 'site'.$x.'_education_description_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience']=array(
							'name'=> 'site'.$x.'_workexperience', 
							'label'   => esc_html__( '3rd Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' =>  esc_html__( 'Work Experience' , 'prosolwpclient' )
						);
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_act']=array('name'=> 'site'.$x.'_workexperience_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_job_act']=array('name'=> 'site'.$x.'_workexperience_job_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_job_man']=array('name'=> 'site'.$x.'_workexperience_job_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_gendesc_act']=array('name'=> 'site'.$x.'_workexperience_gendesc_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_gendesc_man']=array('name'=> 'site'.$x.'_workexperience_gendesc_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_company_act']=array('name'=> 'site'.$x.'_workexperience_company_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_company_man']=array('name'=> 'site'.$x.'_workexperience_company_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_postcode_act']=array('name'=> 'site'.$x.'_workexperience_postcode_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_postcode_man']=array('name'=> 'site'.$x.'_workexperience_postcode_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_country_act']=array('name'=> 'site'.$x.'_workexperience_country_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_country_man']=array('name'=> 'site'.$x.'_workexperience_country_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_federal_act']=array('name'=> 'site'.$x.'_workexperience_federal_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_federal_man']=array('name'=> 'site'.$x.'_workexperience_federal_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_experience_act']=array('name'=> 'site'.$x.'_workexperience_experience_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_experience_man']=array('name'=> 'site'.$x.'_workexperience_experience_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_contract_act']=array('name'=> 'site'.$x.'_workexperience_contract_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_contract_man']=array('name'=> 'site'.$x.'_workexperience_contract_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_employment_act']=array('name'=> 'site'.$x.'_workexperience_employment_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_workexperience_employment_man']=array('name'=> 'site'.$x.'_workexperience_employment_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_expertise']=array(
							'name'=> 'site'.$x.'_expertise', 
							'label'   => esc_html__( '4th Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Expertise' , 'prosolwpclient' )
						);
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_expertise_act']=array('name'=> 'site'.$x.'_expertise_act', 'type'=>'hidden','default' =>'1');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_expertise_skillgroup_sort']=array('name'=> 'site'.$x.'_expertise_skillgroup_sort', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_furtherskill_act']=array('name'=> 'site'.$x.'_furtherskill_act', 'type'=>'hidden','default' =>'1');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_sidedishes']=array(
							'name'=> 'site'.$x.'_sidedishes', 
							'label'   => esc_html__( '5th Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Side Dishes' , 'prosolwpclient' )
						);
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_sidedishes_act']=array('name'=> 'site'.$x.'_sidedishes_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_sidedishes_man']=array('name'=> 'site'.$x.'_sidedishes_man', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_others']=array(
							'name'=> 'site'.$x.'_others', 
							'label'   => esc_html__( '6th Step Label', 'prosolwpclient' ),
							'type'    => 'text_app_form',
							'required' => true,
							'default' => esc_html__( 'Others' , 'prosolwpclient' )
						);
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_others_act']=array('name'=> 'site'.$x.'_others_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_others_source_act']=array('name'=> 'site'.$x.'_others_source_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_others_source_man']=array('name'=> 'site'.$x.'_others_source_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_others_apply_act']=array('name'=> 'site'.$x.'_others_apply_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_others_apply_man']=array('name'=> 'site'.$x.'_others_apply_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_others_message_act']=array('name'=> 'site'.$x.'_others_message_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_applicationform']['site'.$x.'_others_message_man']=array('name'=> 'site'.$x.'_others_message_man', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy1']=array(
							'name'=> 'site'.$x.'_policy1', 
							'label'   => esc_html__( 'First Policy', 'prosolwpclient' ),
							'desc'    => esc_html__( 'This checkbox will be mandatory in the frontend.', 'prosolwpclient' ),
							'type'    => 'textarea_policy',
							'default' => esc_html__( 'Details are correct. I would like to continue the application process!', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy1_act']=array('name'=> 'site'.$x.'_policy1_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy2']=array(
							'name'=> 'site'.$x.'_policy2',
							'label'   => esc_html__( 'Second Policy', 'prosolwpclient' ),
							'type'    => 'textarea_policy'	
						);
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy2_act']=array('name'=> 'site'.$x.'_policy2_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy3']=array(
							'name'=> 'site'.$x.'_policy3', 
							'label'   => esc_html__( 'Third Policy', 'prosolwpclient' ),
							'type'    => 'textarea_policy'	
						);
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy3_act']=array('name'=> 'site'.$x.'_policy3_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy4']=array(
							'name'=> 'site'.$x.'_policy4',
							'label'   => esc_html__( 'Fourth Policy', 'prosolwpclient' ),
							'type'    => 'textarea_policy'	
						);
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy4_act']=array('name'=> 'site'.$x.'_policy4_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy5']=array(
							'name'=> 'site'.$x.'_policy5', 
							'label'   => esc_html__( 'Fifth Policy', 'prosolwpclient' ),
							'type'    => 'textarea_policy'	
						);
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy5_act']=array('name'=> 'site'.$x.'_policy5_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy6']=array(
							'name'=> 'site'.$x.'_policy6',
							'label'   => esc_html__( 'ppvc date', 'prosolwpclient' ),
							'desc'    => esc_html__( 'This checkbox will be mandatory in the frontend.', 'prosolwpclient' ),
							'type'    => 'textarea_policy',
							'default' => esc_html__( 'I give my consent to the saving, examination and electronic processing of my data based on data protection laws.', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_privacypolicy']['site'.$x.'_policy6_act']=array('name'=> 'site'.$x.'_policy6_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
					
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_destemplate']=array(
							'name'=> 'site'.$x.'_destemplate', 
							'label'   => esc_html__( 'Select template', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '1',
							'options'  => array(
								'1'  => esc_html__( 'No Template', 'prosolwpclient' ),
								'0' => esc_html__( 'ProSolution Template', 'prosolwpclient' )
							)
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desfont']=array(
							'name'=> 'site'.$x.'_desfont', 
							'label'   => esc_html__( 'Font', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '0',
							'options'  => array(
								'0' => esc_html__( 'default Font', 'prosolwpclient' ),
								'1' => esc_html__( 'Georgia', 'prosolwpclient' ),
								'2' => esc_html__( 'Palatino', 'prosolwpclient' ),
								'3' => esc_html__( 'Times New Roman', 'prosolwpclient' ),
								'4' => esc_html__( 'Arial', 'prosolwpclient' ),
								'5' => esc_html__( 'Arial Black', 'prosolwpclient' ),
								'6' => esc_html__( 'Comic Sans MS', 'prosolwpclient' ),
								'7' => esc_html__( 'Impact', 'prosolwpclient' ),
								'8' => esc_html__( 'Lucida Sans Unicode', 'prosolwpclient' ),
								'9' => esc_html__( 'Tahoma', 'prosolwpclient' ),
								'10' => esc_html__( 'Trebuchet', 'prosolwpclient' ),
								'11' => esc_html__( 'Verdana', 'prosolwpclient' ),
								'12' => esc_html__( 'Courier New', 'prosolwpclient' ),
								'13' => esc_html__( 'Lucida Console', 'prosolwpclient' )
							)	
						);
						
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desmaincolor']=array(
							'name'=> 'site'.$x.'_desmaincolor', 
							'label'   => esc_html__( 'Main color', 'prosolwpclient' ),
							'type'    => 'color'	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_deslogo']=array(
							'name'=> 'site'.$x.'_deslogo', 
							'label'   => esc_html__( 'Logo', 'prosolwpclient' ),
							'type'    => 'file_with_thumbnail'	
						); 
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desperpage']=array(
							'name'=> 'site'.$x.'_desperpage', 
							'label'   => esc_html__( 'Jobs per page', 'prosolwpclient' ),
							'type'    => 'number',
							'default' => '10',
							'min'     => '1',
							'max'	  => '50',
							'step'    => '1'
						); 
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_dessearchfrontend']=array(
							'name'=> 'site'.$x.'_dessearchfrontend', 
							'label'   => esc_html__( 'Frontend jobsearch', 'prosolwpclient' ),
							'type'    => 'html'
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_dessearchheading']=array(
							'name'=> 'site'.$x.'_dessearchheading', 
							'label'   => esc_html__( 'Heading', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'JOBBORSE', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_dessearchjobtitle']=array(
							'name'=> 'site'.$x.'_dessearchjobtitle',
							'label'   => esc_html__( 'JobTitle', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'What?', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_dessearchplace']=array(
							'name'=> 'site'.$x.'_dessearchplace', 
							'label'   => esc_html__( 'Place', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'Where?', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_dessearchsearchbtn']=array(
							'name'=> 'site'.$x.'_dessearchsearchbtn', 
							'label'   => esc_html__( 'Search button', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'Search', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_dessearchjobidbtn']=array(
							'name'=> 'site'.$x.'_dessearchjobidbtn', 
							'label'   => esc_html__( 'Unsolicited application', 'prosolwpclient' ),
							'type'    => 'text_des_template',
							'default' => esc_html__( 'Unsolicited application', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_dessearchjobidbtn_act']=array('name'=> 'site'.$x.'_dessearchjobidbtn_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desresultfrontend']=array(
							'name'=> 'site'.$x.'_desresultfrontend', 
							'label'   => esc_html__( 'Frontend joblist', 'prosolwpclient' ),
							'type'    => 'text_des_template'
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desresultzipcode_act']=array('name'=> 'site'.$x.'_desresultzipcode_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desresultplaceofwork_act']=array('name'=> 'site'.$x.'_desresultplaceofwork_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desresultworktime_act']=array('name'=> 'site'.$x.'_desresultworktime_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desresultagentname_act']=array('name'=> 'site'.$x.'_desresultagentname_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desresultjobprojectid_act']=array('name'=> 'site'.$x.'_desresultjobprojectid_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desresultcustomer_act']=array('name'=> 'site'.$x.'_desresultcustomer_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desresultcustomer_text']=array(
							'name'=> 'site'.$x.'_desresultcustomer_text', 
							'type'    => 'hidden',
							'default' => esc_html__( 'We are recruiting for following customers:', 'prosolwpclient' ),
							'class'	  => 'hidden'  	
						);	
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desbtnresulttojob']=array(
							'name'=> 'site'.$x.'_desbtnresulttojob', 
							'label'   => esc_html__( 'To job button', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'to job', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_FrontendDetails']=array(
							'name'=> 'site'.$x.'_FrontendDetails',
							'label'   => esc_html__( 'Frontend jobdetails', 'prosolwpclient' ),
							'type'    => 'html'
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desbtndetailsback']=array(
							'name'=> 'site'.$x.'_desbtndetailsback',
							'label'   => esc_html__( 'back button', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back to search', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desbtndetailsapplywhatsapp']=array(
							'name'=> 'site'.$x.'_desbtndetailsapplywhatsapp', 
							'label'   => esc_html__( 'Apply with WhatsApp', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'Apply with WhatsApp', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desbtndetailsapply']=array(
							'name'=> 'site'.$x.'_desbtndetailsapply', 
							'label'   => esc_html__( 'apply button', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'apply !', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailsfrontend']=array(
							'name'=> 'site'.$x.'_desdetailsfrontend',
							'label'   => esc_html__( 'Frontend jobdetails', 'prosolwpclient' ),
							'type'    => 'text_des_template'
						);    
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailszipcode_act']=array('name'=> 'site'.$x.'_desdetailszipcode_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailsplaceofwork_act']=array('name'=> 'site'.$x.'_desdetailsplaceofwork_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailsworktime_act']=array('name'=> 'site'.$x.'_desdetailsworktime_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailssalary_act']=array('name'=> 'site'.$x.'_desdetailssalary_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailsqualification_act']=array('name'=> 'site'.$x.'_desdetailsqualification_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailsprofession_act']=array('name'=> 'site'.$x.'_desdetailsprofession_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailsagentname_act']=array('name'=> 'site'.$x.'_desdetailagentname_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailsjobprojectid_act']=array('name'=> 'site'.$x.'_desdetailjobprojectid_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailscustomer_act']=array('name'=> 'site'.$x.'_desdetailcustomer_act', 'type'=>'hidden','default' =>'0','class'=>'hidden');

						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield1_act']=array('name'=> 'site'.$x.'_desdetailstextfield1_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield2_act']=array('name'=> 'site'.$x.'_desdetailstextfield2_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield3_act']=array('name'=> 'site'.$x.'_desdetailstextfield3_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield4_act']=array('name'=> 'site'.$x.'_desdetailstextfield4_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield5_act']=array('name'=> 'site'.$x.'_desdetailstextfield5_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield6_act']=array('name'=> 'site'.$x.'_desdetailstextfield6_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield7_act']=array('name'=> 'site'.$x.'_desdetailstextfield7_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield8_act']=array('name'=> 'site'.$x.'_desdetailstextfield8_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield9_act']=array('name'=> 'site'.$x.'_desdetailstextfield9_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield10_act']=array('name'=> 'site'.$x.'_desdetailstextfield10_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield11_act']=array('name'=> 'site'.$x.'_desdetailstextfield11_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield12_act']=array('name'=> 'site'.$x.'_desdetailstextfield12_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield13_act']=array('name'=> 'site'.$x.'_desdetailstextfield13_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield14_act']=array('name'=> 'site'.$x.'_desdetailstextfield14_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield15_act']=array('name'=> 'site'.$x.'_desdetailstextfield15_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield16_act']=array('name'=> 'site'.$x.'_desdetailstextfield16_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield17_act']=array('name'=> 'site'.$x.'_desdetailstextfield17_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield18_act']=array('name'=> 'site'.$x.'_desdetailstextfield18_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield19_act']=array('name'=> 'site'.$x.'_desdetailstextfield19_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield20_act']=array('name'=> 'site'.$x.'_desdetailstextfield20_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield21_act']=array('name'=> 'site'.$x.'_desdetailstextfield21_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield22_act']=array('name'=> 'site'.$x.'_desdetailstextfield22_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield23_act']=array('name'=> 'site'.$x.'_desdetailstextfield23_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield24_act']=array('name'=> 'site'.$x.'_desdetailstextfield24_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield25_act']=array('name'=> 'site'.$x.'_desdetailstextfield25_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield26_act']=array('name'=> 'site'.$x.'_desdetailstextfield26_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield27_act']=array('name'=> 'site'.$x.'_desdetailstextfield27_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield28_act']=array('name'=> 'site'.$x.'_desdetailstextfield28_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield29_act']=array('name'=> 'site'.$x.'_desdetailstextfield29_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailstextfield30_act']=array('name'=> 'site'.$x.'_desdetailstextfield30_act', 'type'=>'hidden','default' =>'1','class'=>'hidden');
						
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desdetailscustomer_text']=array(
							'name'=> 'site'.$x.'_desdetailscustomer_text', 
							'type'    => 'hidden',
							'default' => esc_html__( 'We are recruiting for following customers:', 'prosolwpclient' ),
							'class'	  => 'hidden'  	
						);

						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_ApplyFrom']=array(
							'name'=> 'site'.$x.'_ApplyFrom', 
							'label'   => esc_html__( 'Frontend apply form', 'prosolwpclient' ),
							'type'    => 'html'
						);

						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_dessortby']=array(
							'name'=> 'site'.$x.'_dessortby', 
							'label'   => esc_html__( 'Sort By', 'prosolwpclient' ),
							'type'    => 'select',
							'default' => '0',
							'options'  => array(
								'0' => esc_html__( 'publish date', 'prosolwpclient' ),
								'1' => esc_html__( 'jobproject id', 'prosolwpclient' ),
								'2' => esc_html__( 'name', 'prosolwpclient' )
							)	
						);

						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desbtnappformtodetails']=array(
							'name'=> 'site'.$x.'_desbtnappformtodetails',
							'label'   => esc_html__( 'Button to details', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back to details', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desbtnappformtosearch']=array(
							'name'=> 'site'.$x.'_desbtnappformtosearch', 
							'label'   => esc_html__( 'Button to search', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back to search', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desbtnappformtohome']=array(
							'name'=> 'site'.$x.'_desbtnappformtohome', 
							'label'   => esc_html__( 'Button to home', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back to home', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desbtnappformnext']=array(
							'name'=> 'site'.$x.'_desbtnappformnext', 
							'label'   => esc_html__( 'Button next step', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'next', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_designtemplate']['site'.$x.'_desbtnappformback']=array(
							'name'=> 'site'.$x.'_desbtnappformback', 
							'label'   => esc_html__( 'Button back', 'prosolwpclient' ),
							'type'    => 'text',
							'default' => esc_html__( 'back', 'prosolwpclient' ) 	
						);
						$settings_builtin_fields['prosolwpclient_additionalsite']['addsite'.$x]=array(
							'name'              => 'addsite'.$x,
							'label'             => esc_html__( 'Site'.$x.' name', 'prosolwpclient' ),
							'type'              => 'text_site',
							'default'           => 'Site'.$x,
							'max'               => 256	
						);
						$settings_builtin_fields['prosolwpclient_additionalsite']['addsite'.$x.'_urlid']=array('name'=> 'addsite'.$x.'_urlid', 'type'=>'hidden','default' =>'1','class'=>'hidden');
					};			
				}
				$settings_builtin_fields['prosolwpclient_additionalsite']['chkremove']=array('name'=> 'valids', 'type'=>'hidden','default' =>'0');	
				$settings_builtin_fields['prosolwpclient_additionalsite']['valids']=array('name'=> 'valids', 'type'=>'add_site','default' =>'0');

			$settings_fields = array(); //final setting array that will be passed to different filters

			$sections = $this->proSol_getSettingsSections();


			foreach ( $sections as $section ) {
				if ( ! isset( $settings_builtin_fields[ $section['id'] ] ) ) {
					$settings_builtin_fields[ $section['id'] ] = array();
				}
			}


			foreach ( $sections as $section ) {
				$settings_builtin_fields_section_id = $settings_builtin_fields[ $section['id'] ];
				$settings_fields[ $section['id'] ]  = apply_filters( 'prosolwpclient_global_' . $section['id'] . '_fields', $settings_builtin_fields_section_id );

			}


			$settings_fields = apply_filters( 'prosolwpclient_global_fields', $settings_fields ); //final filter if need

			return $settings_fields;
		}


		/**
		 * Admin display
		 */
		public function proSol_displayAdminOverviewMenuPage() {
			global $wpdb;global $prosol_prefix;

			$template_name = 'templates/admin-overview.php';

			
			$table_view = isset( $_GET['table_view'] ) ? intval( $_GET['table_view'] ) : 0;
			$table_name = isset( $_GET['table'] ) ? filter_var( $_GET['table'], FILTER_SANITIZE_STRING ) : '';


			if ( $table_view == 1 ) {
				$template_name = 'templates/admin-view-single-table-list.php';
			}

			include( $template_name );
		}


		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function proSol_enqueueStyles_Admin( $hook ) {
			if ( $hook == 'toplevel_page_prosolutionoverview' ) {
				wp_register_style( 'prosolwpclient-admin', plugin_dir_url( __FILE__ ) . 'css/prosolwpclient-admin.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'prosolwpclient-admin' );
			}
			if ( $hook == 'pro-solution_page_prosolwpclientsettings' ) {
				wp_register_style( 'prosolwpclient-setting', plugin_dir_url( __FILE__ ) . 'css/prosolwpclient-setting.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'prosolwpclient-setting' );
			}	

		}	

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function proSol_enqueueScripts_Admin( $hook ) {
			$suffix = ( defined( 'PROSOLWPCLIENT_SCRIPTDEBUG' ) && PROSOLWPCLIENT_SCRIPTDEBUG ) ? '' : '.min';

			if ( $hook == 'toplevel_page_prosolutionoverview' ) {
				wp_register_script( 'prosolwpclient-admin', plugin_dir_url( __FILE__ ) . 'js/prosolwpclient-admin.js', array( 'jquery' ), $this->version, true );
				// Localize the script with new data
				$prosolwpclient_admin_js_vars = apply_filters('prosolwpclient_admin_js_vars', array(
					'api_key_missing' => esc_html__( 'Api key is missing, please set api user and api password in plugin setting.', 'prosolwpclient' ),
					'loading'         => esc_html__( 'Loading', 'prosolwpclient' ),
					'ajaxurl'         => admin_url( 'admin-ajax.php' ),
					'nonce'           => wp_create_nonce( 'prosolwpclient' ),
					'sync_failed'     => esc_html__( 'Last sync failed!', 'prosolwpclient' )
				));
				wp_localize_script( 'prosolwpclient-admin', 'prosolwpclient', $prosolwpclient_admin_js_vars );
				wp_enqueue_script( 'prosolwpclient-admin' );
			}

			if ( $hook == 'pro-solution_page_prosolwpclientsettings' ) {

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_media();
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_script( 'jquery' );

				wp_register_style( 'prosolwpclientchoosen', plugin_dir_url( __FILE__ ) . 'css/chosen.min.css', array(), $this->version, 'all' );
				wp_register_style( 'prosolwpclient-setting', plugin_dir_url( __FILE__ ) . 'css/prosolwpclient-setting.css', array( 'wp-color-picker' ), $this->version, 'all' );

				wp_register_script( 'jquery.validate.min', plugin_dir_url( __FILE__ ) . 'js/jquery.validate' . $suffix . '.js', array( 'jquery' ), $this->version, false );
				wp_register_script( 'prosolwpclientchoosen', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.min.js', array( 'jquery' ), $this->version, true );
				wp_register_script( 'prosolwpclient-setting', plugin_dir_url( __FILE__ ) . 'js/prosolwpclient-setting.js', array(
					'jquery',
					'wp-color-picker',
					'prosolwpclientchoosen'
				), $this->version, true );
				

				$prosolwpclient_setting_js_vars = apply_filters('prosolwpclient_setting_js_vars', array(
					'required'    => esc_html__( 'This field is required.', 'prosolwpclient' ),
					'remote'      => esc_html__( 'Please fix this field.', 'prosolwpclient' ),
					'email'       => esc_html__( 'Please enter a valid email address.', 'prosolwpclient' ),
					'url'         => esc_html__( 'Please enter a valid URL.', 'prosolwpclient' ),
					'date'        => esc_html__( 'Please enter a valid date.', 'prosolwpclient' ),
					'dateISO'     => esc_html__( 'Please enter a valid date ( ISO ).', 'prosolwpclient' ),
					'number'      => esc_html__( 'Please enter a valid number.', 'prosolwpclient' ),
					'digits'      => esc_html__( 'Please enter only digits.', 'prosolwpclient' ),
					'equalTo'     => esc_html__( 'Please enter the same value again.', 'prosolwpclient' ),
					'maxlength'   => esc_html__( 'Please enter no more than {0} characters.', 'prosolwpclient' ),
					'minlength'   => esc_html__( 'Please enter at least {0} characters.', 'prosolwpclient' ),
					'rangelength' => esc_html__( 'Please enter a value between {0} and {1} characters long.', 'prosolwpclient' ),
					'range'       => esc_html__( 'Please enter a value between {0} and {1}.', 'prosolwpclient' ),
					'max'         => esc_html__( 'Please enter a value less than or equal to {0}.', 'prosolwpclient' ),
					'min'         => esc_html__( 'Please enter a value greater than or equal to {0}.', 'prosolwpclient' ),
					'step'         => esc_html__( 'Please enter a multiple of {0}.', 'prosolwpclient' ),
					'recaptcha'   => esc_html__( 'Please check the captcha.', 'prosolwpclient' ),
					'ajaxurl'     => admin_url( 'admin-ajax.php' ),
					'nonce'       => wp_create_nonce( 'prosolwpclient' ),
				));
				wp_localize_script( 'prosolwpclient-setting', 'prosolwpclient_setting', $prosolwpclient_setting_js_vars );

				wp_enqueue_style( 'prosolwpclientchoosen' );
				wp_enqueue_style( 'prosolwpclient-setting' );

				wp_enqueue_script( 'jquery.validate.min' );
				wp_enqueue_script( 'prosolwpclientchoosen' );
				wp_enqueue_script( 'prosolwpclient-setting' );
				//wp_enqueue_script( 'prosolwpclient-admin' );
				
			}

		}

	}
