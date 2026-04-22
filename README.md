# Imager X Storage Driver for Microsoft Azure blob

This is an external storage driver for Imager X that uploads your Imager transforms to Microsoft Azure's blob storage.

## Requirements

This plugin requires Craft CMS 5.0.0 or later, and Imager X 3.0 or later. External storages are only available in the Pro edition of Imager.

## Installation

To install the plugin, follow these instructions:

1. Add `"paragonn/craft-imager-x-azure-blob": "dev-craft5"` to the requried packages in your `composer.json`
2. Add the following to the `repositories` array in your `composer.json`:
```
{
    "type": "vcs",
    "url": "https://github.com/motumb2b/craft-imager-x-azure-blob",
    "no-api": true
}
```
3. Run `composer update`
4. Install the plugin in the Craft Control Panel under Settings > Plugins

## Configuration

Configure the storage driver by adding new key named `azure` to the `storagesConfig` config setting in your **imager-x.php config file**, with the following configuration:

    'storageConfig' => [
        'azure' => [
            'endpoint' => '',
            'accessKey' => '',
            'secretAccessKey' => '',
            'region' => '',
            'bucket' => '',
            'folder' => '',
            'requestHeaders' => array(),
        ]
    ],

Enable the storage driver by adding the key `azure` to Imager's `storages` config setting:

    'storages' => ['azure'],

Here's an example config, note that the endpoint has to be a complete URL with scheme, and as always you need to make sure that `imagerUrl` is pointed to the right location:

    'imagerUrl' => 'https://foo.blob.core.windows.net/site/imager/',
    'storages' => ['azure'],
    'storageConfig' => [
        'azure'  => [
            'endpoint' => 'https://foo.blob.core.windows.net/site/',
            'connectionString' => 'MY_CONNECTION_STRING',
            'container' => 'MY_CONTAINER',
            'folder' => 'imager',
            'requestHeaders' => array(),
        ]
    ],

Also remember to always empty your Imager transforms cache when adding or removing external storages, as the transforms won't be uploaded if the transform already exists in the cache.


Price, license and support
---
The plugin is released under the MIT license. It requires Imager X Pro, which is a commercial plugin [available in the Craft plugin store](https://plugins.craftcms.com/imager-x).