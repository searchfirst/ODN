<?php
if ($doPaginate) {
	echo $this->Json->toJsonWithPagination($contacts,'Contact');
} else {
	echo $this->Json->toJson($contacts,'Contact');
}
