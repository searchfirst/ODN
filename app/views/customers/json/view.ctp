<?php
if (isset($customer['User'])) { unset($customer['User']['password']); }
echo $this->Js->object($customer);
