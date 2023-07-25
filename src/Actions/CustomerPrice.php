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

    public function getCustomerPrice(int $productId, int $customerId, int $quantity = 1): ?float
    {
        $this->customerPricingCollection
            ->clear()
            ->getSelect()
            ->reset(Zend_Db_Select::WHERE);

        $customerPrices = $this->customerPricingCollection
            ->addFieldToFilter('product_id', ['eq' => $productId])
            ->addFieldToFilter('customer_id', ['eq' => $customerId])
            ->load()
            ->getItems();

        usort(
            $customerPrices,
            fn (CustomerPricing $a, CustomerPricing $b): bool => $a->getData('quantity') < $b->getData('quantity')
        );

        foreach ($customerPrices as $price) {
            if ($quantity >= $price->getData('quantity')) {
                return $price->getData('price');
            }
        }

        return null;
    }
}
