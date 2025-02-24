<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use PrestaShop\Module\ProductVideoLoops\Factory\ProductVideoFactory;
use PrestaShop\Module\ProductVideoLoops\Service\LinkBuilderService;

class ProductVideoPreviewService
{

    private $videoFactory;

    private $linkBuilderService;

    public function __construct(ProductVideoFactory $videoFactory, LinkBuilderService $linkBuilderService)
    {
        $this->videoFactory = $videoFactory;
        $this->linkBuilderService = $linkBuilderService;
    }

    /**
     * Generate HTML with video linked to product ID
     *
     * @param $productId
     *
     * @return string HTML video code
     */
    public function getPreviewHtml(int $productId): string
    {
        $video = $this->videoFactory->createProductVideo($productId);
        if (!$video->id) {
            return '';
        }

        $folderUrl = $this->linkBuilderService->buildVideoURL();
        $videoUrl = $folderUrl . $video->filename;

        $html = '<video width="auto" height="200" autoplay playsinline muted loop>
                <source src="' . $videoUrl . '" type="video/mp4" />
                Twoja przeglądarka nie wspiera wyświetlania wideo.
        </video>';

        return $html;
    }
}
