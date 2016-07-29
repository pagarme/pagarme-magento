<?php

class Inovarti_Pagarme_Model_ServiceVarienDataCollection
    extends Varien_Data_Collection
{
    /**
     * @param $field
     * @param null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        return $this;
    }
}