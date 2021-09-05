<?php
declare(strict_types=1);

namespace Hyper\Auth\Model;

use Hyper\Auth\Api\Data\AuthInterface;
use Hyper\Auth\Api\Data\AuthInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Auth extends \Magento\Framework\Model\AbstractModel
{

    protected $authDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'hyper_auth_auth';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param AuthInterfaceFactory $authDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Hyper\Auth\Model\ResourceModel\Auth $resource
     * @param \Hyper\Auth\Model\ResourceModel\Auth\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        AuthInterfaceFactory $authDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Hyper\Auth\Model\ResourceModel\Auth $resource,
        \Hyper\Auth\Model\ResourceModel\Auth\Collection $resourceCollection,
        array $data = []
    ) {
        $this->authDataFactory = $authDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve auth model with auth data
     * @return AuthInterface
     */
    public function getDataModel()
    {
        $authData = $this->getData();
        
        $authDataObject = $this->authDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $authDataObject,
            $authData,
            AuthInterface::class
        );
        
        return $authDataObject;
    }
}

