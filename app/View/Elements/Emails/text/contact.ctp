CONTACT FORM
============

DETAILS
-------

<?php if(!empty($email_data['Contact']['name'])):?>
NAME: <?php echo $email_data['Contact']['name']?> 
<?php endif;?>
<?php if(!empty($email_data['Contact']['email'])):?>
EMAIL: <?php echo $email_data['Contact']['email']?> 
<?php endif;?>
<?php if(!empty($email_data['Contact']['telephone'])):?>
PHONE: <?php echo $email_data['Contact']['telephone']?> 
<?php endif;?>

<?php foreach($email_data['Contact'] as $key=>$value):?>
<?php if(!preg_match('/name|email|telephone/',$key)):?>
<?php echo Inflector::humanize($key);?> 
<?php echo str_repeat('-',strlen($key));?> 

<?php echo $value;?> 

<?php endif;?>
<?php endforeach;?>