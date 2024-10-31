<?php
	/**
	 * Provide a dashboard view for the plugin
	 *
	 * This file is used to markup the admin setting page
	 *
	 * @link       https://www.prosolwpclient.net
	 * @since      1.0.0
	 *
	 * @package    prosolwpclient
	 * @subpackage prosolwpclient/templates
	 */

	if ( ! defined( 'WPINC' ) )
	{
		die;
	}
?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_html_e( 'ProSolution: Setting', 'prosolwpclient' ); ?></h2>
	<?php 
		$addsite=get_option( 'prosolwpclient_additionalsite' ); 
		$totalsite = $addsite['valids'];
	?>
	<form class='form-horizontal' method='POST' action='admin.php?page=prosolwpclientsettings#prosolwpclient_site'>
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

    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="inside">
							<?php
							
								$this->settings_api->proSol_showNavigation();
								$this->settings_api->proSol_showForms();
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div> <!-- .wrap -->