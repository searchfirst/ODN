<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
<?php echo $this->Sma->link($assets['js']['head'],'js') ?>
<?php echo $this->Sma->link($assets['js']['foot'],'js') ?>
<script>
yepnope([
{test:Modernizr.localstorage,nope:['/js/libs/poly/storage.js']}
]);
</script>
