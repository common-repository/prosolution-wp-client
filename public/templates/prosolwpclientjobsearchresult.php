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

$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
$siteid		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);	

$title = 'Search Result';
$btn_text_JobSearch = 'Back to Job Search';
if($called_from == 'asDefault'){
	$title = 'Job List';
}

$opt = get_option('prosolwpclient_frontend');
$isrec= $opt[$issite.'enable_recruitment'];

$prosoldes = get_option('prosolwpclient_designtemplate');
$pstemplate = $prosoldes[$issite.'destemplate'];

if($pstemplate==0 && $isrec=='on'){
	if(!isset($_GET['siteid'])){
		$gotopagesearch= add_query_arg( array('type' => 'search', 'fromjoblist' => 1), esc_url( get_permalink() ) );
	} else{
		$gotopagesearch= add_query_arg( array('type' => 'search', 'fromjoblist' => 1, 'siteid' => strval($_GET['siteid'])), esc_url( get_permalink() ) );
	}
	$gotopagesearch.="#anchorwp";	
	header("Location: $gotopagesearch");
}
 
?>
<div class="container-fluid">
    <div class="alert alert-info text-center"
         role="alert"><h3><?php esc_html_e( $title, 'prosolwpclient' ); ?></h3></div>

	<?php
		if(!is_object($job_search_result)){
			echo '<div class="alert alert-info text-center" role="alert">'.$job_search_result.'</div>';
		}
		else{

			global $wpdb;global $prosol_prefix;
			$table_ps_profession = $prosol_prefix . 'profession';
			$jobid_arr          = isset( $job_search_result->jobid ) ? $job_search_result->jobid : array();
			$workingplace_arr   = isset( $job_search_result->workingplace ) ? $job_search_result->workingplace : array();
			$profcustomname_arr = isset( $job_search_result->profcustomname ) ? $job_search_result->profcustomname : array();
			$jobname_arr = isset( $job_search_result->jobname ) ? $job_search_result->jobname : array();
			
			$professionid_arr   = isset( $job_search_result->professionid ) ? $job_search_result->professionid : array();

			?>
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
					<tr>
						<th><?php esc_html_e( 'Job', 'prosolwpclient' ); ?></th>
						<th><?php esc_html_e( 'Location', 'prosolwpclient' ); ?></th>
						<th><?php esc_html_e( 'Action', 'prosolwpclient' ); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php
						foreach ( $jobid_arr as $index => $jobid ) {
							if($isrec == 'on'){
								//47690~0176944 point 1, use jobname
								$jobname = $jobname_arr[ $index ];
								
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
									$where_sql  .= $wpdb->prepare(" AND site_id = %s", $siteid);
								}
								$profid_query = $wpdb->get_results( "SELECT DISTINCT professionId FROM $table_ps_profession $where_sql", ARRAY_A );
					
							}else{
								$profcustomname = $profcustomname_arr[ $index ];
								
								if ( $profcustomname == '' ) {
									if(strpos($professionid_arr[ $index ],',') > 0){
										$prof_list=$professionid_arr[ $index ];
										$tag_array = explode(',', $prof_list );
										foreach ($tag_array as $idx => $tag ) {
											if($idx==0) {
												$where_sql .= $wpdb->prepare( " WHERE professionId IN (%s", $tag);
											} elseif($idx+1==count($tag_array)){
												$where_sql .= $wpdb->prepare( ",%s)", $tag);
											} else{
												$where_sql .= $wpdb->prepare(",%s",$tag);
											}											
										}
										$where_sql  .= $wpdb->prepare(" AND site_id = %s", $siteid);
									} else{
										$where_sql = $wpdb->prepare( " WHERE professionId = %s AND site_id=%s", $professionid_arr[ $index ], $siteid );
									}		
									$qprofcustomname = $wpdb->get_results( "SELECT name FROM $table_ps_profession $where_sql" );
									
									if($wpdb->num_rows > 1){
										foreach($qprofcustomname as $index => $prof_name){
											if($index==0){
												$profcustomname=$prof_name->name;
											}else{
												$profcustomname.=', '.$prof_name->name;
											}
										}
									} else{
										$profcustomname=$qprofcustomname[0]->name;
									}									
								}	
								$jobname = $profcustomname;	
							}
							
					?>
						<tr>
							<?php if(!isset($_GET['siteid'])){ ?>
								<td><a href="<?php echo add_query_arg( array(
										'type'  => 'details',
										'jobid' => $jobid
									), esc_url( get_permalink() ) )?>#anchorwp" title="<?php esc_html_e( 'View Job Details', 'prosolwpclient' ) ?>"><?php echo $jobname; ?></a></td>
								<td><?php echo $workingplace_arr[ $index ]; ?> </td>
								<td><a href="<?php echo add_query_arg( array(
										'type'  => 'apply',
										'jobid' => $jobid
									), esc_url( get_permalink() ) )?>#anchorwp" class="btn btn-success" title="<?php esc_html_e( 'Apply', 'prosolwpclient' );?>"><?php esc_html_e( 'Apply', 'prosolwpclient' ); ?></a></td>
							<?php } else{ ?>
								<td><a href="<?php echo add_query_arg( array(
										'type'  => 'details',
										'jobid' => $jobid,
										'siteid' => strval($_GET['siteid'])
									), esc_url( get_permalink() ) )?>#anchorwp" title="<?php esc_html_e( 'View Job Details', 'prosolwpclient' ) ?>"><?php echo $jobname; ?></a></td>
								<td><?php echo $workingplace_arr[ $index ]; ?> </td>
								<td><a href="<?php echo add_query_arg( array(
										'type'  => 'apply',
										'jobid' => $jobid,
										'siteid' => strval($_GET['siteid'])
									), esc_url( get_permalink() ) )?>#anchorwp" class="btn btn-success" title="<?php esc_html_e( 'Apply', 'prosolwpclient' );?>"><?php esc_html_e( 'Apply', 'prosolwpclient' ); ?></a></td>
							<?php } ?>
						</tr>
					<?php
						}
					?>
					</tbody>
				</table>
			</div>
			<?php
		}
	?>
	<?php if(!isset($_GET['siteid'])){ ?>
		<a class="btn btn-primary"
		href="<?php echo add_query_arg( array( 'type' => 'search' ), esc_url( get_permalink() ) ) ?>#anchorwp"
		role="button"><?php esc_html_e( $btn_text_JobSearch, 'prosolwpclient' ); ?></a>	
	<?php }else{ ?>	
		<a class="btn btn-primary"
		href="<?php echo add_query_arg( array( 'type' => 'search', 'siteid' => strval($_GET['siteid']) ), esc_url( get_permalink() ) ) ?>#anchorwp"
		role="button"><?php esc_html_e( $btn_text_JobSearch, 'prosolwpclient' ); ?></a>
	<?php } ?>
</div>