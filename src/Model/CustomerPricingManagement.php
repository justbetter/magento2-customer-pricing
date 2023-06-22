<?php

namespace JustBetter\CustomerPricing\Model;

use JustBetter\CustomerPricing\Api\CustomerPricingManagementInterface;
use JustBetter\CustomerPricing\Model\CustomerPricingFactory as ModelFactory;
use JustBetter\CustomerPricing\Model\ResourceModel\CustomerPricingFactory as ResourceModelFactory;
use JustBetter\CustomerPricing\Model\ResourceModel\CustomerPricing\Collection;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;

class CustomerPricingManagement implements CustomerPricingManagementInterface
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected ModelFactory $customerFactory,
        protected ResourceModelFactory $customerPricingFactory,
        protected Collection $customerPricingCollection
    ) {
    }

    /**
     * @inheritDoc
     */
    public function saveCustomerPrices(string $sku, mixed $customerPrices): bool
    {
        /** @var Product $product */
        $product = $this->productRepository->get($sku);

        $productId = $product->getId();

        $currentCustomerPrices = $this->customerPricingCollection
            ->addFieldToFilter('product_id', ['eq' => $productId])
            ->load()
            ->getItems();

        $resourceModel = $this->customerPricingFactory->create();

        $updatedIds = [];

        foreach ($customerPrices as $customerPriceData) {
            $customerId = $customerPriceData['customer_id'];
            $quantity = $customerPriceData['quantity'];
            $price = $customerPriceData['price'];

            $updated = false;

            /** @var CustomerPricing $currentCustomerPrice */
            foreach ($currentCustomerPrices as $currentCustomerPrice) {
                if (
                    $currentCustomerPrice->getCustomerId() == $customerId &&
                    $currentCustomerPrice->getQuantity() == $quantity
                ) {
                    $currentCustomerPrice->setData('price', $price);

                    $resourceModel->save($currentCustomerPrice);

                    $updatedIds[] = $currentCustomerPrice->getId();

                    $updated = true;
                    break;
                }
            }

            if ($updated) {
                continue;
            }

            $customerPricing = $this->customerFactory->create();

            $customerPricing->setData('product_id', $productId);
            $customerPricing->setData('customer_id', $customerId);
            $customerPricing->setData('quantity', $quantity);
            $customerPricing->setData('price', $price);

            $resourceModel->save($customerPricing);
        }

        /** @var CustomerPricing $price */
        foreach ($currentCustomerPrices as $price) {
            if (in_array($price->getId(), $updatedIds)) {
                continue;
            }

            $resourceModel->delete($price);
        }

        return true;
    }
}
