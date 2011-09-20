<?php
if ($doPaginate) {
	echo $this->Json->toJsonWithPagination($results);
} else {
	echo $this->Json->toJson($results);
}
