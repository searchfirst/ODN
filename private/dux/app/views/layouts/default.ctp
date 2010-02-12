<?php if(!empty($mod_date_for_layout)) header('Last-Modified: '.$time->toRSS($mod_date_for_layout)); ?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>'."\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="charset=utf-8" />
<title><?php echo $title_for_layout;?> » <?php echo MOONLIGHT_WEBSITE_NAME ?></title>
<?php echo $html->css('vendors/thickbox','stylesheet',array('media'=>'screen'));?> 
<?php echo $html->css('vendors/date_picker','stylesheet',array('media'=>'screen'));?> 
<?php echo $html->css('default','stylesheet',array('media'=>'screen'));?> 
<?php echo $html->css('print','stylesheet',array('media'=>'print'));?> 
<!--[if lte IE 6]><?php print $html->css('ie','stylesheet',array('media'=>'screen'));?><![endif]-->
<?php echo $this->renderElement('js/default')?> 
</head>
<body><div id="main">
<div id="header">
<h1><?php echo MOONLIGHT_WEBSITE_NAME ?></h1>
</div><!-- /header -->

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