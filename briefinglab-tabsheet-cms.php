<?php
/**
 * The file responsible for starting the plugin
 *
 * WordPress plugin to manage content of a post (or custom post) in tabs
 * *
 * @wordpress-plugin
 * Plugin Name: Briefinglab Tabsheet CMS
 * Plugin URI: https://github.com/Briefinglab/Briefinglab-Tabsheet-CMS
 * Description: WordPress plugin to manage content of a post (or custom post) in tabs
 * Version: 1.0.0
 * Author: Briefinglab
 * Author URI: http://briefinglab.com
 * Text Domain: Briefinglab-Tabsheet-CMS
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /langs
 */

// If this file is called directly, then abort execution.
if (!defined('WPINC')) {
    die;
}

/**
 * Include the core class responsible for loading all necessary components of the plugin.
 */
require_once plugin_dir_path(__FILE__) . 'includes/class-briefinglab-tabsheet-cms-manager.php';

/**
 * Instantiates the Manager class and then
 * calls its run method officially starting up the plugin.
 */
function run_briefinglab_tabsheet_cms_manager()
{

    $onlimag = new briefinglab_tabsheet_cms_Manager();
    $onlimag->run();

}

// Call the above function to begin execution of the plugin.
run_briefinglab_tabsheet_cms_manager();