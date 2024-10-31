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

	$step_label = get_option('prosolwpclient_applicationform');
	$prosoldes = get_option('prosolwpclient_designtemplate');
	$pstemplate = $prosoldes[$isset.'destemplate'];

	$haspreskill = ($step_label[$isset.'expertise_furtherskill_act'] == '0') && ($skill_id_mustache == '') ? 0 : 1;
?>
<style type="text/css">

	.sticky::before {
	    box-shadow: 1px 2px 3px #000;
	    content: "";
	    display: block;
	    height: 1px;
	    position: relative;
	    width: 100%;
	    z-index: 999999;
	}
	.sticky {
	    background: #fff none repeat scroll 0 0;
	    position: fixed;
	    top: 56px;
	    width: calc(100% - 20px);
	}
	.new_header_div_elm {
		padding-bottom: 57px !important;
	}
	.prosolwpclientcustombootstrap .table-responsive > .table > tbody > tr > td,
	.prosolwpclientcustombootstrap .table-responsive > .table > thead > tr > th {
		white-space:normal !important;
		text-align:center;
	}

	@media ( max-width: 375px) {
		.prosolwpclientcustombootstrap .table-responsive {
			left:-5vw;		
			width:315px !important;	
		}
		
		.prosolwpclientcustombootstrap .table-responsive > .table > tbody > tr > td,
		.prosolwpclientcustombootstrap .table-responsive > .table > tbody > tr > td div > a,
		.prosolwpclientcustombootstrap .table-responsive > .table > tbody > tr > td div > div.chosen-drop > ul > li,
		.prosolwpclientcustombootstrap .table-responsive > .table > tbody > tr > td div > div.chosen-drop > div.chosen-search > .chosen-search-input,
		.prosolwpclientcustombootstrap .table-responsive > .table > thead > tr > th {
			padding: calc(4px + 0.3vw) !important;
			font-size: calc(8px + 0.5vw) !important;
		}
	}
</style>
<?php if($haspreskill){ ?>
	<fieldset class="application-info-expertise">
		<legend><?php esc_html_e( $step_label[$isset.'expertise']) ?></legend>

		<?php if($skill_id_mustache != '' && !is_null($pres_rate_arr)){ ?>
			<div class="preskill table-responsive">
				<table class="table table-striped table-hover">
					<tr class="header-skill">
						<td><b><?php esc_html_e('Skill group', 'prosolwpclient'); ?></b></td>
						<td><b><?php esc_html_e('Knowledge', 'prosolwpclient'); ?></b></td>
						<td><b><?php esc_html_e('Classification', 'prosolwpclient'); ?></b></td>
					</tr>			
					<?php for($i=0;$i<count($skill_id_arr);$i++){ ?>
						<tr class="list-skill">
							<td ><?php echo $skillgroup_name_arr[$i]; ?></td>
							<td ><?php echo $skill_name_arr[$i]; ?></td>
							<td ><?php
								$html = sprintf('<select name="vskill%2$s" id="vskill%2$s" class="form-control vskill  prosolwpclient-chosen-select" data-sgrid="%1$s" data-sid="%2$s"
										data-sgrname="%3$s" data-sname="%4$s"><option value="x">'.esc_html__('No Value', 'prosolwpclient').'</option>
								', $skillgroup_id_arr[$i], $skill_id_arr[$i],$skillgroup_name_arr[$i],$skill_name_arr[$i]);
								// php 8 - need check if key exist in array
								// calling 'not exist key' in array directly will produce error 
								if(array_key_exists($skillgroup_id_arr[$i],$pres_rate_arr)){
									for($idx=0;$idx<count($pres_rate_arr[$skillgroup_id_arr[$i]]);$idx++){ 
										$srateval=array_keys($pres_rate_arr[$skillgroup_id_arr[$i]])[$idx];
										$html .= sprintf('<option value="%1$s">%2$s</option>'
										,$srateval , $pres_rate_arr[$skillgroup_id_arr[$i]][$srateval]);
									}
								}
								echo $html;	
							?></select></td>	
						</tr>		
					<?php } ?> 				
				</table>
			</div>
			<?php if($step_label[$isset.'expertise_furtherskill_act'] == '1'){  ?>
				<legend><?php esc_html_e( 'Add more skill:', 'prosolwpclient' ) ?></legend>		
			<?php } ?>	
		<?php } ?>				
		
		<?php if($step_label[$isset.'expertise_furtherskill_act'] == '1'){  ?>				
			<div role="alert" style="margin-bottom:1rem">

				<select autocomplete="off" name="knowledge_data" id="knowledge_data" class="form-control knowledge_data  prosolwpclient-chosen-select " onchange="getSelectedValue()">
					<option value="0"><?php esc_html_e( 'Please Select Skill Group', 'prosolwpclient' ) ?>:</option>
					<?php
						global $wpdb;global $prosol_prefix;
						$table_ps_skill_group = $prosol_prefix . 'skillgroup';
						$order_by_skillgroup = '';
						if($step_label[$isset.'expertise_skillgroup_sort'] == '1'){
							$order_by_skillgroup = ' ORDER BY Name ASC';
						}			
						$skill_group_arr = $wpdb->get_results( "SELECT * FROM $table_ps_skill_group WHERE site_id='$siteid' $order_by_skillgroup ");
						foreach ($skill_group_arr as $value) {
					?>
					<option value="<?php echo $value->skillgroupId;?>"><?php echo $value->name;  ?></option>
					<?php
					}
					?>
				</select>


				<input type="hidden" name="selection" id="selection" />
				<div class="selection_data">

				</div>
				<!-- <p> <?php //esc_html_e( 'Knowledge', 'prosolwpclient' ) ?> -->
				<?php if($pstemplate!=1){ ?>
					<button type="button" class="btn btn-primary btnprosoldes-step btn-md pull-right expertise-btn-modal" data-bs-toggle="modal"
							title="<?php esc_html_e( 'Add new Expertise', 'prosolwpclient' ) ?>"
							data-bs-target="#expertiseModal" style="margin-top:5px">
						<?php esc_html_e( 'Add', 'prosolwpclient' ) ?>
					</button>
				<?php } else{ ?>
					<button type="button" class="btn btn-primary btn-md pull-right expertise-btn-modal" data-bs-toggle="modal"
							title="<?php esc_html_e( 'Add new Expertise', 'prosolwpclient' ) ?>"
							data-bs-target="#expertiseModal">
						<?php esc_html_e( 'Add', 'prosolwpclient' ) ?>
					</button>		
				<?php } ?>				
					<span class="clearfix"></span>
				</p>
			</div>
		<?php } ?>	

		<!-- Expertise Modal -->
		<div class="modal fade" id="expertiseModal" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header new_header_div_elm">
						<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
						<h4 class="modal-title"><?php esc_html_e( 'Knowledge', 'prosolwpclient' ) ?></h4>
					</div>
					<div class="modal-body" style="overflow:hidden;">
						<div class="form-group">
							<div class="table-responsive">
								<div class="table-fixheader">
									<div>
										<table class="table table-striped table-hover pswp-expertise-table"></table>
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default expertise-abort-btn"
								data-bs-dismiss="modal"><?php esc_html_e( 'Abort, stop', 'prosolwpclient' ) ?>
						</button>
						<button type="button" class="btn btn-default expertise-save-btn"
								data-bs-dismiss="modal"
								data-counter="1"><?php esc_html_e( 'Save and Close', 'prosolwpclient' ) ?>
						</button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="table-responsive check_expertise_entry_exist">
			<table class="table table-striped table-hover">
				<thead>
				<tr class="header-skill">
					<th><?php esc_html_e( 'Skill group', 'prosolwpclient' ) ?></th>
					<th><?php esc_html_e( 'Knowledge', 'prosolwpclient' ) ?></th>
					<th><?php esc_html_e( 'Classification', 'prosolwpclient' ) ?></th>
					<th><?php esc_html_e( 'Action', 'prosolwpclient' ) ?></th>
				</tr>
				</thead>

				<tbody id="pswp_expertise_wrapper">

				</tbody>
			</table>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-0 col-sm-12">

			</div>
		</div>


		<script type="text/javascript">

			var selected_text = jQuery('#knowledge_data option:first-child').val();
			if(selected_text == 0){
				jQuery('.expertise-btn-modal').attr('disabled', 'disabled');
			}
			
			function getSelectedValue(skillgr,skillid,skillval){
				if(typeof(skillgr)!=='undefined' && typeof(skillid)!=='undefined' && typeof(skillval)!=='undefined'){
					jQuery('#knowledge_data option[value="'+skillgr+'"]').prop("selected",true);
				} 

				var index = jQuery('#knowledge_data :selected').val();
				if(index>0){
					jQuery('.expertise-btn-modal').removeAttr('disabled');
					var name = jQuery('#knowledge_data :selected').text();
					jQuery('#expertiseModal .modal-dialog .modal-content .modal-header .modal-title').text(name);
					jQuery.ajax({
						type : "post",
						url : prosolObj.ajaxurl,

						data : {
							action: 'proSol_goupDataIdCallback',
							security: prosolObj.nonce,
							groupid:index,
							hassiteid:'<?php echo $hassiteid; ?>'

						},
						success:function(data){

							req1=jQuery('#expertiseModal .modal-dialog .modal-content .modal-body .form-group .table-responsive .table-fixheader div .pswp-expertise-table').html(data);
							
							if(typeof(skillgr)!=='undefined' && typeof(skillid)!=='undefined' && typeof(skillval)!=='undefined'){
								req2=jQuery('input#skill_'+skillid+'_'+skillid+'[value="'+skillval+'"]').trigger("click");
								jQuery.when(req1, req2).done(function() {
									jQuery('.expertise-save-btn').trigger("click");
								});
							}		
								
						}
					});
				} else {
					jQuery('.expertise-btn-modal').attr('disabled', 'disabled');
				}
			}
			function showdeletebutton(mainthis){
						//console.log(mainthis.attr('data-skillid'));
				var nthis = jQuery(mainthis);

				var skillid = nthis.data('skillid');

				var skillgroupid = nthis.data('skillgroupid');

				var trash_col = nthis.parents('tr');

				trash_col.find('#skill_' + skillid + '_' + skillgroupid).css('display', 'block');
				
			}

			if(document.getElementById("my_skill_header")!==null){
				window.onscroll = function() {myFunction()};

				var header = document.getElementById("my_skill_header");
				var sticky = header.offsetTop;

				function myFunction() {
				if (window.pageYOffset >= sticky) {
					header.classList.add("sticky");
				} else {
					header.classList.remove("sticky");
				}
				}
			}

		</script>
		<!-- mustache template -->
		<script id="pswp_expertise_template" type="x-tmpl-mustache">
			<tr class="single-expertise-entry list-skill">
				<td>{{skill_group}}</td>
				<td class="second_td_check"><input type="hidden" name="skill[{{increment}}][skillID]" value="{{skillid}}" readonly style="border: none;"/>{{knowledge}}</td>
				<td class="thired_td_classification"><input type="hidden" name="skill[{{increment}}][rating]" value="{{rating}}" readonly style="border: none;"/>{{classification}}</td>
				<td>
				<a href="#" title="<?php esc_html_e( 'Delete Expertise', 'prosolwpclient' ) ?>"
					data-skillid="{{skillid}}" data-skillgroupid="{{skillgroupid}}"
					class="dashicons dashicons-post-trash trash-expertise-row"></a>
				</td>
			</tr>


		</script>
	</fieldset>
<?php } ?>	