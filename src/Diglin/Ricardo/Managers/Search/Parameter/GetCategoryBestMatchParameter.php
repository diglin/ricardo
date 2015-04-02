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
namespace Diglin\Ricardo\Managers\Search\Parameter;

use Diglin\Ricardo\Enums\System\LanguageId;
use Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class GetCategoryBestMatchParameter
 * @package Diglin\Ricardo\Managers\Search\Parameter
 */
class GetCategoryBestMatchParameter extends ParameterAbstract
{
    /**
     * @var int
     */
    protected $_languageId = LanguageId::GERMAN;

    /**
     * @var int
     */
    protected $_numberMaxOfResult = 1;

    /**
     * @var bool
     */
    protected $_onlyAllowToSell = true;

    /**
     * @var string
     */
    protected $_sentence = '';

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'languageId',
        'numberMaxOfResult',
        'onlyAllowToSell',
        'sentence'
    );

    /**
     * @return int
     */
    public function getLanguageId()
    {
        return (int) $this->_languageId;
    }

    /**
     * @param int $languageId
     * @return $this
     */
    public function setLanguageId($languageId)
    {
        $this->_languageId = $languageId;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberMaxOfResult()
    {
        return (int) $this->_numberMaxOfResult;
    }

    /**
     * @param int $numberMaxOfResult
     * @return $this
     */
    public function setNumberMaxOfResult($numberMaxOfResult)
    {
        $this->_numberMaxOfResult = $numberMaxOfResult;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getOnlyAllowToSell()
    {
        return (bool) $this->_onlyAllowToSell;
    }

    /**
     * @param boolean $onlyAllowToSell
     * @return $this
     */
    public function setOnlyAllowToSell($onlyAllowToSell)
    {
        $this->_onlyAllowToSell = $onlyAllowToSell;
        return $this;
    }

    /**
     * @return string
     */
    public function getSentence()
    {
        return (string) $this->_sentence;
    }

    /**
     * @param string $sentence
     * @return $this
     */
    public function setSentence($sentence)
    {
        $this->_sentence = $sentence;
        return $this;
    }
}
