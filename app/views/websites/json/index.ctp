<?php
if ($doPaginate) {
	echo $this->Json->toJsonWithPagination($websites,'Website');
} else {
	echo $this->Json->toJson($websites,'Website');
}
