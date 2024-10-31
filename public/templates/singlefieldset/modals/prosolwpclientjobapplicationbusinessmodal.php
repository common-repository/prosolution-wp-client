<?php

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<!-- Business Modal -->
<div class="modal fade" id="businessModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php esc_html_e( 'Select industry', 'prosolwpclient' ) ?></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="pswp-filter-nace"
						   class="col-sm-3 control-label"><?php esc_html_e( 'Filter available NACE', 'prosolwpclient' ) ?></label>
					<div class="col-sm-9">
						<input type="text" name="pswp-filter-nace" class="form-control"
							   id="pswp-filter-nace"
							   placeholder="<?php esc_html_e( 'Filter available NACE', 'prosolwpclient' ) ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php esc_html_e( 'Profession Group', 'prosolwpclient' ); ?></label>
					<div class="col-md-9 business-modal-section">
						<ul class="list-unstyled nace-groups" id="nace_groups_wrapper">
							<!-- mustache template -->
						</ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="conclud_business_ofbutton" type="button" class="btn btn-default" 
						data-bs-dismiss="modal"><?php esc_html_e( 'Conclude', 'prosolwpclient' ) ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script id="nace_groups_template" type="x-tmpl-mustache">
	<?php
		if($pstemplate!=1){
			foreach ( $all_nace as $index => $nace_info ) {
				echo '<li>
					<label class="checkbox-inline">
						<input type="checkbox" name="nacecheckbox" value="' . $nace_info['naceId'] . '">
						<span style="margin-left:0.2em">' . $nace_info['name'] . '</span>
						<span class="checkmark"></span>		
					</label>
						
				</li>';
			}
		} else{
			foreach ( $all_nace as $index => $nace_info ) {
				echo '<li>
					<label class="checkbox-inline">
						<input type="checkbox" name="nacecheckbox" value="' . $nace_info['naceId'] . '">
						<span style="margin-left:0.2em">' . $nace_info['name'] . '</span>
						<span class="checkmark"></span>		
					</label>
						
				</li>';
			}
		}	
	?>

</script>
