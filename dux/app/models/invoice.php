<?php
vendor('forge_fdf');
class Invoice extends AppModel {
	var $name = 'Invoice';
	var $validate = array();
	var $order = 'Invoice.created';
	var $recursive = 1;

	var $hasMany = array('Note'=>array());
	var $belongsTo = array('Service','Customer');
	
	var $invoiceTemplate = '/home/searchfirst/sfd.dux.me.uk/user/htdocs/media/invoice_template.pdf';
	
	function cacheFDF($id,$extra_vars=array()) {
		$modified = $this->field('modified',array('Invoice.id'=>$id));
		$fdf_file = $this->getCacheFilename($id,$extra_vars);
		if(!file_exists($fdf_file) || $this->isCacheOld($modified,$fdf_file)){
			$invoice = $this->find(array('Invoice.id'=>$id));
			$fdf_string = $this->invoiceToFDF($invoice,$extra_vars);
			$this->writeToCache($fdf_file,$fdf_string);
		}
	}
	
	function generatePDF($id,$extra_vars=null) {
		$fdf_filename = $this->getCacheFilename($id,$extra_vars);
		$template = $this->invoiceTemplate;
		$pdfcontent = false;
		ob_start();
		passthru('pdftk '.$template.' fill_form '.$fdf_filename.' output - flatten');
		$pdfcontent = ob_get_contents();
		ob_end_clean();
		return $pdfcontent;
	}
	
	function getCacheFilename($id,$extra_vars=null) {
		$tag = '';
		if(is_array($extra_vars) && count($extra_vars)) $tag = '-'.md5(serialize($extra_vars));
		$filename = ROOT.'/'.APP_DIR.'/tmp/cache/views/'.md5("invoice_$id").$tag.'.fdf';
		return $filename;
	}
	
	function getVatTotal(&$invoice) {
		$c_vat_modifier = $invoice['Invoice']['vat_rate']/100;
		$vattotal = $invoice['Invoice']['amount'] * $c_vat_modifier;
		if($invoice['Invoice']['vat_included']) return money_format('%.2n',$vattotal);
		else return 'N/A';
	}
	
	function getSubTotal(&$invoice) {
		return money_format('%.2n',$invoice['Invoice']['amount']);
	}
	
	function getGrandTotal(&$invoice) {
		$c_vat = (100 + $invoice['Invoice']['vat_rate'])/100;
		$vat_included = $invoice['Invoice']['vat_included'];
		$amount = $invoice['Invoice']['amount'];
		if($vat_included) $amount = round($amount * $c_vat,2);
		return money_format('%.2n',$amount);
	}
	
	function invoiceToFDF($invoice,$extra_vars=array()) {
		$fdf_array = array(
			'CompanyName'	=>	$invoice['Customer']['company_name'],
			'DateOfSupply'	=>	gmstrftime('%d %b %Y',strtotime($invoice['Invoice']['created'])),
			'InvoiceNo'	=>	$invoice['Invoice']['reference'],
			'VATTotal' => $this->getVatTotal($invoice),
			'SubTotal' => $this->getSubTotal($invoice),
			'GrandTotal' =>	$this->getGrandTotal($invoice)
		);
		$address = $invoice['Customer']['address'];
		$address .= (!empty($invoice['Customer']['town']))?"\n{$invoice['Customer']['town']}":'';
		$address .= (!empty($invoice['Customer']['county']))?"\n{$invoice['Customer']['county']}":'';
		$address .= (!empty($invoice['Customer']['post_code']))?"\n{$invoice['Customer']['post_code']}":'';
		if(!empty($invoice['Customer']['country']) && $invoice['Customer']['country']!='UK')
			$address .= "\n{$invoice['Customer']['country']}";
		$fdf_array['CompanyAddress'] = $address;
		if(!empty($extra_vars))
			foreach($extra_vars as $var_key=>$var_val)
				$fdf_array[$var_key] = $var_val;
		//ServiceList, ServicePrices
		$service_list = array();
		$service_prices = array();
		$breakdown = explode("\n",$invoice['Invoice']['description']);
		if(!empty($breakdown)) {
			foreach($breakdown as $breakdown_item) {
				if(preg_match('/,/',$breakdown_item)) {
					list($service_list[],$service_prices[]) = explode(",",$breakdown_item);
				} else {
					$service_list[] = $breakdown_item;
					$service_prices[] = '';
				}
			}
			$fdf_array['ServiceList'] = implode("\n",$service_list);
			$fdf_array['ServicePrices'] = implode("\n",$service_prices);
		}
		foreach($fdf_array as $i=>$fdfa)
			$fdf_array[$i] = iconv('UTF-8','ISO-8859-1//TRANSLIT',$fdfa);
		$fdf_data_names = array();
		$fields_hidden = array();
		$fields_readonly = array();
		$forge_fdf = forge_fdf('',$fdf_array,$fdf_data_names,$fields_hidden,$fields_readonly);
		return $forge_fdf;
	}
	
	function writeToCache($filename,$fdf_string) {
		return file_put_contents($filename,$fdf_string);
	}
	
	function isCacheOld($modified,$filename) {
		$file_date = filectime($filename);
		$last_mod_date = strtotime($modified);
		return $last_mod_date > $file_date;
	}
	
	function getVatRates() {
		//Switch to a better system eventually
		$vat_array = array('Standard UK VAT (17.5%)'=>'17.5','Credit Crunch UK VAT (15%)'=>'15');
	}
}
?>