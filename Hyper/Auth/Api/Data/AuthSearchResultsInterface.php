<?php
declare(strict_types=1);

namespace Hyper\Auth\Api\Data;

interface AuthSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Auth list.
     * @return \Hyper\Auth\Api\Data\AuthInterface[]
     */
    public function getItems();

    /**
     * Set id list.
     * @param \Hyper\Auth\Api\Data\AuthInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

