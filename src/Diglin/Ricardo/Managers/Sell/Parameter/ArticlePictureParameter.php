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

class ArticlePictureParameter extends ParameterAbstract
{
    /**
     * @var string
     */
    protected $_pictureBytes; // required

    /**
     * Enum Diglin\Ricardo\Enums\PictureExtension
     *
     * @var int
     */
    protected $_pictureExtension; // required

    /**
     * @var int
     */
    protected $_pictureIndex; // required

    protected $_requiredProperties = array(
        'pictureBytes',
        'pictureExtension',
        'pictureIndex'
    );

    /**
     * @param mixed $pictureBytes
     * @return $this
     */
    public function setPictureBytes($pictureBytes)
    {
        $this->_pictureBytes = $pictureBytes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureBytes()
    {
        return $this->_pictureBytes;
    }

    /**
     * @param mixed $pictureExtension
     * @return $this
     */
    public function setPictureExtension($pictureExtension)
    {
        $this->_pictureExtension = $pictureExtension;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureExtension()
    {
        return $this->_pictureExtension;
    }

    /**
     * @param mixed $pictureIndex
     * @return $this
     */
    public function setPictureIndex($pictureIndex)
    {
        $this->_pictureIndex = $pictureIndex;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureIndex()
    {
        return $this->_pictureIndex;
    }
}