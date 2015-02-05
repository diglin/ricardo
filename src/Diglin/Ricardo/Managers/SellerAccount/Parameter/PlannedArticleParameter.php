<?php
/**
 * Diglin GmbH - Switzerland
 *
 * This file is part of a Diglin GmbH module.
 *
 * This Diglin GmbH module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
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
