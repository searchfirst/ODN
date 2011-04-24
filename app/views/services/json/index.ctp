<?php
if ($doPaginate) {
	echo $this->Json->toJsonWithPagination($services,'Service');
} else {
	echo $this->Json->toJson($services,'Service');
}
