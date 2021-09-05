<?php
declare(strict_types=1);

namespace Hyper\Auth\Model\ResourceModel\Auth;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'auth_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Hyper\Auth\Model\Auth::class,
            \Hyper\Auth\Model\ResourceModel\Auth::class
        );
    }
}

