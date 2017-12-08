<?php

class Pagarme_Notifications_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getLatestVersionFor($baseBranch, $fetchVersions)
    {
        $resp = $fetchVersions;
        $versions = array_filter($resp, function ($version) {
            return $version->target_commitish == $baseBranch;
        });
        $latest = array_reduce($versions, function ($version1, $version2) {
            return $version1->name > $version2->name ? $version1 : $version2;
        });
        return $latest;
    }
}
