<?php
class OwnedBehavior extends ModelBehavior {
	function beforeSave(&$model) {
		$this->setOwner($model);
	}
	private function setOwner(&$model) {
		if (empty($this->data[$model->alias]['id']) && empty($this->data[$model->alias]['user_id'])) {
			$this->data[$model->alias]['user_id'] = User::getCurrent();
		}
	}
}
