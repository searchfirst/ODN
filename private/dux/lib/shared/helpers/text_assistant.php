<?php
vendor('markdown');
vendor('smartypants');

class TextAssistantHelper extends Helper {
	/*	Passing the model name to the functions is to help the media assistant construct
		urls on pages where the parent model classname may differ from the active model name
		(eg. 'product' when the page is a categories view). If empty, the function will attempt
		to discover it automatically.
	*/
	var $helpers = array('MediaAssistant','Html');
	var $fragmentCount = 0;
	var $fragmentMatches = 0;
	
	function TextAssistantHelper() {}
	
	function plainSnippet($text) {
		$split_text = preg_split('/(\r*\n){2,}/',$text,2);
		$text = $split_text[0];
		return $this->sanitiseText($text);
	}
	
	function sanitiseText($text,$clean_entities=true,$force_strip_html=false) {
		if(!MOONLIGHT_ALLOW_HTML_IN_DESCRIPTIONS || $force_strip_html)
			$text = strip_tags($text,MOONLIGHT_PERMITTED_HTML_ELEMENTS);
		if($clean_entities) $text = htmlspecialchars($text,ENT_COMPAT,'UTF-8');
		return $text;
	}
	
	function link($title,$url) {
		return $this->Html->link($this->sanitiseText($title,true,true),$url);
	}
	
	function htmlFormattedSnippet($text,$media=false,$model=null,$media_link_attributes=null) {
		$split_text = preg_split('/(\r*\n){2,}/',$text,2);
		$text = $split_text[0];
		return $this->htmlFormatted($text,$media,$model,$media_link_attributes);}
	
	function htmlFormattedSnippet2($text,$media=false,$model=null,$media_link_attributes=null) {
		$split_text = preg_split('/(\r*\n){2,}/',$text,3);
		$text = $split_text[0];
		if(!empty($split_text[1])) $text .= "\r\n\r\n".$split_text[1];
		return $this->htmlFormatted($text,$media,$model,$media_link_attributes);
	}
	
	function htmlFormatted($text,$media=false,$model=null,$media_link_attributes=null) {
		$text = $this->sanitiseText($text,false);
		$text = SmartyPants(Markdown($text));
		if($media && count($media))
			$text = $this->_formatTextFragments($text,$media,$model,$media_link_attributes);
		else
			$text = $this->_stripTextFragments($text);
		return $text;}
	
	function _formatTextFragments($text,$media,$model=null,$media_link_attributes=null) {
		$this->callbackData = array('media'=>$media,'model'=>$model,'media_link_attributes'=>$media_link_attributes);
		$this->fragmentMatches = preg_match_all('/\{\[media\]([^\[\]{}]*)\}(\[[a-zA-Z]+\])?/',$text, $matches);
		$text = preg_replace_callback('/\{\[media\]([^\[\]{}]*)\}(\[[a-zA-Z]+\])?/',array($this,'_mediaFragmentCallback'),$text,-1);
		//Eventually add in callbacks for other fragments for linking to products, articles, categories, and pages
		return $text;
	}
	
	function _stripTextFragments($text) {
		return preg_replace('/\{\[(media)\][^\}]*\}(\[[a-zA-Z]+\])?/','',$text);
	}


	function _mediaFragmentCallback($matches) {
		static $i=0;
		if(!isset($this->callbackData['media_link_attributes'][$i]))
			$this->callbackData['media_link_attributes'][$i]=null;
		if(!empty($matches[2])) {
			$matches[2] = str_replace(array('[',']'),array('',''),$matches[2]);
			$media_class = $matches[2];
			$conversion_type = $matches[2];
		} else {
			$media_class = "inline";
			$conversion_type = MOONLIGHT_IMAGE_DEFAULT_CONVERSION;
		}
		if(isset($this->callbackData['media'][$i]))
			$callback_string = $this->MediaAssistant->mediaLink($this->callbackData['media'][$i],
								array('class'=>$media_class,'alt'=>$matches[1]),
								$conversion_type,
								true,
								$this->callbackData['media_link_attributes'][$i],
								$this->callbackData['model']);
		else
			$callback_string = '<span class="media_error">Invalid media reference</span>';
		if($i>=($this->fragmentMatches-1)) $i=0;
		else $i++;
		return $callback_string;
	}


}
?>