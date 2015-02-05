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
