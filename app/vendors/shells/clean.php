<?php
class CleanShell extends Shell {   
	function main() { $this->help(); }

	function help() {
		$this->out('CakePHP Clean:');
		$this->out("cake clean logs - Clean log files");
		$this->out("cake clean cache - Clean models and persistent files");
		$this->hr();
	}

	function initialize() {
		return true;
	}

	function __clean($path) {
		$folder = new Folder($path);
		$tree = $folder->tree($path, false);
		foreach ($tree as $files) {
			foreach ($files as $file) {
				if (!is_dir($file)) {
					$file= new File($file);
					$file->delete();
				}
			}
		}
		return;
	}

	function logs() {
		$this->__clean(TMP . 'logs');    
		$this->out('Logs cleaned.');
	}    

	function cache() {
		$this->__clean(TMP . 'cache' . DS . 'models');
		$this->__clean(TMP . 'cache' . DS . 'persistent');
		$this->__clean(TMP . 'cache' . DS . 'views');
		$this->out('Cache cleaned.');
	}    

	function views() {
		$this->__clean(TMP.'cache'.DS.'views');
		$this->out('Views cleaned.');
	}
}
