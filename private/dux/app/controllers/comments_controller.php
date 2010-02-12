<?php
class CommentsController extends AppController {

	var $name = 'Comments';
	var $helpers = array('Javascript','Html','Form','Time','TextAssistant','MediaAssistant');
	var $components = array('Rss','CommentAssistant');

	function index() {
		$this->Comment->recursive = 1;
		$this->set('approved_comments',$this->Comment->findCount(array('Comment.moderated'=>1,'Comment.spam'=>0)));
		$this->set('unmoderated_comments',$this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>0)));
		$this->set('spam_comments',$this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>1)));
		$this->set('recent_comments',$this->Comment->findAll(null,null,'Comment.created DESC',50));
	}

	function moderated() {
		$this->Comment->recursive = 1;
		$this->set('approved_comments',$this->Comment->findCount(array('Comment.moderated'=>1,'Comment.spam'=>0)));
		$this->set('unmoderated_comments',$this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>0)));
		$this->set('spam_comments',$this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>1)));
		$this->set('recent_comments',$this->Comment->findAll(array('Comment.moderated'=>0,'Comment.spam'=>0),null,'Comment.created DESC'));
	}

	function approved() {
		$this->Comment->recursive = 1;
		$this->set('approved_comments',$this->Comment->findCount(array('Comment.moderated'=>1,'Comment.spam'=>0)));
		$this->set('unmoderated_comments',$this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>0)));
		$this->set('spam_comments',$this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>1)));
		$this->set('recent_comments',$this->Comment->findAll(array('Comment.moderated'=>1,'Comment.spam'=>0),null,'Comment.created DESC'));
	}
	
	function spam() {
		$this->Comment->recursive = 1;
		$this->set('approved_comments',$this->Comment->findCount(array('Comment.moderated'=>1,'Comment.spam'=>0)));
		$this->set('unmoderated_comments',$this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>0)));
		$this->set('spam_comments',$this->Comment->findCount(array('Comment.moderated'=>0,'Comment.spam'=>1)));
		$this->set('recent_comments',$this->Comment->findAll(array('Comment.moderated'=>0,'Comment.spam'=>1),null,'Comment.created DESC'));
	}

	function view($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Article.');
			$this->redirect($this->referer('/comments/'));
		}
		$this->set('comment', $this->Comment->read(null, $id));
	}

	function delete($id = null) {
		if(!$id) {
			$this->Session->setFlash('Invalid id for Comment');
			$this->redirect($this->referer('/comments/'));
		}
		$this->data = $this->Comment->find(array('Comment.id'=>$id));
		if( ($this->data['Comment']['id']==$id) && ($this->Comment->del($id)) ) {
			$this->Session->setFlash('Comment successfully deleted');
			$this->redirect($this->referer('/comments/'));
		} else {
			$this->set('id',$id);
		}
	}
	
	function delete_all($type='default') {
		switch($type) {
			case 'spam':
				$this->Comment->deleteMany(array('Comment.moderated'=>0,'Comment.spam'=>1));
				$this->Session->setFlash('Spam comments deleted');
				break;
			case 'moderated':
				$this->Comment->deleteMany(array('Comment.moderated'=>0,'Comment.spam'=>0));
				$this->Session->setFlash('Moderated comments deleted');
				break;
			case 'approved':
				$this->Comment->deleteMany(array('Comment.moderated'=>1,'Comment.spam'=>0));
				$this->Session->setFlash('Approved comments deleted');
				break;
			default:
				$this->setFlash('You must select which type of comments to delete');
				break;
		}
		$this->redirect($this->referer('/comments'));
	}

	function is_approved() {
		if(isset($this->data['Comment']['id']) && ($comment = $this->Comment->findById($this->data['Comment']['id']))) {
			$this->CommentAssistant->flagAsHam($comment);
			$this->Comment->id = $this->data['Comment']['id'];
			$this->Comment->saveField('moderated',1);
			$this->Comment->saveField('spam',0);
			$this->Session->setFlash('Comment approved');
			$this->redirect($this->referer('/comments'));
		} else {
			$this->Session->setFlash('Invalid Comment');
			$this->redirect($this->referer('/comments'));
		}
	}

	function is_spam() {
		if(isset($this->data['Comment']['id']) && ($comment = $this->Comment->findById($this->data['Comment']['id']))) {
			$this->CommentAssistant->flagAsSpam($comment);
			$this->Comment->del($this->data['Comment']['id']);
			$this->Session->setFlash('Comment deleted');
			$this->redirect($this->referer('/comments'));
		} else {
			$this->Session->setFlash('Invalid Comment');
			$this->redirect($this->referer('/comments'));
		}
	}
}
?>