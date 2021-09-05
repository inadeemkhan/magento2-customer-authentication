<?php
declare(strict_types=1);

namespace Hyper\Auth\Api\Data;

interface AuthInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const AUTH_ID = 'auth_id';
    const ID = 'id';

    /**
     * Get auth_id
     * @return string|null
     */
    public function getAuthId();

    /**
     * Set auth_id
     * @param string $authId
     * @return \Hyper\Auth\Api\Data\AuthInterface
     */
    public function setAuthId($authId);

    /**
     * Get id
     * @return string|null
     */
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return \Hyper\Auth\Api\Data\AuthInterface
     */
    public function setId($id);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Hyper\Auth\Api\Data\AuthExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Hyper\Auth\Api\Data\AuthExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Hyper\Auth\Api\Data\AuthExtensionInterface $extensionAttributes
    );
}

