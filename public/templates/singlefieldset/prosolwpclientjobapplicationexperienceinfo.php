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
	$table_ps_contract           = $prosol_prefix . 'contract';
	$table_ps_employment         = $prosol_prefix . 'employment';
	$table_ps_experienceposition = $prosol_prefix . 'experienceposition';

	$contract_arr           = $wpdb->get_results( "SELECT * FROM $table_ps_contract as contract WHERE contract.site_id='$siteid'  ORDER BY contract.name ASC", ARRAY_A );
	$employment_arr         = $wpdb->get_results( "SELECT * FROM $table_ps_employment as employment WHERE employment.site_id='$siteid'  ORDER BY employment.name ASC", ARRAY_A );
	$experienceposition_arr = $wpdb->get_results( "SELECT * FROM $table_ps_experienceposition as experienceposition WHERE experienceposition.site_id='$siteid'  ORDER BY experienceposition.name ASC", ARRAY_A );
	$opt = get_option('prosolwpclient_applicationform');
	$sect = $isset.'workexperience';
	$fields_section=array('job','gendesc','company','postcode','country','federal','experience','contract','employment');
	$field_opt=array();
	foreach($fields_section as $field){
		$field_opt[$field][1]=$opt[$sect.'_'.$field.'_act'] ? '' : 'hidden';
		$field_opt[$field][2]=$opt[$sect.'_'.$field.'_man'] ? '*' : '';
		$field_opt[$field][3]=$opt[$sect.'_'.$field.'_man'] ? 'data-required="true"' : '';
		$field_opt[$field][4]=$opt[$sect.'_'.$field.'_man'] ? 'required' : '';
	}
?>

<fieldset class="application-info-experience">
	<legend><?php esc_html_e( $opt[$sect]) ?></legend>

	<div class="container1">
		<ul class="nav nav-tabs incremental-nav-tabs exp-tabs" id="exp_tab" role="tablist">
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
					<div class="form-group <?php echo $field_opt['job'][1] ?>">
						<label for="pswp-exp-job-0" class="col-sm-3 control-label"><?php esc_html_e( 'Job', 'prosolwpclient' ) ?>
						<?php echo $field_opt['job'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off" class="form-control prosolwpclient-chosen-select exp-0 " name="experience[0][professionID]" 
								<?php echo $field_opt['job'][3] ?> id="pswp-exp-job-0" onchange="getvalue12(this.value)">
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
						<label for="pswp-exp-beginning-date-0" class="col-sm-3 control-label"><?php esc_html_e( 'Beginning', 'prosolwpclient' ) ?>
						*
						</label>
						<div class="col-sm-7 error-msg-show">
							<select autocomplete="off" name="experience[0][start]" class="form-control prosolwpclient-chosen-select exp-start exp-0 experience-0-start"
								data-required="true" id="pswp-exp-beginning-date-0"  data-rule-expstartyearlessorequal="true" onchange="getvalue12(this.value)">
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
						<label for="pswp-exp-end-date-0" class="col-sm-3 control-label"><?php esc_html_e( 'The End', 'prosolwpclient' ) ?> 
						*
						</label>
						<div class="col-sm-7 error-msg-show">
							<select autocomplete="off" name="experience[0][end]" class="form-control prosolwpclient-chosen-select exp-end exp-0 experience-0-end"
								data-required="true" id="pswp-exp-end-date-0" data-rule-expendyeargraterorequal="true" onchange="getvalue12(this.value)">
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
						<label for="pswp-exp-description-0" class="col-sm-3 control-label"><?php esc_html_e( 'Description', 'prosolwpclient' ) ?>
						*
						</label>
						<div class="col-sm-9 error-msg-show">
                            <textarea name="experience[0][shortNote]" class="form-control exp-0 experience-0-shortNote"  data-rule-maxlength="400"
								data-required="true" id="pswp-exp-description-0" rows="4" cols="50" onkeyup="getvalue12(this.value)" placeholder="<?php esc_html_e( 'Briefly describe your activity', 'prosolwpclient' ) ?>"></textarea>
						</div>
					</div>
					<!--5 General Description-->
					<div class="form-group <?php echo $field_opt['gendesc'][1] ?>">
						<label for="pswp-general-description-0" class="col-sm-3 control-label"><?php esc_html_e( 'General Description', 'prosolwpclient' ) ?>
						<?php echo $field_opt['gendesc'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
                            <textarea name="experience[0][notes]" onkeyup="getvalue12(this.value)" class="form-control exp-0 experience-0-notes" data-rule-maxlength="100"
								<?php echo $field_opt['gendesc'][3] ?> id="pswp-exp-general-description-0" rows="4" cols="50" placeholder="<?php esc_html_e( 'Briefly describe your activity', 'prosolwpclient' ) ?>"></textarea>
						</div>
					</div>
					<!--6 Company / Institution-->
					<div class="form-group <?php echo $field_opt['company'][1] ?>">
						<label for="pswp-company-institution-0" class="col-sm-3 control-label"><?php esc_html_e( 'Company / Institution', 'prosolwpclient' ) ?>
						<?php echo $field_opt['company'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
							<input type="text" onkeyup="getvalue12(this.value)" name="experience[0][company]" class="form-control exp-0 experience-0-company"
								<?php echo $field_opt['company'][3] ?> id="pswp-exp-company-institution-0" data-rule-maxlength="50"
								   placeholder="<?php esc_html_e( 'Company / Institution', 'prosolwpclient' ) ?>">
						</div>
					</div>
					<!--7 Postcode/Town-->
					<div class="form-group <?php echo $field_opt['postcode'][1] ?>">
						<label for="pswp-exp-postcode-0" class="col-sm-3 control-label"><?php esc_html_e( 'Postcode/Town', 'prosolwpclient' ) ?>
						<?php echo $field_opt['postcode'][2] ?>
						</label>

						<div class="col-sm-4 error-msg-show">
							<input type="text" onkeyup="getvalue12(this.value)" name="experience[0][zip]" class="form-control exp-0 experience-0-zip"
								<?php echo $field_opt['postcode'][3] ?> id="pswp-exp-postcode-0" data-rule-minlength="4" data-rule-maxlength="15" data-rule-digits="true"
								   placeholder="<?php esc_html_e( 'Postcode', 'prosolwpclient' ) ?>">
						</div>
						<div class="col-sm-1 control-label"><span>/</span></div>
						<div class="col-sm-4 error-msg-show">
							<input type="text" name="experience[0][city]" class="form-control exp-0 experience-0-city" data-rule-maxlength="50" data-rule-lettersonly="true"
								<?php echo $field_opt['postcode'][3] ?> id="pswp-exp-town-0" onkeyup="getvalue12(this.value)" placeholder="<?php esc_html_e( 'Town', 'prosolwpclient' ) ?>">
						</div>
					</div>
					<!--9 Country-->
					<div class="form-group <?php echo $field_opt['country'][1] ?>">
						<label for="pswp-exp-country-0" class="col-sm-3 control-label"><?php esc_html_e( 'Country', 'prosolwpclient' ) ?>
						<?php echo $field_opt['country'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off" class="form-control prosolwpclient-chosen-select exp-0 exp-0-country pswp-country-selection" name="experience[0][countryID]" 
							<?php echo $field_opt['country'][3] ?> data-defnation="<?php echo $dafault_nation_selected ?>" id="pswp-exp-country-0" data-track="0" onchange="getvalue12(this.value)">
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
					<div class="form-group <?php echo $field_opt['federal'][1] ?>">
						<label for="pswp-exp-federal-state-0" class="col-sm-3 control-label"><?php esc_html_e( 'Federal State', 'prosolwpclient' ) ?>
						<?php echo $field_opt['federal'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off" class="form-control prosolwpclient-chosen-select exp-0 pswp-federal-selection-0" name="experience[0][federalID]"
								<?php echo $field_opt['federal'][3] ?> id="pswp-exp-federal-state-0" onchange="getvalue12(this.value)">
								<option value=""><?php esc_html_e( 'Please Select Federal State', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $federal_arr as $index => $federal_info ) { ?>
										<option value="<?php echo $federal_info['federalId']; ?>"><?php echo $federal_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--10 Experience Position-->
					<div class="form-group <?php echo $field_opt['experience'][1] ?>">
						<label for="pswp-exp-experience-0" class="col-sm-3 control-label"><?php esc_html_e( 'Experience Position', 'prosolwpclient' ) ?>
						<?php echo $field_opt['experience'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off" class="form-control exp-0 exp-position prosolwpclient-chosen-select" name="experience[0][experiencePositionID][]"
								<?php echo $field_opt['experience'][3] ?> id="pswp-exp-experience-0" multiple onchange="getvalue12(this.value)">
								<?php
									foreach ( $experienceposition_arr as $index => $experienceposition_info ) { ?>
										<option value="<?php echo $experienceposition_info['experiencepositionId']; ?>"><?php echo $experienceposition_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--11 Contract-->
					<div class="form-group <?php echo $field_opt['contract'][1] ?>">
						<label for="pswp-exp-contract-0" class="col-sm-3 control-label"><?php esc_html_e( 'Contract', 'prosolwpclient' ) ?>
						<?php echo $field_opt['contract'][2] ?>
						</label>
						<div class="col-sm-9  error-msg-show">
							<select autocomplete="off" class="form-control prosolwpclient-chosen-select exp-0 " name="experience[0][contractID]" 
								<?php echo $field_opt['contract'][3] ?> id="pswp-exp-contract-0" onchange="getvalue12(this.value)">
								<option value=""><?php esc_html_e( 'Please Select Contract', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $contract_arr as $index => $contract_info ) { ?>
										<option value="<?php echo $contract_info['contractId']; ?>"><?php echo $contract_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--12 Employment-->
					<div class="form-group <?php echo $field_opt['employment'][1] ?>">
						<label for="pswp-exp-employment-0" class="col-sm-3 control-label"><?php esc_html_e( 'Employment', 'prosolwpclient' ) ?>
						<?php echo $field_opt['employment'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off" class="form-control prosolwpclient-chosen-select exp-0 " name="experience[0][employmentID]" 
								<?php echo $field_opt['employment'][3] ?> id="pswp-exp-employment-0" onchange="getvalue12(this.value)">
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
		var globla_country = "<?php echo $dafault_nation_selected ?>";
		var getvalue12 = function(_this,_cnter=""){			
			if(_cnter===''){
				var expCountry = document.getElementById("pswp-exp-country-0").value;
				if(_this.length > 0){
					if(globla_country == expCountry){
						document.getElementById("pswp-exp-beginning-date-0").setAttribute("required","true");
						document.getElementById("pswp-exp-end-date-0").setAttribute("required","true");
						document.getElementById("pswp-exp-description-0").setAttribute("required","true");
					}
				}
				
				var expEndDtChk = document.getElementById("pswp-exp-end-date-0");
				var expBegDtChk = document.getElementById("pswp-exp-beginning-date-0");
				var expDescChk = document.getElementById("pswp-exp-description-0");
				var expjobChk = document.getElementById("pswp-exp-job-0");
				var expEndDtChk = document.getElementById("pswp-exp-end-date-0");
				var expBegDtChk = document.getElementById("pswp-exp-beginning-date-0");
				var expDescChk = document.getElementById("pswp-exp-description-0");
				var expGenDescChk = document.getElementById("pswp-exp-general-description-0");
				var expCompanyChk = document.getElementById("pswp-exp-company-institution-0");
				var expPostcdChk = document.getElementById("pswp-exp-postcode-0");
				var expTownChk = document.getElementById("pswp-exp-town-0");
				var expFeStateChk = document.getElementById("pswp-exp-federal-state-0");
				var expExperianceChk = document.getElementById("pswp-exp-experience-0");
				var expContractChk = document.getElementById("pswp-exp-contract-0");
				var expEmploymentChk = document.getElementById("pswp-exp-employment-0");
				var expCountryChk = document.getElementById("pswp-exp-country-0");
				
				var expjob = document.getElementById("pswp-exp-job-0").value;
				var expEndDt = document.getElementById("pswp-exp-end-date-0").value;
				var expBegDt = document.getElementById("pswp-exp-beginning-date-0").value;
				var expDesc = document.getElementById("pswp-exp-description-0").value;
				var expGenDesc = document.getElementById("pswp-exp-general-description-0").value;
				var expCompany = document.getElementById("pswp-exp-company-institution-0").value;
				var expPostcd = document.getElementById("pswp-exp-postcode-0").value;
				var expTown = document.getElementById("pswp-exp-town-0").value;
				var expFeState = document.getElementById("pswp-exp-federal-state-0").value;
				var expExperiance = document.getElementById("pswp-exp-experience-0").value;
				var expContract = document.getElementById("pswp-exp-contract-0").value;
				var expEmployment = document.getElementById("pswp-exp-employment-0").value;
				var exptab = document.getElementById("exp_tab").querySelector('li');
				
				var divs = document.querySelectorAll(".exp-0[data-required]"), i;
				for (i = 0; i < divs.length; ++i) {					
						divs[i].setAttribute("required","true");				
				}

				if(expCountry == ''){
					expCountry=globla_country;
				}
				
				if(expjob === '' ){
					if(expEndDt === ''){ 
					if( expBegDt === ''){
						if( expDesc === '' ){
							if(expGenDesc === ''){
								if( expCompany === '')
								 if(expPostcd === ''){
									if( expTown === '' ){
										if( expCountry === globla_country ){
											if( expFeState === ''){
												if(expExperiance === ''){
													if( expContract === '' ){
														if (expEmployment === ''){
					for (i = 0; i < divs.length; ++i) {
						divs[i].removeAttribute("required");
						if(divs[i].classList.contains('error')){
							divs[i].classList.remove('error');
							divs[i].parentNode.lastElementChild.remove();
						}
					}	

					if(exptab.classList.contains('error-tab')){
						document.getElementById("exp_tab").querySelector('li').classList.remove("error-tab");
					}
					// document.getElementById("pswp-exp-beginning-date-0").removeAttribute("required");
					// document.getElementById("pswp-exp-end-date-0").removeAttribute("required");
					// document.getElementById("pswp-exp-description-0").removeAttribute("required");
					// document.getElementById("pswp-exp-job-0").removeAttribute("required");
					// document.getElementById("pswp-exp-general-description-0").removeAttribute("required");
					// document.getElementById("pswp-exp-company-institution-0").removeAttribute("required");
					// document.getElementById("pswp-exp-postcode-0").removeAttribute("required");
					// document.getElementById("pswp-exp-town-0").removeAttribute("required");
					// document.getElementById("pswp-exp-country-0").removeAttribute("required");
					// document.getElementById("pswp-exp-federal-state-0").removeAttribute("required");
					// document.getElementById("pswp-exp-experience-0").removeAttribute("required");
					// document.getElementById("pswp-exp-contract-0").removeAttribute("required");
					// document.getElementById("pswp-exp-employment-0").removeAttribute("required");
					
					// if(expEndDtChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-end-date-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-end-date-0").nextElementSibling.nextElementSibling.remove();
					// }
					// if(expBegDtChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-beginning-date-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-beginning-date-0").nextElementSibling.nextElementSibling.remove();
					// }
					// if(expDescChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-description-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-description-0").nextElementSibling.remove();
					// }
					// if(expjobChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-job-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-job-0").nextElementSibling.remove();
					// }
					// if(expGenDescChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-general-description-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-general-description-0").nextElementSibling.remove();
					// }
					// if(expCompanyChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-company-institution-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-company-institution-0").nextElementSibling.remove();
					// }
					// if(expPostcdChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-postcode-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-postcode-0").nextElementSibling.remove();
					// }
					// if(expTownChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-town-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-town-0").nextElementSibling.remove();
					// }
					// if(expCountryChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-country-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-country-0").nextElementSibling.nextElementSibling.remove();
					// }
					// if(expFeStateChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-federal-state-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-federal-state-0").nextElementSibling.remove();
					// }
					// if(expExperianceChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-experience-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-experience-0").nextElementSibling.remove();
					// }
					// if(expContractChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-contract-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-contract-0").nextElementSibling.remove();
					// }
					// if(expEmploymentChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-employment-0").classList.remove("error");
					// 	document.getElementById("pswp-exp-employment-0").nextElementSibling.remove();
					// }
					
				} }}}}}}}}}}}
				// else {
				// 	if(globla_country != expCountry){
				// 		document.getElementById("pswp-exp-beginning-date-0").setAttribute("required","true");
				// 		document.getElementById("pswp-exp-end-date-0").setAttribute("required","true");
				// 		document.getElementById("pswp-exp-description-0").setAttribute("required","true");
				// 	}
					
					
				// }
			}else{
				var expCountry = document.getElementById("pswp-exp-country-"+_cnter).value;
				if(_this.length > 0){
					if(globla_country == expCountry){
						document.getElementById("pswp-exp-beginning-date-"+_cnter).setAttribute("required","true");
						document.getElementById("pswp-exp-end-date-"+_cnter).setAttribute("required","true");
						document.getElementById("pswp-exp-description-"+_cnter).setAttribute("required","true");
					}
				}
				var expEndDtChk = document.getElementById("pswp-exp-end-date-"+_cnter);
				var expBegDtChk = document.getElementById("pswp-exp-beginning-date-"+_cnter);
				var expDescChk = document.getElementById("pswp-exp-description-"+_cnter);
				var expjobChk = document.getElementById("pswp-exp-job-"+_cnter);
				var expEndDtChk = document.getElementById("pswp-exp-end-date-"+_cnter);
				var expBegDtChk = document.getElementById("pswp-exp-beginning-date-"+_cnter);
				var expDescChk = document.getElementById("pswp-exp-description-"+_cnter);
				var expGenDescChk = document.getElementById("pswp-exp-general-description-"+_cnter);
				var expCompanyChk = document.getElementById("pswp-exp-company-institution-"+_cnter);
				var expPostcdChk = document.getElementById("pswp-exp-postcode-"+_cnter);
				var expTownChk = document.getElementById("pswp-exp-town-"+_cnter);
				var expFeStateChk = document.getElementById("pswp-exp-federal-state-"+_cnter);
				var expExperianceChk = document.getElementById("pswp-exp-experience-"+_cnter);
				var expContractChk = document.getElementById("pswp-exp-contract-"+_cnter);
				var expEmploymentChk = document.getElementById("pswp-exp-employment-"+_cnter);
				var expCountryChk = document.getElementById("pswp-exp-country-"+_cnter);

				var expjob = document.getElementById("pswp-exp-job-"+_cnter).value;
				var expEndDt = document.getElementById("pswp-exp-end-date-"+_cnter).value;
				var expBegDt = document.getElementById("pswp-exp-beginning-date-"+_cnter).value;
				var expDesc = document.getElementById("pswp-exp-description-"+_cnter).value;
				var expGenDesc = document.getElementById("pswp-exp-general-description-"+_cnter).value;
				var expCompany = document.getElementById("pswp-exp-company-institution-"+_cnter).value;
				var expPostcd = document.getElementById("pswp-exp-postcode-"+_cnter).value;
				var expTown = document.getElementById("pswp-exp-town-"+_cnter).value;
				var expFeState = document.getElementById("pswp-exp-federal-state-"+_cnter).value;
				var expExperiance = document.getElementById("pswp-exp-experience-"+_cnter).value;
				var expContract = document.getElementById("pswp-exp-contract-"+_cnter).value;
				var expEmployment = document.getElementById("pswp-exp-employment-"+_cnter).value;
				var exptab = document.getElementById("exp_tab").getElementsByTagName('li');
				
				var divsx = document.querySelectorAll(".exp-"+_cnter+"[data-required]"), i;
				for (i = 0; i < divsx.length; ++i) {
					divsx[i].setAttribute("required","true");
				}

				if(expCountry == ''){
					expCountry = globla_country;
				}
				//if(expjob === '' && expEndDt === '' && expBegDt === '' && expDesc === '' && expGenDesc === '' && expCompany === '' && expPostcd === '' && expTown === '' && expCountry === globla_country && expFeState === '' && expExperiance === '' && expContract === '' && expEmployment === ''){
					if(expjob === '' ){
					if(expEndDt === ''){ 
					if( expBegDt === ''){
						if( expDesc === '' ){
							if(expGenDesc === ''){
								if( expCompany === '')
								 if(expPostcd === ''){
									if( expTown === '' ){
										if( expCountry === globla_country ){
											if( expFeState === ''){
												if(expExperiance === ''){
													if( expContract === '' ){
														if (expEmployment === ''){
					for (i = 0; i < divsx.length; ++i) {
						divsx[i].removeAttribute("required");
						if(divsx[i].classList.contains('error')){
							divsx[i].classList.remove('error');
							divsx[i].parentNode.lastElementChild.remove();
						}
					}
					
					for (var i = 0; i < exptab.length; ++i) {
						 if(exptab[i].classList.contains('error-tab')){
						 	exptab[i].classList.remove("error-tab");
						 }
					}
					
					// document.getElementById("pswp-exp-beginning-date-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-end-date-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-description-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-job-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-general-description-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-company-institution-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-postcode-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-town-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-country-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-federal-state-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-experience-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-contract-"+_cnter).removeAttribute("required");
					// document.getElementById("pswp-exp-employment-"+_cnter).removeAttribute("required");
					
					// if(expEndDtChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-end-date-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-end-date-"+_cnter).nextElementSibling.remove();
					// }
					// if(expBegDtChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-beginning-date-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-beginning-date-"+_cnter).nextElementSibling.remove();
					// }
					// if(expDescChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-description-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-description-"+_cnter).nextElementSibling.remove();
					// }
					// if(expjobChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-job-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-job-"+_cnter).nextElementSibling.remove();
					// }
					// if(expGenDescChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-general-description-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-general-description-"+_cnter).nextElementSibling.remove();
					// }
					// if(expCompanyChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-company-institution-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-company-institution-"+_cnter).nextElementSibling.remove();
					// }
					// if(expPostcdChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-postcode-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-postcode-"+_cnter).nextElementSibling.remove();
					// }
					// if(expTownChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-town-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-town-"+_cnter).nextElementSibling.remove();
					// }
					// if(expCountryChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-country-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-country-"+_cnter).nextElementSibling.nextElementSibling.remove();
					// }
					// if(expFeStateChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-federal-state-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-federal-state-"+_cnter).nextElementSibling.remove();
					// }
					// if(expExperianceChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-experience-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-experience-"+_cnter).nextElementSibling.remove();
					// }
					// if(expContractChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-contract-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-contract-"+_cnter).nextElementSibling.remove();
					// }
					// if(expEmploymentChk.classList.contains('error')){
					// 	document.getElementById("pswp-exp-employment-"+_cnter).classList.remove("error");
					// 	document.getElementById("pswp-exp-employment-"+_cnter).nextElementSibling.remove();
					// }
					
				} } } } } } } } } } } }
				// else {
				// 	if(globla_country != expCountry){
				// 		document.getElementById("pswp-exp-beginning-date-"+_cnter).setAttribute("required","true");
				// 		document.getElementById("pswp-exp-end-date-"+_cnter).setAttribute("required","true");
				// 		document.getElementById("pswp-exp-description-"+_cnter).setAttribute("required","true");
				// 	}					
				// }
			}			
		}
	</script>
	<script id="new_exp_template" type="x-tmpl-mustache">
        <div class="new-exp-wrap">
                    <div class="exp-form-content">
            <br/>
            <!--1 Job-->
            <div class="form-group <?php echo $field_opt['job'][1] ?>">
				<label for="pswp-exp-job-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Job', 'prosolwpclient' ) ?>
				<?php echo $field_opt['job'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
					<select autocomplete="off"  class="form-control prosolwpclient-chosen-select exp-{{increment}}" name="experience[{{increment}}][professionID]" 
					<?php echo $field_opt['job'][4] ?> <?php echo $field_opt['job'][3] ?> id="pswp-exp-job-{{increment}}" onchange="getvalue12(this.value,{{increment}})">
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
				<label for="pswp-exp-beginning-date-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Beginning', 'prosolwpclient' ) ?> 
				*
				</label>
                <div class="col-sm-7 error-msg-show">
					<select autocomplete="off"  name="experience[{{increment}}][start]" class="form-control prosolwpclient-chosen-select exp-start exp-{{increment}} experience-{{increment}}-start" 
						required data-required="true" id="pswp-exp-beginning-date-{{increment}}"  data-rule-expstartyearlessorequal="true" onchange="getvalue12(this.value,{{increment}})">
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
				<label for="pswp-exp-end-date-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'The End', 'prosolwpclient' ) ?> 
				*
				</label>
                <div class="col-sm-7 error-msg-show">
					<select autocomplete="off"  name="experience[{{increment}}][end]" class="form-control prosolwpclient-chosen-select exp-end exp-{{increment}} experience-{{increment}}-end" 
						required data-required="true" id="pswp-exp-end-date-{{increment}}"  data-rule-expendyeargraterorequal="true" onchange="getvalue12(this.value,{{increment}})">
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
				<label for="pswp-exp-description-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Description', 'prosolwpclient' ) ?>
				*
				</label>
                <div class="col-sm-9 error-msg-show">
                        <textarea name="experience[{{increment}}][shortNote]" onkeyup="getvalue12(this.value,{{increment}})" class="form-control exp-{{increment}} experience-{{increment}}-shortNote" data-rule-maxlength="400"
							required data-required="true" id="pswp-exp-description-{{increment}}" rows="4" cols="50" placeholder="<?php esc_html_e( 'Briefly describe your activity', 'prosolwpclient' ) ?>"></textarea>
                </div>
            </div>
            <!--5 General Description-->
            <div class="form-group <?php echo $field_opt['gendesc'][1] ?>">
				<label for="pswp-general-description-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'General Description', 'prosolwpclient' ) ?>
				<?php echo $field_opt['gendesc'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
                        <textarea name="experience[{{increment}}][notes]" onkeyup="getvalue12(this.value,{{increment}})" class="form-control exp-{{increment}} experience-{{increment}}-notes" data-rule-maxlength="100"
						<?php echo $field_opt['gendesc'][4] ?> <?php echo $field_opt['gendesc'][3] ?> id="pswp-exp-general-description-{{increment}}" rows="4" cols="50" placeholder="<?php esc_html_e( 'Briefly describe your activity', 'prosolwpclient' ) ?>"></textarea>
                </div>
            </div>
            <!--6 Company / Institution-->
            <div class="form-group <?php echo $field_opt['company'][1] ?>">
				<label for="pswp-company-institution-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Company / Institution', 'prosolwpclient' ) ?>
				<?php echo $field_opt['company'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
                    <input type="text" name="experience[{{increment}}][company]" onkeyup="getvalue12(this.value,{{increment}})" class="form-control exp-{{increment}} experience-{{increment}}-company"
					<?php echo $field_opt['company'][4] ?> 	<?php echo $field_opt['company'][3] ?> id="pswp-exp-company-institution-{{increment}}" data-rule-maxlength="50"
                           placeholder="<?php esc_html_e( 'Company / Institution', 'prosolwpclient' ) ?>">
                </div>
            </div>
            <!--7 Postcode/Town-->
            <div class="form-group <?php echo $field_opt['postcode'][1] ?>">
                <label for="pswp-exp-postcode-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Postcode/Town', 'prosolwpclient' ) ?>
				<?php echo $field_opt['postcode'][2] ?>
				</label>

                <div class="col-sm-4 error-msg-show">
                    <input type="text" name="experience[{{increment}}][zip]" onkeyup="getvalue12(this.value,{{increment}})" class="form-control exp-{{increment}} experience-{{increment}}-zip"
					<?php echo $field_opt['postcode'][4] ?> <?php echo $field_opt['postcode'][3] ?> id="pswp-exp-postcode-{{increment}}" data-rule-minlength="4" data-rule-maxlength="15" data-rule-digits="true"
                           placeholder="<?php esc_html_e( 'Postcode', 'prosolwpclient' ) ?>">
                </div>
                <div class="col-sm-1 control-label"><span>/</span></div>
                <div class="col-sm-4 error-msg-show">
                    <input type="text" name="experience[{{increment}}][city]" onkeyup="getvalue12(this.value,{{increment}})" class="form-control exp-{{increment}} experience-{{increment}}-city" data-rule-maxlength="50" data-rule-lettersonly="true"
					<?php echo $field_opt['postcode'][4] ?> <?php echo $field_opt['postcode'][3] ?> id="pswp-exp-town-{{increment}}" placeholder="<?php esc_html_e( 'Town', 'prosolwpclient' ) ?>">
                </div>
            </div>
            <!--9 Country-->
            <div class="form-group <?php echo $field_opt['country'][1] ?>">
				<label for="pswp-exp-country-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Country', 'prosolwpclient' ) ?>
				<?php echo $field_opt['country'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
					<select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-country-selection exp-{{increment}} exp-{{increment}}-country" name="experience[{{increment}}][countryID]"
					<?php echo $field_opt['country'][4] ?> <?php echo $field_opt['country'][3] ?> data-defnation="<?php echo $dafault_nation_selected ?>" id="pswp-exp-country-{{increment}}" data-track="{{increment}}" onchange="getvalue12(this.value,{{increment}})">
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
            <div class="form-group <?php echo $field_opt['federal'][1] ?>">
				<label for="pswp-exp-federal-state-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Federal State', 'prosolwpclient' ) ?>
				<?php echo $field_opt['federal'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
					<select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-federal-selection-{{increment}} exp-{{increment}} " name="experience[{{increment}}][federalID]"
					<?php echo $field_opt['federal'][4] ?> <?php echo $field_opt['federal'][3] ?> id="pswp-exp-federal-state-{{increment}}" onchange="getvalue12(this.value,{{increment}})">
                        <option value=""><?php esc_html_e( 'Please Select Federal State', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $federal_arr as $index => $federal_info ) { ?>
                                <option value="<?php echo $federal_info['federalId']; ?>"><?php echo $federal_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <!--10 Experience Position-->
            <div class="form-group <?php echo $field_opt['experience'][1] ?>">
				<label for="pswp-exp-experience-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Experience Position', 'prosolwpclient' ) ?>
				<?php echo $field_opt['experience'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
					<select autocomplete="off"  class="form-control exp-position prosolwpclient-chosen-select exp-{{increment}} " name="experience[{{increment}}][experiencePositionID][]" 
					<?php echo $field_opt['experience'][4] ?> <?php echo $field_opt['experience'][3] ?> id="pswp-exp-experience-{{increment}}" onchange="getvalue12(this.value,{{increment}})" multiple >
                        <?php
			foreach ( $experienceposition_arr as $index => $experienceposition_info ) { ?>
                                <option value="<?php echo $experienceposition_info['experiencepositionId']; ?>"><?php echo $experienceposition_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <!--11 Contract-->
            <div class="form-group <?php echo $field_opt['contract'][1] ?>">
				<label for="pswp-exp-contract-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Contract', 'prosolwpclient' ) ?>
				<?php echo $field_opt['contract'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
					<select autocomplete="off"  class="form-control prosolwpclient-chosen-select exp-{{increment}} " name="experience[{{increment}}][contractID]" 
					<?php echo $field_opt['contract'][4] ?> <?php echo $field_opt['contract'][3] ?> id="pswp-exp-contract-{{increment}}" onchange="getvalue12(this.value,{{increment}})">
                        <option value=""><?php esc_html_e( 'Please Select Contract', 'prosolwpclient' ) ?></option>
                        <?php
			foreach ( $contract_arr as $index => $contract_info ) { ?>
                                <option value="<?php echo $contract_info['contractId']; ?>"><?php echo $contract_info['name']; ?></option>
                            <?php } ?>
                    </select>
                </div>
            </div>
            <!--12 Employment-->
            <div class="form-group <?php echo $field_opt['employment'][1] ?>">
				<label for="pswp-exp-employment-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Employment', 'prosolwpclient' ) ?>
				<?php echo $field_opt['employment'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
					<select autocomplete="off"  class="form-control prosolwpclient-chosen-select exp-{{increment}} " name="experience[{{increment}}][employmentID]" 
					<?php echo $field_opt['employment'][4] ?> <?php echo $field_opt['employment'][3] ?> id="pswp-exp-employment-{{increment}}" onchange="getvalue12(this.value,{{increment}})">
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
			
		</div>
	</div>
</fieldset>