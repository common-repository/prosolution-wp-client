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
        * Callback for column 'professionid'
        *
        * @param array $item
        *
        * @return string
        */

        function column_professionid($item){
            return stripslashes($item['professionid']);
        }

        /**
        * Callback for column 'professionname'
        *
        * @param array $item
        *
        * @return string
        */

        function column_professionname($item){
            return stripslashes($item['professionname']);
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
                case 'jobproject_id':
                    return $item[$column_name];
                case 'jobproject_name':
                    return $item[$column_name];
                case 'jobname':
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
                case 'professionid':
                    return $item[$column_name];
                case 'professionname':
                    return $item[$column_name];
                case 'workingplace':
                    return $item[$column_name];
                case 'zipcode':
                    return $item[$column_name];
                case 'isced':
                    return $item[$column_name];
                case 'isced_name':
                    return $item[$column_name];
                case 'publishdate':
                    return $item[$column_name];
                case 'max_distance':
                    return $item[$column_name];
                case 'jobrefid':
                    return $item[$column_name];
                case 'exp_year':
                    return $item[$column_name];
                case 'site_id':
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
                'jobproject_id' => esc_html__('jobproject_id', 'prosolwpclient'),
                'jobproject_name' => esc_html__('jobproject_name', 'prosolwpclient'),
                'jobname' => esc_html__('jobname', 'prosolwpclient'),
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
                'professionid' => esc_html__('professionid', 'prosolwpclient'),
                'professionname' => esc_html__('professionname', 'prosolwpclient'),
                'workingplace' => esc_html__('workingplace', 'prosolwpclient'),
                'zipcode' => esc_html__('zipcode', 'prosolwpclient'),
                'isced' => esc_html__('isced', 'prosolwpclient'),
                'isced_name' => esc_html__('isced_name', 'prosolwpclient'),
                'publishdate' => esc_html__('publishdate', 'prosolwpclient'),
                'max_distance' => esc_html__('max_distance', 'prosolwpclient'),
                'jobrefid' => esc_html__('jobrefid', 'prosolwpclient'),
                'exp_year' => esc_html__('exp_year', 'prosolwpclient'),
                'site_id' => esc_html__('site_id', 'prosolwpclient')
            );

            return $columns;
        }


        function proSol_get_sortable_columns()
        {
            $sortable_columns = array(
                'jobid' => array('jobid', false),
                'jobproject_id' => array('jobproject_id', false),
                'jobproject_name' => array('jobproject_name', false),
                'jobname' => array('jobname', false),
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
                'professionid' => array('professionid', false),
                'professionname' => array('professionname', false),
                'workingplace' => array('workingplace', false),
                'zipcode' => array('zipcode', false),
                'isced' => array('isced', false),
                'isced_name' => array('isced_name', false),
                'publishdate' => array('publishdate', false),
                'max_distance' => array('max_distance', false),
                'jobrefid' => array('jobrefid', false),
                'exp_year' => array('exp_year', false),
                'site_id' => array('site_id', false),		
            );

            return $sortable_columns;
        }

        function prepare_items()
        {
            //global $wpdb; //This is used only if making any database queries

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
            $orderby = (isset($_REQUEST['orderby']) && $_REQUEST['orderby'] != '') ? $_REQUEST['orderby'] : 'name';

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
        function proSol_getData($search = '', $orderby = 'name', $order = 'asc', $perpage = 20, $page = 1)
        {

            global $wpdb;

            $table_ps_jobs = $wpdb->prefix . 'jobs';


            $sql_select = "SELECT jobid, jobproject_id, jobproject_name, jobname, federalid, federalname, empgroup_id, empgroup_name, empgroup_type, categoryid, categoryname, customformat_id, customformat_name, countryid, countryname, officeid, officename, worktimeid, worktimename, professionid, professionname, workingplace, zipcode, isced, isced_name, publishdate, max_distance, jobrefid, exp_year, site_id FROM $table_ps_jobs";


            $where_sql = '';

            if ($search != '') {
                if ($where_sql != '') {
                    $where_sql .= ' AND ';
                }
                $where_sql .= $wpdb->prepare(" jobid LIKE '%%%s%%' OR jobproject_id LIKE '%%%s%%' OR jobproject_name LIKE '%%%s%%' OR jobname LIKE '%%%s%%' OR federalid LIKE '%%%s%%' OR federalname LIKE '%%%s%%' OR empgroup_id LIKE '%%%s%%' OR empgroup_name LIKE '%%%s%%' OR empgroup_type LIKE '%%%s%%' OR categoryid LIKE '%%%s%%' OR categoryname LIKE '%%%s%%' OR customformat_id LIKE '%%%s%%' OR customformat_name LIKE '%%%s%%' OR countryid LIKE '%%%s%%' OR countryname LIKE '%%%s%%' OR officeid LIKE '%%%s%%' OR officename LIKE '%%%s%%' OR worktimeid LIKE '%%%s%%' OR worktimename LIKE '%%%s%%' OR professionid LIKE '%%%s%%' OR professionname LIKE '%%%s%%' OR workingplace LIKE '%%%s%%' OR zipcode LIKE '%%%s%%' OR isced LIKE '%%%s%%' OR isced_name LIKE '%%%s%%' OR publishdate LIKE '%%%s%%' OR max_distance LIKE '%%%s%%' OR jobrefid LIKE '%%%s%%' OR exp_year LIKE '%%%s%%' OR site_id LIKE '%%%s%%' OR  ", $search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search	);
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
        function proSol_getDataCount($search = '', $orderby = 'name', $order = 'asc')
        {

            global $wpdb;

            $table_ps_jobs = $wpdb->prefix . 'jobs';

            $sql_select = "SELECT COUNT(*) FROM $table_ps_jobs";

            $where_sql = '';


            if ($search != '') {
                if ($where_sql != '') {
                    $where_sql .= ' AND ';
                }
                $where_sql .= $wpdb->prepare(" jobid LIKE '%%%s%%' OR jobproject_id LIKE '%%%s%%' OR jobproject_name LIKE '%%%s%%' OR jobname LIKE '%%%s%%' OR federalid LIKE '%%%s%%' OR federalname LIKE '%%%s%%' OR empgroup_id LIKE '%%%s%%' OR empgroup_name LIKE '%%%s%%' OR empgroup_type LIKE '%%%s%%' OR categoryid LIKE '%%%s%%' OR categoryname LIKE '%%%s%%' OR customformat_id LIKE '%%%s%%' OR customformat_name LIKE '%%%s%%' OR countryid LIKE '%%%s%%' OR countryname LIKE '%%%s%%' OR officeid LIKE '%%%s%%' OR officename LIKE '%%%s%%' OR worktimeid LIKE '%%%s%%' OR worktimename LIKE '%%%s%%' OR professionid LIKE '%%%s%%' OR professionname LIKE '%%%s%%' OR workingplace LIKE '%%%s%%' OR zipcode LIKE '%%%s%%' OR isced LIKE '%%%s%%' OR isced_name LIKE '%%%s%%' OR publishdate LIKE '%%%s%%' OR max_distance LIKE '%%%s%%' OR jobrefid LIKE '%%%s%%' OR exp_year LIKE '%%%s%%' OR site_id LIKE '%%%s%%' OR  ", $search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search,$search	);

            }

            if ($where_sql == '') {
                $where_sql = '1';
            }


            $sortingOrder = " ORDER BY $orderby $order ";


            $count = $wpdb->get_var("$sql_select  WHERE  $where_sql $sortingOrder");

            return $count;
        }


    }
