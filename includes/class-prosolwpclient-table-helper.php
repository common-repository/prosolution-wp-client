<?php

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<?php

	class CBXProSolWpClient_TableHelper {
		/**
		 * @param string $task
		 *
		 * @return mixed
		 */
		public static function proSol_apiConfig( $issite='', $wpway = true ) {
			$header_info               = array();
			$prosolwpclient_api_config = get_option( 'prosolwpclient_api_config', array() );
			
			if ( $prosolwpclient_api_config != '' ) {
				$prosolwpclient_api_config = maybe_unserialize( $prosolwpclient_api_config );
			}

			$api_user = array_key_exists( $issite.'api_user', $prosolwpclient_api_config ) ? $prosolwpclient_api_config[$issite.'api_user'] : '';
			//$api_pass = array_key_exists( 'api_pass', $prosolwpclient_api_config ) ? base64_decode($prosolwpclient_api_config['api_pass']) : '';
			if(get_option('prosolwpclient_isnewapi') == 2){
				$api_pass = array_key_exists( $issite.'api_pass', $prosolwpclient_api_config ) ? crypt_customv2($prosolwpclient_api_config[$issite.'api_pass'], 'd' ) : '';
			}else{
				$api_pass = array_key_exists( $issite.'api_pass', $prosolwpclient_api_config ) ? crypt_custom($prosolwpclient_api_config[$issite.'api_pass'], 'd' ) : '';
			}
			$api_url  = array_key_exists( $issite.'api_url', $prosolwpclient_api_config ) ? rtrim( esc_url( $prosolwpclient_api_config[$issite.'api_url'] ), '/' ) . '/go/api/' : '';
			
			if ( $api_user != '' && $api_pass != '' && $api_url != '' ) {
				$stamp = gmDate( "Y-m-d H:i:s O" );

				// SHA-Hash erstellen aus Zeitstempel und dem Access-Key
				$signature = hash( 'sha256', $stamp . '::' . $api_pass );

				if ( $wpway ) {
					$header_info = array(
						'prosol-date'   => $stamp,
						'Authorization' => 'WorkExpertAPI ' . $api_user . ':' . $signature,
					);
				} else {
					$header_info = array(
						'prosol-date:' . $stamp,
						'Authorization:' . 'WorkExpertAPI ' . $api_user . ':' . $signature,
					);
				}
			}

			return $header_info;
		}

		public static function proSol_getAuthHeader() {
			static $Header = null;

			if ( empty( $Header ) ) {
				// Aktuellen Zeitstempel im richtigen Format holen
				$stamp = gmDate( "Y-m-d H:i:s O" );

				// SHA-Hash erstellen aus Zeitstempel und dem Access-Key
				$signature = hash( 'sha256', $stamp . '::' . '421921B162AB5EC0B572F0705456AE57743A68B3310BC524ED34B0F3C6E5059F' );

				$Header = array(
					'prosol-date:' . $stamp,
					'Authorization:' . 'WorkExpertAPI ' . 'APIUser' . ':' . $signature,
				);
			}

			return $Header;
		}

		/**
		 * @param string $task
		 * @param string $table_name
		 *
		 * @return mixed
		 */
		public static function proSol_apiActivity( $table_name = '', $synctype = 'all' ) {
			$frontend_settingPage = get_option( 'prosolwpclient_frontend' );
			$response_data = new stdClass();

			$siteidfordb = is_null($_COOKIE['selsite']) ? 0 : $_COOKIE['selsite'];
			$selsite='';
			if(isset($_COOKIE['selsite'])){
				$selsite=$_COOKIE['selsite']==0 ? '' : 'site'.$_COOKIE['selsite'].'_';
			} 
			$header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig($selsite);
			$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($selsite);
			$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($selsite);   
	
			if ( is_array( $header_info ) && sizeof( $header_info ) > 0 && $is_api_setup ) {

				if ( $table_name == 'marital' ) {
					$table_name = 'maritalstatus';
				}
				if ( $table_name == 'skillrate' ) {
					$table_name = 'skillgrouprating';
				}
				if ( $table_name == 'education' ) {
					$table_name = 'educationtype';
				}
				if ( $table_name == 'educationlookup' ) {
					$table_name = 'educationtypedetail';
				}
				if( $table_name == 'jobstamp'){
					$table_name = '';
				}

				if($table_name == 'jobs'){ // project 1440
					$frontend_settingPage = get_option( 'prosolwpclient_frontend' );
					$chkclientlist =  $frontend_settingPage[$issite.'client_list'];
					
					if($frontend_settingPage[$issite.'enable_recruitment'] == 'on'){
						$api_location='recruitment/';
					} else{
						$api_location='application/';
					}

					if($synctype=="all"){
						$safe_data = array(
							'clientidlist' 		=> $chkclientlist
						);						
					} else{
						global $wpdb;global $prosol_prefix;
						$ps_table_jobstamp = $prosol_prefix . 'jobstamp';
						$modifydate = $wpdb->get_var("SELECT add_date FROM $ps_table_jobstamp WHERE site_id in ($siteidfordb) ");
						// $modifydate = '2022-03-01 00:00:00'; // for testing
						$safe_data = array(
							'clientidlist' 		=> $chkclientlist,
							'modifydate'        => $modifydate,
							'includeExpired'	=> 1
						);
					} 
					$api_body  = array( "param" => json_encode( $safe_data ));

					$response    = wp_remote_post( $api_config['api_url'] .''. $api_location .'joblist?limitrows=500', array(
						'headers'        => $header_info,
						'body'           => $api_body
					) ); 
				} else if($table_name != '' && $table_name != 'jobs'){ // default table beside jobs
					$response = wp_remote_get( $api_config['api_url'] . 'system/list/' . $table_name, array( 'headers' => $header_info ) );
				}
				//var_dump($response);
				if ( ! is_wp_error( $response ) ) {
					$response_data = json_decode( $response['body'] )->data;
					
					// project 1440, insert /jobdetail into table Jobs in here
					if($table_name == 'jobs'){
						$response_stamp = json_decode( $response['body'] )->JOBUNTIL;
						$jobid_arr = $response_data->jobid;
						
						// clearing table jobs move here
						global $wpdb;global $prosol_prefix;
						$ps_table_jobs = $prosol_prefix . 'jobs'; 
						$jobid_list = implode(",", $jobid_arr); 

						if($synctype == 'all'){
							$wpdb->query( "DELETE FROM $ps_table_jobs WHERE site_id= $siteidfordb " );
						}else{ 							
							$wpdb->query( "DELETE FROM $ps_table_jobs WHERE site_id= $siteidfordb AND jobid in ($jobid_list) " );	
						}
						//var_dump($response);
						$chkerror=0;
						
						foreach ( $jobid_arr as $index => $jobid ) { 
							$response = wp_remote_get( $api_config['api_url'] . $api_location . 'jobdetail/' . $jobid, array( 'headers' => $header_info ) );
							
							if ( ! is_wp_error( $response ) ) {
								$response_data = json_decode( $response['body'] )->data;
								
								// for table jobs call proSol_allTablesInsertion here, not like other table, different flow
								self::proSol_allTablesInsertion('jobs', $response_data, $synctype, $siteidfordb, $response_stamp );
							} else {
								$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
								$chkerror=1;
								break;
							} 
						}
						
						if($chkerror != 1){ 
							if(!is_null($response_data)){  
								self::proSol_jobstampInsertion($siteidfordb,$response_stamp);
								// add wp scheduler  
								self::prosol_dailytask($siteidfordb);
								
							}
						}
					}

				} else {
					$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
				} 
			} else {
				$response_data = esc_html__( 'Api config invalid', 'prosolwpclient' );
			}
			 
		// ini_set('xdebug.var_display_max_depth', -1);
		// ini_set('xdebug.var_display_max_children', -1);
		// ini_set('xdebug.var_display_max_data', -1);
		// var_dump($response);
			return $response_data;
		}

		public static function proSol_cleardatasites() {
			$remsite='';
			
			if(isset($_COOKIE['removesite'])){
				$remsite=$_COOKIE['removesite'];
				$tablename_arr =  CBXProSolWpClient_Helper::proSol_allTablesArr();
				global $wpdb;global $prosol_prefix;
				foreach($tablename_arr as $tablename => $label){
					$ps_table_name = $prosol_prefix.$tablename;
					$wpdb->query( "DELETE FROM $ps_table_name WHERE site_id in ($remsite) " );
				}
			}


			return $remsite;
		}

		// project 1440, set custom interval
		function proSol_cron_interval( $schedules ) { 
			$schedules['two_hours'] = array(
				'interval' => 7200,
				'display'  => esc_html__( 'Every Two Hours', 'prosolwpclient' ) );
			$schedules['three_minutes'] = array(
				'interval' => 180,
				'display'  => esc_html__( 'Every Three Minutes', 'prosolwpclient' ) );	
			return $schedules;
		}

		// project 1440, dailytask table jobs
		public function proSol_dailytask_tableJobs($siteidfordb = "0"){
		
			$selsite=$siteidfordb=="0" ? '' : 'site'.$siteidfordb.'_';
			$frontend_settingPage = get_option( 'prosolwpclient_frontend' );
			$chkclientlist =  $frontend_settingPage[$selsite.'client_list'];

			if($frontend_settingPage[$selsite.'enable_recruitment'] == 'on'){
				$api_location='recruitment/';

				global $wpdb;global $prosol_prefix;
				$ps_table_jobstamp = $prosol_prefix . 'jobstamp'; 
				$modifydate = $wpdb->get_var("SELECT add_date FROM $ps_table_jobstamp WHERE site_id in ($siteidfordb) ");
				
				// no need run for the first time click button SynchChanges, set duration 10 minutes should be more than enough + 600
				$safe_data = array(
					'clientidlist' 		=> $chkclientlist,
					'modifydate'        => $modifydate,
					'includeExpired'	=> 1
				);  

				$header_info = self::proSol_apiConfig($selsite);  
				$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($selsite);
				$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($selsite); 
					
				$api_body  = array( "param" => json_encode( $safe_data ));

				if($is_api_setup){
					$response    = wp_remote_post( $api_config['api_url'] .''. $api_location .'joblist?limitrows=500', array(
						'headers'        => $header_info,
						'body'           => $api_body
					) );
					
					if ( ! is_wp_error( $response ) ) {
						$response_data = json_decode( $response['body'] )->data;
						$response_stamp = json_decode( $response['body'] )->JOBUNTIL;
						$jobid_arr = $response_data->jobid;
						
						// clearing table jobs move here
						$ps_table_jobs = $prosol_prefix . 'jobs'; 
						$jobid_list = implode(",", $jobid_arr); 

						$wpdb->query( "DELETE FROM $ps_table_jobs WHERE site_id= $siteidfordb AND jobid in ($jobid_list) " );	
						
						$chkerror=0;
						foreach ( $jobid_arr as $index => $jobid ) { 
							$response = wp_remote_get( $api_config['api_url'] . $api_location . 'jobdetail/' . $jobid, array( 'headers' => $header_info ) );
							
							if ( ! is_wp_error( $response ) ) {
								$response_data = json_decode( $response['body'] )->data;
								
								self::proSol_allTablesInsertion('jobs', $response_data, 'changes', $siteidfordb, $response_stamp );
							} else{
								$chkerror=1;
								break;
							} 
						} 
						if($chkerror == 0){
							self::proSol_jobstampInsertion($siteidfordb,$response_stamp);
						}
					}  	
				} else{
					self::proSol_activityTableInsertion( 'error API config', 'wp_ajax_proSol_dailytask_tableJobs', $selsite );	
				}
					
			}  	 
			
		}

		// project 1440, init dailytask
		public static function prosol_dailytask($siteidfordb) {
			$startJobs= "05:00:00";
			// $startJobs= "04:00:00";  // for testing
			$endJobs= "19:00:00"; 
			$argsjob = array($siteidfordb); 
			
			if(time() >= strtotime($startJobs) && time() <= strtotime($endJobs)){
				if ( ! wp_next_scheduled( 'wp_ajax_proSol_dailytask_tableJobs', $argsjob ) ) { 
					 wp_schedule_event( time(), 'two_hours', 'wp_ajax_proSol_dailytask_tableJobs', $argsjob );
				//	wp_schedule_event( time(), 'three_minutes', 'wp_ajax_proSol_dailytask_tableJobs', $argsjob ); // for testing
				}
			} else{
				wp_unschedule_hook('wp_ajax_proSol_dailytask_tableJobs',  $argsjob);	
			}				
		}

		/**
		 * @param string $table_name
		 * @param array  $response_data
		 */
		public static function 
		proSol_allTablesInsertion( $table_name = '', $response_data = array(), $synctypejobs = '', $selsite='0', $response_stamp='') {

			global $wpdb;global $prosol_prefix;

			if ( array_key_exists( $table_name, CBXProSolWpClient_Helper::proSol_allTablesArr() ) ) {
				$ps_table_name = $prosol_prefix . $table_name;
				
				if(isset($_COOKIE['selsite']) && $selsite==0){
					$selsite=$_COOKIE['selsite']==0 ? '0' : $_COOKIE['selsite'];
					$issite=$_COOKIE['selsite']==0 ? '' : 'site'.$_COOKIE['selsite'].'_';
				} else if($selsite!=0){
					$issite='site'.$selslite.'_';
				} else{
					$selsite=0;
					$issite='';
				}	 
				
				// project 1440, process delete jobs moved after calling API joblist/
				if ( $table_name != 'setting' && $table_name !='jobs' && $table_name != 'jobstamp') {
					$wpdb->query( "DELETE FROM $ps_table_name WHERE site_id= $selsite " );
				} 

				if ( $table_name == 'setting' ) {
					$syncstamp_exist = $wpdb->get_var( $wpdb->prepare( "SELECT value FROM $ps_table_name WHERE name = %s AND site_id= %s", 'syncstamp', $selsite ) );
						
					if ( is_null( $syncstamp_exist ) ) { 
						$wpdb->insert(
							$ps_table_name,
							array(
								'name'  => 'syncstamp',
								'value' => time(),
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}   
				}
				
				// project 1440, only insert from function apiActivity
				if ( $table_name == 'jobs' && $synctypejobs != '') {	
					$jobid_arr = $response_data->jobid;
					$jobname_arr = $response_data ->jobname;				
					$jobproject_id_arr = $response_data ->jobproject_id;
					$jobproject_name_arr = $response_data ->jobproject_name;
					$jobstartdate_arr = $response_data ->jobstartdate;				
					$empgroup_id_arr = $response_data ->empgroup_id;
					$empgroup_name_arr = $response_data ->empgroup_name;
					$empgroup_type_arr = $response_data ->empgroup_type;
					$federalid_arr = $response_data ->federalid;
					$federalname_arr = $response_data ->federalname;
					$categoryid_arr = $response_data ->categoryid;
					$categoryname_arr = $response_data ->categoryname;
					$customformat_id_arr = $response_data ->customformat_id;
					$customformat_name_arr = $response_data ->customformat_name;
					$countryid_arr = $response_data ->countryid;
					$countryname_arr = $response_data ->countryname;
					$officeid_arr = $response_data ->officeid;
					$officename_arr = $response_data ->officename;
					$worktimeid_arr = $response_data ->worktimeid;
					$worktimename_arr = $response_data ->worktimename;
					$workingplace_arr = $response_data ->workingplace;
					$zipcode_arr = $response_data ->zipcode;
					$isced_arr = $response_data ->isced;
					$isced_name_arr = $response_data ->isced_name;
					$max_distance_arr = $response_data ->max_distance;
					$exp_year_arr = $response_data ->exp_year;
					$jobrefid_arr = $response_data ->jobrefid;
					$custrefid_arr = $response_data ->custrefid;
					$custreflogo_arr = $response_data ->custreflogo;
					$agentid_arr = $response_data ->agentid;
					$agentname_arr = $response_data ->agentname;
					$showagentphoto_arr = $response_data ->showagentphoto;
					$showsignature_arr = $response_data ->showsignature;
					$showcustname_arr = $response_data ->showcustname;
					$showcustlogo_arr = $response_data ->showcustlogo;
					$qualificationid_arr = $response_data ->qualificationid;
					$untildate_arr = $response_data ->untildate;
					$publishdate_arr = $response_data ->publishdate;
					$salarytext_arr = $response_data ->salarytext;

					$profession_arr = $response_data ->profession;
					$skills_arr = $response_data ->skills;
					$question_arr = $response_data ->question;
					$portal_arr = $response_data ->portal;
					$customer_arr = $response_data ->customer;
					$recruitlink_arr = $response_data ->recruitlink;

					$site_id_arr = $response_data ->site_id;
					ini_set('xdebug.var_display_max_depth', -1);
					ini_set('xdebug.var_display_max_children', -1);
					ini_set('xdebug.var_display_max_data', -1);
					foreach ( $jobid_arr as $index => $jobid ) { 
						$profession_serialize = json_encode($profession_arr[0]) != '""' ?  json_encode($profession_arr[0]) : '';
						$skills_serialize = json_encode($skills_arr[0]) != '""' ?  json_encode($skills_arr[0]) : '';
						$question_serialize = json_encode($question_arr[0]) != '""' ?  json_encode($question_arr[0]) : '';
						$portal_serialize = json_encode($portal_arr[0]) != '""' ?  json_encode($portal_arr[0]) : '';
						$customer_serialize = json_encode($customer_arr[0]) != '""' ?  json_encode($customer_arr[0]) : '';
						$recruitlink_serialize = json_encode($recruitlink_arr[0]) != '""' ?  json_encode($recruitlink_arr[0]) : '';

						$wpdb->insert(
							$ps_table_name,
							array(
								'jobid' => $jobid,
								'jobname' => isset( $jobname_arr[$index] ) ? $jobname_arr[ $index ] : '',
								'jobproject_id' => isset( $jobproject_id_arr[$index] ) ? $jobproject_id_arr[ $index ] : 0,
								'jobproject_name' => isset( $jobproject_name_arr[$index] ) ? $jobproject_name_arr[ $index ] : '',
								'jobstartdate' => isset( $jobstartdate_arr[$index] ) ? $jobstartdate_arr[ $index ] : '',
								'federalid' => isset( $federalid_arr[$index] ) ? $federalid_arr[ $index ] : 0,
								'federalname' => isset( $federalname_arr[$index] ) ? $federalname_arr[ $index ] : '',
								'empgroup_id' => isset( $empgroup_id_arr[$index] ) ? $empgroup_id_arr[ $index ] : 0,
								'empgroup_name' => isset( $empgroup_name_arr[$index] ) ? $empgroup_name_arr[ $index ] : '',
								'empgroup_type' => isset( $empgroup_type_arr[$index] ) ? $empgroup_type_arr[ $index ] : '',
								'categoryid' => isset( $categoryid_arr[$index] ) ? $categoryid_arr[ $index ] : 0,
								'categoryname' => isset( $categoryname_arr[$index] ) ? $categoryname_arr[ $index ] : '',
								'customformat_id' => isset( $customformat_id_arr[$index] ) ? $customformat_id_arr[ $index ] : 0,
								'customformat_name' => isset( $customformat_name_arr[$index] ) ? $customformat_name_arr[ $index ] : '',
								'countryid' => isset( $countryid_arr[$index] ) ? $countryid_arr[ $index ] : '',
								'countryname' => isset( $countryname_arr[$index] ) ? $countryname_arr[ $index ] : '',
								'officeid' => isset( $officeid_arr[$index] ) ? $officeid_arr[ $index ] : 0,
								'officename' => isset( $officename_arr[$index] ) ? $officename_arr[ $index ] : '',
								'worktimeid' => isset( $worktimeid_arr[$index] ) ? $worktimeid_arr[ $index ] : 0,
								'worktimename' => isset( $worktimename_arr[$index] ) ? $worktimename_arr[ $index ] : '',
								'workingplace' => isset( $workingplace_arr[$index] ) ? $workingplace_arr[ $index ] : '',
								'zipcode' => isset( $zipcode_arr[$index] ) ? $zipcode_arr[ $index ] : '',
								'isced' => isset( $isced_arr[$index] ) ? $isced_arr[ $index ] : 0,
								'isced_name' => isset( $isced_name_arr[$index] ) ? $isced_name_arr[ $index ] : '',
								'max_distance' => isset( $max_distance_arr[$index] ) ? $max_distance_arr[ $index ] : '',
								'exp_year' => isset( $exp_year_arr[$index] ) ? $exp_year_arr[ $index ] : '',
								'jobrefid' => isset( $jobrefid_arr[$index] ) ? $jobrefid_arr[ $index ] : '',
								'custrefid' => isset( $custrefid_arr[$index] ) ? $custrefid_arr[ $index ] : '',
								'custreflogo' => isset( $custreflogo_arr[$index] ) ? $custreflogo_arr[ $index ] : '',
								'agentid' => isset( $agentid_arr[$index] ) ? $agentid_arr[ $index ] : '',
								'agentname' => isset( $agentname_arr[$index] ) ? $agentname_arr[ $index ] : '',	
								'showagentphoto' => isset( $showagentphoto_arr[$index] ) ? $showagentphoto_arr[ $index ] : '',
								'showsignature' => isset( $showsignature_arr[$index] ) ? $showsignature_arr[ $index ] : '',	
								'showcustname' => isset( $showcustname_arr[$index] ) ? $showcustname_arr[ $index ] : '',
								'showcustlogo' => isset( $showcustlogo_arr[$index] ) ? $showcustlogo_arr[ $index ] : '',	
								'qualificationid' => isset( $qualificationid_arr[$index] ) ? $qualificationid_arr[ $index ] : '',	
								'untildate' => isset( $untildate_arr[$index] ) ? $untildate_arr[ $index ] : '', 
								'publishdate' => isset( $publishdate_arr[$index] ) ? $publishdate_arr[ $index ] : '',
								'salarytext' => isset( $salarytext_arr[$index] ) ? $salarytext_arr[ $index ] : '',
								'profession' => isset( $profession_serialize ) ? $profession_serialize : '',
								'skills' => isset( $skills_serialize ) ? $skills_serialize : '',
								'question' => isset( $question_serialize ) ? $question_serialize : '',
								'portal' => isset( $portal_serialize ) ? $portal_serialize : '',
								'customer' => isset( $customer_serialize ) ? $customer_serialize : '',
								'recruitlink' => isset( $recruitlink_serialize ) ? $recruitlink_serialize : '',
								'site_id' => $selsite
							),
							array(
								'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'
							)
						);
						
						for($x=1;$x<=30;$x++){
							$keyfield="textfield_".$x;
							$keylabel="textfieldlabel_".$x; 
							
							if(property_exists($response_data,$keyfield)){
								
								$valuefield=$response_data->$keyfield;
								$valuelabel=$response_data->$keylabel; 
								$wpdb->update(
									$ps_table_name,
									array(
										$keyfield => $valuefield[0],
										$keylabel => $valuelabel[0]	
									),
									array(
										'jobid' => $jobid_arr[0]
									),
									array(
										'%s','%s'
									),
									array(
										'%s'
									)
								);		 
							}
						}

					}  
					
					// also delete jobs with untildate < now()  
					if($synctypejobs == 'changes'){
						$qExpired = $wpdb->get_results( "SELECT jobid, untildate FROM $ps_table_name WHERE site_id= $selsite AND (untildate <> '') ", ARRAY_A );
						$listexp='';
						//var_dump($qExpired);
						foreach ( $qExpired as $index => $exjob ) { 
							$timejob=strtotime($exjob['untildate']);
							$datejob=date('Y-m-d', $timejob);
							//var_dump($datejob);
							//var_dump($datejob < current_time('mysql'));
							if($datejob < current_time('mysql')){
								$idjob=$exjob['jobid'];
								$listexp= $listexp=='' ? $idjob : $listexp.",".$idjob;
							} 
						}
						$wpdb->query( "DELETE FROM $ps_table_name WHERE site_id= $selsite AND jobid in ($listexp) " );
					}//die;
					
				}  				

				if ( $table_name == 'country' ) {
					$country_code_arr = $response_data->id;
					$name_arr         = $response_data->name;
					
					foreach ( $country_code_arr as $index => $country_code ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'countryCode' => $country_code,
								'name'        => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}

				if ( $table_name == 'office' ) {
					$zip_arr        = $response_data->zip;
					$mandant_arr    = $response_data->mandant;
					$street_arr     = $response_data->street;
					$email_arr      = $response_data->email;
					$name2_arr      = $response_data->name2;
					$name_arr       = $response_data->name;
					$customtext_arr = $response_data->customtext;
					$city_arr       = $response_data->city;
					$homepage_arr   = $response_data->homepage;
					$id_arr         = $response_data->id;
					$phone2_arr     = $response_data->phone2;
					$phone1_arr     = $response_data->phone1;
					$fax_arr        = $response_data->fax;
					
					foreach ( $id_arr as $index => $office_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'officeId'   => $office_id,
								'mandant'    => isset( $mandant_arr[ $index ] ) ? $mandant_arr[ $index ] : '',
								'name'       => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'name2'      => isset( $name2_arr[ $index ] ) ? $name2_arr[ $index ] : '',
								'street'     => isset( $street_arr[ $index ] ) ? $street_arr[ $index ] : '',
								'zip'        => isset( $zip_arr[ $index ] ) ? $zip_arr[ $index ] : '',
								'city'       => isset( $city_arr[ $index ] ) ? $city_arr[ $index ] : '',
								'phone1'     => isset( $phone1_arr[ $index ] ) ? $phone1_arr[ $index ] : '',
								'phone2'     => isset( $phone2_arr[ $index ] ) ? $phone2_arr[ $index ] : '',
								'fax'        => isset( $fax_arr[ $index ] ) ? $fax_arr[ $index ] : '',
								'email'      => isset( $email_arr[ $index ] ) ? $email_arr[ $index ] : '',
								'homepage'   => isset( $homepage_arr[ $index ] ) ? $homepage_arr[ $index ] : '',
								'customtext' => isset( $customtext_arr[ $index ] ) ? $customtext_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);					
					}
					
				}
				
				if ( $table_name == 'agent' ) {
					$position_arr    = $response_data->position;
					$phonemobile_arr = $response_data->phonemobile;
					$gender_arr      = $response_data->gender;
					$id_arr          = $response_data->id;
					$phone2_arr      = $response_data->phone2;
					$email_arr       = $response_data->email;
					$phone1_arr      = $response_data->phone1;
					$name_arr        = $response_data->name;
					$customtext_arr  = $response_data->customtext;

					foreach ( $id_arr as $index => $agent_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'agentId'     => $agent_id,
								'name'        => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'gender'      => isset( $gender_arr[ $index ] ) ? $gender_arr[ $index ] : '',
								'position'    => isset( $position_arr[ $index ] ) ? $position_arr[ $index ] : '',
								'email'       => isset( $email_arr[ $index ] ) ? $email_arr[ $index ] : '',
								'phone1'      => isset( $phone1_arr[ $index ] ) ? $phone1_arr[ $index ] : '',
								'phone2'      => isset( $phone2_arr[ $index ] ) ? $phone2_arr[ $index ] : '',
								'phonemobile' => isset( $phonemobile_arr[ $index ] ) ? $phonemobile_arr[ $index ] : '',
								'customtext'  => isset( $customtext_arr[ $index ] ) ? $customtext_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}

				if ( $table_name == 'workpermit' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $workpermit_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'workpermitId' => $workpermit_id,
								'name'         => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'staypermit' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $staypermit_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'staypermitId' => $staypermit_id,
								'name'         => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'availability' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $availability_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'availabilityId' => $availability_id,
								'name'           => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'federal' ) {
					$country_arr = $response_data->country;
					$id_arr      = $response_data->id;
					$name_arr    = $response_data->name;

					foreach ( $id_arr as $index => $federal_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'federalId'   => $federal_id,
								'name'        => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'countryCode' => isset( $country_arr[ $index ] ) ? $country_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'marital' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $marital_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'maritalId' => $marital_id,
								'name'      => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'title' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $title_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'titleId' => $title_id,
								'name'    => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'skillgroup' ) {
					$id_arr                 = $response_data->id;
					$name_arr               = $response_data->name;
					$professiongroups_arr   = $response_data->professiongroupidlist;

					foreach ( $id_arr as $index => $skillgroup_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'skillgroupId'       => $skillgroup_id,
								'name'               => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'professiongroups'   => isset( $professiongroups_arr[ $index ] ) ? $professiongroups_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'skill' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;
					$grp_arr  = $response_data->grp;

					foreach ( $id_arr as $index => $skill_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'skillId'      => $skill_id,
								'name'         => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'skillgroupId' => isset( $grp_arr[ $index ] ) ? $grp_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'skillrate' ) {
					$grp_arr    = $response_data->grp;
					$name_arr   = $response_data->name;
					$rating_arr = $response_data->rating;

					foreach ( $rating_arr as $index => $rating_value ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'skillrateId'  => $index + 1,
								'value'        => $rating_value,
								'name'         => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'skillgroupId' => isset( $grp_arr[ $index ] ) ? $grp_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%d',
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'professiongroup' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $professiongroup_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'professiongroupId' => $professiongroup_id,
								'name'              => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'profession' ) {
					$grp_arr  = $response_data->grp;
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $profession_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'professionId'      => $profession_id,
								'name'              => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'professiongroupId' => isset( $grp_arr[ $index ] ) ? $grp_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'education' ) {
					$id_arr              = $response_data->id;
					$name_arr            = $response_data->name;
					$usedetaillookup_arr = $response_data->usedetaillookup;


					foreach ( $id_arr as $index => $educationId ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'educationId' => $educationId,
								'name'        => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'hasLookup'   => isset( $usedetaillookup_arr[ $index ] ) ? $usedetaillookup_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'educationlookup' ) {
					$id_arr          = $response_data->id;
					$name_arr        = $response_data->name;
					$educationid_arr = $response_data->educationid;

					foreach ( $id_arr as $index => $lookupId ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'lookupId'    => $lookupId,
								'name'        => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'educationId' => isset( $educationid_arr[ $index ] ) ? $educationid_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'recruitmentsource' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $recruitmentsourceId ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'recruitmentsourceId' => $recruitmentsourceId,
								'name'                => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'qualification' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $qualification_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'qualificationId' => $qualification_id,
								'name'            => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'qualificationeval' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $qualificationeval_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'qualificationevalId' => $qualificationeval_id,
								'name'                => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'filecategoryemp' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $filecategoryemp_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'filecategoryempId' => $filecategoryemp_id,
								'name'              => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'contract' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $contract_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'contractId' => $contract_id,
								'name'       => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'employment' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $employment_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'employmentId' => $employment_id,
								'name'         => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'experienceposition' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $experienceposition_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'experiencepositionId' => $experienceposition_id,
								'name'                 => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'operationarea' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $operationarea_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'operationareaId' => $operationarea_id,
								'name'            => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}

				if ( $table_name == 'nace' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $nace_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'naceId' => $nace_id,
								'name'   => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}
				if ( $table_name == 'isced' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;

					foreach ( $id_arr as $index => $isced_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'iscedId' => $isced_id,
								'name'    => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}					
				}

				if ( $table_name == 'customfields' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;
					$label_arr = $response_data->label;

					foreach ( $id_arr as $index => $customfields_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'customId' => $index+1,
								'customfieldsId' => $customfields_id,
								'name'    => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'label'    => isset( $label_arr[ $index ] ) ? $label_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}

				if ( $table_name == 'worktime' ) {
					$id_arr   = $response_data->id;
					$name_arr = $response_data->name;
					foreach ( $id_arr as $index => $worktime_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'worktimeId' => $worktime_id,
								'name'    => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						); 
					}
					

				}

				if ( $table_name == 'jobcustomfields' ) {
					$id_arr   = $response_data->customfield_id;
					$name_arr = $response_data->customfield_name;

					foreach ( $id_arr as $index => $customfields_id ) {
						$wpdb->insert(
							$ps_table_name,
							array(
								'customfield_ID' => $customfields_id,
								'customfield_name'    => isset( $name_arr[ $index ] ) ? $name_arr[ $index ] : '',
								'site_id' => $selsite
							),
							array(
								'%s',
								'%s',
								'%s'
							)
						);
					}
				}

				$pswp_sync_time_arr = get_option( 'prosolwpclient_sync_time', array() );
				if ( $pswp_sync_time_arr != '' ) {
					$pswp_sync_time_arr = maybe_unserialize( $pswp_sync_time_arr );
					
					// it seems doesn't work when cron do the job, keep the coding
					if( ($table_name == 'jobs') && ($response_stamp != '') ){
						$pswp_sync_time_arr[ $issite.$table_name ] = $response_stamp;
					} else{
						$pswp_sync_time_arr[ $issite.$table_name ] = current_time( 'mysql' );
					}
					update_option( 'prosolwpclient_sync_time', maybe_serialize( $pswp_sync_time_arr ) );
				}
				
				self::proSol_activityTableInsertion( $table_name, 'sync', $selsite, $response_stamp );
			}

		}

		/**
		 * @param string $activity_msg
		 * @param string $activity_type
		 */
		public static function proSol_activityTableInsertion( $activity_msg = '', $activity_type = '', $selsite ='0', $stampforJobs= '' ) {

			// if(isset($_COOKIE['selsite'])){
			// 	$selsite=$_COOKIE['selsite']==0 ? '0' : $_COOKIE['selsite'];
			// }	
			
			global $wpdb;global $prosol_prefix;
			$table_ps_logs_activity = $prosol_prefix . 'logs_activity';
			
			if($activity_msg!='jobs'){
				$wpdb->insert(
					$table_ps_logs_activity,
					array(
						'activity_msg'  => $activity_msg,
						'activity_type' => $activity_type,
						'add_by'        => get_current_user_id(),
						'add_date'      => current_time( 'mysql' ),
						'site_id'       => $selsite
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%s'
					)
				);
			} else if($activity_msg=='jobs'){ 
				if(is_null($stampforJobs) || $stampforJobs==''){
					$setdate=current_time( 'mysql' );
				} else{
					$setdate=$stampforJobs;
				}  

				$wpdb->insert(
					$table_ps_logs_activity,
					array(
						'activity_msg'  => $activity_msg,
						'activity_type' => $activity_type,
						'add_by'        => get_current_user_id(),
						'add_date'      => $setdate,
						'site_id'       => $selsite
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%s'
					)
				);	
			}

		}

		/** project 1440
		 * @param string $activity_msg
		 * @param string $activity_type
		 */
		public static function proSol_jobstampInsertion( $siteidfordb="0", $response_stamp="" ) {			
			global $wpdb;global $prosol_prefix;
			$table_ps_jobstamp = $prosol_prefix . 'jobstamp';

			$wpdb->query( "DELETE FROM $table_ps_jobstamp WHERE site_id in ($siteidfordb) " );
			if(is_null($response_stamp)){
				$setdate = current_time('mysql');
			} else{
				$setdate = $response_stamp;
			}
			$wpdb->insert(
				$table_ps_jobstamp,
				array(
					'add_by'        => get_current_user_id(),
					'add_date'      => $setdate,
					'site_id'       => $siteidfordb
				),
				array(
					'%s',
					'%s',
					'%s'
				)
			);

		}

	}