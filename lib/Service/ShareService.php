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

use OCA\Files_PhotoSpheres\Service\Helper\IXmpDataReader;
use OCP\Share\IManager as ShareManager;
use OCP\Share\Exceptions\ShareNotFound;
use OCP\Files\NotFoundException;
use OCA\Files_PhotoSpheres\Model\XmpResultModel;

/**
 * class ShareService
 *
 * @package OCA\Files_PhotoSpheres\Service;
 */
class ShareService implements IShareService {

    /**
     * @var ShareManager 
     */
    private $shareManager;

    /**
     *
     * @var IXmpDataReader
     */
    private $xmpDataReader;

    public function __construct(ShareManager $shareManager, IXmpDataReader $xmpDataReader) {
        $this->shareManager = $shareManager;
        $this->xmpDataReader = $xmpDataReader;
    }

    /**
     * 
     * @param string $shareToken
     * @param string $filename 
     * @param string $path 
     * @return array
     */
    public function getXmpData($shareToken, $filename = '', $path = ''): XmpResultModel {
        // This parts are adapted from OCA\Files_Sharing\Controller
        try {
            $share = $this->shareManager->getShareByToken($shareToken);
        } catch (ShareNotFound $e) {
            throw new \Exception('Share not found');
        }

        if (!($share->getPermissions() & \OCP\Constants::PERMISSION_READ)) {
            return new \OCP\AppFramework\Http\DataResponse('Share is read-only');
        }

        if (!$this->validateShare($share)) {
            throw new \Exception('Share not found');
        }

        $shareNode = $share->getNode();

        // Single file share
        if ($shareNode instanceof \OCP\Files\File) {
            return $this->xmpDataReader->readXmpDataFromFileObject($shareNode);
        }
        // Directory share
        else {
            if ($filename === '' || $path === '') {
                throw new \Exception('Information must contain filename and path');
            }

            try {
                /** @var \OCP\Files\Folder $shareNode */
                $shareNode = $shareNode->get($path);
            } catch (NotFoundException $e) {
                throw new \Exception('Share not found');
            }

            if (!($shareNode instanceof \OCP\Files\File)){
                // Directory containing the file -> read the file by name
                $shareNode = $shareNode->get($filename);
            }
            
            return $this->xmpDataReader->readXmpDataFromFileObject($shareNode);
        }
    }

    /**
     * Validate the permissions of the share
     *
     * @param Share\IShare $share
     * @return bool
     */
    private function validateShare(\OCP\Share\IShare $share) {
        return $share->getNode()->isReadable() && $share->getNode()->isShareable();
    }

}
