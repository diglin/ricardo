<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Managers\Sell\Parameter;

use Diglin\Ricardo\Managers\ParameterAbstract;

/**
 * Class CloseArticlesParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class CloseArticlesParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_antiforgeryToken; // required

    /**
     * @var array
     */
    protected $_articleIds = array(); // required

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'antiforgeryToken',
        'articleIds',
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
     * @param CloseArticleParameter $articleIds
     * @param bool $clear
     * @return $this
     */
    public function setArticleIds(CloseArticleParameter $articleIds, $clear = false)
    {
        if ($clear) {
            $this->_articleIds = array();
        }
        $this->_articleIds[] = $articleIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getArticleIds()
    {
        return $this->_articleIds;
    }
}