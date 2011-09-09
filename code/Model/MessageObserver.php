<?php
/**
 * Observer model
 *
 */
class Helti100_Varnish_Model_MessageObserver {
	
	const SESSION_MSGS_KEY = 'helti100varnish_messages';

	public function interceptMessage(Varien_Event_Observer $observer) {

		$time = gettimeofday(true);
		Mage::log("Helti100_Varnish_Model_MessageObserver: Eating messages.", Zend_Log::INFO);

		// our own message storage
		$session = Mage::getSingleton('core/session');
		if (!$session->getData(self::SESSION_MSGS_KEY)) {
			$session->setData(self::SESSION_MSGS_KEY, new Mage_Core_Model_Message_Collection());
		}
		$iceptMessages = $session->getData(self::SESSION_MSGS_KEY);

		// become a hacker to find all sessions
		$sessions = array();
		$ref = new ReflectionProperty('Mage', '_registry');
		$ref->setAccessible(true);
		foreach ($ref->getValue() as $key=>$value) {
			if (preg_match('/^_singleton\/[a-z]+\/session$/i', $key)) {
				$session = substr($key, strlen('_singleton/'));
				$sessions[] = $session;
				Mage::log("Helti100_Varnish_Model_MessageObserver: discovered session $session ($key)", Zend_Log::INFO);
			}
		}

		// give me the messages and forget them (mo-ha-haah!)
		foreach ($sessions as $sessionKey) {
			$session = Mage::getSingleton($sessionKey);
			if ($session instanceof Mage_Core_Model_Session_Abstract) {
				$messages = $session->getMessages(true);
				if ($messages instanceof Mage_Core_Model_Message_Collection) {
					$count = 0;
					foreach ($messages->getItems() as $message) {
						$iceptMessages->addMessage($message);
						$count++;
					}
					Mage::log("Helti100_Varnish_Model_MessageObserver: Yum-yum, $count consumed from $sessionKey.", Zend_Log::INFO);
				}
				else {
					Mage::log("Helti100_Varnish_Model_MessageObserver: $sessionKey has incompatible content " . get_class($messages));
				}
			}
			else {
				Mage::log("Helti100_Varnish_Model_MessageObserver: $sessionKey has incompatible type " . get_class($session));
			}
		}	

		$elapsed = gettimeofday(true) - $time;
		Mage::log("Helti100_Varnish_Model_MessageObserver: Burp! " . round($elapsed, 4) . 's', Zend_Log::INFO);

	}

}
