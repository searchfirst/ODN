<?php
if ($doPaginate) {
	echo $this->Json->toJsonWithPagination($results, 'SearchIndex');
} else {
	echo $this->Json->toJson($results);
}
