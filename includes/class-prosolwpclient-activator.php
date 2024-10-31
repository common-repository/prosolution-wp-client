<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}
?>
<?php

    /**
     * Fired during plugin activation
     *
     * @link       https://www.prosolution.com
     * @since      1.0.0
     *
     * @package    Prosolwpclient
     * @subpackage Prosolwpclient/includes
     */

    /**
     * Fired during plugin activation.
     *
     * This class defines all code necessary to run during the plugin's activation.
     *
     * @since      1.0.0
     * @package    Prosolwpclient
     * @subpackage Prosolwpclient/includes
     * @author     ProSolution <helpdesk@prosolution.com>
     */
    class CBXProSolWpClient_Activator
    {
        /**
         * Short Description. (use period)
         *
         * Long Description.
         *
         * @since    1.0.0
         */ 
        public static function proSol_activate()
        {   
          
        
            //check if can activate plugin
            if (!current_user_can('activate_plugins')) {
                return;
            }

            global $wpdb;global $prosol_prefix;
            
            $charset_collate = '';
            if ($wpdb->has_cap('collation')) {
                if (!empty($wpdb->charset)) {
                    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
                }
                if (!empty($wpdb->collate)) {
                    $charset_collate .= " COLLATE $wpdb->collate";
                }
            }

            $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
            check_admin_referer("activate-plugin_{$plugin}");

            //tables
	        $table_ps_logs_activity      = $prosol_prefix . 'logs_activity';
	        $table_ps_setting            = $prosol_prefix . 'setting';
          $table_ps_jobs               = $prosol_prefix . 'jobs';
          $table_ps_logs_jobstamp      = $prosol_prefix . 'jobstamp';
	        $table_ps_country            = $prosol_prefix . 'country';
	        $table_ps_office             = $prosol_prefix . 'office';
	        $table_ps_agent              = $prosol_prefix . 'agent';
	        $table_ps_workpermit         = $prosol_prefix . 'workpermit';
	        $table_ps_staypermit         = $prosol_prefix . 'staypermit';
	        $table_ps_availability       = $prosol_prefix . 'availability';
	        $table_ps_federal            = $prosol_prefix . 'federal';
	        $table_ps_marital            = $prosol_prefix . 'marital';
	        $table_ps_title              = $prosol_prefix . 'title';
	        $table_ps_skillgroup         = $prosol_prefix . 'skillgroup';
	        $table_ps_skill              = $prosol_prefix . 'skill';
	        $table_ps_skillrate          = $prosol_prefix . 'skillrate';
	        $table_ps_professiongroup    = $prosol_prefix . 'professiongroup';
	        $table_ps_profession         = $prosol_prefix . 'profession';
	        $table_ps_education          = $prosol_prefix . 'education';
	        $table_ps_educationlookup    = $prosol_prefix . 'educationlookup';
	        $table_ps_recruitmentsource  = $prosol_prefix . 'recruitmentsource';
	        $table_ps_qualification      = $prosol_prefix . 'qualification';
	        $table_ps_qualificationeval  = $prosol_prefix . 'qualificationeval';
	        $table_ps_filecategoryemp    = $prosol_prefix . 'filecategoryemp';
	        $table_ps_contract           = $prosol_prefix . 'contract';
	        $table_ps_employment         = $prosol_prefix . 'employment';
	        $table_ps_experienceposition = $prosol_prefix . 'experienceposition';
	        $table_ps_operationarea      = $prosol_prefix . 'operationarea';
	        $table_ps_nace               = $prosol_prefix . 'nace';
          $table_ps_isced              = $prosol_prefix . 'isced';
          $table_ps_customfields       = $prosol_prefix . 'customfields';
          $table_ps_worktime           = $prosol_prefix . 'worktime';
          $table_ps_jobcustomfields       = $prosol_prefix . 'jobcustomfields';

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            //create table for prosolution logs_activity
            $table_ps_logs_activity_sql = "CREATE TABLE $table_ps_logs_activity (
                          id bigint(11) unsigned NOT NULL AUTO_INCREMENT,
                          activity_msg varchar(255) NOT NULL DEFAULT '',
                          activity_type varchar(20) NOT NULL DEFAULT '',
                          add_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who added this, if uest zero',
                          add_date datetime DEFAULT NULL COMMENT 'add date',
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY id (id,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_logs_activity_sql);

            //create table for prosolution setting
            $table_ps_setting_sql = "CREATE TABLE $table_ps_setting (
                          name varchar(50) NOT NULL,
                          value varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (name,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_setting_sql);
            
            //create table for prosolution jobs, project 1440
            $table_ps_jobs_sql = "CREATE TABLE $table_ps_jobs (
              jobid varchar(50) NOT NULL,
              jobname varchar(255) NOT NULL,
              jobstartdate varchar(255) NOT NULL,
              jobproject_id varchar(255) NOT NULL DEFAULT '0',
              jobproject_name varchar(255) NOT NULL,
              empgroup_id varchar(255) NOT NULL DEFAULT '0',
              empgroup_name varchar(255) NOT NULL,
              empgroup_type varchar(255) NOT NULL,
              federalid varchar(255) NOT NULL DEFAULT '0',
              federalname varchar(255) NOT NULL,
              categoryid varchar(255) NOT NULL DEFAULT '0',
              categoryname varchar(255) NOT NULL,
              customformat_id varchar(255) NOT NULL DEFAULT '0',
              customformat_name varchar(255) NOT NULL,
              countryid varchar(5) NOT NULL,
              countryname varchar(255) NOT NULL,
              officeid varchar(255) NOT NULL DEFAULT '0',
              officename varchar(255) NOT NULL,
              worktimeid varchar(255) NOT NULL DEFAULT '0',
              worktimename varchar(255) NOT NULL,
              workingplace varchar(255) NOT NULL,
              zipcode varchar(255) NOT NULL,
              isced varchar(255) NOT NULL DEFAULT '0',
              isced_name varchar(255) NOT NULL,
              max_distance varchar(255) NOT NULL,
              exp_year varchar(255) NOT NULL DEFAULT '0',   
              jobrefid varchar(255) NOT NULL,
              custrefid varchar(255) NOT NULL,
              custreflogo varchar(255) NOT NULL,           
              agentid varchar(255) NOT NULL,
              agentname varchar(255) NOT NULL,
              showagentphoto varchar(255) NOT NULL,
              showsignature varchar(255) NOT NULL,
              showcustname varchar(255) NOT NULL,
              showcustlogo varchar(255) NOT NULL,
              qualificationid varchar(255) NOT NULL,
              untildate varchar(255) NOT NULL,
              publishdate varchar(255) NOT NULL,
              salarytext varchar(255) NOT NULL,
              profession longtext NOT NULL,
              skills longtext NOT NULL,
              question longtext NOT NULL,
              portal longtext NOT NULL,
              customer longtext NOT NULL,
              recruitlink longtext NOT NULL DEFAULT '',
              textfieldlabel_1 varchar(150) NOT NULL,
              textfieldlabel_2 varchar(150) NOT NULL,
              textfieldlabel_3 varchar(150) NOT NULL,
              textfieldlabel_4 varchar(150) NOT NULL,
              textfieldlabel_5 varchar(150) NOT NULL,
              textfieldlabel_6 varchar(150) NOT NULL,
              textfieldlabel_7 varchar(150) NOT NULL,
              textfieldlabel_8 varchar(150) NOT NULL,
              textfieldlabel_9 varchar(150) NOT NULL,
              textfieldlabel_10 varchar(150) NOT NULL,
              textfieldlabel_11 varchar(150) NOT NULL,
              textfieldlabel_12 varchar(150) NOT NULL,
              textfieldlabel_13 varchar(150) NOT NULL,
              textfieldlabel_14 varchar(150) NOT NULL,
              textfieldlabel_15 varchar(150) NOT NULL,
              textfieldlabel_16 varchar(150) NOT NULL,
              textfieldlabel_17 varchar(150) NOT NULL,
              textfieldlabel_18 varchar(150) NOT NULL,
              textfieldlabel_19 varchar(150) NOT NULL,
              textfieldlabel_20 varchar(150) NOT NULL,
              textfield_1 longtext NOT NULL,
              textfield_2 longtext NOT NULL,
              textfield_3 longtext NOT NULL,
              textfield_4 longtext NOT NULL,
              textfield_5 longtext NOT NULL,
              textfield_6 longtext NOT NULL,
              textfield_7 longtext NOT NULL,
              textfield_8 longtext NOT NULL,
              textfield_9 longtext NOT NULL,
              textfield_10 longtext NOT NULL,
              textfield_11 longtext NOT NULL,
              textfield_12 longtext NOT NULL,
              textfield_13 longtext NOT NULL,
              textfield_14 longtext NOT NULL,
              textfield_15 longtext NOT NULL,
              textfield_16 longtext NOT NULL,
              textfield_17 longtext NOT NULL,
              textfield_18 longtext NOT NULL,
              textfield_19 longtext NOT NULL,
              textfield_20 longtext NOT NULL,
              textfieldlabel_21 text NOT NULL,
              textfieldlabel_22 text NOT NULL,
              textfieldlabel_23 text NOT NULL,
              textfieldlabel_24 text NOT NULL,
              textfieldlabel_25 text NOT NULL,
              textfieldlabel_26 text NOT NULL,
              textfieldlabel_27 text NOT NULL,
              textfieldlabel_28 text NOT NULL,
              textfieldlabel_29 text NOT NULL,
              textfieldlabel_30 text NOT NULL,
              textfield_21 text NOT NULL,
              textfield_22 text NOT NULL,
              textfield_23 text NOT NULL,
              textfield_24 text NOT NULL,
              textfield_25 text NOT NULL,
              textfield_26 text NOT NULL,
              textfield_27 text NOT NULL,
              textfield_28 text NOT NULL,
              textfield_29 text NOT NULL,
              textfield_30 text NOT NULL,
              site_id varchar(190) NOT NULL DEFAULT '0',
              PRIMARY KEY  (jobid,site_id)
            ) "; 
            
            dbDelta($table_ps_jobs_sql);
            
            //create table for prosolution jobstamp
            $table_ps_logs_jobstamp_sql = "CREATE TABLE $table_ps_logs_jobstamp (
              id bigint(11) unsigned NOT NULL AUTO_INCREMENT, 
              add_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who added this, if uest zero',
              add_date datetime DEFAULT NULL COMMENT 'add date',
              site_id bigint(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (id,site_id)
            ) $charset_collate; ";

            dbDelta($table_ps_logs_jobstamp_sql);

            //create table for prosolution country
            $table_ps_country_sql = "CREATE TABLE $table_ps_country (
                          countryCode varchar(2) NOT NULL,
                          name varchar(50) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (countryCode,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_country_sql);

            //create table for prosolution office
            $table_ps_office_sql = "CREATE TABLE $table_ps_office (
                          officeId varchar(10) NOT NULL,
                          mandant VARCHAR(10) NOT NULL,
                          name VARCHAR(50) NOT NULL,
                          name2 VARCHAR(50) NOT NULL,
                          street VARCHAR(100) NOT NULL,
                          zip VARCHAR(10) NOT NULL,
                          city VARCHAR(50) NOT NULL,
                          phone1 VARCHAR(50) NOT NULL,
                          phone2 VARCHAR(50) NOT NULL,
                          fax VARCHAR(50) NOT NULL,
                          email VARCHAR(50) NOT NULL,
                          homepage VARCHAR(50) NOT NULL,
                          customtext VARCHAR(50) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (officeId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_office_sql);

            //create table for prosolution agent
            $table_ps_agent_sql = "CREATE TABLE $table_ps_agent (
                          agentId varchar(50) NOT NULL,
                          name VARCHAR(50) NOT NULL,
                          gender VARCHAR(2) NOT NULL,
                          position VARCHAR(50) NOT NULL,
                          email VARCHAR(50) NOT NULL,
                          phone1 VARCHAR(50) NOT NULL,
                          phone2 VARCHAR(50) NOT NULL,
                          phonemobile VARCHAR(50) NOT NULL,
                          customtext VARCHAR(50) NOT NULL,
                          signatureuuid VARCHAR(50) NOT NULL,
                          photouuid VARCHAR(50) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (agentId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_agent_sql);

            //create table for prosolution workpermit
            $table_ps_workpermit_sql = "CREATE TABLE $table_ps_workpermit (
                          workpermitId varchar(10) NOT NULL,
                          name varchar(50) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (workpermitId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_workpermit_sql);

            //create table for prosolution staypermit
            $table_ps_staypermit_sql = "CREATE TABLE $table_ps_staypermit (
                          staypermitId varchar(10) NOT NULL,
                          name varchar(50) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (staypermitId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_staypermit_sql);

            //create table for prosolution availability
            $table_ps_availability_sql = "CREATE TABLE $table_ps_availability (
                          availabilityId varchar(10) NOT NULL,
                          name varchar(50) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (availabilityId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_availability_sql);

            //create table for prosolution federal
            $table_ps_federal_sql = "CREATE TABLE $table_ps_federal (
                          federalId varchar(10) NOT NULL,
                          name varchar(50) NOT NULL,
                          countryCode varchar(2) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (federalId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_federal_sql);


            //create table for prosolution marital
            $table_ps_marital_sql = "CREATE TABLE $table_ps_marital (
                          maritalId varchar(10) NOT NULL,
                          name varchar(30) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (maritalId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_marital_sql);


            //create table for prosolution title
            $table_ps_title_sql = "CREATE TABLE $table_ps_title (
                          titleId varchar(10) NOT NULL,
                          name varchar(30) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (titleId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_title_sql);

            //create table for prosolution skillgroup
            $table_ps_skillgroup_sql = "CREATE TABLE $table_ps_skillgroup (
                          skillgroupId varchar(10) NOT NULL,
                          name varchar(50) NOT NULL,
                          professiongroups varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (skillgroupId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_skillgroup_sql);

            //create table for prosolution skill
            $table_ps_skill_sql = "CREATE TABLE $table_ps_skill (
                          skillId varchar(10) NOT NULL,
                          name varchar(50) NOT NULL,
                          skillgroupId varchar(10) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (skillId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_skill_sql);

            //create table for prosolution skillrate
            $table_ps_skillrate_sql = "CREATE TABLE $table_ps_skillrate (
                          skillrateId INTEGER NOT NULL,
                          name varchar(30) NOT NULL,
                          value varchar(50) NOT NULL,
                          skillgroupId varchar(10) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (skillrateId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_skillrate_sql);

            //create table for prosolution professiongroup
            $table_ps_professiongroup_sql = "CREATE TABLE $table_ps_professiongroup (
                          professiongroupId varchar(10) NOT NULL,
                          name varchar(50) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (professiongroupId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_professiongroup_sql);

            //create table for prosolution profession
            $table_ps_profession_sql = "CREATE TABLE $table_ps_profession (
                          professionId varchar(10) NOT NULL,
                          name varchar(50) NOT NULL,
                          professiongroupId varchar(10) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (professionId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_profession_sql);

            //create table for prosolution education
            $table_ps_education_sql = "CREATE TABLE $table_ps_education (
                          educationId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          hasLookup BIT NOT NULL DEFAULT 0,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (educationId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_education_sql);

            //create table for prosolution educationlookup
            $table_ps_educationlookup_sql = "CREATE TABLE $table_ps_educationlookup (
                          lookupId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          educationId varchar(10) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (lookupId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_educationlookup_sql);

            //create table for prosolution recruitmentsource
            $table_ps_recruitmentsource_sql = "CREATE TABLE $table_ps_recruitmentsource (
                          recruitmentsourceId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (recruitmentsourceId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_recruitmentsource_sql);

            //create table for prosolution qualification
            $table_ps_qualification_sql = "CREATE TABLE $table_ps_qualification (
                          qualificationId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (qualificationId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_qualification_sql);

            //create table for prosolution qualificationeval
            $table_ps_qualificationeval_sql = "CREATE TABLE $table_ps_qualificationeval (
                          qualificationevalId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (qualificationevalId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_qualificationeval_sql);

            //create table for prosolution filecategoryemp
            $table_ps_filecategoryemp_sql = "CREATE TABLE $table_ps_filecategoryemp (
                          filecategoryempId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (filecategoryempId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_filecategoryemp_sql);

            //create table for prosolution contract
            $table_ps_contract_sql = "CREATE TABLE $table_ps_contract (
                          contractId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (contractId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_contract_sql);

            //create table for prosolution employment
            $table_ps_employment_sql = "CREATE TABLE $table_ps_employment (
                          employmentId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (employmentId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_employment_sql);

            //create table for prosolution experienceposition
            $table_ps_experienceposition_sql = "CREATE TABLE $table_ps_experienceposition (
                          experiencepositionId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (experiencepositionId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_experienceposition_sql);

            //create table for prosolution operationarea
            $table_ps_operationarea_sql = "CREATE TABLE $table_ps_operationarea (
                          operationareaId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (operationareaId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_operationarea_sql);

            //create table for prosolution nace
            $table_ps_nace_sql = "CREATE TABLE $table_ps_nace (
                          naceId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (naceId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_nace_sql);

            //create table for prosolution isced
            $table_ps_isced_sql = "CREATE TABLE $table_ps_isced (
                          iscedId varchar(10) NOT NULL,
                          name varchar(100) NOT NULL,
                          site_id bigint(11) NOT NULL DEFAULT '0',
                          PRIMARY KEY (iscedId,site_id)
                        ) $charset_collate; ";

            dbDelta($table_ps_isced_sql);
                
            //create table for prosolution customfields
            $table_ps_customfields_sql = "CREATE TABLE $table_ps_customfields (
              customId varchar(50) NOT NULL,
              customfieldsId varchar(50) NOT NULL,
              name varchar(50) NOT NULL,
              label varchar(50) NOT NULL,
              site_id bigint(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (customId,site_id)
            ) $charset_collate; ";

            dbDelta($table_ps_customfields_sql);

            //create table for prosolution worktime
            $table_ps_worktime_sql = "CREATE TABLE $table_ps_worktime (              
              worktimeId varchar(10) NOT NULL,
              name varchar(50) NOT NULL,
              site_id bigint(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (worktimeId,site_id)
            ) $charset_collate; ";

            dbDelta($table_ps_worktime_sql);

            //create table for prosolution jobcustomfields
            $table_ps_jobcustomfields_sql = "CREATE TABLE $table_ps_jobcustomfields (
              customfield_ID varchar(50) NOT NULL,
              customfield_name varchar(50) NOT NULL,
              site_id bigint(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (customfield_ID,site_id)
            ) $charset_collate; ";

            dbDelta($table_ps_jobcustomfields_sql);

        }

        /**
         * Create pages that the plugin relies on, storing page id's in variables.
         */
        public static function proSol_createPages()
        {
            //pages need to create
            $pages = apply_filters('prosolwpclient_frontend_create_pages', array(
                'frontend_pageid' => array(
                    'slug'    => _x('prosolwpclientfrontend', 'Page slug', 'prosolwpclient'),
                    'title'   => _x('ProSolution Frontend', 'Page title', 'prosolwpclient'),
                    'content' => '[prosolfrontend type="search"]'

                )
            ));
            
            //let's create the pages
	        foreach ( $pages as $field_name => $page ) {

	            self::proSol_createPage( esc_sql( $page['slug'] ), $field_name, $page['title'], $page['content'] );
            }
        }

	    /**
	     * Create a page and store the ID in an option.
	     *
	     * @param mixed  $slug         Slug for the new page
	     * @param string $field_name   Option name to store the page's ID
	     * @param string $page_title   (default: '') Title for the new page
	     * @param string $page_content (default: '') Content for the new page
	     * @param int    $post_parent  (default: 0) Parent for the new page
	     *
	     * @return int page ID
	     */
	    public static function proSol_createPage( $slug, $field_name = '', $page_title = '', $page_content = '' ) {
		    global $wpdb;global $prosol_prefix;

		    $frontend_config = get_option( 'prosolwpclient_frontend' );
        
		    if ( ! is_array( $frontend_config ) || ( is_array( $frontend_config ) && sizeof( $frontend_config ) == 0 ) ) {
			    $frontend_config = array();
		    } else {
			    $frontend_config = maybe_unserialize( $frontend_config );
		    }


		    $option_value = isset( $frontend_config[ $field_name ] ) ? $frontend_config[ $field_name ] : 0;

		    //if post id > 0 and post exists
		    if ( $option_value > 0 && ( $page_object = get_post( $option_value ) ) ) {
			    //if post type is  page and page is  not in state 'pending', 'trash', 'future', 'auto-draft'
			    if ( 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array(
					    'pending',
					    'trash',
					    'future',
					    'auto-draft'
				    ) ) ) {
				    // Valid page is already in place
				    $frontend_config[ $field_name ] = $page_object->ID;
				    self::proSol_addOrUpdate( $frontend_config );

				    return $page_object->ID;
			    }
		    }

		    // post/page doesn't exits ..

		    $valid_page_found = 0;
		    if ( strlen( $page_content ) > 0 ) {
			    // Search for an existing page with the specified page content (typically a shortcode)
			    $valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		    } else {
			    // Search for an existing page with the specified page slug
			    $valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
		    }

		    $valid_page_found = apply_filters( 'prosolwpclient_create_page_id', $valid_page_found, $slug, $page_content );




		    if ( $valid_page_found ) {

			    $frontend_config[ $field_name ] = $valid_page_found;
			    self::proSol_addOrUpdate( $frontend_config );

			    return $valid_page_found;
		    }

		    // Search for a matching valid trashed page
		    if ( strlen( $page_content ) > 0 ) {
			    // Search for an existing page with the specified page content (typically a shortcode)
			    $trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
		    } else {
			    // Search for an existing page with the specified page slug
			    $trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
		    }

		    $page_id = 0;

		    if ( $trashed_page_found ) {
			    $page_id   = $trashed_page_found;
			    $page_data = array(
				    'ID'          => $page_id,
				    'post_status' => 'publish',
			    );
			    wp_update_post( $page_data );
		    } else {
			    $page_data = array(
				    'post_status'    => 'publish',
				    'post_type'      => 'page',
				    'post_author'    => 1,
				    'post_name'      => $slug,
				    'post_title'     => $page_title,
				    'post_content'   => $page_content,
				    'post_parent'    => 0,
				    'comment_status' => 'closed',
			    );
			    $page_id   = wp_insert_post( $page_data );
		    }

		    if ( $page_id > 0 ) {
			    $frontend_config[ $field_name ] = $page_id;
			    $update                                               = self::proSol_addOrUpdate( $frontend_config );
		    }

		    return $page_id;
	    }

	    public static function proSol_addOrUpdate( $value, $name = 'prosolwpclient_frontend' ) {


		    $success = true;


		    if ( get_option( 'prosolwpclient_frontend' ) !== false ) {

			    // The option already exists, so we just update it.
			    $success = update_option( $name, $value );
		    } else {
			    // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
			    $deprecated = null;
			    $autoload   = 'no';
			    $success    = add_option( 'prosolwpclient_frontend', $value, $deprecated, $autoload );
		    }

		    return $success;
	    }

    }
