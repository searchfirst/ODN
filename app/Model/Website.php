<?php
class Website extends AppModel {
    public $actsAs = array(
        'IntCaster'=>array(
            'cacheConfig'=>'lenore'
        ),
        'Searchable.Searchable',
        'Alkemann.Revision'
    );
    public $belongsTo = array(
        'Customer'
    );
    public $hasMany = array(
        'Service'
    );
    public $recursive = 0;
    public $validate = array();

    public function indexData() {
        return $this->data[$this->alias]['uri'];
    }
}
