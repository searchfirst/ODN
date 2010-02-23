<h2><?php echo $textAssistant->sanitiseText($article['Section']['title'].': '.$article['Article']['title']) ?></h2>
<div class="article_display">
<?php if(!empty($article['Decorative'][0]))
echo $mediaAssistant->mediaLink($article['Decorative'][0],array('class'=>'deco'),'crop',true,array('rel'=>'article-gallery')); ?>
<?php echo $textAssistant->htmlFormatted(	$article['Article']['description'],
											$article['Resource'],
											'article',
											$mediaAssistant->generateMediaLinkAttributes($article['Resource'],array('rel'=>'article-gallery'))
);?>
</div>