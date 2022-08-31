<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Checkout
 * @author    Mpx
 */

namespace Mpx\Checkout\Helper;

use Magento\Checkout\Model\SessionFactory as CheckoutSessionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Checkout\Model\Cart;

/**
 * Helper data
 */
class Data extends AbstractHelper
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var CheckoutSessionFactory
     */
    protected $checkoutSessionFactory;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var \Webkul\MpTimeDelivery\Helper\Data
     */
    protected $_helper;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @param CheckoutSessionFactory $checkoutSessionFactory
     * @param CartRepositoryInterface $cartRepository
     * @param \Webkul\MpTimeDelivery\Helper\Data $_helper
     * @param ManagerInterface $messageManager
     * @param Cart $cart
     * @param Context $context
     */
    public function __construct(
        checkoutSessionFactory             $checkoutSessionFactory,
        CartRepositoryInterface            $cartRepository,
        \Webkul\MpTimeDelivery\Helper\Data $_helper,
        ManagerInterface                   $messageManager,
        Cart                               $cart,
        Context                            $context)
    {
        $this->checkoutSessionFactory = $checkoutSessionFactory;
        $this->cartRepository = $cartRepository;
        $this->_helper = $_helper;
        $this->messageManager = $messageManager;
        $this->cart = $cart;
        parent::__construct($context);
    }

    /**
     * Count Seller In Cart
     *
     * @return int
     */
    public function CountSellerInCart(): int
    {
        try {
            $sellerIds = [];
            if ($this->checkoutSessionFactory->create()->getQuote()->getId()) {
                $quote = $this->cartRepository->get($this->checkoutSessionFactory->create()->getQuote()->getId());
                foreach ($quote->getAllItems() as $item) {
                    $mpAssignProductId = $this->_helper->getAssignProduct($item);
                    $sellerIds[] = $this->_helper->getSellerId($mpAssignProductId, $item->getProductId());
                }

            }
        } catch
        (\Exception $e) {
            $this->messageManager->addErrorMessage('Error cannot count seller cart');
        }

        return count(array_unique($sellerIds));
    }
}
