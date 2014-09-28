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

use Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class PlannedArticleParameter
 * @package Diglin\Ricardo\Managers\SellerAccount\Parameter
 */
class PlannedArticleParameter extends ParameterAbstract
{
    /**
     * @var int
     */
    protected $_plannedArticleId;

    /**
     * @var int
     */
    protected $_plannedIndex;

    /**
     * @var boolean
     */
    protected $_withPicture;

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'plannedArticleId',
    );

    protected $_optionalProperties = array(
        'plannedIndex',
        'withPicture',
    );

    /**
     * @param int $plannedArticleId
     * @return $this
     */
    public function setPlannedArticleId($plannedArticleId)
    {
        $this->_plannedArticleId = (int) $plannedArticleId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlannedArticleId()
    {
        return (int) $this->_plannedArticleId;
    }

    /**
     * @param int $plannedIndex
     * @return $this
     */
    public function setPlannedIndex($plannedIndex)
    {
        $this->_plannedIndex = (int) $plannedIndex;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlannedIndex()
    {
        return (int) $this->_plannedIndex;
    }

    /**
     * @param boolean $withPicture
     * @return $this
     */
    public function setWithPicture($withPicture)
    {
        $this->_withPicture = (bool) $withPicture;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getWithPicture()
    {
        return (bool) $this->_withPicture;
    }
}