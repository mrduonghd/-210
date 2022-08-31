<?php
/**
 * Mpx Software.
 *
 * @category  Mpx
 * @package   Mpx_Checkout
 * @author    Mpx
 */

namespace Mpx\Checkout\Observer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mpx\Checkout\Helper\Data as MpxData;
use Magento\Checkout\Model\Session;

/**
 * Add Product To Payment Page Before
 */
class CheckSellerCheckoutSubmit implements ObserverInterface
{
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var  ResultFactory
     */
    protected $resultFactory;

    /**
     * @var MpxData
     */
    protected $data;

    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    protected $_responseFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ActionFlag
     */
    protected $actionFlag;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    protected $forwardFactory;

    /**
     * @param UrlInterface $url
     * @param MpxData $data
     * @param ResponseFactory $responseFactory
     * @param RedirectInterface $redirect
     * @param Session $session
     * @param ResultFactory $resultFactory
     * @param ManagerInterface $messageManager
     * @param ActionFlag $actionFlag
     * @param StoreManagerInterface $_storeManager
     */
    public function __construct(
        UrlInterface     $url,
        MpxData          $data,
        ResponseFactory $responseFactory,
        RedirectInterface $redirect,
        Session $session,
        ResultFactory  $resultFactory,
        ManagerInterface $messageManager,
        ActionFlag $actionFlag,
        StoreManagerInterface $_storeManager,
        ForwardFactory $forwardFactory

    ) {
        $this->_responseFactory = $responseFactory;
        $this->url = $url;
        $this->data = $data;
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->session = $session;
        $this->resultFactory = $resultFactory;
        $this->actionFlag = $actionFlag;
        $this->_storeManager = $_storeManager;
        $this->forwardFactory = $forwardFactory;
    }

    /**
     * Check Seller in Payment Page
     *
     * @param Observer $observer
     * @return \Magento\Framework\Controller\Result\Forward
     */
    public function execute(Observer $observer)
    {
        try {
            $numberSeller = $this->data->countSellerInCart();
            if ($numberSeller > 1) {
//                $cartUrl = $this->url->getUrl('checkout/cart/index');
//                return $this->_responseFactory->create()->setRedirect($cartUrl)->sendResponse();

                $event = $observer->getEvent();
                $CustomRedirectionUrl = $this->url->getUrl('/checkout/cart/');
                $this->redirect->setRedirect($CustomRedirectionUrl);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Error cannot check seller cart');
        }
    }
}
