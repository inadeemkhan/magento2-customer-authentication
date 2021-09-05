<?php
declare(strict_types=1);

namespace Hyper\Auth\Model\Data;

use Hyper\Auth\Api\Data\AuthInterface;

class Auth extends \Magento\Framework\Api\AbstractExtensibleObject implements AuthInterface
{

    /**
     * Get auth_id
     * @return string|null
     */
    public function getAuthId()
    {
        return $this->_get(self::AUTH_ID);
    }

    /**
     * Set auth_id
     * @param string $authId
     * @return \Hyper\Auth\Api\Data\AuthInterface
     */
    public function setAuthId($authId)
    {
        return $this->setData(self::AUTH_ID, $authId);
    }

    /**
     * Get id
     * @return string|null
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * Set id
     * @param string $id
     * @return \Hyper\Auth\Api\Data\AuthInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Hyper\Auth\Api\Data\AuthExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Hyper\Auth\Api\Data\AuthExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Hyper\Auth\Api\Data\AuthExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}

