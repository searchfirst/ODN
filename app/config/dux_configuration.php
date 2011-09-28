<?php
$config = array(
    'Invoice' => array(
        'vat_calculation' => 'round' //Rounding function for VAT calcs round ceil floor supported so far
    ),
    'T' => array(
        'allow_html_in_descriptions' => true,
        'permitted_html_elements' => '<p><span><b><i><em><strong><div><article><section><ul><ol><li><h1><h2><h3><h4><h5><h6>'
    ),
    'Dux' => array(
        'external_links' => array(
            array(
                'title' => 'Reports',
                'href' => 'http://office.searchfirst.co.uk/reports/'
            ),
            array(
                'title' => 'Webmail',
                'href' => 'http://mail.searchfirst.co.uk/'
            ),
            array(
                'title' => 'Fasthosts',
                'href' => 'https://login.fasthosts.co.uk/'
            ),
            array(
                'title' => 'Whiteboard',
                'href' => 'http://whiteboard.searchfirst.co.uk/'
            ),
            array(
                'title' => 'HitMe [New Tracker]',
                'href' => 'http://hit-me.co.uk/cgi-bin/x-t.cgi?NAVG=SignUp'
            ),
            array(
                'title' => 'HitMe [Admin]',
                'href' => 'http://hit-me.co.uk/cgi-bin/admin/admin.cgi'
            ),
            array(
                'title' => 'HitMe [Accounts Manager]',
                'href' => 'http://hit-me.co.uk/cgi-bin/admin/admin.cgi?NAVG=AM'
            )
        ),
        'theme' => 'sf',
        'additional_css' => array(
            'css/themed/sf/print.css'
        )
    )
);
?>
