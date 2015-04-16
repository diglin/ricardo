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

namespace Diglin\Ricardo\Services;

use Diglin\Ricardo\Managers\Search\Parameter\GetCategoryBestMatchParameter;

class Search extends ServiceAbstract
{
    /**
     * @var string
     */
    protected $_service = 'SearchService';

    /**
     * @var string
     */
    protected $_typeOfToken = self::TOKEN_TYPE_ANONYMOUS;

    /**
     * @param GetCategoryBestMatchParameter $getCategoryBestMatchParameter
     * @return array
     */
    public function getCategoryBestMatch(GetCategoryBestMatchParameter $getCategoryBestMatchParameter)
    {
        // there is a typo error into the documentation for "getCategoryBestMatchParamter", keep it here too
        return array(
            'method' => 'GetCategoryBestMatch',
            'params' => array('getCategoryBestMatchParamter' => $getCategoryBestMatchParameter->getDataProperties())
        );
    }

    /**
     * Get the best category result
     *
     * The Ricardo API returns:
     * <pre>
     * {
     *     "GetCategoryBestMatchResult": {
     *       "CategoriesBestMatch": [
     *          "CategoryId": "int",
     *          "CategoryName": "string",
     *        ],[],...
     *     }
     *   }
     * </pre>
     *
     * @param array $data
     * @return bool|array
     */
    public function getCategoryBestMatchResult($data)
    {
        if (isset($data['GetCategoryBestMatchResult']) && isset($data['GetCategoryBestMatchResult']['CategoriesBestMatch'])) {
            return $data['GetCategoryBestMatchResult']['CategoriesBestMatch'];
        }
        return false;
    }
}