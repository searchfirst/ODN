<?php
class WebsitesController extends AppController {
    public $primaryModel = 'Website';
    public $paginate = array(
        'conditions' => array(),
        'paramType' => 'querystring',
        'limit' => 10,
        'order' => array('Website.uri' => 'ASC'),
        'recursive' => -1
    );

    public function index() {
        extract($this->Odn->requestInfo);

        $conditions = array();
        $doPaginate = !(isset($this->request->query['limit']) && $this->request->query['limit'] == 'all');
        $this->Website->recursive = -1;

        if (array_key_exists('customer_id', $this->request->query)) {
            $conditions['Website.customer_id'] = $this->request->query['customer_id'];
        }

        if ($doPaginate) {
            $this->paginate['conditions'] += $conditions;
            $websites = $this->paginate('Website');
        } else {
            $websites = $this->Website->find('all', compact('conditions', 'isAjax'));
        }

        $this->set(compact('doPaginate', 'websites'));
    }

    public function add() {
        extract($this->Odn->requestInfo);

        if ($isPost || $isPut) {
            if ($this->Website->save($this->request->data)) {
                $message = __('Website created successfully.');
                if ($isAjax) {
                    $website = $this->Website->read(null, null, $isAjax);
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect(array('controller' => 'customers', 'action' => 'view', $this->Website->field('customer_id')));
                }
                $this->set(compact('website'));
            } else {
                $message = __('There was an error saving this website. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $this->Session->setFlash($message);
                }
            }
        } else {
            if (!array_key_exists('customer_id', $this->request->query)) {
                $message = __('No customer id was provided to add a website.');
                throw new BadRequestException($message);
            }

            $this->request->data += array(
                'Website' => array(
                    'customer_id' => $this->request->query['customer_id']
                )
            );
        }
    }

    public function edit($id = null) {
        if(!$id) {
            $message = __('No id was provided to edit a website');
            throw new BadRequestException($message);
        }

        extract($this->Odn->requestInfo);
        $this->Website->id = $id;
        $this->Website->recursive = -1;

        if ($isPost || $isPut) {
            if ($this->Website->save($this->request->data)) {
                $message = __('Website saved successfully.');
                if ($isAjax) {
                    $website = $this->Website->read(null, null, $isAjax);
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect(array('controller' => 'customers', 'action' => 'view', $this->Website->field('customer_id')));
                }
            } else {
                $message = __('There was an error saving this website. Please correct any highlighted errors.');
                if ($isAjax) {
                    throw new InternalErrorException($message);
                } else {
                    $this->Session->setFlash($message);
                }
            }
        } else {
            $this->request->data = $this->Website->read();
        }

        $this->set(compact('website'));
    }

    public function view($id) {
        if (!$id) {
            $message = __('No id was provided to view a website');
            throw new BadRequestException($message);
        }

        extract($this->Odn->requestInfo);
        $this->Website->id = $id;

        if ($website = $this->Website->read(null, null, $isAjax)) {
            if (!$isAjax) {
                $title_for_layout = __('%s | Website', $website['Website']['uri']);
            }
        } else {
            $message = __('A website could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        $this->set(compact('title_for_layout', 'website'));
    }

    public function delete($id = null) {
        if (!$id) {
            $message = __('No id was provided to delete a contact.');
            throw new BadRequestException($message);
        }

        $this->Website->id = $id;
        if (!$this->Website->exists()) {
            $message = __('A website could not be found with id: %s', $id);
            throw new NotFoundException($message);
        }

        extract($this->Odn->requestInfo);
        if ($isDelete) {
            if ($this->Website->delete()) {
                $message = __('Website deleted successfully.');
                if ($isAjax) {
                    $this->set(compact('message'));
                } else {
                    $this->Session->setFlash($message);
                    $this->redirect('/');
                }
            } else {
                $message = __('There was an error deleting this website.');
                throw new InternalErrorException($message);
            }
        } else {
            $website = $this->Website->read();
            $title_for_layout = __('Delete %s | Website', $id);
        }

        $this->set(compact('title_for_layout', 'website'));
    }
}
