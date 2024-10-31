<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}
	error_reporting(0);
?>
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

$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
$siteid		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);	
$hassource = isset( $_GET['source'] ) ? strval($_GET['source']) : '';

if(is_object($job_details_result)){
	$prosoldes = get_option('prosolwpclient_designtemplate');
	$pstemplate = $prosoldes[$issite.'destemplate'];
	$prosoldescolor=$prosoldes[$issite.'desmaincolor'];	

	global $wpdb;global $prosol_prefix;
	$table_ps_profession      = $prosol_prefix . 'profession';
	$table_ps_professiongroup = $prosol_prefix . 'professiongroup';
	$table_ps_qualification   = $prosol_prefix . 'qualification';

	$workingplace      	= isset( $job_details_result->workingplace ) ? $job_details_result->workingplace[0] : '';
	$zipcode	       	= isset( $job_details_result->zipcode ) ? $job_details_result->zipcode[0] : '';
	$jobrefid          = isset( $job_details_result->jobrefid ) ? $job_details_result->jobrefid[0] : '';
	$norecprofgroupid 	= isset( $job_details_result->professiongroupid ) ? $job_details_result->professiongroupid[0] : '';
	$jobname    		= isset( $job_details_result->jobname ) ? $job_details_result->jobname[0] : '';
	$profcustomname   	= isset( $job_details_result->profcustomname ) ? $job_details_result->profcustomname[0] : '';
	$professionid      	= isset( $job_details_result->professionid ) ? $job_details_result->professionid[0] : '';
	$qualificationid   	= isset( $job_details_result->qualificationid ) ? $job_details_result->qualificationid[0] : '';
	$worktimename   	= isset( $job_details_result->worktimename ) ? $job_details_result->worktimename[0] : '';
	$jobstart     		= isset( $job_details_result->jobstartdate ) ? $job_details_result->jobstartdate[0] : '';
	$salary 			= isset( $job_details_result->salarytext ) ? $job_details_result->salarytext[0] : '';
	$agentname 		  	= isset( $job_details_result->agentname ) ? $job_details_result->agentname[0] : '';
	$jobproject_id		= isset( $job_details_result->jobproject_id ) ? $job_details_result->jobproject_id[0] : '';
	$customer_arr		= isset( $job_details_result->customer ) ? $job_details_result->customer[0] : array();
	$recruitlink_arr	= isset( $job_details_result->recruitlink ) ? $job_details_result->recruitlink[0] : array();
	
	// OP 1901 - convert customer so it's readable
	$custsingle_arr = $customer_arr[0];  
	foreach($custsingle_arr as $idcust => $custsingle){ 
		if($idcust == 0){
			$cust_fullname = null ? '' : $custsingle->CUSTID.' '.$custsingle->CUSTNAME;
		} else{
			$cust_fullname = $cust_fullname.', '.$custsingle->CUSTID.' '.$custsingle->CUSTNAME;
		}
	}
	$whatsappUrl = $recruitlink_arr[0][0]->WHATSAPPURL;
	$whatsappQR = $recruitlink_arr[0][0]->WHATSAPPQR;
	//die;
	$qualificationname = '';
	if ( $qualificationid != '' ) {
		$qualificationname = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM $table_ps_qualification WHERE qualificationId = %s AND site_id= %s", $qualificationid, $siteid ) );
	}

	$opt = get_option('prosolwpclient_frontend');
	$isrec= $opt[$issite.'enable_recruitment'];

	if($isrec == 'on'){
		//47690~176944, point 2		
		$professionname_arr = $job_details_result->profession;
		if(is_array($professionname_arr[0])){
			$profgroupid_haystack=array();
			for ($x = 0; $x < count($professionname_arr[0]); $x++) {
				if($x==0){
					$profcustomname=$professionname_arr[0][$x]->PROFESSIONNAME;
				}else{
					$profcustomname.=', '.$professionname_arr[0][$x]->PROFESSIONNAME;
				}
				$profgroupid_needle=$professionname_arr[0][$x]->PROFESSIONGROUPID;
				if(!in_array($profgroupid_needle,$profgroupid_haystack)){
					if($x==0){
						$professiongroupname=$professionname_arr[0][$x]->PROFESSIONGROUPNAME;
					}else{
						$professiongroupname.=', '.$professionname_arr[0][$x]->PROFESSIONGROUPNAME;
					}
				}
				$profgroupid_haystack[$x]=$professionname_arr[0][$x]->PROFESSIONGROUPID;
			}
		} else{
			$profcustomname=$professionname_arr[0][0]->PROFESSIONNAME;
			$professiongroupname=$professionname_arr[0][0]->PROFESSIONGROUPNAME;
		}
	} else{
		if ( $profcustomname == '' ) {
			$profcustomname = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM $table_ps_profession WHERE professionId = %s AND site_id=%s ", $professionid, $siteid ) );
		}
		
		$professiongroupname = '';
		if ( $norecprofgroupid != '' ) {
			$professiongroupname = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM $table_ps_professiongroup WHERE professiongroupId = %s AND site_id=%s ", $norecprofgroupid, $siteid ) );
		}
	}
}?>

<div class="container-fluid">
<?php if($pstemplate==1 || $isrec=='off'){ ?>
		<a class="btn btn-primary" id="anchorwp"
		href="<?php echo add_query_arg( array( 'type' => 'result' ), esc_url( get_permalink() ) ) ?>#anchorwp"
		role="button"><?php esc_html_e( 'Back to Job Listing', 'prosolwpclient' ); ?></a><br/><br/>

		<?php
			if(!is_object($job_details_result)){
				echo '<div class="alert alert-info text-center" role="alert">'.$job_details_result.'</div>';
			}else{

				?>
				<div class="alert alert-info text-center" role="alert"><h3><?php esc_html_e( 'Job Details', 'prosolwpclient' ); ?></h3></div>

				<table class="table">
					<tbody>
					<?php if($isrec == 'on'){ ?>
						<tr>
							<th><?php esc_html_e( 'Jobname', 'prosolwpclient' ) ?></th>
							<td><?php esc_html_e( stripslashes( $jobname ), 'prosolwpclient' ) ?></td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Profession', 'prosolwpclient' ) ?></th>
							<td><?php esc_html_e( stripslashes( $profcustomname ), 'prosolwpclient' ) ?></td>
						</tr>
					<?php } else{ ?>
						<tr>
							<th><?php esc_html_e( 'Job', 'prosolwpclient' ) ?></th>
							<td><?php esc_html_e( stripslashes( $profcustomname ), 'prosolwpclient' ) ?></td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Reference Number', 'prosolwpclient' ) ?></th>
							<td><?php esc_html_e( stripslashes( $jobrefid ), 'prosolwpclient' ) ?></td>
						</tr>	
					<?php } ?>
					<tr>
						<th><?php esc_html_e( 'Occupational Group', 'prosolwpclient' ) ?></th>
						<td><?php esc_html_e( stripslashes( $professiongroupname ), 'prosolwpclient' ) ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Qualification', 'prosolwpclient' ) ?></th>
						<td><?php esc_html_e( stripslashes( $qualificationname ), 'prosolwpclient' ) ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Working Time', 'prosolwpclient' ) ?></th>
						<td><?php esc_html_e( stripslashes( $worktimename ), 'prosolwpclient' ) ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Location', 'prosolwpclient' ) ?></th>
						<td><?php esc_html_e( $zipcode.' ' ); esc_html_e( stripslashes( $workingplace ), 'prosolwpclient' ) ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Job Start Date', 'prosolwpclient' ) ?></th>
						<td>
							<?php if ($jobstart == ''){
								esc_html_e( 'Immediately', 'prosolwpclient' );
							} else{
								esc_html_e( stripslashes( $jobstart ), 'prosolwpclient' );
							}
							?>
						</td>
					</tr>
					<?php if ($isrec == 'on'){ ?>
						<tr>
							<th><?php esc_html_e( 'Salary', 'prosolwpclient' ) ?></th>
							<td>
								<?php if ($salary == ''){
									esc_html_e( '', 'prosolwpclient' );
								} else{
									esc_html_e( stripslashes( $salary ), 'prosolwpclient' );
								}
								?>
							</td>
						</tr>
					<?php } ?>			

					<?php foreach ( CBXProSolWpClient_Helper::proSol_fifteenCustomFieldsArr() as $field_key => $field_name ) {
						$textfield   = 'textfield_' . $field_key;
						$textfieldlbl   = 'textfieldlabel_' . $field_key;
						$field_value = isset( $job_details_result->{$textfield} ) ? $job_details_result->{$textfield}[0] : '';
						$field_label = isset( $job_details_result->{$textfieldlbl} ) ? $job_details_result->{$textfieldlbl}[0] : $field_name;
						
						if ( $field_value != '' ) : ?>
							<?php if($field_key>=26 && $field_key<=30){ 
								$multicombobox = explode(",", $field_value);
							?>
								<tr>
									<th><?php esc_html_e( $field_label, 'prosolwpclient' ) ?></th>
									<td>
										<?php foreach($multicombobox as $multicomboxval){ ?>
											<li>
												<?php echo $multicomboxval ?>
											</li>
										<?php } ?>
									</td>
								</tr>
							<?php } else{ ?>
								<tr>
									<th><?php esc_html_e( $field_label, 'prosolwpclient' ) ?></th>
									<td><?php echo $field_value ?></td>
								</tr>
							<?php } ?>
						<?php endif;
					} ?>
					<?php if($whatsappQR != ''){ ?>
						<tr>
							<td> <img src="<?php echo $whatsappQR ?>" alt="WhatsappQR" width="250" height="250"> </td>
						</tr>
					<?php } ?>
					</tbody>
				</table>

				<?php if($whatsappUrl != ''){ ?>
					<a class="btn btn-primary" href="<?php echo $whatsappUrl ?>" target="_blank" rel="noopener noreferrer" role="button"><?php esc_html_e( 'Apply with WhatsApp', 'prosolwpclient' ); ?></a>
				<?php } ?>
				
				<?php if(!isset($_GET['siteid'])){ ?>
					<a class="btn btn-primary"
					href="<?php echo add_query_arg( array( 'type' => 'search' ), esc_url( get_permalink() ) ) ?>#anchorwp"
					role="button"><?php esc_html_e( 'Back to Job Search', 'prosolwpclient' ); ?></a>
					<a class="btn btn-success pull-right"
					href="<?php 
						$arr_arg = array('type'  => 'apply', 'jobid' => $jobid);
						if(isset($_GET['source'])){
							$arr_arg['source']=$hassource;
						}	
						echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 
					?>#anchorwp"
					role="button"><?php esc_html_e( 'Apply', 'prosolwpclient' ); ?></a>
				<?php } else{ ?>
					<a class="btn btn-primary"
					href="<?php echo add_query_arg( array( 'type' => 'search', 'siteid' => strval($_GET['siteid']) ), esc_url( get_permalink() ) ) ?>#anchorwp"
					role="button"><?php esc_html_e( 'Back to Job Search', 'prosolwpclient' ); ?></a>
					<a class="btn btn-success pull-right"
					href="<?php 
						$arr_arg = array('type'  => 'apply', 'jobid' => $jobid, 'siteid' => strval($_GET['siteid']));
						if(isset($_GET['source'])){
							$arr_arg['source']=$hassource;
						}	
						echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 						
					?>#anchorwp"
					role="button"><?php esc_html_e( 'Apply', 'prosolwpclient' ); ?></a>
				<?php } ?>	
	<?php	}
	} else{ //pstemplate == 0 
	if(is_object($job_details_result)){ 
		$icolc=plugins_url( 'img/location.png', dirname( __FILE__ ) );	
	?>
		<style>
			div.prosolwpclientcustombootstrap, div.prosolwpclientcustombootstrap ::placeholder
			 {font-family:<?php echo CBXProSolWpClient_Helper::proSol_getFontName($issite) ?> !important;}

			.prosolwpclientcustombootstrap .jpid {
				width: 10%;
				position: relative;
				right: -90%;
				font-style: italic;
				font-size: calc(8px + 0.2vw)
			}

			@media ( max-width: 768px) { 
				.prosolwpclientcustombootstrap .jpid
				{
					right: -100%;
				}
			}

			.prosolwpclientcustombootstrap b.header-details {
				color:<?php echo $prosoldescolor ?>;
				font-size: 56px;
				font-size: 6.8vmin;
				margin-bottom:1.5rem;
				display: inline-block;
				width:100%;
			}

			.prosolwpclientcustombootstrap span.header-details {
				display: inline-block;
				margin-right: 1em;
				margin-bottom: 0.6em;
			}

			.prosolwpclientcustombootstrap .customer_desc {
				font-size: calc(8px + 0.3vw);
			}	

			.prosolwpclientcustombootstrap .icon-prosoldes{
				fill: <?php echo $prosoldescolor ?>;
				width:1.5rem;
				height:1.5rem;
				position:relative;
				top:6px;
				margin-right:0.4em;
			}

			.prosolwpclientcustombootstrap .resjobdetail-header>span {
				margin-right: 1.4em;
				margin-bottom: 2rem;
				display: block;
			}

			.prosolwpclientcustombootstrap .resjobdcontent  {
				margin-top: 1rem;
			}

			.prosolwpclientcustombootstrap .resjobdcontent b {
				color:<?php echo $prosoldescolor ?>;
			}

			.prosolwpclientcustombootstrap .resjobdcontent>div  {
				margin-bottom: 1em;
			}

			.prosolwpclientcustombootstrap li::marker {
				content: "\2022";  /* Add content: \2022 is the CSS Code/unicode for a bullet */
				color: <?php echo $prosoldescolor ?>;
				display: inline-block; 
			}

			.prosolwpclientcustombootstrap li span {
				margin-left: 6px;
			}
			
			.prosolwpclientcustombootstrap .btnprosoldes, 
			.prosolwpclientcustombootstrap a.btnprosoldes {
				color:#ffffff;
				background-color: <?php echo $prosoldescolor ?>;
				border-radius: 8px;
				font-weight:bold;
				box-shadow: 1px 2px 2px 2px #c4c3c3 !important;
				display: inline-block;
				border: 8px solid <?php echo $prosoldescolor ?>;
			}
			
			.prosolwpclientcustombootstrap .btnprosoldes:hover, 
			.prosolwpclientcustombootstrap a.btnprosoldes:hover {
				color: <?php echo $prosoldescolor ?>;
				background-color: #ffffff;
				border-color: #ffffff;
				border-radius: 8px;
				font-weight:bold;
				box-shadow: 1px 2px 2px 2px #c4c3c3 !important;
				display: inline-block;
			}

			.prosolwpclientcustombootstrap a.btnprosoldes span{
				display: inline-block;
				width:inherit - 6px;
				height:inherit;
				text-align: center;
			}
		</style>
	<?php

		$show_icon_ziploc   = ($prosoldes[$issite.'desdetailszipcode_act']==1 || $prosoldes[$issite.'desdetailsplaceofwork_act']==1) ? "" : "hidden";
		$show_icon_worktime = $prosoldes[$issite.'desdetailsworktime_act']==1 ? "" : "hidden";
		$show_icon_salary   = $prosoldes[$issite.'desdetailssalary_act']==1 ? "" : "hidden";
		$show_icon_qualif   = $prosoldes[$issite.'desdetailsqualification_act']==1  ? "" : "hidden";
		$show_icon_profess  = $prosoldes[$issite.'desdetailsprofession_act']==1 ? "" : "hidden";
		
		$show_icon_agentname = ($prosoldes[$issite.'desdetailsagentname_act']==1) && ($agentname!='') ? "" : "hidden"; 
		$show_jobprojectid = ($prosoldes[$issite.'desdetailsjobprojectid_act']==1)  && ( $jobproject_id != 0) && ($jobproject_id != '')  ? "" : "hidden";
		
		$customer_text = $cust_fullname == ' '? '' : $prosoldes[$issite.'desdetailscustomer_text'];
		$show_customer = ($prosoldes[$issite.'desdetailscustomer_act']==1) && ($customer_text!='') ? "" : "hidden"; 

		if( ($zipcode=="" && $workingplace=="") || (is_null($zipcode) && is_null($workingplace)) )$show_icon_ziploc = "hidden";
		if( $salary=="" || is_null($salary)  )$show_icon_salary = "hidden";
		if( $worktimename=="" || is_null($worktimename)  )$show_icon_worktime = "hidden";
		if( $qualificationname=="" || is_null($qualificationname)  )$show_icon_qualif = "hidden";
		if( $profcustomname=="" || is_null($profcustomname)  )$show_icon_profess = "hidden";
		if( $agentname=="" || is_null($agentname)  )$show_icon_agentname = "hidden";

		$zipcode = ($prosoldes[$issite.'desdetailszipcode_act']==1) ? $zipcode : "";
		$workingplace = ($prosoldes[$issite.'desdetailsplaceofwork_act']==1) ? $workingplace : "";
		
		$header =  sprintf(  '
		<span id="anchorwp">
			<span class="jpid %19$s">#%20$s</span>
			<p class="resjobdetail-header" >
				<b class="header-details">%1$s</b> 
			</p>
			<span>
				<span class="header-details %7$s"><svg class="icon-prosoldes" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
				<path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 256c-35.3 0-64-28.7-64-64s28.7-64 64-64s64 28.7 64 64s-28.7 64-64 64z"/>
				</svg>%2$s %3$s</span>
				<span class="header-details %10$s"><svg class="icon-prosoldes" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 477.9 477.9"><path d="M238.9 0C107 0 0 107 0 238.9s107 238.9 238.9 238.9 238.9-107 238.9-238.9C477.7 107 370.8 0.1 238.9 0zM256 238.9c0 9.4-7.6 17.1-17.1 17.1H102.4c-9.4 0-17.1-7.6-17.1-17.1s7.6-17.1 17.1-17.1h119.5V102.4c0-9.4 7.6-17.1 17.1-17.1S256 93 256 102.4V238.9z"/>
				</svg>%4$s</span> 
				<span class="header-details %11$s"><svg class="icon-prosoldes" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300"><path d="M150 0C67.2 0 0 67.2 0 150S67.2 300 150 300c82.8 0 150-67.2 150-150S232.8 0 150 0zM126.8 48.7h39.1c7.2 0 13 5.8 13 13l-12.2 28.3h-40.7l-12.2-28.3C113.8 54.5 119.6 48.7 126.8 48.7zM147.4 243.5c-33.1 0-62.7-5.5-75.9-8.4 4.3-17.8 14.4-61.4 16.3-79.2 2.9-28 18.7-49.3 40.7-57.1l-14.6 18 12.1 9.8 20.7-25.6 20.7 25.6 12.1-9.8 -15.3-18.8c23.1 7.1 40 29 43 58 1.8 17.7 12 61.4 16.3 79.2C210 238 180.2 243.5 147.4 243.5z"/><path d="M148.6 167.4c-7.5-0.9-13.3-1.9-13.3-6.5 0-6.3 8.9-7 12.8-7 5.7 0 11.8 2.7 13.8 6.1l0.6 1 11.8-5.5 -0.6-1.2c-4.4-9-12.3-11.6-18.3-12.6v-7.9h-13.8v7.9c-12.9 1.9-20.5 9-20.5 19.3 0 16.7 15.1 18.4 26.2 19.6 9.8 1.2 14.4 3.6 14.4 7.6 0 7.7-10.7 8.3-14 8.3 -7.3 0-14.4-3.6-16.4-8.5l-0.5-1.2 -12.8 5.4 0.5 1.2c3.8 8.9 12 14.5 23.1 15.8v8.5h13.8v-9c10.6-1.2 20.9-7.9 20.9-20.6C176.4 170.8 160.4 168.8 148.6 167.4z"/>
				</svg>%5$s</span>
				<span class="header-details %14$s"><svg class="icon-prosoldes" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.043,14H7.957A4.963,4.963,0,0,0,3,18.957V24H21V18.957A4.963,4.963,0,0,0,16.043,14Z"/><circle cx="12" cy="6" r="6"/>
				</svg>%15$s</span>  
				<span class="header-details %12$s"><svg class="icon-prosoldes" xmlns="http://www.w3.org/2000/svg" width="65" height="65" viewBox="0 0 65.4 65.4"><path d="M32.7 0C14.6 0 0 14.6 0 32.7c0 18.1 14.6 32.7 32.7 32.7 18.1 0 32.7-14.6 32.7-32.7C65.4 14.6 50.8 0 32.7 0zM15.3 48.3c-1 0-1.8-0.8-1.8-1.8 0-0.7 0.4-1.2 0.9-1.5V28.7h1.8v16.3c0.5 0.3 0.9 0.9 0.9 1.5C17.1 47.5 16.3 48.3 15.3 48.3zM48 41.9c0 2.1-6.8 5.3-15.3 5.3 -8.4 0-15.3-3.2-15.3-5.3V31.4l15.3 5.5 15.3-5.5V41.9zM32.7 34.9l-15.6-5.6 14.2-2.2c0.3 0.4 0.8 0.7 1.4 0.7 1 0 1.9-0.8 1.9-1.9 0-1-0.8-1.9-1.9-1.9 -0.8 0-1.5 0.5-1.7 1.2l-17.4 2.7 -5.8-2.1 24.9-8.9 24.9 8.9L32.7 34.9z"/>
				</svg>%8$s</span> <br>
				<span class="header-details %13$s"><svg class="icon-prosoldes" xmlns="http://www.w3.org/2000/svg" width="350" height="350" viewBox="0 0 350.1 350.1"><path d="M304.8 72.4c6.5 0 12.5-2.5 17.1-7.1 1.2-1.2 2.3-2.5 3.3-4.1l23.8-30.6c1.7-2.1 1.5-5.2-0.4-7.1L329.5 4.5c-1.9-1.9-5-2.1-7.1-0.4L291.8 27.9c-1.6 1-2.9 2.1-4.1 3.3 -4.6 4.6-7.1 10.6-7.1 17.1 0 4.6 1.3 9 3.7 12.8l-94 94 -24.7-24.7c-0.8-0.8-1.8-1.2-2.9-1.4l-31.9-31.9c8-24 1.8-50.9-16.1-68.8C102 15.6 85.2 8.6 67.2 8.6c-8.8 0-17.3 1.7-25.4 5 -1.7 0.7-2.9 2.1-3.2 3.9 -0.4 1.8 0.2 3.6 1.5 4.9l31 31 -1.3 24.9 -24.9 1.3L13.8 48.6c-1.3-1.3-3.1-1.8-4.9-1.5 -1.8 0.4-3.2 1.6-3.9 3.2 -10.3 25.1-4.5 53.8 14.7 72.9 12.6 12.6 29.5 19.6 47.4 19.6l0 0c7.3 0 14.4-1.2 21.3-3.5l30.3 30.3L33 255.5c-8.6 8.6-13.4 20.1-13.4 32.3 0 12.2 4.8 23.7 13.4 32.3 8.6 8.6 20.1 13.4 32.3 13.4 12.2 0 23.7-4.7 32.3-13.4l85.8-85.8 25.2 25.2c-7.6 23.8-1.3 50.3 16.4 68 12.7 12.7 29.6 19.7 47.5 19.7l0 0c8.8 0 17.3-1.7 25.4-5 1.7-0.7 2.9-2.1 3.2-3.9 0.4-1.8-0.2-3.6-1.5-4.9l-31-31 1.3-24.9 24.9-1.3 31 31c1.3 1.3 3.1 1.8 4.9 1.5 1.8-0.4 3.2-1.6 3.9-3.2 10.3-25.1 4.5-53.8-14.7-72.9 -12.6-12.7-29.5-19.6-47.4-19.6 -7.6 0-15 1.3-22.2 3.8 -0.1-0.2-0.2-0.3-0.4-0.5l-25.9-25.9c-0.2-1.1-0.6-2.1-1.4-2.9l-24.7-24.7 94-94C295.8 71.1 300.2 72.4 304.8 72.4zM51 278.9c-1.4 0-2.8-0.5-3.8-1.6 -2.1-2.1-2.1-5.5 0-7.6l75.2-75.1c2.1-2.1 5.5-2.1 7.6 0 2.1 2.1 2.1 5.5 0 7.6l-75.2 75.2C53.8 278.4 52.4 278.9 51 278.9zM66.2 294.1c-1.4 0-2.8-0.5-3.8-1.6 -2.1-2.1-2.1-5.5 0-7.6l75.1-75.1c2.1-2.1 5.5-2.1 7.6 0 2.1 2.1 2.1 5.5 0 7.6l-75.1 75.1C69 293.6 67.6 294.1 66.2 294.1zM158.5 230.7l-75.1 75.1c-1 1.1-2.4 1.6-3.8 1.6 -1.4 0-2.7-0.5-3.8-1.6 -2.1-2.1-2.1-5.5 0-7.6l75.1-75.1c2.1-2.1 5.5-2.1 7.6 0C160.6 225.2 160.6 228.6 158.5 230.7z"/>
				</svg>%9$s</span> <br>
				<span class="header-details customer_desc %18$s">
					<i>%16$s %17$s</i>
				</span>
				</svg>
			</span>
				 ', $jobname, $zipcode, $workingplace, $worktimename, 
					$salary,"",$show_icon_ziploc,
					$qualificationname,$profcustomname,
					$show_icon_worktime, $show_icon_salary, $show_icon_qualif, $show_icon_profess,
					$show_icon_agentname, $agentname, 
					$customer_text, $cust_fullname, $show_customer,
					$show_jobprojectid, $jobproject_id
				);
		echo $header;
	?>
		<div class="resjobdcontent">
			<?php foreach ( CBXProSolWpClient_Helper::proSol_fifteenCustomFieldsArr() as $field_key => $field_name ) {
				$textfield   = 'textfield_' . $field_key;
				$textfieldlbl   = 'textfieldlabel_' . $field_key;
				$textfieldidentify   = 'textfield' . $field_key;
				$prosoldesdetailscustomdisplay=$prosoldes[$issite.'desdetails'.$textfieldidentify.'_act'];
				$field_value = isset( $job_details_result->{$textfield} ) ? $job_details_result->{$textfield}[0] : '';
				$field_label = isset( $job_details_result->{$textfieldlbl} ) ? $job_details_result->{$textfieldlbl}[0] : $field_name;
				if ( $field_value != '' && $prosoldesdetailscustomdisplay == 1 ) : ?>
					<?php if($field_key>=26 && $field_key<=30){ 
						$multicombobox = explode(",", $field_value);
					?>
						<div>
							<b><?php esc_html_e( $field_label, 'prosolwpclient' ) ?></b><br>
							<span>
								<?php foreach($multicombobox as $multicomboxval){ ?>
									<li>
										<?php echo $multicomboxval ?>
									</li>
								<?php } ?>
							</span>
						</div>
					<?php } else{ ?>
						<div>
							<b><?php esc_html_e( $field_label, 'prosolwpclient' ) ?></b><br>
							<span><?php echo $field_value ?></span>
						</div>
					<?php } ?>
				<?php endif;
			} ?>
			<?php if($whatsappUrl != ''){ ?>
				<div>
					<img src="<?php echo $whatsappQR ?>" alt="WhatsappQR" width="250" height="250">
				</div>
			<?php } ?>
		</div>

		<br>

		<?php 
			if(!isset($_GET['siteid'])){ ?>
			<?php if($whatsappUrl != ''){ ?>
				<a class="btn btnprosoldes" style="display:inline-block;margin-right:1rem"  href="<?php echo $whatsappUrl ?>" target="_blank" rel="noopener noreferrer" role="button"><span><?php echo $prosoldes['desbtndetailsapplywhatsapp'] ?></span></a>
			<?php } ?>
			<a class="btn btnprosoldes" 
				href="<?php 
					$arr_arg = array('type'  => 'apply', 'jobid' => $jobid, 'searchlist' => strval($_GET['searchlist']) );
					if(isset($_GET['source'])){
						$arr_arg['source']=$hassource;
					}	
					echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 
				?>#anchorwp"
				role="button"><span><?php echo $prosoldes['desbtndetailsapply'] ?></span></a>
			<a class="btn btnprosoldes"	style="display:inline-block;margin-left:1rem" 
				href="<?php 
					$arr_arg = array('type'  => 'search', 'searchlist' => strval($_GET['searchlist']) );
					if(isset($_GET['source'])){
						$arr_arg['source']=$hassource;
					}	
					echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 	
				?>#anchorwp"
				role="button"><?php echo $prosoldes['desbtndetailsback'] ?></a>	
		<?php } else{ ?>
			<?php if($whatsappUrl != ''){ ?>
				<a class="btn btnprosoldes" style="display:inline-block;margin-right:1rem" href="<?php echo $whatsappUrl ?>" target="_blank" rel="noopener noreferrer" role="button"><span><?php echo $prosoldes[$issite.'desbtndetailsapplywhatsapp'] ?></span></a>
			<?php } ?>
			<a class="btn btnprosoldes" 
				href="<?php 
					$arr_arg = array('type'  => 'apply', 'jobid' => $jobid, 'siteid' => strval($_GET['siteid']), 'searchlist' => strval($_GET['searchlist']) );
					if(isset($_GET['source'])){
						$arr_arg['source']=$hassource;
					}	
					echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 
				?>#anchorwp"
				role="button"><span><?php echo $prosoldes[$issite.'desbtndetailsapply'] ?></span></a>
			<a class="btn btnprosoldes"	style="display:inline-block;margin-left:1rem" 
				href="<?php 
					$arr_arg = array('type'  => 'search', 'siteid' => strval($_GET['siteid']), 'searchlist' => strval($_GET['searchlist']) );
					if(isset($_GET['source'])){
						$arr_arg['source']=$hassource;
					}	
					echo add_query_arg( $arr_arg, esc_url( get_permalink() ) ); 
				?>#anchorwp"
				role="button"><?php echo $prosoldes[$issite.'desbtndetailsback'] ?></a>	
		<?php }	?>
		
<?php }
} ?>

</div>