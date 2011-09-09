<?php
/**
 * Observer model
 *
 */
class Helti100_Varnish_Model_RequestObserver
{
    /**
     * Check when varnish caching should be enabled.
     *
     * @param Varien_Event_Observer $observer
     * @return Helti100_Varnish_Model_Observer
     */
    public function actionPreDispatch(Varien_Event_Observer $observer)
    {
        /* @var $helper Helti100_Varnish_Helper_Data */
        $helper = Mage::helper('helti100_varnish');
        
        $controllerAction = $observer->getEvent()->getControllerAction();
        
        /* @var $request Mage_Core_Controller_Request_Http */
        $request = $controllerAction->getRequest();
        /* @var $response Mage_Core_Controller_Response_Http */
        $response = $controllerAction->getResponse();
        
        $fullActionName = $controllerAction->getFullActionName();
        
        $lifetime = $helper->isCacheableAction($fullActionName);
        
        if (!$lifetime) {
            return $this;
        }
        
        $response->setHeader('X-Magento-Lifetime', $lifetime, true); // Only for debugging and information
        $response->setHeader('X-Magento-Action', $fullActionName, true); // Only for debugging and information
        $response->setHeader('Cache-Control', 'max-age='. $lifetime, true);
        $response->setHeader('X-Helti100-Varnish', 'cache', true);

        return $this;
    }
}
