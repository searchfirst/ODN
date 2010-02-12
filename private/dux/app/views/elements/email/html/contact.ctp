<h1>Contact Form</h1>

<h2>Details</h2>

<ul>
<?php if(!empty($email_data['Contact']['name'])):?>
<li><strong>Name</strong>: <?php echo $email_data['Contact']['name']?></li>
<?php endif;?>
<?php if(!empty($email_data['Contact']['email'])):?>
<li><strong>Email</strong>: <?php echo $email_data['Contact']['email']?></li>
<?php endif;?>
<?php if(!empty($email_data['Contact']['telephone'])):?>
<li><strong>Phone</strong>: <?php echo $email_data['Contact']['telephone']?></li>
<?php endif;?>
</ul>

<?php foreach($email_data['Contact'] as $key=>$value):?>
<?php if(!preg_match('/name|email|telephone/',$key)):?>
<h2><?php echo Inflector::humanize($key);?></h2>
<?php echo $textAssistant->htmlFormatted($value);?> 

<?php endif;?>
<?php endforeach;?>