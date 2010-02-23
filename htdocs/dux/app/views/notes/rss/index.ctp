<?php foreach($notes as $note):?>
<item>
<guid><?php echo $note['Note']['id'] ?></guid>
<link>http://dux.searchfirst.co.uk/notes</link>
<title><?php echo "{$note['Customer']['company_name']} [{$note['User']['name']}]" ?></title>
<description><![CDATA[<?php echo $html->link($note['Customer']['company_name'],"/customers/view/{$note['Customer']['id']}")?> <strong><?php echo $note['User']['name'] ?> (<?php echo $time->niceShort($note['Note']['created']);?>)</strong>: <?php echo $note['Note']['description'] ?>]]></description>
<pubDate><?php echo $time->toRSS($note['Note']['created']) ?></pubDate>
</item>
<?php endforeach;?>