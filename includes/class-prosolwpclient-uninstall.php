<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}
?>
<?php

    /**
     * Fired during plugin proSol_uninstall
     *
     * @link       https://www.prosolution.com
     * @since      1.0.0
     *
     * @package    Prosolwpclient
     * @subpackage Prosolwpclient/includes
     */

    /**
     * Fired during plugin proSol_uninstall.
     *
     * This class defines all code necessary to run during the plugin's deactivation.
     *
     * @since      1.0.0
     * @package    Prosolwpclient
     * @subpackage Prosolwpclient/includes
     * @author     ProSolution <helpdesk@prosolution.com>
     */
    class CBXProSolWpClient_Uninstall
    {

        /**
         * Short Description. (use period)
         *
         * Long Description.
         *
         * @since    1.0.0
         */
        public static function proSol_uninstall(){


	        global $wpdb;global $prosol_prefix;

	        $settings             = new CBXProSolWpClient_Settings_API( PROSOLWPCLIENT_PLUGIN_NAME, PROSOLWPCLIENT_PLUGIN_VERSION );


	        $delete_global_config = $settings->proSol_get_option( 'delete_global_config', 'prosolwpclient_tools', 'yes' );
			
			$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name IN ('prosolwpclient_isnewapi', 'prosolwpclient_encryptionkey')" );

			if ( $delete_global_config == 'yes' )
	        {
				// Project 1440, remove cron jobs
				$validsite=intval(get_option('prosolwpclient_additionalsite')['valids']);
				for($x=0;$x<=$validsite;$x++){
					if ( wp_next_scheduled( 'wp_ajax_proSol_dailytask_tableJobs', $x ) ) {
						wp_unschedule_hook('wp_ajax_proSol_dailytask_tableJobs',  $x);
					}
				}	
					
		        //delete plugin global options
		        $prefix               = 'prosolwpclient_';
		        $wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '{$prefix}%'" );

		        //delete tables created by this plugin
		        $table_name = array();

		        //tables
		        $table_name[] = $table_ps_logs_activity      = $prosol_prefix . 'logs_activity';
		        $table_name[] = $table_ps_setting            = $prosol_prefix . 'setting';
				$table_name[] = $table_ps_jobs               = $prosol_prefix . 'jobs'; // project 1440
				$table_name[] = $table_ps_jobstamp           = $prosol_prefix . 'jobstamp'; // project 1440
		        $table_name[] = $table_ps_country            = $prosol_prefix . 'country';
		        $table_name[] = $table_ps_office             = $prosol_prefix . 'office';
		        $table_name[] = $table_ps_agent              = $prosol_prefix . 'agent';
		        $table_name[] = $table_ps_workpermit         = $prosol_prefix . 'workpermit';
		        $table_name[] = $table_ps_staypermit         = $prosol_prefix . 'staypermit';
		        $table_name[] = $table_ps_availability       = $prosol_prefix . 'availability';
		        $table_name[] = $table_ps_federal            = $prosol_prefix . 'federal';
		        $table_name[] = $table_ps_marital            = $prosol_prefix . 'marital';
		        $table_name[] = $table_ps_title              = $prosol_prefix . 'title';
		        $table_name[] = $table_ps_skillgroup         = $prosol_prefix . 'skillgroup';
		        $table_name[] = $table_ps_skill              = $prosol_prefix . 'skill';
		        $table_name[] = $table_ps_skillrate          = $prosol_prefix . 'skillrate';
		        $table_name[] = $table_ps_professiongroup    = $prosol_prefix . 'professiongroup';
		        $table_name[] = $table_ps_profession         = $prosol_prefix . 'profession';
		        $table_name[] = $table_ps_education          = $prosol_prefix . 'education';
		        $table_name[] = $table_ps_educationlookup    = $prosol_prefix . 'educationlookup';
		        $table_name[] = $table_ps_recruitmentsource  = $prosol_prefix . 'recruitmentsource';
		        $table_name[] = $table_ps_qualification      = $prosol_prefix . 'qualification';
		        $table_name[] = $table_ps_qualificationeval  = $prosol_prefix . 'qualificationeval';
		        $table_name[] = $table_ps_filecategoryemp    = $prosol_prefix . 'filecategoryemp';
		        $table_name[] = $table_ps_contract           = $prosol_prefix . 'contract';
		        $table_name[] = $table_ps_employment         = $prosol_prefix . 'employment';
		        $table_name[] = $table_ps_experienceposition = $prosol_prefix . 'experienceposition';
		        $table_name[] = $table_ps_operationarea      = $prosol_prefix . 'operationarea';
		        $table_name[] = $table_ps_nace               = $prosol_prefix . 'nace';
		        $table_name[] = $table_ps_isced              = $prosol_prefix . 'isced';
				$table_name[] = $table_ps_customfields       = $prosol_prefix . 'customfields';
				$table_name[] = $table_ps_worktime           = $prosol_prefix . 'worktime';
				$table_name[] = $table_ps_jobcustomfields    = $prosol_prefix . 'jobcustomfields';

		        $sql = "DROP TABLE IF EXISTS " . implode(', ', $table_name);
		        $val = $wpdb->query($sql);
				
	        }

        }

    }
