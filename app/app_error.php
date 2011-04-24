<?php
class AppError extends ErrorHandler {
	function missingId($params) {
		$this->controller->set('model', $params['model']);
		$this->controller->header("HTTP/1.0 404 Not Found");
		$this->_outputMessage('missing_id');
	}
	function recordNotFound($params) {
		if (!empty($params['model'])) {
			$model = $params['model'];
		} elseif (!empty($this->controller->primaryModel)) {
			$model = $this->controller->primaryModel;
		} else {
			$model = 'Unknown Model';
		}
		$this->controller->set('model', $model);
		$this->controller->set('title_for_layout',__("$model Not Found",true));
		$this->controller->header('HTTP/1.0 404 Not Found');
		$this->_outputMessage('record_not_found');
	}
	function ajaxError($params) {
		$this->controller->header('HTTP/1.0 400 Bad Request');
		$this->controller->set('message',!empty($params['message'])?$params['message']:'Error');
		$this->_outputMessage('ajax_error');
	}
}
