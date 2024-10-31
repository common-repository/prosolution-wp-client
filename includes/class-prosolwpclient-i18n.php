<?php

	// If this file is called directly, abort.
	if (!defined('WPINC')) {
		die;
	}
?>
<?php

    /**
     * Define the internationalization functionality
     *
     * Loads and defines the internationalization files for this plugin
     * so that it is ready for translation.
     *
     * @link       https://www.prosolution.com
     * @since      1.0.0
     *
     * @package    Prosolwpclient
     * @subpackage Prosolwpclient/includes
     */

    /**
     * Define the internationalization functionality.
     *
     * Loads and defines the internationalization files for this plugin
     * so that it is ready for translation.
     *
     * @since      1.0.0
     * @package    Prosolwpclient
     * @subpackage Prosolwpclient/includes
     * @author     ProSolution <helpdesk@prosolution.com>
     */
    class CBXProSolWpClient_i18n
    {


        /**
         * Load the plugin text domain for translation.
         *
         * @since    1.0.0
         */
        public function proSol_loadPluginTextdomain()
        {
            load_plugin_textdomain_custom(
                'prosolwpclient',
                false,
                dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
            );

        }


    }
