<?php

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<!-- Job Modal -->
<div class="modal fade" id="jobModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php esc_html_e( 'Select occupation', 'prosolwpclient' ) ?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group">
						<label for="pswp-filter-occupation"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Filter available occupations', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9">
							<input type="text" name="pswp-filter-occupation" class="form-control"
								   id="pswp-filter-occupation"
								   placeholder="<?php esc_html_e( 'Filter available occupations', 'prosolwpclient' ) ?>">
						</div>
					</div><br/><br/>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php esc_html_e( 'Profession Group', 'prosolwpclient' ); ?></label>
						<div class="col-md-9 job-modal-section">
							<ul class="list-unstyled job-profession-groups">
								<?php 
									if($pstemplate!=1){
										foreach ( $all_profession as $index => $profession_info ) {
											echo '<li>
												<label class="checkbox-inline">
													<input type="checkbox" name="professioncheckbox" value="' . $profession_info['professionId'] . '">
													<span style="margin-left:0.2em">' . $profession_info['name'] . '</span>
													<span class="checkmark-layer"></span>		
												</label>
											</li>';								
										}
									} else{
										foreach ( $all_profession as $index => $profession_info ) {												
											echo '<li>
												<label class="checkbox-inline">												
													<input type="checkbox" name="professioncheckbox" value="' . $profession_info['professionId'] . '">
													<span style="margin-left:0.2em">' . $profession_info['name'] . '</span>
													<span class="checkmark-layer"></span>		
												</label>
											</li>';
										}
									}	
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"
						data-bs-dismiss="modal"><?php esc_html_e( 'Conclude', 'prosolwpclient' ) ?>
				</button>
			</div>
		</div>
	</div>
</div>