<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Diglin\Ricardo\Managers\SellerAccount\Parameter;

use Diglin\Ricardo\Enums\Article\ArticlesTypes;
use Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class ClosedArticlesParameter
 * @package Diglin\Ricardo\Managers\SellerAccount\Parameter
 */
class ClosedArticlesParameter extends ParameterAbstract
{
    /**
     * @var int
     */
    protected $_articlesType = ArticlesTypes::ALL;

    /**
     * @var null
     */
    protected $_lastModificationDate = null;

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'articlesType',
    );

    protected $_optionalProperties = array(
        'lastModificationDate',
    );

    /**
     * @param int $articlesType
     * @return $this
     */
    public function setArticlesType($articlesType)
    {
        $this->_articlesType = (int) $articlesType;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticlesType()
    {
        return (int) $this->_articlesType;
    }

    /**
     * @param null $lastModificationDate
     * @return $this
     */
    public function setLastModificationDate($lastModificationDate)
    {
        $this->_lastModificationDate = $lastModificationDate;
        return $this;
    }

    /**
     * @return null
     */
    public function getLastModificationDate()
    {
        return $this->_lastModificationDate;
    }
}
