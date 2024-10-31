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
     * Version:           1.9.5
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
    global $prosol_prefix;
	global $wpdb;
    $prosol_prefix = $wpdb->prefix.'prosolution_';
    /**
     * Currently pligin version.
     * Start at version 1.0.0 and use SemVer - https://semver.org
     * Rename this for your plugin and update it as you release new versions.
     */


    defined('PROSOLWPCLIENT_PLUGIN_NAME') or define('PROSOLWPCLIENT_PLUGIN_NAME', 'prosolwpclient');
    defined('PROSOLWPCLIENT_PLUGIN_VERSION') or define('PROSOLWPCLIENT_PLUGIN_VERSION', '1.9.5');
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
	function crypt_custom_old( $string, $action = 'e' ) {
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
	/* Reference: https://www.geeksforgeeks.org/how-to-encrypt-and-decrypt-a-php-string/ */
	function crypt_custom( $string, $action = 'e' ) {
	    $output = false;
	    $ciphering = "AES-256-CBC";
	    // Use OpenSSL encryption method
		$iv_length = openssl_cipher_iv_length($ciphering);
		$options = 0;
		 
		// Use random_bytes() function to generate a random initialization vector (iv)
		//$encryption_iv = 'B9F9A91250760A905008BYT';
		$encryption_iv = hex2bin(randomstring(23));
		// Alternatively, you can use a fixed iv if needed
		//$encryption_iv = openssl_random_pseudo_bytes($iv_length);
		 
		// Use php_uname() as the encryption key
		$encryption_key = openssl_digest(php_uname(), 'MD5', TRUE);
		if( $action == 'e' ) {
	        $output = str_replace(['+', '/', '='], ['-', '_', ''],base64_encode(openssl_encrypt($string, $ciphering,
		    $encryption_key, $options, $encryption_iv)));
	    }
	    else if( $action == 'd' ){
	       $output = openssl_decrypt(  base64_decode(str_replace(['-', '_'], ['+', '/'], $string)) , $ciphering,$encryption_key, $options, $encryption_iv);
	    }
	
	    return $output;
	}

	function crypt_customv2( $string, $action = 'e' ) {
	    $output = false;
	    $ciphering = "AES-256-CBC";
		$options = 0;

		$file_dir = __DIR__; 
		$file_path = $file_dir . '/vector.txt'; // Path to the txt file
		if (file_exists($file_path)) {
			// Open the file for reading
			$file_txt = fopen($file_path, 'r');
			if ($file_txt) {
				// Read the file content
				$file_content = fread($file_txt, filesize($file_path));
				fclose($file_txt); // Close the file
			} else {
				error_log('Failed to open vector.');die();
			}
		} else {
			error_log("Vector doesn't exists.");die();
		}

		$encryption_iv = $file_content;
		$encryption_key = get_option('prosolwpclient_encryptionkey');

		if( $action == 'e' ) {
	        $output = str_replace(['+', '/', '='], ['-', '_', ''],base64_encode(openssl_encrypt($string, $ciphering,
		    $encryption_key, $options, $encryption_iv)));
	    }
	    else if( $action == 'd' ){
	       $output = openssl_decrypt(  base64_decode(str_replace(['-', '_'], ['+', '/'], $string)) , $ciphering,$encryption_key, $options, $encryption_iv);
	    }
	    return $output;
	}

	function randomstring($len)
	{
	$string = "";
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	for($i=0;$i<$len;$i++)
	$string.=substr($chars,rand(0,strlen($chars)),1);
	return $string;
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
		global $wpdb;global $prosol_prefix;	
		$all_table_arr=array_keys(CBXProSolWpClient_Helper::proSol_allTablesArr());
		// foreach($all_table_arr as $tablename ){
		// 	$prosol_table = $prosol_prefix.$tablename;
		// 	$oldprkey=$tablename.'id';
		// 	if($tablename=='country')$oldprkey='countrycode';
		// 	else if($tablename=='customfields')$oldprkey='customId';
		// 	else if($tablename=='educationlookup')$oldprkey='lookupId';
		// 	if($tablename != 'jobs'){
		// 		$wpdb->query( "ALTER TABLE $prosol_table DROP primary key " );
		// 		$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN site_id BIGINT(11) NOT NULL DEFAULT '0' " );					
		// 		$wpdb->query( "ALTER TABLE $prosol_table ADD primary key (site_id,$oldprkey)" );
		// 	}
		// }
		// $unlist_table_arr=array('comments','commentmeta','links','logs_activity','postmeta','posts','termmeta','terms',
		// 		'term_relationships','term_taxonomy','usermeta','users');
		// foreach($unlist_table_arr as $tablename){
		// 	$prosol_table = $prosol_prefix.$tablename;
		// 	$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN site_id BIGINT(11) NOT NULL DEFAULT '0' " );
		// }	
		
		//delete old cookies if exists
		if (isset($_COOKIE['selsite'])) {
			unset($_COOKIE['selsite']);
			setcookie('selsite', '0', 0 ); // empty value and old timestamp
		}

		// OP 1902 add new column in table jobs
		// $prosol_table = $prosol_prefix.'jobs';
		// $chk_col = $wpdb->get_results("SHOW COLUMNS FROM $prosol_table LIKE 'customer' ", 'ARRAY_A');	
		// if(count($chk_col) == 0){
		// 	$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN customer LONGTEXT NOT NULL AFTER portal " );
		// }

		// OP 2328 add new column in table job for customtextfield
		// for($idx=21;$idx<=30;$idx++){
		// 	$keyfield="textfield_".$idx;
		// 	$keylabel="textfieldlabel_".$idx;

		// 	$chk_col = $wpdb->get_results("SHOW COLUMNS FROM $prosol_table LIKE '$keyfield' ", 'ARRAY_A');    
		// 	if(count($chk_col) == 0){
		// 		$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN $keyfield TEXT NOT NULL " );
		// 	}
		// 	$chk_col = $wpdb->get_results("SHOW COLUMNS FROM $prosol_table LIKE '$keylabel' ", 'ARRAY_A');    
		// 	if(count($chk_col) == 0){
		// 		$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN $keylabel varchar(150) NOT NULL " );
		// 	}
		// }

		$prosol_table = $prosol_prefix.'jobs';
		$chk_col = $wpdb->get_results("SHOW COLUMNS FROM $prosol_table LIKE 'recruitlink' ", 'ARRAY_A');	
		if(count($chk_col) == 0){
			$wpdb->query( "ALTER TABLE $prosol_table ADD COLUMN recruitlink LONGTEXT NOT NULL DEFAULT '' AFTER customer " );
		}

		if (false == get_option('prosolwpclient_encryptionkey') || 0 == get_option('prosolwpclient_encryptionkey') ) {
			update_option( 'prosolwpclient_encryptionkey', bin2hex(random_bytes(16)) );

			$vectorkey = bin2hex(random_bytes(16));
			$plugin_dir = __DIR__; // Current directory where the script is run
			$readme_path = $plugin_dir . '/vector.txt';
			if (! file_put_contents($readme_path, $vectorkey)) {
				error_log('Failed to generate vector.');die();
			}
		}

		//NOTE: this is to change api password with new encryption key for already existing installation
		if( 
			false == get_option('prosolwpclient_isnewapi') || 
			get_option('prosolwpclient_isnewapi') !== false && (get_option('prosolwpclient_isnewapi') == 0 || get_option('prosolwpclient_isnewapi') == 1) 
		){
			$old_apiconfig_arr = get_option('prosolwpclient_api_config');
			$new_apiconfig_arr=array();
			$validsite=intval(get_option('prosolwpclient_additionalsite')['valids']);
			if(false != get_option('prosolwpclient_additionalsite')){
				for($x=0;$x<=$validsite;$x++){
					$issite= $x==0 ? '' : 'site'.$x.'_';
					if(array_key_exists($issite.'api_pass', $old_apiconfig_arr)){

						if($old_apiconfig_arr[$issite.'api_pass'] != ''){
							$new_apiconfig_arr[$issite.'api_url'] = $old_apiconfig_arr[$issite.'api_url'];
							$new_apiconfig_arr[$issite.'api_user'] = $old_apiconfig_arr[$issite.'api_user'];
						
							if(false == get_option('prosolwpclient_isnewapi') || get_option('prosolwpclient_isnewapi') !== false && get_option('prosolwpclient_isnewapi') == 0 ){
								$oldapipass = crypt_custom_old($old_apiconfig_arr[$issite.'api_pass'],'d');		
			
								$new_apiconfig_arr[$issite.'api_pass'] = crypt_customv2($oldapipass, 'e');
							}elseif(get_option('prosolwpclient_isnewapi') !== false && get_option('prosolwpclient_isnewapi') == 1 ){
								$oldapipass = crypt_custom($old_apiconfig_arr[$issite.'api_pass'],'d');		
			
								$new_apiconfig_arr[$issite.'api_pass'] = crypt_customv2($oldapipass, 'e');
							}
							
						}else{
							$new_apiconfig_arr[$issite.'api_url'] = $old_apiconfig_arr[$issite.'api_url'];
							$new_apiconfig_arr[$issite.'api_user'] = $old_apiconfig_arr[$issite.'api_user'];
							$new_apiconfig_arr[$issite.'api_pass'] = $old_apiconfig_arr[$issite.'api_pass'];
						}
						update_option( 'prosolwpclient_api_config', $new_apiconfig_arr );
					}
				}
			}
			update_option( 'prosolwpclient_isnewapi', 2 );
		}

		$table_arr=array();
		$table_arr[1] = 'logs_activity';
		$table_arr[2] = 'setting';
		$table_arr[3] = 'jobs';
		$table_arr[4] = 'jobstamp';
		$table_arr[5] = 'country';
		$table_arr[6] = 'office';
		$table_arr[7] = 'agent';
		$table_arr[8] = 'workpermit';
		$table_arr[9] = 'staypermit';
		$table_arr[10] = 'availability';
		$table_arr[11] = 'federal';
		$table_arr[12] = 'marital';
		$table_arr[13] = 'title';
		$table_arr[14] = 'skillgroup';
		$table_arr[15] = 'skill';
		$table_arr[16] = 'skillrate';
		$table_arr[17] = 'professiongroup';
		$table_arr[18] = 'profession';
		$table_arr[19] = 'education';
		$table_arr[20] = 'educationlookup';
		$table_arr[21] = 'recruitmentsource';
		$table_arr[22] = 'qualification';
		$table_arr[23] = 'qualificationeval';
		$table_arr[24] = 'filecategoryemp';
		$table_arr[25] = 'contract';
		$table_arr[26] = 'employment';
		$table_arr[27] = 'experienceposition';
		$table_arr[28] = 'operationarea';
		$table_arr[29] = 'nace';
		$table_arr[30] = 'isced';
		$table_arr[31] = 'customfields';
		$table_arr[32] = 'worktime';
		$table_arr[33] = 'jobcustomfields';
		
		for($i=1;$i<=33;$i++){
			$currTableName = 'wp_'.$table_arr[$i];
			$nextTableName = 'wp_prosolution_'.$table_arr[$i];
			$chk_col = $wpdb->get_results( "SELECT 1 FROM information_schema.tables WHERE table_name = '$currTableName' ", 'ARRAY_A' );
			
			if(count($chk_col) != 0){
				$wpdb->query( "ALTER TABLE $currTableName RENAME $nextTableName" );
			}
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