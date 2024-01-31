<?php

namespace JustBetter\CustomerPricing\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;

class ProductSku extends Column
{
    protected $productRepository;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected ProductResource $productResource,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $productId = $item['product_id'];
                $sku = $this->productResource->getProductsSku([$productId])[0] ?? [];
                $item[$this->getData('name')] = $sku['sku'] ?? '';
            }
        }

        return $dataSource;
    }
}
