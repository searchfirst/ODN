<?php
class SearchController extends AppController {
    public $components = array(
        'RequestHandler' => array(
            'className' => 'Rest.Rest',
            'catchredir' => true,
            'paginate' => true,
            'debug' => 1,
            'ratelimit' => array(
                'enable' => false
            ),
            'meta' => array(
                'enable' => false
            ),
            'actions' => array(
                'index' => array(
                    'extract' => array(
                        'search_indices.{n}.SearchIndex' => 'search_indices',
                        'search_indices.{n}.Customer' => 'search_indices.{n}.Customer'
                    ),
                    'embed' => false
                )
            )
        )
    );
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
        if ($isAjax) {
            $this->SearchIndex->isAjax = true;
        }
        $conditions = array();
        $doPaginate = true;

        if (array_key_exists('model', $this->request->query)) {
            $model = $this->request->query['model'];
            $conditions['SearchIndex.model'] = $model;
        }

        if (array_key_exists('q', $this->request->query)) {
            App::uses('Sanitize', 'Utility');
            $this->request->data['q'] = Sanitize::escape(urldecode($this->request->query['q']));
            $query = $this->SearchIndex->fuzzyize($this->request->data["q"]);
            $conditions[] = "SearchIndex.data ~* '$query'";

            if (empty($model)) {
                $this->SearchIndex->searchModels(array('Customer','Service','Website','Note'));
            } else {
                $this->SearchIndex->searchModels(array($model));
                $this->paginate['order'] = $this->{$model}->displayField. ' ASC';
            }

            $this->paginate['conditions'] += $conditions;
            $search_indices = $this->paginate('SearchIndex');
            $this->set(compact('search_indices'));
        }
    }
}
