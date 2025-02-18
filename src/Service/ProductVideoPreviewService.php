<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use PrestaShop\Module\ProductVideoLoops\Factory\ProductVideoFactory;

class ProductVideoPreviewService
{

    private $videoFactory;

    public function __construct(ProductVideoFactory $videoFactory)
    {
        $this->videoFactory = $videoFactory;
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

        // TODO: Url https
        $videoUrl = _PS_BASE_URL_.__PS_BASE_URI__.'img/videoloops/'.$video->filename;

        $html = '<video width="auto" height="200" autoplay playsinline muted loop>
                <source src="'.$videoUrl.'" type="video/mp4" />
                Twoja przeglądarka nie wspiera wyświetlania wideo.
        </video>';

        return $html;
    }
}
