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

namespace OCA\Files_PhotoSpheres\AppInfo;

use OCP\AppFramework\App;
use OCP\Util;

/**
 * class Application
 *
 * @package OCA\Files_PhotoSpheres\AppInfo
 */
class Application extends App {
    
        const APP_NAME = 'files_photospheres';
    
	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_NAME, $urlParams);
        }
        
        public function init(){
            $this->registerHooks();
        }

        private function registerHooks(){
            $eventDispatcher = \OC::$server->getEventDispatcher();
            
            $eventDispatcher->addListener('OCA\Files::loadAdditionalScripts', function() {
              script(self::APP_NAME, 'fileAction');  
            });
        }       
}


