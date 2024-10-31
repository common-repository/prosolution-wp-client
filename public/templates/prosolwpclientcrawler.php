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


?>

<div class="container-fluid">
    <div class="alert alert-info text-center"
         role="alert"><h3><?php esc_html_e( 'Crawler Intern Jobfinders', 'prosolwpclient' ); ?></h3></div>
	
	<?php
		if(!is_object($job_details_result)){
			echo esc_html_e( 'Not found any jobs', 'prosolwpclient' );
		}
		else{
	?>
	
	<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
					<tr>
						<th><?php esc_html_e( 'JobID', 'prosolwpclient' ); ?></th>
						<th><?php esc_html_e( 'Category', 'prosolwpclient' ); ?></th>
						<th><?php esc_html_e( 'Office', 'prosolwpclient' ); ?></th>
						<th><?php esc_html_e( 'Profession Name', 'prosolwpclient' ); ?></th>
						<th><?php esc_html_e( 'Qualification', 'prosolwpclient' ); ?></th>
						<th><?php esc_html_e( 'Worktime', 'prosolwpclient' ); ?></th>
						<th><?php esc_html_e( 'Place of Work', 'prosolwpclient' ); ?></th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php
						$jobid_arr          = isset( $job_details_result->jobid ) ? $job_details_result->jobid : array();
						$categoryname_arr          = isset( $job_details_result->categoryname ) ? $job_details_result->categoryname : array();
						$officename_arr          = isset( $job_details_result->officename ) ? $job_details_result->officename : array();
						$professionname_arr          = isset( $job_details_result->professionname ) ? $job_details_result->professionname : array();
						$qualificationname_arr          = isset( $job_details_result->qualificationname ) ? $job_details_result->qualificationname : array();
						$worktimename_arr          = isset( $job_details_result->worktimename ) ? $job_details_result->worktimename : array();
						$workingplace_arr          = isset( $job_details_result->workingplace ) ? $job_details_result->workingplace : array();
						foreach ( $jobid_arr as $index => $jobid ) {
							
							echo '<tr>
									<td>
										<a href="' . add_query_arg( array(
												'type'  => 'details',
												'jobid' => $jobid
											), esc_url( get_permalink() ) ) . '" title="' . esc_html__( 'View Job Details', 'prosolowpclient' ) . '">' . $jobid_arr[ $index ] . '</a></td>
									<td>' . $categoryname_arr[ $index ] . '</td>
									<td>' . $officename_arr[ $index ] . '</td>
									<td>' . $professionname_arr[ $index ] . '</td>
									<td>' . $qualificationname_arr[ $index ] . '</td>
									<td>' . $worktimename_arr[ $index ] . '</td>
									<td>' . $workingplace_arr[ $index ] . '</td>
									<td>
										<a href="' . add_query_arg( array(
											'type'  => 'apply',
											'jobid' => $jobid
										), esc_url( get_permalink() ) ) . '" class="btn btn-success" id="jobid-'.$index.'" name="jobid-'.$index.'" title="' . esc_html__( 'Apply this Job', 'prosolowpclient' ) . '">' . esc_html__( 'Apply', 'prosolowpclient' ) . '</a></td>
								</tr>';
						}
					?>
					</tbody>
				</table>
			</div>
	<?php
		}
	?>
</div>