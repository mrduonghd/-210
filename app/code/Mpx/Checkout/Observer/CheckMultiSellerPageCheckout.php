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
use Magento\Framework\UrlInterface;
use Mpx\Checkout\Helper\Data as MpxData;

/**
 * Add Product To Cart Before
 */
class CheckMultiSellerPageCheckout implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var MpxData
     */
    protected $_helper;

    /**
     * @param UrlInterface $url
     * @param MpxData $_helper
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        UrlInterface     $url,
        MpxData          $_helper,
        ManagerInterface $messageManager
    ) {

        $this->url = $url;
        $this->_helper = $_helper;
        $this->messageManager = $messageManager;
    }

    /**
     * Check Seller in Cart
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        try {
            $limitSellerOnCheckout = $this->_helper->CountSellerInCart();
            if ($limitSellerOnCheckout > 1) {
                $checkoutUrl = $this->url->getUrl('checkout/cart');
                $observer->getControllerAction()
                    ->getResponse()
                    ->setRedirect($checkoutUrl);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Error cannot check seller cart');
        }

        return $this;
    }
}
