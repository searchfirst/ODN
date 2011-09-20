<?php
App::import("Sanitize");
class SearchController extends AppController {
    var $uses = array("Searchable.SearchIndex");
    var $helpers = array("Paginator","Searchable.Searchable");
    var $paginate = array(
        'limit' => 50,
        'order' => array('Customer.company_name' => 'ASC'),
        'recursive' => 0
    );

    function index() {
        $conditions = array();
        $doPaginate = !(isset($this->params['url']['limit']) && $this->params['url']['limit'] == 'all');
        if ($this->RequestHandler->isAjax()) { $this->paginate['limit'] = 10; }
        if (!empty($this->params['url']['model'])) {
            $model = $this->params['url']['model'];
            $conditions['SearchIndex.model'] = $model;
        }
        if (!empty($this->params["url"]["q"])) {
            $this->data["q"] = $this->params["url"]["q"];
            $query = $this->SearchIndex->fuzzyize(Sanitize::escape($this->data["q"]));
            if (empty($model)) {
                $this->SearchIndex->searchModels(array("Customer","Service","Website","Note","Schedule"));
                $Model = ClassRegistry::init($model);
                $order = $Model->displayField. ' ASC';
            } else {
                $this->SearchIndex->searchModels(array($model));
                $order = "SearchIndex.data ASC";
            }
            $conditions[] = 'SearchIndex.data REGEXP "'. $query . '"';
            $this->paginate = array(
                "limit" => 10,
                "conditions" => $conditions,
                "order" => $order,
                "page"=>$page
            );
            if ($doPaginate) {
                $results = $this->paginate("SearchIndex");
            } else {
                $results = $this->SearchIndex->find('all', array('conditions' => $conditions));
            }
            $this->set("results", $results);
        }
        $this->set('doPaginate', $doPaginate);
    }
}
