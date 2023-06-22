<?php

namespace JustBetter\CustomerPricing\Model;

use Magento\Framework\Model\AbstractModel;

class CustomerPricing extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(ResourceModel\CustomerPricing::class);
    }
}
