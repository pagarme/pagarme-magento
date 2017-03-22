<?php

class PagarMe_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
    use \PagarMe\Sdk\Customer\CustomerBuilder {
        \PagarMe\Sdk\Customer\CustomerBuilder::buildCustomer as _buildCustomer;
    }

    /**
     * @param array $data
     *
     * @return \stdClass
     */
    public function prepareCustomerData($data)
    {
        return (object) [
            'document_number' => Zend_Filter::filterStatic(
                $data['pagarme_checkout_customer_document_number'],
                'Digits'
            ),
            'document_type' => $data['pagarme_checkout_customer_document_type'],
            'name' => $data['pagarme_checkout_customer_name'],
            'email' => $data['pagarme_checkout_customer_email'],
            'born_at' => $data['pagarme_checkout_customer_born_at'],
            'addresses' => [
                (object) [
                    'street' => $data[
                        'pagarme_checkout_customer_address_street_1'
                    ],
                    'street_number' => $data[
                        'pagarme_checkout_customer_address_street_2'
                    ],
                    'complementary' => $data[
                        'pagarme_checkout_customer_address_street_3'
                    ],
                    'neighborhood' => $data[
                        'pagarme_checkout_customer_address_street_4'
                    ],
                    'city' => $data['pagarme_checkout_customer_address_city'],
                    'state' => $data['pagarme_checkout_customer_address_state'],
                    'zipcode' => $data[
                        'pagarme_checkout_customer_address_zipcode'
                    ],
                    'country' => $data[
                        'pagarme_checkout_customer_address_country'
                    ]
                ]
            ],
            'phones'          => [
                (object) [
                    'ddd' => $data['pagarme_checkout_customer_phone_ddd'],
                    'number' => $data['pagarme_checkout_customer_phone_number'],
                ],
            ],
            'gender' => $data['pagarme_checkout_customer_gender'],
            'date_created' => null
        ];
    }

    /**
     * @codeCoverageIgnore
     * @param array $array
     *
     * @return \PagarMe\Sdk\Customer\Customer
     */
    public function buildCustomer($array)
    {
        return $this->_buildCustomer($array);
    }

    /**
     * @param float $amount
     *
     * @return int
     */
    public function parseAmountToInteger($amount)
    {
        return intval($amount * 100);
    }

    /**
     * @param int $amount
     *
     * @return float
     */
    public function parseAmountToFloat($amount)
    {
        return floatval($amount / 100);
    }

    /**
     * @param string $phone
     *
     * @return string
     */
    public function getDddFromPhoneNumber($phone)
    {
        return substr(Zend_Filter::filterStatic($phone, 'Digits'), 0, 2);
    }

    /**
     * @param string $phone
     *
     * @return string
     */
    public function getPhoneWithoutDdd($phone)
    {
        return substr(Zend_Filter::filterStatic($phone, 'Digits'), 2);
    }
}
