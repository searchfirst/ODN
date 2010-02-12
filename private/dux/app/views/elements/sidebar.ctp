<div id="sidebar">

<?php if(!empty($moonlight_news_list)):?>
<h3>News</h3>
<ul>
<?php foreach($moonlight_news_list as $ni_key=>$ni_value) {?>
<li><?php echo $html->link($ni_value,"/articles/$ni_key")?></li>
<?php }?>
</ul>
<?php endif;?>

</div>