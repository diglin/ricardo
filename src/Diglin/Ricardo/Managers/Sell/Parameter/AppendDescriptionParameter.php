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
 * Class AppendDescriptionParameter
 * @package Diglin\Ricardo\Managers\Sell\Parameter
 */
class AppendDescriptionParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_articleDescription; // required

    /**
     * Enums Diglin\Ricardo\Enums\System\LanguageCode
     *
     * @var int
     */
    protected $_languageId; // required

    /**
     * @var array
     */
    protected $_requiredProperties = array(
        'articleDescription',
        'languageId',
    );

    /**
     * @param ArticleDescriptionParameter $articleDescription
     * @param bool $clear
     * @return $this
     */
    public function setArticleDescription(ArticleDescriptionParameter $articleDescription, $clear = false)
    {
        if ($clear) {
            $this->_articleDescription = array();
        }

        $this->_articleDescription[] = $articleDescription;
        return $this;
    }

    /**
     * @return array
     */
    public function getArticleDescription()
    {
        return $this->_articleDescription;
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
    public function getLanguageId()
    {
        return $this->_languageId;
    }
}
