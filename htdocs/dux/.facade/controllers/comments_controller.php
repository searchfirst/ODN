<?php
class CommentsController extends AppController {

	var $name = 'Comments';
	var $uses = array('Comment','Article');
	var $components = array('CommentAssistant');
	
	function index() {
		if(!empty($this->data)) {
			$is_spam = $this->CommentAssistant->isSpam($this->data);
			$article_data = $this->Article->findById($this->data['Comment']['article_id']);
			if(!empty($article_data['Article']) && (preg_match("/{$article_data['Article']['slug']}/",$this->referer('')))	&& ($this->Comment->save($this->data))) {
				if($is_spam) {
					$this->Session->setFlash("Your comment has been marked for moderation. If your comment doesn't appear in due course, please contact the webmaster.");
					$this->redirect($this->referer('/'));
				} else {
					$this->Session->setFlash("Thank you for your feedback. Your comment has been accepted.");
					$this->redirect($this->referer('/'));
				}
			} else {
				$this->Session->setFlash("Sorry. There was an error with your feedback. Go back and check for errors. Make sure you provided a valid email address");
			}
		} else {		
			$this->viewPath = 'errors';
			$this->render('not_found');
		}
	}
}
?>