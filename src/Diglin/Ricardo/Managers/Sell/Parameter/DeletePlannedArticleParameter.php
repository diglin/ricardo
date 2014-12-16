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
namespace Diglin\Ricardo\Managers\Sell\Parameter;

use Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class DeletePlannedArticleParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class DeletePlannedArticleParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_antiforgeryToken; // required

    /**
     * @var int
     */
    protected $_plannedArticleId = array(); // required

    /**
     * @var int
     */
    protected $_plannedIndex; // optional

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'antiforgeryToken',
        'plannedArticleId',
    );

    /**
     * @param string $antiforgeryToken
     * @return $this
     */
    public function setAntiforgeryToken($antiforgeryToken)
    {
        $this->_antiforgeryToken = $antiforgeryToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getAntiforgeryToken()
    {
        return $this->_antiforgeryToken;
    }

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
        return $this->_plannedArticleId;
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
        return $this->_plannedIndex;
    }
}
