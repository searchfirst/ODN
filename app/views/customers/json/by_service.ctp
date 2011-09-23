<?php
if ($doPaginate) {
	echo $this->Json->toJsonWithPagination($customers,'Customer');
} else {
	echo $this->Json->toJson($customers,'Customer');
}
