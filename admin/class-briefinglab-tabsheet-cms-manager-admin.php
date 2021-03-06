<?php

class Briefinglab_Tabsheet_CMS_Manager_Admin {

    private $version;

    private $options;

    private $data_model;

    private $js_configuration;

    function __construct( $version, $options, $data_model ) {
        $this->version = $version;
        $this->options = $options;
        $this->data_model = $data_model;
        $this->js_configuration = array();
        if(!WP_DEBUG) {
            $this->js_configuration['js_path'] = BRIEFINGLAB_TABSHEET_CMS_JS_PROD_PATH;
            $this->js_configuration['js_extension'] = $this->version . '.min.js';
        }else{
            $this->js_configuration['js_path'] = BRIEFINGLAB_TABSHEET_CMS_JS_PATH;
            $this->js_configuration['js_extension'] = 'js';
        }
    }

    function load_textdomain() {
        load_plugin_textdomain( 'briefinglab-tabsheet-cms', false, dirname( dirname( plugin_basename( __FILE__ ) ) )  . '/langs' );
    }

}