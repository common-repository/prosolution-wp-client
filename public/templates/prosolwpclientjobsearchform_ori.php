<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}
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
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
	
	$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
	$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
	$siteid		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);	
	
	$opt = get_option('prosolwpclient_frontend');
	$isrec= $opt[$issite.'enable_recruitment'];

	$prosoldes = get_option('prosolwpclient_designtemplate');
	$pstemplate = $prosoldes[$issite.'destemplate'];
	
	$ressearch=0;
	//var_dump(isset($_POST['action']));
	if (isset($_POST['action'])) {		
		
		if($_POST['submit']==$prosoldes[$issite.'dessearchjobidbtn']){
			unset($_POST);
			if(!isset($_GET['siteid'])){
				$appformlink= add_query_arg( array('type' => 'apply' ), esc_url( get_permalink() ) );
			} else{
				$appformlink= add_query_arg( array('type' => 'apply', 'siteid'=> strval($_GET['siteid']) ), esc_url( get_permalink() ) );
			}
			$appformlink=$appformlink.'#anchorwp';
			header("Location: $appformlink");
		}else{
			$keywordjob='/'.$_POST['jobname'].'/i';
			$keywordplace='/'.$_POST['searchplace'].'/i';
			$ressearch=1;			
		}		
	}
	
	if($pstemplate == 1 || $isrec== 'off'){		
		
		$apiinvalid = 0;
		if($isrec == 'on'){
			
			$header_info = CBXProSolWpClient_TableHelper::proSol_apiConfig($issite);
			$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
			$api_config   = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);

			if ( is_array( $header_info ) && sizeof( $header_info ) > 0 && $is_api_setup ) {
				$clientlist = $opt['client_list'];
				$response = wp_remote_get( $api_config['api_url'] . 'system/list/professiongroupwithjob/' . $clientlist, array( 'headers' => $header_info ) );
				
				if ( ! is_wp_error( $response ) ) {
					$response_data = json_decode( $response['body'] )->data;
					$profgroup_id = $response_data -> id;	
					$profgroup_name = $response_data -> name;	
					
					
				} else {
					$response_data = sprintf( __( 'Api response failed. Message: %s', 'prosolwpclient' ), $response->get_error_message() );
					$apiinvalid = 1;
				}
				
				if($profgroup_id == null){
					$response_data = esc_html__( 'Api return empty', 'prosolwpclient' );
					$apiinvalid = 1;
				}

			} else {
				$response_data = esc_html__( 'Api config invalid', 'prosolwpclient' );
				$apiinvalid = 1;
			}	
		}	

		global $wpdb;
		$table_ps_professiongroup = $wpdb->prefix . 'professiongroup';
		$profession_groups = $wpdb->get_results( "SELECT * FROM $table_ps_professiongroup WHERE site_id='$siteid' ", ARRAY_A );
	} else {
		echo sprintf("
			<form class='form-horizontal' method='POST'>
				<input type='hidden' name='prosolwpclient_frontend_formsubmit' value=1>
		");
		wp_nonce_field( 'prosolwpclient_formsubmit', 'prosolwpclient_token' );
	}

	if($ressearch==1 || ( $ressearch==0 && isset($_GET['searchlist']) ) || isset($_GET['fromjoblist']) ){
		$job_search_result=$_SESSION['job_search_result'];	
		$workingplace_arr = isset( $job_search_result->workingplace ) ? $job_search_result->workingplace : array();
		$jobname_arr = isset( $job_search_result->jobname ) ? $job_search_result->jobname : array();
		$zipcode_arr = isset( $job_search_result->zipcode ) ? $job_search_result->zipcode : array();
		$worktimename_arr = isset( $job_search_result->worktimename ) ? $job_search_result->worktimename : array();
		$jobid_arr = isset( $job_search_result->jobid ) ? $job_search_result->jobid : array();
	} 
?>
<span id="anchorwp"></span>
<div class="container-fluid" >
	<div class="row">
		<div class="col-md-12 job-search-form">
			
			<?php 
			if($pstemplate == '1' || $isrec=='off'){ ?>
				<form class="form-horizontal" method="post">
					<div class="alert alert-info text-center" role="alert"><h3><?php esc_html_e( 'Job Search', 'prosolwpclient' ); ?></h3></div>
					<div class="form-group">
						<label for="filter_group"
							class="col-md-3 control-label"><?php esc_html_e( 'Filter available Occupational groups', 'prosolwpclient' ); ?></label>
						<div class="col-md-9">
							<input type="text" name="filter_group" class="form-control" id="filter_group"
								placeholder="<?php esc_html_e( 'Filter Groups', 'prosolwpclient' ); ?>">
						</div>
					</div>

					<input type="hidden" name="group_checked" value="0">
					<div class="form-group">
						<label for="group_checked"
							class="col-md-3 control-label"><?php esc_html_e( 'Profession-Group', 'prosolwpclient' ); ?>
						</label>
						<div class="col-lg-9 error-msg-show">
							<ul class="list-unstyled profession-groups">
								<?php
									if( ($isrec == 'on') && ($apiinvalid == 0) ){
										for($ij=0;$ij<(count($profgroup_id));$ij++){
											echo '<li>
													<label class="checkbox-inline">
														<input id="1" class="" name="group_checked[]" type="checkbox" value="' . $profgroup_id[$ij] . '">' . $profgroup_name[$ij] . '
													</label>
												</li>';
										}
									}  else{
										foreach ( $profession_groups as $index => $single_profession_group ) {
											echo '<li>
													<label class="checkbox-inline">
														<input id="1" class="" name="group_checked[]" type="checkbox" value="' . $single_profession_group['professiongroupId'] . '">' . $single_profession_group['name'] . '
													</label>
												</li>';
										}
									}
								?>
							</ul>
						</div>
					</div>

					<input type="hidden" name="prosolwpclient_frontend_formsubmit" value="1" />
					<input type="hidden" name="prosolwpclient_frontend_url"
						value="<?php echo esc_url( get_permalink() ); ?>#anchorwp" />
					<?php wp_nonce_field( 'prosolwpclient_formsubmit', 'prosolwpclient_token' );?>

					<div class="form-group">
						<div class="col-md-offset-3 col-md-9">
							<button type="submit"
									class="btn btn-default btn-primary"><?php esc_html_e( 'Search', 'prosolwpclient' ); ?></button>&nbsp;&nbsp;							
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-3 col-md-9">
							<?php if(!isset($_GET['siteid'])){ ?>
								<a href="<?php echo add_query_arg( array('type' => 'apply' ), esc_url( get_permalink() ) )?>#anchorwp" class="btn btn-default btn-primary" ><?php esc_html_e( "Didn't find the job that you were searching for?", "prosolwpclient" ); ?></a>
							<?php } else{ ?>
								<a href="<?php echo add_query_arg( array('type' => 'apply', 'siteid'=>strval($_GET['siteid']) ), esc_url( get_permalink() ) )?>#anchorwp" class="btn btn-default btn-primary" ><?php esc_html_e( "Didn't find the job that you were searching for?", "prosolwpclient" ); ?></a>
							<?php } ?>
						</div>
					</div>

				<!-- if $pstemplate == '0' -->
			<?php } else{ 
					$prosoldescolor=$prosoldes[$issite.'desmaincolor'];		
					$showjobidbtn=$prosoldes[$issite.'dessearchjobidbtn_act'] == 1 ? "" : "hidden";
				?>	
					<style>
						div.prosolwpclientcustombootstrap, div.prosolwpclientcustombootstrap ::placeholder
						{font-family:<?php echo CBXProSolWpClient_Helper::proSol_getFontName($issite) ?> !important;}

						.prosolwpclientcustombootstrap div.dessearch {
							flex-grow:1;
							padding-right:1rem;
							margin-bottom:1rem;
							flex: 0 0 150px;
						}

						.prosolwpclientcustombootstrap div.dessearchlast {
							flex-grow:1;
							margin-bottom:1rem;
						}

						.prosolwpclientcustombootstrap div.fluid {
							flex-grow:1;

						}

						.prosolwpclientcustombootstrap div.dessearch-container{
							width:100%;
							height:20%;
							display:flex;
							justify-content: space-between;
							margin-bottom:1rem;							
							flex-wrap: wrap;
						}

						.prosolwpclientcustombootstrap input.textprosoldes, 
						.prosolwpclientcustombootstrap input.textprosoldes:focus {
							border: 2px solid <?php echo $prosoldescolor ?>;
							border-radius: 8px;
						}	

						.prosolwpclientcustombootstrap input.textprosoldes::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
							opacity: 0.6;
						}		
						
						@media ( max-width: 768px) {
							.prosolwpclientcustombootstrap div.dessearch,
							.prosolwpclientcustombootstrap div.dessearchlast,
							.prosolwpclientcustombootstrap p.resjoblist > span	
							 {
								padding-right:0px; 
								flex-basis: 100%;
								text-align: center;
								width: 100%;    							
							}
						}

						.prosolwpclientcustombootstrap .btnprosoldes, 
						.prosolwpclientcustombootstrap input[type="submit"].btnprosoldes {
							color:#ffffff;
							background-color: <?php echo $prosoldescolor ?>;
							border-color: <?php echo $prosoldescolor ?>;
							border-radius: 8px;
							font-weight:bold;
							box-shadow: 2px 2px 2px #c4c3c3;
							display: inline-block;
							width:100%; 
							/* height:95%; */
						}
						
						.prosolwpclientcustombootstrap .btnprosoldes:hover, 
						.prosolwpclientcustombootstrap input[type="submit"].btnprosoldes:hover {
							color: <?php echo $prosoldescolor ?>;
							background-color: #ffffff;
							border-color: #ffffff;
							border-radius: 8px;
							font-weight:bold;
							box-shadow: 1px 2px 2px 2px #c4c3c3;
							display: inline-block;
							width:100%;
							/* height:95%; */
						}
						
						.prosolwpclientcustombootstrap div.jobsearch-perid {
							width:100%;
							border-color: <?php echo $prosoldescolor ?>;
							box-shadow: 0.5px 0.5px 6px 6px #f1f1f1;
							margin-bottom: 1.5em;	
							text-align: left;
							display:flex;
							flex-wrap: wrap;
							justify-content: space-between ;
							align-items:center;

							padding-top:calc(0.4rem + 1.8vw);
							padding-bottom:calc(0.8rem + 1.8vw);
							padding-left: calc(0.8rem + 1.8vw);
							padding-right: calc(0.8rem + 1.8vw);
						}

						.prosolwpclientcustombootstrap .jobsearch-perid img, 
						.prosolwpclientcustombootstrap .jobsearch-perid b 
						{
							margin-left: 1em;
							margin-right: 5px;							
						}

						.prosolwpclientcustombootstrap p.resjoblist{
							width:80%;
							margin-bottom: -10px;
							display: flex;
							flex-wrap: wrap; 
						}

						.prosolwpclientcustombootstrap p.resjoblist > b{
							font-size:calc(9px + 0.9vw);
							flex-basis: 100%;
							margin-left:2vmin;
						} 

						.prosolwpclientcustombootstrap p.resjoblist > span{
							margin-right:2vmax;
							margin-left:1vmin;
							display: flex;
							align-content:center;
						} 
						
						.prosolwpclientcustombootstrap p.resjoblist span span {
							font-size: calc(9px + 0.5vw);
							display: flex;
						} 
						
						.prosolwpclientcustombootstrap a.txt-prosoldes{
							text-align:right;
							color: #000000;
							font-weight:bold;
							text-decoration: none;	
							text-align: left;
							width:20%;	
							font-size: calc(7px + 0.4vw);		
							display:inline-flex;			
							justify-content:space-evenly; 
							align-content:center;
							align-items:center;
							margin-top:10px;
						}

						.prosolwpclientcustombootstrap a.txt-prosoldes span{
							display: flex;	 
						}
						
						.prosolwpclientcustombootstrap a.txt-prosoldes:hover {
							color: <?php echo $prosoldescolor ?>;
							text-decoration: none;
						}

						.prosolwpclientcustombootstrap .icon-prosoldes{
							fill: <?php echo $prosoldescolor ?>;
							width:calc(0.5rem + 0.9vw);
							height:calc(0.5rem + 0.9vw);
							margin-left:1em;
							margin-right:2em;
							margin-left:0.5vmin;
							margin-right:0.5vmax;
							display: flex;
						}

						.prosolwpclientcustombootstrap .icon-prosoldes-arrow{
							width:calc(0.5rem + 0.8vw);
							height:calc(0.5rem + 0.8vw);
							/* position:relative;
							top:1px; */
							display:flex;
						}

						.prosolwpclientcustombootstrap .icon-prosoldes-arrow:hover{
							fill: <?php echo $prosoldescolor ?>;
						}

						.prosolwpclientcustombootstrap .text-left{
							font-size:32px;
							font-weight:bold;
						}

					</style>
					<div class="text-left" role="alert" ><?php echo $prosoldes[$issite.'dessearchheading'] ?></div>
					<div class="form-group">
						
						<div class="dessearch-container">
							<div class="dessearch fluid">
								<input type="text" name="jobname" id="jobnamesearch" class="textprosoldes" placeholder="<?php echo $prosoldes[$issite.'dessearchjobtitle']; ?>" 
										value="<?php if(isset($_POST['jobname']))echo $_POST['jobname'] ?>" />
							</div><div class="dessearch fluid">
								<input type="text" name="searchplace" id="searchplacesearch" class="textprosoldes" placeholder="<?php echo $prosoldes[$issite.'dessearchplace']; ?>" 
										value="<?php if(isset($_POST['searchplace']))echo $_POST['searchplace'] ?>" />
							</div><div class="dessearch">
								<input type="submit" name="submit" id="searchbtn" class="btnprosoldes" value="<?php echo $prosoldes[$issite.'dessearchsearchbtn']; ?>" />
							</div><div class="dessearchlast  <?php echo $showjobidbtn ?> ">  
								<input type="submit" name="submit" id="jobidbtn" class="btnprosoldes" value="<?php echo $prosoldes[$issite.'dessearchjobidbtn']; ?>" />
							</div>
						</div>	
						<input type="hidden" name="action" value="submit" />						

						<!-- section result search -->	
						<div class="jobsearch-resultcontainer">		
							<?php 
							$indexshowlist_arr=array();
							$indexshowlist="";
							if($ressearch==1 || isset($_GET['fromjoblist'])){ //click button search
								foreach ( $jobname_arr as $index => $jobname ) {
									$show=0;
									if( $_POST['jobname'] <> "" && $_POST['searchplace'] == "" ){									
										if(preg_match($keywordjob,$jobname)==1 )$show=1;
									} else if( $_POST['jobname'] == "" && $_POST['searchplace'] <> "" ){
										if(preg_match($keywordplace,$workingplace_arr[$index])==1 )$show=1;	
									} else if( $_POST['jobname'] <> "" && $_POST['searchplace'] <> "" ){
										if( preg_match($keywordjob,$jobname)==1 && preg_match($keywordplace,$workingplace_arr[$index])==1 )$show=1;
									} else if( $_POST['jobname'] == "" && $_POST['searchplace'] == "" )$show=1;

									if($show==1){
										if($indexshowlist==""){
											$indexshowlist=strval($index);
										} else{
											$indexshowlist.=",".strval($index);
										}
										array_push($indexshowlist_arr,$index);
									}		
								}
							} else if( $ressearch==0 && isset($_GET['searchlist']) ){  //back from jobdetails								
								$indexshowlist_arr=explode(",", $_GET['searchlist']);
								$indexshowlist=$_GET['searchlist'];
								
							} ?>
							
							<!-- show list -->
							<?php 
							if($indexshowlist != ""){ 
								for($x=0;$x<count($indexshowlist_arr);$x++){	
							?>		<div class="jobsearch-perid">
										<?php 
											if(!isset($_GET['siteid'])){
												$linktodetail=add_query_arg( array(
													'type'  => 'details',
													'jobid' => $jobid_arr[$indexshowlist_arr[$x]],
													'searchlist'  => strval($indexshowlist)
												), esc_url( get_permalink() ) );
											}else{
												$linktodetail=add_query_arg( array(
													'type'  => 'details',
													'jobid' => $jobid_arr[$indexshowlist_arr[$x]],
													'siteid' => strval($_GET['siteid']),
													'searchlist'  => strval($indexshowlist)													
												), esc_url( get_permalink() ) );
											}											

											$show_icon_ziploc   = ($prosoldes[$issite.'desresultzipcode_act']==1 || $prosoldes[$issite.'desresultplaceofwork_act']==1) ? "" : "hidden";
											$show_icon_worktime = $prosoldes[$issite.'desresultworktime_act']==1 ? "" : "hidden";

											$joblist =  sprintf(  '
												<p class="resjoblist"><b>%1$s</b>
												 <span class="%8$s " style="white-space:normal"><svg class="icon-prosoldes" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 217.1 217.1">
														<path d="M108.6 0C62.4 0 24.9 37.5 24.9 83.7c0 33 13.8 66.3 39.9 96.4 19.5 22.5 38.8 35.2 39.6 35.8 1.2 0.8 2.7 1.2 4.1 1.2 1.4 0 2.9-0.4 4.1-1.2 0.8-0.5 20.1-13.3 39.6-35.8 26.1-30.1 39.9-63.5 39.9-96.4C192.2 37.5 154.7 0 108.6 0zM108.6 200.4C93.2 188.9 39.9 144.8 39.9 83.7 39.9 45.8 70.7 15 108.6 15c37.9 0 68.7 30.8 68.7 68.7C177.2 144.8 123.9 188.9 108.6 200.4z"/><path d="M108.6 50.4c-18.3 0-33.2 14.9-33.2 33.2 0 18.3 14.9 33.2 33.2 33.2 18.3 0 33.2-14.9 33.2-33.2C141.8 65.3 126.9 50.4 108.6 50.4zM108.6 101.9c-10 0-18.2-8.2-18.2-18.2 0-10 8.2-18.2 18.2-18.2 10 0 18.2 8.2 18.2 18.2C126.8 93.7 118.6 101.9 108.6 101.9z"/>
													</svg>  <span>%2$s</span> 
												</span> 
														
												 <span class="%9$s" style="white-space:normal"><svg class="icon-prosoldes" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 477.9 477.9"><path d="M238.9 0C107 0 0 107 0 238.9s107 238.9 238.9 238.9 238.9-107 238.9-238.9C477.7 107 370.8 0.1 238.9 0zM256 238.9c0 9.4-7.6 17.1-17.1 17.1H102.4c-9.4 0-17.1-7.6-17.1-17.1s7.6-17.1 17.1-17.1h119.5V102.4c0-9.4 7.6-17.1 17.1-17.1S256 93 256 102.4V238.9z"/>
													</svg> <span>%4$s</span>
												</span> 
												 
												</p>
												<a href="%7$s#anchorwp" class="txt-prosoldes icon-prosoldes-arrow" title="%6$s"><span>%6$s</span>
													<svg class="icon-prosoldes-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 492 492"><path d="M484.1 226.9L306.5 49.2c-5.1-5.1-11.8-7.9-19-7.9 -7.2 0-14 2.8-19 7.9l-16.1 16.1c-5.1 5.1-7.9 11.8-7.9 19 0 7.2 2.8 14.2 7.9 19.3L355.9 207.5H26.6C11.7 207.5 0 219.2 0 234v22.8c0 14.9 11.7 27.6 26.6 27.6h330.5L252.2 388.9c-5.1 5.1-7.9 11.7-7.9 18.9 0 7.2 2.8 13.9 7.9 18.9l16.1 16.1c5.1 5.1 11.8 7.8 19 7.8 7.2 0 14-2.8 19-7.9l177.7-177.7c5.1-5.1 7.9-11.9 7.9-19.1C492 238.8 489.2 232 484.1 226.9z"/></svg>
												</a>', $jobname_arr[$indexshowlist_arr[$x]], $zipcode_arr[$indexshowlist_arr[$x]]." ".$workingplace_arr[$indexshowlist_arr[$x]], '', $worktimename_arr[$indexshowlist_arr[$x]], "",$prosoldes['desbtnfrontendback'],$linktodetail
														, $show_icon_ziploc, $show_icon_worktime);
											echo $joblist;
										?>
									</div>
							<?php	
								}
							}		
							unset($_POST);	?>		
						</div>	
					</div>	
			<?php }  ?>	 <!--- end of $pstemplate --->

			</form> <!--- start tag of form inside each of condition pstemplate --->
		</div>
	</div>
</div>                                                                                                                                                                            