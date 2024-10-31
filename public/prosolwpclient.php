<?php

    /**
     * The plugin bootstrap file
     *
     * This file is read by WordPress to generate the plugin information in the plugin
     * admin area. This file also includes all of the dependencies used by the plugin,
     * registers the activation and deactivation functions, and defines a function
     * that starts the plugin.
     *
     * @link              https://www.prosolution.com
     * @since             1.0.0
     * @package           Prosolwpclient
     *
     * @wordpress-plugin
     * Plugin Name:       ProSolution WP Client
     * Plugin URI:        https://prosolution.com/produkte-und-services/workexpert.html
     * Description:       WordPress client for ProSolution
     * Version:           1.8.7
     * Author:            ProSolution
     * Author URI:        https://www.prosolution.com
     * License:           GPL-2.0+
     * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
     * Text Domain:       prosolwpclient
     * Domain Path:       /languages
     */

// If this file is called directly, abort.
    if (!defined('WPINC')) {
        die;
    }
    ob_start();
    /**
     * Currently pligin version.
     * Start at version 1.0.0 and use SemVer - https://semver.org
     * Rename this for your plugin and update it as you release new versions.
     */


    defined('PROSOLWPCLIENT_PLUGIN_NAME') or define('PROSOLWPCLIENT_PLUGIN_NAME', 'prosolwpclient');
    defined('PROSOLWPCLIENT_PLUGIN_VERSION') or define('PROSOLWPCLIENT_PLUGIN_VERSION', '1.8.7');
    defined('PROSOLWPCLIENT_BASE_NAME') or define('PROSOLWPCLIENT_BASE_NAME', plugin_basename(__FILE__));
    defined('PROSOLWPCLIENT_ROOT_PATH') or define('PROSOLWPCLIENT_ROOT_PATH', plugin_dir_path(__FILE__));
    defined('PROSOLWPCLIENT_ROOT_URL') or define('PROSOLWPCLIENT_ROOT_URL', plugin_dir_url(__FILE__));
    
    /**
	 * Encrypt and decrypt
	 * 
	 * @author Nazmul Ahsan <n.mukto@gmail.com>
	 * @link http://nazmulahsan.me/simple-two-way-function-encrypt-decrypt-string/
	 *
	 * @param string $string string to be encrypted/decrypted
	 * @param string $action what to do with this? e for encrypt, d for decrypt
	 */
	function crypt_custom( $string, $action = 'e' ) {
	    // you may change these values to your own
	    $secret_key = '0172D31EA7489A24D37E0B836B';
	    $secret_iv = 'B9F9A91250760A905008BYT';
	
	    $output = false;
	    $encrypt_method = "AES-256-CBC";
	    $key = hash( 'sha256', $secret_key );
	    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
	
	    if( $action == 'e' ) {
	        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
	    }
	    else if( $action == 'd' ){
	        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
	    }
	
	    return $output;
	}
	
	function load_plugin_textdomain_custom( $domain, $deprecated = false, $plugin_rel_path = false ) {
		/**
		 * Filters a plugin's locale.
		 *
		 * @since 3.0.0
		 *
		 * @param string $locale The plugin's current locale.
		 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
		 */
		
		/*$allowed_langs = array('en', 'de', 'de-at', 'de-de', 'de-li', 'de-lu', 'de-ch', 'es', 'es-ar', 'es-bo', 'es-cl', 'es-co', 'es-cr', 'es-do', 'es-ec', 'es-sv', 'es-gt', 'es-hn', 'es-mx', 'es-ni', 'es-pa', 'es-py', 'es-pe', 'es-pr', 'es-es', 'es-uy', 'es-ve');
		$lang = get_lang_from_browser($allowed_langs, 'en', NULL, FALSE);
		$lang = explode('-',$lang);
		$lang = strtolower($lang[0]);
		
		if($lang == 'de'){
			$locale = 'de_DE';
		}else if($lang == 'es'){
			$locale = 'es_ES';
		}else if($lang == 'en'){
			$locale = 'en_EN';	
		}else{
			$locale = apply_filters( 'plugin_locale', is_admin() ? get_user_locale() : get_locale(), $domain );
		}*/
		
		$languages_setting  = get_option( 'prosolwpclient_languages' );
		$selsite='';
		if(isset($_COOKIE['selsite'])){
			$selsite=$_COOKIE['selsite'] ? 'site'.$_COOKIE['selsite'].'_' : '';
		}		
		if ( $languages_setting !== false && isset( $languages_setting[ $selsite.'default_language'] ) ) {
			if($languages_setting[ $selsite.'default_language'] == 'de'){
				$locale = 'de_DE';
			}else if($languages_setting[ $selsite.'default_language'] == 'es'){
				$locale = 'es_ES';
			}else{
				$locale = 'en_EN';	
			}
		}else{
			$locale = 'en_EN';
		}
		
		$mofile = $domain . '-' . $locale . '.mo';

		// Try to load from the languages directory first.
		if ( load_textdomain( $domain, WP_LANG_DIR . '/plugins/' . $mofile ) ) {
			return true;
		}

		if ( false !== $plugin_rel_path ) {
			$path = WP_PLUGIN_DIR . '/' . trim( $plugin_rel_path, '/' );
		} elseif ( false !== $deprecated ) {
			_deprecated_argument( __FUNCTION__, '2.7.0' );
			$path = ABSPATH . trim( $deprecated, '/' );
		} else {
			$path = WP_PLUGIN_DIR;
		}

		return load_textdomain( $domain, $path . '/' . $mofile );
	}
	
	/**
	 * Detect browser language.
	 *
	 * @param array $allowed_languages An array of languages that are available on the site.
	 * @param string $default_language Default language to use if none could be detected.
	 * @param string $lang_variable Custom user language support. If not specified $_SERVER['HTTP_ACCEPT_LANGUAGE'] is used.
	 * @param string $strict_mode If true (default) the whole language code ("en-us") is used and not only the left part ("en").
	 * @return string The detected browser language.
	 */
	function get_lang_from_browser($allowed_languages, $default_language, $lang_variable = NULL, $strict_mode = TRUE) {
		// Use $_SERVER['HTTP_ACCEPT_LANGUAGE'] if no language variable is available
		if (NULL === $lang_variable)
			$lang_variable = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	 
		// Any info sent?
		if (empty($lang_variable))
			return $default_language;
	 
		// Split the header
		$accepted_languages = preg_split('/,\s*/', $lang_variable);
	 
		// Set default values
		$current_lang = $default_language;
		$current_q    = 0;
		// Now work through all included languages
		foreach ($accepted_languages as $accepted_language) {
			// Get all info about this language
			$res = preg_match(
				'/^([a-z]{1,8}(?:-[a-z]{1,8})*)'.
				'(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i',
				$accepted_language,
				$matches
			);
	 
			if (!$res)
				continue;
	 
			// Get language code and split into parts immediately
			$lang_code = explode('-', $matches[1]);
	 
			// Is there a quality set?
			if (isset($matches[2]))
				$lang_quality = (float)$matches[2];
			else
				$lang_quality = 1.0;
	 
			// Until the language code is empty...
			while (count($lang_code)) {
				// Check if the language code is available
				if (in_array(strtolower(join('-', $lang_code)), $allowed_languages)) {
					// Check quality
					if ($lang_quality > $current_q) {
						$current_lang = strtolower(join('-', $lang_code));
						$current_q = $lang_quality;
						break;
					}
				}
				// If we're in strict mode we won't try to minimalize the language
				if ($strict_mode)
					break;
	 
				// Cut the most right part of the language code
				array_pop($lang_code);
			}
		}
	 
		return $current_lang;
	}


    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-prosolwpclient-activator.php
     */
    function activateProsolwpclient()
    {
		//from 1.7.0 onwards, add new column site_id and modify primarykey
		global $wpdb;	
		$all_table_arr=array_keys(CBXProSolWpClient_Helper::proSol_allTablesArr());
		foreach($all_table_arr as $tablename ){
			$prosol_table = $wpdb->prefix.$tablename;
			$oldprkey=$tablename.'id';
			if($tablename=='country')$oldprkey='countrycode';
			else if($tablename=='customfields')$oldprkey='customId';
			else if($tablename=='educationlookup')$oldprkey='lookupId';
			if($tablename != 'jobs'){
				$wpdb->query( "ALTER TABLE $prosol_table DROP primary key " );
				$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN site_id BIGINT(11) NOT NULL DEFAULT '0' " );					
				$wpdb->query( "ALTER TABLE $prosol_table ADD primary key (site_id,$oldprkey)" );
			}
		}
		$unlist_table_arr=array('comments','commentmeta','links','logs_activity','postmeta','posts','termmeta','terms',
				'term_relationships','term_taxonomy','usermeta','users');
		foreach($unlist_table_arr as $tablename){
			$prosol_table = $wpdb->prefix.$tablename;
			$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN site_id BIGINT(11) NOT NULL DEFAULT '0' " );
		}	
		
		//delete old cookies if exists
		if (isset($_COOKIE['selsite'])) {
			unset($_COOKIE['selsite']);
			setcookie('selsite', '0', 0 ); // empty value and old timestamp
		}

		// OP 1902 add new column in table jobs
		$prosol_table = $wpdb->prefix.'jobs';
		$chk_col = $wpdb->get_results("SHOW COLUMNS FROM $prosol_table LIKE 'customer' ", 'ARRAY_A');	
		if(count($chk_col) == 0){
			$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN customer LONGTEXT NOT NULL AFTER portal " );
		}

		$prosol_table = $prosol_prefix.'jobs';
		$chk_col = $wpdb->get_results("SHOW COLUMNS FROM $prosol_table LIKE 'recruitlink' ", 'ARRAY_A');	
		if(count($chk_col) == 0){
			$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN recruitlink LONGTEXT NOT NULL DEFAULT '' AFTER customer " );
		}

        $prosolwpclient_frontend = get_option( 'prosolwpclient_frontend' );

        if ( $prosolwpclient_frontend === false ) {
            $prosolwpclient_frontend = array(
                'frontend_pageid' => 0
            );

            //add the default values
            update_option( 'prosolwpclient_frontend', $prosolwpclient_frontend );
        }

        require_once plugin_dir_path(__FILE__) . 'includes/class-prosolwpclient-activator.php';
		CBXProSolWpClient_Activator::proSol_activate();

        CBXProSolWpClient_Activator::proSol_createPages(); //create the shortcode page
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-prosolwpclient-deactivator.php
     */
    function deactivateProsolwpclient()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/class-prosolwpclient-deactivator.php';
        CBXProSolWpClient_Deactivator::proSol_deactivate();
    }

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-prosolwpclient-deactivator.php
	 */
    function uninstallProsolwpclient(){
	    require_once plugin_dir_path(__FILE__) . 'includes/class-prosolwpclient-uninstall.php';
	    CBXProSolWpClient_Uninstall::proSol_uninstall();
    }
    register_activation_hook(__FILE__, 'activateProsolwpclient');
    register_deactivation_hook(__FILE__, 'deactivateProsolwpclient');
	register_uninstall_hook(__FILE__, 'uninstallProsolwpclient'); //delets all custom table and custom option values created by this plugin

    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path(__FILE__) . 'includes/class-prosolwpclient.php';




	/**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function runProsolwpclient()
    {
        $plugin = new CBXProSolWpClient();
		$plugin->proSol_runs();		
		
		//remove deleted site's data
		CBXProSolWpClient_TableHelper::proSol_cleardatasites();
    }
	
	// Setting a custom timeout value for cURL. Using a high value for priority to ensure the function runs after any other added to the same action hook.
	function sar_custom_curl_timeout( $handle ){
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
		curl_setopt( $handle, CURLOPT_TIMEOUT, 30 ); // 30 seconds. Too much for production, only for testing.
	}

	// Setting custom timeout for the HTTP request
	function sar_custom_http_request_timeout( $timeout_value ) {
		return 30; // 30 seconds. Too much for production, only for testing.
	}
	
	// Setting custom timeout in HTTP request args
	function sar_custom_http_request_args( $r ){
		$r['timeout'] = 30; // 30 seconds. Too much for production, only for testing.
		return $r;
	}

	runProsolwpclient();
	ob_clean();