<?php
class User extends AppModel {
    public $actsAs = array(
        'Acl'=>array(
            'type' => 'requester'
        ),
        'IntCaster' => array(
            'cacheConfig' => 'lenore'
        )
    );
    public static $currentUser = array();
    public $displayField = 'username';
    public $order = 'User.name';
    public $recursive = 1;

    public $validate = array();

    public $hasMany = array(   
        "Customer" => array(
            'order' => 'Customer.modified DESC'
        ),
        'Service' => array(
            'order' => 'Service.modified DESC'
        )
    );
    public $belongsTo = array(
        'Group'
    );
    public $hasAndBelongsToMany = array(
        'Website'=>array(
            'with'=>'Service',
            'className'=>'Website'
        )
    );
        
    public static function getCurrent($key=null) {
        if(!empty(self::$currentUser) ){
            if(!$key) {
                return self::$currentUser;
            } elseif(!empty(self::$currentUser['User'][$key])) {
                return self::$currentUser['User'][$key];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function setCurrent($user) {
        self::$currentUser = $user;
        return true;
    }

    function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
            $groupId = $this->data['User']['group_id'];
        } else {
            $groupId = $this->field('group_id');
        }
        if (!$groupId) {
            return null;
        } else {
            return array('Group' => array('id' => $groupId));
        }
    }

    function bindNode($user) {
        return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    }

    function beforeSave($options = array()) {
        parent::beforeSave($options);
        if (!empty($this->data['User']) && array_key_exists('password', $this->data['User'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }
        return true;
    }
}
