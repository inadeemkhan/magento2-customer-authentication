<?php
declare(strict_types=1);

namespace Hyper\Auth\Model;

use Hyper\Auth\Api\AuthRepositoryInterface;
use Hyper\Auth\Api\Data\AuthInterfaceFactory;
use Hyper\Auth\Api\Data\AuthSearchResultsInterfaceFactory;
use Hyper\Auth\Model\ResourceModel\Auth as ResourceAuth;
use Hyper\Auth\Model\ResourceModel\Auth\CollectionFactory as AuthCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class AuthRepository implements AuthRepositoryInterface
{

    protected $resource;

    protected $authFactory;

    protected $authCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataAuthFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceAuth $resource
     * @param AuthFactory $authFactory
     * @param AuthInterfaceFactory $dataAuthFactory
     * @param AuthCollectionFactory $authCollectionFactory
     * @param AuthSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceAuth $resource,
        AuthFactory $authFactory,
        AuthInterfaceFactory $dataAuthFactory,
        AuthCollectionFactory $authCollectionFactory,
        AuthSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->authFactory = $authFactory;
        $this->authCollectionFactory = $authCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAuthFactory = $dataAuthFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Hyper\Auth\Api\Data\AuthInterface $auth)
    {
        /* if (empty($auth->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $auth->setStoreId($storeId);
        } */
        
        $authData = $this->extensibleDataObjectConverter->toNestedArray(
            $auth,
            [],
            \Hyper\Auth\Api\Data\AuthInterface::class
        );
        
        $authModel = $this->authFactory->create()->setData($authData);
        
        try {
            $this->resource->save($authModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the auth: %1',
                $exception->getMessage()
            ));
        }
        return $authModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($authId)
    {
        $auth = $this->authFactory->create();
        $this->resource->load($auth, $authId);
        if (!$auth->getId()) {
            throw new NoSuchEntityException(__('Auth with id "%1" does not exist.', $authId));
        }
        return $auth->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->authCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Hyper\Auth\Api\Data\AuthInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Hyper\Auth\Api\Data\AuthInterface $auth)
    {
        try {
            $authModel = $this->authFactory->create();
            $this->resource->load($authModel, $auth->getAuthId());
            $this->resource->delete($authModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Auth: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($authId)
    {
        return $this->delete($this->get($authId));
    }
}

