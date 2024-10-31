<?php

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<script type="text/javascript">
	(function ($) {
		'use strict';

		$(window).on("beforeunload", function() {
			if(!$('.prosolapp_submit_msg p').hasClass('alert-success')){
				//return prosolObj.form_unload_alert;
			}
			
		});
	})(jQuery);
</script>

<?php

	/**
	 * Provide a public-facing view for the plugin
	 *
	 * This file is used to markup the public-facing aspects of the plugin.
	 *
	 * @link       https://www.prosolution.com
	 * @since      1.0.0
	 *
	 * @package    Prosolwpclient
	 * @subpackage Prosolwpclient/public/templates
	 */
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
	$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
	$siteid		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);
	$hassource = isset( $_GET['source'] ) ? strval($_GET['source']) : '';

	global $wpdb;global $prosol_prefix;
	$table_ps_title             = $prosol_prefix . 'title';
	$table_ps_marital           = $prosol_prefix . 'marital';
	$table_ps_federal           = $prosol_prefix . 'federal';
	$table_ps_country           = $prosol_prefix . 'country';
	$table_ps_education         = $prosol_prefix . 'education';
	$table_ps_isced             = $prosol_prefix . 'isced';
	$table_ps_educationlookup   = $prosol_prefix . 'educationlookup';
	$table_ps_recruitmentsource = $prosol_prefix . 'recruitmentsource';

	$title_arr             = $wpdb->get_results( "SELECT * FROM $table_ps_title as title WHERE title.site_id='$siteid' ORDER BY title.name ASC", ARRAY_A );
	$marital_arr           = $wpdb->get_results( "SELECT * FROM $table_ps_marital as marital WHERE marital.site_id='$siteid' ORDER BY marital.name ASC", ARRAY_A );
	$federal_arr           = $wpdb->get_results( "SELECT * FROM $table_ps_federal as federal WHERE federal.site_id='$siteid' ORDER BY federal.name ASC", ARRAY_A );
	$country_arr           = $wpdb->get_results( "SELECT * FROM $table_ps_country as country WHERE country.site_id='$siteid' ORDER BY country.name ASC", ARRAY_A );
	$education_arr         = $wpdb->get_results( "SELECT * FROM $table_ps_education as education WHERE education.site_id='$siteid' ORDER BY education.name ASC", ARRAY_A );

	$isced_arr             = $wpdb->get_results( "SELECT * FROM $table_ps_isced as isced WHERE isced.site_id='$siteid' ORDER BY isced.name ASC", ARRAY_A );
	$recruitmentsource_arr = $wpdb->get_results( "SELECT * FROM $table_ps_recruitmentsource as recruitmentsource WHERE recruitmentsource.site_id='$siteid' ORDER BY recruitmentsource.name ASC", ARRAY_A );

	$frontend_setting        = get_option( 'prosolwpclient_frontend' );
	$pol 					 = get_option('prosolwpclient_privacypolicy');
	$dafault_nation_ph       = esc_html__( 'Please select one option', 'prosolwpclient' );
	$dafault_nation_selected = null;
	if ( $frontend_setting !== false && isset( $frontend_setting[$issite.'default_nation'] ) ) {
		$dafault_nation_selected = $frontend_setting[$issite.'default_nation'];
	}
	$list_opt_appform = get_option('prosolwpclient_applicationform');
	$isrec= $frontend_setting[$issite.'enable_recruitment'];

	$prosoldes = get_option('prosolwpclient_designtemplate');
	$pstemplate = $prosoldes[$issite.'destemplate'];
	$prosoldescolor=$prosoldes[$issite.'desmaincolor'];	

	if($pstemplate!=1 && $isrec=="on"){
		$lightercolor= CBXProSolWpClient_Helper::shadeColor($prosoldescolor,60);
		$prosoldesfont= CBXProSolWpClient_Helper::proSol_getFontName($issite);
?>
	<style>
		div.prosolwpclientcustombootstrap, div.prosolwpclientcustombootstrap ::placeholder 
		{font-family:<?php echo $prosoldesfont ?> !important;}

		.prosolwpclientcustombootstrap input:focus {
			border-color: <?php echo $prosoldescolor ?> !important;
			box-shadow: 0 0 1px <?php echo $prosoldescolor ?> inset !important;
		}

		.prosolwpclientcustombootstrap .dashicons,
		.prosolwpclientcustombootstrap .dashicons:hover {
			color: <?php echo $prosoldescolor ?> !important;	
		}

		.prosolwpclientcustombootstrap .alert-info {
			color: <?php echo $lightercolor ?> !important;
			background-color: <?php echo $lightercolor ?> !important;
			border-color: <?php echo $lightercolor ?> !important;
		}

		.prosolwpclientcustombootstrap .contop-button {
			display:inline-flex;
			flex-flow: row wrap; 
			padding-right:4em;
		} 

		.prosolwpclientcustombootstrap .btnprosoldes {
			cursor:pointer;
		} 

		.prosolwpclientcustombootstrap .contop-button > a.btnprosoldes {
			margin-bottom:.5em;		
			flex: 1 1 auto;
		} 

		@media ( max-width: 1024px) {
			.prosolwpclientcustombootstrap .contop-button > a.btnprosoldes {
				padding-right:0px; 							
				flex-basis: 100%;
			}
			.prosolwpclientcustombootstrap .btnprosoldes, 
			.prosolwpclientcustombootstrap a.btnprosoldes {
				margin-right:0px !important;
			}	
			
			.prosolwpclientcustombootstrap .contop-button {
				padding-right:0px;
				width:100%;	
			}
		}

		.prosolwpclientcustombootstrap .btnprosoldes, 
		.prosolwpclientcustombootstrap a.btnprosoldes {
			color:#ffffff !important;
			background-color: <?php echo $prosoldescolor ?> !important;
			border-radius: 8px !important;
			box-shadow: 1px 2px 2px 2px #c4c3c3 !important;
			display: inline-block !important;
			border: 8px solid <?php echo $prosoldescolor ?> !important;
			margin-right:1rem;
		}
		
		.prosolwpclientcustombootstrap .btnprosoldes:hover, 
		.prosolwpclientcustombootstrap a.btnprosoldes:hover {
			color: <?php echo $prosoldescolor ?> !important;
			background-color: #ffffff !important;
			border-color: #ffffff !important;
			border-radius: 8px !important;
			box-shadow: 1px 2px 2px 2px #c4c3c3 !important;
			display: inline-block !important;
			margin-right:1rem;
		}

		.prosolwpclientcustombootstrap .btnprosoldes-step, 
		.prosolwpclientcustombootstrap a.btnprosoldes-step {
			color:#ffffff !important;
			background-color: <?php echo $prosoldescolor ?> !important;
			border-radius: 8px !important;
			box-shadow: 1px 2px 2px 2px #c4c3c3 !important;
			display: inline-block !important;
			border: 2px solid <?php echo $prosoldescolor ?> !important;
		}
		
		.prosolwpclientcustombootstrap .btnprosoldes-step:hover, 
		.prosolwpclientcustombootstrap a.btnprosoldes-step:hover {
			color: <?php echo $prosoldescolor ?> !important;
			background-color: #ffffff !important;
			border-color: #ffffff !important;
			border-radius: 8px !important;
			box-shadow: 1px 2px 2px 2px #c4c3c3 !important;
			display: inline-block !important;	
			box-sizing: border-box !important;	
		}

		.prosolwpclientcustombootstrap button[disabled].btnprosoldes-step {
			height:2.6em;
		}					

		.prosolwpclientcustombootstrap .commands {
			overflow: inherit !important;
		}
		/* Base for label.checkbox-inline styling */
		/* The container */
		.prosolwpclientcustombootstrap .checkbox-inline {
			display: block;
			position: relative;
			padding-left: 35px;
			margin-bottom: 12px;
			cursor: pointer;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}

		/* Hide the browser's default checkbox */
		.prosolwpclientcustombootstrap .checkbox-inline input {
			position: absolute;
			opacity: 0;
			cursor: pointer;
			height: 0;
			width: 0;
		}

		/* Create a custom checkbox */
		.prosolwpclientcustombootstrap .checkmark {
		position: absolute;
		top: 8px;
		left: 2px;
		height: 16px;
		width: 16px;
		background-color: #eee;
		}

		.prosolwpclientcustombootstrap .checkmark-layer {
		position: absolute;
		top: 3px;
		left: 2px;
		height: 16px;
		width: 16px;
		background-color: #eee;
		}

		/* On mouse-over, add a grey background color */
		.prosolwpclientcustombootstrap .checkbox-inline:hover input ~ .checkmark,
		.prosolwpclientcustombootstrap .checkbox-inline:hover input ~ .checkmark-layer {
		background-color: #ccc;
		}

		/* When the checkbox is checked, add a custom prosoldef background */
		.prosolwpclientcustombootstrap .checkbox-inline input:checked ~ .checkmark,
		.prosolwpclientcustombootstrap .checkbox-inline input:checked ~ .checkmark-layer {
		background-color: <?php echo $prosoldescolor ?>;
		}

		/* Create the checkmark/indicator (hidden when not checked) */
		.prosolwpclientcustombootstrap .checkmark:after,
		.prosolwpclientcustombootstrap .checkmark-layer:after {
		content: "";
		position: absolute;
		display: none;
		}

		/* Show the checkmark when checked */
		.prosolwpclientcustombootstrap .checkbox-inline input:checked ~ .checkmark:after,
		.prosolwpclientcustombootstrap .checkbox-inline input:checked ~ .checkmark-layer:after {
		display: block;
		}

		/* Style the checkmark/indicator */
		.prosolwpclientcustombootstrap .checkbox-inline .checkmark:after,
		.prosolwpclientcustombootstrap .checkbox-inline .checkmark-layer:after {
		left: 5px;
		top: 2px;
		width: 6px; /*7 12 */
		height: 10px;
		border: solid white;
		border-width: 0 2px 2px 0; /* 0 3 3 0*/
		-webkit-transform: rotate(43deg);
		-ms-transform: rotate(43deg);
		transform: rotate(30deg); /*45 */
		}
		
		/* Base for label.radio-inline styling */
		/* The container */
		.prosolwpclientcustombootstrap .radio-inline,
		.prosolwpclientcustombootstrap .radio-inline-unused {
			display: block;
			position: relative;
			padding-left: 35px;
			margin-bottom: 12px;
			cursor: pointer;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}

		/* Hide the browser's default checkbox */
		.prosolwpclientcustombootstrap .radio-inline input,
		.prosolwpclientcustombootstrap .radio-inline-unused input {
			position: absolute;
			opacity: 0;
			cursor: pointer;
			height: 0;
			width: 0;
		}

		/* Create a custom checkbox */
		.prosolwpclientcustombootstrap .radiomark {
		position: absolute;
		top: 12px;
		left: 3px;
		height: 12px;
		width: 12px;
		box-shadow: 0 0 0 1pt <?php echo $prosoldescolor ?>; 
		border-radius:8px;
		}

		.prosolwpclientcustombootstrap .radiomarksidedish {
		position: absolute;
		left: 3px;
		height: 12px;
		width: 12px;
		box-shadow: 0 0 0 1pt <?php echo $prosoldescolor ?>; 
		border-radius:8px;
		}	

		.prosolwpclientcustombootstrap .radiomark-layer,
		.prosolwpclientcustombootstrap .radiomarksidedish-layer {
		position: absolute;
		top: 3px;
		left: 2px;
		height: 16px;
		width: 16px;
		background-color: #eee;
		}

		/* On mouse-over, add a grey background color */
		.prosolwpclientcustombootstrap .radio-inline:hover input ~ .radiomark,
		.prosolwpclientcustombootstrap .radio-inline:hover input ~ .radiomark-layer {
		
		}

		/* When the checkbox is checked, add a custom prosoldef background */
		.prosolwpclientcustombootstrap .radio-inline input:checked ~ .radiomark,
		.prosolwpclientcustombootstrap .radio-inline input:checked ~ .radiomark-layer {
		
		}

		/* Create the checkmark/indicator (hidden when not checked) */
		.prosolwpclientcustombootstrap .radiomark:after,
		.prosolwpclientcustombootstrap .radiomark-layer:after,
		.prosolwpclientcustombootstrap .radiomarksidedish:after,
		.prosolwpclientcustombootstrap .radiomarksidedish-layer:after {
		content: "";
		position: absolute;
		display: none;
		}

		/* Show the checkmark when checked */
		.prosolwpclientcustombootstrap .radio-inline input:checked ~ .radiomark:after,
		.prosolwpclientcustombootstrap .radio-inline input:checked ~ .radiomark-layer:after,
		.prosolwpclientcustombootstrap .radio-inline-unused input:checked ~ .radiomark:after,
		.prosolwpclientcustombootstrap .radio-inline-unused input:checked ~ .radiomark-layer:after,
		.prosolwpclientcustombootstrap .radio-inline input:checked ~ .radiomarksidedish:after,
		.prosolwpclientcustombootstrap .radio-inline input:checked ~ .radiomarksidedish-layer:after,
		.prosolwpclientcustombootstrap .radio-inline-unused input:checked ~ .radiomarksidedish:after,
		.prosolwpclientcustombootstrap .radio-inline-unused input:checked ~ .radiomarksidedish-layer:after {
		display: block;
		}

		/* Style the checkmark/indicator */
		.prosolwpclientcustombootstrap .radio-inline .radiomark:after,
		.prosolwpclientcustombootstrap .radio-inline .radiomark-layer:after,
		.prosolwpclientcustombootstrap .radio-inline-unused .radiomark:after,
		.prosolwpclientcustombootstrap .radio-inline-unused .radiomark-layer:after,
		.prosolwpclientcustombootstrap .radio-inline .radiomarksidedish:after,
		.prosolwpclientcustombootstrap .radio-inline .radiomarksidedish-layer:after,
		.prosolwpclientcustombootstrap .radio-inline-unused .radiomarksidedish:after,
		.prosolwpclientcustombootstrap .radio-inline-unused .radiomarksidedish-layer:after {
		left: 1px;
		top: 1px;
		border: solid <?php echo $prosoldescolor ?>;
		border-width: 5px;
		border-radius: 8px;
		}

		.prosolwpclientcustombootstrap .gender-parent {
			display: flex;
		}
		.prosolwpclientcustombootstrap .gender-radio {
			margin-right: 14px;
		}		

	</style>		
<?php
	} else{ 
?>
	<style>
		.prosolwpclientcustombootstrap .gender-parent {
			display: flex;
		}
		.prosolwpclientcustombootstrap .gender-radio {
			margin-right: 14px;
		}		
	</style>	
<?php
	}					
?>
<div class="container-fluid" >
	<p class="contop-button">
		<?php if($jobid){ ?>
			<?php if($pstemplate==1 || $isrec=='off'){ ?>	
				<a class="btn btn-primary" href="<?php 
					$arr_arg = array('type'  => 'details',	'jobid' => $jobid);
					if(isset($_GET['siteid'])){
						$arr_arg['siteid']=strval($_GET['siteid']);
					} 
					if(isset($_GET['source'])){
						$arr_arg['source']=$hassource;
					}	
					echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 			
				?>#anchorwp"
					role="button"><?php esc_html_e( 'Back to Job Details', 'prosolwpclient' ); ?></a>
				<a class="btn btn-primary"	href="<?php 
					$arr_arg = array('type'  => 'result',	'jobid' => $jobid);
					if(isset($_GET['siteid'])){
						$arr_arg['siteid']=strval($_GET['siteid']);
					}
					if(isset($_GET['source'])){
						$arr_arg['source']=$hassource;
					}	
					echo add_query_arg( $arr_arg, esc_url( get_permalink() ) );	
				?>#anchorwp"
					role="button"><?php esc_html_e( 'Back to Job Listing', 'prosolwpclient' ); ?></a>
			<?php }else{ ?>	
				<a class="btn btnprosoldes" href="<?php 
					$arr_arg = array('type'  => 'details',	'jobid' => $jobid, 'searchlist' => strval($_GET['searchlist']));
					if(isset($_GET['siteid'])){
						$arr_arg['siteid']=strval($_GET['siteid']);
					}
					if(isset($_GET['source'])){
						$arr_arg['source']=$hassource;
					}	
					echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 
				?>#anchorwp"
					role="button"><?php echo $prosoldes[$issite.'desbtnappformtodetails'] ?></a>
				<a class="btn btnprosoldes"	href="<?php 
					$arr_arg = array('type'  => 'search',	'jobid' => $jobid, 'searchlist' => strval($_GET['searchlist']));
					if(isset($_GET['siteid'])){
						$arr_arg['siteid']=strval($_GET['siteid']);
					}
					if(isset($_GET['source'])){
						$arr_arg['source']=$hassource;
					}	
					echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 
				?>#anchorwp"
					role="button"><?php echo $prosoldes[$issite.'desbtnappformtosearch'] ?></a>
			<?php } ?>	
			<?php 		
			if($isrec=="on"){ 
				$prof_id_mustache = '';
				$prof_name_mustache = '';
				$prof_showinappli_mustache = '';
				$skill_id_mustache = '';
				$skillgroup_id_mustache = '';
				$skillrate_val_mustache = '';
				$skill_name_mustache = '';
				$skillgroup_name_mustache = '';
				$srate_level_mustache = '';
				$srate_name_mustache = '';
				$srate_group_mustache ='';

				$apiinvalid = 0; 
				$header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig($issite);
				$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
				$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);
				
				if ( is_array( $header_info ) && sizeof( $header_info ) > 0 && $is_api_setup ) {
					
					//for profession
					$response = wp_remote_get( $api_config['api_url'] . 'recruitment/jobprofession/' . $jobid, array( 'headers' => $header_info ) );
					//var_dump($response);
					if ( ! is_wp_error( $response ) ) {							
						$response_data = json_decode( $response['body'] )->data;
						if(gettype($response_data) == 'object'){								
							$prof_id_arr = $response_data -> id;
							$prof_name_arr = $response_data -> name;
							$prof_showinappli_arr = $response_data -> selected;
							$prof_id_mustache = join(",",$prof_id_arr);
							$prof_name_mustache = join(",",$prof_name_arr);
							$prof_showinappli_mustache = join(",",$prof_showinappli_arr);							
						} else{
							$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response['body']  );
							$apiinvalid = 1;
						}						
					} else {
						$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
						$apiinvalid = 1;
					}
					
					//for skill 
					$response = wp_remote_get( $api_config['api_url'] . 'recruitment/jobskill/' . $jobid, array( 'headers' => $header_info ) );
					//var_dump($response);
					if ( ! is_wp_error( $response ) ) {
						if(gettype($response_data) == 'object'){
							$response_data = json_decode( $response['body'] )->data;
							$skillgroup_id_arr = $response_data -> skillgroup_id;
							$skill_id_arr = $response_data -> skill_id;
							$skill_val_arr = $response_data -> rate_level;
							$skillgroup_name_arr = $response_data -> skillgroup_name;
							$skill_name_arr = $response_data -> skill_name;
							$skill_rate_arr = $response_data -> rate;

							$skillgroup_id_mustache = join(",",$skillgroup_id_arr);
							$skill_id_mustache = join(",",$skill_id_arr);							
							$skillrate_val_mustache = join(",",$skill_val_arr);	
							$skillgroup_name_mustache = join(",",$skillgroup_name_arr);
							$skill_name_mustache = join(",",$skill_name_arr);
							$skill_rate_mustache = join(",",$skill_rate_arr);
							
							//for skillrate with skill_show = 0
							$response = wp_remote_get( $api_config['api_url'] . 'system/list/skillgrouprating/' . $skillgroup_id_mustache. ';ignore', array( 'headers' => $header_info ) );
							//var_dump($response);
							if ( ! is_wp_error( $response ) ) {
								if(gettype($response_data) == 'object'){
									$response_data = json_decode( $response['body'] )->data;
									$srate_level_arr = $response_data -> rating;
									$srate_name_arr = $response_data -> name;
									$srate_group_arr = $response_data -> grp;
									$srate_level_mustache = join(",",$srate_level_arr);
									$srate_name_mustache = join(",",$srate_name_arr);
									$srate_group_mustache = join(",",$srate_group_arr);
									
									$pres_rate_arr=array();
									$prev_group_id=0;
									for ($i = 0; $i < count($srate_group_arr); $i++) {
										if($i==0 || $prev_groupid != $srate_group_arr[$i] ){
											$pres_rate_arr[$srate_group_arr[$i]]=array();	
										}			
										$pres_rate_arr[$srate_group_arr[$i]][$srate_level_arr[$i]]=$srate_name_arr[$i];
										$prev_groupid = $srate_group_arr[$i];

									}	

								} else{
									$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response['body']  );
									$apiinvalid = 1;
								}	
							} else {
								$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
								$apiinvalid = 1;
							} 
						} else{
							$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response['body']  );
							$apiinvalid = 1;
						}	
						
					} else {
						$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
						$apiinvalid = 1;
					}	
					
				} else {
					$response_data = esc_html__( 'Api config invalid', 'prosolwpclient' );
					$apiinvalid = 1;
				}	 
				
				// $prof_id_mustache = '1,314';
				// $skillgroup_id_mustache = '1,40';
				// $skill_id_mustache = '1017,56';				
				 //$skillrate_val_mustache = '40,100';
				?> 
				<input type="hidden" class="prof_id_mustache" name="prof_id_mustache" value="<?php echo $prof_id_mustache?>">
				<input type="hidden" class="prof_name_mustache" name="prof_name_mustache" value="<?php echo $prof_name_mustache?>">
				<input type="hidden" class="prof_showinappli_mustache" name="prof_showinappli_mustache" value="<?php echo $prof_showinappli_mustache?>">
				<input type="hidden" class="skillgroup_id_mustache" name="skillgroup_id_mustache" value="<?php echo $skillgroup_id_mustache?>">
				<input type="hidden" class="skill_id_mustache" name="skill_id_mustache" value="<?php echo $skill_id_mustache?>">
				<input type="hidden" class="skillrate_val_mustache" name="skillrate_val_mustache" value="<?php echo $skillrate_val_mustache?>">
				<input type="hidden" class="skillgroup_name_mustache" name="skillgroup_name_mustache" value="<?php echo $skillgroup_name_mustache?>">
				<input type="hidden" class="skill_name_mustache" name="skill_name_mustache" value="<?php echo $skill_name_mustache?>">
				<input type="hidden" class="skill_rate_mustache" name="skill_rate_mustache" value="<?php echo $skill_rate_mustache?>">
				<input type="hidden" class="srate_level_mustache" name="srate_level_mustache" value="<?php echo $srate_level_mustache?>">
				<input type="hidden" class="srate_name_mustache" name="srate_name_mustache" value="<?php echo $srate_name_mustache?>">
				<input type="hidden" class="srate_group_mustache" name="srate_group_mustache" value="<?php echo $srate_group_mustache?>">
			<?php } 
		} ?>
		<?php if($pstemplate==1 || $isrec=='off'){
				if($removebtn != 1){ ?>
			<a class="btn btn-primary"
			href="<?php 
				$arr_arg = array('type'  => 'search', 'jobid' => $jobid);
				if(isset($_GET['siteid'])){
					$arr_arg['siteid']=strval($_GET['siteid']);
				}
				if(isset($_GET['source'])){
					$arr_arg['source']=$hassource;
				}	
				echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 
			?>#anchorwp"
			role="button"><?php esc_html_e( 'Back to Job Search', 'prosolwpclient' ); ?></a>
			<?php }
			} else{ 
				if($removebtn != 1){ 
					if($postcontent!='apply'){?>   
						<a class="btn btnprosoldes"
						href="<?php
							$arr_arg = array();
							if(isset($_GET['siteid'])){
								$arr_arg['siteid']=strval($_GET['siteid']);
							}
							if(isset($_GET['source'])){
								$arr_arg['source']=$hassource;
							}	
							echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 	
						?>#anchorwp"
						role="button"><?php echo $prosoldes[$issite.'desbtnappformtohome'] ?></a>
					<?php }else if (!isset($_GET['jobid'])){ ?>		
						<a class="btn btnprosoldes"	href="<?php 
							$arr_arg = array('type'  => 'search', 'searchlist' => strval($_GET['searchlist']) );
							if(isset($_GET['siteid'])){
								$arr_arg['siteid']=strval($_GET['siteid']);
							}
							if(isset($_GET['source'])){
								$arr_arg['source']=$hassource;
							}	
							echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 
						?>#anchorwp"
						role="button"><?php echo $prosoldes[$issite.'desbtnappformtosearch'] ?></a>
					<?php } ?>		
				<?php }else{ ?>		
					<a class="btn btnprosoldes"	href="<?php 
					$arr_arg = array('type'  => 'search', 'searchlist' => strval($_GET['searchlist']) );
					if(isset($_GET['siteid'])){
						$arr_arg['siteid']=strval($_GET['siteid']);
					}
					if(isset($_GET['source'])){
						$arr_arg['source']=$hassource;
					}	
					echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 
					?>#anchorwp"
					role="button"><?php echo $prosoldes[$issite.'desbtnappformtosearch'] ?></a>
			<?php } ?>	
		<?php } ?> 
	</p>


	<div class="row wrap">
		<div class="col-lg-12">
			<div id="prosolfull_app_form">
				<div class="prosolapp_submit_msg"></div>
				<form id="prosoljobApplyForm" class="form-horizontal"
					  action="<?php echo htmlspecialchars( $_SERVER["PHP_SELF"] ); ?>"
					  method="post" role="form"
					  enctype="multipart/form-data">
					  
					<input type="hidden" class="one-pager" name="one_pager" value="<?php echo $list_opt_appform[$issite.'one_pager'] ?>">
					<!--application form section-->
					<?php 
						if( $list_opt_appform[$issite.'personaldata_act'] != '0'){
							include( 'singlefieldset/prosolwpclientjobapplicationpersonalinfo.php' ); 
						}
							
						if( $list_opt_appform[$issite.'education_act'] != '0'){ 
							include( 'singlefieldset/prosolwpclientjobapplicationeducationinfo.php' );
						}
						
						if( $list_opt_appform[$issite.'workexperience_act'] != '0'){ 
							include( 'singlefieldset/prosolwpclientjobapplicationexperienceinfo.php' ); 
						}
						
						if( $list_opt_appform[$issite.'expertise_act'] != '0'){  
							include( 'singlefieldset/prosolwpclientjobapplicationexpertiseinfo.php' );
						}	
				
						if( $list_opt_appform[$issite.'sidedishes_act'] != '0'){ 
							include( 'singlefieldset/prosolwpclientjobapplicationsidedishesinfo.php' );
						}

						if( $list_opt_appform[$issite.'others_act'] != '0'){ 
							include( 'singlefieldset/prosolwpclientjobapplicationothersinfo.php' ); 
						}	
						
						if($list_opt_appform[$issite.'one_pager'] == '0'){
							for ($i = 1; $i <= 6; $i++){
								if($frontend_setting[$issite.'enable_recruitment'] == 'off' && $i == 6)break;
								$man_agree = ($i == 1 || $i == 6) ? '*' : '';
								if($pol[$issite.'policy'.$i.'_act'] == '1' ||  $pol[$issite.'policy'.$i.'_act'] == '6'){
									$id = 'pswp-agree'.$i; 
										$html= sprintf('
											<div class="form-group onepage-policy">
												<div class="col-lg-offset-3 col-lg-9">
													<label class="checkbox-inline">
														<input name="%1$s" type="checkbox" value="0" id="%1$s" data-mand="%3$s">
														<span style="margin-left:0.2em">%2$s %3$s</span>
														<span class="checkmark"></span>
													</label>
												</div>
											</div>
										',$id,$pol[$issite.'policy'.$i],$man_agree); 
									
									echo $html;
								}
							}						
					?>
					<div class="form-group">
						<div class="col-sm-offset-1 col-sm-11">
							<p>(*) = <?php esc_html_e( 'required', 'prosolwpclient' ) ?>!</p>
						</div>
					</div>
					
					<?php 
						} ?>

					<input type="hidden" name="jobID" id="jobID" value="<?php echo $jobid; ?>" />
					<input type="hidden" name="pswp-application-submit" value="1" />
	
					<?php if($pstemplate==1 || $isrec=='off'){ ?>
						<button id="applicationSubmitBtn" type="submit"
								class="btn btn-primary submit application-submit-btn"><?php esc_html_e( 'Send', 'prosolwpclient' ); ?></button>
					<?php }else{ ?>	
						<button id="applicationSubmitBtn" type="submit"
								class="btn btn-primary btnprosoldes-step submit application-submit-btn"><?php esc_html_e( 'Send', 'prosolwpclient' ); ?></button>
					<?php } ?>	
				</form>

				<?php include( 'singlefieldset/modals/prosolwpclientjobapplicationjobmodal.php' ); ?>
				<?php include( 'singlefieldset/modals/prosolwpclientjobapplicationactivitymodal.php' ); ?>
				<?php include( 'singlefieldset/modals/prosolwpclientjobapplicationbusinessmodal.php' ); ?>
				<?php include( 'singlefieldset/modals/prosolwpclientjobapplicationattachmentmodal.php' ); ?>
			</div>
		</div>
	</div>
</div>