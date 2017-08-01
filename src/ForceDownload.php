<?php
/**
 * ForceDownload Plugin for Craft CMS 3.x
 *
 * @link      https://mdxdave.de
 * @copyright Copyright (c) 2017 MDXDave
 */

namespace mdxdave\forcedownload;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

class ForceDownload extends Plugin
{

    public static $plugin;

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->twig->addExtension(new twigextensions\ForceDownloadTwigExtension());

        Craft::info(
            Craft::t(
                'force-download',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }
}
