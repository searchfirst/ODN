<?php
class RemoveEmptyRelationshipsBehavior extends ModelBehavior {
    function afterFind($model, $results, $primary) {
        if ($primary && is_array($results)) {
            $assocKeys = array_merge(array_keys($model->belongsTo),array_keys($model->hasOne));
            foreach ($results as $r => $result) {
                foreach ($assocKeys as $assoc) {
                    if (array_key_exists($assoc, $result) && empty($result[$assoc]['id'])) {
                        //$results[$r][$assoc] = null;
                        unset($results[$r][$assoc]);
                    }
                }
            }
        }
        return $results;
    }
}
