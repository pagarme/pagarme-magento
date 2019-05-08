<?php

abstract class PagarMe_Core_Model_AbstractPaymentMethod extends Mage_Payment_Model_Method_Abstract
{
    use PagarMe_Core_Trait_ConfigurationsAccessor;

    protected $_isInitializeNeeded = true;
    
    /**
     * Returns payment method code for postback route
     *
     * @return string
     */
    abstract protected function getPostbackCode();

    /**
     * @codeCoverageIgnore
     * @return string
     */
    protected function getUrlForPostback()
    {
        $postbackUrl = Mage::getBaseUrl();
        $developmentPostbackUrl = $this->getDevelopmentPostbackUrl();

        if ($this->isDeveloperModeEnabled() && $developmentPostbackUrl !== '') {
            $postbackUrl = $developmentPostbackUrl;
        }

        $postbackUrl .=  sprintf(
            'pagarme_core/%s/postback',
            $this->getPostbackCode()
        );

        return $postbackUrl;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Return boolean depending on a module system configuration
     *
     * @see    app/code/community/PagarMe/Core/etc/system.xml
     * @return boolean
     */
    public function isTransparentCheckoutActive()
    {
        return $this->isTransparentCheckoutActiveStoreConfig();
    }

    /**
     * Check whether payment method can be used
     *
     * @param Mage_Sales_Model_Quote|null $quote
     *
     * @return boolean
     */
    public function isAvailable($quote = null)
    {
        $isInAvailableCaptureMethods = strpos(
            $this->getActiveTransparentPaymentMethod(),
            $this->getCode()
        );

        return $this->isTransparentCheckoutActive()
            && $isInAvailableCaptureMethods !== false;
    }
}
