<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}
?>
<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.prosolution.com
 * @since      1.0.0
 *
 * @package    Prosolwpclient
 * @subpackage Prosolwpclient/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Prosolwpclient
 * @subpackage Prosolwpclient/includes
 * @author     ProSolution <helpdesk@prosolution.com>
 */
class CBXProSolWpClient {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      CBXProSolWpClient_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = PROSOLWPCLIENT_PLUGIN_NAME;
		$this->version     = PROSOLWPCLIENT_PLUGIN_VERSION;
		
		$this->proSol_loadDependencies();
		$this->proSol_setlocale();
		$this->proSol_defineadminHooks();
		$this->proSol_definePublicHooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CBXProSolWpClient_Loader. Orchestrates the hooks of the plugin.
	 * - CBXProSolWpClient_i18n. Defines internationalization functionality.
	 * - Prosolwpclient_Admin. Defines all hooks for the admin area.
	 * - Prosolwpclient_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function proSol_loadDependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prosolwpclient-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prosolwpclient-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-prosolwpclient-admin.php';

		require_once plugin_dir_path( __FILE__ ) . 'class-setting.php';

		/**
		 * all table listing template
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-setting-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-jobstamp-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-jobs-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-country-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-office-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-agent-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-workpermit-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-staypermit-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-availability-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-federal-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-marital-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-title-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-skillgroup-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-skill-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-skillrate-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-professiongroup-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-profession-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-education-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-educationlookup-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-recruitmentsource-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-qualification-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-qualificationeval-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-filecategoryemp-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-contract-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-employment-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-experienceposition-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-operationarea-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-operationarea-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-nace-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-isced-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-customfields-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-worktime-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/single-table-list/class-prosolwpclient-jobcustomfields-list.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prosolwpclient-functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prosolwpclient-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-prosolwpclient-table-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/UploadHandler.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-prosolwpclient-public.php';


		$this->loader = new CBXProSolWpClient_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the CBXProSolWpClient_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function proSol_setlocale() {

		$plugin_i18n = new CBXProSolWpClient_i18n();

		$this->loader->proSol_add_action( 'plugins_loaded', $plugin_i18n, 'proSol_loadPluginTextdomain' );

		// project 1440, set custom cron interval
		$dailytask = new CBXProSolWpClient_TableHelper(); 
		$this->loader->proSol_add_filter( 'cron_schedules', $dailytask, 'proSol_cron_interval' );
		$this->loader->proSol_add_action( 'wp_ajax_proSol_dailytask_tableJobs', $dailytask, 'proSol_dailytask_tableJobs' );		
		
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function proSol_defineadminHooks() {

		$plugin_admin = new CBXProSolWpClient_Admin( $this->proSol_get_plugin_name(), $this->proSol_get_version() );
		
		//setting init and add  setting sub menu in setting menu
		$this->loader->proSol_add_action( 'admin_init', $plugin_admin, 'proSol_adminInitCallback' );
		$this->loader->proSol_add_action( 'admin_notices', $plugin_admin, 'proSol_adminNotices' );
		//$this->loader->proSol_add_action( 'admin_menu', $plugin_admin, 'myRenamedPlugin' );

		//create admin menu page
		$this->loader->proSol_add_action( 'admin_menu', $plugin_admin, 'proSol_adminPages' );

		$this->loader->proSol_add_action( 'admin_enqueue_scripts', $plugin_admin, 'proSol_enqueueStyles_Admin' );
		$this->loader->proSol_add_action( 'admin_enqueue_scripts', $plugin_admin, 'proSol_enqueueScripts_Admin' );
		$this->loader->proSol_add_action( "wp_ajax_proSol_ajaxTablesync", $plugin_admin, "proSol_ajaxTablesync" );
		$this->loader->proSol_add_action( "wp_ajax_proSol_ajaxClearlog", $plugin_admin, "proSol_ajaxClearlog" );

		$this->loader->proSol_add_filter( 'set-screen-option', $plugin_admin, 'proSol_pswp_table_results_per_page', 10, 3 );
		
		//URL check validity
		$this->loader->proSol_add_action( "wp_ajax_proSol_url_validate", $plugin_admin, "proSol_url_validate" );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function proSol_definePublicHooks() {

		$plugin_public = new CBXProSolWpClient_Public( $this->proSol_get_plugin_name(), $this->proSol_get_version() );
		
		$this->loader->proSol_add_action( 'template_redirect', $plugin_public, 'proSol_prosolwpclientFrontendFormsubmit' );

		$this->loader->proSol_add_action( 'wp_ajax_proSol_groupSelectionToTrainingCallback', $plugin_public, 'proSol_groupSelectionToTrainingCallback' );
		$this->loader->proSol_add_action( 'wp_ajax_nopriv_proSol_groupSelectionToTrainingCallback', $plugin_public, 'proSol_groupSelectionToTrainingCallback' );

		$this->loader->proSol_add_action( 'wp_ajax_proSol_goupDataIdCallback', $plugin_public, 'proSol_goupDataIdCallback' );
		$this->loader->proSol_add_action( 'wp_ajax_nopriv_proSol_goupDataIdCallback', $plugin_public, 'proSol_goupDataIdCallback' );

		$this->loader->proSol_add_action( 'wp_ajax_proSol_countrySelectionToFederalCallback', $plugin_public, 'proSol_countrySelectionToFederalCallback' );
		$this->loader->proSol_add_action( 'wp_ajax_nopriv_proSol_countrySelectionToFederalCallback', $plugin_public, 'proSol_countrySelectionToFederalCallback' );

		// blueimp file upload
		$this->loader->proSol_add_action( 'wp_ajax_proSol_fileUploadProcess', $plugin_public, 'proSol_fileUploadProcess' );
		$this->loader->proSol_add_action( 'wp_ajax_nopriv_proSol_fileUploadProcess', $plugin_public, 'proSol_fileUploadProcess' );

		$this->loader->proSol_add_action( 'wp_ajax_proSol_fileUploadModalProcess', $plugin_public, 'proSol_fileUploadModalProcess' );
		$this->loader->proSol_add_action( 'wp_ajax_nopriv_proSol_fileUploadModalProcess', $plugin_public, 'proSol_fileUploadModalProcess' );

		$this->loader->proSol_add_action( 'wp_ajax_proSol_fileDeleteProcess', $plugin_public, 'proSol_fileDeleteProcess' );
		$this->loader->proSol_add_action( 'wp_ajax_nopriv_proSol_fileDeleteProcess', $plugin_public, 'proSol_fileDeleteProcess' );

		// job application submit
		$this->loader->proSol_add_action( 'wp_ajax_proSol_applicationSubmitProcess', $plugin_public, 'proSol_applicationSubmitProcess' );
		$this->loader->proSol_add_action( 'wp_ajax_nopriv_proSol_applicationSubmitProcess', $plugin_public, 'proSol_applicationSubmitProcess' );
		
		// pagination, job search
		$this->loader->proSol_add_action( 'wp_ajax_proSol_paginationjobsearch', $plugin_public, 'proSol_paginationjobsearch' );
		$this->loader->proSol_add_action( 'wp_ajax_nopriv_proSol_paginationjobsearch', $plugin_public, 'proSol_paginationjobsearch' );
	
		$this->loader->proSol_add_action( 'wp_enqueue_scripts', $plugin_public, 'proSol_enqueueStyles' );
		$this->loader->proSol_add_action( 'wp_enqueue_scripts', $plugin_public, 'proSol_enqueueScripts' );

		//cron
		$this->loader->proSol_add_action( 'init', $plugin_public, 'proSol_autoSync' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function proSol_runs() {
		$this->loader->proSol_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function proSol_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    CBXProSolWpClient_Loader    Orchestrates the hooks of the plugin.
	 */
	public function proSol_get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function proSol_get_version() {
		return $this->version;
	}

}
