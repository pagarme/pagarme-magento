<?php

use PagarMe_Core_Model_PostbackHandler_Exception as PostbackHandlerException;

abstract class PagarMe_Core_Transaction_AbstractPostbackController extends Mage_Core_Controller_Front_Action
{
    /**
     * @return Zend_Controller_Response_Abstract
     * @throws Zend_Controller_Response_Exception
     */
    public function postbackAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->getResponse()->setHttpResponseCode(405);
        }

        if (!$this->isValidRequest($request)) {
            return $this->getResponse()->setHttpResponseCode(400);
        }

        $transactionId = $request->getPost('id');
        $currentStatus = $request->getPost('current_status');
        $oldStatus = $request->getPost('old_status');

        try {
            Mage::getModel('pagarme_core/postback')
                ->processPostback(
                    $transactionId,
                    $currentStatus,
                    $oldStatus
                );
            return $this->getResponse()
                ->setBody('ok');
        } catch (PostbackHandlerException $postbackException) {
            return $this
                ->getResponse()
                ->setHttpResponseCode(200)
                ->setBody($postbackException->getMessage());
        } catch (Exception $exception) {
            return $this
                ->getResponse()
                ->setHttpResponseCode(500)
                ->setBody($exception->getMessage());
        }
    }

    /**
     * @param Mage_Core_Controller_Request_Http $request
     *
     * @return bool
     */
    protected function isValidRequest(
        Mage_Core_Controller_Request_Http $request
    ) {
        if ($request->getPost('id') == null) {
            return false;
        }

        if ($request->getPost('current_status') == null) {
            return false;
        }

        $signature = $request->getHeader('X-Hub-Signature');

        if ($signature == false) {
            return false;
        }

        if (!$this->isAuthenticRequest($request, $signature)) {
            return false;
        }

        return true;
    }

    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @param string $signature
     *
     * @return bool
     */
    protected function isAuthenticRequest(
        Mage_Core_Controller_Request_Http $request,
        $signature
    ) {
        return Mage::getModel('pagarme_core/sdk_adapter')->getPagarMeSdk()
            ->postback()
            ->validateRequest($request->getRawBody(), $signature);
    }
}
