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
	$table_ps_profession         = $prosol_prefix . 'profession';
	$table_ps_contract           = $prosol_prefix . 'contract';
	$table_ps_employment         = $prosol_prefix . 'employment';
	$table_ps_experienceposition = $prosol_prefix . 'experienceposition';

	$all_profession         = $wpdb->get_results( "SELECT * FROM $table_ps_profession as profession ORDER BY profession.name ASC", ARRAY_A );
	$contract_arr           = $wpdb->get_results( "SELECT * FROM $table_ps_contract as contract ORDER BY contract.name ASC", ARRAY_A );
	$employment_arr         = $wpdb->get_results( "SELECT * FROM $table_ps_employment as employment ORDER BY employment.name ASC", ARRAY_A );
	$experienceposition_arr = $wpdb->get_results( "SELECT * FROM $table_ps_experienceposition as experienceposition ORDER BY experienceposition.name ASC", ARRAY_A );
	$step_label = get_option('prosolwpclient_applicationform');
?>

<fieldset class="application-info-experience">
	<legend><?php esc_html_e( $step_label[$issite.'workexperience']) ?></legend>

	<div class="container1">
		<ul class="nav nav-tabs incremental-nav-tabs exp-tabs" role="tablist">
			<li class="active">
				<a href="#pswp_exp_0"
				   data-toggle="tab"><?php esc_html_e( 'Experience 1', 'prosolwpclient' ) ?></a><span>x</span>
			</li>
			<li>
				<a href="#" class="pswp-add-exp"
				   data-counter="0" data-numbertrack="2">+ <?php esc_html_e( 'Add Experience', 'prosolwpclient' ) ?></a>
			</li>
		</ul>
		<div class="tab-content exp-tab-content">
			<div class="tab-pane active" id="pswp_exp_0">
				<div class="exp-form-content">
					<br />
					<!--1 Job-->
					<div class="form-group">
						<label for="pswp-exp-job-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Job', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9">
							<select autocomplete="off" class="form-control prosolwpclient-chosen-select" name="experience[0][professionID]" id="pswp-exp-job-0" onchange="getvalue12(this.value)">
								<option value=""><?php esc_html_e( 'Please Select Occupation', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $all_profession as $index => $profession_info ) { ?>
										<option value="<?php echo $profession_info['professionId']; ?>"><?php echo $profession_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--2 Beginning-->
					<div class="form-group">
						<label for="pswp-exp-beginning-date-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Beginning', 'prosolwpclient' ) ?>
							*</label>
						<div class="col-sm-7 error-msg-show">
							<select autocomplete="off" name="experience[0][start]" class="form-control prosolwpclient-chosen-select exp-start prosolwpclient-chosen-selectexperience-0-start" id="pswp-exp-beginning-date-0"  data-rule-expstartyearlessorequal="true" onchange="getvalue12(this.value)">
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
					<!--3 End-->
					<div class="form-group">
						<label for="pswp-exp-end-date-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'The End', 'prosolwpclient' ) ?> *</label>
						<div class="col-sm-7 error-msg-show">
							<select autocomplete="off" name="experience[0][end]" class="form-control prosolwpclient-chosen-select exp-end experience-0-end" id="pswp-exp-end-date-0" data-rule-expendyeargraterorequal="true" onchange="getvalue12(this.value)">
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
					<!--4 Description-->
					<div class="form-group">
						<label for="pswp-exp-description-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Description', 'prosolwpclient' ) ?>
							*</label>
						<div class="col-sm-9 error-msg-show">
                            <textarea name="experience[0][shortNote]" class="form-control experience-0-shortNote"  data-rule-maxlength="400"
									  id="pswp-exp-description-0" rows="4" cols="50" onkeyup="getvalue12(this.value)"></textarea>
						</div>
					</div>
					<!--5 General Description-->
					<div class="form-group">
						<label for="pswp-general-description-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'General Description', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9 error-msg-show">
                            <textarea name="experience[0][notes]" onkeyup="getvalue12(this.value)" class="form-control experience-0-notes" data-rule-maxlength="100"
									  id="pswp-exp-general-description-0" rows="4" cols="50"></textarea>
						</div>
					</div>
					<!--6 Company / Institution-->
					<div class="form-group">
						<label for="pswp-company-institution-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Company / Institution', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9 error-msg-show">
							<input type="text" onkeyup="getvalue12(this.value)" name="experience[0][company]" class="form-control experience-0-company"
								   id="pswp-exp-company-institution-0" data-rule-maxlength="50"
								   placeholder="<?php esc_html_e( 'Company / Institution', 'prosolwpclient' ) ?>">
						</div>
					</div>
					<!--7 Postcode/Town-->
					<div class="form-group">
						<label for="pswp-exp-postcode-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Postcode/Town', 'prosolwpclient' ) ?>
						</label>

						<div class="col-sm-4 error-msg-show">
							<input type="text" onkeyup="getvalue12(this.value)" name="experience[0][zip]" class="form-control experience-0-zip"
								   id="pswp-exp-postcode-0" data-rule-minlength="4" data-rule-maxlength="15" data-rule-digits="true"
								   placeholder="<?php esc_html_e( 'Postcode', 'prosolwpclient' ) ?>">
						</div>
						<div class="col-sm-1 control-label"><span>/</span></div>
						<div class="col-sm-4 error-msg-show">
							<input type="text" name="experience[0][city]" class="form-control experience-0-city" data-rule-maxlength="50" data-rule-lettersonly="true"
								   id="pswp-exp-town-0" onkeyup="getvalue12(this.value)" placeholder="<?php esc_html_e( 'Town', 'prosolwpclient' ) ?>">
						</div>
					</div>
					<!--9 Country-->
					<div class="form-group">
						<label for="pswp-exp-country-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Country', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9">
							<select autocomplete="off" class="form-control prosolwpclient-chosen-select pswp-country-selection" name="experience[0][countryID]" id="pswp-exp-country-0" data-track="0" onchange="getvalue12(this.value)">
								<option value=""><?php esc_html_e( 'Please Select Country', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $country_arr as $index => $country_info ) { ?>
										<option <?php if ( $country_info['countryCode'] === $dafault_nation_selected )
											echo 'selected' ?> value="<?php echo $country_info['countryCode']; ?>"><?php echo $country_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--8 Federal State-->
					<div class="form-group">
						<label for="pswp-exp-federal-state-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Federal State', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9">
							<select autocomplete="off" class="form-control prosolwpclient-chosen-select pswp-federal-selection-0" name="experience[0][federalID]"
									id="pswp-exp-federal-state-0" onchange="getvalue12(this.value)">
								<option value=""><?php esc_html_e( 'Please Select Federal State', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $federal_arr as $index => $federal_info ) { ?>
										<option value="<?php echo $federal_info['federalId']; ?>"><?php echo $federal_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--10 Experience Position-->
					<div class="form-group">
						<label for="pswp-exp-experience-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Experience Position', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9">
							<select autocomplete="off" class="form-control exp-position prosolwpclient-chosen-select" name="experience[0][experiencePositionID][]"
									id="pswp-exp-experience-0" multiple onchange="getvalue12(this.value)">
								<?php
									foreach ( $experienceposition_arr as $index => $experienceposition_info ) { ?>
										<option value="<?php echo $experienceposition_info['experiencepositionId']; ?>"><?php echo $experienceposition_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--11 Contract-->
					<div class="form-group">
						<label for="pswp-exp-contract-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Contract', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9">
							<select autocomplete="off" class="form-control" name="experience[0][contractID]" id="pswp-exp-contract-0" onchange="getvalue12(this.value)">
								<option value=""><?php esc_html_e( 'Please Select Contract', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $contract_arr as $index => $contract_info ) { ?>
										<option value="<?php echo $contract_info['contractId']; ?>"><?php echo $contract_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--12 Employment-->
					<div class="form-group">
						<label for="pswp-exp-employment-0"
							   class="col-sm-3 control-label"><?php esc_html_e( 'Employment', 'prosolwpclient' ) ?></label>
						<div class="col-sm-9">
							<select autocomplete="off" class="form-control" name="experience[0][employmentID]" id="pswp-exp-employment-0" onchange="getvalue12(this.value)">
								<option value=""><?php esc_html_e( 'Please Select Employment', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $employment_arr as $index => $employment_info ) { ?>
										<option value="<?php echo $employment_info['employmentId']; ?>"><?php echo $employment_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var getvalue12 = function(_this,_cnter=""){
			if(_cnter===''){
				if(_this.length > 0){
					document.getElementById("pswp-exp-beginning-date-0").setAttribute("required","true");
					document.getElementById("pswp-exp-end-date-0").setAttribute("required","true");
					document.getElementById("pswp-exp-description-0").setAttribute("required","true");
				}
				var expjob = document.getElementById("pswp-exp-job-0").value;
				var expEndDt = document.getElementById("pswp-exp-end-date-0").value;
				var expBegDt = document.getElementById("pswp-exp-beginning-date-0").value;
				var expGenDesc = document.getElementById("pswp-exp-general-description-0").value;
				var expCompany = document.getElementById("pswp-exp-company-institution-0").value;
				var expPostcd = document.getElementById("pswp-exp-postcode-0").value;
				var expTown = document.getElementById("pswp-exp-town-0").value;
				var expCountry = document.getElementById("pswp-exp-country-0").value;
				var expFeState = document.getElementById("pswp-exp-federal-state-0").value;
				var expExperiance = document.getElementById("pswp-exp-experience-0").value;
				var expContract = document.getElementById("pswp-exp-contract-0").value;
				var expEmployment = document.getElementById("pswp-exp-employment-0").value;
				
				if(expjob === '' && expEndDt === '' && expBegDt === '' && expGenDesc === '' && expCompany === '' && expPostcd === '' && expTown === '' && expCountry === '' && expFeState === '' && expExperiance === '' && expContract === '' && expEmployment === ''){
					document.getElementById("pswp-exp-beginning-date-0").removeAttribute("required");
					document.getElementById("pswp-exp-end-date-0").removeAttribute("required");
					document.getElementById("pswp-exp-description-0").removeAttribute("required");
					
					document.getElementById("pswp-exp-beginning-date-0").classList.remove("error");
					document.getElementById("pswp-exp-end-date-0").classList.remove("error");
					document.getElementById("pswp-exp-description-0").classList.remove("error");
					document.getElementById("pswp-exp-beginning-date-0").nextElementSibling.remove();
					document.getElementById("pswp-exp-end-date-0").nextElementSibling.remove();
					document.getElementById("pswp-exp-description-0").nextElementSibling.remove();
				}
			}else{
				if(_this.length > 0){
					document.getElementById("pswp-exp-beginning-date-"+_cnter).setAttribute("required","true");
					document.getElementById("pswp-exp-end-date-"+_cnter).setAttribute("required","true");
					document.getElementById("pswp-exp-description-"+_cnter).setAttribute("required","true");
				}
				var expjob = document.getElementById("pswp-exp-job-"+_cnter).value;
				var expEndDt = document.getElementById("pswp-exp-end-date-"+_cnter).value;
				var expBegDt = document.getElementById("pswp-exp-beginning-date-"+_cnter).value;
				var expGenDesc = document.getElementById("pswp-exp-general-description-"+_cnter).value;
				var expCompany = document.getElementById("pswp-exp-company-institution-"+_cnter).value;
				var expPostcd = document.getElementById("pswp-exp-postcode-"+_cnter).value;
				var expTown = document.getElementById("pswp-exp-town-"+_cnter).value;
				var expCountry = document.getElementById("pswp-exp-country-"+_cnter).value;
				var expFeState = document.getElementById("pswp-exp-federal-state-"+_cnter).value;
				var expExperiance = document.getElementById("pswp-exp-experience-"+_cnter).value;
				var expContract = document.getElementById("pswp-exp-contract-"+_cnter).value;
				var expEmployment = document.getElementById("pswp-exp-employment-"+_cnter).value;
				
				if(expjob === '' && expEndDt === '' && expBegDt === '' && expGenDesc === '' && expCompany === '' && expPostcd === '' && expTown === '' && expCountry === '' && expFeState === '' && expExperiance === '' && expContract === '' && expEmployment === ''){
					document.getElementById("pswp-exp-beginning-date-"+_cnter).removeAttribute("required");
					document.getElementById("pswp-exp-end-date-"+_cnter).removeAttribute("required");
					document.getElementById("pswp-exp-description-"+_cnter).removeAttribute("required");
					
					document.getElementById("pswp-exp-beginning-date-"+_cnter).classList.remove("error");
					document.getElementById("pswp-exp-end-date-"+_cnter).classList.remove("error");
					document.getElementById("pswp-exp-description-"+_cnter).classList.remove("error");
					document.getElementById("pswp-exp-beginning-date-"+_cnter).nextElementSibling.remove();
					document.getElementById("pswp-exp-end-date-"+_cnter).nextElementSibling.remove();
					document.getElementById("pswp-exp-description-"+_cnter).nextElementSibling.remove();
				}
			}			
		}
	</script>
	<script id="new_exp_template" type="x-tmpl-mustache">
        <div class="new-exp-wrap">
                    <div class="exp-form-content">
            <br/>
            <!--1 Job-->
            <div class="form-group">
                <label for="pswp-exp-job-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Job', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9">
                    <select autocomplete="off"  class="form-control prosolwpclient-chosen-select" name="experience[{{increment}}][professionID]" id="pswp-exp-job-{{increment}}" onchange="getvalue12(this.value,{{increment}})">
                        <option value=""><?php esc_html_e( 'Please Select Occupation', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $all_profession as $index => $profession_info ) { ?>
                                <option value="<?php echo $profession_info['professionId']; ?>"><?php echo $profession_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <!--2 Beginning-->
            <div class="form-group">
                <label for="pswp-exp-beginning-date-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Beginning', 'prosolwpclient' ) ?> *</label>
                <div class="col-sm-7 error-msg-show">
					<select autocomplete="off"  name="experience[{{increment}}][start]" class="form-control exp-start experience-{{increment}}-start" id="pswp-exp-beginning-date-{{increment}}"  data-rule-expstartyearlessorequal="true" onchange="getvalue12(this.value,{{increment}})">
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
            <!--3 End-->
            <div class="form-group">
                <label for="pswp-exp-end-date-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'The End', 'prosolwpclient' ) ?> *</label>
                <div class="col-sm-7 error-msg-show">
					<select autocomplete="off"  name="experience[{{increment}}][end]" class="form-control exp-end experience-{{increment}}-end" id="pswp-exp-end-date-{{increment}}"  data-rule-expendyeargraterorequal="true" onchange="getvalue12(this.value,{{increment}})">
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
            <!--4 Description-->
            <div class="form-group">
                <label for="pswp-exp-description-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Description', 'prosolwpclient' ) ?> *</label>
                <div class="col-sm-9 error-msg-show">
                        <textarea name="experience[{{increment}}][shortNote]" onkeyup="getvalue12(this.value,{{increment}})" class="form-control experience-{{increment}}-shortNote"
                                  id="pswp-exp-description-{{increment}}" rows="4" cols="50"  data-rule-maxlength="400"></textarea>
                </div>
            </div>
            <!--5 General Description-->
            <div class="form-group">
                <label for="pswp-general-description-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'General Description', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9 error-msg-show">
                        <textarea name="experience[{{increment}}][notes]" onkeyup="getvalue12(this.value,{{increment}})" class="form-control experience-{{increment}}-notes" data-rule-maxlength="100"
                                  id="pswp-exp-general-description-{{increment}}" rows="4" cols="50"></textarea>
                </div>
            </div>
            <!--6 Company / Institution-->
            <div class="form-group">
                <label for="pswp-company-institution-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Company / Institution', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9 error-msg-show">
                    <input type="text" name="experience[{{increment}}][company]" onkeyup="getvalue12(this.value,{{increment}})" class="form-control experience-{{increment}}-company"
                           id="pswp-exp-company-institution-{{increment}}" data-rule-maxlength="50"
                           placeholder="<?php esc_html_e( 'Company / Institution', 'prosolwpclient' ) ?>">
                </div>
            </div>
            <!--7 Postcode/Town-->
            <div class="form-group">
                <label for="pswp-exp-postcode-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Postcode/Town', 'prosolwpclient' ) ?>
                </label>

                <div class="col-sm-4 error-msg-show">
                    <input type="text" name="experience[{{increment}}][zip]" onkeyup="getvalue12(this.value,{{increment}})" class="form-control experience-{{increment}}-zip"
                           id="pswp-exp-postcode-{{increment}}" data-rule-minlength="4" data-rule-maxlength="15" data-rule-digits="true"
                           placeholder="<?php esc_html_e( 'Postcode', 'prosolwpclient' ) ?>">
                </div>
                <div class="col-sm-1 control-label"><span>/</span></div>
                <div class="col-sm-4 error-msg-show">
                    <input type="text" name="experience[{{increment}}][city]" class="form-control experience-{{increment}}-city" data-rule-maxlength="50" data-rule-lettersonly="true"
                           id="pswp-exp-town-{{increment}}" placeholder="<?php esc_html_e( 'Town', 'prosolwpclient' ) ?>">
                </div>
            </div>
            <!--9 Country-->
            <div class="form-group">
                <label for="pswp-exp-country-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Country', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9">
                    <select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-country-selection" name="experience[{{increment}}][countryID]" id="pswp-exp-country-{{increment}}" data-track="{{increment}}" onchange="getvalue12(this.value,{{increment}})">
                        <option value=""><?php esc_html_e( 'Please Select Country', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $country_arr as $index => $country_info ) { ?>
                                <option <?php if ( $country_info['countryCode'] === $dafault_nation_selected )
				echo 'selected' ?> value="<?php echo $country_info['countryCode']; ?>"><?php echo $country_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <!--8 Federal State-->
            <div class="form-group">
                <label for="pswp-exp-federal-state-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Federal State', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9">
                    <select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-federal-selection-{{increment}}" name="experience[{{increment}}][federalID]" id="pswp-exp-federal-state-{{increment}}" onchange="getvalue12(this.value,{{increment}})">
                        <option value=""><?php esc_html_e( 'Please Select Federal State', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $federal_arr as $index => $federal_info ) { ?>
                                <option value="<?php echo $federal_info['federalId']; ?>"><?php echo $federal_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <!--10 Experience Position-->
            <div class="form-group">
                <label for="pswp-exp-experience-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Experience Position', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9">
                    <select autocomplete="off"  class="form-control exp-position prosolwpclient-chosen-select" name="experience[{{increment}}][experiencePositionID][]" id="pswp-exp-experience-{{increment}}" onchange="getvalue12(this.value,{{increment}})" multiple
                            >
                        <?php
			foreach ( $experienceposition_arr as $index => $experienceposition_info ) { ?>
                                <option value="<?php echo $experienceposition_info['experiencepositionId']; ?>"><?php echo $experienceposition_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <!--11 Contract-->
            <div class="form-group">
                <label for="pswp-exp-contract-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Contract', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9">
                    <select autocomplete="off"  class="form-control" name="experience[{{increment}}][contractID]" id="pswp-exp-contract-{{increment}}" onchange="getvalue12(this.value,{{increment}})">
                        <option value=""><?php esc_html_e( 'Please Select Contract', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $contract_arr as $index => $contract_info ) { ?>
                                <option value="<?php echo $contract_info['contractId']; ?>"><?php echo $contract_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <!--12 Employment-->
            <div class="form-group">
                <label for="pswp-exp-employment-{{increment}}"
                       class="col-sm-3 control-label"><?php esc_html_e( 'Employment', 'prosolwpclient' ) ?></label>
                <div class="col-sm-9">
                    <select autocomplete="off"  class="form-control" name=experience[{{increment}}][employmentID]" id="pswp-exp-employment-{{increment}}" onchange="getvalue12(this.value,{{increment}})">
                        <option value=""><?php esc_html_e( 'Please Select Employment', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $employment_arr as $index => $employment_info ) { ?>
                                <option value="<?php echo $employment_info['employmentId']; ?>"><?php echo $employment_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            </div>
        </div>





	</script>

	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-11">
			<p>(*) = <?php esc_html_e( 'required', 'prosolwpclient' ) ?>!</p>
		</div>
	</div>
</fieldset>