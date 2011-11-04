<?php
App::uses('AppHelper', 'View/Helper');
class JsonHelper extends AppHelper {
    public $helpers = array('Paginator');
    public $controller;

    public function toJson($data, $settings = array()) {
        $_settings = array(
            'doPaginate' => false
        );
        extract(array_merge($_settings, $settings));
        
        if ($doPaginate) {
            $paging = $this->params->paging;
            $modelClass = key($paging);
            $page = $paging[$modelClass]['page'];
            $total = $paging[$modelClass]['count'];
            $per_page = $paging[$modelClass]['limit'];
            $models = $data;
            return json_encode(compact('page', 'total', 'per_page', 'models'));
        } else {
            return json_encode($data);
        }
    }
}
