<?php

namespace JustBetter\CustomerPricing\Api;

interface CustomerPricingManagementInterface
{
    /**
     * @param string $sku
     * @param mixed $customerPrices
     * @return bool
     */
    public function saveCustomerPrices(string $sku, mixed $customerPrices): bool;
}
