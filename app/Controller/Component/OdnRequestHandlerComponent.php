<?php
App::uses('RequestHandlerComponent', 'Controller/Component');
class OdnRequestHandlerComponent extends RequestHandlerComponent {
    public function initialize($controller, $settings = array()) {
        parent::initialize($controller, $settings);
        $this->addInputType('json', array(array($this, 'moveRootToModelData'), $controller->modelClass));
        if (empty($this->ext) && $this->request->is('ajax')) {
            $accepts = $this->request->accepts();
            $extensions = Router::extensions();
            if ($this->prefers('json')) {
                $this->ext = 'json';
            } else if ($this->prefers('xml')) {
                $this->ext = 'xml';
            }
        }
    }

    public function moveRootToModelData($input, $modelClass) {
        $input = json_decode($input, true);
        if (!array_key_exists($modelClass, $input)) {
            $input[$modelClass] = array();
            foreach ($input as $key => $val) {
                if (!preg_match('/^[A-Z]{1}/', $key)) {
                    if (!($key == 'created' && $key == 'modified')) {
                        $input[$modelClass][$key] = $val;
                    }
                    unset($input[$key]);
                }
            }
        }
        return $input;
    }
}
