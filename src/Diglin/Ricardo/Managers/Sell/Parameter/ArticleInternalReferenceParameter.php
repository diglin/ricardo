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