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
	$table_ps_profession = $prosol_prefix . 'profession';
	$all_profession      = $wpdb->get_results( "SELECT * FROM $table_ps_profession as profession WHERE profession.site_id='$siteid' ORDER BY profession.name ASC", ARRAY_A );
	
	$opt = get_option('prosolwpclient_applicationform');
	$pol = get_option('prosolwpclient_privacypolicy');
	$genset = get_option( 'prosolwpclient_frontend' );
	$sect = $issite.'personaldata';
	$fields_section=array('title','federal','phone','mobile','email','nationality','marital','gender','diverse','expectedsalary','countrybirth','availfrom','notes');
	$isrec=0;
	
	if($genset[$issite.'enable_recruitment'] == 'on'){
		$isrec=1;

		//add default value for job 
		if( (isset($prof_id_arr) || $prof_id_arr != null) && (isset($prof_name_arr) || $prof_name_arr != null) 
			&& (isset($prof_showinappli_arr) || $prof_showinappli_arr != null) ){
			$all_profession = [];	
			
			for ($i = 0; $i < count($prof_id_arr); $i++){				
				$all_profession[$i]['professionId'] = $prof_id_arr[$i];
				$all_profession[$i]['name'] = $prof_name_arr[$i];			
			}	
				
		}
		//new form for recruitment
		array_push($fields_section,'max_distance');
		array_push($fields_section,'empgroup_ID');
		array_push($fields_section,'tagid');

		global $wpdb;global $prosol_prefix;
		$table_ps_customfields = $prosol_prefix . 'customfields';
		$qCustomfields_arr      = $wpdb->get_results( "SELECT * FROM $table_ps_customfields WHERE site_id='$siteid' ORDER BY customfieldsId ASC ", ARRAY_A );
		$customfields_arr_fields = array();
		$customfields_arr_values = array(); 
		foreach ( $qCustomfields_arr as $index => $cf_info ) {
			//get fields
			if(substr($cf_info['customfieldsId'], 0, 24) != 'Title_profileOptionValue' ){
				array_push($fields_section,$cf_info['customfieldsId']);
				array_push($customfields_arr_fields,$cf_info['customfieldsId']);
			}	
			//get value
			$next_cf = $qCustomfields_arr[$index+1]['customfieldsId'];
			$prev_cf = $qCustomfields_arr[$index-1]['customfieldsId'];
			if($cf_info['customfieldsId'] == $next_cf || $cf_info['customfieldsId'] == $prev_cf){
				//set as array first before add value of option type 
				if($cf_info['customfieldsId'] == $next_cf && $cf_info['customfieldsId'] != $prev_cf)
					$customfields_arr_values[ $cf_info['customfieldsId'] ] = array();
				$temp_val= $cf_info['name'] === '' ? $cf_info['label'] : $cf_info['name'];
				array_push($customfields_arr_values[ $cf_info['customfieldsId'] ] ,$temp_val);
				
			} else{
				$customfields_arr_values[ $cf_info['customfieldsId'] ] = $cf_info['name'] === '' ? $cf_info['label'] : $cf_info['name'];	
			}
		} 
	}	
	//set hide/show, mandatory input
	$field_opt=array();
	foreach($fields_section as $field){
		$field_opt[$field][1]=$opt[$sect.'_'.$field.'_act'] ? '' : 'hidden';
		$field_opt[$field][2]=$opt[$sect.'_'.$field.'_man'] ? '*' : '';
		$field_opt[$field][3]=$opt[$sect.'_'.$field.'_man'] ? 'required' : '';
		if($field == 'expectedsalary'){
			$field_opt[$field][4]=$opt[$sect.'_'.$field.'_text'] ? $opt[$sect.'_'.$field.'_text'] : '';
		}
	}
	
?>

<fieldset class="application-info-personal">
	<legend><?php esc_html_e( $opt[$sect]) ?></legend>
	<input type="hidden" class="isrec" name="isrec" value="<?php echo $isrec?>">
	<!--1 Title-->
	<div class="form-group <?php echo $field_opt['title'][1] ?>">
		<label for="pswp-title"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Title', 'prosolwpclient' ) ?>
			   <?php echo $field_opt['title'][2] ?>	      
		</label>
		<div class="col-sm-9 error-msg-show">
			<select autocomplete="off"  class="form-control prosolwpclient-chosen-select" name="title" id="pswp-title"  <?php echo $field_opt['title'][3] ?> >
				<option value=""><?php esc_html_e( 'Please Select Title', 'prosolwpclient' ) ?></option>
				<?php
					foreach ( $title_arr as $index => $title_info ) { ?>
						<option value="<?php echo $title_info['name']; ?>"><?php echo $title_info['name']; ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
	<!--2 Family Name-->
	<div class="form-group">
		<label for="pswp-family-name"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Family Name', 'prosolwpclient' ) ?>
			*</label>
		<div class="col-sm-9 error-msg-show">
			<input type="text" name="lastname" class="form-control"
				   id="lastname" required data-rule-required="true" data-rule-maxlength="50"
				   placeholder="<?php esc_html_e( 'Family Name', 'prosolwpclient' ) ?>">
		</div>
	</div>
	<!--3 First Given Name-->
	<div class="form-group">
		<label for="pswp-first-given-name"
			   class="col-sm-3 control-label"><?php esc_html_e( 'First Given Name', 'prosolwpclient' ) ?>
			*</label>
		<div class="col-sm-9 error-msg-show">
			<input type="text" name="firstname" class="form-control"
				   id="firstname" required data-rule-required="true" data-rule-maxlength="50"
				   placeholder="<?php esc_html_e( 'First Given Name', 'prosolwpclient' ) ?>">
		</div>
	</div>
	<!--4 Road-->
	<div class="form-group">
		<label for="pswp-road"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Road', 'prosolwpclient' ) ?> *</label>
		<div class="col-sm-9 error-msg-show">
			<input type="text" name="street" class="form-control" id="street" required data-rule-required="true" data-rule-maxlength="80"
				   placeholder="<?php esc_html_e( 'Road', 'prosolwpclient' ) ?>">
		</div>
	</div>
	<!--5 Postcode/Town-->
	<div class="form-group">
		<label for="pswp-postcode"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Postcode* / Town*', 'prosolwpclient' ) ?></label>

		<div class="col-sm-4 error-msg-show">
			<input type="text" name="zip" class="form-control"
				   id="zip" required data-rule-required="true" data-rule-minlength="4" data-rule-maxlength="15" placeholder="<?php esc_html_e( 'Postcode', 'prosolwpclient' ) ?>">
		</div>
		<div class="control-label col-sm-1"><span>/</span></div>
		<div class="col-sm-4 error-msg-show">
			<input type="text" name="city" class="form-control"
				   id="city" required data-rule-required="true" data-rule-maxlength="50" placeholder="<?php esc_html_e( 'Town', 'prosolwpclient' ) ?>">
		</div>
	</div>
	<!--7 Country-->
	<div class="form-group">
		<label for="pswp-country"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Country', 'prosolwpclient' ) ?> *</label>
		<div class="col-sm-9 error-msg-show">
			<select autocomplete="off" class="form-control prosolwpclient-chosen-select pswp-country-selection" name="countryID" id="countryID" required data-rule-required="true" data-track="0">
				<option value=""><?php esc_html_e( 'Please Select Country', 'prosolwpclient' ) ?></option>
				<?php
					foreach ( $country_arr as $index => $country_info ) { ?>
						<option <?php if ( $country_info['countryCode'] === $dafault_nation_selected ) echo 'selected' ?>
							value="<?php echo $country_info['countryCode']; ?>"><?php echo $country_info['name']; ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
	<!--6 Federal State-->
	<div class="form-group <?php echo $field_opt['federal'][1] ?>">
		<label for="pswp-federal-state"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Federal State', 'prosolwpclient' ) ?>
			<?php echo $field_opt['federal'][2] ?>
		</label>
		<div class="col-sm-9 error-msg-show">
			<select autocomplete="off" class="form-control prosolwpclient-chosen-select pswp-federal-selection-0" name="federalID" id="pswp-federal-state" <?php echo $field_opt['federal'][3] ?>>
				<option value=""><?php esc_html_e( 'Please Select Federal State', 'prosolwpclient' ) ?></option>
				<?php
					foreach ( $federal_arr as $index => $federal_info ) { ?>
						<option value="<?php echo $federal_info['federalId']; ?>"><?php echo $federal_info['name']; ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
	<!--8 Date of Birth-->
	<div class="form-group">
		<label for="pswp-date-birth"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Date of Birth', 'prosolwpclient' ) ?>
			*</label>
		<div class="col-sm-7 error-msg-show">
			<input type="text" name="birthdate" class="form-control pswpuidatepicker-restrictfucture"
				   id="birthdate" required data-rule-required="true" 
				   placeholder="<?php esc_html_e( 'Please Pick Date of Birth', 'prosolwpclient' ) ?>">
		</div>
		<div class="col-sm-2">
			<span style="font-size: 12px;">DD.MM.YYYY</span>
		</div>
	</div>
	<!--9 Phone-->
	<div class="form-group <?php echo $field_opt['phone'][1] ?>">
		<label for="pswp-phone"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Phone', 'prosolwpclient' ); echo $field_opt['phone'][2]; ?> 
		</label>
		<div class="col-sm-9 error-msg-show">
			<input type="text" name="phone1" class="form-control" id="phone1" <?php echo $field_opt['phone'][3] ?> 
					data-rule-required="true" data-rule-minlength="9" data-rule-maxlength="35" placeholder="<?php esc_html_e( 'Phone', 'prosolwpclient' ) ?>">
		</div>
	</div>
	<!--10 Mobile-->
	<div class="form-group <?php echo $field_opt['mobile'][1] ?>">
		<label for="pswp-mobile"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Mobile', 'prosolwpclient' ); echo $field_opt['mobile'][2] ?> 
		</label>
		<div class="col-sm-9 error-msg-show">
			<input type="text" name="phone2" class="form-control" id="pswp-mobile" <?php echo $field_opt['mobile'][3] ?>
					data-rule-minlength="9" data-rule-maxlength="35" placeholder="<?php esc_html_e( 'Mobile', 'prosolwpclient' ) ?>">
		</div>
	</div>
	<!--11 E-mail-->
	<div class="form-group <?php echo $field_opt['email'][1] ?>">
		<label for="pswp-email"
			   class="col-sm-3 control-label"><?php esc_html_e( 'E-mail', 'prosolwpclient' ); echo $field_opt['email'][2] ?> 
		</label>
		<div class="col-sm-9 error-msg-show">
			<input type="email" name="email" class="form-control" id="pswp-email" <?php echo $field_opt['email'][3] ?>
					data-rule-maxlength="200" placeholder="<?php esc_html_e( 'E-mail', 'prosolwpclient' ) ?>">
		</div>
	</div>
	<!--12 Nationality-->
	<div class="form-group <?php echo $field_opt['nationality'][1] ?>">
		<label for="pswp-nationality"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Nationality', 'prosolwpclient' ); echo $field_opt['nationality'][2] ?>
		</label>
		<div class="col-sm-9 error-msg-show">
			<select autocomplete="off" class="form-control prosolwpclient-chosen-select" name="nationality" id="nationality" <?php echo $field_opt['nationality'][3] ?>
				data-rule-required="true">
				<option value=""><?php esc_html_e( 'Please Select Nationality', 'prosolwpclient' ) ?></option>
				<?php
					foreach ( $country_arr as $index => $country_info ) { ?>
						<option <?php if ( $country_info['countryCode'] === $dafault_nation_selected ) echo 'selected' ?> value="<?php echo $country_info['countryCode']; ?>"><?php echo $country_info['name']; ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
	<!--13 Marital Status-->
	<div class="form-group <?php echo $field_opt['marital'][1] ?>">
		<label for="pswp-marital-status"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Marital Status', 'prosolwpclient' ); echo $field_opt['marital'][2] ?>
		</label>
		<div class="col-sm-9 error-msg-show">
			<select autocomplete="off"  class="form-control prosolwpclient-chosen-select" name="maritalID" id="maritalID" <?php echo $field_opt['marital'][3] ?>
				data-rule-required="true">
				<option value=""><?php esc_html_e( 'Please Select Marital Status', 'prosolwpclient' ) ?></option>
				<?php
					foreach ( $marital_arr as $index => $marital_info ) { ?>
						<option value="<?php echo $marital_info['maritalId']; ?>"><?php echo $marital_info['name']; ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
	<!--14 Gender-->
	<div class="form-group <?php echo $field_opt['gender'][1] ?>">
		<label for="pswp-gender"
			   class="col-lg-3 control-label"><?php esc_html_e( 'Gender', 'prosolwpclient' ); echo $field_opt['gender'][2] ?>
		</label>
		<div class="col-lg-9 error-msg-show gender-parent">
			<label class="radio-inline gender-radio">
				<input name="gender" type="radio" value="0" <?php echo $field_opt['gender'][3] ?> data-rule-required="true" id="gender">
					<span style="margin-left:0.2em"> <?php esc_html_e( 'Female', 'prosolwpclient' ) ?></span>
					<span class="radiomark"></span>
			</label>
			<label class="radio-inline gender-radio">
				<input name="gender" type="radio" value="1" <?php echo $field_opt['gender'][3] ?> data-rule-required="true" id="gender">
					<span style="margin-left:0.2em"> <?php esc_html_e( 'Male', 'prosolwpclient' ) ?></span>
					 <span class="radiomark"></span>
			</label>
			<label class="radio-inline gender-radio <?php echo $field_opt['diverse'][1] ?> " >
				<input name="gender" type="radio" value="2" <?php echo $field_opt['gender'][3] ?> data-rule-required="true" id="gender"> 
					<span style="margin-left:0.2em"> <?php esc_html_e( 'Diverse', 'prosolwpclient' ) ?></span>
					<span class="radiomark"></span>
			</label>
		</div>
	</div>
	<!--15 Country of Birth-->
	<div class="form-group <?php echo $field_opt['countrybirth'][1] ?>">
		<label for="pswp-birth-country"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Country of Birth', 'prosolwpclient' ); echo $field_opt['countrybirth'][2] ?>
		</label>
		<div class="col-sm-9 error-msg-show">
			<select autocomplete="off"  class="form-control prosolwpclient-chosen-select" name="birthcountry" id="birthcountry" <?php echo $field_opt['countrybirth'][3] ?>>
				<option value=""><?php esc_html_e( 'Please Select Birth Country', 'prosolwpclient' ) ?></option>
				<?php
					foreach ( $country_arr as $index => $country_info ) { ?>
						<option <?php if ( $country_info['countryCode'] === $dafault_nation_selected ) echo 'selected' ?> value="<?php echo $country_info['countryCode']; ?>"><?php echo $country_info['name']; ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
	<!--16 Job-->
	<div class="form-group">
		<label for="pswp-job" class="col-sm-3 control-label"><?php esc_html_e( 'Job', 'prosolwpclient' ) ?> *</label>
		<div class="col-sm-9 error-msg-show">
			<ul id="job_selection_wrapper" style="list-style-type: none;">
				<input type="hidden" class="profession_tempo" name="profession[]" required data-rule-required="true">
				<!-- mustache template -->
				<script id="job_selection_template" type="x-tmpl-mustache">
					<li class="job-selection-wrap valid job-li-{{jobid}}">
						<input type="hidden" name="profession[]" value="{{jobid}}" required data-rule-required="true">
						<span class="job-title">{{job_title}}</span>
						<span class="job-remove" data-jobid="{{jobid}}" style="color: red;"> x </span>
					</li>
				</script>
			</ul>

			<button type="button" class="btn btn-default btn-md job-btn-modal" data-bs-toggle="modal"
					data-bs-target="#jobModal">
				<?php esc_html_e( 'Choose', 'prosolwpclient' ) ?>
			</button>
		</div>
	</div>
	<!--17 Expected Salary-->
	<div class="form-group <?php echo $field_opt['expectedsalary'][1] ?>">
		<label for="pswp-expectedsalary" class="col-sm-3 control-label">
			<?php esc_html_e( $field_opt['expectedsalary'][4], 'prosolwpclient' ); echo $field_opt['expectedsalary'][2]  ?>
		</label>

		<div class="col-sm-4 error-msg-show">
			<input type="text" name="expectedsalary" class="form-control" <?php echo $field_opt['expectedsalary'][3] ?>
				   id="expectedsalary" data-rule-maxlength="15" 
				   value="">
		</div>
	</div>
	<!--18 Available From-->
	<div class="form-group <?php echo $field_opt['availfrom'][1] ?>">
		<label for="pswp-available-date"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Available From', 'prosolwpclient' ); echo $field_opt['availfrom'][2] ?>
		</label>
		<div class="col-sm-7 error-msg-show">
			<input type="text" name="availabilitydate" class="form-control pswpuidatepicker" id="availabilitydate"  <?php echo $field_opt['availfrom'][3] ?>
				data-rule-required="true" placeholder="<?php esc_html_e( 'Please Pick Date', 'prosolwpclient' ) ?>">
		</div>
		<div class="col-sm-2">
			<span style="font-size: 12px;">DD.MM.YYYY</span>
		</div>
	</div> 
	<!--19 Notes-->
	<div class="form-group <?php echo $field_opt['notes'][1] ?>">
		<label for="pswp-notes"
			   class="col-sm-3 control-label"><?php esc_html_e( 'Do not forward documents to', 'prosolwpclient' ); 	echo $field_opt['notes'][2] ?>
		</label>
		<div class="col-sm-9 error-msg-show">
			<textarea name="information" class="form-control" data-rule-maxlength="300"
				<?php echo $field_opt['notes'][3] ?> id="pswp-notes" rows="4" cols="50"></textarea>
		</div>
	</div>

	<!-- Recruitment fields --> 
	<?php if($genset[$issite.'enable_recruitment']=='on'){ 
		// foreach ( $customfields_arr_values as $cf_option ) {
		// 	$cf_objkey = array_keys($customfields_arr_values,$cf_option);
		// 	var_dump($cf_objkey);
		//}	
		foreach($customfields_arr_fields as $cf_field){
			if(substr($cf_field, 0, 17) == 'Title_profileText' ){
	?>
				<div class="form-group error-msg-show <?php echo $field_opt[$cf_field][1] ?>"><label for="<?php echo $cf_field ?>" class="col-sm-3 control-label"><?php echo $customfields_arr_values[ $cf_field ]; echo $field_opt[$cf_field][2]; ?></label>
					<div class="col-sm-9">
						<textarea name="<?php echo $cf_field ?>" class="form-control" data-rule-maxlength="400" id="<?php echo $cf_field ?>" rows="2" cols="50" <?php echo $field_opt[$cf_field][3] ?> ></textarea>
					</div>
				</div>
	<?php			
			} elseif (substr($cf_field, 0, 19) == 'Title_profileOption') {
				$poindex=substr($cf_field, -1);
				
	?>	
				<div class="form-group <?php echo $field_opt[$cf_field][1] ?>"><label for="<?php echo $cf_field ?>"class="col-sm-3 control-label"><?php echo $customfields_arr_values[ $cf_field ]; echo $field_opt[$cf_field][2] ?></label>
					<div class="col-sm-9 error-msg-show">
						<select autocomplete="off"  class="form-control prosolwpclient-chosen-select" name="<?php echo $cf_field ?>" id="<?php echo $cf_field ?>" <?php echo $field_opt[$cf_field][3] ?> >
							
							<?php	 						    
								foreach ( array_keys($customfields_arr_values) as $cf_key ) { 
									if($cf_key == 'Title_profileOptionValue'.$poindex){	 
										if(is_array($customfields_arr_values[$cf_key])){
							?>
											<option value="<?php echo $customfields_arr_values[$cf_key] ?>"><?php echo $customfields_arr_values[$cf_key] ?></option>
							<?php				
										} else {
											foreach($customfields_arr_values[$cf_key] as $cf_value){	
							?>
												<option value="<?php echo $cf_value ?>"><?php echo $cf_value ?></option>
							<?php 
											}
										}	
									}
								}	
							?>
						</select>
					</div>
				</div>	
	<?php			
			}
		}
	?>
		<div class="form-group error-msg-show <?php echo $field_opt['max_distance'][1] ?>"><label for="tagid" class="col-sm-3 control-label"><?php esc_html_e( 'Max Distance', 'prosolwpclient' ); echo $field_opt['max_distance'][2];  ?></label>
		<div class="col-sm-9"><textarea name="max_distance" class="form-control" data-rule-maxlength="400" id="max_distance" rows="2" cols="50" <?php echo $field_opt['max_distance'][3] ?> ></textarea></div></div>

		<div class="form-group error-msg-show <?php echo $field_opt['empgroup_ID'][1] ?>"><label for="empgroup_ID" class="col-sm-3 control-label"><?php esc_html_e( 'Employee Group ID', 'prosolwpclient' ); echo $field_opt['empgroup_ID'][2];  ?></label>
		<div class="col-sm-9"><textarea name="empgroup_ID" class="form-control" data-rule-maxlength="400" id="empgroup_ID" rows="2" cols="50" <?php echo $field_opt['empgroup_ID'][3] ?> ></textarea></div></div>
	
		<div class="form-group error-msg-show <?php echo $field_opt['tagid'][1] ?>"><label for="tagid" class="col-sm-3 control-label"><?php esc_html_e( 'Tag ID', 'prosolwpclient' ); echo $field_opt['tagid'][2];  ?></label>
		<div class="col-sm-9"><textarea name="tagid" class="form-control" data-rule-maxlength="400" id="tagid" rows="2" cols="50" <?php echo $field_opt['tagid'][3] ?> ></textarea></div></div>
	<?php } ?>
	
	<!--19 Agree checkbox-->
	<?php
	if($opt[$issite.'one_pager'] == '1'){
		for ($i = 1; $i <= 6; $i++){
			if($frontend_setting[$issite.'enable_recruitment'] == 'off' && $i == 6)break;
			$man_agree = ($i == 1 || $i == 6) ? '*' : '';
			if($pol[$issite.'policy'.$i.'_act'] == '1' ||  $pol[$issite.'policy'.$i.'_act'] == '6'){
				$id = 'pswp-agree'.$i; 
					$html= sprintf('
						<div class="form-group">
							<div class="col-lg-offset-3 col-lg-9">
								<label class="checkbox-inline">
									<input name="%1$s" type="checkbox" value="0" id="%1$s" data-mand="%3$s">
									<span style="margin-left:0.2em">%2$s %3$s</span>
									<span class="checkmark"></span>
								</label>
							</div>
						</div>
					',$id,$pol[$issite.'policy'.$i],$man_agree); 
				
				echo $html;
			}
		}
	?>

	<div class="form-group">
		<div class="col-sm-offset-1 col-sm-11">
			<p>(*) = <?php esc_html_e( 'required', 'prosolwpclient' ) ?>!</p>
		</div>
	</div>
	
<?php 
	} ?>
</fieldset>