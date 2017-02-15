<?php

namespace PagarMe\Sdk\Customer;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Client;
use PagarMe\Sdk\Customer\Request\CustomerCreate;
use PagarMe\Sdk\Customer\Request\CustomerGet;
use PagarMe\Sdk\Customer\Request\CustomerList;
use PagarMe\Sdk\Customer\Address;
use PagarMe\Sdk\Customer\Phone;

class CustomerHandler extends AbstractHandler
{
    use CustomerBuilder;

    /**
     * @param string $name
     * @param string $email
     * @param int $documentNumber
     * @param Address $address
     * @param Phone $phone
     * @param string $bornAt
     * @param string $gender
     */
    public function create(
        $name,
        $email,
        $documentNumber,
        Address $address,
        Phone $phone,
        $bornAt = null,
        $gender = null
    ) {
        $request = new CustomerCreate(
            $name,
            $email,
            $documentNumber,
            $address,
            $phone,
            $bornAt,
            $gender
        );

        $response = $this->client->send($request);

        return $this->buildCustomer($response);
    }

    /**
     * @param int $customerId
     */
    public function get($customerId)
    {
        $request = new CustomerGet($customerId);
        $response = $this->client->send($request);

        return $this->buildCustomer($response);
    }

    /**
     * @param int $page
     * @param int $count
     */
    public function getList($page = null, $count = null)
    {
        $request = new CustomerList($page, $count);
        $response = $this->client->send($request);

        $customers = [];
        foreach ($response as $customerResponse) {
            $customers[] = $this->buildCustomer($customerResponse);
        }

        return $customers;
    }
}
