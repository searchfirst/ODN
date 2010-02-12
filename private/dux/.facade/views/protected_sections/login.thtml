<h2><?php echo htmlentities(MOONLIGHT_PROTECTED_SECTIONS_TITLE) ?></h2>

<form method="post" action="/private">
<?php echo $form->labelTag('ProtectedSection/title','Login ID')?> 
<?php echo $html->input('ProtectedSection/title',array('size'=>20))?> 
<?php echo $html->tagErrorMsg('ProtectedSection/title','Please provide a valid ID')?> 
<?php echo $form->labelTag('ProtectedSection/password','Password')?> 
<?php echo $html->password('ProtectedSection/password',array('size'=>20))?>
<?php echo $html->tagErrorMsg('ProtectedSection/password','Please provide a valid password')?> 
<?php echo $html->submit('Login')?>
</form>