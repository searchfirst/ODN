<?php
class JsonHelper extends AppHelper {
	var $helpers = array('Paginator');

	function __construct($options = null) {
		parent::__construct($options);
	}

	function toJsonWithPagination($data,$model) {
		$page = $this->params['paging'][$model]['page'];
		$total = $this->params['paging'][$model]['count'];
		$per_page = $this->params['paging'][$model]['options']['limit'];
		if (is_array($data)) {
			foreach ($data as $z=>$d) {
				$data[$z] = $this->modelDataToRoot($d,$model);
			}
		}
		$models = $data;
		$paginatedData = array(
			'page' => $page,
			'total' => $total,
			'per_page' => $per_page,
			'models' => $data
		);
		return json_encode($paginatedData);
	}

	function toJson($data,$model=false) {
		//if (($model !== false) && !empty($data[$model])) {
		//	$data = $this->modelDataToRoot($data,$model);
		//}
		if ($model !== false && is_array($data)) {
			foreach ($data as $z=>$d) {
				$data[$z] = $this->modelDataToRoot($d,$model);
			}
		}
		return json_encode($data);
	}

	private function modelDataToRoot($data,$model) {
		foreach ($data[$model] as $k=>$d) {
			$data[$k] = $d;
			unset($data[$model][$k]);
		}
		unset($data[$model]);
		return $data;
	}
}
