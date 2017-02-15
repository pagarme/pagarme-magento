<?php

namespace PagarMe\Sdk\Plan;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Plan\Request\PlanCreate;
use PagarMe\Sdk\Plan\Request\PlanList;
use PagarMe\Sdk\Plan\Request\PlanGet;
use PagarMe\Sdk\Plan\Request\PlanUpdate;

class PlanHandler extends AbstractHandler
{
    /**
     * @param int $amount
     * @param int $days
     * @param string $name
     * @param int $trialDays
     * @param array $paymentsMethods
     * @param int $charges
     * @param int $installments
     * @return Plan
     */
    public function create(
        $amount,
        $days,
        $name,
        $trialDays = null,
        $paymentsMethods = [],
        $charges = null,
        $installments = null
    ) {
        $request = new PlanCreate(
            $amount,
            $days,
            $name,
            $trialDays,
            $paymentsMethods,
            $charges,
            $installments
        );

        $response = $this->client->send($request);

        return new Plan(get_object_vars($response));
    }

    /**
     * @param int $page
     * @param int $count
     * @return array
     */
    public function getList($page = null, $count = null)
    {
        $request = new PlanList(
            $page,
            $count
        );

        $response = $this->client->send($request);
        $plans = [];
        foreach ($response as $planData) {
            $plans[] = new Plan(get_object_vars($planData));
        }

        return $plans;
    }

    /**
     * @param int $planId
     * @return Plan
     */
    public function get($planId)
    {
        $request = new PlanGet($planId);

        $response = $this->client->send($request);

        return new Plan(get_object_vars($response));
    }

    /**
     * @param Plan $plan
     * @return Plan
     */
    public function update(Plan $plan)
    {
        $request = new PlanUpdate($plan);

        $response = $this->client->send($request);

        return new Plan(get_object_vars($response));
    }
}
