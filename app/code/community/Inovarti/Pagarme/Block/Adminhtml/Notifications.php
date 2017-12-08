<?php

class Inovarti_Pagarme_Block_Adminhtml_Notifications extends Mage_Adminhtml_Block_Template
{
    public function getMessage()
    {
        try{
            $helper = Mage::helper('pagarme_notifications');
            $latestVersion = $helper->getLatestVersionFor('master', $this->fetchGithubVersions());
            $version = Mage::getConfig()->getNode()->modules->Inovarti_Pagarme->version;
            if ($this->isLatestVersion($latestVersion['name'], $version)) {
                return __("There's a new version for the Pagar.me module!") . " " . sprintf("<a href='%s' target='_blank'>Click Here</a> for more information!", $latestVersion['html_url']);
            }
        } catch (\Exception $ex) {
        }

        return '';
    }

    private function isLatestVersion ($latestVersion, $actualVersion) {
        $latestVersionStripped = preg_replace('/[^0-9\.]/', '', $latestVersion);
        $actualVersionStripped = preg_replace('/[^0-9\.]/', '', $actualVersion);
        return $latestVersionStripped == $actualVersionStripped;
    }

    private function fetchGithubVersions ()
    {
        $headers = [
            'User-Agent: pagarme-magento'
        ];
        $defaults = [
            CURLOPT_URL => 'https://api.github.com/repos/pagarme/pagarme-magento/releases',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => false
        ];
        $ch = curl_init();
        curl_setopt($ch,  $defaults);
        $rawResponse = curl_exec($ch);
        $response =  json_decode($rawResponse);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode >= 400) {
            throw new \Exception();
        }

        return $response;
    }
}
