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

    private $linkBuilderService;

    public function __construct(GetProductVideoQueryHandler $queryHandler, 
        LinkBuilderService $linkBuilderService
    ){
        $this->queryHandler = $queryHandler;
        $this->linkBuilderService = $linkBuilderService;
    }

    /**
     * ActionPresentProductListing hook logic
     *
     * @param array $params
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

        /*  
        $presentedProduct is an object so we can't modify it as a typical array
        Insted of it use temporary array */
        $cover = $presentedProduct['cover'];

        $cover['is_video'] = true;
        $cover['video_url'] = $videoUrl;

        $presentedProduct['cover'] = $cover;
    }
}
