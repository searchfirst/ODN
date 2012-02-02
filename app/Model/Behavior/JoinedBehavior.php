<?php
class JoinedBehavior extends ModelBehavior {
    function beforeSave(&$Model) {
        $this->appendJoinedDate($Model);
        return true;
    }
    private function appendJoinedDate(&$Model) {
        if (empty($Model->data[$Model->alias]['id'])) {
            $Model->data[$Model->alias]['joined'] = DboSource::expression('NOW()');
        }
    }
}
