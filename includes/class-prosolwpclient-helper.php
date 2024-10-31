<?php

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<?php

	class CBXProSolWpClient_Helper {
		
		public static function proSol_getSitecookie() {
			$selsite='';
			if(isset($_COOKIE['selsite'])){
				$selsite=$_COOKIE['selsite']==0 ? '' : 'site'.$_COOKIE['selsite'].'_';
			}
			return $selsite;
		}	
					
		public static function proSol_getSiteid($issite = '') {
			if($issite!=''){
				$addsite= get_option('prosolwpclient_additionalsite');
				$getkey=array_keys($addsite);
				$urlid_arr=array();

				for($x=0;$x<count($addsite);$x++){				
					$chkurlid=substr($getkey[$x],0,7);
					$pos = strpos($getkey[$x], '_');
					$anotherchkurlid=substr($getkey[$x],$pos,strlen($getkey[$x]));
					
					if($chkurlid=='addsite' && $anotherchkurlid=='_urlid'){						
						$getsite= substr($getkey[$x],7,$pos-7);
						$urlid_arr[ $getsite ]= $addsite[ $getkey[$x] ];						
					}					
				}

				if(in_array($issite,$urlid_arr)){
					$chkkey=array_search($issite,$urlid_arr);
					$issite='site'.$chkkey.'_';
				}
			}
			return $issite;
		}	

		public static function proSol_getSiteidonly($issite = '') {
			if($issite!=''){
				$addsite= get_option('prosolwpclient_additionalsite');
				$getkey=array_keys($addsite);
				$urlid_arr=array();
				
				for($x=0;$x<count($addsite);$x++){				
					$chkurlid=substr($getkey[$x],0,7);
					$pos = strpos($getkey[$x], '_');
					$anotherchkurlid=substr($getkey[$x],$pos,strlen($getkey[$x]));
					
					if($chkurlid=='addsite' && $anotherchkurlid=='_urlid'){						
						$getsite= substr($getkey[$x],7,$pos-7);
						$urlid_arr[ $getsite ]= $addsite[ $getkey[$x] ];						
					}					
				}
				if(in_array($issite,$urlid_arr)){
					$chkkey=array_search($issite,$addsite);
					$pos=explode("_",$chkkey);
					$issite=substr($pos[0],7,strlen($chkkey)-7);					
				}
			} else{
				$issite=0;
			}
			return $issite;
		}	

		public static function proSol_isApiSetup($issite = '') {
			$prosolwpclient_api_config = get_option( 'prosolwpclient_api_config', array() );
			
			if ( $prosolwpclient_api_config != '' ) {
				$prosolwpclient_api_config = maybe_unserialize( $prosolwpclient_api_config );
			}
			$api_user = array_key_exists( $issite.'api_user', $prosolwpclient_api_config ) ? sanitize_text_field( $prosolwpclient_api_config[$issite.'api_user'] ) : '';
			//$api_pass = array_key_exists( 'api_pass', $prosolwpclient_api_config ) ?  sanitize_text_field( base64_decode($prosolwpclient_api_config['api_pass'] )) : '';
			if(get_option('prosolwpclient_isnewapi') == 2){
				$api_pass = array_key_exists( $issite.'api_pass', $prosolwpclient_api_config ) ?   crypt_customv2($prosolwpclient_api_config[$issite.'api_pass'], 'd' ) : '';
			}else{
				$api_pass = array_key_exists( $issite.'api_pass', $prosolwpclient_api_config ) ?   crypt_custom($prosolwpclient_api_config[$issite.'api_pass'], 'd' ) : '';
			}
			$api_url  = array_key_exists( $issite.'api_url', $prosolwpclient_api_config ) ? esc_url( $prosolwpclient_api_config[$issite.'api_url'] ) : '';
			//$pass = base64_decode($api_pass);
			if ( $api_user == '' || $api_pass == '' || $api_url == '' ) {
				return false;
			} else {
				return true;
			}
		}

		public static function proSol_getApiConfig($issite = '') {
			$prosolwpclient_api_config = get_option( 'prosolwpclient_api_config', array() );

			if ( $prosolwpclient_api_config != '' ) {
				$prosolwpclient_api_config = maybe_unserialize( $prosolwpclient_api_config );
			}

			$api_user = array_key_exists( $issite.'api_user', $prosolwpclient_api_config ) ? sanitize_text_field( $prosolwpclient_api_config[$issite.'api_user'] ) : '';
			//$api_pass = array_key_exists( 'api_pass', $prosolwpclient_api_config ) ?  sanitize_text_field(base64_decode($prosolwpclient_api_config['api_pass'] )) : '';
			if(get_option('prosolwpclient_isnewapi') == 2){
				$api_pass = array_key_exists( $issite.'api_pass', $prosolwpclient_api_config ) ?  crypt_customv2($prosolwpclient_api_config[$issite.'api_pass'], 'd'  ) : '';
			}else{
				$api_pass = array_key_exists( $issite.'api_pass', $prosolwpclient_api_config ) ? crypt_custom($prosolwpclient_api_config[$issite.'api_pass'], 'd' ) : '';
			}
			$api_url  = array_key_exists( $issite.'api_url', $prosolwpclient_api_config ) ? esc_url( $prosolwpclient_api_config[$issite.'api_url'] ) : '';
			
			$api_config['api_user'] = $api_user;
			$api_config['api_pass'] = $api_pass;
			$api_config['api_url']  = rtrim( $api_url, '/' ) . '/go/api/';

			return $api_config;
		}

		/**
		 * Clear log activity
		 *
		 * @param int $clear
		 */
		public static function proSol_clearLog( $clear = 1 ) {
			global $wpdb;global $prosol_prefix;
			$table_ps_logs_activity = $prosol_prefix . 'logs_activity';
			$table_ps_users         = $wpdb->prefix . 'users';

			$selsite='0';
			if(isset($_COOKIE['selsite'])){
				$selsite=$_COOKIE['selsite']==0 ? '0' : $_COOKIE['selsite'];
			}
			
			$output = '';

			if ( intval( $clear ) == 1 ) {
				$wpdb->query( "DELETE FROM $table_ps_logs_activity WHERE site_id='$selsite' " );
				
				CBXProSolWpClient_TableHelper::proSol_activityTableInsertion( esc_html__( 'Activity log cleared', 'prosolwpclient' ), 'clear' );
			}

			$join = " JOIN $table_ps_users users ON users.ID = log.add_by ";
			$where = " WHERE log.site_id='$selsite' ";
			$sql_select = "SELECT log.*, users.display_name FROM $table_ps_logs_activity as log ";

			$page    = 1;
			$perpage = 20;

			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;

			$order_by     = 'log.id';
			$order        = 'desc';
			$sortingOrder = " ORDER BY $order_by $order ";

			$logs_activity_data = $wpdb->get_results( "$sql_select $join $where $sortingOrder $limit_sql", 'ARRAY_A' );

			foreach ( $logs_activity_data as $index => $single_activity ) {
				$activity_msg = '';
				if ( $single_activity['activity_type'] == 'sync' ) {
					$activity_msg = '<strong>' . ucfirst( stripslashes( $single_activity['activity_msg'] ) ) . '</strong>' . ' table ' . '<strong>synced</strong>';
				}
				if ( $single_activity['activity_type'] == 'clear' ) {
					$activity_msg = ucfirst( stripslashes( $single_activity['activity_msg'] ) );
				}

				if ( current_user_can( 'edit_user', $single_activity['add_by'] ) ) {
					$add_by = '<a target="_blank" href="' . get_edit_user_link( $single_activity['add_by'] ) . '">' . stripslashes( $single_activity['display_name'] ) . '</a>';
				} else {
					$add_by = '<a href="#" target="_blank"' . stripslashes( $single_activity['display_name'] ) . '</a>';
				}

				$output .= '<li>' . $activity_msg . esc_html__( ' at ', 'prosolwpclient' ) . CBXProSolWpClient_Helper::proSol_dateReadableFormat( $single_activity['add_date'] ) . '
                                            ' . esc_html__( ' by ', 'prosolwpclient' ) . $add_by . '
                                        </li>';
			}

			return $output;
		}

		/**
		 * Set message to session
		 *
		 * @param        $message
		 * @param string $error
		 */
		public static function proSol_setSessionData( $message, $error = 'info' ) {


			$messages   = isset( $_SESSION['prosolwpclient_notices'] ) ? $_SESSION['prosolwpclient_notices'] : array();
			$messages[] = array(
				'class'   => $error,
				'message' => $message
			);

			$_SESSION['prosolwpclient_notices'] = $messages;
		}

		/**
		 * get messages from session
		 */
		public static function proSol_getSessionData() {

			$messages = array();

			if ( isset( $_SESSION['prosolwpclient_notices'] ) ) {
				$messages = $_SESSION['prosolwpclient_notices'];
				unset( $_SESSION['prosolwpclient_notices'] );
			}

			return $messages;
		}

		/**
		 * @return array
		 */
		public static function proSol_allTablesArr() {
			$all_tables_arr = array(
				'setting'            => 'Setting',
				'jobstamp'			 => 'Jobstamp',	
				'jobs'				 => 'Jobs',	
				'country'            => 'Country',
				'office'             => 'Office',
				'agent'              => 'Agent',
				'workpermit'         => 'Work Permit',
				'staypermit'         => 'Stay Permit',
				'availability'       => 'Availability',
				'federal'            => 'Federal',
				'marital'            => 'Marital',
				'title'              => 'Title',
				'skillgroup'         => 'Skill Group',
				'skill'              => 'Skill',
				'skillrate'          => 'Skill Rate',
				'professiongroup'    => 'Profession Group',
				'profession'         => 'Profession',
				'education'          => 'Education',
				'educationlookup'    => 'Education Lookup',
				'recruitmentsource'  => 'Recruitment Source',
				'qualification'      => 'Qualification',
				'qualificationeval'  => 'Qualification Eval',
				'filecategoryemp'    => 'File Category Emp',
				'contract'           => 'Contract',
				'employment'         => 'Employment',
				'experienceposition' => 'Experience Position',
				'operationarea'      => 'Operation Area',
				'nace'               => 'Nace',
				'isced'              => 'Isced',
				'customfields'       => 'Customfields',
				'worktime'      	 => 'Work Time',
				'jobcustomfields'    => 'jobcustomfields'
			);

			return apply_filters( 'prosolwpclient_table_names', $all_tables_arr );
		}

		/**
		 * List all global option name with prefix prosolwpclient_
		 */
		public static function proSol_getAllOptionNames() {
			global $wpdb;global $prosol_prefix;

			$prefix       = 'prosolwpclient_';
			$option_names = $wpdb->get_results( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'", ARRAY_A );

			return apply_filters( 'prosolwpclient_option_names', $option_names );
		}

		/**
		 * @param $timestamp
		 *
		 * @return false|string
		 */
		public static function proSol_dateReadableFormat( $timestamp ) {
			return date( 'M j, Y h:i a', strtotime( $timestamp ) );
		}

		/**
		 * @return array
		 */
		public static function proSol_fifteenCustomFieldsArr() {
			$fifteen_custom_fields_arr = array(
				1  => 'Field 1',
				2  => 'Field 2',
				3  => 'Field 3',
				4  => 'Field 4',
				5  => 'Field 5',
				6  => 'Field 6',
				7  => 'Field 7',
				8  => 'Field 8',
				9  => 'Field 9',
				10 => 'Field 10',
				11 => 'Field 11',
				12 => 'Field 12',
				13 => 'Field 13',
				14 => 'Field 14',
				15 => 'Field 15',
				16 => 'Field 16',
                17 => 'Field 17',
                18 => 'Field 18',
                19 => 'Field 19',
                20 => 'Field 20',
                21 => 'Field 21',
                22 => 'Field 22',
                23 => 'Field 23',
                24 => 'Field 24',
                25 => 'Field 25',
                26 => 'Field 26',
                27 => 'Field 27',
                28 => 'Field 28',
                29 => 'Field 29',
                30 => 'Field 30',
			);

			return $fifteen_custom_fields_arr;
		}

		public static function proSol_formTabKeyNames() {
			$tab_key_names = array(
				'1' => esc_html__( 'Personal Data', 'prosolwpclient' ),
				'2' => esc_html__( 'Education', 'prosolwpclient' ),
				'3' => esc_html__( 'Work Experience', 'prosolwpclient' ),
				'4' => esc_html__( 'Expertise', 'prosolwpclient' ),
				'5' => esc_html__( 'Side Dishes', 'prosolwpclient' ),
				'6' => esc_html__( 'Others', 'prosolwpclient' )
			);

			return $tab_key_names;
		}

		/**
		 * convert byte to mb or GB or others
		 * @param     $bytes
		 * @param int $precision
		 *
		 * @return string
		 */
		public static function proSol_formatBytesToMB($bytes, $precision = 2) {
			$base = log($bytes, 1024);
			$suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

			return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
		}

		/**
		 * handle font name from setting
		 * @return string
		 */
		public static function proSol_getFontName($issite='') {
			$prosoldes = get_option('prosolwpclient_designtemplate');
			$prosoldesfont=$prosoldes[$issite.'desfont'];
			switch ($prosoldesfont) {
				case "0":
					return "";
				case "1":
					return "Georgia, serif";
				case "2":
					return "'Palatino Linotype', 'Book Antiqua', Palatino, serif";
				case "3":
					return "'Times New Roman', Times, serif";
				case "4":
					return "Arial, Helvetica, sans-serif";	
				case "5":
					return "'Arial Black', Gadget, sans-serif";
				case "6":
					return "'Comic Sans MS', cursive, sans-serif";
				case "7":
					return "Impact, Charcoal, sans-serif";
				case "8":
					return "'Lucida Sans Unicode', 'Lucida Grande', sans-serif";
				case "9":
					return "Tahoma, Geneva, sans-serif";
				case "10":
					return "'Trebuchet MS', Helvetica, sans-serif";
				case "11":
					return "Verdana, Geneva, sans-serif";
				case "12":
					return "'Courier New', Courier, monospace";	
				case "13":
					return "'Lucida Console', Monaco, monospace";		
			}
		}	

		/**
		 * handle color shade
		 * @param    $color
		 * @param 	 $percent
		 * @return string
		 */
		public static function shadeColor($color, $percent) {
			$num = base_convert(substr($color, 1), 16, 10);
			$amt = round(1.5 * $percent);
			$r = ($num >> 16) + $amt;
			$b = ($num >> 8 & 0x00ff) + $amt;
			$g = ($num & 0x0000ff) + $amt;
			
			return '#'.substr(base_convert(0x1000000 + ($r<255?$r<1?0:$r:255)*0x10000 + ($b<255?$b<1?0:$b:255)*0x100 + ($g<255?$g<1?0:$g:255), 10, 16), 1);
		}

	}