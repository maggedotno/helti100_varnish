<?php

/**
 * CallController
 * Renders the block that are requested via an ajax call
 *
 */
class Helti100_Varnish_BlocksController extends Mage_Core_Controller_Front_Action {



	public function getAction() {
		
		if (!$this->getRequest()->isXmlHttpRequest()) {
			Mage::throwException('This is not an XmlHttpRequest'); 
		}

		$session = Mage::getSingleton('core/session');
		$url = Mage::getSingleton('core/url');

		$response = array();
		$response['sid'] = Mage::getModel('core/session')->getEncryptedSessionId();

		if ($currentProductId = $this->getRequest()->getParam('currentProductId')) {
			Mage::log('Product ID ' . $currentProductId);
			Mage::getSingleton('catalog/session')->setLastViewedProductId($currentProductId);
		}

		$this->loadLayout();
		$layout = $this->getLayout();

		$requestedBlockNames = $this->getRequest()->getParam('getBlocks');
		if ($requestedBlockNames && count($requestedBlockNames)>0) {
			foreach ($requestedBlockNames as $id => $requestedBlockName) {
				Mage::log('Fetching block ' . $requestedBlockName);
				if ($requestedBlockName=='_messages') {
					// deliver session messages
				        $msgs = $session->getTrappedMessages($msgs); // msgs will pe picked up on need
					$session->setTrappedMessages(array());
					$response['blocks'][$id] = implode('<br />', $msgs);
				}
				else {
					$block = $layout->getBlock($requestedBlockName);
					if ($block) {
						$html = $url->sessionUrlVar($block->toHtml()); // replaces ___SID stuff...
						$response['blocks'][$id] = $html;
					} else {
						$response['blocks'][$id] = 'BLOCK NOT FOUND';
					}
				}
			}
		}
		else {
			Mage::log('No blocks were requested.', 'WARN');
		}

		$this->getResponse()->setBody(Zend_Json::encode($response));
	}

}
