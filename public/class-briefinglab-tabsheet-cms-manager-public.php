<?php

class Briefinglab_Tabsheet_CMS_Manager_Public {

    private $version;

    private $options;

    private $data_model;

    private $js_configuration;

    static private $occurence = 0;
    static $ids = array();

    function __construct( $version, $options, $data_model ) {
        $this->version = $version;
        $this->options = $options;
        $this->data_model = $data_model;
        $this->js_configuration = array();
        if(WP_DEBUG == false) {
            $this->js_configuration['js_path'] = BRIEFINGLAB_TABSHEET_CMS_JS_PROD_PATH;
            $this->js_configuration['js_extension'] = $this->version . '.min.js';
        }else{
            $this->js_configuration['js_path'] = BRIEFINGLAB_TABSHEET_CMS_JS_PATH;
            $this->js_configuration['js_extension'] = 'js';
        }
    }

    public function tabsheet_fn($attr,$content=''){
        
        
        $html = '';
        if(self::$occurence == 0){
            $html .= '<ul class="nav nav-tabs" role="tablist">'."\n";
            foreach (self::$ids as $k=>$v){ 
                $id = trim(str_replace(array('"','id='),'',$v));
                echo $id;
                $html .= '<li '.(!$k?'class="active"':'').'><a href="#tab'.$id.'" role="tab" data-toggle="tab"><i class="fa fa-'.$id.' pr-10"></i> '.str_replace('-',' ',ucfirst($id)).'</a></li>';
            }
            $html.='</ul><div class="tab-content">';
        }

        $id = $attr['id'];
        $title = str_replace('-',' ',ucfirst($id));

        $html .= '<div class="tab-pane fade in '.(self::$occurence == 0?'active':'').'" id="tab'.$id.'">'."\n";
        $html .= '<h5 class="text-center title">'.$title.'</h5>'."\n";
        $html .= '<div class="space"></div>'."\n";
        $html .= '<div class="row">'."\n";
        $html .= '<div class="col-md-12">'."\n";
        $html .= $content."\n";
        $html .= '</div></div></div>'."\n";

        
        self::$occurence++;
        $html .= (self::$occurence == count(self::$ids) ? '</div>':'');

        return $html;


    }

    /**
     * [tabsheet_detect_shortcode description]
     * @return [type] [description]
     */
    function tabsheet_detect_shortcode()
    {
        global $post;
        $pattern = get_shortcode_regex();
        
        if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
            && array_key_exists( 2, $matches )
            && array_key_exists( 3, $matches )
            && in_array( 'tabsheet', $matches[2] ) )
        {
            
        self::$ids = $matches[3];
            
        }
    }

    function add_shortcode(){

        add_action( 'wp', array($this,'tabsheet_detect_shortcode') );
        add_shortcode('tabsheet', array($this,'tabsheet_fn') );
    }

}