<?php
declare(strict_types=1);

namespace Flux\AdminBar\Block;

use Magento\Backend\Model\Auth\Session as AdminSession;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Model\Page as CmsPage;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Authorization\Model\UserContextInterface;

class Adminbar extends Template
{
    /**
     * @var AdminSession
     */
    private $adminSession;

    /**
     * @var AppState
     */
    private $appState;

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var UserContextInterface
     */
    private $userContext;

    /**
     * @param Context $context
     * @param AdminSession $adminSession
     * @param AppState $appState
     * @param DeploymentConfig $deploymentConfig
     * @param Registry $registry
     * @param UrlInterface $urlBuilder
     * @param PageRepositoryInterface $pageRepository
     * @param CustomerSession $customerSession
     * @param UserContextInterface $userContext
     * @param array $data
     */
    public function __construct(
        Context $context,
        AdminSession $adminSession,
        AppState $appState,
        DeploymentConfig $deploymentConfig,
        Registry $registry,
        UrlInterface $urlBuilder,
        PageRepositoryInterface $pageRepository,
        CustomerSession $customerSession = null,
        UserContextInterface $userContext = null,
        array $data = []
    ) {
        $this->adminSession = $adminSession;
        $this->appState = $appState;
        $this->deploymentConfig = $deploymentConfig;
        $this->registry = $registry;
        $this->urlBuilder = $urlBuilder;
        $this->pageRepository = $pageRepository;
        $this->customerSession = $customerSession ?: ObjectManager::getInstance()->get(CustomerSession::class);
        $this->userContext = $userContext ?: ObjectManager::getInstance()->get(UserContextInterface::class);
        parent::__construct($context, $data);
    }

    /**
     * Get admin session object
     *
     * @return AdminSession
     */
    public function getAdminSession(): AdminSession
    {
        return $this->adminSession;
    }

    /**
     * Get app state object
     *
     * @return AppState
     */
    public function getAppState(): AppState
    {
        return $this->appState;
    }

    /**
     * Check if the admin bar should be displayed
     *
     * @return bool
     */
    public function shouldDisplay(): bool
    {
        // Hide in production mode
        if ($this->appState->getMode() === AppState::MODE_PRODUCTION) {
            return false;
        }

        // Check if admin cookie exists
        $cookieManager = ObjectManager::getInstance()->get(\Magento\Framework\Stdlib\CookieManagerInterface::class);
        $adminCookieName = 'admin';
        $adminCookie = $cookieManager->getCookie($adminCookieName);

        // Check for presence of admin session or cookie
        if ($this->adminSession->isLoggedIn() || $adminCookie) {
            return true;
        }

        // Check if this is an admin user
        $isAdmin = false;
        try {
            $customerData = $this->customerSession->getCustomerData();
            if ($customerData && $customerData->getId()) {
                // Get current user from user context
                $userId = $this->userContext->getUserId();
                $userType = $this->userContext->getUserType();
                
                // Check if user is admin type
                $isAdmin = ($userType === UserContextInterface::USER_TYPE_ADMIN && $userId);
            }
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            return false;
        }

        return $isAdmin;
    }

    /**
     * Get current product if on product page
     *
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * Get current CMS page if on CMS page
     *
     * @return CmsPage|null
     */
    public function getCurrentCmsPage()
    {
        return $this->registry->registry('cms_page');
    }

    /**
     * Get admin URL for editing current product
     *
     * @param int $productId
     * @return string
     */
    public function getProductEditUrl(int $productId): string
    {
        return $this->getAdminUrl('catalog/product/edit', ['id' => $productId]);
    }

    /**
     * Get admin URL for editing current CMS page
     *
     * @param int $pageId
     * @return string
     */
    public function getCmsPageEditUrl(int $pageId): string
    {
        return $this->getAdminUrl('cms/page/edit', ['page_id' => $pageId]);
    }
    
    /**
     * Get admin dashboard URL
     *
     * @return string
     */
    public function getAdminDashboardUrl(): string
    {
        return $this->getAdminUrl('admin/dashboard');
    }

    /**
     * Get admin URL for given path and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    private function getAdminUrl(string $route, array $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
} 