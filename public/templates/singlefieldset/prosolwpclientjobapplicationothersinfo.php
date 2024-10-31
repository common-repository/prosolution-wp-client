<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}
	$hassiteid = isset( $_GET['siteid'] ) ? $_GET['siteid'] : '';
	$issite		  = CBXProSolWpClient_Helper::proSol_getSiteid($hassiteid);
	$siteid		  = CBXProSolWpClient_Helper::proSol_getSiteidonly($hassiteid);

	$opt = get_option('prosolwpclient_applicationform');
	$sect = $issite.'others';
	$fields_section=array('source','apply','message');
	$field_opt=array();
	foreach($fields_section as $field){
		$field_opt[$field][1]=$opt[$sect.'_'.$field.'_act'] ? '' : 'hidden';
		$field_opt[$field][2]=$opt[$sect.'_'.$field.'_man'] ? '*' : '';
		$field_opt[$field][3]=$opt[$sect.'_'.$field.'_man'] ? 'required' : '';
	}
?>

<fieldset class="application-info-others">
	<legend><?php esc_html_e( $opt[$sect]) ?></legend>
	<!--1 Source Selection-->
	<div class="form-group <?php echo $field_opt['source'][1] ?>">
		<label for="pswp-source-select" class="col-md-5 control-label"><?php esc_html_e( 'You became aware of us through', 'prosolwpclient' ) ?>
			<?php echo $field_opt['source'][2] ?>
		</label>
		<div class="col-md-7 error-msg-show">
			<select autocomplete="off"  class="form-control prosolwpclient-chosen-select" name="recruitmentsourceID" id="recruitmentsourceID"
				<?php echo $field_opt['source'][3] ?> >
				<option value=""><?php esc_html_e( 'Please Select aware source', 'prosolwpclient' ) ?></option>
				<?php
					foreach ( $recruitmentsource_arr as $index => $recruitmentsource_info ) { ?>
						<option value="<?php echo $recruitmentsource_info['recruitmentsourceId']; ?>"><?php echo $recruitmentsource_info['name']; ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
	<!--2 Apply for-->
	<div class="form-group <?php echo $field_opt['apply'][1] ?>">
		<label for="pswp-apply-for" class="col-md-5 control-label"><?php esc_html_e( 'You are applying specifically for', 'prosolwpclient' ) ?>
			<?php echo $field_opt['apply'][2] ?>
		</label>
		<div class="col-md-7 error-msg-show">
			<input type="text" name="applyfor" class="form-control"
				<?php echo $field_opt['apply'][3] ?> id="applyfor" data-rule-maxlength="80"
				   placeholder="<?php esc_html_e( 'Applying for', 'prosolwpclient' ) ?>">
		</div>
	</div>
	<!--3 Message-->
	<div class="form-group <?php echo $field_opt['message'][1] ?>">
		<label for="pswp-message" class="col-md-5 control-label"><?php esc_html_e( 'What else do you want to tell us?', 'prosolwpclient' ) ?>
			<?php echo $field_opt['message'][2] ?>
		</label>
		<div class="col-md-7 error-msg-show">
                            <textarea name="othercommunication" class="form-control"
								<?php echo $field_opt['message'][3] ?> id="othercommunication" rows="4" cols="50" data-rule-maxlength="2000"
								  placeholder="<?php esc_html_e( 'Message', 'prosolwpclient' ) ?>"></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-offset-1 col-md-11">
			
		</div>
	</div>
</fieldset>