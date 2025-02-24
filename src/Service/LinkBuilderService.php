<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use Tools;

class LinkBuilderService
{
    public function buildVideoFolderPath(): string
    {
        return _PS_CORE_IMG_DIR_ . 'videoloops/';
    }

    public function buildVideoURL(): string
    {
        $sslAndDomain = Tools::getShopDomainSsl(true);

        return $sslAndDomain . __PS_BASE_URI__ . 'img/videoloops/';
    }
}