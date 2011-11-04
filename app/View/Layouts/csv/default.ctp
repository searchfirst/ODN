<?php header('Content-type: text/csv;charset=UTF-8')?>
<?php if(!empty($mod_date_for_layout)) header('Last-Modified: '.$time->toRSS($mod_date_for_layout))?>
<?php echo $content_for_layout;?>