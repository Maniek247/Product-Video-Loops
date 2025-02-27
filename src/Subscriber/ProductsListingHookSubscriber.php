<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Subscriber;

use PrestaShop\Module\ProductVideoLoops\CQRS\Query\GetProductVideoQuery;
use PrestaShop\Module\ProductVideoLoops\CQRS\QueryHandler\GetProductVideoQueryHandler;
use PrestaShop\Module\ProductVideoLoops\Service\LinkBuilderService;

final class ProductsListingHookSubscriber
{
    /**
     * @var GetProductVideoQueryHandler
     */
    private $queryHandler;

    /**
     * @var LinkBuilderService
     */
    private $linkBuilderService;

    /**
     * @param GetProductVideoQueryHandler $queryHandler
     * @param LinkBuilderService $linkBuilderService
     */
    public function __construct(GetProductVideoQueryHandler $queryHandler, 
        LinkBuilderService $linkBuilderService
    ){
        $this->queryHandler = $queryHandler;
        $this->linkBuilderService = $linkBuilderService;
    }

    /**
     * Modifies the presented product data by adding video information if available.
     * 
     * This method is triggered by the ActionPresentProductListing hook. It checks if the presented 
     * product has an associated video and, if so, modifies its cover data to include video
     *
     * @param array $params An associative array containing the presented product data.
     * 
     * @return void
     */
    public function onActionPresentProductListing(array $params): void
    {
        if (!isset($params['presentedProduct'])) {
            return;
        }
        $presentedProduct = &$params['presentedProduct'];

        if (!isset($presentedProduct['id_product'])) {
            return;
        }
        $idProduct = (int)$presentedProduct['id_product'];

        $productVideo = $this->queryHandler->handle(new GetProductVideoQuery($idProduct));
        if (!$productVideo) {
            return;
        }

        $folderUrl = $this->linkBuilderService->buildVideoURL();
        $videoUrl = $folderUrl . $productVideo->filename;

        // Clone cover array to modify it, as $presentedProduct is an LazyArray object
        $cover = $presentedProduct['cover'];

        $cover['is_video'] = true;
        $cover['video_url'] = $videoUrl;

        $presentedProduct['cover'] = $cover;
    }
}
