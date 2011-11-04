<?php
class Payment extends AppModel {
    var $actsAs = array(
        "IntCaster" => array("cacheConfig" => "lenore")
    );
    var $belongsTo = array(
        "Customer"
    );
    var $hasMany = array(
        "InvoiceContribution"
    );
    var $order = "Invoice.created";
    var $recursive = 1;
}
