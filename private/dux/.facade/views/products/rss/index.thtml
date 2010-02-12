<?php if(!empty($products)):?>
<?php foreach($products as $product) {?>
	<item>
		<title><?php echo htmlentities($product['Product']['title']) ?></title>
		<link><?php echo "http://{$_SERVER['HTTP_HOST']}".$html->url('/products/'.$product['Product']['slug']) ?></link>
		<guid isPermaLink="true"><?php echo "http://{$_SERVER['HTTP_HOST']}".$html->url('/products/'.$product['Product']['slug']) ?></guid>
		<description>
			<![CDATA[<?php echo $textAssistant->htmlFormatted(
					$product['Product']['description'],$product['Resource'],'Product',
					$mediaAssistant->generateMediaLinkAttributes($product['Resource'],array())
					) ?>]]>
		</description>
		<author><?php echo MOONLIGHT_WEBMASTER_EMAIL.' ('.MOONLIGHT_WEBMASTER_NAME.')' ?></author>
		<pubDate><?php echo $time->toRSS($product['Product']['modified'])?></pubDate>
	</item>
<?php }?>
<?php else:?>
	<item>
		<title>No <?php echo MOONLIGHT_CATEGORIES_TITLE ?></title>
		<description>There are no products</description>
	</item>
<?php endif;?>
