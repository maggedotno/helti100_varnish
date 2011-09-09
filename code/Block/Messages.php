<?php

class Helti100_Varnish_Block_Messages extends Mage_Core_Block_Messages
{

	public function addMessage(Mage_Core_Model_Message_Abstract $msg) {
		parent::addMessage("All messages are overridden.");
	}

	public function addMessages(Mage_Core_Model_Message_Collection $msgs) {
		foreach ($msgs->getItems() as $msg) {
			$this->addMessage($msg);
		}
	}

	public function setMessages(Mage_Core_Model_Message_Collection $msgs) {
		// keep msgs somewhere else
		parent::setMessages(new Mage_Core_Model_Message_Collection());
	}

}


