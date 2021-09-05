<?php
declare(strict_types=1);

namespace Hyper\Auth\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface AuthRepositoryInterface
{

    /**
     * Save Auth
     * @param \Hyper\Auth\Api\Data\AuthInterface $auth
     * @return \Hyper\Auth\Api\Data\AuthInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Hyper\Auth\Api\Data\AuthInterface $auth);

    /**
     * Retrieve Auth
     * @param string $authId
     * @return \Hyper\Auth\Api\Data\AuthInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($authId);

    /**
     * Retrieve Auth matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Hyper\Auth\Api\Data\AuthSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Auth
     * @param \Hyper\Auth\Api\Data\AuthInterface $auth
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Hyper\Auth\Api\Data\AuthInterface $auth);

    /**
     * Delete Auth by ID
     * @param string $authId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($authId);
}

