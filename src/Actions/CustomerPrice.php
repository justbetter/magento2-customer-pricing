<?php

namespace JustBetter\CustomerPricing\Actions;

use JustBetter\CustomerPricing\Model\CustomerPricing;
use JustBetter\CustomerPricing\Model\ResourceModel\CustomerPricing\Collection;
use Psr\Log\LoggerInterface;
use Zend_Db_Select;

class CustomerPrice
{
    public function __construct(
        protected Collection $customerPricingCollection,
        protected LoggerInterface $logger,
    ) {
    }

    public function getCustomerPrice(int $productId, int $customerId, float $quantity = 1): ?float
    {
        $this->customerPricingCollection
            ->clear()
            ->getSelect()
            ->reset(Zend_Db_Select::WHERE)
            ->reset(Zend_Db_Select::ORDER);

        $customerPrices = $this->customerPricingCollection
            ->addFieldToFilter('product_id', ['eq' => $productId])
            ->addFieldToFilter('customer_id', ['eq' => $customerId])
            ->addFieldToFilter('quantity', ['lteq' => $quantity])
            ->setOrder('quantity')
            ->getItems();

        foreach ($customerPrices as $price) {
            if ($quantity >= (float) $price->getData('quantity')) {
                return (float) $price->getData('price');
            }
        }

        return null;
    }
}
