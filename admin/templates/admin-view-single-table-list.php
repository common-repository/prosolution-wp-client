<?php
    /**
     * Provide a dashboard view for the plugin
     *
     * This file is used to markup the table listing
     *
     *
     * @package    prosolwpclient
     * @subpackage v/admin/templates
     */

    if (!defined('WPINC')) {
        die;
    }
?>

<?php
    $issite = CBXProSolWpClient_Helper::proSol_getSitecookie();
	$is_api_setup =  $show_sync = CBXProSolWpClient_Helper::proSol_isApiSetup($issite);
	$api_config = CBXProSolWpClient_Helper::proSol_getApiConfig($issite);

	$table_displayname  = ucfirst( $table_name );
	$list_table_name    = 'CBXProSolWpClient' . $table_displayname . '_List_Table';
	$pswp_table_list    = new $list_table_name();
	$pswp_sync_time_arr = get_option( 'prosolwpclient_sync_time', array() );
	if ( $pswp_sync_time_arr != '' ) {
		$pswp_sync_time_arr = maybe_unserialize( $pswp_sync_time_arr );
	}
    
	$redirect_url = admin_url('admin.php?page=prosolutionoverview&table_view=1&task=sync&table=' . $table_name);
	$sync_time = array_key_exists( $issite.$table_name, $pswp_sync_time_arr ) ? CBXProSolWpClient_Helper::proSol_dateReadableFormat( $pswp_sync_time_arr[ $issite.$table_name ] ) : '';
?>

    <div class="wrap">
        <h2>
            <?php echo esc_html__('Table: ', 'prosolwpclient') . $table_displayname; ?>
            <?php
                if($table_name != 'Setting' && $table_name != 'jobs' && $table_name != 'jobstamp') {
                	if($show_sync){
		                echo '<a class="button button-primary button-sync-table " href="'.$redirect_url.'" data-tablename="' . $table_name . '" data-redirect="1">' . esc_attr__('Sync', 'prosolwpclient') . '</a>';
					}
                    echo '<span style="font-size: 12px; margin-left: 20px; font-weight: normal" class="table-last_updatetime">'.esc_html__('Last sync time: ', 'prosolwpclient').' <i id="synctime-' . $table_name . '">' . $sync_time . '</i></span>';
                }
            ?>
        </h2>

        <?php 
            $addsite=get_option( 'prosolwpclient_additionalsite' ); 
            $totalsite = $addsite['valids'];        
        ?>
        <form class='form-horizontal' method='POST' action='admin.php?page=prosolutionoverview&table_view=1&table=<?php echo $table_name?>'>
            <select type="select" name="selsite" id="selsite">
                <option value="0">master</option>
                <?php for($x=1; $x<=$totalsite; $x++){    ?>
                    <option value="<?php echo $x ?>" ><?php echo $addsite['addsite'.$x.'_urlid'] ?> - <?php echo $addsite['addsite'.$x] ?></option>

                <?php } ?>
            </select>
            <?php wp_nonce_field( 'prosolwpclient_formsubmit', 'prosolwpclient_token' );?>
            <input type="submit" name="submitselsite" id="submitselsite" class="btn btn-default btn-primary" value="<?php echo esc_html__('change site', 'prosolwpclient'); ?>">
        </form>	        
        
        <!--- project 1440 --->
        <?php if($table_name == 'jobs'){ ?>
            <style>  
                .prosolwpclient_singletablejobs table.widefat td,
                .prosolwpclient_singletablejobs table.widefat th  {
                    width: 200px;
                    overflow:hidden !important;
                }
            </style>            
        <?php } ?>

        <?php
            $pswp_table_list->prepare_items();
        ?>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content" class="prosolwpclient_singletablejobs">
                    <div class="meta-box-sortables ui-sortable">
                        <div class="postbox">
                            <div class="inside">
                                <?php $pswp_table_list->views(); ?>
                                <form id="pswp_table_listing" method="post">
                                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                                    <?php $pswp_table_list->search_box('Search List', 'prosolwpclient'); ?>
                                    <?php $pswp_table_list->display() ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
        <!-- #poststuff -->
    </div>
<?php
