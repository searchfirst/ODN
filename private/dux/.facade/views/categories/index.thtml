<h2><?php echo htmlentities(MOONLIGHT_CATEGORIES_TITLE) ?></h2>

<div class="product_list">
<?php foreach ($categories as $category):?>
<div class="item">
<h3><?php if(isset($category['Decorative'][0])) echo $mediaAssistant->mediaLink($category['Decorative'][0],array('class'=>'alt deco'),'crop');?>
<?php echo $html->link($category['Category']['title'],'/categories/'.$category['Category']['slug'])?></h3>
<?php echo $textAssistant->htmlFormattedSnippet2($category['Category']['description'],$category['Resource'])?>
</div>
<?php endforeach; ?>
</div>