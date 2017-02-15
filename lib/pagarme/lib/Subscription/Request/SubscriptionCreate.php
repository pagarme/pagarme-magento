<?php

namespace PagarMe\Sdk\Subscription\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Plan\Plan;
use PagarMe\Sdk\Customer\Customer;

abstract class SubscriptionCreate implements RequestInterface
{
    /**
     * @var Plan $plan
     */
    protected $plan;

    /**
     * @var Customer $customer
     */
    protected $customer;

    /**
     * @var string $postbackUrl
     */
    protected $postbackUrl;

    /**
     * @var array $metadata
     */
    protected $metadata;

    /**
     * @var string $paymentMethod
     */
    protected $paymentMethod;

    /**
     * @var Plan $plan
     * @var Customer $customer
     * @var string $postbackUrl
     * @var array $metadata
     */
    public function __construct(
        Plan $plan,
        Customer $customer,
        $postbackUrl,
        $metadata
    ) {
        $this->plan        = $plan;
        $this->customer    = $customer;
        $this->postbackUrl = $postbackUrl;
        $this->metadata    = $metadata;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return [
            'plan_id'        => $this->plan->getId(),
            'payment_method' => $this->paymentMethod,
            'metadata'       => $this->metadata,
            'customer'       => [
                'name'            => $this->customer->getName(),
                'email'           => $this->customer->getEmail(),
                'document_number' => $this->customer->getDocumentNumber(),
                'address'         => $this->getAddresssData(),
                'phone'           => $this->getPhoneData(),
                'born_at'         => $this->customer->getBornAt(),
                'gender'          => $this->customer->getGender()
            ]
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'subscriptions';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }

    /**
     *  @return array
     */
    protected function getAddresssData()
    {
        $address = $this->customer->getAddress();

        $addressData = [
            'street'        => $address->getStreet(),
            'street_number' => $address->getStreetNumber(),
            'neighborhood'  => $address->getNeighborhood(),
            'zipcode'       => $address->getZipcode()
        ];

        if (!is_null($address->getComplementary())) {
            $addressData['complementary'] = $address->getComplementary();
        }

        if (!is_null($address->getCity())) {
            $addressData['city'] = $address->getCity();
        }

        if (!is_null($address->getState())) {
            $addressData['state'] = $address->getState();
        }

        if (!is_null($address->getCountry())) {
            $addressData['country'] = $address->getCountry();
        }

        return $addressData;
    }

    /**
     *  @return array
     */
    protected function getPhoneData()
    {
        $phone = $this->customer->getPhone();

        $phoneData = [
            'ddd'    => $phone->getDdd(),
            'number' => $phone->getNumber()
        ];

        if (!is_null($phone->getDdi())) {
            $phoneData['ddi'] = $phone->getDdi();
        }

        return $phoneData;
    }
}
