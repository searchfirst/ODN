<h2>
<?php echo $section['Section']['title']?></h2>
<div class="section_item">
<?php if(!empty($section['Decorative'][0])) echo $mediaAssistant->mediaLink($section['Decorative'][0],array('class'=>'banner'),'banner');?>
<?php echo $textAssistant->htmlFormatted($section['Section']['description'],$section['Resource'])?>
<div class="article_list">
<?php foreach($section['Article'] as $article):?>
<div class="item">
<h3><?php if(!empty($article['Decorative'][0])) echo $mediaAssistant->mediaLink($article['Decorative'][0],array('class'=>'deco'),'crop',false,null,'Article');?>
<?php echo $html->link($article['title'],"/articles/{$article['slug']}")?></h3>
<?php echo $textAssistant->htmlFormattedSnippet($article['description'])?>
</div>
<?php endforeach;?>
</div>
</div>