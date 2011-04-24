<?php
class RemoveEmptyRelationshipsBehavior extends ModelBehavior {
	function afterFind($model, $results, $primary) {
		if ($primary && !empty($results[0])) {
			$assocKeys = array_merge(array_keys($model->belongsTo),array_keys($model->hasOne));
			foreach ($assocKeys as $assoc) {
				if (!empty($results[0][$assoc]) && empty($results[0][$assoc]['id'])) {
					$results[0][$assoc] = null;
				}
			}
		}
		return $results;
	}
}
