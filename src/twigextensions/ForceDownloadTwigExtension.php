<?php
/**
 * ForceDownload Plugin for Craft CMS 3.x
 *
 * @link      https://mdxdave.de
 * @copyright Copyright (c) 2017 MDXDave
 */

namespace mdxdave\forcedownload\twigextensions;

use mdxdave\forcedownload\ForceDownload;
use Craft;

class ForceDownloadTwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'ForceDownload';
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('forceDownload', [$this, 'forceDownload']),
        ];
    }

    function forceDownload($asset, $downloadCounter=null) {
        if($downloadCounter){
          $download = Craft::$app->urlManager->getMatchedElement();
          $download->setFieldValue($downloadCounter, $download->getFieldValue($downloadCounter)+1);
          Craft::$app->elements->saveElement($download);
        }
        $path = $this->_getFullDirectoryPath($asset);
        $filename = $asset->filename;
        $filepath = $path . '/' . $filename;
        header('Content-type: '.$asset->getMimeType());
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $asset->size);
        header('Accept-Ranges: bytes');
        readfile($filepath);
        exit();
    }
    
    private function _getFullDirectoryPath($file)
    {
        $volumeId = $file->volumeId;
        $volume = Craft::$app->getVolumes()->getVolumeById($volumeId);
        return $volume->path;
    }
}
