<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<title><?php echo $title_for_layout ?> | ODN</title>
<?php echo $this->element('css') ?> 
<?php echo $this->element('js') ?> 
</head>
<body>
<?php echo $this->element('header') ?> 
<?php echo $this->element('menu') ?> 
<?php echo $this->element('search/search') ?> 
<?php echo $this->element('sidebar') ?> 
<?php echo $this->Session->flash() ?> 
<?php echo $this->Session->flash('auth') ?> 
<section role="main" id="content">
<?php echo $content_for_layout ?> 
</section>
<?php echo $this->element('users/details') ?> 
<?php echo $this->element('footer') ?> 
<?php echo $this->element('js_foot') ?>
</body>
</html>
