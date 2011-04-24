<?php
if ($doPaginate) {
	echo $this->Json->toJsonWithPagination($users,'User');
} else {
	echo $this->Json->toJson($users,'User');
}
