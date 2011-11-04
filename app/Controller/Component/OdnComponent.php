<?php
class OdnComponent extends Component {
    public $controller;
    public $requestInfo;

    public function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
        $this->settings = $settings;
        $this->setCurrentUser();
        $this->requestInfo = array(
            'isPost' => $this->controller->request->is('ajax'),
            'isPut' => $this->controller->request->is('put'),
            'isDelete' => $this->controller->request->is('delete'),
            'isAjax' => $this->controller->request->is('ajax')
        );
    }

    public function startup(&$controller) {
        if (!$this->controller->request->is('ajax')) {
            $this->setTheme();
            $this->setPrimaryModel();
        }
    }

    public function beforeRender(&$controller) {
        $this->buildAssets();
        //$this->mergeGetData();
    }

    public function renderAjax($json_object=array(),$controllerViewPath=false) {
        Configure::write('debug', 0);
        $this->controller->request->renderAs($this->controller,'json');
        $this->controller->request->respondAs('json');
        $this->controller->set('json_object',$json_object);
        if (!$controllerViewPath) {
            $this->controller->viewPath = 'json';
            $this->controller->render('index');
        } else {
            $this->controller->render($this->controller->action);
        }
    }

    protected function setCurrentUser() {
        $currentUser = array('User' => $this->controller->Auth->user());
        User::setCurrent($currentUser);
        $this->controller->set('currentUser', $currentUser);
    }

    private function setTheme() {
        if($theme = Configure::read('Odn.theme')) {
            $this->controller->theme = $theme;
        }
    }

    private function setPrimaryModel() {
        if(!empty($this->controller->primaryModel)) {
            $this->controller->set('primaryModel',$this->controller->primaryModel);
        }
    }

    private function buildAssets() {
        $js = $this->controller->Sma->build(Configure::read('Sma.js'), 'js');
        $css = $this->controller->Sma->build(Configure::read('Sma.css'), 'css');
        $this->controller->set('assets', compact('css', 'js'));
    }

    private function mergeGetData() {
        if(!empty($this->controller->params['url']['data'])) {
            if(empty($this->controller->data)) {
                $this->controller->data = $this->controller->params['url']['data'];
            } else {
                $this->controller->data = array_merge($this->controller->params['url']['data'],$this->controller->data);
            }
            unset($this->controller->params['url']['data']);
        }
    }
}
