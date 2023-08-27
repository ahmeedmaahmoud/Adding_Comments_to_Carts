<?php

namespace Myvendor\Comments\Observer;
use Magento\Framework\DataObject\Copy as ObjectCopyService;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;

class OrderCommentObserver implements ObserverInterface
{
    /**
     * @var ObjectCopyService
     */
    private ObjectCopyService $objectCopyService;

    public function __construct(
        ObjectCopyService $objectCopyService
    ) {
        $this->objectCopyService = $objectCopyService;
    }
    public function execute(Observer $observer)
    {
        /* @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /* @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_convert_quote',
            'to_order',
            $quote,
            $order
        );

        return $this;
    }
}
