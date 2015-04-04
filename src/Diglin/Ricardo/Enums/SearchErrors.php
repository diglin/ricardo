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

namespace Diglin\Ricardo\Enums;

class SearchErrors extends AbstractEnums
{

    const EMPTYSENTENCE	= 1; // The search text is empty
    const NOCATEGORYFOUND = 2; // The no category found
    const BASESEARCHPARAMETERSEMPTY = 3; // The Base Search Parameters Empty
    const PAGENUMBERTOOSMALL = 4; // The page number is too small need start from 1
    const PAGESIZETOOSMALL = 5; // The Page Size is too small need start from 1
    const NORESULTFOUND = 6; // No result found
    const SITENUMBERISNOTSET = 7; // The site number is not selected default = 1
    const UNIVERSEMISS = 8; // The universe id is not selected
    const NUMBEROFARTICLESINCORECT = 9; // The Number Of Articles incorrect
    const NOTACCESSORYCATEGORY = 10; // Not accessory category
    const INCORRECTSELLERID = 11; // The incorrect seller identifier

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'The search text is empty', 'value' => self::EMPTYSENTENCE),
            array('label' => 'No category found', 'value' => self::NOCATEGORYFOUND),
            array('label' => 'The Base Search Parameters Empty', 'value' => self::BASESEARCHPARAMETERSEMPTY),
            array('label' => 'The page number is too small need start from 1', 'value' => self::PAGENUMBERTOOSMALL),
            array('label' => 'The Page Size is too small need start from 1', 'value' => self::PAGESIZETOOSMALL),
            array('label' => 'No result found', 'value' => self::NORESULTFOUND),
            array('label' => 'The site number is not selected default = 1', 'value' => self::SITENUMBERISNOTSET),
            array('label' => 'The universe id is not selected', 'value' => self::UNIVERSEMISS),
            array('label' => 'The Number Of Articles incorrect', 'value' => self::NUMBEROFARTICLESINCORECT),
            array('label' => 'Not accessory category', 'value' => self::NOTACCESSORYCATEGORY),
            array('label' => 'The incorrect seller identifier', 'value' => self::INCORRECTSELLERID),
        );
    }
}
