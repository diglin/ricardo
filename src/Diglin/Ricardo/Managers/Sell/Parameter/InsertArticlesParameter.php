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
 * Class InsertArticlesParameter
 * @package Diglin\Ricardo\Managers\Sell
 */
class InsertArticlesParameter extends ParameterAbstract
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
        'articles',
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
     * @param InsertArticleParameter $articles
     * @param bool $clear
     * @return $this
     */
    public function setArticles(InsertArticleParameter $articles, $clear = false)
    {
        if ($clear) {
            $this->_articles = array();
        }
        $this->_articles[] = $articles;
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