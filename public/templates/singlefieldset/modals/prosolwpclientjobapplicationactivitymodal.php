<?php

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<!-- Activity Modal -->
<div class="modal fade" id="activityModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php esc_html_e( 'Select activity area', 'prosolwpclient' ) ?></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="pswp-filter-area"
						   class="col-md-3 control-label"><?php esc_html_e( 'Filter available operation areas', 'prosolwpclient' ) ?></label>
					<div class="col-md-9">
						<input type="text" name="pswp-filter-area" class="form-control"
							   id="pswp-filter-area"
							   placeholder="<?php esc_html_e( 'Filter available operation areas', 'prosolwpclient' ) ?>">
					</div>
				</div><br/><br/>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php esc_html_e( 'Profession Group', 'prosolwpclient' ); ?></label>
					<div class="col-md-9 activity-modal-section">
						<ul class="list-unstyled operation-areas" id="operation_areas_wrapper">
							<!-- mustache template -->
						</ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="conclud_active_ofbutton" class="btn btn-default"
						data-bs-dismiss="modal"><?php esc_html_e( 'Conclude', 'prosolwpclient' ) ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script id="operation_areas_template" type="x-tmpl-mustache">
	<?php 
		if($pstemplate!=1){
			foreach ( $all_operationarea as $index => $area_info ) {
				echo '<li>
					<label class="checkbox-inline">
						<input type="checkbox" name="operationareacheckbox" value="' . $area_info["operationareaId"] . '">
						<span style="margin-left:0.2em">'. $area_info["name"] . '</span>
						<span class="checkmark-layer"></span>	
					</label>
				</li>';
			}
		} else{
			foreach ( $all_operationarea as $index => $area_info ) {
				echo '<li>
					<label class="checkbox-inline">
						<input type="checkbox" name="operationareacheckbox" value="' . $area_info["operationareaId"] . '">
						<span style="margin-left:0.2em">'. $area_info["name"] . '</span>
						<span class="checkmark-layer"></span>	
					</label>
				</li>';
			}
		}	
	?>
</script>
