<?php

abstract class Inovarti_Pagarme_Block_Adminhtml_AbstractPagarme
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Prepare grid collection object
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        if ($this->getCollection()) {

            $this->_preparePage();

            $columnId = $this->getParam($this->getVarNameSort(), $this->_defaultSort);
            $dir      = $this->getParam($this->getVarNameDir(), $this->_defaultDir);
            $filter   = $this->getParam($this->getVarNameFilter(), null);

            if (is_null($filter)) {
                $filter = $this->_defaultFilter;
            }

            if (is_string($filter)) {
                $data = $this->helper('adminhtml')->prepareFilterString($filter);

                if (!$data) {
                    return $this;
                }

                $this->getById($data);
                $this->_setFilterValues($data);
                $this->setCollection($this->collection);

            }
        }

        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function prepareCollection($pagarmeModel)
    {
        return $this->setCollectionData($pagarmeModel);
    }

    /**
     * @param $accounts
     * @param $collection
     * @return $this
     */
    private function setCollectionData($pagarmeModel)
    {
        $this->collection = $this->currentModel->getCollectionData($pagarmeModel);
        return $this;
    }

    /**
     * @param $data
     * @return $this|Inovarti_Pagarme_Block_Adminhtml_AbstractPagarme
     */
    protected function getById($data)
    {

        $modelById = $this->currentModel->getById($data['id']);

        if ($data) {
            return $this->setCollectionData([$modelById]);
        }

        $collection = Mage::getModel('pagarme/ServiceVarienDataCollection');
        $this->collection = $collection;
        return $this;
    }
}