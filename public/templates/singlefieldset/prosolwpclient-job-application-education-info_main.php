<?php

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<?php
	$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
	$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
	$siteid		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);

	global $wpdb;global $prosol_prefix;
	$table_ps_nace          = $prosol_prefix . 'nace';
	$table_ps_operationarea = $prosol_prefix . 'operationarea';

	$all_nace          = $wpdb->get_results( "SELECT * FROM $table_ps_nace as nace WHERE nace.site_id='$siteid' ORDER BY nace.name ASC", ARRAY_A );
	$all_operationarea = $wpdb->get_results( "SELECT * FROM $table_ps_operationarea as operationarea WHERE operationarea.site_id='$siteid' ORDER BY operationarea.name ASC", ARRAY_A );
	
	$step_label = get_option('prosolwpclient_applicationform');
?>
<fieldset class="application-info-education">
	<legend><?php esc_html_e( $step_label[$issite.'education'] ) ?></legend>

	<div class="">
		<ul class="nav nav-tabs incremental-nav-tabs edu-tabs" role="tablist">
			<li class="active">
				<a href="#pswp_edu_0"
				   data-toggle="tab"><?php esc_html_e( 'Education 1', 'prosolwpclient' ) ?></a><span>x</span>
			</li>
			<li>
				<a href="#" class="pswp-add-edu"
				   data-counter="0" data-numbertrack="2">+ <?php esc_html_e( 'Add Education', 'prosolwpclient' ) ?></a>
			</li>
		</ul>
		<div class="tab-content edu-tab-content">
			<div class="tab-pane active" id="pswp_edu_0">
				<div class="edu-form-content">
					<br />
					<!--1 Group-->
					<div class="form-group">
						<label for="pswp-group-0" class="col-sm-3 control-label"><?php esc_html_e( 'Group', 'prosolwpclient' ) ?> *</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off"  class="form-control group-selection prosolwpclient-chosen-select education-0-typeID" name="education[0][typeID]" id="pswp-group-0"
									data-track="0" onchange="getvalue(this.value)">
								<option value=""><?php esc_html_e( 'Please Select Group', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $education_arr as $index => $education_info ) { ?>
										<option value="<?php echo $education_info['educationId']; ?>"><?php echo $education_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--2 Training / Practice-->
					<div class="form-group">
						<label for="pswp-training-practice-0" class="col-sm-3 control-label"><?php esc_html_e( 'Training / Practice', 'prosolwpclient' ) ?> *</label>
						<div class="col-sm-9 error-msg-show training-practice-section-0">
							<select autocomplete="off"  class="form-control training-practice prosolwpclient-chosen-select education-0-detailID" name="education[0][detailID]"
									id="pswp-training-practice-0" >
								<option value=""><?php esc_html_e( 'Please Select Training / Practice', 'prosolwpclient' ) ?></option>
								<?php
/*									foreach ( $educationlookup_arr as $index => $educationlookup_info ) { */?><!--
										<option value="<?php /*echo $educationlookup_info['lookupId']; */?>"><?php /*echo $educationlookup_info['name']; */?></option>
									--><?php /*} */?>
							</select>
						</div>
					</div>
					<!--3 Beginning-->
					<div class="form-group">
						<label for="pswp-beginning-date-0" class="col-sm-3 control-label"><?php esc_html_e( 'Beginning', 'prosolwpclient' ) ?> *</label>
						<div class="col-sm-7 error-msg-show">
							<select autocomplete="off"  name="education[0][start]" class="form-control prosolwpclient-chosen-select edu-start education-0-start" id="pswp-beginning-date-0"  data-rule-startyearlessorequal="true">
								<option value=""><?php esc_html_e( 'Please Pick Beginning Year', 'prosolwpclient' ) ?></option>
								<?php
									for ( $i = date( 'Y' ); $i >= 1900; $i -- ) { ?>
										<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php } ?>
							</select>
						</div>
						<div class="col-sm-2">
							<span>YYYY</span>
						</div>
					</div>
					<!--4 End-->
					<div class="form-group">
						<label for="pswp-end-date-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'The End', 'prosolwpclient' ) ?> *</label>
						<div class="col-sm-7 error-msg-show">
							<select autocomplete="off" name="education[0][end]" class="form-control prosolwpclient-chosen-select edu-end education-0-end" id="pswp-end-date-0"  data-rule-endyeargraterorequal="true">
								<option value=""><?php esc_html_e( 'Please Pick The End Year', 'prosolwpclient' ) ?></option>
								<?php
									for ( $i = date( 'Y' ); $i >= 1900; $i -- ) { ?>
										<option value="<?php echo $i ?>"><?php echo $i ?></option>
									<?php } ?>
							</select>
						</div>
						<div class="col-sm-2">
							<span>YYYY</span>
						</div>
					</div>
					<!--5 Postcode/Town-->
					<div class="form-group">
						<label for="pswp-edu-postcode-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Postcode/Town', 'prosolwpclient' ) ?>
						</label>

						<div class="col-sm-4 error-msg-show">
							<input type="text" name="education[0][zip]" class="form-control education-0-zip"
								   id="pswp-edu-postcode-0" data-rule-minlength="4" data-rule-maxlength="15" data-rule-digits="true"
								   placeholder="<?php esc_html_e( 'Postcode', 'prosolwpclient' ) ?>">
						</div>
						<div class="col-sm-1 control-label"><span>/</span></div>
						<div class="col-sm-4 error-msg-show">
							<input type="text" name="education[0][city]" class="form-control education-0-city"
								   id="pswp-edu-town-0" data-rule-maxlength="50" data-rule-lettersonly="true" placeholder="<?php esc_html_e( 'Town', 'prosolwpclient' ) ?>">
						</div>
					</div>
					<!--7 Country-->
					<div class="form-group">
						<label for="pswp-edu-country-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Country', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-country-selection" name="education[0][countryId]" id="pswp-edu-country-0" data-track="0">
								<option value=""><?php esc_html_e( 'Please Select Country', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $country_arr as $index => $country_info ) { ?>
										<option <?php if ( $country_info['countryCode'] === $dafault_nation_selected )
											echo 'selected' ?> value="<?php echo $country_info['countryCode']; ?>"><?php echo $country_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--6 Federal State-->
					<div class="form-group">
						<label for="pswp-edu-federal-state-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Federal State', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-federal-selection-0" name="education[0][federalId]"
									id="pswp-edu-federal-state-0">
								<option value=""><?php esc_html_e( 'Please Select Federal State', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $federal_arr as $index => $federal_info ) { ?>
										<option value="<?php echo $federal_info['federalId']; ?>"><?php echo $federal_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--8 Level of Education-->
					<div class="form-group">
						<label for="pswp-education-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Level of Education', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off"  class="form-control" name="education[0][iscedID]" id="pswp-education-0">
								<option value=""><?php esc_html_e( 'None', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $isced_arr as $index => $isced_info ) { ?>
										<option value="<?php echo $isced_info['iscedId']; ?>"><?php echo $isced_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--9 Field of Activity-->
					<div class="form-group">
						<label for="pswp-activity-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Field of Activity', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9 activity-section-wrap-0 error-msg-show">
							<input type="hidden" name="education[0][operationAreaID]">


							<ul id="activity_selection_wrapper" style="list-style-type: none;">
								<!-- mustache template -->
							</ul>

							<button type="button" class="btn btn-default btn-md activity-btn-modal" data-bs-toggle="modal" data-activity-modaltrack="0"
									data-bs-target="#activityModal">
								<?php esc_html_e( 'Choose', 'prosolwpclient' ) ?>
							</button>
						</div>
					</div>

					<!--10 Business-->
					<div class="form-group">
						<label for="pswp-business-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Business', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9 business-section-wrap-0 error-msg-show">
							<input type="hidden" name="education[0][naceID]">
							<!--<select name="education[0][naceID][]" class="form-control business-section"
									style="display: none; " multiple>
							</select>-->
							<ul id="business_selection_wrapper" style="list-style-type: none;">
								<!-- mustache template -->
							</ul>
							<button type="button" class="btn btn-default btn-md business-btn-modal" data-bs-toggle="modal" data-business-modaltrack="0"
									data-bs-target="#businessModal">
								<?php esc_html_e( 'strip' ) ?>
							</button>
						</div>
					</div>
					<!--11 Description-->
					<div class="form-group">
						<label for="pswp-description-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Description', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9 error-msg-show">
                        <textarea name="education[0][notes]" class="form-control education-0-notes" data-rule-maxlength="400"
								  id="pswp-description-0" rows="4" cols="50"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		// (function ($) {
		// 	'use strict';
			
			
		// })(jQuery);
	</script>

	<script id="new_edu_template" type="x-tmpl-mustache">
        <div class="new-edu-wrap">
        <div class="edu-form-content">


            <br/>
            <div class="form-group">
                <label for="pswp-group-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Group', 'prosolwpclient' ) ?> *</label>
                <div class="col-sm-9 error-msg-show">
                    <select autocomplete="off"  class="form-control group-selection prosolwpclient-chosen-select education-{{increment}}-typeID" name="education[{{increment}}][typeID]" id="pswp-group-{{increment}}" data-track="{{increment}}" required data-rule-required="true">
                         <option value=""><?php esc_html_e( 'Please Select Group', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $education_arr as $index => $education_info ) { ?>
                                <option value="<?php echo $education_info['educationId']; ?>"><?php echo $education_info['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <!--2 Training / Practice-->
            <div class="form-group">
                <label for="pswp-training-practice-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Training / Practice', 'prosolwpclient' ) ?> *</label>
                <div class="col-sm-9 error-msg-show training-practice-section-{{increment}}">
                    <select autocomplete="off"  class="form-control training-practice prosolwpclient-chosen-select education-{{increment}}-detailID" name="education[{{increment}}][detailID]" id="pswp-training-practice-{{increment}}" required data-rule-required="true">
                         <option value=""><?php esc_html_e( 'Please Select Training / Practice', 'prosolwpclient' ) ?></option>
                        <?php
/*			foreach ( $educationlookup_arr as $index => $educationlookup_info ) { */?><!--
                                <option value="<?php /*echo $educationlookup_info['lookupId']; */?>"><?php /*echo $educationlookup_info['name']; */?></option>
                        --><?php /*} */?>
                    </select>
                </div>
            </div>
            <!--3 Beginning-->
            <div class="form-group">
                <label for="pswp-beginning-date-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Beginning', 'prosolwpclient' ) ?> *</label>
                <div class="col-sm-7 error-msg-show">
					<select autocomplete="off"  name="education[{{increment}}][start]" class="form-control edu-start education-{{increment}}-start" id="pswp-beginning-date-{{increment}}" required data-rule-required="true" data-rule-startyearlessorequal="true">
						<option value=""><?php esc_html_e( 'Please Pick Beginning Year', 'prosolwpclient' ) ?></option>
						<?php
			for ( $i = date( 'Y' ); $i >= 1900; $i -- ) { ?>
							<option value="<?php echo $i ?>"><?php echo $i ?></option>
						<?php } ?>
					</select>
                </div>
                <div class="col-sm-2">
                    <span>YYYY</span>
                </div>
            </div>
            <!--4 End-->
            <div class="form-group">
                <label for="pswp-end-date-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'The End', 'prosolwpclient' ) ?> *</label>
                <div class="col-sm-7 error-msg-show">
					<select autocomplete="off"  name="education[{{increment}}][end]" class="form-control edu-end education-{{increment}}-end" id="pswp-end-date-{{increment}}" required data-rule-required="true" data-rule-endyeargraterorequal="true">
						<option value=""><?php esc_html_e( 'Please Pick The End Year', 'prosolwpclient' ) ?></option>
						<?php
			for ( $i = date( 'Y' ); $i >= 1900; $i -- ) { ?>
							<option value="<?php echo $i ?>"><?php echo $i ?></option>
						<?php } ?>
					</select>
                </div>
                <div class="col-sm-2">
                    <span>YYYY</span>
                </div>
            </div>
            <!--5 Postcode/Town-->
            <div class="form-group">
                <label for="pswp-edu-postcode-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Postcode/Town', 'prosolwpclient' ) ?></label>

                <div class="col-sm-4 error-msg-show">
                    <input type="text" name="education[{{increment}}][zip]" class="form-control education-{{increment}}-zip"
                           id="pswp-edu-postcode-{{increment}}" data-rule-minlength="4" data-rule-maxlength="15" data-rule-digits="true" placeholder="<?php esc_html_e( 'Postcode', 'prosolwpclient' ) ?>">
                </div>
                <div class="col-sm-1 control-label"><span>/</span></div>
                <div class="col-sm-4 error-msg-show">
                    <input type="text" name="education[{{increment}}][city]" class="form-control education-{{increment}}-city"
                           id="pswp-edu-town-{{increment}}" data-rule-maxlength="50" data-rule-lettersonly="true" placeholder="<?php esc_html_e( 'Town', 'prosolwpclient' ) ?>">
                </div>
            </div>
            <!--7 Country-->
            <div class="form-group">
                <label for="pswp-edu-country-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Country', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9  error-msg-show">
                    <select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-country-selection" name="education[{{increment}}][countryID]" id="pswp-edu-country-{{increment}}" data-track="{{increment}}">
                        <option value=""><?php esc_html_e( 'Please Select Country', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $country_arr as $index => $country_info ) { ?>
                                                    <option <?php if ( $country_info['countryCode'] === $dafault_nation_selected )
				echo 'selected' ?> value="<?php echo $country_info['countryCode']; ?>"><?php echo $country_info['name']; ?></option>
                                                <?php } ?>
                    </select>
                </div>
            </div>
            <!--6 Federal State-->
                <div class="form-group">
                    <label for="pswp-edu-federal-state-{{increment}}"
                           class="col-sm-3 control-label"><?php esc_html_e( 'Federal State', 'prosolwpclient' ) ?></label>
                    <div class="col-sm-9  error-msg-show">
                        <select autocomplete="off" class="form-control prosolwpclient-chosen-select pswp-federal-selection-{{increment}}" name="education[{{increment}}][federalID]" id="pswp-edu-federal-state-{{increment}}">
                            <option value=""><?php esc_html_e( 'Please Select Federal State', 'prosolwpclient' ) ?></option>
                            <?php
			foreach ( $federal_arr as $index => $federal_info ) { ?>
                                    <option value="<?php echo $federal_info['federalId']; ?>"><?php echo $federal_info['name']; ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
            <!--8 Level of Education-->
            <div class="form-group">
                <label for="pswp-education-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Level of Education', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9  error-msg-show">
                    <select autocomplete="off"  class="form-control" name="education[{{increment}}][iscedID]" id="pswp-education-{{increment}}">
                        <option value=""><?php esc_html_e( 'None', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $isced_arr as $index => $isced_info ) { ?>
                                <option value="<?php echo $isced_info['iscedId']; ?>"><?php echo $isced_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <!--9 Field of Activity-->
            <div class="form-group">
                <label for="pswp-activity-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Field of Activity', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9 activity-section-wrap-{{increment}} error-msg-show">
                    <input type="hidden" name="education[{{increment}}][operationAreaID]">
					<ul id="activity_selection_wrapper" style="list-style-type: none;">
						<!-- mustache template -->
					</ul>
                    <button type="button" class="btn btn-default btn-md activity-btn-modal" data-bs-toggle="modal" data-activity-modaltrack="{{increment}}"
                            data-bs-target="#activityModal">
                        <?php esc_html_e( 'Choose', 'prosolwpclient' ) ?>
                    </button>
                </div>
            </div>
            <!--10 Business-->
            <div class="form-group">
                <label for="pswp-business-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Business', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9 business-section-wrap-{{increment}} error-msg-show">
                    <input type="hidden" name="education[{{increment}}][naceID]">
                    <ul id="business_selection_wrapper" style="list-style-type: none;">
						<!-- mustache template -->
					</ul>
                    <button type="button" class="btn btn-default btn-md business-btn-modal" data-bs-toggle="modal" data-business-modaltrack="{{increment}}"
                            data-bs-target="#businessModal">
                        <?php esc_html_e( 'Choose', 'prosolwpclient' ) ?>
                    </button>
                </div>
            </div>
            <!--11 Description-->
            <div class="form-group">
                <label for="pswp-description-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Description', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9 error-msg-show">
                    <textarea name="education[{{increment}}][notes]" class="form-control education-{{increment}}-notes" data-rule-maxlength="400"
                        id="pswp-description-{{increment}}" rows="4" cols="50"></textarea>
                </div>
            </div>
        </div>
        </div>


	</script>

	<script id="activity_selection_template" type="x-tmpl-mustache">
		<li class="activity-selection-wrap activity-li-{{activityid}}">
			<input type="hidden" name="education[{{index}}][operationAreaID][]" value="{{activityid}}">
			<span class="activity-title">{{activity_title}}</span>
			<span class="activity-remove" data-activityid="{{activityid}}" data-track="{{track}}" style="color: red;"> x </span>
		</li>


	</script>

	<script id="business_selection_template" type="x-tmpl-mustache">
		<li class="business-selection-wrap business-li-{{businessid}}">
			<input type="hidden" name="education[{{index}}][naceID][]" value="{{businessid}}">
			<span class="business-title">{{business_title}}</span>
			<span class="business-remove" data-businessid="{{businessid}}" data-track="{{track}}" style="color: red;"> x </span>
		</li>


	</script>

	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-11">
			<p>(*) = <?php esc_html_e( 'required', 'prosolwpclient' ) ?>!</p>
		</div>
	</div>
</fieldset>