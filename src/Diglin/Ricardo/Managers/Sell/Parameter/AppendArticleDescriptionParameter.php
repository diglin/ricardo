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
 * Class AppendArticleDescriptionParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class AppendArticleDescriptionParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_antiforgeryToken; // required

    /**
     * @var int
     */
    protected $_articleId; // required

    /**
     * @var array of AppendDescriptionParameter
     */
    protected $_descriptions = array();

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'antiforgeryToken',
        'articleId',
        'descriptions',
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
     * @param int $articleId
     * @return $this
     */
    public function setArticleId($articleId)
    {
        $this->_articleId = $articleId;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticleId()
    {
        return $this->_articleId;
    }

    /**
     * @param AppendDescriptionParameter $descriptions
     * @param bool $clear
     * @return $this
     */
    public function setDescriptions(AppendDescriptionParameter $descriptions, $clear = false)
    {
        if ($clear) {
            $this->_descriptions = array();
        }
        $this->_descriptions[] = $descriptions;
        return $this;
    }

    /**
     * @return array of \Diglin\Ricardo\Managers\Sell\Parameter\AppendDescriptionParameter
     */
    public function getDescriptions()
    {
        return $this->_descriptions;
    }


}