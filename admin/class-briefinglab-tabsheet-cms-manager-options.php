<?php

class Briefinglab_Tabsheet_CMS_Manager_Options {

    private $version;

    private $options;

    private $js_configuration;

    function __construct($version, $options) {
        $this->version = $version;
        $this->options = $options;
        if(!WP_DEBUG) {
            $this->js_configuration['css_path'] = BRIEFINGLAB_TABSHEET_CMS_CSS_PROD_PATH;
            $this->js_configuration['js_path'] = BRIEFINGLAB_TABSHEET_CMS_JS_PROD_PATH;
            $this->js_configuration['css_extension'] = $this->version . 'min.css';
            $this->js_configuration['js_extension'] = $this->version . 'min.js';
        }else{
            $this->js_configuration['css_path'] = BRIEFINGLAB_TABSHEET_CMS_CSS_PATH;
            $this->js_configuration['js_path'] = BRIEFINGLAB_TABSHEET_CMS_JS_PATH;
            $this->js_configuration['css_extension'] = 'css';
            $this->js_configuration['js_extension'] = 'js';
        }
    }

    public function register_scripts() { 
        wp_register_script( 'briefinglab-tabsheet-cms-admin-js', plugins_url( $this->js_configuration['js_path'] . 'briefinglab-tabsheet-cms-admin.' . $this->js_configuration['js_extension'], __FILE__ ) );
        wp_register_style( 'briefinglab-tabsheet-cms-admin-css', plugins_url( $this->js_configuration['css_path'] . 'briefinglab-tabsheet-cms-admin.' . $this->js_configuration['css_extension'], __FILE__ ) );
    }

    // echo '<div class="debug_"><pre>'.print_r($this->options,true).'</pre></div>';

    public function enqueue_scripts($hook) { 
      $post_types = explode('|||',$this->options['briefinglab-tabsheet-cms-post-type']);
        if(empty($post_types) || ('post.php' == $hook && in_array(get_post_type(),$post_types))){
            wp_localize_script('briefinglab-tabsheet-cms-admin-js', 'tabsheet_tablist',$this->options['briefinglab-tabsheet-cms-tab-list']);
            wp_enqueue_script('briefinglab-tabsheet-cms-admin-js');
            wp_enqueue_style('briefinglab-tabsheet-cms-admin-css');
            $this->js_wp_editor();
        }
    }

    function add_plugin_options_page() {
        add_options_page(
            'WP Tabsheet CMS options',
            __('Tabsheet CMS Options', 'briefinglab-tabsheet-cms'),
            'manage_options',
            'briefinglab-tabsheet-cms-options',
            array( $this, 'render_admin_options_page' )
        );
    }

    function render_admin_options_page() {
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2><?php _e( 'WP Tabsheet CMS options', 'briefinglab-tabsheet-cms' )?></h2>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'briefinglab-tabsheet-cms-options' );
                do_settings_sections( 'briefinglab-tabsheet-cms-options' );
                submit_button();
                ?>
            </form>
        </div>
    <?php wp_editor('','ididid');
    }

    function options_page_init() {
        register_setting(
            'briefinglab-tabsheet-cms-options', // Option group
            'briefinglab-tabsheet-cms-options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'briefinglab-tabsheet-cms-options', // ID
            __('General settings', 'briefinglab-tabsheet-cms'), // Title
            array( $this, 'print_section_info' ), // Callback
            'briefinglab-tabsheet-cms-options' // Page
        );

        add_settings_field(
            'briefinglab-tabsheet-cms-post-type',
            __( 'Post type', 'briefinglab-tabsheet-cms' ),
            array( $this, 'post_type_callback' ),
            'briefinglab-tabsheet-cms-options',
            'briefinglab-tabsheet-cms-options'
        );

        add_settings_field(
            'briefinglab-tabsheet-cms-tab-list',
            __( 'Tab list', 'briefinglab-tabsheet-cms' ),
            array( $this, 'tab_list_callback' ),
            'briefinglab-tabsheet-cms-options',
            'briefinglab-tabsheet-cms-options'
        );

    }

    public function print_section_info()
    {
        //_e( 'Enter your settings below:', 'briefinglab-tabsheet-cms' );
    }

    function sanitize( $input ) {

        foreach ($input as $key => $value){
            if( ! is_array( $value ) )
                $input[$key] =  sanitize_text_field($value);
        }

        if( $input['briefinglab-tabsheet-cms-post-type'] )
            $input['briefinglab-tabsheet-cms-post-type'] = implode( '|||', $input['briefinglab-tabsheet-cms-post-type'] );

        return $input;
    }

    public function post_type_callback() {
        $disabled = ( isset( $this->options['briefinglab-tabsheet-cms-entire-website'] ) && ( 1 == $this->options['briefinglab-tabsheet-cms-entire-website'] ) ) ? 'disabled="disabled"' : '';

        $value = isset( $this->options['briefinglab-tabsheet-cms-post-type'] ) ? esc_attr( $this->options['briefinglab-tabsheet-cms-post-type']) : '';
        $selected_post_types = explode( '|||', $value );

        $post_types = get_post_types( array(), 'objects');

        unset($post_types['attachment']);
        unset($post_types['revision']);
        unset($post_types['nav_menu_item']);

        $format = '<br /><input type="checkbox" class="briefinglab-tabsheet-cms-post-type" name="briefinglab-tabsheet-cms-options[briefinglab-tabsheet-cms-post-type][]" value="%s" %s %s/> %s';

        foreach( $post_types as $key => $value ){
            $checked = '';
            if( in_array( $key, $selected_post_types )) {
                $checked = 'checked="checked"';
            }

            printf( $format, $key, $checked, $disabled, $value->name );
        }

    }

    public function tab_list_callback() {
        $value = isset( $this->options['briefinglab-tabsheet-cms-tab-list'] ) ? esc_attr( $this->options['briefinglab-tabsheet-cms-tab-list']) : '';
        $description = '<p class="description">' . __('list the tabs to be showed splitted by ";" e.g: specifiche tecniche;descrizione;optionals', 'briefinglab-tabsheet-cms') . '</p>';
        $format = '<br /><input type="text" class="large-text" id="briefinglab-tabsheet-cms-tab-list" name="briefinglab-tabsheet-cms-options[briefinglab-tabsheet-cms-tab-list]" value="%s"/>%s';
        printf( $format, $value, $description);
    }


    function js_wp_editor( $settings = array() ) {
        if( ! class_exists( '_WP_Editors' ) )
            require( ABSPATH . WPINC . '/class-wp-editor.php' );
        $set = _WP_Editors::parse_settings( 'apid', $settings );

        if ( !current_user_can( 'upload_files' ) )
            $set['media_buttons'] = false;

        if ( $set['media_buttons'] ) {
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_script('media-upload');

            $post = get_post();
            if ( ! $post && ! empty( $GLOBALS['post_ID'] ) )
                $post = $GLOBALS['post_ID'];

            wp_enqueue_media( array(
                'post' => $post
            ) );
        }

        _WP_Editors::editor_settings( 'apid', $set );

        $ap_vars = array(
            'url' => get_home_url(),
            'includes_url' => includes_url()
        );

        wp_register_script( 'ap_wpeditor_init', dirname(plugin_dir_url(__FILE__)) . '/js/js-wp-editor.min.js', array( 'jquery' ), '1.1', true );
        wp_localize_script( 'ap_wpeditor_init', 'ap_vars', $ap_vars );
        wp_enqueue_script( 'ap_wpeditor_init' );
    }
}