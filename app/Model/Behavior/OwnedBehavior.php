<?php
class OwnedBehavior extends ModelBehavior {
	function beforeSave(&$Model) {
		$this->setOwner($Model);
		return true;
	}
	private function setOwner(&$Model) {
		if (empty($Model->data[$Model->alias]['id']) && empty($Model->data[$Model->alias]['user_id'])) {
			$Model->data[$Model->alias]['user_id'] = User::getCurrent('id');
		}
	}
}
