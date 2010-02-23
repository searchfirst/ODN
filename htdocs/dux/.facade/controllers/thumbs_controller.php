<?php
vendor("phpthumb"); 
require(CAKE_CORE_INCLUDE_PATH.DS.'shared'.DS.'vendors'.DS.'phpthumb'.DS.'phpthumb_config.php');

class ThumbsController extends AppController {
	var $name = 'Thumbs';
	var $uses = null;
	var $layout = null;

	function beforeRender() {}
	
	function index() {
		if(count($this->params['pass'])) {
			$src = implode('/',array_slice($this->params['pass'],1));
			$action = $this->params['pass'][0];
			$phpThumb = new phpThumb();
			$sourceFilename = MOONLIGHT_MEDIA_PATH.$src;
			$cacheFilename = md5("$action$src");
			$phpThumb->zc = 1;
			$phpThumb->src = $sourceFilename;
			$phpThumb->config_cache_directory = MOONLIGHT_IMAGE_THUMBNAIL_CACHE.DS;
			$phpThumb->config_imagemagick_path = MOONLIGHT_IMAGE_IMAGEMAGICK_PATH;
			$phpThumb->config_prefer_imagemagick = true;
			$phpThumb->config_output_format = 'jpeg';
			$phpThumb->config_error_die_on_error = false;
			$phpThumb->config_document_root = '';
			$phpThumb->config_cache_prefix = '';
			$phpThumb->config_cache_source_enabled = 1;
			$phpThumb->cache_filename = $phpThumb->config_cache_directory.$cacheFilename;
			$phpThumb->config_max_source_pixels = 19200000;
			$phpThumb->sia = implode('_',array_slice($this->params['pass'],1));
			$moonlight_phpthumb_config_params = $GLOBALS['moonlight_phpthumb_config_params'];
			
			if(in_array($action,array_keys($moonlight_phpthumb_config_params))) {
				$thumb_vars = $moonlight_phpthumb_config_params[$action];
				$phpThumb->cache_filename = $phpThumb->config_cache_directory.$cacheFilename;
				
				$phpThumb->setParameter('w',$thumb_vars['w']);
				$phpThumb->setParameter('h',$thumb_vars['h']);
				if(isset($thumb_vars['fltr'])) $phpThumb->setParameter('fltr',$thumb_vars['fltr']);
				
				if(	file_exists($phpThumb->cache_filename) && (filemtime($phpThumb->src) < filemtime($phpThumb->cache_filename)) ) {
					$phpThumb->useRawIMoutput = true;
					$phpThumb->IMresizedData = file_get_contents($phpThumb->cache_filename);
					$this->set('phpthumb',$phpThumb);
				}
				elseif($phpThumb->GenerateThumbnail()) {
					$phpThumb->RenderToFile($phpThumb->cache_filename);
					$this->set('phpthumb',$phpThumb);
				} else {
					die('Error caching thumbnail <pre>'.print_r($phpThumb,true).'</pre>');
				}
			} else {
				$this->redirect(MOONLIGHT_MEDIA_WEB_ROOT.$src);
			}
		} else {
			$this->redirect('/');
		}
	}
}
?>