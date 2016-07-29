<?php

class Inovarti_Pagarme_Model_TransactionPayables extends Inovarti_Pagarme_Model_Library
{
    /**
     * @return Varien_Data_Collection
     * @throws Exception
     */
    public function preparePayables($transactionId)
    {
        $response = $this->request('/transactions/'.$transactionId.'/split_rules');
        $splitCollection = new Varien_Data_Collection();

        foreach ($response as $item) {

            $itemAmount = $item['amount'] / 100;
            $itemAmount = number_format($itemAmount, 2, '.', '');

            $item['amount'] = Mage::helper('core')->currency($itemAmount, true, false);
            $split = new Varien_Object();
            $split->setData($item);
            $splitCollection->addItem($split);
        }

        return $splitCollection;
    }

    /**
     * @param $url
     * @return mixed
     * @throws Exception
     * @throws PagarMe_Exception
     */
    private function request($url)
    {
        $request = new PagarMe_Request($url, 'GET');
        $response = $request->run();

        return $response;
    }
}