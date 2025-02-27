<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use Tools;

class LinkBuilderService
{
    /**
     * Builds folder path for video files
     *
     * @return string Path to the video storage folder
     */
    public function buildVideoFolderPath(): string
    {
        return _PS_CORE_IMG_DIR_ . 'videoloops/';
    }

    /**
     * Builds URL for accessing video files
     *
     * @return string URL to the video folder
     */
    public function buildVideoURL(): string
    {
        $sslAndDomain = Tools::getShopDomainSsl(true);

        return $sslAndDomain . __PS_BASE_URI__ . 'img/videoloops/';
    }
}