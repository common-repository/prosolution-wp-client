
<?php

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
	error_reporting(0);
	
	/*
		/**
		 * The public-facing functionality of the plugin.
		 *
		 * @link       https://www.prosolution.com
		 * @since      1.0.0
		 *
		 * @package    Prosolwpclient
		 * @subpackage Prosolwpclient/public
		 */

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the public-facing stylesheet and JavaScript.
	 *
	 * @package    Prosolwpclient
	 * @subpackage Prosolwpclient/public
	 * @author     ProSolution <helpdesk@prosolution.com>
	 */
	class CBXProSolWpClient_Public {

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
		 * @param      string $plugin_name The name of the plugin.
		 * @param      string $version     The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version     = $version;

			$this->settings_api = new CBXProSolWpClient_Settings_API( $this->plugin_name, $this->version );

			add_shortcode( 'prosolfrontend', array( $this, 'proSol_prosolwpclientShortcode' ) );
		}

		public function proSol_autoSync() {
			//siteurl?prosolwpclientsync=na7wg36kqx42huc5
			if (false == get_option('prosolwpclient_encryptionkey') || 0 == get_option('prosolwpclient_encryptionkey') ) {
                update_option( 'prosolwpclient_encryptionkey', bin2hex(random_bytes(16)) );
    
                $vectorkey = bin2hex(random_bytes(16));
                $plugin_dir = __DIR__; // Current directory where the script is run
                $readme_path = $plugin_dir . '/../vector.txt';
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

			$prosolwpclientsync = isset( $_GET['prosolwpclientsync'] ) ? $_GET['prosolwpclientsync'] : '';
			if ( $prosolwpclientsync != '' ) {
				$prosolwpclientsync = sanitize_text_field( $prosolwpclientsync );
				if ( $prosolwpclientsync != '' && $prosolwpclientsync !== null ) {

					$sync_key = $this->settings_api->proSol_get_option( 'sync_key', 'prosolwpclient_tools', 'na7wg36kqx42huc5' );

					if ( $sync_key != '' && $sync_key != null && $sync_key == $prosolwpclientsync ) {
						//now sync all


						$plugin_admin = new CBXProSolWpClient_Admin( PROSOLWPCLIENT_PLUGIN_NAME, PROSOLWPCLIENT_PLUGIN_VERSION );
						$plugin_admin->proSol_allTableSync( false );
					}
				}
			}

		}

		/**
		 * prosolwpclient shortcode callback
		 * @return string
		 */
		public function proSol_prosolwpclientShortcode( $atts ) {

			wp_enqueue_style( 'prosolwpclientcustombootstrap' );
			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_style( 'fontawesome' );
			wp_enqueue_style( 'chosen.min' );
			wp_enqueue_style( 'sweetalert' );
			wp_enqueue_style( 'jquery.fileupload' );
			wp_enqueue_style( 'dropzone.min' );
			wp_enqueue_style( 'bootstrap-icons' );
			wp_enqueue_style( 'fileinput' );
			wp_enqueue_style( 'krajeetheme' );
			wp_enqueue_style( 'prosolwpclient-public' );

			$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
			$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
			$siteid		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);	
			$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
			$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);

			if ( $is_api_setup === false ) {
				return '<div class="prosolwpclientcustombootstrap"><div class="alert alert-warning" role="alert">' . esc_html__( 'Sorry, job portal is not loaded due to api misconfiguration', 'prosolwpclient' ) . '</div></div>';
			}


			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'bootstrap.bundle.min' );
			wp_enqueue_script( 'jquery.validate.min' );
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery.formtowizard' );

			wp_enqueue_script( 'mustache.min' );
			wp_enqueue_script( 'chosen.jquery.min' );
			wp_enqueue_script( 'sweetalert.min' );
			wp_enqueue_script( 'fileinput' );
			wp_enqueue_script( 'krajeetheme' );
			//wp_enqueue_script( 'combobox_skill' );

			wp_enqueue_script( 'jquery.ui.widget' );
			//wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('tmpl');
			wp_enqueue_script( 'load-image.all' );
			wp_enqueue_script( 'canvas-to-blob' );
			wp_enqueue_script( 'blueimp-gallery' );
			wp_enqueue_script( 'jquery.iframetransport' );
			wp_enqueue_script( 'jquery.fileupload' );
			wp_enqueue_script( 'dropzone.min' );
			wp_enqueue_script( 'jquery.fileuploadprocess' );
			wp_enqueue_script( 'jquery.fileuploadvalidate' );
			//			wp_enqueue_script( 'basic_plus_file_upload' );
			wp_enqueue_script( 'jquery.xdr-transport' );

			wp_enqueue_script( 'prosolwpclient-public' );

			
			$frontend_settingPage = get_option( 'prosolwpclient_frontend' );

			$pageDefault=0;
			if ( $frontend_settingPage !== false && isset( $frontend_settingPage[$issite.'frontend_pageid'] ) && intval( $frontend_settingPage[$issite.'frontend_pageid'] ) > 0 ) {
				$pageDefault =  $frontend_settingPage[$issite.'frontend_pageid'];
			}
			
			global $wpdb;global $prosol_prefix;
			$qposts = $wpdb->get_var( $wpdb->prepare( "SELECT post_content FROM $wpdb->posts WHERE post_parent = %s ORDER BY post_date DESC LIMIT 1", $pageDefault ) );
			
			if(strpos($qposts,'"apply"' ) == true){
				$postcontent = 'apply';
			} else if ( strpos($qposts,'"result"' ) == true) {
				$postcontent = 'result';
			} else {
				$postcontent = 'search';
			}
			
			$atts = shortcode_atts(
				array(
					'type' => $postcontent 
				),
				$atts, 'prosolwpclientfrontend' );
				
			$template_type = esc_attr( $atts['type'] );
			$template_name = 'templates/prosolwpclientjobsearchform.php';
			$param_type = isset( $_REQUEST['type'] ) ? filter_var( $_REQUEST['type'], FILTER_SANITIZE_STRING ) : '';
			 
			$called_from='';
			$removebtn=0;
			
			if ( $param_type == '' && $template_type == 'search' ) {
				$template_name = 'templates/prosolwpclientjobsearchform.php';
			} else if ( $param_type == '' && $template_type == 'apply' ) {
				$template_name = 'templates/prosolwpclientjobapply.php';
				$removebtn=1;
			} else if ( $param_type == '' && $template_type == 'result' ) {
				$param_type = 'result';
				$called_from='';
			}  
 
			if ( $param_type == 'result' ) {
				
				$job_search_result = array();
				 //var_dump(sizeof( $_SESSION ) > 0 && isset( $_SESSION['job_search_result'] ) && $called_from == '' );die();
				//var_dump($_SESSION['job_search_result']);
				 //wp_die();
				if ( sizeof( $_SESSION ) > 0 && isset( $_SESSION['job_search_result'] ) && $called_from == '' ) {
					$job_search_result = $_SESSION['job_search_result'];
					//unset( $_SESSION['job_search_result'] );
				} else {
					/* old coding, call API when click button search
						$chkclientlist =  $frontend_settingPage[$issite.'client_list'];
						$safe_data = array(
							'clientidlist' 		=> $chkclientlist
						);
						$api_body  = array( "param" => json_encode( $safe_data ));
										
						
						$header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig($issite);
						
						if($frontend_settingPage[$issite.'enable_recruitment'] == 'on'){
							$api_location='recruitment/';
						} else{
							$api_location='application/';
						}

						$response    = wp_remote_post( $api_config['api_url'] .''. $api_location .'joblist/', array(
							'headers' => $header_info,
							'body'    => $api_body
						) );
						//var_dump($api_body);					
						//var_dump($response);
					Project 1440 */
					
					global $wpdb;global $prosol_prefix;
					$table_ps_jobs = $prosol_prefix . 'jobs';
					$where = " WHERE site_id='$siteid' ORDER BY STR_TO_DATE(publishdate, '%d.%m.%Y') desc ";    
					$sql_select = "SELECT * FROM $table_ps_jobs  ";

					$qjobs = $wpdb->get_results("$sql_select $where", 'ARRAY_A');
					$recordcount = count($qjobs);  
					
					if ( $recordcount != 0 ) {
						// transform into API's output structure 
						$obj_response= (object)[];
						for($xo=0;$xo<$recordcount;$xo++){
								foreach ( $qjobs[$xo] as $key => $value ) {
									if(!property_exists($obj_response,$key)){
									$obj_response->$key=array();
									}  
									array_push($obj_response->$key, $value);
							}
						}
						
						// transform json column		
						$profession_decode = json_decode($obj_response->profession[0]);	
						$skills_decode = json_decode($obj_response->skills[0]);
						$question_decode = json_decode($obj_response->question[0]);
						$portal_decode = json_decode($obj_response->portal[0]);
						$customer_decode = json_decode($obj_response->customer[0]);
						$recruitlink_decode = json_decode($obj_response->recruitlink[0]);
						$obj_response->profession[0]=array();
						$obj_response->skills[0]=array();
						$obj_response->question[0]=array();
						$obj_response->portal[0]=array();
						$obj_response->customer[0]=array();
						$obj_response->recruitlink[0]=array();
						for($xo=0;$xo<$recordcount;$xo++){
							$obj_response->profession[0][$xo]=$profession_decode;
							$obj_response->skills[0][$xo]=$skills_decode;
							$obj_response->question[0][$xo]=$question_decode;
							$obj_response->portal[0][$xo]=$portal_decode;
							$obj_response->customer[0][$xo]=$customer_decode;
							$obj_response->recruitlink[0][$xo]=$recruitlink_decode;
						}

						$job_search_result = $obj_response;
						
					} else {
						$job_search_result = sprintf( __( 'database returns empty', 'prosolwpclient' )  );
					}

				}
				
				$template_name = 'templates/prosolwpclientjobsearchresult.php';
			}

			$jobid = isset($_REQUEST['jobid']) ? $_REQUEST['jobid'] : '';
			//$uuid='EAC87A80-9262-4C49-851F7C5FD02FBE64';
			$woexPreviewUUID = isset($_REQUEST['woexPreview']) ? $_REQUEST['woexPreview'] : '';
			if ( $param_type == 'details' ) {
				// project 1440,kev
				/* $header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig($issite);

				if($frontend_settingPage[$issite.'enable_recruitment'] == 'on'){
					$api_location='recruitment/';
				} else{
					$api_location='application/';
				}

				$response = wp_remote_get( $api_config['api_url'] . $api_location . 'jobdetail/' . $jobid, array( 'headers' => $header_info ) );
				if ( ! is_wp_error( $response ) ) {

					$job_details_result = json_decode( $response['body'] )->data;					
				} else {
					$job_details_result = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
				} */

				global $wpdb;global $prosol_prefix;
				$table_ps_jobs = $prosol_prefix . 'jobs';
				$where = " WHERE jobid='$jobid' AND site_id='$siteid' ";   
				$sql_select = "SELECT * FROM $table_ps_jobs ";

				$qjobs = $wpdb->get_results("$sql_select $where ", "OBJECT");
				
				$recordcount = count($qjobs);  
				if ( $recordcount != 0 ) {
					// transform into API's output structure 
					$obj_response= (object)[];
					for($xo=0;$xo<$recordcount;$xo++){
							foreach ( $qjobs[$xo] as $key => $value ) {
								if(!property_exists($obj_response,$key)){
								$obj_response->$key=array();
								}  
								array_push($obj_response->$key, $value);
						}
					}
					
					// transform json column		
					$profession_decode = json_decode($obj_response->profession[0]);	
					$skills_decode = json_decode($obj_response->skills[0]);
					$question_decode = json_decode($obj_response->question[0]);
					$portal_decode = json_decode($obj_response->portal[0]);
					$customer_decode = json_decode($obj_response->customer[0]);
					$recruitlink_decode = json_decode($obj_response->recruitlink[0]);
					$obj_response->profession[0]=array();
					$obj_response->skills[0]=array();
					$obj_response->question[0]=array();
					$obj_response->portal[0]=array();
					$obj_response->customer[0]=array();
					$obj_response->recruitlink[0]=array();
					
					for($xo=0;$xo<$recordcount;$xo++){
						$obj_response->profession[0][$xo]=$profession_decode;
						$obj_response->skills[0][$xo]=$skills_decode;
						$obj_response->question[0][$xo]=$question_decode;
						$obj_response->portal[0][$xo]=$portal_decode;
						$obj_response->customer[0][$xo]=$customer_decode;
						$obj_response->recruitlink[0][$xo]=$recruitlink_decode;
					}

					$job_details_result = $obj_response;
					// var_dump($profession_decode);
				} else {
					$header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig($issite);
					$safe_data = array(
						'jobid' 			=> $jobid,
						'uuid'				=> $woexPreviewUUID
					);
					$api_location = 'recruitment/';
					$api_body  = array( "param" => json_encode( $safe_data ) );
					$response    = wp_remote_post( $api_config['api_url'] .''. $api_location .'previewisvalid', array(
						'headers' => $header_info,
						'body'    => $api_body
					) );
					if ( ! is_wp_error( $response ) ) {
						$response_data = json_decode( $response['body'] )->data;
						$result = $response_data->previewstatus;
						if($result){
							$response = wp_remote_get( $api_config['api_url'] . $api_location . 'jobdetail/' . $jobid, array( 'headers' => $header_info ) );
							$response_data = json_decode( $response['body'] )->data;

							$job_details_result = $response_data;
						}else{
							$job_details_result = sprintf( __( 'Database returns empty', 'prosolwpclient' ) );
						}
					}else{
						$job_details_result = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
					}
				} 
				
				$template_name = 'templates/prosolwpclientjobdetails.php';
			} 

			if ( $param_type == 'apply' ) {

				$session_id = session_id();
				if ( isset( $_SESSION[ $session_id ] ) ) {
					unset( $_SESSION[ $session_id ] );
				}
				$template_name = 'templates/prosolwpclientjobapply.php';
			}
			
			if ( $param_type == 'crawler' ) {
				$uuid = $_REQUEST['uuid'];
				$header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig($issite);
				$response = wp_remote_get( $api_config['api_url'] . 'application/jobdetails/' . $uuid, array( 'headers' => $header_info ) );
				if ( ! is_wp_error( $response ) ) {

					$job_details_result = json_decode( $response['body'] )->data;

				} else {
					$job_details_result = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
				}
				
				$template_name = 'templates/prosolwpclientcrawler.php';
			}
			
			$opt = get_option('prosolwpclient_designtemplate');
			$wplogo= $opt[$issite.'deslogofile'];

			ob_start();
			echo '<p id="anchorwp"></p>';
			echo '<img style="margin: 32px 15px;" class="prosolwpclientlogo" src="'. $wplogo .'"></img>';
			echo '<div class="prosolwpclientcustombootstrap">';
			include( $template_name );
			echo '</div>';
			$output = ob_get_contents();
			ob_clean();
			
			return $output;
		}
		
		/**
		 * template_redirect callback to see parameter and show appropriate page
		 */
		public function proSol_prosolwpclientFrontendFormsubmit() {
			if ( ! session_id() ) {
				session_start();
			}
			//for testing jobsearchresult set as default on prosolution template, remove cache previous search
			//unset( $_SESSION['job_search_result'] );
			$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
			$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
			$siteid		  = CBXProSolWpClient_Helper::proSol_getSiteidOnly($hassiteid);
			$opt = get_option('prosolwpclient_frontend');
			$isrec= $opt[$issite.'enable_recruitment']; 
			
			$destemp = get_option('prosolwpclient_designtemplate');
			$useprosoltemplate=$destemp[$issite.'destemplate'];
			//work project 515, for prosolution template, always submit form either searchbtn or jobidbtn
			//jobidbtn redirect to ?type=apply
			//doesn't take effect ? instead produce bug in development v1.7
			// if($useprosoltemplate==0 && $isrec=='on'){
			// 	if($_POST['submit']==$destemp[$issite.'dessearchjobidbtn']){
			// 		wp_safe_redirect( add_query_arg( 'type', 'apply', $page_url ) );
			// 		exit;
			// 	}
			// }  
			
			//job search submit and redirect to job result page
			//var_dump(( isset( $_POST['prosolwpclient_frontend_formsubmit'] ) && intval( $_POST['prosolwpclient_frontend_formsubmit'] ) == 1 ) &&
			//( isset( $_POST['prosolwpclient_token'] ) && wp_verify_nonce( $_POST['prosolwpclient_token'], 'prosolwpclient_formsubmit' ) ));
			if ( ( isset( $_POST['prosolwpclient_frontend_formsubmit'] ) && intval( $_POST['prosolwpclient_frontend_formsubmit'] ) == 1 ) &&
			     ( isset( $_POST['prosolwpclient_token'] ) && wp_verify_nonce( $_POST['prosolwpclient_token'], 'prosolwpclient_formsubmit' ) ) 
				|| (
					isset($_GET['type']) && strval($_GET['type']) == "search" && isset($_GET['fromjoblist']) && strval($_GET['fromjoblist']) == "1" 
				)
				) {

				$post_data = $_POST;

				$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
				$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);



				if ( $is_api_setup === false ) {
					return;
				}				

				// sanitization
				$group_checked = isset( $post_data['group_checked'] ) ? $post_data['group_checked'] : '';
				if(!isset($_GET['siteid'])){
					$page_url      = isset( $post_data['prosolwpclient_frontend_url'] ) ? esc_url( $post_data['prosolwpclient_frontend_url'] ).'#anchorwp' : '';
				}else{
					$page_url      = isset( $post_data['prosolwpclient_frontend_url'] ) ? esc_url( $post_data['prosolwpclient_frontend_url'].'?siteid='.$_GET['siteid'].'#anchorwp' ) : '';
				}
				
				if($isrec == 'on'){
					/* remove this chunk after 49720, only bypass selected professiongroup
					global $wpdb;global $prosol_prefix;
					$table_ps_profession       = $prosol_prefix . 'profession';
					$where_sql = '';
					if(is_array( $group_checked )){
						foreach($group_checked as $index => $profgroup){
							if($index==0 && (count($group_checked) > 1) ){
								$where_sql  = $wpdb->prepare( " WHERE professiongroupId IN (%d", $profgroup );
							} elseif ($index==0 && (count($group_checked) == 1) ){
								$where_sql  = $wpdb->prepare( " WHERE professiongroupId = %d", $profgroup );
							} elseif ($index== (count($group_checked) - 1) ){
								$where_sql  .= $wpdb->prepare( ",%d)", $profgroup );	
							} else {
								$where_sql  .= $wpdb->prepare( ",%d", $profgroup );
							}	
						}
					}
					$profid_query = $wpdb->get_results( "SELECT DISTINCT professionId FROM $table_ps_profession $where_sql", ARRAY_A );
					$teest=$wpdb->last_query;
					$profid_list='';
					if(count($profid_query)>0){
						foreach($profid_query as $index => $profid){
							if($index==0){
								$profid_list=$profid['professionId'];
							}else{
								$profid_list.=','.$profid['professionId'];
							}
						}
					}
					*/
					// Project 1440 get joblist from db
					// $safe_data = array(
					// 	'professionGroupID' 	=> is_array( $group_checked ) ? implode( ',', $group_checked ) : '',
					// 	'limitrows'         => 500,
					// 	'clientidlist'      => $opt['client_list']
					// );
					// $api_location = 'recruitment/';
					
					global $wpdb;global $prosol_prefix;
					$table_ps_jobs = $prosol_prefix . 'jobs';
					$where = " WHERE site_id='$siteid' ";  
					if(isset( $post_data['jobname'] ) && $post_data['jobname'] != ''){
						$filter = $post_data['jobname'];
						$where .= " AND (jobname LIKE '%$filter%' OR CAST(jobproject_id AS UNSIGNED) LIKE '%$filter%' OR categoryname LIKE '%$filter%') ";
					} 
					if(isset( $post_data['searchplace'] ) && $post_data['searchplace'] != ''){
						$filterplace = $post_data['searchplace'];
						$where .= " AND (workingplace LIKE '%$filterplace%' OR zipcode LIKE '%$filterplace%') ";
					} 

					if($destemp[$issite.'dessortby'] != "" && $destemp[$issite.'dessortbyorder'] != "" ){
						$sortorder = $destemp[$issite.'dessortbyorder'];
						if($destemp[$issite.'dessortby'] == 1){
							$where .= " ORDER BY CAST(jobproject_id AS UNSIGNED) $sortorder ";
						}elseif($destemp[$issite.'dessortby'] == 2){
							$where .= " ORDER BY jobname $sortorder ";
						}else{
							$where .= " ORDER BY STR_TO_DATE(publishdate, '%d.%m.%Y') $sortorder ";
						}
					}else{
						$where .= " ORDER BY STR_TO_DATE(publishdate, '%d.%m.%Y') desc ";
					}
					
					$sql_select = "SELECT * FROM $table_ps_jobs ";

					$qjobs = $wpdb->get_results("$sql_select $where ", "OBJECT"); 
					
					$recordcount = count($qjobs);  
					if ( $recordcount != 0 ) {
						// transform into API's output structure 
						$obj_response= (object)[];
						for($xo=0;$xo<$recordcount;$xo++){
							 foreach ( $qjobs[$xo] as $key => $value ) {
								 if(!property_exists($obj_response,$key)){
									$obj_response->$key=array();
								 }  
								 array_push($obj_response->$key, $value);
							}
						}						
								
						$response_data = $obj_response;
						
					} else {
						$response_data = sprintf( __( 'Database returns empty.', 'prosolwpclient' ) );
					}

				} else{
					$safe_data = array(
						'professionGroupID' => is_array( $group_checked ) ? implode( ',', $group_checked ) : '',
						'limitrows'         => 500
					);
					$api_location = 'application/';

					$api_body  = array( "param" => json_encode( $safe_data ) );

					$header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig($issite);

					$response = wp_remote_post( $api_config['api_url'] . '' . $api_location . 'joblist/', array(
						'headers' => $header_info,
						'body'    => $api_body
					) );

					if ( ! is_wp_error( $response ) ) {
						$response_data = json_decode( $response['body'] )->data;
						//var_dump($response_data);
					} else {
						$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
					}
				}
				
				if ( sizeof( $_SESSION ) > 0 && isset( $_SESSION['job_search_result'] ) ) {
					unset( $_SESSION['job_search_result'] );
				}
				$_SESSION['job_search_result'] = $response_data;
				//debug hasil search profession group, must comment redirect and exit
					
				if($useprosoltemplate==1 || $isrec=='off'){
					wp_safe_redirect( add_query_arg( 'type', 'result', $page_url ) );
					exit;
				}	
				
			}

		}

		/**
		 * pagination at jobsearch
		 */
		public function proSol_paginationjobsearch() { 
			$actbutton=$_POST['actbutton']; 
			$pg_counter=$_POST['pg_counter'];
			$pg_start=$_POST['pg_start'];
			$pg_next=$_POST['pg_next'];
			$issite=$_POST['issite'];   
			$count_indexshowlist=$_POST['count_indexshowlist'];  
			$prosoldes = get_option('prosolwpclient_designtemplate');
			$pstemplate = $prosoldes[$issite.'destemplate'];

			$pg_item=$prosoldes[$issite.'desperpage'];
			if($count_indexshowlist < $pg_item){
				$pg_item=$count_indexshowlist;
			}
			$pg_end=intdiv($count_indexshowlist, $pg_item);
			$pg_sisa=fmod($count_indexshowlist, $pg_item); 
			
			if($actbutton=='pg_next'){ 
				$pg_counter++; 
			} else if($actbutton=='pg_prev'){
				$pg_counter--; 
			} else if($actbutton=='pg_nextt'){
				if($pg_sisa != 0){ // total item odd
					$pg_counter=$pg_end; 
				} else{ // total item even
					$pg_counter=$pg_end-1; 	
				}	
			} else {
				$pg_counter=0;
			}

			$pg_start=$pg_counter * $pg_item;
			if($pg_counter==$pg_end){
				$pg_next=$count_indexshowlist;
			}else{
				$pg_next=$pg_start + $pg_item;
			}
	
			$showprev=$pg_start != 0?'':'style=visibility:hidden';
			$html = sprintf( '<input type="text" name="jobsearch_page" id="pg_prevv" class="btnprosoldes button" readonly value="<<"  %1$s />', $showprev );
			$html .= sprintf( '<input type="text" name="jobsearch_page" id="pg_prev" class="btnprosoldes button" readonly value="<"  %1$s />', $showprev );
			
			$view_start = $pg_counter+1;
			if($pg_sisa != 0){
				$view_end = $pg_end+1;
				$checkbtnnext = $pg_counter;
			} else{
				$view_end = $pg_end;
				$checkbtnnext = $pg_counter+1;
			}  
			$html .= sprintf( '<b>%1$s/%2$s</b>', $view_start, $view_end );
			$shownext=( $checkbtnnext != $pg_end) && ($pg_item!=$count_indexshowlist) ?'':'style=visibility:hidden';  
			$html .= sprintf( '<input type="text" name="jobsearch_page" id="pg_next" class="btnprosoldes button" readonly value=">" %1$s />', $shownext);
			$html .= sprintf( '<input type="text" name="jobsearch_page" id="pg_nextt" class="btnprosoldes button" readonly value=">>" %1$s />', $shownext);
				
			$return_response = array();  
			$return_response['pg_counter']  = $pg_counter;
			$return_response['pg_start']  = $pg_start;
			$return_response['pg_next']  = $pg_next;
			$return_response['html']  = $html;
			echo json_encode( $return_response ); 
			 
			wp_die();
		}

		/**
		 * group change will change member dropdown
		 */
		public function proSol_groupSelectionToTrainingCallback() {
			$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
			$issite		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);
			
			check_ajax_referer( 'prosolwpclient', 'security' );

			$group_id = isset( $_POST['group_id'] ) ? filter_var( $_REQUEST['group_id'], FILTER_SANITIZE_STRING ) : '';

			global $wpdb;global $prosol_prefix;
			$table_ps_education       = $prosol_prefix . 'education';
			$table_ps_educationlookup = $prosol_prefix . 'educationlookup';

			$join = $where_sql = $order_by = '';
			$join .= " LEFT JOIN $table_ps_education AS education ON education.educationId = educationlookup . educationId ";

			$where_sql    = $wpdb->prepare( "educationlookup . educationId =%s AND educationlookup . site_id=%s ", $group_id,$issite );
			$order_by     = 'ORDER BY educationlookup.name';
			$training_arr = $wpdb->get_results( "SELECT educationlookup.name, educationlookup.lookupId FROM $table_ps_educationlookup AS educationlookup $join WHERE $where_sql $order_by" );

			echo json_encode( $training_arr );
			wp_die();
		}
		function proSol_goupDataIdCallback() {
			$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
			$getsiteid = isset( $_POST['hassiteid'] ) ? filter_var( $_REQUEST['hassiteid'], FILTER_SANITIZE_STRING ) : '';
			$issite		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($getsiteid);
			check_ajax_referer( 'prosolwpclient', 'security' );
			$group_id = isset( $_POST['groupid'] ) ? filter_var( $_REQUEST['groupid'], FILTER_SANITIZE_STRING ) : '';
			global $wpdb;global $prosol_prefix;
			
			$table_ps_skill_group_rate = $prosol_prefix . 'skillrate';
			$rate_arr = $wpdb->get_results( "SELECT name,value FROM $table_ps_skill_group_rate as skillrate  WHERE skillgroupId='$group_id' AND site_id='$issite' ", ARRAY_A);
		?>
			<thead style="background: #e5e5e5;">
				<tr id="my_skill_header">
					<th class="col-md-4"><?php esc_html_e( 'Skill', 'prosolwpclient' ) ?></th>
					<?php
						$colsum = 1;
						foreach ( $rate_arr as $index => $rate_info ) {
							
							$rate_name = esc_html__( $rate_info['name'], 'prosolwpclient' );
					?>
							<th class="col-md-1"><?php echo $rate_name; ?></th>
					<?php
							$colsum++;
						}
					?>
				</tr>
			</thead>
			<tbody class="content_tbody check_skill_popup">	
				<tr>
					<td colspan="<?php echo $colsum; ?>" style="background:#FFF;padding: 0;">
						<div style="overflow-y:auto;height: 400px;">
							<table class="table-striped">
								<tbody>
									<?php
										$table_ps_skill = $prosol_prefix . 'skill';
										$ID = $_POST['groupid'];
										$skill_arr = $wpdb->get_results( "SELECT name,skillId,skillgroupId FROM $table_ps_skill as skill WHERE skillgroupId='$group_id' AND site_id='$issite' ORDER BY skill.name ASC", ARRAY_A);
											
										foreach ( $skill_arr as $index => $skill_info ) {
											$knowledge_name = esc_html__( $skill_info['name'], 'prosolwpclient' );
											$skill_id       = $skill_info['skillId'];
											$skill_group_id = $skill_info['skillgroupId'];
											$table_ps_skill_group = $prosol_prefix . 'skillgroup';
											$skill_group_name = $wpdb->get_results( "SELECT name FROM $table_ps_skill_group as skillgroup  WHERE skillgroupId='$group_id' AND site_id='$issite'", ARRAY_A);

											$name_group = $skill_group_name[0]['name'];
									?>
										
									<tr>
										<script type="text/javascript">
											(function ($) {
												$(document).ready(function () {
													$('.check_expertise_entry_exist .table-striped #pswp_expertise_wrapper tr.single-expertise-entry').each(function(){
														var $skill = "<?php echo $skill_id; ?>";
														var $skill_group = "<?php echo $skill_id; ?>";
														var $tdval =  $(this).find('.second_td_check input').val();
														var $knowledge_td = $(this).find('.thired_td_classification').text();
														   if($tdval == $skill) {
																var $this = $(this);
																var $trash_col1 = $this.parents('.check_expertise_entry_exist').siblings();

																$trash_col1.find('.check_skill_popup input:radio[name=skill_' + $skill + ']').each(function () {
																	var $knowledge_third = $(this).data('knowledge_type');

																	if($knowledge_td == $knowledge_third){
																		$(this).prop('checked', true);
																		$(".content_tbody").find('#skill_' + $skill + '_' +$skill_group).css('display', 'block');
																	}
																});
															}
													});
												});
											})(jQuery);
										</script>
										<td class="col-md-4 tarsh_show_lab">
											<a href="#" style="display: none;"
											   class="dashicons dashicons-post-trash trash-expertise-entry"
											   title="<?php esc_html_e( 'Delete Expertise', 'prosolwpclient' ) ?>"
											   id="skill_<?php echo $skill_id; ?>_<?php echo $skill_id; ?>"
											   data-selected_val="<?php echo $group_id; ?>"
											   data-skill_group_name="<?php echo $name_group;?>"
											   data-skillid="<?php echo $skill_id; ?>"
											   data-skillgroupid="<?php echo $skill_id; ?>">
											</a>
											<?php esc_html_e( $skill_info['name'], 'prosolwpclient' ) ?>
										</td>
										<?php
											foreach ( $rate_arr as $index => $rate_info ) {
												$rate_value = esc_html__( $rate_info['value'], 'prosolwpclient' );
												$rate_name = esc_html__( $rate_info['name'], 'prosolwpclient' );
										?>
												<td  class="col-md-1">
													<label class="radio-inline-unused">
														<input type="radio" value="<?php echo $rate_value; ?>"
															   name="skill_<?php echo $skill_id; ?>"
															   id="skill_<?php echo $skill_id; ?>_<?php echo $skill_id; ?>"
															   data-selected_val="<?php echo $group_id; ?>"
															   data-skill_group_name="<?php echo $name_group;?>"
															   data-skillid="<?php echo $skill_id; ?>"
															   data-skillgroupid="<?php echo $skill_id; ?>"
															   data-knowledge="<?php echo $knowledge_name; ?>"
															   data-knowledge_type="<?php echo $rate_name; ?>" onchange="showdeletebutton(this)">
														<span class="radiomarksidedish"></span>	   
													</label>
												</td>
										<?php
											}
										?>
									</tr>
								<?php
									}
								?>		
								</tbody>
							</table>
						</div>
					</td>
				</tr>	
			<tbody>
<?php		

			wp_die();
			// this is required to terminate immediately and return a proper response			
		}

		/**
		 * country change will change federal dropdown
		 */
		public function proSol_countrySelectionToFederalCallback() {
			$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
			$issite	   = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);
			check_ajax_referer( 'prosolwpclient', 'security' );

			$country_code = isset( $_POST['country_code'] ) ? filter_var( $_REQUEST['country_code'], FILTER_SANITIZE_STRING ) : '';

			global $wpdb;global $prosol_prefix;
			$table_ps_country = $prosol_prefix . 'country';
			$table_ps_federal = $prosol_prefix . 'federal';

			$join = $where_sql = $order_by = '';
			$join .= " LEFT JOIN $table_ps_country AS country ON country.countryCode = federal . countryCode ";

			$where_sql   = $wpdb->prepare( "federal . countryCode =%s AND federal . site_id='$issite' ", $country_code );
			$order_by    = 'ORDER BY federal.name';
			$federal_arr = $wpdb->get_results( "SELECT federal.federalId, federal.name FROM $table_ps_federal AS federal $join WHERE $where_sql $order_by" );
			//echo "SELECT federal.federalId, federal.name FROM $table_ps_federal AS federal $join WHERE $where_sql $order_by";

			echo json_encode( $federal_arr );
			wp_die();
		}

		/**
		 * @return array
		 * make prosolwpclient folder in uploads directory if not exist, return path info
		 */
		private function proSol_checkUploadDir() {
			$upload_dir = wp_upload_dir();

			$upload_dir_basedir = $upload_dir['basedir'];
			$upload_dir_baseurl = $upload_dir['baseurl'];

			$prosol_base_dir = $upload_dir_basedir . '/prosolwpclient/';
			$prosol_base_url = $upload_dir_baseurl . '/prosolwpclient/';

			if ( ! class_exists( 'WP_Filesystem_Base' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
			}

			$folder_exists = 1;
			//let's check if the prosolwpclient folder exists in upload dir
			if ( ! ( new WP_Filesystem_Base )->exists( $prosol_base_dir ) ) {
				//if not then create it

				$created = wp_mkdir_p( $prosol_base_dir );
				if ( $created ) {
					$folder_exists = 1;
				} else {
					$folder_exists = 0;
				}
			}

			return array(
				'folder_exists'      => $folder_exists,
				'upload_dir_basedir' => $upload_dir_basedir,
				'upload_dir_baseurl' => $upload_dir_baseurl,
				'prosol_base_dir'    => $prosol_base_dir,
				'prosol_base_url'    => $prosol_base_url
			);
		}

		/**
		 * blueimp file upload process
		 */
		public function proSol_fileUploadProcess() {
			check_ajax_referer( 'prosolwpclient', 'security' );

			//if the upload dir for prosolwpclient is not created then then create it
			$dir_info = $this->proSol_checkUploadDir();
			$submit_data  = $_FILES["files"];
			$mime_type   = isset( $submit_data['type'] ) ? $submit_data['type'][0] : '';
			$ext = proSol_mimeExt($mime_type);
			
			if ( in_array( $ext, proSol_imageExtArr() ) || in_array( $ext, proSol_documentExtArr() ) ) {
				if ( is_array( $dir_info ) && sizeof( $dir_info ) > 0 && array_key_exists( 'folder_exists', $dir_info ) && $dir_info['folder_exists'] == 1 ) {
					$options = array(
						'script_url'     => admin_url( 'admin-ajax.php' ),
						'upload_dir'     => $dir_info['prosol_base_dir'],
						'upload_url'     => $dir_info['prosol_base_url'],
						'print_response' => false,
					);
	
					$upload_handler = new CBXProSolWpClient_UploadHandler( $options );
	
					$response_obj = $upload_handler->response['files'][0];
					if ( $response_obj->name != '' ) {
						if ( ! session_id() ) {
							session_start();
						}
	
						$attached_file_name = $response_obj->name;
	
						$extension = pathinfo( $attached_file_name, PATHINFO_EXTENSION );
	
						$newfilename                 = wp_create_nonce( session_id() . time() ) . '.' . $extension;
						$rename_status               = rename( $dir_info['prosol_base_dir'] . $attached_file_name, $dir_info['prosol_base_dir'] . $newfilename );
						$response_obj->newfilename   = $newfilename;
						$response_obj->rename_status = $rename_status;
						$response_obj->extension     = $extension;
	
						$return_response = array( 'files' => array( 0 => $response_obj ) );
						echo json_encode( $return_response );
						wp_die();
					}
				}
			}
		}

		/**
		 * delete uploaded file from local drive & unset from session
		 */
		public function proSol_fileDeleteProcess() {
			check_ajax_referer( 'prosolwpclient', 'security' );

			$submit_data  = $_REQUEST;
			$filename     = isset( $submit_data['filename'] ) ? sanitize_text_field( $submit_data['filename'] ) : '';
			$filesizebyte = isset( $submit_data['filesizebyte'] ) ? sanitize_text_field( $submit_data['filesizebyte'] ) : 0;

			$ok_to_progress = 0;
			$session_id     = session_id();
			if ( $filename != '' ) {
				if ( isset( $_SESSION[ $session_id ] ) ) {
					$files_name_arr = $_SESSION[ $session_id ]['files_track'];
					if ( is_array( $files_name_arr ) && sizeof( $files_name_arr ) > 0 && array_key_exists( $filename, $files_name_arr ) ) {
						$deleted = unlink( wp_upload_dir()['basedir'] . '/prosolwpclient/' . $filename );
						if ( $deleted ) {
							$_SESSION[ $session_id ]['totalfilesize'] -= $filesizebyte;
							unset( $_SESSION[ $session_id ]['files'][ $files_name_arr[ $filename ][0] ] );
							unset( $_SESSION[ $session_id ]['files_location'][ $files_name_arr[ $filename ][1] ] );
							unset( $_SESSION[ $session_id ]['files_track'][ $filename ] );
							if ( isset( $_SESSION[ $session_id ]['portrait'][ $filename ] ) ) {
								unset( $_SESSION[ $session_id ]['portrait'][ $filename ] );
							}
							$ok_to_progress = 1;
						}
					}
				}
			}

			echo json_encode( $ok_to_progress );
			wp_die();
		}

		/**
		 * before closing file uploading bootstrap modal, check ajax validation and made proper session data
		 */
		public function proSol_fileUploadModalProcess() {
			check_ajax_referer( 'prosolwpclient', 'security' );
			$session_id  = session_id();
			$submit_data = $_REQUEST;

			$validation_errors = $success_data = $return_response = array();

			$sidetitle   = isset( $submit_data['sidetitle'] ) ? sanitize_text_field( $submit_data['sidetitle'] ) : '';
			$description = isset( $submit_data['description'] ) ? sanitize_text_field( $submit_data['description'] ) : '';
			$attachtype  = isset( $submit_data['attachtype'] ) ? sanitize_text_field( $submit_data['attachtype'] ) : '';
			$newfilename = isset( $submit_data['newfilename'] ) ? sanitize_text_field( $submit_data['newfilename'] ) : '';
			$mime_type   = isset( $submit_data['mime-type'] ) ? sanitize_text_field( $submit_data['mime-type'] ) : '';
			$ext         = isset( $submit_data['ext'] ) ? sanitize_text_field( $submit_data['ext'] ) : '';
			$filesize    = isset( $submit_data['filesize'] ) ? intval( $submit_data['filesize'] ) : 0;

			if ( $sidetitle == '' ) {
				$validation_errors['sidetitle']['sidetitle_empty'] = esc_html__( 'Please enter title', 'prosolwpclient' );
			} elseif ( strlen( $sidetitle ) > 35 ) {
				$validation_errors['sidetitle']['sidetitle_max_crossed'] = esc_html__( 'Title could not be more than 35 characters', 'prosolwpclient' );
			}
			if ( $description != '' ) {
				if ( strlen( $description ) > 150 ) {
					$validation_errors['description']['description_max_crossed'] = esc_html__( 'Description could not be more than 150 characters', 'prosolwpclient' );
				}
			}
			if ( $attachtype == '' ) {
				$validation_errors['attachtype']['attachtype_empty'] = esc_html__( 'Please choose attachment type', 'prosolwpclient' );
			} elseif ( ! ( $attachtype == 'docu' || $attachtype == 'photo' ) ) {
				$validation_errors['attachtype']['attachtype_invalid'] = esc_html__( 'Selection of attachment type is invalid', 'prosolwpclient' );
			}
			if ( $newfilename == '' ) {
				$validation_errors['newfilename']['newfilename_empty'] = esc_html__( 'Sorry! Your uploaded file size is invalid. Please check and try again.', 'prosolwpclient' );
			}

			if ( $filesize <= 0 ) {
				$validation_errors['newfilename']['newfilename_size_invalid'] = esc_html__( 'Please upload file', 'prosolwpclient' );
			}

			if ( isset( $_SESSION[ $session_id ]['totalfilesize'] ) && ( $_SESSION[ $session_id ]['totalfilesize'] + $filesize ) > 10485760 ) {
				$validation_errors['newfilename']['newfilename_totalsize_invalid'] = esc_html__( 'Sorry! Your already uploaded total file size limit crossed. Please delete existing file and try again. You can upload no more than ', 'prosolwpclient' ) . CBXProSolWpClient_Helper::proSol_formatBytesToMB( 10485760 - $_SESSION[ $session_id ]['totalfilesize'] );
			}

			if ( $attachtype == 'docu' && ! in_array( $ext, proSol_documentExtArr() ) ) {
				$validation_errors['attachtype']['ext_invalid'] = esc_html__( 'Invalid file for document type. Allowable file: pdf, doc, docx, xls, xlsx, txt, odt, ods, odp, rtf, pps, ppt, pptx, ppsx, vcf, msg, eml, ogg, mp3, wav, wma, asf, mov, avi, mpg, mpeg, mp4, wmf, 3g2, 3gp, png, jpg, jpeg, gif, bmp, tif, tiff, key, numbers, pages', 'prosolwpclient' );
			}

			if ( $attachtype == 'photo' && ! in_array( $ext, proSol_imageExtArr() ) ) {
				$validation_errors['attachtype']['ext_invalid'] = esc_html__( 'Invalid file for photo type. Allowable file: gif, jpg, jpeg, png' );
			}

			if ( $attachtype == 'photo' && in_array( $ext, proSol_imageExtArr() ) ) {
				$info = getimagesize( wp_upload_dir()['basedir'] . '/prosolwpclient/' . $newfilename );
				if ( $info === false ) {
					$validation_errors['newfilename']['newfilename_invalid'] = esc_html__( 'Unable to determine image type of uploaded file. Please upload valid file', 'prosolwpclient' );
				}

				if ( ( $info[2] !== IMAGETYPE_GIF ) && ( $info[2] !== IMAGETYPE_JPEG ) && ( $info[2] !== IMAGETYPE_PNG ) ) {
					$validation_errors['newfilename']['newfilename_invalid'] = esc_html__( 'Image content is invalid. Please upload valid file', 'prosolwpclient' );
				}
			}

			$ok_to_process = false;
			if ( sizeof( $validation_errors ) == 0 ) {
				$ok_to_process = true;

				$uploaded     = 0;
				$file_ref_key = 'file1';
				if ( isset( $_SESSION[ $session_id ] ) ) {
					$uploaded     = intval( sizeof( $_SESSION[ $session_id ]['files_location'] ) );
					$file_ref_key = 'file' . ( $uploaded + 1 );
				}

				$single_upload_data = array(
					'ref'         => $file_ref_key,
					'name'        => $sidetitle,
					'ext'         => $ext,
					'description' => $description,
					'filesize'	  => $filesize
				);

				$attach_link = wp_upload_dir()['basedir'] . '/prosolwpclient/' . $newfilename;
				$file_info   = array(
					'name'     => $attach_link,
					'mime'     => $mime_type,
					'postname' => $attach_link,
				);

				if($attachtype == 'photo'){
					if ( in_array( $ext, proSol_imageExtArr() ) ) {
						$_SESSION[ $session_id ]['portrait'][ $newfilename ] = $single_upload_data;
					}
				} else {
					$_SESSION[ $session_id ]['files'][]              = $single_upload_data;
				}

				$_SESSION[ $session_id ]['files_location'][ $file_ref_key ] = $file_info;
				$_SESSION[ $session_id ]['files_track'][ $newfilename ]     = array( $uploaded, $file_ref_key );
				$_SESSION[ $session_id ]['totalfilesize']                   = isset( $_SESSION[ $session_id ]['totalfilesize'] ) ? ( $_SESSION[ $session_id ]['totalfilesize'] + $filesize ) : $filesize;

				$success_data = array(
					'name'        => $sidetitle,
					'desc'        => $description,
					'attach_link' => wp_upload_dir()['baseurl'] . '/prosolwpclient/' . $newfilename,
				);
			}

			$return_response['ok_to_process'] = $ok_to_process;
			$return_response['success']       = $success_data;
			$return_response['error']         = $validation_errors;

			echo json_encode( $return_response );
			wp_die();
		}

		/**
		 * job application submit: data validation and hit api with safe data
		 */
		public function proSol_applicationSubmitProcess() {
			global $wpdb;global $prosol_prefix;	
			check_ajax_referer( 'prosolwpclient', 'security' );

			$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
			$issite	   = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
			$siteid	   = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);
			$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
			$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);

			$post_data = $_POST;

			//print_r($post_data);
			$tab_error_ref = array();

			$jobID = $post_data['jobID'];

			//isset( $post_data['jobID'] ) ? intval( $post_data['jobID'] ) : -1;
			//echo $jobID;die;
			$validation_errors = $success_data = $return_response = $safe_data = array();
			$ok_to_process     = 0;
			$portrait          = '';

			$custom_error_message = '';


			if($jobID == '' || isset($jobID)){
				$title     = isset( $post_data['title'] ) ? sanitize_text_field( $post_data['title'] ) : '';
				$lastname  = isset( $post_data['lastname'] ) ? sanitize_text_field( stripslashes($post_data['lastname'] )) : '';
				$firstname = isset( $post_data['firstname'] ) ? sanitize_text_field( stripslashes($post_data['firstname'] )) : '';
				$street    = isset( $post_data['street'] ) ? sanitize_text_field( $post_data['street'] ) : '';
				$zip       = isset( $post_data['zip'] ) ? sanitize_text_field( $post_data['zip'] ) : '';
				$city      = isset( $post_data['city'] ) ? sanitize_text_field( $post_data['city'] ) : '';

				$federalID = isset( $post_data['federalID'] ) ? sanitize_text_field( $post_data['federalID'] ) : '';
				$countryID = isset( $post_data['countryID'] ) ? sanitize_text_field( $post_data['countryID'] ) : '';

				$birthcountry   = isset( $post_data['birthcountry'] ) ? sanitize_text_field( $post_data['birthcountry'] ) : '';
				$birthdate   = isset( $post_data['birthdate'] ) ? sanitize_text_field( $post_data['birthdate'] ) : '';
				$email       = isset( $post_data['email'] ) ? sanitize_email( $post_data['email'] ) : '';
				$phone1      = isset( $post_data['phone1'] ) ? sanitize_text_field( $post_data['phone1'] ) : '';
				$phone2      = isset( $post_data['phone2'] ) ? sanitize_text_field( $post_data['phone2'] ) : '';
				$nationality = isset( $post_data['nationality'] ) ? sanitize_text_field( $post_data['nationality'] ) : '';
				$maritalID   = isset( $post_data['maritalID'] ) ? sanitize_text_field( $post_data['maritalID'] ) : '';
				$gender      = isset( $post_data['gender'] ) ? intval( $post_data['gender'] ) : ''; 
				$availabilitydate = isset( $post_data['availabilitydate'] ) ? sanitize_text_field( $post_data['availabilitydate'] ) : '';
				$information      = isset( $post_data['information'] ) ? sanitize_text_field( $post_data['information'] ) : '';
				$salaryExpect   = isset( $post_data['expectedsalary'] ) ? sanitize_text_field( $post_data['expectedsalary'] ) : '';

				$genset       = get_option( 'prosolwpclient_frontend' );
				$default_office = null;
				if ( $genset !== false && isset( $genset[$issite.'default_office'] ) ) {
					$default_office = $genset[$issite.'default_office'];
				}

				//for recruitment additional fields & set office for table jobs
				if ( $genset[$issite.'enable_recruitment'] == 'on' ) {
					$table_ps_customfields = $prosol_prefix . 'customfields';
					$qCustomfields_arr      = $wpdb->get_results( "SELECT * FROM $table_ps_customfields WHERE site_id= '$siteid' ", ARRAY_A );
					$pt_arr = array();
					foreach ( $qCustomfields_arr as $index => $cf_info ) {
						if(substr($cf_info['customfieldsId'], 0, 24) != 'Title_profileOptionValue' ){
							$temp_id = $cf_info['customfieldsId'];
							$temp_val = isset( $post_data[$temp_id] ) ? sanitize_text_field( $post_data[$temp_id] ) : '';
							$pt_arr[$temp_id]=$temp_val;
						}	
					}
					$ppvc_date = new DateTime();
					$ppvc_date = date_format($ppvc_date,"d.m.Y");
					$max_distance = isset( $post_data['max_distance'] ) ? sanitize_text_field( $post_data['max_distance'] ) : 0;
					$empgroup_ID = isset( $post_data['empgroup_ID'] ) ? sanitize_text_field( $post_data['empgroup_ID'] ) : '';
					$tagid = isset( $post_data['tagid'] ) ? sanitize_text_field( $post_data['tagid'] ) : '';
				 			
					// project 1440
					if($jobID != ''){
						$table_ps_jobs =$prosol_prefix . 'jobs';
						 $officeidfromJobs = $wpdb->get_var("SELECT officeid FROM $table_ps_jobs WHERE site_id = $siteid AND jobid=$jobID");
						if ( !is_null( $officeidfromJobs ) ) {
							$default_office = $officeidfromJobs;
						}
					}
				}

				$profession  = 0;

				if ( isset( $post_data['profession'] ) && is_array( $post_data['profession'] ) ) {
					$profession = array();
					foreach ( $post_data['profession'] as $index => $professionID ) {
						$profession[] = array( 'professionID' => sanitize_text_field( $professionID ) );
					}
				}
								
				/* if ( empty( $title ) ) {
					$validation_errors['title']['title_empty'] = esc_html__( 'Please enter title', 'prosolwpclient' );
					$tab_error_ref['lastname']                       = 1;
				} */
				if ( empty( $lastname ) ) {
					$validation_errors['lastname']['lastname_empty'] = esc_html__( 'Please enter family name', 'prosolwpclient' );
					$tab_error_ref['lastname']                       = 1;
				} elseif ( strlen( $lastname ) > 50 ) {
					$validation_errors['lastname']['lastname_max_crossed'] = esc_html__( 'Family Name could not be more than 50 characters', 'prosolwpclient' );
					$tab_error_ref['lastname']                             = 1;
				}
				if ( empty( $firstname ) ) {
					$validation_errors['firstname']['firstname_empty'] = esc_html__( 'Please enter first given name', 'prosolwpclient' );
					$tab_error_ref['firstname']                        = 1;
				} elseif ( strlen( $lastname ) > 50 ) {
					$validation_errors['firstname']['firstname_max_crossed'] = esc_html__( 'First Given Name could not be more than 50 characters', 'prosolwpclient' );
					$tab_error_ref['firstname']                              = 1;
				}
				if ( empty( $street ) ) {
					$validation_errors['street']['street_empty'] = esc_html__( 'Please enter road', 'prosolwpclient' );
					$tab_error_ref['street']                     = 1;
				} elseif ( strlen( $lastname ) > 80 ) {
					$validation_errors['street']['street_max_crossed'] = esc_html__( 'Road could not be more than 80 characters', 'prosolwpclient' );
					$tab_error_ref['street']                           = 1;
				}
				if ( empty( $zip ) ) {
					$validation_errors['zip']['zip_empty'] = esc_html__( 'Please enter postcode', 'prosolwpclient' );
					$tab_error_ref['zip']                  = 1;
				} elseif ( strlen( $zip ) < 4 || strlen( $zip ) > 15 ) {
					$validation_errors['zip']['zip_max_crossed'] = esc_html__( 'Postcode number length should be between 4 and 15 digits', 'prosolwpclient' );
					$tab_error_ref['zip']                        = 1;
				}
				if ( strlen( $city ) > 50 ) {
					$validation_errors['city']['city_max_crossed'] = esc_html__( 'Town could not be more than 50 characters', 'prosolwpclient' );
					$tab_error_ref['city']                         = 1;
				}
				if ( empty( $countryID ) ) {
					$validation_errors['countryID']['countryID_empty'] = esc_html__( 'Please select country', 'prosolwpclient' );
					$tab_error_ref['countryID']                        = 1;
				}

				$is_sixteen = 0;
				if ( ! empty( $birthdate ) ) {
					// $birthdate will first be a string-date
					$then = strtotime( $birthdate );
					//The age to be over, over +16
					$min = strtotime( '+16 years', $then );

					if ( time() > $min ) {
						$is_sixteen = 1;
					}
				}

				// birthdate check
				if ( empty( $birthdate ) ) {
					$validation_errors['birthdate']['birthdate_empty'] = esc_html__( 'Please select date of birth', 'cbxrbooking' );
					$tab_error_ref['birthdate']                        = 1;

				} elseif ( new DateTime( $birthdate ) > new DateTime() ) {
					$validation_errors['birthdate']['date_greater'] = esc_html__( 'Sorry! date of birth is greater than today', 'cbxrbooking' );
					$tab_error_ref['birthdate']                     = 1;
				} elseif ( $is_sixteen == 0 ) {
					$validation_errors['birthdate']['date_child'] = esc_html__( 'Sorry! You must be over 16 years old', 'cbxrbooking' );
					$tab_error_ref['birthdate']                   = 1;
				} else {
					$birthdate_arr = explode( '.', $birthdate );
					if ( ! checkdate( $birthdate_arr[1], $birthdate_arr[0], $birthdate_arr[2] ) ) {
						$validation_errors['birthdate']['date_invalid'] = esc_html__( 'Sorry! date of birth is invalid', 'cbxrbooking' );
						$tab_error_ref['birthdate']                     = 1;
					}
				}

				/* if ( empty( $phone1 ) ) {
					$validation_errors['phone1']['phone1_empty'] = esc_html__( 'Please enter phone', 'prosolwpclient' );
					$tab_error_ref['phone1']                     = 1;
				} else */
				if ( ! empty( $phone1 ) ) {
					if ( strlen( $phone1 ) < 9 || strlen( $phone1 ) > 35 ) {
						$validation_errors['phone1']['phone1_max_crossed'] = esc_html__( 'Phone number length should be between 9 and 35 characters', 'prosolwpclient' );
						$tab_error_ref['phone1']                           = 1;
					}
				}	
				if ( ! empty( $phone2 ) ) {
					if ( strlen( $phone2 ) < 9 || strlen( $phone2 ) > 35 ) {
						$validation_errors['phone2']['phone2_max_crossed'] = esc_html__( 'Phone number length should be between 9 and 35 characters', 'prosolwpclient' );
						$tab_error_ref['phone2']                           = 1;
					}
				}
				/*
				if ( empty( $nationality ) ) {
					$validation_errors['nationality']['nationality_empty'] = esc_html__( 'Please select nationality', 'prosolwpclient' );
					$tab_error_ref['nationality']                          = 1;
				}
				if ( empty( $maritalID ) ) {
					$validation_errors['maritalID']['maritalID_empty'] = esc_html__( 'Please select marital status', 'prosolwpclient' );
					$tab_error_ref['maritalID']                        = 1;
				}
				if ( $gender === '' ) {
					$validation_errors['gender']['gender_empty'] = esc_html__( 'Please select gender', 'prosolwpclient' );
					$tab_error_ref['gender']                     = 1;
				} */
				// profession check
				if ( $profession == 0 || ! is_array( $profession ) && ! sizeof( $profession ) > 0 ) {
					$validation_errors['profession']['profession_empty'] = esc_html__( 'Please select at least one job', 'prosolwpclient' );
					$tab_error_ref['profession']                         = 1;
				}
				// availabilitydate check
				if ( ! empty( $availabilitydate ) ) {

					if ( strtotime(Date( $availabilitydate )) < strtotime(Date( 'd.m.Y' )) ) {
						$validation_errors['availabilitydate']['availabilitydate_less'] = esc_html__( 'Sorry! available from date is less than today', 'cbxrbooking' );
						$tab_error_ref['availabilitydate']                              = 1;
					} else {
						$availabilitydate_arr = explode( '.', $availabilitydate );
						if ( ! checkdate( $availabilitydate_arr[1], $availabilitydate_arr[0], $availabilitydate_arr[2] ) ) {
							$validation_errors['availabilitydate']['availabilitydate_invalid'] = esc_html__( 'Sorry! available from date is invalid', 'cbxrbooking' );
							$tab_error_ref['availabilitydate']                                 = 1;
						}
					}
				}

				// email check
				if ( ! empty( $email ) ) {
					if ( ! is_email( $email ) ) {
						$validation_errors['email']['email_invalid'] = esc_html__( 'Please provide valid email address', 'prosolwpclient' );
						$tab_error_ref['email']                      = 1;
					}
					if ( strlen( $email ) > 200 ) {
						$validation_errors['email']['email_max_crossed'] = esc_html__( 'Email could not be more than 50 characters', 'prosolwpclient' );
						$tab_error_ref['email']                          = 1;
					}
				}

				// expectedsalary check
				if ( ! empty( $salaryExpect ) ) {
					if ( ! is_numeric( $salaryExpect ) ) {
						$validation_errors['expectedsalary']['expectedsalary_invalid'] = esc_html__( 'Please provide valid expectedsalary address', 'prosolwpclient' );
						$tab_error_ref['expectedsalary']                      = 1;
					}
					if ( strlen( $salaryExpect ) > 200 ) {
						$validation_errors['expectedsalary']['expectedsalary_max_crossed'] = esc_html__( 'expectedsalary could not be more than 50 characters', 'prosolwpclient' );
						$tab_error_ref['expectedsalary']                          = 1;
					}
				}

				if ( $information != '' ) {
					if ( strlen( $information ) > 300 ) {
						$validation_errors['information']['information_max_crossed'] = esc_html__( 'Do not forward documents to content could not be more than 300 characters', 'prosolwpclient' );
						$tab_error_ref['information']                                = 1;
					} else {
						$information = esc_html__( 'Do not forward to: ', 'prosolwpclient' ) . $information;
					}
				}

				// education
				$education = array();
				if ( isset( $post_data['education'] ) && is_array( $post_data['education'] ) ) {
					$edu_post_arr = $post_data['education'];

					if ( is_array( $edu_post_arr ) ) {
						if ( sizeof( $edu_post_arr ) > 10 ) {
							$validation_errors['top_errors']['education_general'] = esc_html__( 'Number of education can not be more than 10', 'prosolwpclient' );
							$tab_error_ref['education_general']                   = 2;
						} else if ( sizeof( $edu_post_arr ) > 0 ) {
							foreach ( $edu_post_arr as $index_key => $edu_single_data ) {
								$edu_safe_data = array();
								foreach ( $edu_single_data as $edu_key => $edu_value ) {
									if ( $edu_key == 'start' || $edu_key == 'end' ) {
										if ( $edu_key == 'start' ) {
											if ( empty( $edu_value ) ) {

											} else {

												if ( strlen( $edu_value ) != 4 ) {
													$validation_errors['education']['start'][ $index_key ]['start_invalid'] = esc_html__( 'Please provide valid beginning year', 'prosolwpclient' );
													$tab_error_ref['education']['start'] = 2;
												} elseif ( isset( $edu_single_data['start'] ) && isset( $edu_single_data['end'] ) ) {
													$start_value = sanitize_text_field( $edu_single_data['start'] );
													$end_value   = sanitize_text_field( $edu_single_data['end'] );
													$diff        = intval( $end_value ) - intval( $start_value );
													if ( $diff < 0 ) {
														$validation_errors['education']['end'][ $index_key ]['end_greater'] = esc_html__( 'End year should be greater or equal to beginning year', 'prosolwpclient' );
													$tab_error_ref['education']['end'] = 2;
													}
												}
												$edu_safe_data[ $edu_key ] = '01.01.' . sanitize_text_field( $edu_value );
											}
										}
										if ( $edu_key == 'end' ) {
											if ( empty( $edu_value ) ) {


											} else {

												if ( strlen( $edu_value ) != 4 ) {
													$validation_errors['education']['end'][ $index_key ]['end_invalid'] = esc_html__( 'Please provide valid end year', 'prosolwpclient' );
													$tab_error_ref['education']['end']                                  = 2;
												}
												$edu_safe_data[ $edu_key ] = '01.01.' . sanitize_text_field( $edu_value );
											}

										}

									}
									if ( $edu_key == 'operationAreaID' && is_array( $edu_value ) && sizeof( $edu_value ) > 0 ) {
										$operationAreaIDList = array();
										foreach ( $edu_value as $index => $value ) {
											$operationAreaIDList[] = sanitize_text_field( $value );
										}
										$edu_safe_data['operationAreaIDList'] = implode( ',', $operationAreaIDList );
									}
									if ( $edu_key == 'naceID' && is_array( $edu_value ) && sizeof( $edu_value ) > 0 ) {
										$naceIDList = array();
										foreach ( $edu_value as $index => $value ) {
											$naceIDList[] = sanitize_text_field( $value );
										}
										$edu_safe_data['naceIDList'] = implode( ',', $naceIDList );
									}

									if ( $edu_key == 'typeID' ) {
										if ( empty( $edu_value ) ) {

										} else {
											$edu_safe_data[ $edu_key ] = sanitize_text_field( $edu_value );

										}
									}
									if ( $edu_key == 'detailID' ) {
										if ( empty( $edu_value ) ) {


										} else {
											$edu_safe_data[ $edu_key ] = sanitize_text_field( $edu_value );
										}
									}

									if ( $edu_key == 'zip' ) {
										if ( ! empty( $edu_value ) ) {
											if ( strlen( $edu_value ) < 4 || strlen( $edu_value ) > 15 ) {
												$validation_errors['education']['zip'][ $index_key ]['zip_max_crossed'] = esc_html__( 'Postcode number length should be between 4 and 15 digits', 'prosolwpclient' );
												$tab_error_ref['education']['zip']                                      = 2;
											} else {
												$edu_safe_data[ $edu_key ] = sanitize_text_field( $edu_value );
											}
										}
									}
									if ( $edu_key == 'city' ) {
										if ( ! empty( $edu_value ) ) {
											if ( strlen( $edu_value ) > 50 ) {
												$validation_errors['education']['city'][ $index_key ]['city_max_crossed'] = esc_html__( 'Town could not be more than 50 characters', 'prosolwpclient' );
												$tab_error_ref['education']['city']                                       = 2;
											} else {
												$edu_safe_data[ $edu_key ] = sanitize_text_field( $edu_value );
											}
										}
									}

									if ( $edu_key == 'notes' ) {
										if ( ! empty( $edu_value ) ) {
											if ( strlen( $edu_value ) > 400 ) {
												$validation_errors['education']['notes'][ $index_key ]['notes_max_crossed'] = esc_html__( 'Description could not be more than 400 characters', 'prosolwpclient' );
												$tab_error_ref['education']['notes']                                        = 2;
											} else {
												$edu_safe_data[ $edu_key ] = sanitize_text_field( $edu_value );
											}
										}
									}

									if ( $edu_key == 'federalId' || $edu_key == 'countryId' || $edu_key == 'iscedID' ) {
										if ( $edu_value != '' ) {
											$edu_safe_data[ $edu_key ] = sanitize_text_field( $edu_value );
										}
									}
								}
								$education[] = $edu_safe_data;
							}
						}


					}
				}

				// experience
				$experience = array();
				if ( isset( $post_data['experience'] ) && is_array( $post_data['experience'] ) ) {
					$exp_post_arr = $post_data['experience'];

					if ( is_array( $exp_post_arr ) ) {

						if ( sizeof( $exp_post_arr ) > 10 ) {
							$validation_errors['top_errors']['experience_general'] = esc_html__( 'Number of experience can not be more than 10', 'prosolwpclient' );
							$tab_error_ref['experience_general']                   = 3;
						} else if ( sizeof( $exp_post_arr ) > 0 ) {
							foreach ( $exp_post_arr as $index_key => $exp_single_data ) {
								$exp_safe_data = array();
								foreach ( $exp_single_data as $exp_key => $exp_value ) {
									if ( $exp_key == 'start' || $exp_key == 'end' ) {
										if ( $exp_key == 'start' ) {
											if ( empty( $exp_value ) ) {

											} else {
												if ( strlen( $exp_value ) != 4 ) {
												$validation_errors['experience']['start'][ $index_key ]['start_invalid'] = esc_html__( 'Please provide valid beginning year', 'prosolwpclient' );
												$tab_error_ref['experience']['start']                                    = 3;
												} elseif ( isset( $exp_single_data['start'] ) && isset( $exp_single_data['end'] ) ) {
													$start_value = sanitize_text_field( $exp_single_data['start'] );
													$end_value   = sanitize_text_field( $exp_single_data['end'] );
													$diff        = intval( $end_value ) - intval( $start_value );
													if ( $diff < 0 ) {
														$validation_errors['experience']['end'][ $index_key ]['end_greater'] = esc_html__( 'End year should be greater or equal to beginning year', 'prosolwpclient' );
														$tab_error_ref['experience']['end']                                  = 3;
													}
												}

												$exp_safe_data[ $exp_key ] = '01.01.' . sanitize_text_field( $exp_value );
											}

										}
										if ( $exp_key == 'end' ) {
											 if ( empty( $exp_value ) ) {

											 } else {
											 	if ( strlen( $exp_value ) != 4 ) {
													$validation_errors['experience']['end'][ $index_key ]['end_invalid'] = esc_html__( 'Please provide valid end year', 'prosolwpclient' );
													$tab_error_ref['experience']['end']                                  = 3;
												}

												$exp_safe_data[ $exp_key ] = '01.01.' . sanitize_text_field( $exp_value );
											 }

										}

									}
									if ( $exp_key == 'operationAreaID' && is_array( $exp_value ) && sizeof( $exp_value ) > 0 ) {
										$operationAreaIDList = array();
										foreach ( $exp_value as $index => $value ) {
											$operationAreaIDList[] = sanitize_text_field( $value );
										}
										$exp_safe_data['operationAreaIDList'] = implode( ',', $operationAreaIDList );
									}
									if ( $exp_key == 'naceID' && is_array( $exp_value ) && sizeof( $exp_value ) > 0 ) {
										$naceIDList = array();
										foreach ( $exp_value as $index => $value ) {
											$naceIDList[] = sanitize_text_field( $value );
										}
										$exp_safe_data['naceIDList'] = implode( ',', $naceIDList );
									}
									if ( $exp_key == 'experiencePositionID' && is_array( $exp_value ) && sizeof( $exp_value ) > 0 ) {
										$experiencePositionIDList = array();
										foreach ( $exp_value as $index => $value ) {
											$experiencePositionIDList[] = sanitize_text_field( $value );
										}
										$exp_safe_data['experiencePositionIDList'] = implode( ',', $experiencePositionIDList );
									}

									if ( $exp_key == 'shortNote' ) {
										if ( empty( $exp_value ) ) {

										}
										else {
											if ( strlen( $exp_value ) > 400 ) {
												$validation_errors['experience']['shortNote'][ $index_key ]['short_notes_max_crossed'] = esc_html__( 'Description could not be more than 400 characters', 'prosolwpclient' );
												$tab_error_ref['experience']['shortNote']                                              = 2;
											} else {
												$exp_safe_data[ $exp_key ] = sanitize_text_field( $exp_value );
											}
										}
									}

									if ( $exp_key == 'notes' ) {
										if ( ! empty( $exp_value ) ) {
											if ( strlen( $exp_value ) > 100 ) {
												$validation_errors['experience']['notes'][ $index_key ]['notes_max_crossed'] = esc_html__( 'General  Description could not be more than 100 characters', 'prosolwpclient' );
												$tab_error_ref['experience']['notes']                                        = 2;
											} else {
												$exp_safe_data[ $exp_key ] = sanitize_text_field( $exp_value );
											}
										}
									}

									if ( $exp_key == 'company' ) {
										if ( ! empty( $exp_value ) ) {
											if ( strlen( $exp_value ) > 50 ) {
												$validation_errors['experience']['company'][ $index_key ]['company_max_crossed'] = esc_html__( 'Company / Institution could not be more than 50 characters', 'prosolwpclient' );
												$tab_error_ref['experience']['company']                                          = 2;
											} else {
												$exp_safe_data[ $exp_key ] = sanitize_text_field( $exp_value );
											}
										}
									}

									if ( $exp_key == 'zip' ) {
										if ( ! empty( $exp_value ) ) {
											if ( strlen( $exp_value ) < 4 || strlen( $exp_value ) > 15 ) {
												$validation_errors['experience']['zip'][ $index_key ]['zip_max_crossed'] = esc_html__( 'Postcode number length should be between 4 and 15 digits', 'prosolwpclient' );
												$tab_error_ref['experience']['zip']                                      = 2;
											} else {
												$exp_safe_data[ $exp_key ] = sanitize_text_field( $exp_value );
											}
										}
									}
									if ( $exp_key == 'city' ) {
										if ( ! empty( $exp_value ) ) {
											if ( strlen( $exp_value ) > 50 ) {
												$validation_errors['experience']['city'][ $index_key ]['city_max_crossed'] = esc_html__( 'Town could not be more than 50 characters', 'prosolwpclient' );
												$tab_error_ref['experience']['city']                                       = 2;
											} else {
												$exp_safe_data[ $exp_key ] = sanitize_text_field( $exp_value );
											}
										}
									}

									if ( $exp_key == 'professionID' || $exp_key == 'federalId' || $exp_key == 'countryId' || $exp_key == 'contractID' || $exp_key == 'employmentID' ) {
										if ( $exp_value != '' ) {
											$exp_safe_data[ $exp_key ] = sanitize_text_field( $exp_value );
										}
									}
								}
								$experience[] = $exp_safe_data;
							}
						}

					}
				}

				//skill
				$skill = array();
				if ( isset( $post_data['skill'] ) && is_array( $post_data['skill'] ) ) {
					$skill_post_arr = $post_data['skill'];

					if ( is_array( $skill_post_arr ) && sizeof( $skill_post_arr ) > 0 ) {
						$skill_safe_data = array();
						foreach ( $skill_post_arr as $index => $single_skill_info ) {
							if ( is_array( $single_skill_info ) && sizeof( $single_skill_info ) > 0 ) {
								$single_skill_arr = array();
								foreach ( $single_skill_info as $skill_key => $skill_value ) {
									if ( $skill_key == 'skillID' ) {
										$single_skill_arr[ $skill_key ] = sanitize_text_field( $skill_value );
									}
									if ( $skill_key == 'rating' ) {
										$single_skill_arr[ $skill_key ] = intval( $skill_value );
									}
								}
								$skill_safe_data[] = $single_skill_arr;
							}
						}
						$skill = $skill_safe_data;
					}
				}

				//files
				$files      = array();
				$session_id = session_id();
				if ( isset( $_SESSION[ $session_id ] ) && sizeof( $_SESSION[ $session_id ] ) > 0 ) {
					if ( isset( $_SESSION[ $session_id ]['files'] ) && sizeof( $_SESSION[ $session_id ]['files'] ) > 0 ) {
						$files = $_SESSION[ $session_id ]['files'];
					}
				}
				// others
				$recruitmentsourceID = isset( $post_data['recruitmentsourceID'] ) ? sanitize_text_field( $post_data['recruitmentsourceID'] ) : '';
				$applyfor            = isset( $post_data['applyfor'] ) ? sanitize_text_field( $post_data['applyfor'] ) : '';
				if ( $applyfor != '' ) {
					if ( strlen( $applyfor ) > 80 ) {
						$validation_errors['applyfor']['applyfor_max_crossed'] = esc_html__( 'I am applying specifically for content could not be more than 80 characters', 'prosolwpclient' );
					}
				}
				$othercommunication = isset( $post_data['othercommunication'] ) ? sanitize_text_field( $post_data['othercommunication'] ) : '';
				if ( $othercommunication != '' ) {
					if ( strlen( $othercommunication ) > 2000 ) {
						$validation_errors['othercommunication']['othercommunication_max_crossed'] = esc_html__( 'What else do you want to tell us? content could not be more than 2000 characters', 'prosolwpclient' );
					}
				}

				if ( $applyfor != '' ) {
					$information .= "\n" . esc_html__( 'I apply for: ', 'prosolwpclient' ) . $applyfor;
				}
				if ( $othercommunication != '' ) {
					$information .= "\n" . esc_html__( 'Other message: ', 'prosolwpclient' ) . $othercommunication;
				}

				$safe_data = array(
					'lastname'   => $lastname,
					'firstname'  => $firstname,
					'profession' => $profession,
				);

				if ( isset( $_SESSION[ $session_id ] ) && sizeof( $_SESSION[ $session_id ] ) > 0 ) {
					if ( isset( $_SESSION[ $session_id ]['portrait'] ) && sizeof( $_SESSION[ $session_id ]['portrait'] ) > 0 ) {
						$portrait = array_values( $_SESSION[ session_id() ]['portrait'] )[0];

					}
				}

				$temp_safe_data = array(
					'jobID'               => $jobID,
					'title'               => $title,
					'street'              => $street,
					'zip'                 => $zip,
					'city'                => $city,
					'federalID'           => $federalID,
					'countryID'           => $countryID,
					'birthdate'           => $birthdate,
					'phone1'              => $phone1,
					'phone2'              => $phone2,
					'email'               => $email,
					'salaryExpect'        => $salaryExpect,
					'nationality'         => $nationality,
					'maritalID'           => $maritalID,
					'gender'              => $gender,
					'availabilitydate'    => $availabilitydate,
					'recruitmentsourceID' => $recruitmentsourceID,
					'information'         => $information,
					'portrait'            => $portrait,
					'files'               => $files,
					'BirthCountry'		  => $birthcountry,
					'officeID'			  => $default_office,
				);

				foreach ( $temp_safe_data as $key => $value ) {
					if ( $value !== '' ) {
						$safe_data[ $key ] = $value;
					}
				}

				$safe_data['education']  = $education;
				$safe_data['experience'] = $experience;
				$safe_data['skill']      = $skill;
				$safe_data['source'] = isset( $_GET['source'] ) ? strval($_GET['source']) : '';

				if ( $genset[$issite.'enable_recruitment'] == 'on' ) {
					foreach(array_keys($pt_arr) as $cf_key){
						//remove 'Title_'
						$totalstr=strlen($cf_key)-6;
						$real_cfkey=substr($cf_key,-$totalstr);
						$safe_data[$real_cfkey]= $pt_arr[$cf_key];
					}
					
					$safe_data['pers_privacy_confirmdate'] = $ppvc_date;
					$safe_data['max_distance'] = intval($max_distance);
					$safe_data['empgroup_ID'] = $empgroup_ID;
					$safe_data['tagid'] = $tagid;
				}

			}
			// var_dump($_GET);die;
			if ( sizeof( $validation_errors ) > 0 ) {

			} else {
				$ok_to_process = 1;
				$wpway         = true;

				$header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig( $issite, $wpway );

				if ( $wpway ) {


					$boundary                    = wp_generate_password( 24 );
					$boundary                    = 'abc';
					$header_info['content-type'] = 'multipart/form-data; boundary=' . $boundary;

					$payload = '';

					$api_body = array( "param" => json_encode( $safe_data ) );
					$st = json_decode($api_body['param']);
					
		 		   if($st->experience){
		 		   		foreach ($st->experience as $key => $value) {
		 		   			if (empty($value )) {
		 		   				unset($st->experience);
		 		   			}

		 		   		}
		 		   }

		 		   if($st->education){
		 		   		foreach ($st->education as $key => $value) {
		 		   			$cntt = 0;
		 		   			if (empty($value )) {
		 		   				unset($st->education);
		 		   			} else {
		 		   				foreach ($value as $key => $value1) {
		 		   					$cnt++;
		 		   				}

		 		   				$frontend_setting        = get_option( 'prosolwpclient_frontend' );
								$dafault_nation_ph       = esc_html__( 'Please select one option', 'prosolwpclient' );
								$dafault_nation_selected = null;
								if ( $frontend_setting !== false && isset( $frontend_setting[$issite.'default_nation'] ) ) {
									$dafault_nation_selected = $frontend_setting[$issite.'default_nation'];
								}

		 		   				if($cnt==1 && $value->countryId==$dafault_nation_selected){
		 		   					unset($st->education);
		 		   				}
		 		   			}
		 		   		}
		 		   }

					$api_body1 = array( "param" => json_encode( $st ) );
					// First, add the standard POST fields:
					foreach ( $api_body1 as $name => $value ) {
						if ( is_array( $value ) ) {
							foreach ( $value as $index => $professionID_arr ) {
								foreach ( $professionID_arr as $professionID => $professionValue ) {
									$payload .= '--' . $boundary;
									$payload .= "\r\n";
									$payload .= 'Content-Disposition: form-data; name="' . $name . '[' . $index . ']' . '[' . $professionID . ']' .
									            '"' . "\r\n\r\n";
									$payload .= $professionValue;
									$payload .= "\r\n";
								}
							}
						} else {
							$payload .= '--' . $boundary;
							$payload .= "\r\n";
							$payload .= 'Content-Disposition: form-data; name="' . $name .
							            '"' . "\r\n\r\n";
							$payload .= $value;
							$payload .= "\r\n";
						}
					}

					$local_files = array();
					//add the files to the post-data array
					if ( isset( $_SESSION[ $session_id ] ) && sizeof( $_SESSION[ $session_id ] ) > 0 ) {
						if ( isset( $_SESSION[ $session_id ]['files_location'] ) && sizeof( $_SESSION[ $session_id ]['files_location'] ) > 0 ) {
							$files_location = $_SESSION[ $session_id ]['files_location'];
							if ( is_array( $files_location ) && sizeof( $files_location ) > 0 ) {
								foreach ( $files_location as $file_key => $file_info ) {
									$local_files[ $file_key ] = $file_info['name'];
								}
								add_action('http_api_curl', 'sar_custom_curl_timeout', 9999, 1);
								add_filter( 'http_request_timeout', 'sar_custom_http_request_timeout', 9999 );
								add_filter('http_request_args', 'sar_custom_http_request_args', 9999, 1);
							}
						//var_dump($files_location);
						}
					}
					//var_dump($local_files);	

					// Upload the file
					if ( sizeof( $local_files ) > 0 ) {
						foreach ( $local_files as $index => $local_file ) {
							//							$filename = 'file' . ( $index + 1 );
							$filename = $index;

							$payload .= '--' . $boundary;
							$payload .= "\r\n";
							$payload .= 'Content-Disposition: form-data; name="' . $filename .
							            '"; filename="' . basename( $local_file ) . '"' . "\r\n";
							
							$payload .= 'Content-Type: application/x-object' . "\r\n";
							$payload .= "\r\n";
							$payload .= file_get_contents( $local_file );
							$payload .= "\r\n";
						}
					}
					$payload .= '--' . $boundary . '--';

					if($genset['enable_recruitment']=='on'){
						$api_location = 'recruitment/';
					} else{
						$api_location = 'application/';
					}
					
					$response = wp_remote_post( $api_config['api_url'] . '' . $api_location . 'create',
						array(
							'headers'   => $header_info,
							'body'      => $payload,
							'timeout '  => 10000,
							'sslverify' => is_ssl()
						)
					);
					
					// var_dump($payload);
					//  ini_set('xdebug.var_display_max_depth', -1);
					// ini_set('xdebug.var_display_max_children', -1);
					// ini_set('xdebug.var_display_max_data', -1);
					//  var_dump($response);
					$msg = '';
					if ( ! is_wp_error( $response ) ) {
						$response_data = json_decode( $response['body'] );
						
						// die;
						$hit = 0;
						if ( $response_data->ERROR == '' ) {
							$msg = esc_html__( 'Application has been submitted successfully.', 'prosolwpclient' );
							$hit = 1;

							// after successfully application submit, delete files from the local
							if ( isset( $_SESSION[ $session_id ] ) && sizeof( $_SESSION[ $session_id ] ) > 0 ) {
								if ( isset( $_SESSION[ $session_id ]['files_track'] ) && sizeof( $_SESSION[ $session_id ]['files_track'] ) > 0 ) {
									$uploaded_files = $_SESSION[ $session_id ]['files_track'];

									foreach ( $uploaded_files as $file_key => $file_info ) {
										$deleted = unlink( wp_upload_dir()['basedir'] . '/prosolwpclient/' . $file_key );
										if ( $deleted ) {
											unset( $_SESSION[ $session_id ]['files_track'][ $file_key ] );
										}
									}

									if ( sizeof( $_SESSION[ $session_id ]['files_track'] ) == 0 ) {
										unset( $_SESSION[ $session_id ] );
									}
								}
							}
						} else {
							$msg = $response_data->MESSAGE;
						}
						$success_data['hit'] = $hit;
					} else {

						$msg = sprintf( __( 'Submit response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
					}

					$success_data['msg'] = $msg;

				} else {

					// Initializing the curl
					$cli = curl_init();
					
					if($genset['enable_recruitment']=='on'){
						$api_location = 'recruitment/';
					} else{
						$api_location = 'application/';
					}
					$url_to_call = $api_config['api_url'] . '/' . $api_location .'create/';
					curl_setopt( $cli, CURLOPT_URL, $url_to_call );
					curl_setopt( $cli, CURLOPT_RETURNTRANSFER, true );
					curl_setopt( $cli, CURLOPT_HTTPHEADER, $header_info );

					curl_setopt( $cli, CURLOPT_POST, true );
					$api_body = array( "param" => json_encode( $safe_data ) );

					//add the files to the post-data array
					if ( isset( $_SESSION[ $session_id ] ) && sizeof( $_SESSION[ $session_id ] ) > 0 ) {
						if ( isset( $_SESSION[ $session_id ]['files_location'] ) && sizeof( $_SESSION[ $session_id ]['files_location'] ) > 0 ) {
							$files_location = $_SESSION[ $session_id ]['files_location'];
							if ( is_array( $files_location ) && sizeof( $files_location ) > 0 ) {
								foreach ( $files_location as $file_key => $file_info ) {
									$api_body[ $file_key ] = new CURLFile( $file_info['name'], $file_info['mime'], $file_info['postname'] );
								}
							}
						}
					}


					curl_setopt( $cli, CURLOPT_POSTFIELDS, $api_body );

					$response = curl_exec( $cli );

					$response_data = json_decode( $response );
					
					$msg = '';
					if ( is_object( $response_data ) ) {
						$hit = 0;
						if ( $response_data->ERROR == '' ) {
							$msg = esc_html__( 'Application submitted successfully.', 'prosolwpclient' );
							$hit = 1;

							// after successfully application submit, delete files from the local
							if ( isset( $_SESSION[ $session_id ] ) && sizeof( $_SESSION[ $session_id ] ) > 0 ) {
								if ( isset( $_SESSION[ $session_id ]['files_track'] ) && sizeof( $_SESSION[ $session_id ]['files_track'] ) > 0 ) {
									$uploaded_files = $_SESSION[ $session_id ]['files_track'];

									foreach ( $uploaded_files as $file_key => $file_info ) {
										$deleted = unlink( wp_upload_dir()['basedir'] . '/prosolwpclient/' . $file_key );
										if ( $deleted ) {
											unset( $_SESSION[ $session_id ]['files_track'][ $file_key ] );
										}
									}

									if ( sizeof( $_SESSION[ $session_id ]['files_track'] ) == 0 ) {
										unset( $_SESSION[ $session_id ] );
									}
								}
							}
						} else {
							$msg = esc_html__( $response_data->MESSAGE, 'prosolwpclient' );
						}
						$success_data['hit'] = $hit;
					} else {
						$msg = esc_html__( 'Sorry! Some problem during request. Please try again.', 'prosolwpclient' );
					}

					$success_data['msg'] = $msg;
				}
			}

			$return_response['ok_to_process'] = $ok_to_process;
			$return_response['success']       = $success_data;
			$return_response['error']         = $validation_errors;
			$return_response['tab_error_ref'] = $tab_error_ref;
			
			echo json_encode( $return_response );
			
			wp_die();
		}

		/**
		 * convert any date to iso standard format Y-m-d
		 *
		 * @param $date_to_convert
		 *
		 * @return string
		 */
		public function proSol_convertIsoDateFormat( $date_to_convert ) {

			$formatted_date = DateTime::createFromFormat( 'd.m.Y', $date_to_convert );

			return $formatted_date->format( 'Y-m-d' );
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function proSol_enqueueStyles() {
			wp_enqueue_style( 'dashicons' );

			wp_register_style( 'prosolwpclientcustombootstrap', plugin_dir_url( __FILE__ ) . 'css/prosolwpclientcustombootstrap.css', array(), $this->version, 'all' );
			wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array( 'prosolwpclientcustombootstrap' ), $this->version, 'all' );
			wp_register_style( 'fontawesome', plugin_dir_url( __FILE__ ) . 'css/fontawesome/all.css', array( 'prosolwpclientcustombootstrap' ), $this->version, 'all' );
			wp_register_style( 'chosen.min', plugin_dir_url( __FILE__ ) . 'css/chosen.min.css', array( 'prosolwpclientcustombootstrap' ), $this->version, 'all' );
			wp_register_style( 'jquery.fileupload', plugin_dir_url( __FILE__ ) . 'css/jquery.fileupload.css', array( 'prosolwpclientcustombootstrap' ), $this->version, 'all' );
			wp_register_style( 'sweetalert', plugin_dir_url( __FILE__ ) . 'css/sweetalert.css', array( 'prosolwpclientcustombootstrap' ), $this->version, 'all' );
			wp_register_style( 'dropzone.min', plugin_dir_url( __FILE__ ) . 'css/dropzone.min.css', array( 'prosolwpclientcustombootstrap' ), $this->version, 'all' );
			wp_register_style('bootstrap-icons','https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css',array( 'prosolwpclientcustombootstrap' ), $this->version, 'all');
			wp_register_style( 'fileinput', plugin_dir_url( __FILE__ ) . 'css/fileinput.css', array( 'prosolwpclientcustombootstrap' ), $this->version, 'all' );
			wp_register_style( 'krajeetheme', plugin_dir_url( __FILE__ ) . 'css/krajeetheme/explorer-fa5/theme.css', array( 'prosolwpclientcustombootstrap' ), $this->version, 'all' );

			wp_register_style( 'prosolwpclient-public', plugin_dir_url( __FILE__ ) . 'css/prosolwpclientpublic.css', array(
				'prosolwpclientcustombootstrap',
				'dashicons'
			), $this->version, 'all' );
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function proSol_enqueueScripts() {
			$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
			$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);

			$suffix = ( defined( 'PROSOLWPCLIENT_SCRIPTDEBUG' ) && PROSOLWPCLIENT_SCRIPTDEBUG ) ? '' : '.min';

			//wp_register_script( 'jquery.min', plugin_dir_url( __FILE__ ) . 'js/jquery' . $suffix . '.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'bootstrap.bundle.min', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle' . $suffix . '.js', array( 'jquery' ), $this->version, false );
			//wp_register_script( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'js/jquery-ui' . $suffix . '.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'jquery.validate.min', plugin_dir_url( __FILE__ ) . 'js/jquery.validate' . $suffix . '.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'jquery.formtowizard', plugin_dir_url( __FILE__ ) . 'js/jquery.formtowizard.js', array(
				'jquery',
				'jquery.validate.min'
			), $this->version, false );
			wp_register_script( 'mustache.min', plugin_dir_url( __FILE__ ) . 'js/mustache' . $suffix . '.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'chosen.jquery.min', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery' . $suffix . '.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'sweetalert.min', plugin_dir_url( __FILE__ ) . 'js/sweetalert' . $suffix . '.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'fileinput', plugin_dir_url( __FILE__ ) . 'js/fileinput.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'krajeetheme', plugin_dir_url( __FILE__ ) . 'css/krajeetheme/explorer-fa5/theme.js', array( 'jquery' ), $this->version, false );

			//wp_register_script( 'jquery.ui.widget', plugin_dir_url( __FILE__ ) . 'js/jqueryfileupload/vendor/jquery.ui.widget.js', array( 'jquery' ), $this->version, false );
			/*wp_register_script( 'load-image.all', plugin_dir_url( __FILE__ ) . 'js/jqueryfileupload/jssaveas/load-image.all.min.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'canvas-to-blob', plugin_dir_url( __FILE__ ) . 'js/jqueryfileupload/jssaveas/canvas-to-blob.min.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'jquery.iframetransport', plugin_dir_url( __FILE__ ) . 'js/jqueryfileupload/jquery.iframetransport.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'jquery.fileupload', plugin_dir_url( __FILE__ ) . 'js/jqueryfileupload/jquery.fileupload.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'jquery.fileuploadprocess', plugin_dir_url( __FILE__ ) . 'js/jqueryfileupload/jquery.fileuploadprocess.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'jquery.fileuploadvalidate', plugin_dir_url( __FILE__ ) . 'js/jqueryfileupload/jquery.fileuploadvalidate.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'dropzone.min', plugin_dir_url( __FILE__ ) . 'js/dropzone.min.js', array( 'jquery' ), $this->version, false );

			wp_register_script( 'jquery.xdr-transport', plugin_dir_url( __FILE__ ) . 'js/jqueryfileupload/cors/jquery.xdr-transport.js', array( 'jquery' ), $this->version, false );*/
			
			wp_register_script( 'jquery.ui.widget', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/vendor/jquery.ui.widget.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'tmpl', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/jssaveas/tmpl.min.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'load-image.all', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/jssaveas/load-image.all.min.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'canvas-to-blob', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/jssaveas/canvas-to-blob.min.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'blueimp-gallery', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/jssaveas/jquery.blueimp-gallery.min.js', array( 'jquery' ), $this->version, false );//
			wp_register_script( 'jquery.iframetransport', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/jquery.iframe-transport.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'jquery.fileupload', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/jquery.fileupload.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'jquery.fileuploadprocess', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/jquery.fileupload-process.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'jquery.fileuploadvalidate', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/jquery.fileupload-validate.js', array( 'jquery' ), $this->version, false );
			wp_register_script( 'dropzone.min', plugin_dir_url( __FILE__ ) . 'js/dropzone.min.js', array( 'jquery' ), $this->version, false );

			wp_register_script( 'jquery.xdr-transport', plugin_dir_url( __FILE__ ) . 'js/jQuery-File-Upload-master/js/cors/jquery.xdr-transport.js', array( 'jquery' ), $this->version, false );


			wp_register_script( 'prosolwpclient-public', plugin_dir_url( __FILE__ ) . 'js/prosolwpclientpublic.js', array(
				'jquery',
				'jquery.formtowizard'
			), $this->version, false );

			// Localize the script with new data
			$translation_array = array(
				'select_options'           => esc_html__( 'Select Some Options', 'prosolwpclient' )
			);
			wp_localize_script( 'chosen.jquery.min', 'prosolObj', $translation_array );
			//45760, change step label
			$step_label = get_option('prosolwpclient_applicationform');
			$prosoldes = get_option('prosolwpclient_designtemplate');

			$translation_array = array(
				'ajaxurl'                          => admin_url( 'admin-ajax.php' ),
				'nonce'                            => wp_create_nonce( 'prosolwpclient' ),
				'get_permalink_url'                => get_permalink(),
				'file'                             => esc_html__( 'File', 'prosolwpclient' ),
				'photo'                            => esc_html__( 'Photo', 'prosolwpclient' ),
				'choose'                           => esc_html__( 'Choose', 'prosolwpclient' ),
				'to_edit'                          => esc_html__( 'To Edit', 'prosolwpclient' ),
				'edu_entry_delete_alart_msg'       => esc_html__( 'Are you sure to delete this skill entry ?', 'prosolwpclient' ),
				'file_delete_alart_msg'            => esc_html__( 'Are you sure to delete this file entry ?', 'prosolwpclient' ),
				'max_total_file_size_exceed_alert' => esc_html__( 'Sorry! Your already uploaded total file size limit crossed. Please delete existing file and try again. You can upload no more than ', 'prosolwpclient' ),
				'delete_err_msg'                   => esc_html__( 'Sorry! Some problem during deletion. Please try again.', 'prosolwpclient' ),
				'federal_list_empty' 			   => esc_html__( 'Country selected doesnt have federal list' , 'prosolwpclient' ),

				'education'              => esc_html__( 'Education ', 'prosolwpclient' ),
				'experience'             => esc_html__( 'Experience ', 'prosolwpclient' ),
				'attach'                 => esc_html__( 'Attachment ', 'prosolwpclient' ),
				'delete'                 => esc_html__( 'Delete', 'prosolwpclient' ),
				'training_practice_ph'   => esc_html__( 'Please Select Training / Practice', 'prosolwpclient' ),
				'federal_ph'             => esc_html__( 'Please Select Federal State', 'prosolwpclient' ),
				'next'                   => esc_html__( $prosoldes[$issite.'desbtnappformnext'], 'prosolwpclient' ),
				'prev'                   => esc_html__( $prosoldes[$issite.'desbtnappformback'], 'prosolwpclient' ),
				'stepstr'                => esc_html__( $step_label[$issite.'step_label'], 'prosolwpclient'),
				'file_ext'               => esc_html__( 'pdf, doc, docx, xls, xlsx, txt, odt, ods, odp, rtf, pps, ppt, pptx, ppsx, vcf, msg, eml, ogg, mp3, wav, wma, asf, mov, avi, mpg, mpeg, mp4, wmf, 3g2, 3gp, png, jpg, jpeg, gif, bmp, tif, tiff, key, numbers, pages', 'prosolwpclient' ),
				'photo_ext'              => esc_html__( 'gif, jpg, jpeg, png', 'prosolwpclient' ),
				
				'lastname_empty'         => esc_html__( 'Please enter family name', 'prosolwpclient' ),
				'firstname_empty'        => esc_html__( 'Please enter first given name', 'prosolwpclient' ),
				'street_empty'           => esc_html__( 'Please enter road', 'prosolwpclient' ),
				'city_empty'             => esc_html__( 'Please enter town', 'prosolwpclient' ),
				'zip_empty'              => esc_html__( 'Please enter Postcode.', 'prosolwpclient' ),
				'zip_digit'              => esc_html__( 'Please enter only Numbers.', 'prosolwpclient' ),
				'zip_min'                => esc_html__( 'Postcode number length should be between 4 and 15 digits.', 'prosolwpclient' ),
				'zip_max'                => esc_html__( 'Postcode number length should be between 4 and 15 digits.', 'prosolwpclient' ),
				'countryID_empty'        => esc_html__( 'Please select country', 'prosolwpclient' ),
				'birthdate_empty'        => esc_html__( 'Please select date of birth', 'prosolwpclient' ),
				'profession_empty'       => esc_html__( 'Please select at least one job', 'prosolwpclient' ),
				'expectedsalary_digit'              => esc_html__( 'Please enter only Numbers.', 'prosolwpclient' ),
			
				'download'            => esc_html__( 'Download', 'prosolwpclient' ),
				'processing'          => esc_html__( 'Processing...', 'prosolwpclient' ),
				'file_upload_failed'  => esc_html__( 'File Upload Failed', 'prosolwpclient' ),
				'upload_common_error' => esc_html__( 'Sorry! Some problem during uploading. Please try again.', 'prosolwpclient' ),

				'sidetitle_required'       => esc_html__( 'Please enter title.', 'prosolwpclient' ),
				'file_type_required'       => esc_html__( 'File type should be checked', 'prosolwpclient' ),
				'newfilename_required'     => esc_html__( 'File should be uploaded', 'prosolwpclient' ),
				'futuredate_restrict_msg'  => esc_html__( 'Sorry! date of birth is greater than today.', 'prosolwpclient' ),
				'pastdate_restrict_msg'    => esc_html__( 'Sorry! availability date is less than today.', 'prosolwpclient' ),
				'under_sixteen_year_msg'   => esc_html__( 'Sorry! You must be over 16 years old.', 'prosolwpclient' ),
				'apply_again'              => esc_html__( 'Enter another Application', 'prosolwpclient' ),
				'startyearlessorequal_msg' => esc_html__( 'Start year should be less or equal to end year.', 'prosolwpclient' ),
				'endyeargraterorequal_msg' => esc_html__( 'End year should be greater or equal to beginning year.', 'prosolwpclient' ),
				//'app_submit_err_msg'       => esc_html__( 'Sorry! There exists some error in fields of tab ', 'prosolwpclient' ),
				'phone_invalid'            => esc_html__( 'Please enter only digit or + or -', 'prosolwpclient' ),
				'letters_only_msg'         => esc_html__( 'Please enter only characters', 'prosolwpclient' ),
				'select_options'           => esc_html__( 'Select Some Options', 'prosolwpclient' ),

				'required'    => esc_html__( 'This field is required.', 'prosolwpclient' ),
				'remote'      => esc_html__( 'Please fix this field.', 'prosolwpclient' ),
				'email'       => esc_html__( 'Please enter a valid email address.', 'prosolwpclient' ),
				'salaryExpect' => esc_html__( 'Please enter a valid expectedsalary address.', 'prosolwpclient' ),
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
				'recaptcha'   => esc_html__( 'Please check the captcha.', 'prosolwpclient' ),

				'wizardsteps'             => array(
					'1' => esc_html__( 'Personal Data', 'prosolwpclient' ),
					'2' => esc_html__( 'Education', 'prosolwpclient' ),
					'3' => esc_html__( 'Work Experience', 'prosolwpclient' ),
					'4' => esc_html__( 'Expertise', 'prosolwpclient' ),
					'5' => esc_html__( 'Side Dishes', 'prosolwpclient' ),
					'6' => esc_html__( 'Others', 'prosolwpclient' )
				),
				'form_tab_key_names'      => CBXProSolWpClient_Helper::proSol_formTabKeyNames(),
				'form_tab_key'            => esc_html__( 'in tab', 'prosolwpclient' ),
				'form_unload_alert'       => esc_html__( 'Are you sure? You didn\'t finish the form!', 'prosolwpclient' ),
				'limit_cross_edu_tab_msg' => esc_html__( 'Sorry! Maximum tab limit for education crossed. Please remove existing tab to add new.', 'prosolwpclient' ),
				'limit_cross_exp_tab_msg' => esc_html__( 'Sorry! Maximum tab limit for experience crossed. Please remove existing tab to add new.', 'prosolwpclient' ),
				'all_empty_fields_tab_msg'   => esc_html__( 'Sorry! Please fill mandatory fields to add new tab.', 'prosolwpclient' ),
				'invalid_tab_count_msg'   => esc_html__( 'There is an error in', 'prosolwpclient' )
			);
			wp_localize_script( 'prosolwpclient-public', 'prosolObj', $translation_array );
		}

	}
?>