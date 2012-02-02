<?php
App::uses('Model', 'Model');
class AppModel extends Model {
    public function afterFind($results, $primary) {
        if (!empty($this->isAjax)) {
            if ($primary) {
                $alias = $this->alias;
                foreach($results as $key => $result) {
                    if (array_key_exists($alias, $result)) {
                        $r = $result[$alias];
                        unset($results[$key][$alias]);
                        $results[$key] += $r;
                    }
                }
            }
        }
        return $results;
    }
}
