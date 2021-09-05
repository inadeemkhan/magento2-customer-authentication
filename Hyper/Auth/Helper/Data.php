<?php
declare(strict_types=1);

namespace Hyper\Auth\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Hyper\Auth\Model\AuthFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\UrlInterface;

class Data extends AbstractHelper
{
    /**
     * @var Auth 
     */
    protected $_authFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $_customerRepo;
    /**
     * @var Session
     */
    protected $_customerSession;
    /**
     * @var ResultFactory
     */
    protected $_resultFactory;
    /**
     * @var UrlInterface
     */
    protected $_url;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CustomerFactory $customerFactory
     * @param Auth $authFactory
     * @param _customerRepo $customerRepo
     * @param Session $customerSession
     * @param ResultFactory $resultFactory
     * @param UrlInterface $url
     */
    public function __construct(
        Context $context,
        AuthFactory $authFactory,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepo,
        Session $customerSession,
        ResultFactory $resultFactory,
        UrlInterface $url
    ) {
        $this->_authFactory     = $authFactory;
        $this->_storeManager    = $storeManager;
        $this->_customerFactory = $customerFactory;
        $this->_customerRepo    = $customerRepo;
        $this->_customerSession = $customerSession;
        $this->_resultFactory   = $resultFactory;
        $this->_url             = $url;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * @var array
     * 
     * @return bool
     */
    public function saveTokenDetails($details)
    {
        try {
            $authFactory = $this->_authFactory->create();
            $tokenCollection = $authFactory->getCollection()->addFieldToFilter('customer_id', ['eq' => $details['customer_id']]);

            $tokenCollectionId = '';
            foreach ($tokenCollection as $tokenDetails) {
                $tokenCollectionId = $tokenDetails->getId();
            }

            if(!empty($details)):
                if($tokenCollectionId):
                   $authFactory->load($tokenCollectionId)->getData();
                endif;
                $authFactory->setData($details);
                $authFactory->save();
                return true;
            endif;
        } catch (\Exception $ex) {
            $response=['error' => $ex->getdetails()];
        }
    }

    /**
     * @var array
     * 
     * @return bool
     */
    public function createNewCustomer($decodedDetailsArray)
    {
        try {
            // Get Website ID.
            $websiteId = $this->_storeManager->getWebsite()->getWebsiteId();

            // Instantiate object of Customer Factory.
            $customer = $this->_customerFactory->create();
            $customer->setWebsiteId($websiteId);

            // Preparing data for new customer.
            $customer->setEmail($decodedDetailsArray[0]); 
            $customer->setFirstname($decodedDetailsArray[1]);
            $customer->setLastname($decodedDetailsArray[2]);
            $customer->setPassword($decodedDetailsArray[3]);

            // Save data
            $customer->save();
            return true;

        } catch (\Exception $ex) {
            $response=['error' => $ex->getdetails()];
        }
    }

    /**
     * @var array
     * 
     * @return bool
     */
    public function getCutomerLogin($customerEmail)
    {
        try {
            $customerRepo = $this->_customerRepo->get($customerEmail);                   
            $customer = $this->_customerFactory->create()->load($customerRepo->getId()); 
            $this->_customerSession->setCustomerAsLoggedIn($customer); 

            if($this->_customerSession->isLoggedIn()) {
                /** 
                 * @var \Magento\Framework\Controller\Result\Redirect $result 
                 */
                $result = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $result->setUrl($this->_url->getUrl('customer/account'));
                return $result;
            }else{
                echo "customer is Not Logged in";
                die("..........");
            }

        } catch (\Exception $ex) {
            $response=['error' => $ex->getdetails()];
        }
    }
}

