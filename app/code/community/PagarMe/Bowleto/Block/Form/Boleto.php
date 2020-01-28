<?php
class PagarMe_Bowleto_Block_Form_Boleto extends Mage_Payment_Block_Form
{
    use PagarMe_Core_Trait_ConfigurationsAccessor;
    const TEMPLATE = 'pagarme/form/boleto.phtml';

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate(self::TEMPLATE);
    }

    public function getEncryptionKey()
    {
        return Mage::getStoreConfig(
            'payment/pagarme_configurations/general_encryption_key'
        );
    }

    public function getCheckoutConfig()
    {
        $quote = $this->getQuote();
    }

    public function getCurrentSubtotal()
    {
        $subtotalPunctuated = Mage::getModel('checkout/session')->getQuote()->getData()['subtotal'];
        return preg_replace('/[^0-9]/', '', $subtotalPunctuated);
    }

    public function getAdminBoletoInstructions()
    {
      return $this->getBoletoInstructions();
    }
}
