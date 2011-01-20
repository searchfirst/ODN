<?php
$config = array(
	'Invoice' => array(
		'vat_calculation' => 'round' //Rounding function for VAT calcs round ceil floor supported so far
	),
	'Dux' => array(
		'external_links' => array(
			'Reports'=>'http://office.searchfirst.co.uk/reports/',
			'Webmail'=>'http://mail.searchfirst.co.uk/',
			'Fasthosts'=>'https://login.fasthosts.co.uk/',
			'Whiteboard'=>'http://whiteboard.searchfirst.co.uk/',
			'HitMe [New Tracker]'=>'http://hit-me.co.uk/cgi-bin/x-t.cgi?NAVG=SignUp',
			'HitMe [Admin]'=>'http://hit-me.co.uk/cgi-bin/admin/admin.cgi',
			'HitMe [Accounts Manager]'=>'http://hit-me.co.uk/cgi-bin/admin/admin.cgi?NAVG=AM'
		),
		'theme' => 'sf'
	)
);
?>
