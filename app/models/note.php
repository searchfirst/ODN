<?php
class Note extends AppModel {
    var $validate = array();
    var $order = 'Note.modified DESC';
    var $recursive = 1;
    var $actsAs = array(
        'Owned','Searchable.Searchable',
        'IntCaster'=>array(
            'cacheConfig'=>'lenore'
        ),
        'Detextiliser'=>array(
            'fields'=>array('description')
        )
    );
    var $_findMethods = array('owned'=>true,'countOwned'=>true);

    var $hasMany = array();
    var $belongsTo = array(
        'Website',
        'Customer',
        'User' => array(
            'fields' => array('name','id')
        ),
        'Service'
    );

    function beforeSave() {
        if(empty($this->data['Note']['id']))
            $this->data['Note']['user_id'] = User::getCurrent('id');
        if(!empty($this->data['Note']['service_id']))
            $this->data['Note']['model'] = 'Service';
        return true;
    }
    
    function _findCountOwned($state, $query, $results = array()) {
        if ($state == "before") {
            $query['recursive'] = 1;
            $query['fields'] = 'count(Note.id) Count';
            $this->unbindModel(array(
                'belongsTo' => array('Website','Customer','User','Service')
            ));
            $joins = array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('User.id = Note.user_id')
                ),
                array(
                    'table' => 'customers',
                    'alias' => 'Customer',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('true')
                ),
                array(
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Service.id = Note.service_id','Customer.id = Service.customer_id')
                )
            );
            $query['joins'] = $joins;
            if (!empty($query['user'])) {
                $user = $query['user'];
                unset($query['user']);
            } else {
                $user = User::getCurrent('id');
            }
            if (!isset($query['conditions'])) {
                $query['conditions'] = array();
            }
            $query['conditions']['OR'] = array('Note.user_id'=>$user,'Service.user_id'=>$user);
            return $query;
        } elseif ($state == "after") {
            if (!empty($results[0][0]['Count'])) {
                $results = $results[0][0]['Count'];
            }
            return $results;
        }
    }
    function _findOwned($state, $query, $results = array()) {
        if ($state == "before") {
            $query['recursive'] = 1;
            $query['fields'] = '*';
            $this->unbindModel(array(
                'belongsTo' => array('Website','Customer','User','Service')
            ));
            $joins = array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('User.id = Note.user_id')
                ),
                array(
                    'table' => 'customers',
                    'alias' => 'Customer',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('true')
                ),
                array(
                    'table' => 'services',
                    'alias' => 'Service',
                    'type' => 'inner',
                    'foreignKey' => false,
                    'conditions' => array('Service.id = Note.service_id','Customer.id = Service.customer_id')
                )
            );
            $query['joins'] = $joins;
            if (!empty($query['user'])) {
                $user = $query['user'];
                unset($query['user']);
            } else {
                $user = User::getCurrent('id');
            }
            if (!isset($query['conditions'])) {
                $query['conditions'] = array();
            }
            $query['conditions']['OR'] = array('Note.user_id'=>$user,'Service.user_id'=>$user);
            return $query;
        } elseif ($state == "after") {
            return $results;
        }
    }
    function findForUser($cuid,$options=array()) {

        if(!empty($options['conditions']))
            $conditions = " AND ".$options['conditions'];
        else
            $conditions = "";
        if(!empty($options['page']))
            $page = $options['page'];
        else
            $page = 1;
        if(!empty($options['limit'])) {
            $t_limit = " LIMIT ".(($page-1)*$options['limit']).", {$options['limit']}";
            $limit = $options['limit'];
        } else {
            $t_limit = '';
            $limit = 0;
        }
        if(!empty($options['order']))
            $order = $options['order'];
        else
            $order = 'Note.created DESC';

        $query = sprintf("SELECT * FROM users User JOIN customers Customer JOIN services Service JOIN notes Note ON Service.customer_id=Customer.id AND Note.service_id=Service.id AND User.id=Note.user_id WHERE (Note.user_id=%s OR Service.user_id=%s)%s ORDER BY %s%s",$cuid,$cuid,$conditions,$order,$t_limit);

        $count_query = sprintf("SELECT count(Note.id) Count FROM customers Customer JOIN services Service JOIN notes Note ON Service.customer_id=Customer.id AND Note.service_id=Service.id WHERE (Note.user_id=%s OR Service.user_id=%s)%s",$cuid,$cuid,$conditions);

        $q_count = $this->query($count_query);
        $cu_notes['items'] = $this->query($query);
        $cu_notes['count'] = $q_count[0][0]['Count'];
        $cu_notes['pages'] = ($limit)?ceil($cu_notes['count']/$limit):1;
        $cu_notes['curr_page'] = $page;
        return $cu_notes;
    }
}
