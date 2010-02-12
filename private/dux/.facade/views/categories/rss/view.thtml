<?php if(!empty($category['Product'])):?>
<?php foreach(array_splice($category['Product'],0,20) as $product) {?>
	<item>
		<title><?php echo htmlentities($product['title']) ?></title>
		<link>http://<?php echo $_SERVER['HTTP_HOST']."/products/{$product['slug']}" ?></link>
<?php if(!empty($product['Decorative'][0])):?>
		<image>http://<?php echo $_SERVER['HTTP_HOST']."/thumbs/".MOONLIGHT_IMAGE_DEFAULT_CONVERSION."/products/".$product['Decorative'][0]['slug'].'.'.$product['Decorative'][0]['extension'] ?></image>
<?php endif;?>
		<description>
			<?php echo $textAssistant->htmlFormatted($product['description']) ?>

		</description>
	</item>
<?php  }?>
<?php else:?>
	<item>
		<title>No <?php echo MOONLIGHT_CATEGORIES_TITLE ?></title>
		<link>http://<?php echo $_SERVER['HTTP_HOST'] ?></link>
		<description>There is nothing here at this time.</description>
	</item>
<?php endif;?>