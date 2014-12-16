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
 * Class CloseArticleParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class CloseArticleParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_antiforgeryToken; // required

    /**
     * @var int
     */
    protected $_articleId; // required

    protected $_requiredProperties = array(
        'antiforgeryToken',
        'articleId',
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
}
