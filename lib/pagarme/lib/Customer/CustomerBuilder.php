<?php

namespace PagarMe\Sdk\Customer;

trait CustomerBuilder
{
    /**
     * @param array $customerData
     * @return Customer
     */
    private function buildCustomer($customerData)
    {
        $customerData->address = new Address(
            get_object_vars($customerData->addresses[0])
        );

        $customerData->phone = new Phone($customerData->phones[0]);

        $customerData->date_created = new \DateTime(
            $customerData->date_created
        );

        return new Customer(get_object_vars($customerData));
    }
}
