<?php
declare(strict_types=1);

namespace Myvendor\Comments\Model\Resolver;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Quote\Api\CartRepositoryInterface;

class AddCommentToCartOrder implements ResolverInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    private $maskedQuoteIdToQuoteId;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        \Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId
    ) {
        $this->cartRepository = $cartRepository;
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
    }

    public function resolve(
        $field,
        $context,
        $info,
        array $value = null,
        array $args = null
    ) {
        $cartId = $args['cartId'];
        $comment = $args['comment'];
        $cartId = $this->maskedQuoteIdToQuoteId->execute($cartId);

        try {
            $cart = $this->cartRepository->get($cartId);
            $cart->setData('comment', $comment);
            $this->cartRepository->save($cart);

            return ['success' => true, 'message' => 'Comment added to cart order successfully.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Unable to add the comment.'];
        }
    }
}
