<?php if(!empty($mod_date_for_layout)) header('Last-Modified: '.$time->toRSS($mod_date_for_layout)); ?>
<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="charset=utf-8" />
<title><?php echo $textAssistant->sanitiseText($title_for_layout);?> | Dux</title>
<?php echo $html->css('default','stylesheet',array('media'=>'screen'));?> 
<?php echo $html->css('print','stylesheet',array('media'=>'print'));?> 
<?php echo $this->renderElement('js/default')?> 
</head>
<body><div id="main">
<div id="header">
<h1>Dux</h1>
</div>

<?php echo $this->renderElement('search/search')?> 
<?php print $this->renderElement('menu')?> 
<?php echo $this->renderElement('sidebar')?> 

<div id="content">
<?php echo $this->renderElement('users/details')?> 

<?php
if ($session->check('Message.flash')) $session->flash();
echo $content_for_layout;
?> 
</div>

</div>

<?php echo $this->renderElement('footer')?> 
</body>
</html>