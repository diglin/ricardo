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
 * Class DeletePlannedArticlesParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class DeletePlannedArticlesParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_antiforgeryToken; // required

    /**
     * @var array
     */
    protected $_articles = array(); // required

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
     * @param DeletePlannedArticleParameter $plannedArticle
     * @param bool $clear
     * @return $this
     */
    public function setArticles(DeletePlannedArticleParameter $plannedArticle = null, $clear = false)
    {
        if ($clear) {
            $this->_articles = array();
        }

        if (is_null($plannedArticle)) {
            return $this;
        }

        $this->_articles[] = $plannedArticle;
        return $this;
    }

    /**
     * @return array
     */
    public function getArticles()
    {
        return $this->_articles;
    }
}