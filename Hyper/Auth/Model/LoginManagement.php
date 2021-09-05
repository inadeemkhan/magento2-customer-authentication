<?php
namespace Hyper\Auth\Model;

use Hyper\Auth\Api\LoginManagementInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\Url\DecoderInterface;
use Magento\Customer\Model\Customer;
use Magento\Store\Model\StoreManagerInterface;
use Hyper\Auth\Helper\Data;

class LoginManagement implements LoginManagementInterface
{
    /**
     * @var EncoderInterface
     */
    protected $_detailsEncode;
    /**
     * @var DecoderInterface
     */
    protected $_detailsDecode;
    /**
     * @var Customer
     */
    protected $_customer;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @param EncoderInterface $detailsEncode
     * @param DecoderInterface $detailsDecode
     * @param Customer $customer
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     */
    public function __construct(
        EncoderInterface $detailsEncode,
        DecoderInterface $detailsDecode,
        Customer $customer,
        StoreManagerInterface $storeManager,
        Data $helper
    ) {
        $this->_detailsEncode = $detailsEncode;
        $this->_detailsDecode = $detailsDecode;
        $this->_customer      = $customer;
        $this->_storeManager  = $storeManager;
        $this->_helper        = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function loginGetMethod($storeid, $details)
    {
        try{
            // Decoding requeted data.
            $decodedDetails = $this->getDecodedetails($details);
            $decodedDetailsArray = explode(" ", $decodedDetails);
             
            /** 
             * Expacting array sometning like below after exploding by space
             * Array(
             *     [0] => nadeemkhan@yopmail.com
             *     [1] => Nadeem
             *     [2] => Khan
             *     [3] => java@123
             * )
             */
             
            // Getting store id to pass in method.
            $websiteId = $this->_storeManager->getStore()->getWebsiteId();

            // Passing email Id here to check if customer already exist in store.
            $isCustomerExist = $this->customerExists($decodedDetailsArray[0], $websiteId);

            if($isCustomerExist):
                // Encoding data to create token.
                $encodedLoginToken = $this->getEncodeUrl($decodedDetails);
                $customerCollection = $this->_customer->loadByEmail($decodedDetailsArray[0]);

                // Creating array to save on Auth table.
                $saveArray = [
                    'customer_id'    => $customerCollection->getId(),
                    'customer_name'  => $customerCollection->getName(),
                    'customer_email' => $customerCollection->getEmail(),
                    'access_token'   => $encodedLoginToken
                ];

                // Saving data in Auth Table with Token information.
                $this->_helper->saveTokenDetails($saveArray);

                // Sending response back.
                $response = [
                    'storeId'      => $websiteId,
                    'email'        => $customerCollection->getEmail(),
                    'access_token' => $encodedLoginToken
                ];
                return json_encode($response);

            else:
                // Creating new customer If details not matching.
                $this->_helper->createNewCustomer($decodedDetailsArray);

                // Login and Redirecting to the Dasboard/Account Page.
                $this->_helper->getCutomerLogin($decodedDetailsArray[0]);

            endif;
        }catch(\Exception $e) {
            $response=['error' => $e->getdetails()];
        }
    }

    /**
     * @var string $details
     * 
     * @return string 
     */
    public function getDecodedetails($details)
    {
        return $this->_detailsDecode->decode($details);
    }

    /**
     * @var string $details
     * 
     * @return string 
     */
    public function getEncodeUrl($details)
    {
        return $this->_detailsEncode->encode($details);
    }

    /**
     * @param string $email
     * @param null $websiteId
     *
     * @return bool|\Magento\Customer\Model\Customer
     */
    public function customerExists($email, $websiteId)
    {
        $customer = $this->_customer;
        if ($websiteId) {
            $customer->setWebsiteId($websiteId);
        }
        $customer->loadByEmail($email);
        if ($customer->getId() && $customer->getIsActive()) {
            return $customer;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function loginPostMethod($storeid, $details, $city)
    {
        try{
            $response = [
                'storeid' => $storeid,
                'email'   => $details,
                'city'    => $city
            ];
        }catch(\Exception $e) {
            $response=['error' => $e->getdetails()];
        }
        return json_encode($response);
    }
}