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
namespace Diglin\Ricardo\Managers;
use Diglin\Ricardo\Managers\Search\Parameter\GetCategoryBestMatchParameter;

/**
 * Class SearchTest
 * @package Diglin\Ricardo\Managers
 */
class SearchTest extends TestAbstract
{
    /**
     * @var Search
     */
    protected $_searchManager;

    protected function setUp()
    {
        $this->_searchManager = new Search($this->getServiceManager());
        parent::setUp();
    }

    public function testGetCategoryBestMatch()
    {
        $parameter = new GetCategoryBestMatchParameter();
        $parameter->setSentence('iphone');
        $parameter->setNumberMaxOfResult(10);

        $result = $this->_searchManager->getCategoryBestMatch($parameter);

        $this->assertGreaterThanOrEqual(5, count($result), 'Counted Category less than 5');
        $this->assertArrayHasKey('CategoryId', $result[0], 'Category not found');

        $this->outputContent($result, 'Get Category Best Match: ', true);
    }

}
