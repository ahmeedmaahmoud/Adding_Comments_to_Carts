<?php
namespace Myvendor\Comments\Controller\Checkout;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;

class SaveComment extends Action
{
    protected $checkoutSession;
    protected $cartRepository;

    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        CartRepositoryInterface $cartRepository
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->cartRepository = $cartRepository;
    }

    public function execute()
    {
        $comment = $this->getRequest()->getParam('comment');
        $quote = $this->checkoutSession->getQuote();
        $quote->setData('comment', $comment);

        try {
            $this->cartRepository->save($quote);
            $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $result->setData(['success' => true]);
        } catch (\Exception $e) {
            $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $result->setData(['success' => false, 'error' => $e->getMessage()]);
        }

        return $result;
    }
}
