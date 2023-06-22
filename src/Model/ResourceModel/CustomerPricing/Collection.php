<?php

namespace JustBetter\CustomerPricing\Model\ResourceModel\CustomerPricing;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct(): void
    {
        $this->_init(
            \JustBetter\CustomerPricing\Model\CustomerPricing::class,
            \JustBetter\CustomerPricing\Model\ResourceModel\CustomerPricing::class
        );
    }
}
