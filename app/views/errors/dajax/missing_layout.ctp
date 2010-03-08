<h1><?php __('Missing Layout'); ?></h1>
<p class="error"><?php echo sprintf(__("You are seeing this error because the layout file %s can not be found or does not exist.", true), $file);?></p>
<p><span class="notice"><strong><?php __('Notice'); ?>: </strong>
<?php echo sprintf(__('If you want to customize this error message, create %s', true), APP_DIR.DS."views/errors/missing_layout.ctp");?></span></p>
<p><span class="notice"><strong><?php __('Fatal'); ?>: </strong>
<?php echo sprintf(__('Confirm you have created the file: %s', true), $file);?></span></p>
