<?php

class Helti100_Varnish_Block_InterceptedMessages extends Mage_Core_Block_Messages {

	public function _construct() {
		Mage::log("Helti100_Varnish_Block_InterceptedMessages: Im alive", Zend_Log::INFO);
	}

	public function _toHtml() {
		Mage::log("Helti100_Varnish_Block_InterceptedMessages: in _tohtml");

		$session = Mage::getSingleton('core/session');
		$messages = $session->getData(Helti100_Varnish_Model_MessageObserver::SESSION_MSGS_KEY);
		$session->setData(Helti100_Varnish_Model_MessageObserver::SESSION_MSGS_KEY); // unset
		if ($messages) {
			$this->setMessages($messages);
		}
		return parent::_toHtml();
	}	

}
