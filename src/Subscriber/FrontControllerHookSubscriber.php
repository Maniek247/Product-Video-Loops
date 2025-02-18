<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Subscriber;

use PrestaShop\Module\ProductVideoLoops\CQRS\Query\GetProductVideoQuery;
use PrestaShop\Module\ProductVideoLoops\CQRS\QueryHandler\GetProductVideoQueryHandler;

final class FrontControllerHookSubscriber
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
     * ActionPresentProduct hook logic
     *
     * @param array $params
     */
    public function onActionPresentProduct(array $params): void
    {
        //echo '<pre>';
        //var_dump($params);
        //echo '</pre>';
        if (!isset($params['presentedProduct'])) {
            return;
        }
    
        /*TODO: 'presentedProduct' chyba nie jest tablicą, tylko obiektem LazyArray
        Na końcu tego kodu pojawiły się problemy z operacją przesunięcia tablicy - do weryfikacji */
        $presentedProduct = &$params['presentedProduct'];

        //echo '<pre>';
        //var_dump($presentedProduct);
        //echo '</pre>';
        if (!isset($presentedProduct['id_product'])) {
            return;
        }
        $idProduct = (int)$presentedProduct['id_product'];
        //echo '<pre>';
        //var_dump($idProduct);
        //echo '</pre>';

        /* Pobranie info z DB
        GetProductVideoQuery($idProduct) zwraca encję ProductVideo lub null na podstawie konkretnego id*/
        $productVideo = $this->queryHandler->handle(new GetProductVideoQuery($idProduct));
        if (!$productVideo) {
            return;
        }
        //echo '<pre>';
        //var_dump($productVideo);
        //echo '</pre>';

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
            'video_url' => 'https://adamdebesciak.eu/img/videoloops/67a34dc77a4f9-Make_perfect_jewellery_product_ad_seed723238620.mp4',  //TODO: Link builder method
        ];       
        
        // Pobranie zdjęć do tymczasowej tablicy
        $images = $presentedProduct['images'];
        if (!is_array($images)) {
            $images = [];
        }
        //echo '<pre>';
        //echo '<p>shift</p>';
        //var_dump($images);
        //echo '</pre>';

        // przesunięcie w tablicy zdjęć
        foreach ($images as &$element) {
            if (isset($element['position'])){
                $element['position']++;
            }
            if (isset($element['cover']) && ($element['cover'] != 0)){
                $element['cover'] = 0;
            }
        }
        unset($element);
        //echo '<pre>';
        //echo '<p>shift</p>';
        //var_dump($images);
        //echo '</pre>';

        // Element video idzie na początek tablicy zdjęć
        array_unshift($images, $videoArray);

        // Przypisanie tymczasowej tablicy do obiektu LazyArray
        $presentedProduct['images'] = $images;

        $presentedProduct['default_image'] = $videoArray;
    }

    private function buildVideoUrl(string $filename): string
    {
        // TODO: generowanie linka z odpowiednim http/https
        return _PS_BASE_URL_.__PS_BASE_URI__.'img/videoloops/'.$filename;
    }
}
