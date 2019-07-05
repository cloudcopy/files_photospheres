<?php

/**
 * Nextcloud - Files_PhotoSpheres
 *
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Robin Windey <ro.windey@gmail.com>
 *
 * @copyright Robin Windey 2019
 */

namespace OCA\Files_PhotoSpheres\Service;

use OCA\Files_PhotoSpheres\Model\XmpResultModel;

interface IShareService {
    
    /**
     * 
     * @param string $shareToken
     * @return XmpResultModel 
     */
    function getXmpData($shareToken) : XmpResultModel;
}
