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
namespace Diglin\Ricardo\Managers\Sell\Parameter;

use \Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class ArticleInternalReferenceParameter
 */
class ArticleInternalReferenceParameter extends ParameterAbstract
{
    /**
     * @var int
     */
    protected $_internalReferenceTypeId; // required

    /**
     * @var string
     */
    protected $_internalReferenceValue; // required

    protected $_requiredProperties = array(
        'internalReferenceTypeId',
        'internalReferenceValue'
    );

    /**
     * @param string $internalReferenceValue
     * @return $this
     */
    public function setInternalReferenceValue($internalReferenceValue)
    {
        $this->_internalReferenceValue = $internalReferenceValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getInternalReferenceValue()
    {
        return $this->_internalReferenceValue;
    }

    /**
     * @param string $internalReferenceTypeId
     * @return $this
     */
    public function setInternalReferenceTypeId($internalReferenceTypeId)
    {
        $this->_internalReferenceTypeId = $internalReferenceTypeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getInternalReferenceTypeId()
    {
        return $this->_internalReferenceTypeId;
    }
}
