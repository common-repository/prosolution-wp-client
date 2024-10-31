<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}

	$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
	$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
	$siteid		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);

	$step_label = get_option('prosolwpclient_applicationform');
	$mandatorydoument = $step_label[$issite.'sidedishes_man'] ? 'required' : '';
?>

<?php
 //if($step_label['one_pager'] == "0"){
?>
	<style>
		.prosolwpclientcustombootstrap .onepage-sidedish{
			z-index:1 !important; 
		}
	</style>
<?php
 //}
?>

<fieldset class="application-info-side-dishes">
	<legend><?php esc_html_e( $step_label[$issite.'sidedishes']) ?></legend>
	<div class="error-msg-show">
		<input type="hidden" name="sidedishesdoc" id="sidedishesdoc" class="sidedishesdoc" value="" <?php echo $mandatorydoument ?>>
	</div>
	<div class="form-group">
		<div class="col-sm-12">
			<?php esc_html_e( 'Additional files that you want to submit to us (CV, certificates, application letter, etc.)', 'prosolwpclient' ) ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="button" class="btn btn-default btn-md attachment-btn-modal pswp-attachment-btn"
					data-bs-toggle="modal" data-uploaded-size="0" data-backdrop="static"
					data-bs-target="#attachmentModal">
				<?php esc_html_e( 'Add Attachment', 'prosolwpclient' ) ?>
			</button>
		</div>
	</div>

	<div class="table-responsive onepage-sidedish">
		<table class="table table-striped table-hover">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Title', 'prosolwpclient' ) ?></th>
				<th><?php esc_html_e( 'Desc.', 'prosolwpclient' ) ?></th>
				<th><?php esc_html_e( 'File', 'prosolwpclient' ) ?></th>
				<th><?php esc_html_e( 'Type', 'prosolwpclient' ) ?></th>
				<th><?php esc_html_e( 'Size', 'prosolwpclient' ) ?></th>
				<th><?php esc_html_e( 'Action', 'prosolwpclient' ) ?></th>
			</tr>
			</thead>

			<tbody id="new_attach_wrapper">

			</tbody>
		</table>
	</div>

	<!-- mustache template -->
	<script id="new_attach_template" type="x-tmpl-mustache">
				<tr class="new-attach-wrap">
					<td>{{name}}</td>
					<td>{{description}}</td>
					<td><a href="{{attach_link}}" target="_blank">{{main_file_name}}</a></td>
					<td>{{radio_type}}</td>
					<td>{{file_size}}</td>
					<td>
					   <a href="#" title="<?php esc_html_e( 'Delete Attachment', 'prosolwpclient' ) ?>"
						   data-filename="{{new_file_name}}" data-filesizebyte="{{filesizebyte}}"
						   class="dashicons dashicons-post-trash trash-attachment"></a>
					</td>
				</tr>
			</script>
</fieldset>