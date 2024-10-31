<h2><?php esc_html_e('ProSolution Dashboard ', 'prosolwpclient'); ?></h2>
<?php
    $issite = CBXProSolWpClient_Helper::proSol_getSitecookie();
	$is_api_setup = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
    $api_config = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);
?>

<div class="wrap">
    <h1>
        <?php esc_attr_e('Database Synchronization', 'prosolwpclient'); ?>
        <?php 
            $addsite=get_option( 'prosolwpclient_additionalsite' );  
            $totalsite = $addsite['valids'];

            $selsite='0';
            if(isset($_COOKIE['selsite'])){
                $selsite=$_COOKIE['selsite']==0 ? '0' : $_COOKIE['selsite'];
            }
        ?>
        <form class='form-horizontal' method='POST' action='admin.php?page=prosolutionoverview'>
            <select type="select" name="selsite" id="selsite">
                <option value="0">master</option>
                <?php if($totalsite!=0){
                    for($x=1; $x<=$totalsite; $x++){    ?>
                        <option value="<?php echo $x ?>" ><?php echo $addsite['addsite'.$x.'_urlid'] ?> - <?php echo $addsite['addsite'.$x] ?></option>
                    <?php }     
                } ?>
            </select>
            <?php wp_nonce_field( 'prosolwpclient_formsubmit', 'prosolwpclient_token' );?>
            <input type="submit" name="submitselsite" id="submitselsite" class="btn btn-default btn-primary" value="<?php echo esc_html__('change site', 'prosolwpclient'); ?>">
        </form>	
	</h1>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <h2>
                            <span><?php esc_attr_e('Data Tables', 'prosolwpclient'); ?></span>
                            <?php
								if($is_api_setup){
									echo '<a href="' . admin_url('admin.php?page=prosolutionoverview&task=syncall') . '" style="float: right;"  class="button button-primary" id="prosolsyncall">'
									     . esc_html__('Sync All', 'prosolwpclient') . '</a>';
								}
                            ?>
                        </h2>
                        <div class="inside">
                            <table class="widefat striped">
                                <thead>
                                <tr>
                                    <th class="row-title"><?php esc_attr_e('Table Name', 'prosolwpclient'); ?></th>
                                    <th colspan="2"><?php esc_attr_e('Action', 'prosolwpclient'); ?></th>
                                    <th><?php esc_attr_e('Last Sync', 'prosolwpclient'); ?></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php       
                                    $pswp_sync_time_arr = get_option('prosolwpclient_sync_time', array());
                                    
                                    $show_sync = $is_api_setup;
                                    if ($pswp_sync_time_arr != '') {
                                        $pswp_sync_time_arr = maybe_unserialize($pswp_sync_time_arr);
                                    }

                                    $all_tables_arr = CBXProSolWpClient_Helper::proSol_allTablesArr();
                                    $overview_page_url = admin_url('admin.php?page=prosolutionoverview');
                                    $url_params = array('table_view' => 1); 
                                    
                                    foreach ($all_tables_arr as $table_key => $table_name) { 
                                        $sync_time = array_key_exists($table_key, $pswp_sync_time_arr) ? CBXProSolWpClient_Helper::proSol_dateReadableFormat($pswp_sync_time_arr[$issite.$table_key]) : '';
                                        $url_params['table'] = $table_key;
                                        $sync_url_params = $url_params;
                                        $sync_url_params['task'] = 'sync';

                                        $sync_html = '';
                                        if ($table_key != 'setting' && $table_key != 'jobstamp') {
                                            //' . add_query_arg($sync_url_params, $overview_page_url) . '
                                            $sync_js_class = ($show_sync) ? 'prosolsyncsingle' : 'prosolsyncsingle_d';
                                            $sync_html = '<a href="' . admin_url('admin.php?page=prosolutionoverview&table_view=1&table=' . $table_key) . '" data-redirect="0" class="button  ' . $sync_js_class . '" data-tablename="' . $table_key . '" data-synctype="all">'
                                                         . esc_attr__('Sync', 'prosolwpclient') . '</a>';
                                            // project 1440             
                                            if($table_key == 'jobs'){ 
                                                global $wpdb;global $prosol_prefix;
                                                $table_ps_josbstamp = $prosol_prefix . 'jobstamp';

                                                $where = " WHERE site_id='$selsite'";    
                                                $sql_select = "SELECT add_date FROM $table_ps_josbstamp  ";

                                                $qjobstamp = $wpdb->get_results("$sql_select $where", 'ARRAY_A');
                                                $recordcount = count($qjobstamp); 
                                                if($recordcount == 0){
                                                    $disabled='disabled';
                                                } else{
                                                    $disabled='';
                                                    $sync_time=CBXProSolWpClient_Helper::proSol_dateReadableFormat($qjobstamp[0]['add_date']);
                                                } 
                                                $sync_html = '<a href="' . admin_url('admin.php?page=prosolutionoverview&table_view=1&table=' . $table_key) . '" data-redirect="0" class="button  ' . $sync_js_class . '" data-tablename="' . $table_key . '" data-synctype="all">'
                                                         . esc_attr__('SynchAll', 'prosolwpclient') . '</a>';
                                                $sync_html .= '<a href="' . admin_url('admin.php?page=prosolutionoverview&table_view=1&table=' . $table_key) . '" data-redirect="0" class="button  ' . $sync_js_class . ' syncjobsch" data-tablename="' . $table_key . '" data-synctype="changes"' . $disabled . ' >'
                                                         . esc_attr__('SynchChanges', 'prosolwpclient') . '</a>';
                                            }
                                        }

                                        echo '<tr>
                                                <td class="row-title">
                                                    <label for="tablecell">' . esc_attr__($table_name, 'prosolwpclient') . '</label>
                                                </td>
                                                <td>
                                                    <a href="' . add_query_arg($url_params, $overview_page_url) . '" class="">' . esc_attr__('View Data', 'prosolwpclient') . '</a>
                                                </td>
                                                <td>
                                                    ' . $sync_html . '
                                                </td>
                                                <td class="synctime-common" id="synctime-' . $table_key . '">' . $sync_time . '</td>
                                            </tr>';
                                    }
                                ?>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th class="row-title"><?php esc_attr_e('Table Name', 'prosolwpclient'); ?></th>
                                    <th colspan="2"><?php esc_attr_e('Action', 'prosolwpclient'); ?></th>
                                    <th><?php esc_attr_e('Last Sync', 'prosolwpclient'); ?></th>
                                </tr>
                                </tfoot>
                            </table>
                            <br class="clear"/>
                        </div>
                    </div>
                </div>
            </div>
            <!-- post-body-content -->

            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <h2>
                            <span><?php esc_attr_e('Logs and Activity', 'prosolwpclient'); ?></span>
                            <a title="<?php esc_html_e('Refresh/reload Log', 'prosolwpclient'); ?>"
                               href="<?php echo add_query_arg('clear_log', 2, $_SERVER['REQUEST_URI']) ?>"
                               style="float: right;" class="button prosolrefreshlog_reset" data-cleartype="2">
                                <span class="dashicons dashicons-update"></span>
                            </a>
                            <a title="<?php esc_html_e('Delete Log and reload', 'prosolwpclient'); ?>"
                               href="<?php echo add_query_arg('clear_log', 1, $_SERVER['REQUEST_URI']) ?>"
                               style="margin-left: 20px; float: right;" class="button prosolrefreshlog_reset"
                               data-cleartype="1" >
                                <span class="dashicons dashicons-trash"></span>
                            </a>

                        </h2>

                        <?php
                            global $wpdb;global $prosol_prefix;
                            $table_ps_logs_activity = $prosol_prefix . 'logs_activity';
                            $table_ps_users = $wpdb->prefix. 'users';

                            $join = " LEFT JOIN $table_ps_users users ON users.ID = log.add_by ";
                            $where = " WHERE log.site_id='$selsite' AND log.add_by in (log.add_by, 0)";    
                            $sql_select = "SELECT log.*, users.display_name FROM $table_ps_logs_activity as log  ";

                            $page = 1;
                            $perpage = 30;

                            $start_point = ($page * $perpage) - $perpage;
                            $limit_sql = "LIMIT";
                            $limit_sql .= ' ' . $start_point . ',';
                            $limit_sql .= ' ' . $perpage;

                            $order_by = 'log.id';
                            $order = 'desc';
                            $sortingOrder = " ORDER BY $order_by $order ";

                            $logs_activity_data = $wpdb->get_results("$sql_select $join $where $sortingOrder $limit_sql", 'ARRAY_A');
                        ?>

                        <div class="inside">
                            <ul id="prosolwpclient_log">
                                <?php
                                    foreach ($logs_activity_data as $index => $single_activity) {
                                        $activity_msg = '';
                                        if ($single_activity['activity_type'] == 'sync') {
                                            $activity_msg = '<strong>' . ucfirst(stripslashes($single_activity['activity_msg'])) . '</strong>' . ' table ' . '<strong>synced</strong>';
                                        }
                                        if ($single_activity['activity_type'] == 'clear') {
                                            $activity_msg = ucfirst(stripslashes($single_activity['activity_msg']));
                                        }

                                        if (current_user_can('edit_user', $single_activity['add_by'])) {
                                            $add_by = '<a target="_blank" href="' . get_edit_user_link($single_activity['add_by']) . '">' . stripslashes($single_activity['display_name']) . '</a>';
                                        } else {
                                            $add_by = '<a href="#" target="_blank"' . stripslashes($single_activity['display_name']) . '</a>';
                                        }

                                        echo '<li>' . $activity_msg . esc_html__(' at ', 'prosolwpclient') . CBXProSolWpClient_Helper::proSol_dateReadableFormat($single_activity['add_date']) . '
                                                ' . esc_html__(' by ', 'prosolwpclient') . $add_by . '
                                            </li>';
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br class="clear"/>
    </div>
</div> <!-- .wrap -->
<br class="clear"/>

<script type="text/javascript">
   
</script>
