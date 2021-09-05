<?php
declare(strict_types=1);

namespace Hyper\Auth\Model\ResourceModel;

class Auth extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('hyper_auth_auth', 'auth_id');
    }
}

