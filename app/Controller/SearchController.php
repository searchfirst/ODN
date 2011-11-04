<?php
class SearchController extends AppController {
    public $helpers = array(
        'Paginator',
        'Searchable.Searchable'
    );
    public $paginate = array(
        'conditions' => array(),
        'limit' => 50,
        'order' => 'SearchIndex.data ASC',
        'paramType' => 'querystring',
        'recursive' => 0
    );
    public $uses = array(
        'Searchable.SearchIndex',
        'Customer',
        'Service',
        'Website',
        'Note'
    );

    public function index() {
        extract($this->Odn->requestInfo);
        $conditions = array();
        $doPaginate = true;

        if (array_key_exists('model', $this->request->query['model'])) {
            $model = $this->request->query['model'];
            $conditions['SearchIndex.model'] = $model;
        }

        if (array_key_exists('q', $this->request->query)) {
            App::uses('Sanitize', 'Utility');
            $this->request->data['q'] = $this->request->query['q'];
            $query = $this->SearchIndex->fuzzyize(Sanitize::escape($this->data["q"]));
            $conditions[] = "SearchIndex.data ~* '$query'";

            if (empty($model)) {
                $this->SearchIndex->searchModels(array('Customer','Service','Website','Note'));
            } else {
                $this->SearchIndex->searchModels(array($model));
                $this->paginate['order'] = $this->{$model}->displayField. ' ASC';
            }

            $this->paginate['conditions'] += $conditions;
            $results = $this->paginate('SearchIndex');
            $this->set(compact('doPaginate', 'results'));
        }
    }
}
