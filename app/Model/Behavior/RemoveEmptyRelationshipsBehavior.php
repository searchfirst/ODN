<?php
class RemoveEmptyRelationshipsBehavior extends ModelBehavior {
    function afterFind(Model $Model, $results, $primary) {
        if ($primary && is_array($results)) {
            $assocKeys = array_merge(array_keys($Model->belongsTo),array_keys($Model->hasOne));
            foreach ($results as $r => $result) {
                foreach ($assocKeys as $assoc) {
                    if (array_key_exists($assoc, $result) && empty($result[$assoc]['id'])) {
                        unset($results[$r][$assoc]);
                    }
                }
            }
        }
        return $results;
    }
}
