<?php

class Inovarti_Pagarme_Model_Github_Service extends Mage_Core_Model_Abstract
{
    public function getV1LatestVersion()
    {
        $ch = curl_init('https://api.github.com/repos/pagarme/pagarme-magento/releases/latest');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: Pagarme-magento-v1'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        curl_close($ch);
        return json_decode($resp);
    }
}
