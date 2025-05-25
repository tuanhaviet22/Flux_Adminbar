<?php
declare(strict_types=1);

namespace Flux\AdminBar\Controller\Adminhtml\Auth;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\Model\Auth\Session as AdminSession;

class Check extends Action implements HttpGetActionInterface
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
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param AdminSession $adminSession
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        AdminSession $adminSession
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->adminSession = $adminSession;
    }

    /**
     * Check if admin is logged in
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData([
            'isLoggedIn' => $this->adminSession->isLoggedIn(),
            'userId' => $this->adminSession->isLoggedIn() ? $this->adminSession->getUser()->getId() : null
        ]);
    }
} 