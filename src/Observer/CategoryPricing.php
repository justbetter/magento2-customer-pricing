<?php

namespace JustBetter\CustomerPricing\Observer;

use JustBetter\CustomerPricing\Actions\CustomerPrice;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class CategoryPricing implements ObserverInterface
{
    public function __construct(
        protected Session $customerSession,
        protected UserContextInterface $userContext,
        protected CustomerPrice $customerPrice,
        protected ScopeConfigInterface $scopeConfig,
        protected StoreManagerInterface $storeManager
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

            if ($this->scopeConfig->isSetFlag('customer_pricing/price/as_special_price', ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getId())) {
                $product->setSpecialPrice($price);
            } else {
                $product->setPrice($price);
            }
            $product->setFinalPrice($price);
        }
    }
}
