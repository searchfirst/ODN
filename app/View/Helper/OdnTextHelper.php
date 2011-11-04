<?php
App::import('Vendor', 'markdown');
App::import('Vendor', 'smartypants');
App::import('Vendor', 'textile');
App::uses('TextHelper', 'View/Helper');
class OdnTextHelper extends TextHelper {
    public function plainSnippet($text) {
        $split_text = preg_split('/(\r*\n){2,}/',$text,2);
        $text = trim($split_text[0]);
        return $this->stripTextFragments($this->sanitise($text));
    }

    public function sanitise($text,$clean_entities=true,$force_strip_html=false) {
        if(!Configure::read('T.allow_html_in_descriptions') || $force_strip_html)
            $text = strip_tags($text,Configure::read('T.permitted_html_elements'));
        if($clean_entities) $text = htmlspecialchars($text,ENT_COMPAT,'UTF-8'); 
        return $text;
    }

    /*
     * Required options:
     *  media
     *  text
     * Other options:
     *  media_options
     */
    public function format($options=array()) {
        if(is_array($options) && !empty($options)) {
            $default_options = array('media_options'=>array(),'media'=>null);
            $options = array_merge($default_options,$options);
            $media = $options['media'];
            $media_options = $options['media_options'];
            $text = $options['text'];
            if(!preg_match('/{\[markdown\]}/',$text)) {
                $txtl = new Textile;
                $text = $txtl->TextileThis($text);
            } else {
                $text = str_replace('{[markdown]}','',$text);
                $text = SmartyPants(Markdown($text),1);
            }
            $text = $this->sanitise($text,false);
            return $text;
        }
    }
}
