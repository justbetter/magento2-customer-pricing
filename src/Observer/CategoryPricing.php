<?php

namespace JustBetter\CustomerPricing\Observer;

use JustBetter\CustomerPricing\Actions\CustomerPrice;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CategoryPricing implements ObserverInterface
{
    public function __construct(
        protected Session $customerSession,
        protected UserContextInterface $userContext,
        protected CustomerPrice $customerPrice
    ) {
    }

    public function execute(Observer $observer): void
    {
        $customerId = $this->customerSession->getCustomerId();

        if ($customerId === null) {
            return;
        }

        $collection = $observer->getEvent()->getCollection();

        /** @var Product $product */
        foreach ($collection as $product) {
            $price = $this->customerPrice->getCustomerPrice($product->getId(), $customerId);

            if ($price === null) {
                continue;
            }

            $product->setPrice($price);
            $product->setFinalPrice($price);
        }
    }
}
