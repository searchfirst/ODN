<?php
class JoinedBehavior extends ModelBehavior {
	function beforeSave(&$model) {
		$this->appendJoinedDate($model);
	}
	private function appendJoinedDate(&$model) {
		if (empty($this->data[$model->alias]['id'])) {
			$this->data[$model->alias]['joined'] = DboSource::expression('NOW()');
		}
	}
}
