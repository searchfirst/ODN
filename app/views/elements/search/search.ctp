<section role="search">
<?php echo $this->Form->create(false,array("type"=>"get","inputDefaults"=>array("div"=>false,"label"=>false),"url"=>array("controller"=>"customers","action"=>"search"))) ?> 
<?php echo $this->Form->input("q",array("label"=>"Search","autosave"=>"co.uk.searchfirst.dux","results"=>10,"type"=>"search")) ?> 
<?php echo $this->Form->end(array("div"=>false,"label"=>"Go")) ?> 
</section>
