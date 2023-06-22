<?php

namespace JustBetter\CustomerPricing\Observer;

use JustBetter\CustomerPricing\Actions\CustomerPrice;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\AttributeValue;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class FinalPrice implements ObserverInterface
{
    public function __construct(
        protected Session $customerSession,
        protected UserContextInterface $userContext,
        protected LoggerInterface $logger,
        protected CustomerPrice $customerPrice
    ) {
    }

    public function execute(Observer $observer): void
    {
        $customerId = $this->customerSession->getCustomerId();

        if ($customerId === null) {
            return;
        }

        /** @var Product $product */
        $product = $observer->getProduct();
        $qty = $observer->getQty() ?? 1;

        $price = $this->customerPrice->getCustomerPrice($product->getId(), $customerId, $qty);

        if ($price === null) {
            return;
        }

        $product->setData('final_price', $price);
        $product->setData('price', $price);
        $observer->setData('product', $product);
    }

}
