<?php

namespace PagarMe\Acceptance\Helper;

use PagarMe\Sdk\Customer\Address;
use PagarMe\Sdk\Customer\Phone;

trait CustomerDataProvider
{

    protected function getCustomerName()
    {
        return 'Joao Silva';
    }

    protected function getCustomerEmail()
    {
        return uniqid('user') . '@email.com';
    }

    protected function getCustomerDocumentNumber()
    {
        return '78863832064';
    }

    protected function getCustomerBornAt()
    {
        return '15071991';
    }

    protected function getCustomerGender()
    {
        return 'M';
    }

    protected function getValidCustomerData()
    {
        return [
            'born_at'         => $this->getCustomerBornAt(),
            'document_number' => $this->getCustomerDocumentNumber(),
            'email'           => $this->getCustomerEmail(),
            'gender'          => $this->getCustomerGender(),
            'name'            => $this->getCustomerName(),
            'address' => [
                'city'          => 'sao paulo,',
                'complementary' => 'apto,',
                'country'       => 'Brasil,',
                'neighborhood'  => 'pinheiros,',
                'state'         => 'SP,',
                'street'        => 'rua qualquer,',
                'street_number' => '13,',
                'zipcode'       => '05444040,',
            ],
            'phone' => [
                'ddd'    => 11,
                'ddi'    => 55,
                'number' => 999887766,
            ]
        ];
    }
}
