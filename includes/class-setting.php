<?php
	error_reporting(0);
	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<?php
	if ( ! defined( 'WPINC' ) ) {
		die;
	}
	/**
	 * weDevs Settings API wrapper class
	 *
	 * @version 1.0
	 *
	 * @author  Tareq Hasan <tareq@weDevs.com>
	 * @link    http://tareq.weDevs.com Tareq's Planet
	 * @example src/settings-api.php How to use the class
	 * Further modified by prosolution team
	 *
	 * Further modification by prosolution team
	 */
	if ( ! class_exists( 'CBXProSolWpClient_Settings_API' ) ):
		class CBXProSolWpClient_Settings_API {

			/**
			 * settings sections array
			 *
			 * @var array
			 */
			private $settings_sections = array();

			/**
			 * Settings fields array
			 *
			 * @var array
			 */
			private $settings_fields = array();

			/**
			 * Singleton instance
			 *
			 * @var object
			 */
			private static $_instance;

			public function __construct( $plugin_name, $version ) {

			}


			/**
			 * Set settings sections
			 *
			 * @param array $sections setting sections array
			 */
			function proSol_set_sections( $sections ) {
				$this->settings_sections = $sections;

				return $this;
			}

			/**
			 * Add a single section
			 *
			 * @param array $section
			 */
			function proSol_add_section( $section ) {
				$this->settings_sections[] = $section;

				return $this;
			}

			/**
			 * Set settings fields
			 *
			 * @param array $fields settings fields array
			 */
			function proSol_set_fields( $fields ) {
				$this->settings_fields = $fields;

				return $this;
			}

			function proSol_add_field( $section, $field ) {
				$defaults = array(
					'name'  => '',
					'label' => '',
					'desc'  => '',
					'type'  => 'text'
				);

				$arg                                 = wp_parse_args( $field, $defaults );
				$this->settings_fields[ $section ][] = $arg;

				return $this;
			}

			/**
			 * Initialize and registers the settings sections and fileds to WordPress
			 *
			 * Usually this should be called at `proSol_admin_init` hook.
			 *
			 * This function gets the initiated settings sections and fields. Then
			 * registers them to WordPress and ready for use.
			 */

			function proSol_admin_init() {				
				//delete_option('site0_prosolwpclient_privacypolicy'); //for remove option manually, uncomment when create new
				//delete_option('prosolwpclient_designtemplate'); 
				//register settings sections
				
				foreach ( $this->settings_sections as $section ) {					
					if ( false == get_option( $section['id'] ) ) {
						$section_default_value = $this->proSol_getDefaultValueBySection( $section['id'] );
						add_option( $section['id'], $section_default_value );
					} else {							
						$section_default_value = $this->proSol_getMissingDefaultValueBySection( $section['id'] );
						 
						if(array_key_exists( 'api_pass',$section_default_value)){ 
							// we need to use this because sync always update the option
							$api_confog_arr=array();
							$validsite=intval(get_option('prosolwpclient_additionalsite')['valids']);
							for($x=0;$x<=$validsite;$x++){
								$issite= $x==0 ? '' : 'site'.$x.'_';
								if(array_key_exists($issite.'oldapi_pass',$section_default_value)){
									if($section_default_value[$issite.'api_pass'] != ''){
										$api_confog_arr[$issite.'api_url'] = $section_default_value[$issite.'api_url'];
										$api_confog_arr[$issite.'api_user'] = $section_default_value[$issite.'api_user'];
										if(get_option('prosolwpclient_isnewapi') == 2){
											$api_confog_arr[$issite.'api_pass'] = crypt_customv2($section_default_value[$issite.'api_pass'],'e');
										}else{
											$api_confog_arr[$issite.'api_pass'] = crypt_custom($section_default_value[$issite.'api_pass'],'e');
										}
									}else{
										$api_confog_arr[$issite.'api_url'] = $section_default_value[$issite.'api_url'];
										$api_confog_arr[$issite.'api_user'] = $section_default_value[$issite.'api_user'];
										$api_confog_arr[$issite.'api_pass'] = $section_default_value[$issite.'oldapi_pass'];
									} 
									update_option( $section['id'], $api_confog_arr );
								}
							}
						} else{	
							update_option( $section['id'], $section_default_value );
						}
						
					}

					if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
						$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
						$callback        = create_function( '', 'echo "' . str_replace( '"', '\"', $section['desc'] ) . '";' );
					} else if ( isset( $section['callback'] ) ) {
						$callback = $section['callback'];
					} else {
						$callback = null;
					}
					add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
				}				
				
				//register settings fields
				foreach ( $this->settings_fields as $section => $field ) {
					foreach ( $field as $option ) {

						$name     = $option['name'];
						$type     = isset( $option['type'] ) ? $option['type'] : 'text';
						$label    = isset( $option['label'] ) ? $option['label'] : '';
						$callback = isset( $option['callback'] ) ? $option['callback'] : array(
							$this,
							'proSol_callback_' . $type
						);

						$args = array(
							'id'                => $option['name'],
							'class'             => isset( $option['class'] ) ? $option['class'] : $name,
							'label_for'         => $args['label_for'] = "{$section}[{$option['name']}]",
							'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
							'name'              => $label,
							'section'           => $section,
							'size'              => isset( $option['size'] ) ? $option['size'] : null,
							'required'          => ( isset( $option['required'] ) && boolval( $option['required'] ) == true ) ? boolval( $option['required'] ) : false,
							'min'               => isset( $option['min'] ) ? $option['min'] : '',
							'max'               => isset( $option['max'] ) ? $option['max'] : '',
							'step'              => isset( $option['step'] ) ? $option['step'] : '',
							'options'           => isset( $option['options'] ) ? $option['options'] : '',
							'default'           => isset( $option['default'] ) ? $option['default'] : '',
							'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
							'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
							'type'              => $type,
							'activated'			=>isset($option['activated']) ? $option['activated'] : '',
							'optgroup'          => isset( $option['optgroup'] ) ? intval( $option['optgroup'] ) : 0
						);
						
						add_settings_field( "{$section}[{$name}]", $label, $callback, $section, $section, $args );
					}
				}
				
				// creates our settings in the options table
				foreach ( $this->settings_sections as $section ) {
					register_setting( $section['id'], $section['id'], array( $this, 'proSol_sanitize_options' ) );
				}
			}

			/**
			 * Prepares default values by section
			 *
			 * @param $section_id
			 *
			 * @return array
			 */
			function proSol_getDefaultValueBySection( $section_id ) {
				
				$default_values = array();

				$fields = $this->settings_fields[ $section_id ];
				
				foreach ( $fields as $field ) {
					$default_values[ $field['name'] ] = isset( $field['default'] ) ? $field['default'] : '';
				}
				
				return $default_values;
			}	

			/**
			 * Prepares default values by section
			 *
			 * @param $section_id
			 *
			 * @return array
			 */
			function proSol_getMissingDefaultValueBySection( $section_id ) {  
				
				$section_value = get_option( $section_id );
				$fields = $this->settings_fields[ $section_id ];
					
				//transfer old data to new index site
				if( isset($_COOKIE['removesite']) && get_option('prosolwpclient_additionalsite')['chkremove']==1){
					$deletedsite_arr= explode(',' , $_COOKIE['removesite']);
					$totalsite=intval(get_option('prosolwpclient_additionalsite')['valids']);

					//assign new value based on new index for tab prosolwpclient_additionalsite
					if($section_id=='prosolwpclient_additionalsite'){
						for($x=1;$x<=$totalsite;$x++){
							$new_index = $this->proSol_getNewIndexAfterSiteRemoval( $x );
							$section_value['addsite'.$x] = get_option($section_id)['addsite'.$new_index];
							$section_value['addsite'.$x.'_urlid'] = get_option($section_id)['addsite'.$new_index.'_urlid'];
						}						
					}

					foreach ( $fields as $field ) {

						//assign new value based on new index for setting site only
						$chksite=substr($field['name'],0,4);
						if($chksite=='site'){
							$pos = strpos($field['name'], '_');
							$onlyfieldname=substr($field['name'],$pos,strlen($field['name']));
							$chksitekey= substr($field['name'],4,$pos-4);
							$new_index = $this->proSol_getNewIndexAfterSiteRemoval( $chksitekey );
							$section_value[ $field['name'] ] = get_option($section_id)['site'.$new_index.$onlyfieldname];
							// if($section_id=='prosolwpclient_designtemplate'){
							// 	var_dump($chksitekey);
							// 	var_dump(get_option('prosolwpclient_languages'));
							// }
							
						}

						if ( ! isset( $section_value[ $field['name'] ] ) ) {
							$section_value[ $field['name'] ] = isset( $field['default'] ) ? $field['default'] : '';	
						}
					}
					
				} else{
					
					foreach ( $fields as $field ) {
						if ( ! isset( $section_value[ $field['name'] ] ) ) {
							$section_value[ $field['name'] ] = isset( $field['default'] ) ? $field['default'] : '';
						}
					}
				}
				
				return $section_value;
			}

			/**
			 * Prepares new index site
			 *
			 * @param $rev_index
			 *
			 * @return string
			 */
			function proSol_getNewIndexAfterSiteRemoval( $rev_index ) {
				$new_index = $rev_index;				
				if( isset($_COOKIE['removesite']) && get_option('prosolwpclient_additionalsite')['chkremove']==1){
					$new_index_arr= [];
					$deletedsite_arr =[];
					array_push($new_index_arr,'0');
					$deletedsite_arr= explode(',' , $_COOKIE['removesite']);
					$totalsite=intval(get_option('prosolwpclient_additionalsite')['valids']);
					//insert new order
					$totaloldindex=count($deletedsite_arr)+$totalsite;
					for($x=1;$x<=$totaloldindex;$x++){
						if( !in_array($x,$deletedsite_arr) ){
							array_push($new_index_arr,$x);
						}	
					}
					//get new index	
					$new_index=$new_index_arr[$rev_index];			
					
					return $new_index;
				} else{
					return $new_index;
				}

			}

			/**
			 * Get field description for display
			 *
			 * @param array $args settings field args
			 */
			public function proSol_get_field_description( $args ) {
				$value = is_array( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) ) ? 0 : $this->proSol_get_option( $args['id'], $args['section'], $args['default'] );
				$value = esc_attr( $value );
				if ( ! empty( $args['desc'] ) ) {
					$desc = sprintf( '<p class="description" value="%2$s">%1$s</p>', $args['desc'], $value );
				} else {
					$desc = '';
				}

				return $desc;
			}

			/**
			 * Displays a text field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_text( $args ) {
				$value    = esc_attr( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );
				$size     = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$type     = isset( $args['type'] ) ? $args['type'] : 'text';
				$required = $args['required'] ? 'required' : '';
				$min      = ( isset( $args['min'] ) && ( $args['min'] != '' ) ) ? intval( $args['min'] ) : 0;
				$max      = ( isset( $args['max'] ) && ( $args['max'] != '' ) ) ? intval( $args['max'] ) : 0;
				
				$minlength_html = $maxlength_html = '';
				if ( $min > 0 ) {
					$minlength_html = 'data-rule-minlength="' . $min . '"';
				}
				if ( $max > 0 ) {
					$maxlength_html = 'data-rule-maxlength="' . $max . '"';
				}

				
				$html = sprintf( '<input type="%1$s" class="%4$s %2$s-text " id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" %6$s %7$s %8$s/>', $type, $size, $args['section'], $args['id'], $value, $required, $minlength_html, $maxlength_html );
				$html .= $this->proSol_get_field_description( $args );
				if($args['id'] == 'client_list' ){
					$html .= sprintf('<div class="cllist_warningtext hidden">'.esc_html__('Please add maximum 5 client ID','prosolwpclient').'</div>');
					$html .= sprintf('<div class="cllista_warningtext hidden">'.esc_html__('Please input correct number type of client ID','prosolwpclient').'</div>');
				}

				echo $html;
			}

			/**
			 * Displays a text field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_text_check( $args ) {
				$array_value = $this->proSol_get_option( $args['id'], $args['section'], $args['default'] );
				$value = $array_value['label'];
				$size     = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$type     = 'text';
				$min      = ( isset( $args['min'] ) && ( $args['min'] != '' ) ) ? intval( $args['min'] ) : 0;
				$max      = ( isset( $args['max'] ) && ( $args['max'] != '' ) ) ? intval( $args['max'] ) : 0;

				$minlength_html = $maxlength_html = '';
				if ( $min > 0 ) {
					$minlength_html = 'data-rule-minlength="' . $min . '"';
				}
				if ( $max > 0 ) {
					$maxlength_html = 'data-rule-maxlength="' . $max . '"';
				}
				$checked = $array_value['activated'] == '1' ? ' checked ' : '';
				$value_checked = $array_value['activated'];
				
				$html = sprintf( ' 
						<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" %7$s %8$s/>
					', $type, $size, $args['section'], $args['id'], $value, $value_checked, $minlength_html, $maxlength_html, $checked);
				if ($args['id'] != 'jobname'){
					$html .= sprintf( '	
						<input type="checkbox" style="margin-bottom:4px" id="%3$s[%4$s_view]" name="%3$s[%4$s_view]" %7$s %8$s %9$s />'.esc_html__('Show / Hide','prosolwpclient').
						'<input type="hidden" id="%3$s[%4$s_act]" name="%3$s[%4$s_act]" value="%6$s" />
					', $type, $size, $args['section'], $args['id'], $value, $value_checked, $minlength_html, $maxlength_html, $checked);
				//$html .= $this->proSol_get_field_description( $args );
	
				}
				
				echo $html;
			}

			/**
			 * Displays a text field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_text_twocolumn( $args ) {

				$value    = esc_attr( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );
				$size     = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$type     = isset( $args['type'] ) ? $args['type'] : 'text';
				$required = $args['required'] ? 'required' : '';
				$min      = ( isset( $args['min'] ) && ( $args['min'] != '' ) ) ? intval( $args['min'] ) : 0;
				$max      = ( isset( $args['max'] ) && ( $args['max'] != '' ) ) ? intval( $args['max'] ) : 0;

				$minlength_html = $maxlength_html = '';
				if ( $min > 0 ) {
					$minlength_html = 'data-rule-minlength="' . $min . '"';
				}
				if ( $max > 0 ) {
					$maxlength_html = 'data-rule-maxlength="' . $max . '"';
				}
				
				$html = sprintf( '<input type="%1$s" class="%4$s %2$s-text " id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" %6$s %7$s %8$s/>'
					, $type, $size, $args['section'], $args['id'], $value, $required, $minlength_html, $maxlength_html);
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}
			
			/**
			 * Displays a text field for a settings field in tab application form
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_text_app_form( $args ) {
				$array_value    = $this->proSol_get_option( $args['id'], $args['section'], $args['default']);
				$value	  = esc_attr($array_value['label']);
				$size     = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$required = $args['required'] ? 'required' : '';
				$checked = $array_value['activated'] == '1' ? 'checked ' : '';
				$manchecked = $array_value['mandatory'] == '1' ? 'checked ' : '';

				$appformlist1=array();
				$validsite=intval(get_option('prosolwpclient_additionalsite')['valids']);
				for($x=0;$x<=$validsite;$x++){
					$isite=  $x==0 ? '' : 'site'.$x.'_';
					array_push($appformlist1,$isite.'personaldata');
				}
				if(in_array($args['id'],$appformlist1)){
					$checked .= ' disabled';
				}
				
				$min      = ( isset( $args['min'] ) && ( $args['min'] != '' ) ) ? intval( $args['min'] ) : 0;
				$max      = ( isset( $args['max'] ) && ( $args['max'] != '' ) ) ? intval( $args['max'] ) : 0;

				$minlength_html = $maxlength_html = '';
				if ( $min > 0 ) {
					$minlength_html = 'data-rule-minlength="' . $min . '"';
				}
				if ( $max > 0 ) {
					$maxlength_html = 'data-rule-maxlength="' . $max . '"';
				}

				$fields_app_form='';
				//get site from option
				$chksite=substr($args['id'],0,4);
				if($chksite=='site'){
					$pos = strpos($args['id'], '_');
					$getsite= substr($args['id'],0,$pos);
					$issite = $getsite.'_';		
				}else{
					$issite = '';		
				}
				if(array_key_exists('field_list',$array_value)){ //exclude sidedishes
					if($array_value['field_list'][0] == 'skillgroup'){
						$isfurtherskill= $array_value['furtherskill_act'];
						if($isfurtherskill== '1'){
							$checkedfskill=" checked ";
						}else{
							$checkedfskill="";
						}
						$fields_app_form  = sprintf( '<p><i>'.esc_html__('Set Following fields:','prosolwpclient').'</i></p><br>'
						.esc_html__('Activate "further skills"','prosolwpclient').'
						<input type="checkbox" id="%1$s[%2$s_%3$s_view4]" name="%1$s[%2$s_%3$s_view4]" %5$s />
						<input type="hidden" id="%1$s[%2$s_%3$s_act]" name="%1$s[%2$s_%3$s_act]" value="%6$s" />
						'

						, $args['section'], $args['id'], 'furtherskill', '', $checkedfskill, $isfurtherskill);
						$sel_orderno = '';
						$sel_name = '';
						$defval_skillgroup_sort = $array_value['skillgroup_sort'];						
						if($defval_skillgroup_sort == '0'){
							$sel_orderno = 'selected = "selected"';
						} else {
							$sel_name = 'selected = "selected"';
						}
						$fields_app_form .= sprintf( '
							<ul class="skillgr_setting">
								<li>'.esc_html__('Skillgroup sort by:','prosolwpclient').'
								&nbsp;<select class="%1$s-text prosolwpclient-chosen-select" id="%2$s[%3$s_skillgroup_sort]" name="%2$s[%3$s_skillgroup_sort]"/>
									<option value="0" %4$s>'.esc_html__('Order number','prosolwpclient').'</option>
									<option value="1" %5$s>'.esc_html__('Name list','prosolwpclient').'</option>
								</select></li>
							</ul>' 
						, $size, $args['section'], $args['id'], $sel_orderno, $sel_name );
					} else{
						$fields_app_form = sprintf( '<p><i>'.esc_html__('Set Following fields:','prosolwpclient').'</i></p>
							<table><tr><th>'.esc_html__('Fields','prosolwpclient').'</th><th>'.esc_html__('Show / Hide','prosolwpclient').'</th><th>'.esc_html__('Mandatory','prosolwpclient').'</th></tr>' 
						);
						
						foreach($array_value['field_list'] as $fieldcol) {
							//default value checkbox
							$checkedcol2 = $array_value[$fieldcol.'_man'] == '1' ? 'checked ' : '';
							$checkedcol1 = '';
							if($array_value[$fieldcol.'_act'] == '1') {
								$checkedcol1 = 'checked';
							}else{
								$checkedcol2 .= ' disabled';
							}
							//custom default value chkbox
							// to remove notice at debugging, add isset
							$chkgender = isset( $array_value['gender_act'] ) ? $array_value['gender_act'] : '';
							if($chkgender == '1'){
								$checkedcol3 = $array_value['diverse_act'] == '1' ? 'checked' : '';
							} else{
								$checkedcol3 = 'disabled';
							}						

							//label for recruitment fields
							if(substr($fieldcol,0,6) == 'Title_' ){
								$col_label = $array_value[$fieldcol.'_cflabel'];
							} else {
								$col_label = $fieldcol;
							}

							//style for personaldata
							$padtop = $fieldcol == 'gender' ? "54.5px" : "0px";
							$widcol = $args['id'] == $issite.'personaldata' ? "width: 15%;" : "";

							//style checkbox for diverse 
							$wrdclr_diverse = $chkgender == '1' ? 'color:black' : 'color:gray';
							

							$fields_app_form .=  sprintf(  '<tr>
								<td style="padding:0 0 %8$s 10px">'.esc_html__($col_label,'prosolwpclient').'</td>
								<td style="%9$s padding:0 0 %8$s 10px"><input type="checkbox" id="%1$s[%2$s_%3$s_view1]" name="%1$s[%2$s_%3$s_view1]" %4$s /></td>
								<td style="%9$s padding:0 0 %8$s 10px"><input type="checkbox" id="%1$s[%2$s_%3$s_view2]" name="%1$s[%2$s_%3$s_view2]" %5$s /></td>
								<input type="hidden" id="%1$s[%2$s_%3$s_act]" name="%1$s[%2$s_%3$s_act]" value="%6$s" />
								<input type="hidden" id="%1$s[%2$s_%3$s_man]" name="%1$s[%2$s_%3$s_man]" value="%7$s" />
								', $args['section'], $args['id'], $fieldcol, $checkedcol1, $checkedcol2, $array_value[$fieldcol.'_act'], $array_value[$fieldcol.'_man'],$padtop,$widcol);
							if($fieldcol == 'gender'){ //checkbox for diverse 
								$fields_app_form .=  sprintf( '
									<td style="padding:0 0 0 10px; %6$s" align="left" class="diverse_style"><input type="checkbox" id="%1$s[%2$s_%3$s_view3]" name="%1$s[%2$s_%3$s_view3]" %4$s />'
									.esc_html__('Activate Gender "Diverse"','prosolwpclient').'
									<br><br><span><i>'.esc_html__('Please make sure that a salutation for gender "Diverse" is set in WorkExpert','prosolwpclient').'</i></span>
									</td>								
									<input type="hidden" id="%1$s[%2$s_diverse_act]" name="%1$s[%2$s_diverse_act]" value="%5$s" />
								', $args['section'], $args['id'], $fieldcol, $checkedcol3, $array_value['diverse_act'], $wrdclr_diverse );	
							}
							if($fieldcol == 'expectedsalary'){
								$fields_app_form .=  sprintf( '
									<td style="padding:0 0 0 10px; " align="left" class="diverse_style">
										<input type="text" id="%1$s[%2$s_%3$s_text]" name="%1$s[%2$s_%3$s_text]" value="%4$s"  required />
									</td>
								', $args['section'], $args['id'], $fieldcol, $array_value['expectedsalary_text']);
							}
							$fields_app_form .= sprintf( '</tr>' );
						}
						$fields_app_form .= sprintf( '</table>' );
					}
					
				} 

				$warning_text='';
				if($args['id'] == $issite.'others') $warning_text = sprintf('<div class="warningtext hidden">'.esc_html__('Please add minimum 1 field in activated step','prosolwpclient').'</div>');
				
				$html = sprintf( '<input type="text" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" %6$s %7$s %8$s/>
					<input type="checkbox" id="%3$s[%4$s_view]" name="%3$s[%4$s_view]" %1$s/>
					<input type="hidden" id="%3$s[%4$s_act]" name="%3$s[%4$s_act]" value="%9$s"/>'.esc_html__('Activate','prosolwpclient') 
					.'%10$s'.'%11$s', $checked, $size, $args['section'], $args['id'], $value, $required, $minlength_html, $maxlength_html, $array_value['activated'], $fields_app_form, $warning_text );

				if(str_contains($args['id'], 'sidedishes')){
					$html .= sprintf( '&nbsp;<input type="checkbox" id="%3$s[%4$s_mandatoryview]" name="%3$s[%4$s_mandatoryview]" %1$s/>
						<input type="hidden" id="%3$s[%4$s_man]" name="%3$s[%4$s_man]" value="%9$s"/>'.esc_html__('Mandatory','prosolwpclient') 
						.'%10$s'.'%11$s', $manchecked, $size, $args['section'], $args['id'], $value, $required, $minlength_html, $maxlength_html, $array_value['mandatory'], $fields_app_form, $warning_text );
				}

				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			/**
			 * Displays a text field for a settings field in tab application form
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_text_des_template( $args ) {
				$array_value    = $this->proSol_get_option( $args['id'], $args['section'], $args['default']);
				$valabel = isset($array_value['label']) ? $array_value['label'] : '';
				$chkactivated = isset($array_value['activated']) ? $array_value['activated'] : '';
				
				$value	  = esc_attr($valabel);
				$size     = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$required = $args['required'] ? 'required' : '';
				$checked = $chkactivated == '1' ? 'checked ' : '';
				$min      = ( isset( $args['min'] ) && ( $args['min'] != '' ) ) ? intval( $args['min'] ) : 0;
				$max      = ( isset( $args['max'] ) && ( $args['max'] != '' ) ) ? intval( $args['max'] ) : 0;

				global $wpdb;global $prosol_prefix;

				$minlength_html = $maxlength_html = '';
				if ( $min > 0 ) {
					$minlength_html = 'data-rule-minlength="' . $min . '"';
				}
				if ( $max > 0 ) {
					$maxlength_html = 'data-rule-maxlength="' . $max . '"';
				}

				//check for field dessearchjobidbtn on all sites
				$destemplist1=array();
				$destemplist2=array();
				$validsite=intval(get_option('prosolwpclient_additionalsite')['valids']);
				for($x=0;$x<=$validsite;$x++){
					$isite=  $x==0 ? '' : 'site'.$x.'_';
					array_push($destemplist1,$isite.'desresultfrontend');
					array_push($destemplist2,$isite.'dessearchjobidbtn');
				}

				if(in_array($args['id'],$destemplist2)){					
					$html = sprintf( '
						<input type="text" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" %6$s %7$s %8$s/>
						<input type="checkbox" id="%3$s[%4$s_view]" name="%3$s[%4$s_view]" %1$s/>
						<input type="hidden" id="%3$s[%4$s_act]" name="%3$s[%4$s_act]" value="%9$s"/>'.esc_html__('Show / dont show unsolicited application button','prosolwpclient') 
					, $checked, $size, $args['section'], $args['id'], $value, $required, $minlength_html, $maxlength_html, $array_value['activated']);
				} else{
					$html = sprintf( '<p><i>'.esc_html__('Set Following fields:','prosolwpclient').'</i></p>
							<table><tr><th>'.esc_html__('Fields','prosolwpclient').'</th><th>'.esc_html__('Show / Hide','prosolwpclient').'</th></tr>' 
						);
						foreach($array_value['field_list'] as $fieldcol) {
							//default value checkbox
							$checkedcol1 = '';
							if($array_value[$fieldcol] == '1') {
								$checkedcol1 = 'checked';
							}
							//get site from option
							$chksite=substr($args['id'],0,4);
							if($chksite=='site'){
								$pos = strpos($args['id'], '_');
								$getsite= substr($args['id'],0,$pos);
								$issite = $getsite.'_';		
								$selsite = substr($args['id'],4,1);
							}else{
								$issite = '';	
								$selsite = 0;	
							}
							
							if(in_array($args['id'],$destemplist1)){
								$idfield=$issite.'desresult';
							}else{
								$idfield=$issite.'desdetails';
							}
							
							if($fieldcol == 'placeofwork'){
								$label='City  ';
							}else if($fieldcol == 'worktime'){	
								$label='Worktime';
							}else{
								$label=$fieldcol;
							}

							if (str_contains($fieldcol, 'textfield')) { 
								$table_jobcustomfield = $prosol_prefix . 'jobcustomfields';
								$customlabel=$wpdb->get_var( $wpdb->prepare( "SELECT customfield_name FROM $table_jobcustomfield WHERE customfield_ID = %s AND site_id='$selsite';", "$fieldcol" ) );
								if($customlabel != ''){
									$label=$customlabel;
								}
							}

							$html .=  sprintf(  '<tr>
								<td style="padding:0 0 10px">'.esc_html__($label,'prosolwpclient').'</td>
								<td style="padding:0 0 10px"><input type="checkbox" id="%1$s[%2$s%3$s_view]" name="%1$s[%2$s%3$s_view]" %4$s /></td>
								<input type="hidden" id="%1$s[%2$s%3$s_act]" name="%1$s[%2$s%3$s_act]" value="%5$s" />
								', $args['section'], $idfield, $fieldcol, $checkedcol1, $array_value[$fieldcol]);
							if($fieldcol == 'customer'){ //input text after checkbox customer
								$html .=  sprintf( '
									<td style="width:50%%" align="left">
										<input type="text" maxlength=100 style="width:85%%" id="%1$s[%2$s%3$s_text]" name="%1$s[%2$s%3$s_text]" value="%4$s"/>
									</td>					
								', $args['section'], $idfield, $fieldcol, $array_value['customer_text'] );

								if($idfield == $issite.'desdetails'){
									$html .= sprintf( '
										<table><tr><th>'.esc_html__('Textfields','prosolwpclient').'</th><th>'.esc_html__('Show / Hide','prosolwpclient').'</th></tr>' 
									);	
								}
							}
							$html .= sprintf( '</tr>' );
						}
						$html .= sprintf( '</table>' );				
				}				
				
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			/**
			 * Displays a text field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_text_site( $args ) {
				$array_value= $this->proSol_get_option( $args['id'], $args['section'], $args['default'] );
				$value    = esc_attr($array_value['name']);
				$urlid    = esc_attr($array_value['urlid']);
				$siteid    = esc_attr($array_value['siteid']);
				$size     = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$type     = isset( $args['type'] ) ? $args['type'] : 'text';
				$required = $args['required'] ? 'required' : '';
				$min      = ( isset( $args['min'] ) && ( $args['min'] != '' ) ) ? intval( $args['min'] ) : 0;
				$max      = ( isset( $args['max'] ) && ( $args['max'] != '' ) ) ? intval( $args['max'] ) : 0;

				$minlength_html = $maxlength_html = '';
				if ( $min > 0 ) {
					$minlength_html = 'data-rule-minlength="' . $min . '"';
				}
				if ( $max > 0 ) {
					$maxlength_html = 'data-rule-maxlength="' . $max . '"';
				}
				
				$html = sprintf( '<input type="%1$s" class="%4$s %2$s-text " id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" %6$s %7$s %8$s data-urlid="%9$s"/>
					'.esc_html__('Url id','prosolwpclient').'
					<input type="%1$s" class="%4$s " id="%3$s[%4$s_urlid]" name="%3$s[%4$s_urlid]" value="%10$s" %6$s />
					&nbsp;&nbsp;<a id="remsites" class="button button-default" target="_blank" data-urlid="%9$s">'.esc_html__('- remove site','prosolwpclient').'</a>	
				', $type, $size, $args['section'], $args['id'], $value, $required, $minlength_html, $maxlength_html,$siteid,$urlid);
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			function proSol_callback_add_site( $args ) {
				$array_value= $this->proSol_get_option( $args['id'], $args['section'], $args['default'] );
				
				$value    = esc_attr($array_value['name']);
				$size     = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$type     = isset( $args['type'] ) ? $args['type'] : 'text';
				$required = $args['required'] ? 'required' : '';
				$min      = ( isset( $args['min'] ) && ( $args['min'] != '' ) ) ? intval( $args['min'] ) : 0;
				$max      = ( isset( $args['max'] ) && ( $args['max'] != '' ) ) ? intval( $args['max'] ) : 0;
				$minlength_html = $maxlength_html = '';
				if ( $min > 0 ) {
					$minlength_html = 'data-rule-minlength="' . $min . '"';
				}
				if ( $max > 0 ) {
					$maxlength_html = 'data-rule-maxlength="' . $max . '"';
				}
				
				$html = sprintf( '<input type="hidden" class="%4$s %2$s-text " id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" %6$s %7$s %8$s data-urlid=""/>
					<input type="hidden" class="%4$s %2$s-text " id="%3$s[chkremove]" name="%3$s[chkremove]" value=0 /><span class="hidden alertsite" >'.esc_html__('Please change to other site before delete','prosolwpclient').'</span>
					<span class="hidden removesite" >'.esc_html__('- remove site','prosolwpclient').'</span>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a id="addsites" class="button button-primary" target="_blank">'.esc_html__('+ add site','prosolwpclient').'</a>					
				', $type, $size, $args['section'], $args['id'], $value, $required, $minlength_html, $maxlength_html);
				
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			/**
			 * Displays a url field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_html_des_template( $args ) {
				$this->proSol_callback_text_des_template( $args );
			}

			/**
			 * Displays a url field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_url( $args ) {
				$this->proSol_callback_text_twocolumn( $args );
			}

			/**
			 * Displays a number field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_number( $args ) {
				$value       = esc_attr( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );
				$size        = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$type        = isset( $args['type'] ) ? $args['type'] : 'number';
				$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';
				$min         = empty( $args['min'] ) ? '' : ' min="' . $args['min'] . '"';
				$max         = empty( $args['max'] ) ? '' : ' max="' . $args['max'] . '"';
				$step        = empty( $args['max'] ) ? '' : ' step="' . $args['step'] . '"';
				$required = $args['required'] ? 'required' : '';

				$html        = sprintf( '<input type="%1$s" class="%2$s-number" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s %7$s %8$s %9$s %10$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder, $min, $max, $step, $required );
				$html        .= $this->proSol_get_field_description( $args );
				echo $html;
			}

			/**
			 * Displays a checkbox for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_checkbox( $args ) {

				$value = esc_attr( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );
				$required = $args['required'] ? 'required' : '';

				$html = '<fieldset>';
				$html .= sprintf( '<label for="wpuf-%1$s[%2$s]">', $args['section'], $args['id'] );
				$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
				$html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s %4$s />', $args['section'], $args['id'], checked( $value, 'on', false ), $required );
				$html .= sprintf( '%1$s</label>', $args['desc'] );
				$html .= '</fieldset>';

				echo $html;
			}

			function proSol_callback_chkbox_recruitment( $args ) {
				$value = esc_attr( $this->proSol_get_option( $args['id'], $args['section']) );

				$html = '<fieldset>';
				$html .= sprintf( '<label for="wpuf-%1$s[%2$s]">', $args['section'], $args['id'] );
				$html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" id="%1$s[%2$s]" value="%3$s" />', $args['section'], $args['id'], $value  );
				$html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $value, checked( $value, 'on', false ) );
				$html .= sprintf( '%1$s</label>', $args['desc'] );
				$html .= '</fieldset>';

				echo $html;
			}
			
			/**
			 * Displays a multicheckbox a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_multicheck( $args ) {

				$value = $this->proSol_get_option( $args['id'], $args['section'], $args['default'] );

				$required = $args['required'] ? 'required' : '';

				$html = '<fieldset>';
				foreach ( $args['options'] as $key => $label ) {
					$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
					$html    .= sprintf( '<label for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
					$html    .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
					$html    .= sprintf( '%1$s</label><br>', $label );
				}
				$html .= $this->proSol_get_field_description( $args );
				$html .= '</fieldset>';

				echo $html;
			}

			/**
			 * Displays a multicheckbox a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_radio( $args ) {

				$value = $this->proSol_get_option( $args['id'], $args['section'], $args['default'] );

				$required = $args['required'] ? 'required' : '';

				$html = '<fieldset>';
				foreach ( $args['options'] as $key => $label ) {
					$html .= sprintf( '<label for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
					$html .= sprintf( '<input type="radio" class="radio" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
					$html .= sprintf( '%1$s</label><br>', $label );
				}
				$html .= $this->proSol_get_field_description( $args );
				$html .= '</fieldset>';

				echo $html;
			}

			/**
			 * Displays a selectbox for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_select( $args ) {
				$isrec = get_option('prosolwpclient_frontend');
				$selectlist=array(); 
				$html = '';
				$validsite=intval(get_option('prosolwpclient_additionalsite')['valids']);
				for($x=0;$x<=$validsite;$x++){
					$isite=  $x==0 ? '' : 'site'.$x.'_';
					array_push($selectlist,$isite.'default_office'); 
				}	
				$chksite=substr($args['id'],0,4);
				if($chksite=='site'){
					$pos = strpos($args['id'], '_');
					$getsite= substr($args['id'],0,$pos);
					$issite = $getsite.'_';		
				}else{
					$issite = '';		
				}	

				$required = $args['required'] ? 'required' : '';
				$value = esc_attr( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );
				$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular prosolwpclient-chosen-select';

				$html .= sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]" %4$s>', $size, $args['section'], $args['id'], $required );
				
				$counter=0; 
				foreach ( $args['options'] as $key => $label ) {
					if($counter==0 && $value==0){
						$html .= sprintf( '<option value="%s" selected>%s</option>', $key, $label );
					} else{
						if($value=='' && $isrec[$issite.'enable_recruitment'] == 'on'){
							$def_select= $key=='1' ? 'selected' : '';
							$html .= sprintf( '<option value="%s"%s>%s</option>', $key, $def_select, $label );
						} else{
							$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
						}	
					}
					$counter++;	
				}
				$html .= sprintf( '</select>' );

				if(in_array($args['id'],$selectlist) && $isrec[$issite.'enable_recruitment'] == 'on'){
					$html .= sprintf( '&nbsp;&nbsp;&nbsp;<font color="green">'.esc_html__('Office must be set for feature Recruitment.','prosolwpclient').'</font>' );
				}

				if($args['id'] == $issite.'dessortby'){
					$radiovalue = $this->proSol_get_option( $args['id'].'order', $args['section'], 'ASC' );
					$html .=  sprintf('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label for="wpuf-%1$s[%2$s][ASC]">
							<input type="radio" class="radio" id="wpuf-%1$s[%2$s][ASC]" name="%1$s[%2$s]" value="ASC" %3$s />
						'.esc_html__('ascending','prosolwpclient').'</label>
						&nbsp;&nbsp;&nbsp;
						<label for="wpuf-%1$s[%2$s][DESC]">
							<input type="radio" class="radio" id="wpuf-%1$s[%2$s][DESC]" name="%1$s[%2$s]" value="DESC" %4$s />
						'.esc_html__('descending','prosolwpclient').'</label>
					', $args['section'], $args['id'].'order', checked( $radiovalue, 'ASC', false ), checked( $radiovalue, 'DESC', false ));
				}

				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			/**
			 * Displays a textarea for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_textarea( $args ) {

				$value = esc_textarea( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );
				$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

				$required = $args['required'] ? 'required' : '';

				$html = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" %5$s>%4$s</textarea>', $size, $args['section'], $args['id'], $value, $required );
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			function proSol_callback_textarea_policy( $args ) {

				$array_value = $this->proSol_get_option( $args['id'], $args['section'], $args['default'] );
				$value = $array_value['label'];
				//use size in proSol_callback_wysiwyg
				//$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';
				$checked = $array_value['activated'] == '1' ? ' checked ' : '';
				$value_checked = $array_value['activated'];
				
				$required = $args['required'] ? 'required' : '';
				// at first, policy 1 & 6 should be mandatory; keep coding for now
				// $getid= substr($args['id'],$pos-1,1);	
				// if($getid == "1" || $getid == "6"){
				// 	$disabled = ' disabled ';	
				// 	$value_checked=1;
				// 	$checked=' checked ';
				// } else{
				// 	$disabled =  '';
				// } 
				
				echo '<div style="max-width: ' . $size . ';">';

				$editor_settings = array(
					'teeny'         => true,
					'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
					'textarea_rows' => 10,
					'media_buttons' => false
				);
				if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
					$editor_settings = array_merge( $editor_settings, $args['options'] );
				}
				wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );
				
				//checkbox mandatory
				$html = sprintf( '<br>
					<input type="checkbox" id="%2$s[%3$s_view]" name="%2$s[%3$s_view]" %7$s />'.esc_html__('Activate','prosolwpclient') 
					.'<input type="hidden" id="%2$s[%3$s_act]" name="%2$s[%3$s_act]" value="%6$s" />'
					, $size, $args['section'], $args['id'], $value, $required, $value_checked, $checked );
				echo $html;

				echo '</div>';

				echo $this->proSol_get_field_description( $args );
			}

			/**
			 * Displays a textarea for a settings field
			 *
			 * @param array $args settings field args
			 *
			 * @return string
			 */
			function proSol_callback_html( $args ) {
				echo $this->proSol_get_field_description( $args );
			}

			function proSol_callback_hidden( $args ) {
				//do nothing
			}


			/**
			 * Displays a rich text textarea for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_wysiwyg( $args ) {


				$value = $this->proSol_get_option( $args['id'], $args['section'], $args['default'] );
				$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';

				echo '<div style="max-width: ' . $size . ';">';

				$editor_settings = array(
					'teeny'         => true,
					'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
					'textarea_rows' => 10
				);
				if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
					$editor_settings = array_merge( $editor_settings, $args['options'] );
				}

				wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );

				echo '</div>';

				echo $this->proSol_get_field_description( $args );
			}

			/**
			 * Displays a file upload field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_file( $args ) {

				$value = esc_attr( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );
				$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$id    = $args['section'] . '[' . $args['id'] . ']';
				$label = isset( $args['options']['button_label'] ) ?
					$args['options']['button_label'] :
					esc_html__( 'Choose File' );

				$html = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
				$html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			/**
			 * Displays a logo upload field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_file_with_thumbnail( $args ) {
				$array_value = $this->proSol_get_option( $args['id'], $args['section'], $args['default'] );
				$value_file = $array_value['file'];
				$value_name = $array_value['name'];
				
				$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
				$id    = $args['section'] . '[' . $args['id'] . ']';
				$label = isset( $args['options']['button_label'] ) ?
					$args['options']['button_label'] :
					esc_html__( 'Choose File' );
				 
				$html = sprintf( '<input type="button" id="%1$s[%2$s]" class="button wpul-browse" value="%3$s" />', $args['section'], $args['id'], $label );
				$html .= sprintf( '<span style="color:gray; padding-left:15px;vertical-align:inherit;" class="%1$s-text wpul-url" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</span>', $size, $args['section'], $args['id'], $value_name );
				$html .= sprintf( '<br><img style="margin:32px 0"; class="wpul-img" src="%1$s" draggable="false" alt="" value="">',  $value_file );
				$html .= sprintf( '<input type="hidden" class="%1$s-text wpul-value" id="%2$s[%3$sfile]" name="%2$s[%3$sfile]" value="%4$s"/>', $size, $args['section'], $args['id'], $value_file );
				$html .= sprintf( '<input type="hidden" class="%1$s-text wpul-name" id="%2$s[%3$sname]" name="%2$s[%3$sname]" value="%4$s"/>', $size, $args['section'], $args['id'], $value_name );
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			/**
			 * Displays a password field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_password( $args ) { 

				$value = esc_attr( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );
				$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

				$chksite=substr($args['id'],0,4);
				if($chksite=='site'){
					$pos = strpos($args['id'], '_');
					$getsite= substr($args['id'],0,$pos);
					$passsite = $getsite.'_';		
				}else{
					$passsite = '';		
				}
				
				$html = sprintf( '<input type="password" autocomplete="new-password" class="%3$s %1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" />', $size, $args['section'], $args['id']);
				$html .= sprintf( '<input type="hidden" class="%1$s-text" id="prosolwpclient_api_config[%3$soldapi_pass]" name="prosolwpclient_api_config[%3$soldapi_pass]" value="%2$s"/>', $size, $value,$passsite );
				$html .= $this->proSol_get_field_description( $args );
				
				echo $html;
			}

			/**
			 * Displays a button url checks field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_btn_url_check( $args ) {

				$html = sprintf( '<div class="apivalid">
					<a class="button button-primary">'. esc_html__('URL check',"prosolwpclient").'</a>
					</div>');
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}
			 
			/**
			 * Displays a color picker field for a settings field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_color( $args ) {

				$value = esc_attr( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );
				$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

				$html = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, $args['default'] );
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			/**
			 * Displays a info field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_title( $args ) {
				$html = sprintf( '<td colspan="2"><h3 class="setting_heading_title"><span>%s</span></h3></td>', $args['label'] );
				echo $html;
			}

			/**
			 * Displays a info field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_subtitle( $args ) {
				$html = sprintf( '<td colspan="2"><h4 class="setting_heading_subtitle"><span>%s</span></h4></td>', $args['label'] );
				echo $html;
			}

			/**
			 * Displays a info field
			 *
			 * @param array $args settings field args
			 */
			function proSol_callback_redirect_uri_pattern( $args ) {
				$value = esc_attr( $this->proSol_get_option( $args['id'], $args['section'], $args['default'] ) );

				$html = sprintf( '<label for="wpuf-%1$s[%2$s]"><code class="prosolwpclientclipboard">', $args['section'], $args['id'] );
				//                $html .= sprintf('%1$s</code></label><br>', $value);
				$html .= sprintf( '%1$s</code><span class="prosolwpclientclipboardtrigger" data-clipboard-target=".prosolwpclientclipboard" title="' . esc_html__( "Copy to clipboard", "prosolwpclient" ) . '">
                            <img style="width: 16px; height: 16px;" src="' . plugins_url( 'includes/assets/img/clippy_18.png', dirname( __FILE__ ) ) . '" alt="' . esc_html__( 'Copy to clipboard', 'prosolwpclient' ) . '">
                        </span><div class="clear"></div></label><br>', $value );
				$html .= $this->proSol_get_field_description( $args );

				echo $html;
			}

			/**
			 * Sanitize callback for Settings API
			 */
			function proSol_sanitize_options( $options ) {
				foreach ( $options as $option_slug => $option_value ) {
					$sanitize_callback = $this->proSol_get_sanitize_callback( $option_slug );

					// If callback is set, call it
					if ( $sanitize_callback ) {
						$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
						continue;
					}
				}

				return $options;
			}

			public static function proSol_sanitize_text_field( $option_value ) {
				return sanitize_text_field( $option_value );
			}

			public static function proSol_sanitize_textarea_field( $option_value ) {
				return sanitize_textarea_field( $option_value );

			}

			/**
			 * Get sanitization callback for given option slug
			 *
			 * @param string $slug option slug
			 *
			 * @return mixed string or bool false
			 */
			function proSol_get_sanitize_callback( $slug = '' ) {
				if ( empty( $slug ) ) {
					return false;
				}

				// Iterate over registered fields and see if we can find proper callback
				foreach ( $this->settings_fields as $section => $options ) {
					foreach ( $options as $option ) {
						if ( $option['name'] != $slug ) {
							continue;
						}

						// Return the callback name
						return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
					}
				}

				return false;
			}

			/**
			 * Get the value of a settings field
			 *
			 * @param string $option  settings field name
			 * @param string $section the section name this field belongs to
			 * @param string $default default text if it's not found
			 *
			 * @return string
			 */
			function proSol_get_option( $option, $section, $default = '' ) {
				$isrec = get_option('prosolwpclient_frontend');
				
				//for application form
				$list_app_form=array();
				$fields_section=array();
				$destemplist1=array();
				$destemplist2=array();
				$destemplist3=array();
				$destemplist4=array();
				$destemplist5=array();

				global $wpdb;global $prosol_prefix;
				$table_ps_customfields = $prosol_prefix . 'customfields';
				
				$validsite=intval(get_option('prosolwpclient_additionalsite')['valids']);
				for($x=0;$x<=$validsite;$x++){
					$issite=  $x==0 ? '' : 'site'.$x.'_';
					array_push($list_app_form,$issite.'personaldata',$issite.'education',$issite.'workexperience',$issite.'expertise',$issite.'sidedishes',$issite.'others');
					$fields_section[$issite.'personaldata']=array('title','federal','phone','mobile','email','nationality','marital','gender','expectedsalary','countrybirth','availfrom','notes');
					
					if($isrec[$issite.'enable_recruitment'] == 'on'){
						array_push($fields_section[$issite.'personaldata'],'max_distance');
						array_push($fields_section[$issite.'personaldata'],'empgroup_ID');
						array_push($fields_section[$issite.'personaldata'],'tagid');
						
						$qCustomfields_arr = $wpdb->get_results( "SELECT * FROM $table_ps_customfields WHERE site_id = $x ", ARRAY_A );

						foreach ( $qCustomfields_arr as $index => $cf_info ) {
							//filter to not push Title_profileOptionValue
							// unset($fields_section[$issite.'personaldata'][$cf_info['customfieldsId']]);
							if(substr($cf_info['customfieldsId'], 0, 24) != 'Title_profileOptionValue' ) 
								array_push($fields_section[$issite.'personaldata'],$cf_info['customfieldsId']);
								
							}
						
					}
					$fields_section[$issite.'education']=array('postcode','country','level','description');
					$fields_section[$issite.'workexperience']=array('job','gendesc','company','postcode','country','federal','experience','contract','employment');
					$fields_section[$issite.'expertise']=array('skillgroup','furtherskill');
					$fields_section[$issite.'others']=array('source','apply','message');
					//for design template
					$fields_section[$issite.'desresultfrontend']=array('zipcode','placeofwork','worktime','agentname','jobprojectid','customer');
					$fields_section[$issite.'desdetailsfrontend']=array('zipcode','placeofwork','worktime','salary','profession','qualification','agentname','jobprojectid','customer','textfield1','textfield2','textfield3','textfield4','textfield5','textfield6','textfield7','textfield8','textfield9','textfield10','textfield11','textfield12','textfield13','textfield14','textfield15','textfield16','textfield17','textfield18','textfield19','textfield20','textfield21','textfield22','textfield23','textfield24','textfield25','textfield26','textfield27','textfield28','textfield29','textfield30');	
					array_push($destemplist1,$issite.'desresultfrontend',$issite.'desdetailsfrontend');
					array_push($destemplist2,$issite.'dessearchjobidbtn');
					array_push($destemplist3,$issite.'desresultfrontend');
					array_push($destemplist4,$issite.'desdetailsfrontend');
					array_push($destemplist5,$issite.'deslogo');
				}

				$options = get_option( $section );
				
				if ( isset( $options[ $option ] ) ) {	
					if( $section == 'prosolwpclient_applicationform' && in_array($option,$list_app_form) ){ // set for application form
						$output=array();
						$output['label']=$options[ $option ];
						$output[ 'activated' ]=$options[ $option.'_act' ];
						$output[ 'mandatory' ]=$options[ $option.'_man' ];
						
						for($x=0;$x<=$validsite;$x++){
							$issite=  $x==0 ? '' : 'site'.$x.'_';
							if( $option == $issite.'personaldata' || $option == $issite.'education' || $option == $issite.'workexperience' || $option == $issite.'others' || $option == $issite.'expertise'){
								$output['field_list']=$fields_section[$option];
								foreach($fields_section[$option] as $field_app_form){
									if($field_app_form == 'skillgroup' ){ //exclusive for expertise -> skillgroup
										$output[$field_app_form.'_sort']=$options[ $option.'_'.$field_app_form.'_sort' ];
									} else if($field_app_form == 'furtherskill' ){ //exclusive for expertise -> furtherskill	
										$output[$field_app_form.'_act']=$options[ $option.'_'.$field_app_form.'_act' ];
									} else{
										$output[$field_app_form.'_act']=$options[ $option.'_'.$field_app_form.'_act' ];
										$output[$field_app_form.'_man']=$options[ $option.'_'.$field_app_form.'_man' ];	
									}
									
									if($isrec[$issite.'enable_recruitment'] == 'on' && substr($field_app_form,0,6) == 'Title_'){
										//get label for new recruitment
										$qCustomfields_arr = $wpdb->get_results( "SELECT * FROM $table_ps_customfields WHERE site_id = $x ", ARRAY_A );
										foreach ( $qCustomfields_arr as $index => $cf_info ) {
											//filter to not push Title_profileOptionValue
											
											if($cf_info['customfieldsId'] == $field_app_form ){
												$temp_name = $cf_info['name'] === '' ? $cf_info['label'] : $cf_info['name'];
												$output[$field_app_form.'_cflabel']=$temp_name;
											} 
										}	
									}										
								}
								//add custom setting
								if($option == $issite.'personaldata')$output['diverse_act']=$options[$issite.'personaldata_diverse_act'];
								if($option == $issite.'personaldata')$output['expectedsalary_text']=$options[$issite.'personaldata_expectedsalary_text'];
							}	
						}					
					} else if( $section == 'prosolwpclient_privacypolicy' || $section == 'prosolwpclient_joblist' ){
						$output=array();
						$output['label']=$options[ $option ];
						$output[ 'activated' ]=$options[ $option.'_act' ];	
					} else if( $section == 'prosolwpclient_designtemplate' ){						
						if( in_array($option,$destemplist1) ){
							$output=array();
							$output['field_list']=$fields_section[$option];
							//get site from option
							$chksite=substr($option,0,4);
							if($chksite=='site'){
								$pos = strpos($option, '_');
								$getsite= substr($option,0,$pos);
								$issite = $getsite.'_';		
							}else{
								$issite = '';		
							}
							foreach($fields_section[$option] as $field_des_template){						
								if(in_array($option,$destemplist3) ){										
									$output[$field_des_template]=$options[$issite.'desresult'.$field_des_template.'_act' ];		
									if($field_des_template=='customer'){
										$output['customer_text'] = $options[$issite.'desresult'.$field_des_template.'_text' ];
									}									
								} else if(in_array($option,$destemplist4) ){
									$output[$field_des_template]=$options[$issite.'desdetails'.$field_des_template.'_act' ];
									if($field_des_template=='customer'){
										$output['customer_text'] = $options[$issite.'desdetails'.$field_des_template.'_text' ];
									}
								}
							
							}
						} else if(in_array($option,$destemplist2)) {	
							$output=array();						
							$output['label' ]=$options[ $option ];	
							$output['activated' ]=$options[ $option.'_act' ];		
						} else if(in_array($option,$destemplist5)) {	
							$output=array(); 		
							$output['file' ]=$options[ $option.'file' ];	
							$output['name' ]=$options[ $option.'name' ];  
						} else {
							$output = $options[ $option ];	 
						}							
					} else if($section == 'prosolwpclient_additionalsite'){	
						$output=array();
						$output['name' ]=$options[ $option ];	
						// remove notice undefined index in debugging
						$getisset = isset($options[ $option.'_urlid' ]) ? $options[ $option.'_urlid' ] : '';
						$output['urlid' ]=$getisset;	
						$totlen=strlen($option);
						$output['siteid' ]=substr($option,7,$totlen);						
					} else{ 
						$output = $options[ $option ];
					}
					return $output;
				}

				return $default;
			}

			/**
			 * Show navigations as tab
			 *
			 * Shows all the settings section labels as tab
			 */
			function proSol_showNavigation() {
				$html = '<h2 class="nav-tab-wrapper">';

				foreach ( $this->settings_sections as $tab ) {
					$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
				}

				$html .= '</h2>';

				echo $html;
			}

			/**
			 * Show the section settings forms
			 *
			 * This function displays every sections in a different form
			 */
			function proSol_showForms() {
				?>
				<div class="metabox-holder">
					<?php foreach ( $this->settings_sections as $form ) { ?>
						<div id="<?php echo $form['id']; ?>" class="group" style="display: none;">
							<form method="post" action="options.php" class="prosolsettingapiform" >
								<?php
									do_action( 'prosolwpclient_setting_top_' . $form['id'], $form );
									settings_fields( $form['id'] );
									do_settings_sections( $form['id'] );
									do_action( 'prosolwpclient_setting_bottom_' . $form['id'], $form );
								?>
								<div style="padding-left: 10px">
									<?php submit_button(); ?>
								</div>
							</form>
						</div>
					<?php } ?>
				</div>
				<?php
			}

		}
	endif;
