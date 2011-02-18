<?php
App::import('Vendor', 'markdown');
App::import('Vendor', 'smartypants');
App::import('Vendor', 'textile');
class THelper extends Helper {
	/*	Passing the model name to the functions is to help the media assistant construct
		urls on pages where the parent model classname may differ from the active model name
		(eg. 'product' when the page is a categories view).
	*/
	var $helpers = array('Html');
	var $fragmentCount = 0;
	var $fragmentMatches = 0;

	function plainSnippet($text) {
		$split_text = preg_split('/(\r*\n){2,}/',$text,2);
		$text = trim($split_text[0]);
		return $this->stripTextFragments($this->sanitiseText($text));
	}

	function sanitiseText($text,$clean_entities=true,$force_strip_html=false) {
		return $this->sanitise($text,$clean_entities,$force_strip_html);
	}

	function sanitise($text,$clean_entities=true,$force_strip_html=false) {
		if(!Configure::read('T.allow_html_in_descriptions') || $force_strip_html)
			$text = strip_tags($text,Configure::read('T.permitted_html_elements'));
		if($clean_entities) $text = htmlspecialchars($text,ENT_COMPAT,'UTF-8');	
		return $text;
	}

	function link($title,$url) {
		return $this->Html->link($this->sanitiseText($title,false,true),$url);
	}

	function htmlFormattedSnippet($text,$media=false,$model=null,$media_link_attributes=null) {
		$split_text = preg_split('/(\r*\n){2,}/',$text,2);
		$text = $split_text[0];
		return $this->htmlFormatted($text,$media,$model,$media_link_attributes);
	}

	function htmlFormattedSnippet2($text,$media=false,$model=null,$media_link_attributes=null) {
		$split_text = preg_split('/(\r*\n){2,}/',$text,3);
		$text = $split_text[0];
		if(!empty($split_text[1])) $text .= "\r\n\r\n".$split_text[1];
		return $this->htmlFormatted($text,$media,$model,$media_link_attributes);
	}

	function htmlFormatted($text,$media=false,$model=null,$media_options=array()) {
		$text = $this->sanitiseText($text,false);
		$text = SmartyPants(Markdown($text));
		if($media && count($media))
			$text = $this->formatTextFragments($text,$media,$model,$media_options);
		else
			$text = $this->stripTextFragments($text);
		return $text;}

	function format($options=array()) {
		/*
		 * Required options:
		 *	media
		 *	model
		 *	text
		 * Other options:
		 *	media_options
		 */
		if(is_array($options) && !empty($options)) {
			$default_options = array('media_options'=>array(),'media'=>null,'model'=>$this->params['models'][0]);
			$options = array_merge($default_options,$options);
			$media = $options['media'];
			$model = $options['model'];
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

	private function formatTextFragments($text,$media,$model=null,$media_options=array()) {
		$this->callbackDataResource = $media;
		$this->callbackData = array('model'=>$model);
		if(!empty($media_options['html_attributes']))
			$this->callbackData['html_attributes'] = $media_options['html_attributes'];
		if(!empty($media_options['conversion_parameter']))
			$this->callbackData['conversion_parameter'] = $media_options['conversion_parameter'];
		if(!empty($media_options['link']))
			$this->callbackData['link'] = $media_options['link'];
		if(!empty($media_options['link_attributes']))
			$this->callbackData['link_attributes'] = $media_options['link_attributes'];
		$this->fragmentMatches = preg_match_all('/\{\[media\]([^\[\]{}]*)\}(\[[a-zA-Z]+\])?/',$text, $matches);
		$text = preg_replace_callback('/\{\[media\]([^\[\]{}]*)\}(\[[a-zA-Z]+\])?/',array($this,'mediaFragment'),$text,-1);
		return $text;
	}

	private function stripTextFragments($text) {
		return preg_replace('/\{\[(media)\][^\}]*\}(\[[a-zA-Z]+\])?/','',$text);
	}

	private function mediaFragment($matches) {
		static $i=0;
		$cb_options = $this->callbackData;
		$cb_options['data'] = !empty($this->callbackDataResource[$i])?$this->callbackDataResource[$i]:null;
		$options = array();
		if(!empty($matches[2])) {
			$conversion_parameter = str_replace(array('[',']'),array('',''),$matches[2]);
			if(!empty($conversion_parameter)) $options['conversion_parameter'] = $conversion_parameter;
		}
		$options = array_merge($cb_options,$options);
		$callback_string = $this->MediaAssistant->media($options);
		if($i>=($this->fragmentMatches-1)) $i=0;
		else $i++;
		return $callback_string;
	}
}
