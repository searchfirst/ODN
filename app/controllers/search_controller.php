<?php
App::import("Sanitize");
class SearchController extends AppController {
    var $uses = array("Searchable.SearchIndex");
    var $helpers = array("Paginator","Searchable.Searchable");
    var $paginate = array(
        'limit' => 50,
        'recursive' => 0
    );

    function index() {
        $conditions = array();
        if (!empty($this->params['url']['limit'])) {
            $this->paginate['limit'] = 10;
        }
        if (!empty($this->params['url']['model'])) {
            $model = $this->params['url']['model'];
            $conditions['SearchIndex.model'] = $model;
        }
        if (!empty($this->params['url']['page'])) {
            $this->paginate['page'] = $this->params['url']['page'];
        } else {
            $this->paginate['page'] = 1;
        }
        if (!empty($this->params["url"]["q"])) {
            $this->data["q"] = $this->params["url"]["q"];
            $query = $this->SearchIndex->fuzzyize(Sanitize::escape($this->data["q"]));
            if (empty($model)) {
                $this->SearchIndex->searchModels(array("Customer","Service","Website","Note","Schedule"));
                $this->paginate['order'] = "SearchIndex.data ASC";
            } else {
                $this->SearchIndex->searchModels(array($model));
                $Model = ClassRegistry::init($model);
                $this->paginate['order'] = $Model->displayField. ' ASC';
            }
            $conditions[] = "SearchIndex.data ~* '$query'";
            $this->paginate['conditions'] = $conditions;
            $results = $this->paginate("SearchIndex");
            $this->set("results", $results);
        }
        $this->set('doPaginate', true);
    }
}
