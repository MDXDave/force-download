<?php
/**
 * ForceDownload Plugin for Craft CMS 3.1.x
 *
 * @link      https://mdxdave.de
 * @copyright Copyright (c) 2017-2019 MDXDave
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
            new \Twig_SimpleFilter('downloadButton', [$this, 'downloadButton']),
        ];
    }
    
    public function getFunctions(){
        return [
          new \Twig_SimpleFunction("showDownloadButton", [$this, 'showDownloadButton']),
        ];
    }
    
    function showDownloadButton($id, $desc=true){
      $formatter = Craft::$app->getFormatter();
      if(is_numeric($id))
        $asset = Craft::$app->elements->getElementById($id);
      else
        $asset = Craft::$app->elements->getElementByUri($id);
        
      $path = $this->_getFullDirectoryPath($asset->filename[0]);
      $output = '<br /><div class="ui grid center aligned">
      <div class="ui raised compact segment olive" style="min-width: 70%;">
        <h3 style="text-align: center">'. $asset->filename[0]->filename .'</h3>
        <div class="ui three statistics mini">
            <div class="statistic blue">
                <div class="value">'. $formatter->asShortSize($asset->filename[0]->size) .'</div>
                <div class="label">Größe</div>
            </div>
            <div class="statistic green">
                <div class="value">'. $asset->downloadCount .'</div>
                <div class="label">Downloads</div>
            </div>
            <div class="statistic">
                <div class="value">'. $asset->filename[0]->extension .'</div>
                <div class="label">Dateityp</div>
            </div>
        </div>';
        if($desc)
              $output .= '<p>'. $asset->text .'</p>';
      $output .= '<div class="ui grid center aligned"><div class="column twelve wide" style="margin: 10px;">
    <form method="post" action="'. $asset->url .'?checksum='. hash("sha512", $asset->filename[0]->filename) .'&do=download">
        <input type="hidden" name="'. Craft::$app->config->general->csrfTokenName .'" value="'. Craft::$app->request->csrfToken .'">
        <button id="downloadButton" class="ui labeled icon button green big">
            <i class="download icon white"></i> Herunterladen
        </button>
    </form></div></div></div></div><br />';
    return \Craft\helpers\Template::raw($output);
     
    }
    
    function downloadButton($raw){      
        $raw = preg_replace_callback("/\[download\]([0-9a-z-\/]*)\[\/download\]/", function($match){
          return $this->showDownloadButton($match[1], true);
        }, $raw);
        return $raw;    
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
        if($volume->url != null)
          return $volume->url;
        else
          return $volume->path;
    }
}
