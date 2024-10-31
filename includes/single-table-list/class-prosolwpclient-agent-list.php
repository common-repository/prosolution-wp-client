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

    class CBXProSolWpClientAgent_List_Table extends WP_List_Table
    {

        function __construct()
        {
            global $status, $page;

            //Set parent defaults
            parent::__construct(array(
                                    'singular' => 'pswpagentlist',     //singular name of the listed records
                                    'plural'   => 'pswpagentlists',    //plural name of the listed records
                                    'ajax'     => false      //does this table support ajax?
                                ));

        }


        /**
         * Callback for collumn 'agentId'
         *
         * @param array $item
         *
         * @return string
         */

        function column_agentId($item)
        {
            return stripslashes($item['agentId']);
        }

        /**
         * Callback for collumn 'name'
         *
         * @param array $item
         *
         * @return string
         */

        function column_name($item)
        {
            return stripslashes($item['name']);
        }

        /**
         * Callback for collumn 'gender'
         *
         * @param array $item
         *
         * @return string
         */

        function column_gender($item)
        {
            return stripslashes($item['gender']);
        }

        /**
         * Callback for collumn 'position'
         *
         * @param array $item
         *
         * @return string
         */

        function column_position($item)
        {
            return stripslashes($item['position']);
        }

        /**
         * Callback for collumn 'email'
         *
         * @param array $item
         *
         * @return string
         */

        function column_email($item)
        {
            return stripslashes($item['email']);
        }

        /**
         * Callback for collumn 'phone1'
         *
         * @param array $item
         *
         * @return string
         */

        function column_phone1($item)
        {
            return stripslashes($item['phone1']);
        }

        /**
         * Callback for collumn 'phone2'
         *
         * @param array $item
         *
         * @return string
         */

        function column_phone2($item)
        {
            return stripslashes($item['phone2']);
        }

        /**
         * Callback for collumn 'phonemobile'
         *
         * @param array $item
         *
         * @return string
         */

        function column_phonemobile($item)
        {
            return stripslashes($item['phonemobile']);
        }

        /**
         * Callback for collumn 'customtext'
         *
         * @param array $item
         *
         * @return string
         */

        function column_customtext($item)
        {
            return stripslashes($item['customtext']);
        }

        /**
         * Callback for collumn 'signatureuuid'
         *
         * @param array $item
         *
         * @return string
         */

        function column_signatureuuid($item)
        {
            return stripslashes($item['signatureuuid']);
        }

        /**
         * Callback for collumn 'photouuid'
         *
         * @param array $item
         *
         * @return string
         */

        function column_photouuid($item)
        {
            return stripslashes($item['photouuid']);
        }

        /**
         * Callback for collumn 'site_id'
         *
         * @param array $item
         *
         * @return string
         */

        function column_site_id($item)
        {
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
         * @param array $proSol_column_name The name/slug of the column to be processed
         *
         * @return string Text or HTML to be placed inside the column <td>
         **************************************************************************/
        function proSol_column_default($item, $proSol_column_name)
        {

            switch ($proSol_column_name) {
                case 'agentId':
                    return $item[$proSol_column_name];
                case 'name':
                    return $item[$proSol_column_name];
                case 'gender':
                    return $item[$proSol_column_name];
                case 'position':
                    return $item[$proSol_column_name];
                case 'email':
                    return $item[$proSol_column_name];
                case 'phone1':
                    return $item[$proSol_column_name];
                case 'phone2':
                    return $item[$proSol_column_name];
                case 'phonemobile':
                    return $item[$proSol_column_name];
                case 'customtext':
                    return $item[$proSol_column_name];
                case 'signatureuuid':
                    return $item[$proSol_column_name];
                case 'photouuid':
                    return $item[$proSol_column_name];
                case 'site_id':
                    return $item[$proSol_column_name];    
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
'agentId'       => esc_html__('Agent Id', 'prosolwpclient'),
'name'          => esc_html__('Name', 'prosolwpclient'),
'gender'        => esc_html__('Gender', 'prosolwpclient'),
'position'      => esc_html__('Position', 'prosolwpclient'),
'email'         => esc_html__('Email', 'prosolwpclient'),
'phone1'        => esc_html__('Phone1', 'prosolwpclient'),
'phone2'        => esc_html__('Phone2', 'prosolwpclient'),
'phonemobile'   => esc_html__('Phone mobile', 'prosolwpclient'),
'customtext'    => esc_html__('Custom text', 'prosolwpclient'),
'signatureuuid' => esc_html__('Signature uuid', 'prosolwpclient'),
'photouuid'     => esc_html__('Photo uuid', 'prosolwpclient'),
'site_id'     => esc_html__('Site id', 'prosolwpclient')
            );

            return $columns;
        }


        function proSol_get_sortable_columns()
        {
            $sortable_columns = array(
                'agentId'       => array('agentId', false),     //true means it's already sorted
                'name'          => array('name', false),
                'gender'        => array('gender', false),
                'position'      => array('position', false),
                'email'         => array('email', false),
                'phone1'        => array('phone1', false),
                'phone2'        => array('phone2', false),
                'phonemobile'   => array('phonemobile', false),
                'customtext'    => array('customtext', false),
                'signatureuuid' => array('signatureuuid', false),
                'photouuid'     => array('photouuid', false),
                'site_id'     => array('site_id', false)
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

            global $wpdb;global $prosol_prefix;

            $table_ps_agent = $prosol_prefix . 'agent';


            $sql_select = "SELECT agentId,name,gender,position,email,phone1,phone2,phonemobile,customtext,signatureuuid,photouuid,site_id FROM $table_ps_agent";


            $where_sql = '';

            if ($search != '') {
                if ($where_sql != '') {
                    $where_sql .= ' AND ';
                }
                $where_sql .= $wpdb->prepare(" agentId LIKE '%%%s%%' 
                OR name LIKE '%%%s%%' OR gender LIKE '%%%s%%' 
                OR position LIKE '%%%s%%' OR email LIKE '%%%s%%' 
                OR phone1 LIKE '%%%s%%' OR phone2 LIKE '%%%s%%' 
                OR phonemobile LIKE '%%%s%%' OR customtext LIKE '%%%s%%' 
                OR signatureuuid LIKE '%%%s%%' OR photouuid LIKE '%%%s%%' 
                OR site_id LIKE '%%%s%%'",
                                             $search, $search, $search, $search,
                                             $search, $search, $search, $search,
                                             $search, $search, $search, $search);
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

            global $wpdb;global $prosol_prefix;

            $table_ps_agent = $prosol_prefix . 'agent';

            $sql_select = "SELECT COUNT(*) FROM $table_ps_agent";

            $where_sql = '';


            if ($search != '') {
                if ($where_sql != '') {
                    $where_sql .= ' AND ';
                }
                $where_sql .= $wpdb->prepare(" agentId LIKE '%%%s%%' 
                OR name LIKE '%%%s%%' OR gender LIKE '%%%s%%' 
                OR position LIKE '%%%s%%' OR email LIKE '%%%s%%' 
                OR phone1 LIKE '%%%s%%' OR phone2 LIKE '%%%s%%' 
                OR phonemobile LIKE '%%%s%%' OR customtext LIKE '%%%s%%' 
                OR signatureuuid LIKE '%%%s%%' OR photouuid LIKE '%%%s%%' 
                OR site_id LIKE '%%s%%'",
                                             $search, $search, $search, $search,
                                             $search, $search, $search, $search,
                                             $search, $search, $search, $search);
            }

            if ($where_sql == '') {
                $where_sql = '1';
            }


            $sortingOrder = " ORDER BY $orderby $order ";


            $count = $wpdb->get_var("$sql_select  WHERE  $where_sql $sortingOrder");

            return $count;
        }


    }
