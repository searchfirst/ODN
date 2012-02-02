<?php
class Website extends AppModel {
    public $actsAs = array(
        'IntCaster'=>array(
            'cacheConfig'=>'lenore'
        ),
        //'Alkemann.Revision'
    );
    public $belongsTo = array(
        'Customer'
    );
    public $hasMany = array(
        'Service'
    );
    public $recursive = 0;
    public $validate = array();
}
