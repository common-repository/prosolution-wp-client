<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}
?>
<?php

    if (!class_exists('WP_List_Table')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    }

    class CBXProSolWpClientJobs_List_Table extends WP_List_Table
    {

        function __construct()
        {
            global $status, $page;

            //Set parent defaults
            parent::__construct(array(
                                    'singular' => 'pswpjobslist',     //singular name of the listed records
                                    'plural'   => 'pswpjobslists',    //plural name of the listed records
                                    'ajax'     => false      //does this table support ajax?
                                ));

        }

        /**
        * Callback for column 'jobid'
        *
        * @param array $item
        *
        * @return string
        */
        function column_jobid($item){
            return stripslashes($item['jobid']);
        }

        /**
        * Callback for column 'jobname'
        *
        * @param array $item
        *
        * @return string
        */

        function column_jobname($item){
            return stripslashes($item['jobname']);
        }

        /**
        * Callback for column 'jobproject_id'
        *
        * @param array $item
        *
        * @return string
        */

        function column_jobproject_id($item){
            return stripslashes($item['jobproject_id']);
        }

        /**
        * Callback for column 'jobproject_name'
        *
        * @param array $item
        *
        * @return string
        */

        function column_jobproject_name($item){
            return stripslashes($item['jobproject_name']);
        }

        /**
        * Callback for column 'jobstartdate'
        *
        * @param array $item
        *
        * @return string
        */

        function column_jobstartdate($item){
            return stripslashes($item['jobstartdate']);
        }

        /**
        * Callback for column 'federalid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_federalid($item){
            return stripslashes($item['federalid']);
        }

        /**
        * Callback for column 'federalname'
        *
        * @param array $item
        *
        * @return string
        */

        function column_federalname($item){
            return stripslashes($item['federalname']);
        }

        /**
        * Callback for column 'empgroup_id'
        *
        * @param array $item
        *
        * @return string
        */

        function column_empgroup_id($item){
            return stripslashes($item['empgroup_id']);
        }

        /**
        * Callback for column 'empgroup_name'
        *
        * @param array $item
        *
        * @return string
        */

        function column_empgroup_name($item){
            return stripslashes($item['empgroup_name']);
        }

        /**
        * Callback for column 'empgroup_type'
        *
        * @param array $item
        *
        * @return string
        */

        function column_empgroup_type($item){
            return stripslashes($item['empgroup_type']);
        }

        /**
        * Callback for column 'categoryid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_categoryid($item){
            return stripslashes($item['categoryid']);
        }

        /**
        * Callback for column 'categoryname'
        *
        * @param array $item
        *
        * @return string
        */

        function column_categoryname($item){
            return stripslashes($item['categoryname']);
        }

        /**
        * Callback for column 'customformat_id'
        *
        * @param array $item
        *
        * @return string
        */

        function column_customformat_id($item){
            return stripslashes($item['customformat_id']);
        }

        /**
        * Callback for column 'customformat_name'
        *
        * @param array $item
        *
        * @return string
        */

        function column_customformat_name($item){
            return stripslashes($item['customformat_name']);
        }

        /**
        * Callback for column 'countryid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_countryid($item){
            return stripslashes($item['countryid']);
        }

        /**
        * Callback for column 'countryname'
        *
        * @param array $item
        *
        * @return string
        */

        function column_countryname($item){
            return stripslashes($item['countryname']);
        }

        /**
        * Callback for column 'officeid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_officeid($item){
            return stripslashes($item['officeid']);
        }

        /**
        * Callback for column 'officename'
        *
        * @param array $item
        *
        * @return string
        */

        function column_officename($item){
            return stripslashes($item['officename']);
        }

        /**
        * Callback for column 'worktimeid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_worktimeid($item){
            return stripslashes($item['worktimeid']);
        }

        /**
        * Callback for column 'worktimename'
        *
        * @param array $item
        *
        * @return string
        */

        function column_worktimename($item){
            return stripslashes($item['worktimename']);
        }

        /**
        * Callback for column 'workingplace'
        *
        * @param array $item
        *
        * @return string
        */

        function column_workingplace($item){
            return stripslashes($item['workingplace']);
        }

        /**
        * Callback for column 'zipcode'
        *
        * @param array $item
        *
        * @return string
        */

        function column_zipcode($item){
            return stripslashes($item['zipcode']);
        }

        /**
        * Callback for column 'isced'
        *
        * @param array $item
        *
        * @return string
        */

        function column_isced($item){
            return stripslashes($item['isced']);
        }

        /**
        * Callback for column 'isced_name'
        *
        * @param array $item
        *
        * @return string
        */

        function column_isced_name($item){
            return stripslashes($item['isced_name']);
        }

        /**
        * Callback for column 'max_distance'
        *
        * @param array $item
        *
        * @return string
        */

        function column_max_distance($item){
            return stripslashes($item['max_distance']);
        }
        
        /**
        * Callback for column 'exp_year'
        *
        * @param array $item
        *
        * @return string
        */

        function column_exp_year($item){
            return stripslashes($item['exp_year']);
        }

        /**
        * Callback for column 'jobrefid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_jobrefid($item){
            return stripslashes($item['jobrefid']);
        }

        /**
        * Callback for column 'custrefid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_custrefid($item){
            return stripslashes($item['custrefid']);
        }

         /**
        * Callback for column 'custreflogo'
        *
        * @param array $item
        *
        * @return string
        */

        function column_custreflogo($item){
            return stripslashes($item['custreflogo']);
        }

         /**
        * Callback for column 'agentid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_agentid($item){
            return stripslashes($item['agentid']);
        }

         /**
        * Callback for column 'agentname'
        *
        * @param array $item
        *
        * @return string
        */

        function column_agentname($item){
            return stripslashes($item['agentname']);
        }
        
         /**
        * Callback for column 'showagentphoto'
        *
        * @param array $item
        *
        * @return string
        */

        function column_showagentphoto($item){
            return stripslashes($item['showagentphoto']);
        }

         /**
        * Callback for column 'showsignature'
        *
        * @param array $item
        *
        * @return string
        */

        function column_showsignature($item){
            return stripslashes($item['showsignature']);
        }

         /**
        * Callback for column 'showcustname'
        *
        * @param array $item
        *
        * @return string
        */

        function column_showcustname($item){
            return stripslashes($item['showcustname']);
        }
        
         /**
        * Callback for column 'showcustlogo'
        *
        * @param array $item
        *
        * @return string
        */

        function column_showcustlogo($item){
            return stripslashes($item['showcustlogo']);
        }

         /**
        * Callback for column 'qualificationid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_qualificationid($item){
            return stripslashes($item['qualificationid']);
        }

         /**
        * Callback for column 'untildate'
        *
        * @param array $item
        *
        * @return string
        */

        function column_untildate($item){
            return stripslashes($item['untildate']);
        }

        /**
        * Callback for column 'publishdate'
        *
        * @param array $item
        *
        * @return string
        */

        function column_publishdate($item){
            return stripslashes($item['publishdate']);
        }

        /**
        * Callback for column 'salarytext'
        *
        * @param array $item
        *
        * @return string
        */

        function column_salarytext($item){
            return stripslashes($item['salarytext']);
        }

        /**
        * Callback for column 'profession'
        *
        * @param array $item
        *
        * @return string
        */

        function column_profession($item){
            return stripslashes($item['profession']);
        }

        /**
        * Callback for column 'skills'
        *
        * @param array $item
        *
        * @return string
        */

        function column_skills($item){
            return stripslashes($item['skills']);
        }

        /**
        * Callback for column 'question'
        *
        * @param array $item
        *
        * @return string
        */

        function column_question($item){
            return stripslashes($item['question']);
        }

        /**
        * Callback for column 'portal'
        *
        * @param array $item
        *
        * @return string
        */

        function column_portal($item){
            return stripslashes($item['portal']);
        }

        /**
        * Callback for column 'customer'
        *
        * @param array $item
        *
        * @return string
        */

        function column_customer($item){
            return stripslashes($item['customer']);
        }   

        /**
        * Callback for column 'recruitlink'
        *
        * @param array $item
        *
        * @return string
        */

        function column_recruitlink($item){
            return stripslashes($item['recruitlink']);
        }   

         /**
        * Callback for column 'textfieldlabel_1'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_1($item){
            return stripslashes($item['textfieldlabel_1']);
        }

        /**
        * Callback for column 'textfieldlabel_2'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_2($item){
            return stripslashes($item['textfieldlabel_2']);
        }

        /**
        * Callback for column 'textfieldlabel_3'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_3($item){
            return stripslashes($item['textfieldlabel_3']);
        }

        /**
        * Callback for column 'textfieldlabel_4'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_4($item){
            return stripslashes($item['textfieldlabel_4']);
        }

        /**
        * Callback for column 'textfieldlabel_5'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_5($item){
            return stripslashes($item['textfieldlabel_5']);
        }

        /**
        * Callback for column 'textfieldlabel_6'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_6($item){
            return stripslashes($item['textfieldlabel_6']);
        }

        /**
        * Callback for column 'textfieldlabel_7'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_7($item){
            return stripslashes($item['textfieldlabel_7']);
        }

        /**
        * Callback for column 'textfieldlabel_8'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_8($item){
            return stripslashes($item['textfieldlabel_8']);
        }

        /**
        * Callback for column 'textfieldlabel_9'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_9($item){
            return stripslashes($item['textfieldlabel_9']);
        }

        /**
        * Callback for column 'textfieldlabel_10'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_10($item){
            return stripslashes($item['textfieldlabel_10']);
        }

        /**
        * Callback for column 'textfieldlabel_11'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_11($item){
            return stripslashes($item['textfieldlabel_11']);
        }

        /**
        * Callback for column 'textfieldlabel_12'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_12($item){
            return stripslashes($item['textfieldlabel_12']);
        }

        /**
        * Callback for column 'textfieldlabel_13'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_13($item){
            return stripslashes($item['textfieldlabel_13']);
        }

        /**
        * Callback for column 'textfieldlabel_14'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_14($item){
            return stripslashes($item['textfieldlabel_14']);
        }

        /**
        * Callback for column 'textfieldlabel_15'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_15($item){
            return stripslashes($item['textfieldlabel_15']);
        }

        /**
        * Callback for column 'textfieldlabel_16'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_16($item){
            return stripslashes($item['textfieldlabel_16']);
        }

        /**
        * Callback for column 'textfieldlabel_17'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_17($item){
            return stripslashes($item['textfieldlabel_17']);
        }

        /**
        * Callback for column 'textfieldlabel_18'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_18($item){
            return stripslashes($item['textfieldlabel_18']);
        }

        /**
        * Callback for column 'textfieldlabel_19'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_19($item){
            return stripslashes($item['textfieldlabel_19']);
        }

        /**
        * Callback for column 'textfieldlabel_20'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_20($item){
            return stripslashes($item['textfieldlabel_20']);
        } 

        /**
        * Callback for column 'textfield_1'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_1($item){
            return stripslashes($item['textfield_1']);
        }

        /**
        * Callback for column 'textfield_2'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_2($item){
            return stripslashes($item['textfield_2']);
        }

        /**
        * Callback for column 'textfield_3'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_3($item){
            return stripslashes($item['textfield_3']);
        }

        /**
        * Callback for column 'textfield_4'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_4($item){
            return stripslashes($item['textfield_4']);
        }

        /**
        * Callback for column 'textfield_5'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_5($item){
            return stripslashes($item['textfield_5']);
        }

        /**
        * Callback for column 'textfield_6'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_6($item){
            return stripslashes($item['textfield_6']);
        }

        /**
        * Callback for column 'textfield_7'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_7($item){
            return stripslashes($item['textfield_7']);
        }

        /**
        * Callback for column 'textfield_8'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_8($item){
            return stripslashes($item['textfield_8']);
        }

        /**
        * Callback for column 'textfield_9'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_9($item){
            return stripslashes($item['textfield_9']);
        }

        /**
        * Callback for column 'textfield_10'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_10($item){
            return stripslashes($item['textfield_10']);
        }

        /**
        * Callback for column 'textfield_11'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_11($item){
            return stripslashes($item['textfield_11']);
        }

        /**
        * Callback for column 'textfield_12'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_12($item){
            return stripslashes($item['textfield_12']);
        }

        /**
        * Callback for column 'textfield_13'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_13($item){
            return stripslashes($item['textfield_13']);
        }

        /**
        * Callback for column 'textfield_14'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_14($item){
            return stripslashes($item['textfield_14']);
        }

        /**
        * Callback for column 'textfield_15'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_15($item){
            return stripslashes($item['textfield_15']);
        }

        /**
        * Callback for column 'textfield_16'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_16($item){
            return stripslashes($item['textfield_16']);
        }

        /**
        * Callback for column 'textfield_17'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_17($item){
            return stripslashes($item['textfield_17']);
        }

        /**
        * Callback for column 'textfield_18'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_18($item){
            return stripslashes($item['textfield_18']);
        }

        /**
        * Callback for column 'textfield_19'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_19($item){
            return stripslashes($item['textfield_19']);
        }

        /**
        * Callback for column 'textfield_20'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_20($item){
            return stripslashes($item['textfield_20']);
        } 

        /**
        * Callback for column 'textfieldlabel_21'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_21($item){
            return stripslashes($item['textfieldlabel_21']);
        } 

        /**
        * Callback for column 'textfieldlabel_22'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_22($item){
            return stripslashes($item['textfieldlabel_22']);
        } 

        /**
        * Callback for column 'textfieldlabel_23'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_23($item){
            return stripslashes($item['textfieldlabel_23']);
        } 

        /**
        * Callback for column 'textfieldlabel_24'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_24($item){
            return stripslashes($item['textfieldlabel_24']);
        } 

        /**
        * Callback for column 'textfieldlabel_25'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_25($item){
            return stripslashes($item['textfieldlabel_25']);
        } 

        /**
        * Callback for column 'textfieldlabel_26'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_26($item){
            return stripslashes($item['textfieldlabel_26']);
        } 

        /**
        * Callback for column 'textfieldlabel_27'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_27($item){
            return stripslashes($item['textfieldlabel_27']);
        } 

        /**
        * Callback for column 'textfieldlabel_28'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_28($item){
            return stripslashes($item['textfieldlabel_28']);
        } 

        /**
        * Callback for column 'textfieldlabel_29'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_29($item){
            return stripslashes($item['textfieldlabel_29']);
        } 

        /**
        * Callback for column 'textfieldlabel_30'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfieldlabel_30($item){
            return stripslashes($item['textfieldlabel_30']);
        } 

        /**
        * Callback for column 'textfield_21'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_21($item){
            return stripslashes($item['textfield_21']);
        } 

        /**
        * Callback for column 'textfield_22'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_22($item){
            return stripslashes($item['textfield_22']);
        } 

        /**
        * Callback for column 'textfield_23'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_23($item){
            return stripslashes($item['textfield_23']);
        } 

        /**
        * Callback for column 'textfield_24'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_24($item){
            return stripslashes($item['textfield_24']);
        } 

        /**
        * Callback for column 'textfield_25'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_25($item){
            return stripslashes($item['textfield_25']);
        } 

        /**
        * Callback for column 'textfield_26'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_26($item){
            return stripslashes($item['textfield_26']);
        } 

        /**
        * Callback for column 'textfield_27'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_27($item){
            return stripslashes($item['textfield_27']);
        } 

        /**
        * Callback for column 'textfield_28'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_28($item){
            return stripslashes($item['textfield_28']);
        } 

        /**
        * Callback for column 'textfield_29'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_29($item){
            return stripslashes($item['textfield_29']);
        } 

        /**
        * Callback for column 'textfield_30'
        *
        * @param array $item
        *
        * @return string
        */
        function column_textfield_30($item){
            return stripslashes($item['textfield_30']);
        } 

        /**
        * Callback for column 'site_id'
        *
        * @param array $item
        *
        * @return string
        */

        function column_site_id($item){
            return stripslashes($item['site_id']);
        }


        /** ************************************************************************
         * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
         * is given special treatment when columns are processed. It ALWAYS needs to
         * have it's own method.
         *
         * @see WP_List_Table::::single_row_columns()
         *
         * @param array $item A singular item (one full row's worth of data)
         *
         * @return string Text to be placed inside the column <td> (movie title only)
         **************************************************************************/
        function proSol_column_cb($item)
        {
            return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />',
                /*$1%s*/
                $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
                /*$2%s*/
                $item['countryCode']                //The value of the checkbox should be the record's id
            );
        }

        /** ************************************************************************
         * Recommended. This method is called when the parent class can't find a method
         * specifically build for a given column. Generally, it's recommended to include
         * one method for each column you want to render, keeping your package class
         * neat and organized. For example, if the class needs to process a column
         * named 'title', it would first see if a method named $this->column_title()
         * exists - if it does, that method will be used. If it doesn't, this one will
         * be used. Generally, you should try to use custom column methods as much as
         * possible.
         *
         * Since we have defined a column_title() method later on, this method doesn't
         * need to concern itself with any column with a name of 'title'. Instead, it
         * needs to handle everything else.
         *
         * For more detailed insight into how columns are handled, take a look at
         * WP_List_Table::single_row_columns()
         *
         * @param array $item        A singular item (one full row's worth of data)
         * @param array $column_name The name/slug of the column to be processed
         *
         * @return string Text or HTML to be placed inside the column <td>
         **************************************************************************/
        function proSol_column_default($item, $column_name)
        {

            switch ($column_name) {
                case 'jobid':
                    return $item[$column_name];
                case 'jobname':
                    return $item[$column_name];
                case 'jobproject_id':
                    return $item[$column_name];
                case 'jobproject_name':
                    return $item[$column_name];
                case 'jobstartdate':
                    return $item[$column_name];    
                case 'federalid':
                    return $item[$column_name];
                case 'federalname':
                    return $item[$column_name];
                case 'empgroup_id':
                    return $item[$column_name];
                case 'empgroup_name':
                    return $item[$column_name];
                case 'empgroup_type':
                    return $item[$column_name];
                case 'categoryid':
                    return $item[$column_name];
                case 'categoryname':
                    return $item[$column_name];
                case 'customformat_id':
                    return $item[$column_name];
                case 'customformat_name':
                    return $item[$column_name];
                case 'countryid':
                    return $item[$column_name];
                case 'countryname':
                    return $item[$column_name];
                case 'officeid':
                    return $item[$column_name];
                case 'officename':
                    return $item[$column_name];
                case 'worktimeid':
                    return $item[$column_name];
                case 'worktimename':
                    return $item[$column_name];
                case 'workingplace':
                    return $item[$column_name];
                case 'zipcode':
                    return $item[$column_name];
                case 'isced':
                    return $item[$column_name];
                case 'isced_name':
                    return $item[$column_name]; 
                case 'max_distance':
                    return $item[$column_name]; 
                case 'exp_year':
                    return $item[$column_name];
                case 'jobrefid':
                    return $item[$column_name];      
                case 'custrefid':
                    return $item[$column_name];
                case 'custreflogo':
                    return $item[$column_name];
                case 'agentid':
                    return $item[$column_name];
                case 'agentname':
                    return $item[$column_name];
                case 'showagentphoto':
                    return $item[$column_name];
                case 'showsignature':
                    return $item[$column_name]; 
                case 'showcustname':
                    return $item[$column_name]; 
                case 'exp_year':
                    return $item[$column_name];
                case 'showcustlogo':
                    return $item[$column_name];          
                case 'qualificationid':
                    return $item[$column_name];   
                case 'untildate':
                    return $item[$column_name];      
                case 'publishdate':
                    return $item[$column_name];    
                case 'salarytext':
                    return $item[$column_name];  
                case 'profession':
                    return $item[$column_name];
                case 'skills':
                    return $item[$column_name];    
                case 'question':
                    return $item[$column_name];
                case 'portal':
                    return $item[$column_name];    
                case 'customer':
                    return $item[$column_name]; 
                case 'recruitlink':
                    return $item[$column_name];             
                case 'textfieldlabel_1':
                    return $item[$column_name];
                case 'textfieldlabel_2':
                    return $item[$column_name];
                case 'textfieldlabel_3':
                    return $item[$column_name];
                case 'textfieldlabel_4':
                    return $item[$column_name];
                case 'textfieldlabel_5':
                    return $item[$column_name];
                case 'textfieldlabel_6':
                    return $item[$column_name];
                case 'textfieldlabel_7':
                    return $item[$column_name];
                case 'textfieldlabel_8':
                    return $item[$column_name];
                case 'textfieldlabel_9':
                    return $item[$column_name];
                case 'textfieldlabel_10':
                    return $item[$column_name];
                case 'textfieldlabel_11':
                    return $item[$column_name];
                case 'textfieldlabel_12':
                    return $item[$column_name];
                case 'textfieldlabel_13':
                    return $item[$column_name];
                case 'textfieldlabel_14':
                    return $item[$column_name];
                case 'textfieldlabel_15':
                    return $item[$column_name];
                case 'textfieldlabel_16':
                    return $item[$column_name];
                case 'textfieldlabel_17':
                    return $item[$column_name];
                case 'textfieldlabel_18':
                    return $item[$column_name];
                case 'textfieldlabel_19':
                    return $item[$column_name];
                case 'textfieldlabel_20':
                    return $item[$column_name];
                case 'textfield_1':
                    return $item[$column_name];
                case 'textfield_2':
                    return $item[$column_name];
                case 'textfield_3':
                    return $item[$column_name];
                case 'textfield_4':
                    return $item[$column_name];
                case 'textfield_5':
                    return $item[$column_name];
                case 'textfield_6':
                    return $item[$column_name];
                case 'textfield_7':
                    return $item[$column_name];
                case 'textfield_8':
                    return $item[$column_name];
                case 'textfield_9':
                    return $item[$column_name];
                case 'textfield_10':
                    return $item[$column_name];
                case 'textfield_11':
                    return $item[$column_name];
                case 'textfield_12':
                    return $item[$column_name];
                case 'textfield_13':
                    return $item[$column_name];
                case 'textfield_14':
                    return $item[$column_name];
                case 'textfield_15':
                    return $item[$column_name];
                case 'textfield_16':
                    return $item[$column_name];
                case 'textfield_17':
                    return $item[$column_name];
                case 'textfield_18':
                    return $item[$column_name];
                case 'textfield_19':
                    return $item[$column_name];
                case 'textfield_20':
                    return $item[$column_name];
                case 'textfieldlabel_21':
                    return $item[$column_name];
                case 'textfield_21':
                    return $item[$column_name];
                case 'textfieldlabel_22':
                    return $item[$column_name];
                case 'textfield_22':
                    return $item[$column_name];
                case 'textfieldlabel_23':
                    return $item[$column_name];
                case 'textfield_23':
                    return $item[$column_name];
                case 'textfieldlabel_24':
                    return $item[$column_name];
                case 'textfield_24':
                    return $item[$column_name];
                case 'textfieldlabel_25':
                    return $item[$column_name];
                case 'textfield_25':
                    return $item[$column_name];
                case 'textfieldlabel_26':
                    return $item[$column_name];
                case 'textfield_26':
                    return $item[$column_name];
                case 'textfieldlabel_27':
                    return $item[$column_name];
                case 'textfield_27':
                    return $item[$column_name];
                case 'textfieldlabel_28':
                    return $item[$column_name];
                case 'textfield_28':
                    return $item[$column_name];
                case 'textfieldlabel_29':
                    return $item[$column_name];
                case 'textfield_29':
                    return $item[$column_name];
                case 'textfieldlabel_30':
                    return $item[$column_name];
                case 'textfield_30':
                    return $item[$column_name];
                default:
                    return print_r($item, true); //Show the whole array for troubleshooting purposes
            }
        }
		
		function get_columns(){
			$this->proSol_get_columns();
		}

        function proSol_get_columns()
        {
            $columns = array(
//                'cb'          => '<input type="checkbox" />', //Render a checkbox instead of text
                'jobid' => esc_html__('jobid', 'prosolwpclient'),
                'jobname' => esc_html__('jobname', 'prosolwpclient'),
                'jobproject_id' => esc_html__('jobproject_id', 'prosolwpclient'),
                'jobproject_name' => esc_html__('jobproject_name', 'prosolwpclient'),
                'jobstartdate' => esc_html__('jobstartdate', 'prosolwpclient'),
                'federalid' => esc_html__('federalid', 'prosolwpclient'),
                'federalname' => esc_html__('federalname', 'prosolwpclient'),
                'empgroup_id' => esc_html__('empgroup_id', 'prosolwpclient'),
                'empgroup_name' => esc_html__('empgroup_name', 'prosolwpclient'),
                'empgroup_type' => esc_html__('empgroup_type', 'prosolwpclient'),
                'categoryid' => esc_html__('categoryid', 'prosolwpclient'),
                'categoryname' => esc_html__('categoryname', 'prosolwpclient'),
                'customformat_id' => esc_html__('customformat_id', 'prosolwpclient'),
                'customformat_name' => esc_html__('customformat_name', 'prosolwpclient'),
                'countryid' => esc_html__('countryid', 'prosolwpclient'),
                'countryname' => esc_html__('countryname', 'prosolwpclient'),
                'officeid' => esc_html__('officeid', 'prosolwpclient'),
                'officename' => esc_html__('officename', 'prosolwpclient'),
                'worktimeid' => esc_html__('worktimeid', 'prosolwpclient'),
                'worktimename' => esc_html__('worktimename', 'prosolwpclient'),
                'workingplace' => esc_html__('workingplace', 'prosolwpclient'),
                'zipcode' => esc_html__('zipcode', 'prosolwpclient'),
                'isced' => esc_html__('isced', 'prosolwpclient'),
                'isced_name' => esc_html__('isced_name', 'prosolwpclient'),
                'max_distance' => esc_html__('max_distance', 'prosolwpclient'),
                'exp_year' => esc_html__('exp_year', 'prosolwpclient'),
                'jobrefid' => esc_html__('jobrefid', 'prosolwpclient'),
                'custrefid' => esc_html__('custrefid', 'prosolwpclient'),
                'custreflogo' => esc_html__('custreflogo', 'prosolwpclient'),
                'agentid' => esc_html__('agentid', 'prosolwpclient'),
                'agentname' => esc_html__('agentname', 'prosolwpclient'),
                'showagentphoto' => esc_html__('showagentphoto', 'prosolwpclient'),
                'showsignature' => esc_html__('showsignature', 'prosolwpclient'),
                'showcustname' => esc_html__('showcustname', 'prosolwpclient'),
                'showcustlogo' => esc_html__('showcustlogo', 'prosolwpclient'),
                'qualificationid' => esc_html__('qualificationid', 'prosolwpclient'),  
                'untildate' => esc_html__('untildate', 'prosolwpclient'),
                'publishdate' => esc_html__('publishdate', 'prosolwpclient'),
                'salarytext' => esc_html__('salarytext', 'prosolwpclient'),

                'profession' => esc_html__('profession', 'prosolwpclient'),
                'skills' => esc_html__('skills', 'prosolwpclient'),
                'question' => esc_html__('question', 'prosolwpclient'),
                'portal' => esc_html__('portal', 'prosolwpclient'),

                'customer' => esc_html__('customer', 'prosolwpclient'),

                'recruitlink' => esc_html__('recruitlink', 'prosolwpclient'),

                'textfieldlabel_1' => esc_html__('textfieldlabel_1', 'prosolwpclient'),
                'textfieldlabel_2' => esc_html__('textfieldlabel_2', 'prosolwpclient'),
                'textfieldlabel_3' => esc_html__('textfieldlabel_3', 'prosolwpclient'),
                'textfieldlabel_4' => esc_html__('textfieldlabel_4', 'prosolwpclient'),
                'textfieldlabel_5' => esc_html__('textfieldlabel_5', 'prosolwpclient'),
                'textfieldlabel_6' => esc_html__('textfieldlabel_6', 'prosolwpclient'),
                'textfieldlabel_7' => esc_html__('textfieldlabel_7', 'prosolwpclient'),
                'textfieldlabel_8' => esc_html__('textfieldlabel_8', 'prosolwpclient'),
                'textfieldlabel_9' => esc_html__('textfieldlabel_9', 'prosolwpclient'),
                'textfieldlabel_10' => esc_html__('textfieldlabel_10', 'prosolwpclient'),
                'textfieldlabel_11' => esc_html__('textfieldlabel_11', 'prosolwpclient'),
                'textfieldlabel_12' => esc_html__('textfieldlabel_12', 'prosolwpclient'),
                'textfieldlabel_13' => esc_html__('textfieldlabel_13', 'prosolwpclient'),
                'textfieldlabel_14' => esc_html__('textfieldlabel_14', 'prosolwpclient'),
                'textfieldlabel_15' => esc_html__('textfieldlabel_15', 'prosolwpclient'),
                'textfieldlabel_16' => esc_html__('textfieldlabel_16', 'prosolwpclient'),
                'textfieldlabel_17' => esc_html__('textfieldlabel_17', 'prosolwpclient'),
                'textfieldlabel_18' => esc_html__('textfieldlabel_18', 'prosolwpclient'),
                'textfieldlabel_19' => esc_html__('textfieldlabel_19', 'prosolwpclient'),
                'textfieldlabel_20' => esc_html__('textfieldlabel_20', 'prosolwpclient'),
                'textfieldlabel_21' => esc_html__('textfieldlabel_21', 'prosolwpclient'),
                'textfieldlabel_22' => esc_html__('textfieldlabel_22', 'prosolwpclient'),
                'textfieldlabel_23' => esc_html__('textfieldlabel_23', 'prosolwpclient'),
                'textfieldlabel_24' => esc_html__('textfieldlabel_24', 'prosolwpclient'),
                'textfieldlabel_25' => esc_html__('textfieldlabel_25', 'prosolwpclient'),
                'textfieldlabel_26' => esc_html__('textfieldlabel_26', 'prosolwpclient'),
                'textfieldlabel_27' => esc_html__('textfieldlabel_27', 'prosolwpclient'),
                'textfieldlabel_28' => esc_html__('textfieldlabel_28', 'prosolwpclient'),
                'textfieldlabel_29' => esc_html__('textfieldlabel_29', 'prosolwpclient'),
                'textfieldlabel_30' => esc_html__('textfieldlabel_30', 'prosolwpclient'),
                'textfield_1' => esc_html__('textfield_1', 'prosolwpclient'),
                'textfield_2' => esc_html__('textfield_2', 'prosolwpclient'),
                'textfield_3' => esc_html__('textfield_3', 'prosolwpclient'),
                'textfield_4' => esc_html__('textfield_4', 'prosolwpclient'),
                'textfield_5' => esc_html__('textfield_5', 'prosolwpclient'),
                'textfield_6' => esc_html__('textfield_6', 'prosolwpclient'),
                'textfield_7' => esc_html__('textfield_7', 'prosolwpclient'),
                'textfield_8' => esc_html__('textfield_8', 'prosolwpclient'),
                'textfield_9' => esc_html__('textfield_9', 'prosolwpclient'),
                'textfield_10' => esc_html__('textfield_10', 'prosolwpclient'),
                'textfield_11' => esc_html__('textfield_11', 'prosolwpclient'),
                'textfield_12' => esc_html__('textfield_12', 'prosolwpclient'),
                'textfield_13' => esc_html__('textfield_13', 'prosolwpclient'),
                'textfield_14' => esc_html__('textfield_14', 'prosolwpclient'),
                'textfield_15' => esc_html__('textfield_15', 'prosolwpclient'),
                'textfield_16' => esc_html__('textfield_16', 'prosolwpclient'),
                'textfield_17' => esc_html__('textfield_17', 'prosolwpclient'),
                'textfield_18' => esc_html__('textfield_18', 'prosolwpclient'),
                'textfield_19' => esc_html__('textfield_19', 'prosolwpclient'),
                'textfield_20' => esc_html__('textfield_20', 'prosolwpclient'),
                'textfield_21' => esc_html__('textfield_21', 'prosolwpclient'),
                'textfield_22' => esc_html__('textfield_22', 'prosolwpclient'),
                'textfield_23' => esc_html__('textfield_23', 'prosolwpclient'),
                'textfield_24' => esc_html__('textfield_24', 'prosolwpclient'),
                'textfield_25' => esc_html__('textfield_25', 'prosolwpclient'),
                'textfield_26' => esc_html__('textfield_26', 'prosolwpclient'),
                'textfield_27' => esc_html__('textfield_27', 'prosolwpclient'),
                'textfield_28' => esc_html__('textfield_28', 'prosolwpclient'),
                'textfield_29' => esc_html__('textfield_29', 'prosolwpclient'),
                'textfield_30' => esc_html__('textfield_30', 'prosolwpclient'),

                'site_id' => esc_html__('site_id', 'prosolwpclient')
            );

            return $columns;
        }


        function proSol_get_sortable_columns()
        {
            $sortable_columns = array(
                'jobid' => array('jobid', false),
                'jobname' => array('jobname', false),
                'jobproject_id' => array('jobproject_id', false),
                'jobproject_name' => array('jobproject_name', false),
                'jobstartdate' => array('jobstartdate', false),
                'federalid' => array('federalid', false),
                'federalname' => array('federalname', false),
                'empgroup_id' => array('empgroup_id', false),
                'empgroup_name' => array('empgroup_name', false),
                'empgroup_type' => array('empgroup_type', false),
                'categoryid' => array('categoryid', false),
                'categoryname' => array('categoryname', false),
                'customformat_id' => array('customformat_id', false),
                'customformat_name' => array('customformat_name', false),
                'countryid' => array('countryid', false),
                'countryname' => array('countryname', false),
                'officeid' => array('officeid', false),
                'officename' => array('officename', false),
                'worktimeid' => array('worktimeid', false),
                'worktimename' => array('worktimename', false),
                'workingplace' => array('workingplace', false),
                'zipcode' => array('zipcode', false),
                'isced' => array('isced', false),
                'isced_name' => array('isced_name', false),
                'max_distance' => array('max_distance', false),
                'exp_year' => array('exp_year', false),
                'jobrefid' => array('jobrefid', false),
                'custrefid' => array('custrefid', false),
                'custreflogo' => array('custreflogo', false),
                'agentid' => array('agentid', false),
                'agentname' => array('agentname', false),
                'showagentphoto' => array('showagentphoto', false),
                'showsignature' => array('showsignature', false),
                'showcustname' => array('showcustname', false),
                'showcustlogo' => array('showcustlogo', false),
                'qualificationid' => array('qualificationid', false),  
                'untildate' => array('publishdate', false),
                'publishdate' => array('publishdate', false),
                'salarytext' => array('publishdate', false), 
                'profession' => array('profession', false),
                'skills' => array('skills', false),
                'question' => array('question', false),
                'portal' => array('portal', false), 
                'customer' => array('customer', false),
                'recruitlink' => array('recruitlink', false),
                'textfieldlabel_1' => array('textfieldlabel_1', false),
                'textfieldlabel_2' => array('textfieldlabel_2', false),
                'textfieldlabel_3' => array('textfieldlabel_3', false),
                'textfieldlabel_4' => array('textfieldlabel_4', false),
                'textfieldlabel_5' => array('textfieldlabel_5', false),
                'textfieldlabel_6' => array('textfieldlabel_6', false),
                'textfieldlabel_7' => array('textfieldlabel_7', false),
                'textfieldlabel_8' => array('textfieldlabel_8', false),
                'textfieldlabel_9' => array('textfieldlabel_9', false),
                'textfieldlabel_10' => array('textfieldlabel_10', false),
                'textfieldlabel_11' => array('textfieldlabel_11', false),
                'textfieldlabel_12' => array('textfieldlabel_12', false),
                'textfieldlabel_13' => array('textfieldlabel_13', false),
                'textfieldlabel_14' => array('textfieldlabel_14', false),
                'textfieldlabel_15' => array('textfieldlabel_15', false),
                'textfieldlabel_16' => array('textfieldlabel_16', false),
                'textfieldlabel_17' => array('textfieldlabel_17', false),
                'textfieldlabel_18' => array('textfieldlabel_18', false),
                'textfieldlabel_19' => array('textfieldlabel_19', false),
                'textfieldlabel_20' => array('textfieldlabel_20', false),
                'textfieldlabel_21' => array('textfieldlabel_21', false),
                'textfieldlabel_22' => array('textfieldlabel_22', false),
                'textfieldlabel_23' => array('textfieldlabel_23', false),
                'textfieldlabel_24' => array('textfieldlabel_24', false),
                'textfieldlabel_25' => array('textfieldlabel_25', false),
                'textfieldlabel_26' => array('textfieldlabel_26', false),
                'textfieldlabel_27' => array('textfieldlabel_27', false),
                'textfieldlabel_28' => array('textfieldlabel_28', false),
                'textfieldlabel_29' => array('textfieldlabel_29', false),
                'textfieldlabel_30' => array('textfieldlabel_30', false),
                'textfield_1' => array('textfield_1', false),
                'textfield_2' => array('textfield_2', false),
                'textfield_3' => array('textfield_3', false),
                'textfield_4' => array('textfield_4', false),
                'textfield_5' => array('textfield_5', false),
                'textfield_6' => array('textfield_6', false),
                'textfield_7' => array('textfield_7', false),
                'textfield_8' => array('textfield_8', false),
                'textfield_9' => array('textfield_9', false),
                'textfield_10' => array('textfield_10', false),
                'textfield_11' => array('textfield_11', false),
                'textfield_12' => array('textfield_12', false),
                'textfield_13' => array('textfield_13', false),
                'textfield_14' => array('textfield_14', false),
                'textfield_15' => array('textfield_15', false),
                'textfield_16' => array('textfield_16', false),
                'textfield_17' => array('textfield_17', false),
                'textfield_18' => array('textfield_18', false),
                'textfield_19' => array('textfield_19', false),
                'textfield_20' => array('textfield_20', false),
                'textfield_21' => array('textfield_21', false),
                'textfield_22' => array('textfield_22', false),
                'textfield_23' => array('textfield_23', false),
                'textfield_24' => array('textfield_24', false),
                'textfield_25' => array('textfield_25', false),
                'textfield_26' => array('textfield_26', false),
                'textfield_27' => array('textfield_27', false),
                'textfield_28' => array('textfield_28', false),
                'textfield_29' => array('textfield_29', false),
                'textfield_30' => array('textfield_30', false),
                'site_id' => array('site_id', false),		
            );

            return $sortable_columns;
        }

        function prepare_items()
        {
            //global $wpdb;global $prosol_prefix; //This is used only if making any database queries

            /**
             * First, lets decide how many records per page to show
             */
            $user = get_current_user_id();

            $screen = get_current_screen();

            /**
             * REQUIRED for pagination. Let's figure out what page the user is currently
             * looking at. We'll need this later, so you should always include it in
             * your own package classes.
             */
            $current_page = $this->get_pagenum();


            $option_name = $screen->get_option('per_page', 'option'); //the core class name is WP_Screen


            $per_page = intval(get_user_meta($user, $option_name, true));


            if ($per_page == 0) {
                $per_page = intval($screen->get_option('per_page', 'default'));
            }


            /**
             * REQUIRED. Now we need to define our column headers. This includes a complete
             * array of columns to be displayed (slugs & titles), a list of columns
             * to keep hidden, and a list of columns that are sortable. Each of these
             * can be defined in another method (as we've done here) before being
             * used to build the value for our _column_headers property.
             */
            $columns = $this->proSol_get_columns();
            $hidden = array();
            $sortable = $this->proSol_get_sortable_columns();

            /**
             * REQUIRED. Finally, we build an array to be used by the class for column
             * headers. The $this->_column_headers property takes an array which contains
             * 3 other arrays. One for all columns, one for hidden columns, and one
             * for sortable columns.
             */
            $this->_column_headers = array($columns, $hidden, $sortable);


            /**
             * Optional. You can handle your bulk actions however you see fit. In this
             * case, we'll handle them within our package just to keep things clean.
             */


            /**
             * Instead of querying a database, we're going to fetch the example data
             * property we created for use in this plugin. This makes this example
             * package slightly different than one you might build on your own. In
             * this example, we'll be using array manipulation to sort and paginate
             * our data. In a real-world implementation, you will probably want to
             * use sort and pagination data to build a custom query instead, as you'll
             * be able to use your precisely-queried data immediately.
             */


            $order = (isset($_REQUEST['order']) && $_REQUEST['order'] != '') ? $_REQUEST['order'] : 'asc';
            $orderby = (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] != '') ? $_REQUEST['orderby'] : 'jobid';

            $search = (isset($_REQUEST['s']) && $_REQUEST['s'] != '') ? sanitize_text_field($_REQUEST['s']) : '';

            $data = $this->proSol_getData($search, $orderby, $order, $per_page, $current_page);
            $total_items = intval($this->proSol_getDataCount($search, $orderby, $order));


            /**
             * The WP_List_Table class does not handle pagination for us, so we need
             * to ensure that the data is trimmed to only the current page. We can use
             * array_slice() to
             */
            //$data = array_slice($data, (($current_page - 1) * $per_page), $per_page);


            /**
             * REQUIRED. Now we can add our *sorted* data to the items property, where
             * it can be used by the rest of the class.
             */
            $this->items = $data;


            /**
             * REQUIRED. We also have to register our pagination options & calculations.
             */
            $this->set_pagination_args(array(
                                           'total_items' => $total_items,                  //WE have to calculate the total number of items
                                           'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
                                           'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
                                       ));
        }

        /**
         * Get Data
         *
         * @param int $perpage
         * @param int $page
         *
         * @return array|null|object
         */
        function proSol_getData($search = '', $orderby = 'jobid', $order = 'asc', $perpage = 20, $page = 1)
        {

            global $wpdb;global $prosol_prefix;

            $table_ps_jobs = $prosol_prefix . 'jobs';


            $sql_select = "SELECT * FROM $table_ps_jobs";


            $where_sql = '';

            if ($search != '') {
                if ($where_sql != '') {
                    $where_sql .= ' AND ';
                }
                $where_sql .= $wpdb->prepare(" jobid LIKE '%%%s%%' OR jobname LIKE '%%%s%%' OR jobproject_id LIKE '%%%s%%' OR jobproject_name LIKE '%%%s%%' OR jobstartdate LIKE '%%%s%%' OR federalid LIKE '%%%s%%' OR federalname LIKE '%%%s%%' OR empgroup_id LIKE '%%%s%%' OR empgroup_name LIKE '%%%s%%' OR empgroup_type LIKE '%%%s%%' OR categoryid LIKE '%%%s%%' OR categoryname LIKE '%%%s%%' OR customformat_id LIKE '%%%s%%' OR customformat_name LIKE '%%%s%%' OR countryid LIKE '%%%s%%' OR countryname LIKE '%%%s%%' OR officeid LIKE '%%%s%%' OR officename LIKE '%%%s%%' OR worktimeid LIKE '%%%s%%' OR worktimename LIKE '%%%s%%' OR workingplace LIKE '%%%s%%' OR zipcode LIKE '%%%s%%' OR isced LIKE '%%%s%%' OR isced_name LIKE '%%%s%%' OR max_distance LIKE '%%%s%%' OR exp_year LIKE '%%%s%%' OR jobrefid LIKE '%%%s%%' OR custrefid LIKE '%%%s%%' OR custreflogo LIKE '%%%s%%' OR agentid LIKE '%%%s%%' OR agentname LIKE '%%%s%%' OR showagentphoto LIKE '%%%s%%' OR showsignature LIKE '%%%s%%' OR showcustname LIKE '%%%s%%' OR showcustlogo LIKE '%%%s%%' OR qualificationid LIKE '%%%s%%' OR untildate LIKE '%%%s%%' OR publishdate LIKE '%%%s%%' OR salarytext LIKE '%%%s%%' OR profession LIKE '%%%s%%' OR skills LIKE '%%%s%%' OR question LIKE '%%%s%%' OR portal LIKE '%%%s%%' OR customer LIKE '%%%s%%' OR recruitlink LIKE '%%%s%%' OR textfieldlabel_1 LIKE '%%%s%%' OR textfieldlabel_2 LIKE '%%%s%%' OR textfieldlabel_3 LIKE '%%%s%%' OR textfieldlabel_4 LIKE '%%%s%%' OR textfieldlabel_5 LIKE '%%%s%%' OR textfieldlabel_6 LIKE '%%%s%%' OR textfieldlabel_7 LIKE '%%%s%%' OR textfieldlabel_8 LIKE '%%%s%%' OR textfieldlabel_9 LIKE '%%%s%%' OR textfieldlabel_10 LIKE '%%%s%%' OR textfieldlabel_11 LIKE '%%%s%%' OR textfieldlabel_12 LIKE '%%%s%%' OR textfieldlabel_13 LIKE '%%%s%%' OR textfieldlabel_14 LIKE '%%%s%%' OR textfieldlabel_15 LIKE '%%%s%%' OR textfieldlabel_16 LIKE '%%%s%%' OR textfieldlabel_17 LIKE '%%%s%%' OR textfieldlabel_18 LIKE '%%%s%%' OR textfieldlabel_19 LIKE '%%%s%%' OR textfieldlabel_20 LIKE '%%%s%%' OR textfield_1 LIKE '%%%s%%' OR textfield_2 LIKE '%%%s%%' OR textfield_3 LIKE '%%%s%%' OR textfield_4 LIKE '%%%s%%' OR textfield_5 LIKE '%%%s%%' OR textfield_6 LIKE '%%%s%%' OR textfield_7 LIKE '%%%s%%' OR textfield_8 LIKE '%%%s%%' OR textfield_9 LIKE '%%%s%%' OR textfield_10 LIKE '%%%s%%' OR textfield_11 LIKE '%%%s%%' OR textfield_12 LIKE '%%%s%%' OR textfield_13 LIKE '%%%s%%' OR textfield_14 LIKE '%%%s%%' OR textfield_15 LIKE '%%%s%%' OR textfield_16 LIKE '%%%s%%' OR textfield_17 LIKE '%%%s%%' OR textfield_18 LIKE '%%%s%%' OR textfield_19 LIKE '%%%s%%' OR textfield_20 LIKE '%%%s%%' OR site_id LIKE '%%%s%%'  ", $search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search	);
            }


            if ($where_sql == '') {
                $where_sql = '1';
            }


            $start_point = ($page * $perpage) - $perpage;
            $limit_sql = "LIMIT";
            $limit_sql .= ' ' . $start_point . ',';
            $limit_sql .= ' ' . $perpage;


            $sortingOrder = " ORDER BY $orderby $order ";


            $data = $wpdb->get_results("$sql_select  WHERE  $where_sql $sortingOrder  $limit_sql", 'ARRAY_A');

            return $data;
        }

        /**
         * Get total data count
         *
         * @param int $perpage
         * @param int $page
         *
         * @return array|null|object
         */
        function proSol_getDataCount($search = '', $orderby = 'jobid', $order = 'asc')
        {

            global $wpdb;global $prosol_prefix;

            $table_ps_jobs = $prosol_prefix . 'jobs';

            $sql_select = "SELECT COUNT(*) FROM $table_ps_jobs";

            $where_sql = '';


            if ($search != '') {
                if ($where_sql != '') {
                    $where_sql .= ' AND ';
                }
                $where_sql .= $wpdb->prepare(" jobid LIKE '%%%s%%' OR jobname LIKE '%%%s%%' OR jobproject_id LIKE '%%%s%%' OR jobproject_name LIKE '%%%s%%' OR jobstartdate LIKE '%%%s%%' OR federalid LIKE '%%%s%%' OR federalname LIKE '%%%s%%' OR empgroup_id LIKE '%%%s%%' OR empgroup_name LIKE '%%%s%%' OR empgroup_type LIKE '%%%s%%' OR categoryid LIKE '%%%s%%' OR categoryname LIKE '%%%s%%' OR customformat_id LIKE '%%%s%%' OR customformat_name LIKE '%%%s%%' OR countryid LIKE '%%%s%%' OR countryname LIKE '%%%s%%' OR officeid LIKE '%%%s%%' OR officename LIKE '%%%s%%' OR worktimeid LIKE '%%%s%%' OR worktimename LIKE '%%%s%%' OR workingplace LIKE '%%%s%%' OR zipcode LIKE '%%%s%%' OR isced LIKE '%%%s%%' OR isced_name LIKE '%%%s%%' OR max_distance LIKE '%%%s%%' OR exp_year LIKE '%%%s%%' OR jobrefid LIKE '%%%s%%' OR custrefid LIKE '%%%s%%' OR custreflogo LIKE '%%%s%%' OR agentid LIKE '%%%s%%' OR agentname LIKE '%%%s%%' OR showagentphoto LIKE '%%%s%%' OR showsignature LIKE '%%%s%%' OR showcustname LIKE '%%%s%%' OR showcustlogo LIKE '%%%s%%' OR qualificationid LIKE '%%%s%%' OR untildate LIKE '%%%s%%' OR publishdate LIKE '%%%s%%' OR salarytext LIKE '%%%s%%' OR profession LIKE '%%%s%%' OR skills LIKE '%%%s%%' OR question LIKE '%%%s%%' OR portal LIKE '%%%s%%' OR customer LIKE '%%%s%%' OR recruitlink LIKE '%%%s%%' OR textfieldlabel_1 LIKE '%%%s%%' OR textfieldlabel_2 LIKE '%%%s%%' OR textfieldlabel_3 LIKE '%%%s%%' OR textfieldlabel_4 LIKE '%%%s%%' OR textfieldlabel_5 LIKE '%%%s%%' OR textfieldlabel_6 LIKE '%%%s%%' OR textfieldlabel_7 LIKE '%%%s%%' OR textfieldlabel_8 LIKE '%%%s%%' OR textfieldlabel_9 LIKE '%%%s%%' OR textfieldlabel_10 LIKE '%%%s%%' OR textfieldlabel_11 LIKE '%%%s%%' OR textfieldlabel_12 LIKE '%%%s%%' OR textfieldlabel_13 LIKE '%%%s%%' OR textfieldlabel_14 LIKE '%%%s%%' OR textfieldlabel_15 LIKE '%%%s%%' OR textfieldlabel_16 LIKE '%%%s%%' OR textfieldlabel_17 LIKE '%%%s%%' OR textfieldlabel_18 LIKE '%%%s%%' OR textfieldlabel_19 LIKE '%%%s%%' OR textfieldlabel_20 LIKE '%%%s%%' OR textfield_1 LIKE '%%%s%%' OR textfield_2 LIKE '%%%s%%' OR textfield_3 LIKE '%%%s%%' OR textfield_4 LIKE '%%%s%%' OR textfield_5 LIKE '%%%s%%' OR textfield_6 LIKE '%%%s%%' OR textfield_7 LIKE '%%%s%%' OR textfield_8 LIKE '%%%s%%' OR textfield_9 LIKE '%%%s%%' OR textfield_10 LIKE '%%%s%%' OR textfield_11 LIKE '%%%s%%' OR textfield_12 LIKE '%%%s%%' OR textfield_13 LIKE '%%%s%%' OR textfield_14 LIKE '%%%s%%' OR textfield_15 LIKE '%%%s%%' OR textfield_16 LIKE '%%%s%%' OR textfield_17 LIKE '%%%s%%' OR textfield_18 LIKE '%%%s%%' OR textfield_19 LIKE '%%%s%%' OR textfield_20 LIKE '%%%s%%' OR site_id LIKE '%%%s%%'  ", $search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search	);

            }

            if ($where_sql == '') {
                $where_sql = '1';
            }


            $sortingOrder = " ORDER BY $orderby $order ";


            $count = $wpdb->get_var("$sql_select  WHERE  $where_sql $sortingOrder");

            return $count;
        }


    }
