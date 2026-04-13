<?php
/**
 * Digital Ocean Spaces external storage driver for Imager X
 *
 * @link      https://www.spacecat.ninja/
 * @copyright Copyright (c) 2020 André Elvan
 */

namespace paragonn\ImagerxAzureBlobStorage\externalstorage;

use Craft;
use craft\helpers\FileHelper;
use spacecatninja\imagerx\models\ConfigModel;
use spacecatninja\imagerx\services\ImagerService;
use spacecatninja\imagerx\externalstorage\ImagerStorageInterface;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use League\Flysystem\Filesystem;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;

use yii\web\Controller;
use yii\web\UploadedFile;

class AzureStorage implements ImagerStorageInterface
{
    /**
     * @param string $file
     * @param string $uri
     * @param bool $isFinal
     * @param array $settings
     * @return bool
     */
    public static function upload(string $file, string $uri, bool $isFinal, array $settings)
    {

        /** @var ConfigModel $settings */
        $config = ImagerService::getConfig();

        $connectionString = Craft::parseEnv($settings['connectionString']);

        try {
            $client = static::client($connectionString);
        } catch (\InvalidArgumentException $e) {
            Craft::error('Invalid configuration of Azure Client: ' . $e->getMessage(), __METHOD__);
            return false;
        }

        if (isset($settings['folder']) && $settings['folder'] !== '') {
            $uri = ltrim(FileHelper::normalizePath(Craft::parseEnv($settings['folder']) . '/' . $uri), '/');
        }

        // Always use forward slashes for Azure
        $uri = str_replace('\\', '/', $uri);

        $adapter = new AzureBlobStorageAdapter($client, Craft::parseEnv($settings['container']));
        $filesystem = new Filesystem($adapter);
        $stream = fopen($file, 'r+');
        $filesystem->writeStream($uri, $stream);
        // $filesystem->updateStream($uri, $stream);
        // fclose($stream);

        /*$opts = $settings['requestHeaders'];
        $cacheDuration = $isFinal ? $config->cacheDurationExternalStorage : $config->cacheDurationNonOptimized;
        if (! isset($opts['Cache-Control'])) {
            $opts['CacheControl'] = 'max-age=' . $cacheDuration . ', must-revalidate';
        }*/

        return true;
    }

    /**
     * Get the Azure Blob Storage client.
     *
     * @param string $connectionString Connection string to use
     * @return BlobRestProxy
     */
    protected static function client(string $connectionString): BlobRestProxy
    {
        return BlobRestProxy::createBlobService($connectionString);
    }
}