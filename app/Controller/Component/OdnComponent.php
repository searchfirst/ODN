<?php
class OdnComponent extends Component {
    public $requestInfo;

    public function initialize(&$Controller, $settings = array()) {
        $this->settings = $settings;
        $this->setCurrentUser($Controller);
        $this->requestInfo = array(
            'isPost' => $Controller->request->is('ajax'),
            'isPut' => $Controller->request->is('put'),
            'isDelete' => $Controller->request->is('delete'),
            'isAjax' => $Controller->request->is('ajax')
        );
    }

    public function startup(&$Controller) {
        if (!$Controller->request->is('ajax')) {
            $this->setTheme($Controller);
            $this->setPrimaryModel($Controller);
        }
    }

    public function beforeRender(&$Controller) {
        $this->buildAssets($Controller);
    }

    protected function setCurrentUser(&$Controller) {
        $currentUser = array('User' => $Controller->Auth->user());
        User::setCurrent($currentUser);
        $Controller->set('currentUser', $currentUser);
    }

    private function setTheme(&$Controller) {
        if ($theme = Configure::read('Odn.theme')) {
            $Controller->theme = $theme;
        }
    }

    private function setPrimaryModel(&$Controller) {
        if(!empty($Controller->primaryModel)) {
            $Controller->set('primaryModel', $Controller->primaryModel);
        }
    }

    private function buildAssets(&$Controller) {
        $js = $Controller->Sma->build(Configure::read('Sma.js'), 'js');
        $css = $Controller->Sma->build(Configure::read('Sma.css'), 'css');
        $Controller->set('assets', compact('css', 'js'));
    }
}
