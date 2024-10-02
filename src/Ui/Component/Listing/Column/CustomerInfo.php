<?php
namespace JustBetter\CustomerPricing\Ui\Component\Listing\Column;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class CustomerInfo extends Column
{
    protected $customerRepository;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CustomerRepositoryInterface $customerRepository,
        array $components = [],
        array $data = []
    ) {
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                try {
                    $customerId = $item['customer_id'];
                    $customer = $this->customerRepository->getById($customerId);
                    $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();
                    $item[$this->getData('name')] = $customerName . ' (' . $customerId . ')';
                } catch (\Exception $e) {
                    $item[$this->getData('name')] = '-';
                }
            }
        }

        return $dataSource;
    }
}
