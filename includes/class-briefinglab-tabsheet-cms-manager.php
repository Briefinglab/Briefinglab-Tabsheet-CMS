<?php

/**
 * The Manager is the core plugin responsible for including and
 * instantiating all of the code that composes the plugin
 */

/**
 * The Manager is the core plugin responsible for including and
 * instantiating all of the code that composes the plugin.
 *
 * The Manager includes an instance to the Loader which is 
 * responsible for coordinating the hooks that exist within the plugin.
 *
 * It also maintains a reference to the plugin slug which can be used in
 * internationalization, and a reference to the current version of the plugin
 * so that we can easily update the version in a single place to provide
 * cache busting functionality when including scripts and styles.
 *
 * @since 1.0.0
 */
class Briefinglab_Tabsheet_CMS_Manager {

    /**
     * A reference to the loader class that coordinates the hooks and callbacks
     * throughout the plugin.
     *
     * @access protected
     * @var PLUGIN_CLASS_NAME_BASE_Loader $loader Manages hooks between the WordPress hooks and the callback functions.
     */
    protected $loader;

    /**
     * Represents the slug of the plugin that can be used throughout the plugin
     * for internationalization and other purposes.
     *
     * @access protected
     * @var string $plugin_slug The single, hyphenated string used to identify this plugin.
     */
    protected $plugin_slug;

    /**
     * Maintains the current version of the plugin so that we can use it throughout
     * the plugin.
     *
     * @access protected
     * @var string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Store the options set for the plugin (if there are) to be used as context in the admin e public side.
     *
     * @access protected
     * @var string $options The current options set for the plugin.
     */
    protected $options;

    /**
     * Instantiates the plugin by setting up the core properties and loading
     * all necessary dependencies and defining the hooks.
     *
     * The constructor will define both the plugin slug and the verison
     * attributes, but will also use internal functions to import all the
     * plugin dependencies, and will leverage the Single_Post_Meta_Loader for
     * registering the hooks and the callback functions used throughout the
     * plugin.
     */
    public function __construct() {


        $this->plugin_slug = 'briefinglab-tabsheet-cms';
        $this->version = '1.0.0';
        $this->options = get_option( 'briefinglab-tabsheet-cms-options' );

        $this->load_dependencies();
        $this->define_register_activation_hook();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }



    /**
     * Imports the Classes needed to make the plugin working.
     *
     * The Manager administration class defines all unique functionality for
     * introducing custom functionality into the WordPress dashboard.
     *
     * The Manager public class defines all unique functionality for
     * introducing custom functionality into the public side.
	 *	
     * The Loader is the class that will coordinate the hooks and callbacks
     * from WordPress and the plugin. This function instantiates and sets the reference to the
     * $loader class property.
     *
     * @access private
     */
    private function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-briefinglab-tabsheet-cms-config.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-briefinglab-tabsheet-cms-model.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-briefinglab-tabsheet-cms-manager-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-briefinglab-tabsheet-cms-manager-options.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-briefinglab-tabsheet-cms-manager-public.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-briefinglab-tabsheet-cms-theme-functions.php';

        require_once plugin_dir_path( __FILE__ ) . 'class-briefinglab-tabsheet-cms-loader.php';
        $this->loader = new Briefinglab_Tabsheet_CMS_Loader();

    }

    /**
     * Defines the hooks and callback functions that are used for setting up the plugin stylesheets, scripts, logic
     * and the plugin's meta box.
     *
     * @access private
     */
    private function define_admin_hooks() {

        $data_model = Briefinglab_Tabsheet_CMS_Model::getInstance();
        $admin = new Briefinglab_Tabsheet_CMS_Manager_Admin( $this->version, $this->options, $data_model);
        $admin_options = new Briefinglab_Tabsheet_CMS_Manager_Options( $this->version, $this->options );

        $this->loader->add_action( 'admin_init', $admin_options, 'register_scripts' );
        $this->loader->add_action( 'admin_enqueue_scripts', $admin_options, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $admin_options, 'add_plugin_options_page' );
        $this->loader->add_action( 'admin_init', $admin_options, 'options_page_init' );
        $this->loader->add_action( 'init', $admin, 'load_textdomain' );

    }

    /**
     * Defines the hooks and callback functions that are used for rendering information on the front
     * end of the site.
     *
     * @access private
     */
    private function define_public_hooks() {

        $data_model = Briefinglab_Tabsheet_CMS_Model::getInstance();
        $public = new Briefinglab_Tabsheet_CMS_Manager_Public( $this->version, $this->options, $data_model);

        Briefinglab_Tabsheet_CMS_Theme_Functions::define_theme_functions();

        $this->loader->add_action( 'init', $public, 'add_shortcode' );

    }

    private function define_register_activation_hook() {

    }

    /**
     * Sets this class into motion.
     *
     * Executes the plugin by calling the run method of the loader class which will
     * register all of the hooks and callback functions used throughout the plugin
     * with WordPress.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Returns the current version of the plugin to the caller.
     *
     * @return string $this->version The current version of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}