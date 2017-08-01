# ForceDownload plugin for Craft CMS 3.x

Simple Downloader for Craft CMS 3.0.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require mdxdave/force-download

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for ForceDownload.

## Using ForceDownload

Just use the following code in your Twig template file (whereas __filename__ is an asset field):
        
        {{ entry.filename[0]|forceDownload }}
        
To use a rudimental download counter, use the fieldname as 2nd param (the __downloadCounter__ field will be inceremented by 1):
        
        {{ entry.filename[0]|forceDownload("downloadCounter") }}



Brought to you by [MDXDave](https://mdxdave.de)
