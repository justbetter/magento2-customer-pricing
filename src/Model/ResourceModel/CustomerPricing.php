<?php

namespace JustBetter\CustomerPricing\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerPricing extends AbstractDb
{
    protected string $_entityIdField = 'id';

    protected function _construct()
    {
        $this->_init('customer_pricing', 'id');
    }
}
