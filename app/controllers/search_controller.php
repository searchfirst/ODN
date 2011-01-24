<?php
App::import("Sanitize");
class SearchController extends AppController {
	var $uses = array("Searchable.SearchIndex");
	var $helpers = array("Paginator","Searchable.Searchable");

	function index($page=1) {
		if (!empty($this->params["url"]["q"])) $this->data["q"] = $this->params["url"]["q"];
		if (!empty($this->data["q"])) {
			$query = Sanitize::escape($this->data["q"]);
			$this->SearchIndex->searchModels(array("Customer","Service","Website","Note","Schedule"));
			$this->paginate = array(
				"limit" => 10,
				"conditions" => "MATCH(SearchIndex.data) AGAINST('$query' IN BOOLEAN MODE)",
				"page"=>$page
			);
			$this->set("results",$this->paginate("SearchIndex"));
		}

	}
}
