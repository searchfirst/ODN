<h2><?php echo $html->link('Employee','/users/') ?>: <?php echo $user['User']['name']?></h2>
<div class="options">
<?php echo $this->renderElement('edit_form',array('id'=>$user['User']['id'],'title'=>$user['User']['name']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$user['User']['id'],'title' => $user['User']['name']))?> 
</div>

<div id="item_display">
<ul>
<li><a href="#item_display_information">Information</a></li>
<li><a href="#item_display_children">Customers</a></li>
</ul>

<div id="item_display_information">
<h3 class="tabs-heading">Information</h3>
<dl>
<?php if(empty($user['User']['resigned'])):?>
<dt>Joined:</dt>
<dd><?php echo $time->niceShort($user['User']['joined'])?></dd>
<?php else:?>
<dt>Resigned:</dt>
<dd><?php echo $time->niceShort($user['User']['resigned'])?></dd>
<?php endif;?>
<dt>Telephone:</dt>
<dd><?php echo $user['User']['telephone']?></dd>
<?php if(!empty($user['User']['fax'])):?>
<dt>Fax:</dt>
<dd><?php echo $user['User']['fax']?></dd>
<?php endif;?>
<?php if(!empty($user['User']['email'])):?>
<dt>Email:</dt>
<dd><?php echo $html->link($user['User']['email'],'mailto:'.$user['User']['email'])?></dd>
<?php endif;?>
<dt>Address:</dt>
<dd>
<?php echo nl2br($user['User']['address'])?><br />
<?php echo $user['User']['town']?><br />
<?php echo $user['User']['county']?><br />
<?php echo $user['User']['post_code']?><br />
<?php echo $user['User']['country']?>
</dd>
</div>

<div id="item_display_children">
<h3 class="tabs-heading">Customers</h3>
<div class="item_list">
<h3>Sales</h3>
<?php foreach ($user['Customer'] as $i=>$customer):?>
<div class="item<?php echo $i%2?" even":"" ?>">
<div class="options">
<?php echo $this->renderElement('edit_form',array('id'=>$customer['id'],'title'=>$customer['company_name']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$customer['id'],'title'=>$customer['company_name']))?> 
</div>
<h3><?php echo $html->link($customer['company_name'],"/customers/view/{$customer['id']}") ?></h3> 
</div>
<?php endforeach;?>
<h3>Technical</h3>
<?php foreach ($user['TechnicalCustomer'] as $i=>$customer):?>
<div class="item<?php echo $i%2?" even":"" ?>">
<div class="options">
<?php echo $this->renderElement('edit_form',array('id'=>$customer['id'],'title'=>$customer['company_name']))?> 
<?php echo $this->renderElement('delete_form',array('id'=>$customer['id'],'title'=>$customer['company_name']))?> 
</div>
<h3><?php echo $html->link($customer['company_name'],"/customers/view/{$customer['id']}") ?></h3> 
</div>
<?php endforeach;?>
</div>
</div>

</div>