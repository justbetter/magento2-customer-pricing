<?php

namespace JustBetter\CustomerPricing\Observer;

use JustBetter\CustomerPricing\Actions\CustomerPrice;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Backend\Model\Session\Quote;
use Magento\Catalog\Model\Product;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class FinalPrice implements ObserverInterface
{
    public function __construct(
        protected Session $customerSession,
        protected UserContextInterface $userContext,
        protected CustomerRepositoryInterface $customerRepositoryInterface,
        protected LoggerInterface $logger,
        protected State $state,
        protected Quote $quote,
        protected CustomerPrice $customerPrice
    ) {
    }

    public function execute(Observer $observer): void
    {
        $customerId = $this->customerSession->getCustomerId();
        $isAdmin = $this->state->getAreaCode() === Area::AREA_ADMINHTML;

        if ($isAdmin) {
            $customerId = $this->quote->getCustomerId();
        } elseif ($customerId === null && ! $isAdmin) {
            $customerId = $this->userContext->getUserType() === UserContextInterface::USER_TYPE_CUSTOMER ? (int) $this->userContext->getUserId() : null;
        }

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
