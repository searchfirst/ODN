<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $textAssistant->sanitiseText($title_for_layout);?> | Dux</title>
<?php echo $html->css('default','stylesheet',array('media'=>'screen'));?> 
<?php echo $html->css('print','stylesheet',array('media'=>'print'));?> 
<?php echo $this->element('js/default')?> 
</head>
<body id="dashboard"><div id="main">
<div id="header">
<h1>Dux</h1>
</div>

<?php echo $this->element('search/search')?> 

<?php echo $this->element('menu')?> 

<?php echo $this->element('sidebar')?> 

<div id="content">
<?php echo $this->element('users/details')?> 

<?php
if ($session->check('Message.flash'))
    $session->flash();
echo $content_for_layout;
?>
</div>
</div>

<?php echo $this->element('footer')?> 
</body>
</html>
