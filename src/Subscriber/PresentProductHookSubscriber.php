<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Subscriber;

use PrestaShop\Module\ProductVideoLoops\CQRS\Query\GetProductVideoQuery;
use PrestaShop\Module\ProductVideoLoops\CQRS\QueryHandler\GetProductVideoQueryHandler;
use PrestaShop\Module\ProductVideoLoops\Service\LinkBuilderService;

final class PresentProductHookSubscriber
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
     * ActionPresentProduct hook logic
     *
     * @param array $params
     */
    public function onActionPresentProduct(array $params): void
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

        $videoArray = [
            'cover' => 1,
            'id_image' => 777,
            'legend' => 'Product video',
            'position' => 1,
            // Some templates may need jpg thumbnail in cart, invoice etc. so it can be passed below in arrays if needed
            'bySize' => [
                'small_default' => [
                    'url'     => '',
                    'width'   => 98,
                    'height'  => 98,
                    'sources' => [],
                ],
                'cart_default' => [
                    'url'     => '',
                    'width'   => 125,
                    'height'  => 125,
                    'sources' => [],
                ],
                'home_default' => [
                    'url'     => '',
                    'width'   => 250,
                    'height'  => 250,
                    'sources' => [],
                ],
                'medium_default' => [
                    'url'     => '',
                    'width'   => 452,
                    'height'  => 452,
                    'sources' => [],
                ],
                'large_default' => [
                    'url'     => '',
                    'width'   => 800,
                    'height'  => 800,
                    'sources' => [],
                ],
            ],
            'small' => [
                'url'     => '',
                'width'   => 98,
                'height'  => 98,
                'sources' => [],
            ],
            'medium' => [
                'url'     => '',
                'width'   => 250,
                'height'  => 250,
                'sources' => [],
            ],
            'large' => [
                'url'     => '',
                'width'   => 800,
                'height'  => 800,
                'sources' => [],
            ],
            'associatedVariants' => [],
            'is_video'  => true,
            'video_url' => $videoUrl,
        ];       
        
        /*  
        $presentedProduct is an object so we can't modify it as a typical array
        Insted of it use temporary array */
        $images = $presentedProduct['images'];
        if (!is_array($images)) {
            $images = [];
        }

        foreach ($images as &$image) {
            if (isset($image['position'])){
                $image['position']++;
            }
            if (isset($image['cover']) && ($image['cover'] != 0)){
                $image['cover'] = 0;
            }
        }
        unset($image);

        array_unshift($images, $videoArray);

        $presentedProduct['images'] = $images;
        $presentedProduct['default_image'] = $videoArray;
    }
}
