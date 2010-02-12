<div id="search">

<div id="search_customer">
<form method="post" action="<?php echo $html->url('/customers/search')?>" accept-type="UTF-8">
<input type="search" name="q" autosave="co.uk.searchfirst.dux" results="10" placeholder="Customer Search" id="SearchCustomerQ" />
<?php //echo $form->submit('Search')?>
</form>
</div>

<div id="search_website">
<form method="post" action="<?php echo $html->url('/websites/search')?>" accept-type="UTF-8">
<input type="search" name="q" autosave="co.uk.searchfirst.dux" results="10" placeholder="Website Search" id="SearchWebsiteQ" />
<?php //echo $form->submit('Search')?>
</form>
</div>

<ul>
<li><a href="#search_customer">Customers</a></li>
<li><a href="#search_website">Websites</a></li>
</ul>

</div>