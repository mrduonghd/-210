<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Checkout
 * @author    Mpx
 */

namespace Mpx\Checkout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Mpx\Checkout\Helper\Data as MpxData;

/**
 * Display Error Message Cart
 */
class CheckMultiSellerPageShoppingCart implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var MpxData
     */
    protected $_helper;

    /**
     * @param MpxData $_helper
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        MpxData          $_helper,
        ManagerInterface $messageManager
    ) {
        $this->_helper = $_helper;
        $this->messageManager = $messageManager;
    }

    /**
     * Display error message page cart
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $numberSeller = $this->_helper->CountSellerInCart();
        if ($numberSeller > 1) {
            $this->messageManager->addErrorMessage(__(
                'You cannot purchase items from multiple stores at the same time.Sorry for your inconvenience, but please purchase for each store.'
            ));
        }
    }
}
