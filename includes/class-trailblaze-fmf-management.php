<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://trailblazecreative.com/
 * @since      1.0.0
 *
 * @package    Trailblaze_Fmf_Management
 * @subpackage Trailblaze_Fmf_Management/includes
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
 * @package    Trailblaze_Fmf_Management
 * @subpackage Trailblaze_Fmf_Management/includes
 * @author     TrailBlaze Creative <info@trailblazecreative.com>
 */
class Trailblaze_Fmf_Management {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Trailblaze_Fmf_Management_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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
		if ( defined( 'TRAILBLAZE_FMF_MANAGEMENT_VERSION' ) ) {
			$this->version = TRAILBLAZE_FMF_MANAGEMENT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'trailblaze-fmf-management';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Trailblaze_Fmf_Management_Loader. Orchestrates the hooks of the plugin.
	 * - Trailblaze_Fmf_Management_i18n. Defines internationalization functionality.
	 * - Trailblaze_Fmf_Management_Admin. Defines all hooks for the admin area.
	 * - Trailblaze_Fmf_Management_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-trailblaze-fmf-management-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-trailblaze-fmf-management-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-trailblaze-fmf-management-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-trailblaze-fmf-management-public.php';

		$this->loader = new Trailblaze_Fmf_Management_Loader();


        /**
         * The class responsible for integrating ACF.
         */
        if ( !class_exists( 'Trailblaze_Fmf_Management_ACF_Integrate ' ) ) {
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-trailblaze-fmf-management-acf-integrate.php';
            new Trailblaze_Fmf_Management_ACF_Integrate($this->get_plugin_name(), $this->get_version());
        }

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Trailblaze_Fmf_Management_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Trailblaze_Fmf_Management_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Trailblaze_Fmf_Management_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $this->loader->add_action( 'acf/init', $plugin_admin, 'acf_add_local_field_groups' );

        // add filter gform_field_value_your_parameter
        // $this->loader->add_filter( 'user_register', $plugin_admin, 'after_user_registration', 10, 4 );

        // add init action to setup pdf rewrite rules
        $this->loader->add_action( 'init', $plugin_admin, 'setup_pdf_rewrite_rules' );

        $this->loader->add_filter('query_vars', $plugin_admin, 'add_query_vars');

        // hook up add_admin_menu
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Trailblaze_Fmf_Management_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


        // add_filter( 'gform_confirmation', 'custom_confirmation', 10, 4 );
        $this->loader->add_filter('gform_confirmation', $plugin_public, 'custom_confirmation', 10, 4 );


        // add template_include filter to load pdf template
        $this->loader->add_filter( 'template_include', $plugin_public, 'load_pdf_template', 10, 1 );

        $this->loader->add_filter( 'gform_field_value_raffle_ticket_number', $plugin_public, 'get_raffle_ticket_number' );

        
        // $this->loader->add_action( 'user_register', $plugin_public, 'after_user_registration', 10, 4 );

        // gform_after_submission
        $this->loader->add_action( 'gform_after_submission', $plugin_public, 'gform_after_submission', 10, 2 );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Trailblaze_Fmf_Management_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
