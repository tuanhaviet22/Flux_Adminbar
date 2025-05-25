<?php
declare(strict_types=1);

namespace Flux\AdminBar\Controller\Auth;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\Model\Auth\Session as AdminSession;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Stdlib\CookieManagerInterface;

class Status implements ActionInterface, HttpGetActionInterface
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var AdminSession
     */
    private $adminSession;
    
    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @param JsonFactory $resultJsonFactory
     * @param AdminSession $adminSession
     * @param CookieManagerInterface $cookieManager
     */
    public function __construct(
        JsonFactory $resultJsonFactory,
        AdminSession $adminSession,
        CookieManagerInterface $cookieManager = null
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->adminSession = $adminSession;
        $this->cookieManager = $cookieManager ?: ObjectManager::getInstance()->get(CookieManagerInterface::class);
    }

    /**
     * Check if admin is logged in
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        
        $isLoggedIn = $this->adminSession->isLoggedIn();
        $adminCookie = $this->cookieManager->getCookie('admin');
        
        return $resultJson->setData([
            'isLoggedIn' => $isLoggedIn || (bool)$adminCookie,
            'userId' => $isLoggedIn ? $this->adminSession->getUser()->getId() : null
        ]);
    }
} 