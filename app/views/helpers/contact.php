<?php
class ContactHelper extends Helper {

	function __construct($options = null) {
		parent::__construct($options);
	}

	function fullSummary(&$contact) {
		$summary = '';
		$emails = $this->emails($contact);
		$phones = $this->phoneSummary($contact);
		if (!empty($contact['address'])) {
			$summary .= $contact['address']."\n\n";
		}
		if (!empty($emails)) {
			$summary .= $emails."\n\n";
		}
		if (!empty($phones)) {
			$summary .= $phones."\n\n";
		}
		return $summary;
	}

	function emails(&$contact) {
		$text = '';
		if (!empty($contact['email'])) {
			$emails = explode(';',$contact['email']);
			$text = '<b>'.__('Email',true).':</b> ';
			foreach ($emails as $email) {
				$text .= "<a href=\"mailto:$email\">$email</a>";
			}
			return $text;
		} else { return ''; }
	}

	function phoneSummary(&$contact) {
		$summary = '';
		if (!empty($contact['telephone'])) {
			$summary .= "<b>Telephone:</b> {$contact['telephone']}\n";
		}
		if (!empty($contact['mobile'])) {
			$summary .= "<b>Mobile:</b> {$contact['mobile']}\n";
		}
		if (!empty($contact['fax'])) {
			$summary .= "<b>Fax:</b> {$contact['fax']}\n";
		}
		return $summary;
	}
}
