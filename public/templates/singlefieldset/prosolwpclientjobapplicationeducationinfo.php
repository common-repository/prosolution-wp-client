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
	
	$opt = get_option('prosolwpclient_applicationform');
	$sect = $issite.'education';
	$fields_section=array('postcode','country','level','description');
	$field_opt=array();
	foreach($fields_section as $field){
		$field_opt[$field][1]=$opt[$sect.'_'.$field.'_act'] ? '' : 'hidden';
		$field_opt[$field][2]=$opt[$sect.'_'.$field.'_man'] ? '*' : '';
		$field_opt[$field][3]=$opt[$sect.'_'.$field.'_man'] ? 'data-required="true"' : '';
		$field_opt[$field][4]=$opt[$sect.'_'.$field.'_man'] ? 'required' : '';
	}
?>

<fieldset class="application-info-education">
	<legend><?php esc_html_e( $opt[$sect]) ?></legend>
	<div class="">
		<ul class="nav nav-tabs incremental-nav-tabs edu-tabs" id="edu_tab" role="tablist">
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
						<label for="pswp-group-0" class="col-sm-3 control-label"><?php esc_html_e( 'Group', 'prosolwpclient' ) ?> 
							*
						</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off"  class="form-control group-selection prosolwpclient-chosen-select edu-0 education-0-typeID" name="education[0][typeID]" id="pswp-group-0" onchange="getvalue(this.value)" data-track="0" 
							data-rule-required="required">
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
						<label for="pswp-training-practice-0" class="col-sm-3 control-label"><?php esc_html_e( 'Training / Practice', 'prosolwpclient' ) ?> 
							*
						</label>
						<div class="col-sm-9 error-msg-show training-practice-section-0">
							<select autocomplete="off"  class="form-control training-practice prosolwpclient-chosen-select edu-0 education-0-detailID" name="education[0][detailID]"
								data-required="required" id="pswp-training-practice-0" onchange="getvalue(this.value)" >
								<option value=""><?php esc_html_e( 'Please Select Training / Practice', 'prosolwpclient' ) ?></option>

							</select>
						</div>
					</div>
					<!--3 Beginning-->
					<div class="form-group">
						<label for="pswp-beginning-date-0" class="col-sm-3 control-label"><?php esc_html_e( 'Beginning', 'prosolwpclient' ) ?> 
							*
						</label>
						<div class="col-sm-7 error-msg-show">
							<select autocomplete="off"  name="education[0][start]" class="form-control prosolwpclient-chosen-select edu-start edu-0 education-0-start" 
								data-required="required" id="pswp-beginning-date-0" onchange="getvalue(this.value)"  data-rule-startyearlessorequal="true">
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
						<label for="pswp-end-date-0" class="col-sm-3 control-label"><?php esc_html_e( 'The End', 'prosolwpclient' ) ?> 
							*									
						</label>
						<div class="col-sm-7 error-msg-show">
							<select autocomplete="off" name="education[0][end]" class="form-control prosolwpclient-chosen-select edu-end edu-0 education-0-end" 
								data-required="required" id="pswp-end-date-0"  onchange="getvalue(this.value)" data-rule-endyeargraterorequal="true">
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
					<div class="form-group <?php echo $field_opt['postcode'][1] ?>">
						<label for="pswp-edu-postcode-0" class="col-sm-3 control-label"><?php esc_html_e( 'Postcode/Town', 'prosolwpclient' ) ?>
							<?php echo $field_opt['postcode'][2] ?>
						</label>

						<div class="col-sm-4 error-msg-show">
							<input type="text" name="education[0][zip]" class="form-control edu-0 education-0-zip"
								<?php echo $field_opt['postcode'][3] ?> id="pswp-edu-postcode-0" onkeyup="getvalue(this.value)" data-rule-minlength="4" data-rule-maxlength="15" data-rule-digits="true"
								placeholder="<?php esc_html_e( 'Postcode', 'prosolwpclient' ) ?>">
						</div>
						<div class="col-sm-1 control-label"><span>/</span></div>
						<div class="col-sm-4 error-msg-show">
							<input type="text" name="education[0][city]" class="form-control edu-0 education-0-city"
								<?php echo $field_opt['postcode'][3] ?> id="pswp-edu-town-0" onkeyup="getvalue(this.value)" data-rule-maxlength="50" data-rule-lettersonly="true" placeholder="<?php esc_html_e( 'Town', 'prosolwpclient' ) ?>">
						</div>
					</div>
					<!--7 Country-->
					<div class="form-group <?php echo $field_opt['country'][1] ?>">
						<label for="pswp-edu-country-0" class="col-sm-3 control-label"><?php esc_html_e( 'Country', 'prosolwpclient' ) ?>
							<?php echo $field_opt['country'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-country-selection edu-0 " name="education[0][countryId]" 
								<?php echo $field_opt['country'][3] ?> data-defnation="<?php echo $dafault_nation_selected ?>" id="pswp-edu-country-0" onchange="getvalue(this.value)" data-track="0">
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
						<label for="pswp-edu-federal-state-0" class="col-sm-3 control-label"><?php esc_html_e( 'Federal State', 'prosolwpclient' ) ?>
							*						
						</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-federal-selection-0 edu-0 " name="education[0][federalId]"
								data-required="required" id="pswp-edu-federal-state-0" onchange="getvalue(this.value)">
								<option value=""><?php esc_html_e( 'Please Select Federal State', 'prosolwpclient' ) ?></option>
								<?php
									foreach ( $federal_arr as $index => $federal_info ) { ?>
										<option value="<?php echo $federal_info['federalId']; ?>"><?php echo $federal_info['name']; ?></option>
									<?php } ?>
							</select>
						</div>
					</div>
					<!--8 Level of Education-->
					<div class="form-group <?php echo $field_opt['level'][1] ?>">
						<label for="pswp-education-0" class="col-sm-3 control-label"><?php esc_html_e( 'Level of Education', 'prosolwpclient' ) ?>
							<?php echo $field_opt['level'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
							<select autocomplete="off"  class="form-control prosolwpclient-chosen-select edu-0 " name="education[0][iscedID]" 
								<?php echo $field_opt['level'][3] ?> id="pswp-education-0" onchange="getvalue(this.value)">
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
						<label for="pswp-activity-0" class="col-sm-3 control-label"><?php esc_html_e( 'Field of Activity', 'prosolwpclient' ) ?>
							*
						</label>
						<div class="col-sm-9 activity-section-wrap-0 error-msg-show">
							<input type="hidden" name="education[0][operationAreaID]" id="pswp-edu-foact-0" class="edu-0"
							data-required="required">

							<ul id="activity_selection_wrapper" class="edu-0-foac" style="list-style-type: none;" onclick="getspanActivity()" >
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
						<label for="pswp-business-0" class="col-sm-3 control-label"><?php esc_html_e( 'Business', 'prosolwpclient' ) ?>
							*
						</label>
						<div class="col-sm-9 business-section-wrap-0 error-msg-show">
							<input type="hidden" name="education[0][naceID]" id="pswp-edu-business-0" class="edu-0"
							data-required="required">

							<ul id="business_selection_wrapper" class="edu-0-business" style="list-style-type: none;" onclick="getspanBusiness()">
								<!-- mustache template -->
							</ul>
							<button type="button" class="btn btn-default btn-md business-btn-modal" data-bs-toggle="modal" data-business-modaltrack="0"
									data-bs-target="#businessModal">
									<?php esc_html_e('Choose', 'prosolwpclient' ) ?>
							</button>
						</div>
					</div>
					<!--11 Description-->
					<div class="form-group <?php echo $field_opt['description'][1] ?>">
						<label for="pswp-description-0" class="col-sm-3 control-label"><?php esc_html_e( 'Description', 'prosolwpclient' ) ?> 
							<?php echo $field_opt['description'][2] ?>
						</label>
						<div class="col-sm-9 error-msg-show">
                        <textarea name="education[0][notes]" class="form-control education-0-notes edu-0 " data-rule-maxlength="400"
							<?php echo $field_opt['description'][3] ?> id="pswp-description-0" onkeyup="getvalue(this.value)" rows="4" cols="50" placeholder="<?php esc_html_e( 'Briefly describe your activity', 'prosolwpclient' ) ?>"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
	var countryValue = "<?php echo $dafault_nation_selected ?>";

		var getvalue = function(_this,_cnter=""){
			if(_cnter===''){			
				if(_this.length > 0){
					var eduCountry = document.getElementById("pswp-edu-country-0").value;
					if(eduCountry == countryValue){
						document.getElementById("pswp-group-0").setAttribute("required","true");
						document.getElementById("pswp-training-practice-0").setAttribute("required","true");
						document.getElementById("pswp-beginning-date-0").setAttribute("required","true");
						document.getElementById("pswp-end-date-0").setAttribute("required","true");
						//document.getElementById("pswp-description-0").setAttribute("required","true");
					}

				}
				var edugroup = document.getElementById("pswp-group-0").value;
				var edutraining = document.getElementById("pswp-training-practice-0").value;
				var eduBegDt = document.getElementById("pswp-beginning-date-0").value;
				var eduEndDt = document.getElementById("pswp-end-date-0").value;
				var eduPostcode = document.getElementById("pswp-edu-postcode-0").value;
				var eduTown = document.getElementById("pswp-edu-town-0").value;
				var eduCountry = document.getElementById("pswp-edu-country-0").value;
				var eduFederalState = document.getElementById("pswp-edu-federal-state-0").value;
				var edulevelEducation = document.getElementById("pswp-education-0").value;
				var eduDescription = document.getElementById("pswp-description-0").value;
				var eduActivity = document.getElementById('activity_selection_wrapper').getElementsByTagName('li').length;
				var eduBusiness = document.getElementById('business_selection_wrapper').getElementsByTagName('li').length;

				var edugroupChk = document.getElementById("pswp-group-0");
				var edutrainingChk = document.getElementById("pswp-training-practice-0");
				var eduBegDtChk = document.getElementById("pswp-beginning-date-0");
				var eduEndDtChk = document.getElementById("pswp-end-date-0");
				var eduDescriptionChk = document.getElementById("pswp-description-0");
				var edutab = document.getElementById("edu_tab").querySelector('li');

				var divs = document.querySelectorAll(".edu-0[data-required]"), i;
				for (i = 0; i < divs.length; ++i) {	
					if(eduActivity > 0 && divs[i].id=='pswp-edu-foact-0' ){
						// don't set required
					} else if(divs[i].id=='pswp-edu-business-0' && eduBusiness > 0){
						// don't set required
					} else {
						divs[i].setAttribute("required","true");
					}					
				}

				if(edugroup == 0){
					edutraining='';
				}
				if(eduCountry == 0){
					eduCountry = countryValue;
				}
				
				if(edugroup === '' && edutraining === '' && eduBegDt === '' && eduEndDt === '' && eduPostcode === '' && eduTown === '' && eduCountry === countryValue  && eduFederalState === '' && edulevelEducation === '' && eduDescription === '' &&  eduActivity === 0 && eduBusiness === 0){
					for (i = 0; i < divs.length; ++i) {
						divs[i].removeAttribute("required");
						if(divs[i].classList.contains('error')){
							divs[i].classList.remove('error');
							divs[i].parentNode.lastElementChild.remove();
							// if(divs[i].id == "pswp-group-0" || divs[i].id == "pswp-training-practice-0"){
							// 	divs[i].parentNode.lastElementChild.remove();
							// } else if(divs[i].id == "pswp-beginning-date-0" || divs[i].id == "pswp-end-date-0" || 
							// 		  divs[i].id == "pswp-edu-federal-state-0" || divs[i].id == "pswp-education-0"){
										  
							// 	divs[i].nextElementSibling.nextElementSibling.remove();
							// } else if(divs[i].id == "pswp-edu-foact-0" || divs[i].id == "pswp-edu-business-0" ){
							// 	divs[i].nextElementSibling.nextElementSibling.nextElementSibling.remove();
							// } else {
							// 	divs[i].nextElementSibling.remove();
							// }
						}
					}	

					if(edutab.classList.contains('error-tab')){
						document.getElementById("edu_tab").querySelector('li').classList.remove("error-tab");
					}
					// document.getElementById("pswp-group-0").removeAttribute("required");
					// document.getElementById("pswp-training-practice-0").removeAttribute("required");
					// document.getElementById("pswp-beginning-date-0").removeAttribute("required");
					// document.getElementById("pswp-end-date-0").removeAttribute("required");
					// //document.getElementById("pswp-description-0").removeAttribute("required");

					// if(edugroupChk.classList.contains('error')){
					// 	document.getElementById("pswp-group-0").classList.remove("error");
					// 	document.getElementById("pswp-group-0").parentNode.lastElementChild.remove();
					// }

					// if(edutrainingChk.classList.contains('error')){
					// 	document.getElementById("pswp-training-practice-0").classList.remove("error");
					// 	document.getElementById("pswp-training-practice-0").parentNode.lastElementChild.remove();

					// }

					// if(eduBegDtChk.classList.contains('error')){
					// 	document.getElementById("pswp-beginning-date-0").classList.remove("error");
					// 	document.getElementById("pswp-beginning-date-0").nextElementSibling.nextElementSibling.remove();
					// }

					// if(eduEndDtChk.classList.contains('error')){
					// 	document.getElementById("pswp-end-date-0").classList.remove("error");
					// 	document.getElementById("pswp-end-date-0").nextElementSibling.nextElementSibling.remove();
					// }
					
					// if(eduDescriptionChk.classList.contains('error')){
					// 	document.getElementById("pswp-description-0").classList.remove("error");
					// 	document.getElementById("pswp-description-0").nextElementSibling.remove();
					// }
				}
				// else{
				// 	if(eduCountry != countryValue){
				// 		document.getElementById("pswp-group-0").setAttribute("required","true");
				// 		document.getElementById("pswp-training-practice-0").setAttribute("required","true");
				// 		document.getElementById("pswp-beginning-date-0").setAttribute("required","true");
				// 		document.getElementById("pswp-end-date-0").setAttribute("required","true");
				// 		//document.getElementById("pswp-description-0").setAttribute("required","true");
				// 	}
				// }
			}
			else{
				if(_this.length > 0){
					var eduCountry = document.getElementById("pswp-edu-country-"+_cnter).value;
					if(eduCountry == countryValue){
						document.getElementById("pswp-group-"+_cnter).setAttribute("required","true");
						document.getElementById("pswp-training-practice-"+_cnter).setAttribute("required","true");
						document.getElementById("pswp-beginning-date-"+_cnter).setAttribute("required","true");
						document.getElementById("pswp-end-date-"+_cnter).setAttribute("required","true");
						//document.getElementById("pswp-description-"+_cnter).setAttribute("required","true");
					}
				}

				var edugroup = document.getElementById("pswp-group-"+_cnter).value;
				var edutraining = document.getElementById("pswp-training-practice-"+_cnter).value;
				var eduBegDt = document.getElementById("pswp-beginning-date-"+_cnter).value;
				var eduEndDt = document.getElementById("pswp-end-date-"+_cnter).value;
				var eduPostcode = document.getElementById("pswp-edu-postcode-"+_cnter).value;
				var eduTown = document.getElementById("pswp-edu-town-"+_cnter).value;
				var eduCountry = document.getElementById("pswp-edu-country-"+_cnter).value;
				var eduFederalState = document.getElementById("pswp-edu-federal-state-"+_cnter).value;
				var edulevelEducation = document.getElementById("pswp-education-"+_cnter).value;
				var eduDescription = document.getElementById("pswp-description-"+_cnter).value;
				var parentactivity = document.querySelector('.acti_selec_wrap-'+_cnter);
				if (parentactivity.querySelector('li') !== null) {
				  eduActivity = document.querySelector('.acti_selec_wrap-'+_cnter).getElementsByTagName('li').length;
				}
				var parentbusiness = document.querySelector('.busi_sele_wrap-'+_cnter);

				if (parentbusiness.querySelector('li') !== null) {
				  eduBusiness = document.querySelector('.busi_sele_wrap-'+_cnter).getElementsByTagName('li').length;

				}
				if(eduCountry == 0){
					eduCountry = countryValue;
				}
				var divsx = document.querySelectorAll(".edu-"+_cnter+"[data-required]"), i;
				for (i = 0; i < divsx.length; ++i) {
					if(eduActivity > 0 && divsx[i].id=='pswp-edu-foact-'+_cnter){
						// don't set required
					} else if(divsx[i].id=='pswp-edu-business-'+_cnter && eduBusiness > 0){
						// don't set required
					} else {
						divsx[i].setAttribute("required","true");
					}
				}

				var edugroupChk = document.getElementById("pswp-group-"+_cnter);
				var edutrainingChk = document.getElementById("pswp-training-practice-"+_cnter);
				var eduBegDtChk = document.getElementById("pswp-beginning-date-"+_cnter);
				var eduEndDtChk = document.getElementById("pswp-end-date-"+_cnter);
				var eduDescriptionChk = document.getElementById("pswp-description-"+_cnter);
				var edutab = document.getElementById("edu_tab").getElementsByTagName('li');
				if(edugroup == 0){
					edutraining='';
				}
				
				//if(edugroup === '' && edutraining === '' && eduBegDt === '' && eduEndDt === '' && eduPostcode === '' && eduTown === '' && eduCountry === countryValue && eduFederalState === '' && edulevelEducation === '' && eduDescription === '' &&  eduActivity === undefined && eduBusiness === undefined){
					if(edugroup === ""){
						if( edutraining === ""){
							if( eduBegDt === ''){
								if( eduEndDt === ''){
									if( eduPostcode === ''){
										if( eduTown === ''){
											if( eduCountry === ''){
												if( eduFederalState === ''){
													if( edulevelEducation === '' ){
														if( eduDescription === ''){
															if( eduActivity === undefined ){
																if(eduBusiness=== undefined) {
					for (i = 0; i < divsx.length; ++i) {
						divsx[i].removeAttribute("required");
						if(divsx[i].classList.contains('error')){
							divsx[i].classList.remove('error');
							divsx[i].parentNode.lastElementChild.remove();
							// if(divsx[i].id == "pswp-group-"+_cnter || divsx[i].id == "pswp-training-practice-"+_cnter){
							// 	divsx[i].parentNode.lastElementChild.remove();
							// } else if(divsx[i].id == "pswp-beginning-date-"+_cnter || divsx[i].id == "pswp-end-date-"+_cnter || 
							// 		  divsx[i].id == "pswp-edu-federal-state-"+_cnter || divsx[i].id == "pswp-education-"+_cnter){
										  
							// 	divsx[i].nextElementSibling.nextElementSibling.remove();
							// } else if(divsx[i].id == "pswp-edu-foact-"+_cnter || divs[i].id == "pswp-edu-business-"+_cnter ){
							// 	divsx[i].nextElementSibling.nextElementSibling.nextElementSibling.remove();
							// } else {
							// 	divsx[i].nextElementSibling.remove();
							// }
						}
					}

					for (var i = 0; i < edutab.length; ++i) {
						if(edutab[i].classList.contains('error-tab')){
							edutab[i].classList.remove("error-tab");
						}
					}	

						// document.getElementById("pswp-group-"+_cnter).removeAttribute("required");
						// document.getElementById("pswp-training-practice-"+_cnter).removeAttribute("required");
						// document.getElementById("pswp-beginning-date-"+_cnter).removeAttribute("required");
						// document.getElementById("pswp-end-date-"+_cnter).removeAttribute("required");
						// document.getElementById("pswp-description-"+_cnter).removeAttribute("required");

						// if(edugroupChk.classList.contains('error')){
						// 	document.getElementById("pswp-group-"+_cnter).classList.remove("error");
						// 	document.getElementById("pswp-group-"+_cnter).parentNode.lastElementChild.remove();
						// }

						// if(edutrainingChk.classList.contains('error')){
						// 	document.getElementById("pswp-training-practice-"+_cnter).classList.remove("error");
						// 	document.getElementById("pswp-training-practice-"+_cnter).parentNode.lastElementChild.remove();
						// }

						// if(eduBegDtChk.classList.contains('error')){
						// 	document.getElementById("pswp-beginning-date-"+_cnter).classList.remove("error");
						// 	document.getElementById("pswp-beginning-date-"+_cnter).nextElementSibling.remove();
						// }

						// if(eduEndDtChk.classList.contains('error')){
						// 	document.getElementById("pswp-end-date-"+_cnter).classList.remove("error");
						// 	document.getElementById("pswp-end-date-"+_cnter).nextElementSibling.remove();
						// }
						
						// if(eduDescriptionChk.classList.contains('error')){
						// 	document.getElementById("pswp-description-"+_cnter).classList.remove("error");
						// 	document.getElementById("pswp-description-"+_cnter).nextElementSibling.remove();
						// }
				} } } } } } } } } } } }
				// else{
				// 	if(eduCountry != countryValue){
				// 		document.getElementById("pswp-group-"+_cnter).setAttribute("required","true");
				// 		document.getElementById("pswp-training-practice-"+_cnter).setAttribute("required","true");
				// 		document.getElementById("pswp-beginning-date-"+_cnter).setAttribute("required","true");
				// 		document.getElementById("pswp-end-date-"+_cnter).setAttribute("required","true");
				// 		//document.getElementById("pswp-description-"+_cnter).setAttribute("required","true");
				// 	}
				// }
			}
		}
		var getspanActivity	= function(_cnter=""){
			var eduActivity = document.getElementById('activity_selection_wrapper').getElementsByTagName('li').length;
			if(_cnter===''){
				if(eduActivity <= 1){
					var edugroup = document.getElementById("pswp-group-0").value;
					var edutraining = document.getElementById("pswp-training-practice-0").value;
					var eduBegDt = document.getElementById("pswp-beginning-date-0").value;
					var eduEndDt = document.getElementById("pswp-end-date-0").value;
					var eduPostcode = document.getElementById("pswp-edu-postcode-0").value;
					var eduTown = document.getElementById("pswp-edu-town-0").value;
					var eduCountry = document.getElementById("pswp-edu-country-0").value;
					var eduFederalState = document.getElementById("pswp-edu-federal-state-0").value;
					var edulevelEducation = document.getElementById("pswp-education-0").value;
					var eduDescription = document.getElementById("pswp-description-0").value;
					var eduActivity = document.getElementById('activity_selection_wrapper').getElementsByTagName('li').length;
					var eduBusiness = document.getElementById('business_selection_wrapper').getElementsByTagName('li').length;

					var edugroupChk = document.getElementById("pswp-group-0");
					var edutrainingChk = document.getElementById("pswp-training-practice-0");
					var eduBegDtChk = document.getElementById("pswp-beginning-date-0");
					var eduEndDtChk = document.getElementById("pswp-end-date-0");
					var eduDescriptionChk = document.getElementById("pswp-description-0");
					var edutab = document.getElementById("edu_tab").querySelector('li');
					if(edugroup == 0){
						edutraining='';
					}

					if(edugroup === ""){
						if( edutraining === ""){
							if( eduBegDt === ''){
								if( eduEndDt === ''){
									if( eduPostcode === ''){
										if( eduTown === ''){
											if( eduCountry === ''){
												if( eduFederalState === ''){
													if( edulevelEducation === '' ){
														if( eduDescription === ''){
															if( eduActivity === 1 ){
																if( eduBusiness === 0){
						var divs = document.querySelectorAll(".edu-0[data-required]"), i;
						for (i = 0; i < divs.length; ++i) {
							divs[i].removeAttribute("required");
							if(divs[i].classList.contains('error')){
								divs[i].classList.remove('error');
								divs[i].parentNode.lastElementChild.remove();
							}
						}

						if(edutab.classList.contains('error-tab')){
							document.getElementById("edu_tab").querySelector('li').classList.remove("error-tab");
						}
						
						// document.getElementById("pswp-group-0").removeAttribute("required");
						// document.getElementById("pswp-training-practice-0").removeAttribute("required");
						// document.getElementById("pswp-beginning-date-0").removeAttribute("required");
						// document.getElementById("pswp-end-date-0").removeAttribute("required");

						// if(edugroupChk.classList.contains('error')){
						// 	document.getElementById("pswp-group-0").classList.remove("error");
						// 	document.getElementById("pswp-group-0").parentNode.lastElementChild.remove();
						// }

						// if(edutrainingChk.classList.contains('error')){
						// 	document.getElementById("pswp-training-practice-0").classList.remove("error");
						// 	document.getElementById("pswp-training-practice-0").parentNode.lastElementChild.remove();

						// }

						// if(eduBegDtChk.classList.contains('error')){
						// 	document.getElementById("pswp-beginning-date-0").classList.remove("error");
						// 	document.getElementById("pswp-beginning-date-0").nextElementSibling.remove();
						// }


						// if(eduEndDtChk.classList.contains('error')){
						// 	document.getElementById("pswp-end-date-0").classList.remove("error");
						// 	document.getElementById("pswp-end-date-0").nextElementSibling.remove();

						// }
												
						// if(eduDescriptionChk.classList.contains('error')){
						// 	document.getElementById("pswp-description-0").classList.remove("error");
						// 	document.getElementById("pswp-description-0").nextElementSibling.remove();
						// }
					} } } } } } } } } }  } }

				}
			}else{
				if(eduActivity <= 1){

					var edugroup = document.getElementById("pswp-group-"+_cnter).value;
					var edutraining = document.getElementById("pswp-training-practice-"+_cnter).value;
					var eduBegDt = document.getElementById("pswp-beginning-date-"+_cnter).value;
					var eduEndDt = document.getElementById("pswp-end-date-"+_cnter).value;
					var eduPostcode = document.getElementById("pswp-edu-postcode-"+_cnter).value;
					var eduTown = document.getElementById("pswp-edu-town-"+_cnter).value;
					var eduCountry = document.getElementById("pswp-edu-country-"+_cnter).value;
					var eduFederalState = document.getElementById("pswp-edu-federal-state-"+_cnter).value;
					var edulevelEducation = document.getElementById("pswp-education-"+_cnter).value;
					var eduDescription = document.getElementById("pswp-description-"+_cnter).value;

					var parentactivity = document.querySelector('.acti_selec_wrap-'+_cnter);
					eduActivity= parentactivity.getElementsByTagName("li").length;
					var parentbusiness = document.querySelector('.busi_sele_wrap-'+_cnter);
					  eduBusiness = parentbusiness.getElementsByTagName('li').length;

					var edugroupChk = document.getElementById("pswp-group-"+_cnter);
					var edutrainingChk = document.getElementById("pswp-training-practice-"+_cnter);
					var eduBegDtChk = document.getElementById("pswp-beginning-date-"+_cnter);
					var eduEndDtChk = document.getElementById("pswp-end-date-"+_cnter);
					var eduDescriptionChk = document.getElementById("pswp-description-"+_cnter);
					var edutab = document.getElementById("edu_tab").getElementsByTagName('li');
					if(edugroup == 0){
						edutraining='';
					}					
					
					if(edugroup === ""){
						if( edutraining === ""){
							if( eduBegDt === ''){
								if( eduEndDt === ''){
									if( eduPostcode === ''){
										if( eduTown === ''){
											if( eduCountry === ''){
												if( eduFederalState === ''){
													if( edulevelEducation === '' ){
														if( eduDescription === ''){
															if( eduActivity === 1 ){
																if(eduBusiness===0) {
						var divsx = document.querySelectorAll(".edu-"+_cnter+"[data-required]"), i;
						for (i = 0; i < divsx.length; ++i) {
							divsx[i].removeAttribute("required");
							if(divsx[i].classList.contains('error')){
								divsx[i].classList.remove('error');
								divsx[i].parentNode.lastElementChild.remove();
							}
						}

						for (var i = 0; i < edutab.length; ++i) {
							if(edutab[i].classList.contains('error-tab')){
							edutab[i].classList.remove("error-tab");
							}
						}
							// document.getElementById("pswp-group-"+_cnter).removeAttribute("required");
							// document.getElementById("pswp-training-practice-"+_cnter).removeAttribute("required");
							// document.getElementById("pswp-beginning-date-"+_cnter).removeAttribute("required");
							// document.getElementById("pswp-end-date-"+_cnter).removeAttribute("required");
							

							// if(edugroupChk.classList.contains('error')){
							// 	document.getElementById("pswp-group-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-group-"+_cnter).parentNode.lastElementChild.remove();
							// }

							// if(edutrainingChk.classList.contains('error')){
							// 	document.getElementById("pswp-training-practice-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-training-practice-"+_cnter).parentNode.lastElementChild.remove();
							// }

							// if(eduBegDtChk.classList.contains('error')){
							// 	document.getElementById("pswp-beginning-date-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-beginning-date-"+_cnter).nextElementSibling.remove();
							// }

							// if(eduEndDtChk.classList.contains('error')){
							// 	document.getElementById("pswp-end-date-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-end-date-"+_cnter).nextElementSibling.remove();
							// }
							
							// if(eduDescriptionChk.classList.contains('error')){
							// 	document.getElementById("pswp-description-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-description-"+_cnter).nextElementSibling.remove();
							// }
					} } }  } } } } } } } } }
				}
			}
		}
		var getspanBusiness = function(_cnter=""){
			var eduBusiness = document.getElementById('business_selection_wrapper').getElementsByTagName('li').length;
			if(_cnter===''){
				if(eduBusiness <= 1){
					var edugroup = document.getElementById("pswp-group-0").value;
					var edutraining = document.getElementById("pswp-training-practice-0").value;
					var eduBegDt = document.getElementById("pswp-beginning-date-0").value;
					var eduEndDt = document.getElementById("pswp-end-date-0").value;
					var eduPostcode = document.getElementById("pswp-edu-postcode-0").value;
					var eduTown = document.getElementById("pswp-edu-town-0").value;
					var eduCountry = document.getElementById("pswp-edu-country-0").value;
					var eduFederalState = document.getElementById("pswp-edu-federal-state-0").value;
					var edulevelEducation = document.getElementById("pswp-education-0").value;
					var eduDescription = document.getElementById("pswp-description-0").value;
					var eduActivity = document.getElementById('activity_selection_wrapper').getElementsByTagName('li').length;
					var eduBusiness = document.getElementById('business_selection_wrapper').getElementsByTagName('li').length;

					var edugroupChk = document.getElementById("pswp-group-0");
					var edutrainingChk = document.getElementById("pswp-training-practice-0");
					var eduBegDtChk = document.getElementById("pswp-beginning-date-0");
					var eduEndDtChk = document.getElementById("pswp-end-date-0");
					var eduDescriptionChk = document.getElementById("pswp-description-0");
					var edutab = document.getElementById("edu_tab").querySelector('li');

					if(edugroup == 0){
						edutraining='';
					}

					if(edugroup === ""){
						if( edutraining === ""){
							if( eduBegDt === ''){
								if( eduEndDt === ''){
									if( eduPostcode === ''){
										if( eduTown === ''){
											if( eduCountry === ''){
												if( eduFederalState === ''){
													if( edulevelEducation === '' ){
														if( eduDescription === ''){
															if( eduActivity === 0 ){
																if(eduBusiness===1) {
						var divs = document.querySelectorAll(".edu-0[data-required]"), i;
						for (i = 0; i < divs.length; ++i) {
							divs[i].removeAttribute("required");
							if(divs[i].classList.contains('error')){
								divs[i].classList.remove('error');
								divs[i].parentNode.lastElementChild.remove();
							}
						}

						if(edutab.classList.contains('error-tab')){
							document.getElementById("edu_tab").querySelector('li').classList.remove("error-tab");
						}

						// document.getElementById("pswp-group-0").removeAttribute("required");
						// document.getElementById("pswp-training-practice-0").removeAttribute("required");
						// document.getElementById("pswp-beginning-date-0").removeAttribute("required");
						// document.getElementById("pswp-end-date-0").removeAttribute("required");

						// if(edugroupChk.classList.contains('error')){
						// 	document.getElementById("pswp-group-0").classList.remove("error");
						// 	document.getElementById("pswp-group-0").parentNode.lastElementChild.remove();
						// }

						// if(edutrainingChk.classList.contains('error')){
						// 	document.getElementById("pswp-training-practice-0").classList.remove("error");
						// 	document.getElementById("pswp-training-practice-0").parentNode.lastElementChild.remove();

						// }

						// if(eduBegDtChk.classList.contains('error')){
						// 	document.getElementById("pswp-beginning-date-0").classList.remove("error");
						// 	document.getElementById("pswp-beginning-date-0").nextElementSibling.remove();
						// }

						// if(eduEndDtChk.classList.contains('error')){
						// 	document.getElementById("pswp-end-date-0").classList.remove("error");
						// 	document.getElementById("pswp-end-date-0").nextElementSibling.remove();

						// }
						
						// if(eduDescriptionChk.classList.contains('error')){
						// 	document.getElementById("pswp-description-0").classList.remove("error");
						// 	document.getElementById("pswp-description-0").nextElementSibling.remove();
						// }
					} } } } } } } } } } } }

				}
			}else{
				if(eduBusiness <= 1){

					var edugroup = document.getElementById("pswp-group-"+_cnter).value;
					var edutraining = document.getElementById("pswp-training-practice-"+_cnter).value;
					var eduBegDt = document.getElementById("pswp-beginning-date-"+_cnter).value;
					var eduEndDt = document.getElementById("pswp-end-date-"+_cnter).value;
					var eduPostcode = document.getElementById("pswp-edu-postcode-"+_cnter).value;
					var eduTown = document.getElementById("pswp-edu-town-"+_cnter).value;
					var eduCountry = document.getElementById("pswp-edu-country-"+_cnter).value;
					var eduFederalState = document.getElementById("pswp-edu-federal-state-"+_cnter).value;
					var edulevelEducation = document.getElementById("pswp-education-"+_cnter).value;
					var eduDescription = document.getElementById("pswp-description-"+_cnter).value;

					var parentactivity = document.querySelector('.acti_selec_wrap-'+_cnter);
					eduActivity= parentactivity.getElementsByTagName("li").length;
					var parentbusiness = document.querySelector('.busi_sele_wrap-'+_cnter);
					  eduBusiness = parentbusiness.getElementsByTagName('li').length;

					var edugroupChk = document.getElementById("pswp-group-"+_cnter);
					var edutrainingChk = document.getElementById("pswp-training-practice-"+_cnter);
					var eduBegDtChk = document.getElementById("pswp-beginning-date-"+_cnter);
					var eduEndDtChk = document.getElementById("pswp-end-date-"+_cnter);
					var eduDescriptionChk = document.getElementById("pswp-description-"+_cnter);
					var edutab = document.getElementById("edu_tab").getElementsByTagName('li');
					if(edugroup == 0){
						edutraining='';
					}

					if(edugroup === ""){
						if( edutraining === ""){
							if( eduBegDt === ''){
								if( eduEndDt === ''){
									if( eduPostcode === ''){
										if( eduTown === ''){
											if( eduCountry === ''){
												if( eduFederalState === ''){
													if( edulevelEducation === '' ){
														if( eduDescription === ''){
															if( eduActivity === 0 ){
																if(eduBusiness===1) {
						var divsx = document.querySelectorAll(".edu-"+_cnter+"[data-required]"), i;
						for (i = 0; i < divsx.length; ++i) {
							divsx[i].removeAttribute("required");
							if(divsx[i].classList.contains('error')){
								divsx[i].classList.remove('error');
								divsx[i].parentNode.lastElementChild.remove();
							}
						}
						for (var i = 0; i < edutab.length; ++i) {
								if(edutab[i].classList.contains('error-tab')){
								edutab[i].classList.remove("error-tab");
								}
						}
							// document.getElementById("pswp-group-"+_cnter).removeAttribute("required");
							// document.getElementById("pswp-training-practice-"+_cnter).removeAttribute("required");
							// document.getElementById("pswp-beginning-date-"+_cnter).removeAttribute("required");
							// document.getElementById("pswp-end-date-"+_cnter).removeAttribute("required");

							

							// if(edugroupChk.classList.contains('error')){
							// 	document.getElementById("pswp-group-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-group-"+_cnter).parentNode.lastElementChild.remove();
							// }

							// if(edutrainingChk.classList.contains('error')){
							// 	document.getElementById("pswp-training-practice-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-training-practice-"+_cnter).parentNode.lastElementChild.remove();
							// }

							// if(eduBegDtChk.classList.contains('error')){
							// 	document.getElementById("pswp-beginning-date-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-beginning-date-"+_cnter).nextElementSibling.remove();
							// }

							// if(eduEndDtChk.classList.contains('error')){
							// 	document.getElementById("pswp-end-date-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-end-date-"+_cnter).nextElementSibling.remove();
							// }
							
							// if(eduDescriptionChk.classList.contains('error')){
							// 	document.getElementById("pswp-description-"+_cnter).classList.remove("error");
							// 	document.getElementById("pswp-description-"+_cnter).nextElementSibling.remove();
							// }
					} } }}}}}}}}}}
				}
			}
		}
</script>
	<script id="new_edu_template" type="x-tmpl-mustache">
        <div class="new-edu-wrap">
        <div class="edu-form-content">


            <br/>
            <div class="form-group">
                <label for="pswp-group-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Group', 'prosolwpclient' ) ?> 
					*	 
				</label>
                <div class="col-sm-9 error-msg-show">
                    <select autocomplete="off"  class="form-control group-selection prosolwpclient-chosen-select edu-{{increment}} education-{{increment}}-typeID" name="education[{{increment}}][typeID]" 
						required data-required="required" id="pswp-group-{{increment}}" data-track="{{increment}}" onchange="getvalue(this.value,{{increment}})">
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
                <label for="pswp-training-practice-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Training / Practice', 'prosolwpclient' ) ?> 
					*
				</label>
                <div class="col-sm-9 error-msg-show training-practice-section-{{increment}}">
                    <select autocomplete="off"  class="form-control training-practice prosolwpclient-chosen-select edu-{{increment}} education-{{increment}}-detailID" name="education[{{increment}}][detailID]" 
						required data-required="required" id="pswp-training-practice-{{increment}}" onchange="getvalue(this.value,{{increment}})">
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
                <label for="pswp-beginning-date-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Beginning', 'prosolwpclient' ) ?> 
				*
				</label>
                <div class="col-sm-7 error-msg-show">
					<select autocomplete="off"  name="education[{{increment}}][start]" class="form-control prosolwpclient-chosen-select edu-start edu-{{increment}} education-{{increment}}-start" 
						required data-required="required" id="pswp-beginning-date-{{increment}}" onchange="getvalue(this.value,{{increment}})"  data-rule-startyearlessorequal="true">
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
                <label for="pswp-end-date-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'The End', 'prosolwpclient' ) ?>
				*
				</label>
                <div class="col-sm-7 error-msg-show">
					<select autocomplete="off"  name="education[{{increment}}][end]" class="form-control prosolwpclient-chosen-select edu-end edu-{{increment}} education-{{increment}}-end" 
						required data-required="required" id="pswp-end-date-{{increment}}"  data-rule-endyeargraterorequal="true" onchange="getvalue(this.value,{{increment}})">
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
            <div class="form-group <?php echo $field_opt['postcode'][1] ?>">
                <label for="pswp-edu-postcode-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Postcode/Town', 'prosolwpclient' ) ?>
				<?php echo $field_opt['postcode'][2] ?>
				</label>

                <div class="col-sm-4 error-msg-show">
                    <input type="text" name="education[{{increment}}][zip]" class="form-control edu-{{increment}} education-{{increment}}-zip"
					<?php echo $field_opt['postcode'][4] ?> <?php echo $field_opt['postcode'][3] ?> id="pswp-edu-postcode-{{increment}}"  onkeyup="getvalue(this.value,{{increment}})" data-rule-minlength="4" data-rule-maxlength="15" data-rule-digits="true" placeholder="<?php esc_html_e( 'Postcode', 'prosolwpclient' ) ?>">
                </div>
                <div class="col-sm-1 control-label"><span>/</span></div>
                <div class="col-sm-4 error-msg-show">
                    <input type="text" name="education[{{increment}}][city]" class="form-control edu-{{increment}} education-{{increment}}-city"
					<?php echo $field_opt['postcode'][4] ?> <?php echo $field_opt['postcode'][3] ?> id="pswp-edu-town-{{increment}}" onkeyup="getvalue(this.value,{{increment}})" data-rule-maxlength="50" data-rule-lettersonly="true" placeholder="<?php esc_html_e( 'Town', 'prosolwpclient' ) ?>">
                </div>
            </div>
            <!--7 Country-->
            <div class="form-group <?php echo $field_opt['country'][1] ?>">
                <label for="pswp-edu-country-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Country', 'prosolwpclient' ) ?>
				<?php echo $field_opt['country'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
                    <select autocomplete="off"  class="form-control prosolwpclient-chosen-select pswp-country-selection edu-{{increment}} " name="education[{{increment}}][countryID]"
					<?php echo $field_opt['country'][4] ?> <?php echo $field_opt['country'][3] ?> data-defnation="<?php echo $dafault_nation_selected ?>" id="pswp-edu-country-{{increment}}" data-track="{{increment}}" onchange="getvalue(this.value,{{increment}})">
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
                    <label for="pswp-edu-federal-state-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Federal State', 'prosolwpclient' ) ?>	   
						*
					</label>
                    <div class="col-sm-9 error-msg-show">
                        <select autocomplete="off" class="form-control prosolwpclient-chosen-select pswp-federal-selection-{{increment}} edu-{{increment}} " name="education[{{increment}}][federalID]" 
							required data-required="required" id="pswp-edu-federal-state-{{increment}}" onchange="getvalue(this.value,{{increment}})">
                            <option value=""><?php esc_html_e( 'Please Select Federal State', 'prosolwpclient' ) ?></option>
                            <?php
			foreach ( $federal_arr as $index => $federal_info ) { ?>
                                    <option value="<?php echo $federal_info['federalId']; ?>"><?php echo $federal_info['name']; ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
            <!--8 Level of Education-->
            <div class="form-group <?php echo $field_opt['level'][1] ?>">
                <label for="pswp-education-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Level of Education', 'prosolwpclient' ) ?>
				<?php echo $field_opt['level'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
                    <select autocomplete="off"  class="form-control prosolwpclient-chosen-select edu-{{increment}} " name="education[{{increment}}][iscedID]" 
					<?php echo $field_opt['level'][4] ?> <?php echo $field_opt['level'][3] ?>  id="pswp-education-{{increment}}" onchange="getvalue(this.value,{{increment}})">
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
                <label for="pswp-activity-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Field of Activity', 'prosolwpclient' ) ?>
					*
				</label>
                <div class="col-sm-9 activity-section-wrap-{{increment}} error-msg-show">
                    <input type="hidden" name="education[{{increment}}][operationAreaID]" id="pswp-edu-foact-{{increment}}"  class="edu-{{increment}}"
					required data-required="required">
					<ul id="activity_selection_wrapper"  class="acti_selec_wrap-{{increment}} edu-{{increment}}-foac" style="list-style-type: none;" onclick="getspanActivity({{increment}})"></ul>
                    <button type="button" class="btn btn-default btn-md activity-btn-modal" data-bs-toggle="modal" data-activity-modaltrack="{{increment}}"
                            data-bs-target="#activityModal">
                        <?php esc_html_e( 'Choose', 'prosolwpclient' ) ?>
                    </button>
                </div>
            </div>
            <!--10 Business-->
            <div class="form-group">
                <label for="pswp-business-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Business', 'prosolwpclient' ) ?>
					*
				</label>
                <div class="col-sm-9 business-section-wrap-{{increment}} error-msg-show">
                    <input type="hidden" name="education[{{increment}}][naceID]" id="pswp-edu-business-{{increment}}" class="edu-{{increment}}"
					required data-required="required">
                    <ul id="business_selection_wrapper" class="busi_sele_wrap-{{increment}} edu-{{increment}}-business" style="list-style-type: none;" onclick="getspanBusiness({{increment}})">
						<!-- mustache template -->
					</ul>
                    <button type="button" class="btn btn-default btn-md business-btn-modal" data-bs-toggle="modal" data-business-modaltrack="{{increment}}"
                            data-bs-target="#businessModal">
                        <?php esc_html_e( 'Choose', 'prosolwpclient' ) ?>
                    </button>
                </div>
            </div>
            <!--11 Description-->
            <div class="form-group <?php echo $field_opt['description'][1] ?>">
                <label for="pswp-description-{{increment}}" class="col-sm-3 control-label"><?php esc_html_e( 'Description', 'prosolwpclient' ) ?> 
				<?php echo $field_opt['description'][2] ?>
				</label>
                <div class="col-sm-9 error-msg-show">
                    <textarea name="education[{{increment}}][notes]" class="form-control edu-{{increment}}  education-{{increment}}-notes" data-rule-maxlength="400"
					<?php echo $field_opt['description'][4] ?>  <?php echo $field_opt['description'][3] ?> 
					 id="pswp-description-{{increment}}" rows="4" cols="50" onkeyup="getvalue(this.value,{{increment}})" placeholder="<?php esc_html_e( 'Briefly describe your activity', 'prosolwpclient' ) ?>"></textarea>
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

		</div>
	</div>
</fieldset>
