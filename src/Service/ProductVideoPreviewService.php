<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use PrestaShop\Module\ProductVideoLoops\Factory\ProductVideoFactory;
use PrestaShop\Module\ProductVideoLoops\Service\LinkBuilderService;

class ProductVideoPreviewService
{
    /**
     * @var ProductVideoFactory
     */
    private $videoFactory;

    /**
     * @var LinkBuilderService
     */
    private $linkBuilderService;

    /**
     * @param ProductVideoFactory $videoFactory
     * @param LinkBuilderService $linkBuilderService
     */
    public function __construct(ProductVideoFactory $videoFactory, LinkBuilderService $linkBuilderService)
    {
        $this->videoFactory = $videoFactory;
        $this->linkBuilderService = $linkBuilderService;
    }

    /**
     * Generates an HTML snippet to preview a video associated with a given product ID
     *
     * If no video is available for the product, returns an empty string
     *
     * @param int $productId
     *
     * @return string HTML snippet for video preview
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
        </video>';

        return $html;
    }
}
