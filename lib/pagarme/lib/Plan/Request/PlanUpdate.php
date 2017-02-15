<?php

namespace PagarMe\Sdk\Plan\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Plan\Plan;

class PlanUpdate implements RequestInterface
{
    /**
     * @var Plan $plan
     */
    private $plan;

    /**
     * @param Plan $plan
     */
    public function __construct(Plan $plan)
    {
        $this->plan = $plan;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        $plan = $this->plan;

        return [
            'id'              => $plan->getId(),
            'name'            => $plan->getName(),
            'trial_days'      => $plan->getTrialDays(),
            'charges'         => $plan->getCharges(),
        ];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return sprintf('plans/%d', $this->plan->getId());
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_PUT;
    }
}
