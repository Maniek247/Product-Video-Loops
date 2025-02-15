<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Subscriber;

use PrestaShop\Module\ProductVideoLoops\CQRS\Query\GetProductVideoQuery;
use PrestaShop\Module\ProductVideoLoops\CQRS\QueryHandler\GetProductVideoQueryHandler;

final class ProductsListingHookSubscriber
{
    /**
     * @var GetProductVideoQueryHandler
     */
    private $queryHandler;

    public function __construct(GetProductVideoQueryHandler $queryHandler)
    {
        $this->queryHandler = $queryHandler;
    }

    /**
     * Logika obsługująca hook 'actionPresentProductListing'.
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

        $videoThumb = 'https://adamdebesciak.eu/img/videoloops/dodge%20felgi.jpg';

        /*  
        $presentedProduct is an object so we can't modify it as a typical array
        Insted of it we using temporary array */
        $cover = $presentedProduct['cover'];

        // TODO: check if presta needs $videoThumb and if, build temporary arrays 
        /*if (isset($presentedProduct['value']['bySize']['home_default'])) {
            $presentedProduct['value']['bySize']['home_default']['url'] = $videoThumb;
            $presentedProduct['value']['bySize']['home_default']['sources']['jpg'] = $videoThumb;
        }*/

        $cover['is_video'] = true;
        $cover['video_url'] = 'https://adamdebesciak.eu/img/videoloops/67a34dc77a4f9-Make_perfect_jewellery_product_ad_seed723238620.mp4';

        $presentedProduct['cover'] = $cover;
        //echo '<pre>';
        //var_dump($params['presentedProduct']);
        //echo '</pre>';
    }
}
