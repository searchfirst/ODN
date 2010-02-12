<html>
<head>
<link rel="stylesheet" href="/css/default.css">
</head>
<body>
<div style="height:500px;width:350px;overflow:auto;">
<h2>Notes</h2>
<ul class="item_list">
<?php foreach($notes as $note):?>
<li class="item"><?php echo $html->link($note['Customer']['company_name'],"/customers/view/{$note['Customer']['id']}")?> <strong><?php echo $note['User']['name'] ?> (<?php echo $time->niceShort($note['Note']['created']);?>)</strong>: <?php echo htmlentities($note['Note']['description']) ?></li>
<?php endforeach;?>
</ul>
</div>
</body>
</html>