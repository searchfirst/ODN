<?php
require(CAKE_CORE_INCLUDE_PATH.'/shared/vendors/akismet/Akismet.class.php');

class CommentAssistantComponent extends Object {

	function startup(&$controller) {
		if(defined('MOONLIGHT_AKISMET_API_KEY'))
			$this->akismet = & new Akismet("http://{$_SERVER['HTTP_HOST']}",MOONLIGHT_AKISMET_API_KEY);
	}
	function isSpam(&$comment_data) {
		if(defined('MOONLIGHT_AKISMET_API_KEY')) {
			$this->akismet->setCommentAuthor($comment_data['Comment']['author']);
			$this->akismet->setCommentAuthorEmail($comment_data['Comment']['email']);
			$this->akismet->setCommentAuthorURL($comment_data['Comment']['uri']);
			$this->akismet->setCommentContent($comment_data['Comment']['description']);
			if($this->akismet->isCommentSpam()) {
				$comment_data['Comment']['spam'] = 1;
				return true;
			} else {
				$comment_data['Comment']['spam'] = 0;
				$comment_data['Comment']['moderated'] = 1;
				return false;
			}
		} else {
			$comment_data['Comment']['moderated'] = 0;
			return true;
		}
	}
	
	function flagAsSpam($comment_data) {
		if(defined('MOONLIGHT_AKISMET_API_KEY')) {
			$this->akismet->setCommentAuthor($comment_data['Comment']['author']);
			$this->akismet->setCommentAuthorEmail($comment_data['Comment']['email']);
			$this->akismet->setCommentAuthorURL($comment_data['Comment']['uri']);
			$this->akismet->setCommentContent($comment_data['Comment']['description']);
			$this->akismet->submitSpam();
			return true;
		} else return false;
	}
	
	function flagAsHam($comment_data) {
		if(defined('MOONLIGHT_AKISMET_API_KEY')) {
			$this->akismet->setCommentAuthor($comment_data['Comment']['author']);
			$this->akismet->setCommentAuthorEmail($comment_data['Comment']['email']);
			$this->akismet->setCommentAuthorURL($comment_data['Comment']['uri']);
			$this->akismet->setCommentContent($comment_data['Comment']['description']);
			$this->akismet->submitHam();
			return true;
		} else return false;
	}
}
?>