<?php

namespace JustBetter\CustomerPricing\Controller\Adminhtml\CustomerPricing;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use JustBetter\CustomerPricing\Model\ResourceModel\CustomerPricing\CollectionFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        protected Filter $filter,
        protected CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $deletedItems = 0;

        foreach ($collection->getItems() as $item) {
            $item->delete();
            $deletedItems++;
        }

        $this->messageManager->addSuccessMessage(__('Deleted %1 item(s).', $deletedItems));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('customerpricing/index/index');
    }
}
