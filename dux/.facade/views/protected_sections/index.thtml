<h2><?php echo htmlentities(MOONLIGHT_PROTECTED_SECTIONS_TITLE) ?>: <?php echo $textAssistant->sanitiseText($protected_section['ProtectedSection']['title'],true,true) ?></h2>
<div class="section_list">
<?php if(!empty($protected_section)):?>
<div class="item">
<?php if(isset($protected_section['Decorative'][0])) echo $mediaAssistant->mediaLink($protected_section['Decorative'][0],array('class'=>'banner'),'banner');?>
<?php echo $textAssistant->htmlFormattedSnippet($protected_section['ProtectedSection']['description'],$protected_section['Resource']) ?>
</div>
<?php if(!empty($protected_section['ProtectedItem'])):?>
<div class="article_list">
<?php foreach($protected_section['ProtectedItem'] as $protected_item) {?>
<div class="item">
<h3><?php if(isset($protected_item['Decorative'][0])) echo $mediaAssistant->mediaLink($protected_item['Decorative'][0],array('class'=>'deco'),'crop',false,null,'ProtectedItem')?> 
<?php echo $html->link($textAssistant->sanitiseText($protected_item['title'],true,true),"/private/{$protected_item['slug']}")?></h3>
<?php echo $textAssistant->htmlFormattedSnippet($protected_item['description'])?> 
</div>
<?php }?>
</div>
<?php endif;?>
<?php endif;?>
</div>
<form method="post" action="<?php echo $html->url('/private')?>" class="inline">
<p class="info">You are logged in as <?php echo $textAssistant->sanitiseText($protected_section['ProtectedSection']['title'],true,true) ?>. If you wish to change areas then logout:
<?php echo $html->hidden('ProtectedSection/logout',array('value'=>'1')) ?>
<?php echo $html->submit('Logout') ?></p>
</form>