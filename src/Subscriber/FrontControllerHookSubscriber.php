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
     * Logika obsługująca hook 'actionPresentProduct'.
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
    
        /*'presentedProduct' chyba nie jest tablicą, tylko obiektem Lazyxxxx
        Na końcu tego kodu pojawiły się problemy z operacją przesunięcia tablicy - do weryfikacji */
        $presentedProduct = &$params['presentedProduct'];

        if (!isset($presentedProduct['id_product'])) {
            return;
        }
        $idProduct = (int) $presentedProduct['id_product'];
        //echo '<pre>';
        //var_dump($idProduct);
        //echo '</pre>';

        /* Pobranie info z DB
        GetProductVideoQuery($idProduct) zwraca encję ProductVideo lub null na podstawie konkretnego id*/
        $productVideo = $this->queryHandler->handle(new GetProductVideoQuery($idProduct));
        if (!$productVideo) {
            return;
        }
        echo '<pre>';
        var_dump($productVideo);
        echo '</pre>';

        // URL do obrazka zastępczego dla wideo (thumbnail)
        $videoThumb = 'https://adamdebesciak.eu/img/videoloops/dodge%20felgi.jpg';

        // VideoArray + inne klucze wymagane przez szablony(bySize, small, medium, large)
        $videoArray = [
            'id_image'  => $idProduct,
            'legend'    => 'Product video',
            'position'  => 1,
            'cover'     => 1,
            'is_video'  => true,
            'video_url' => 'https://adamdebesciak.eu/img/videoloops/67a34dc77a4f9-Make_perfect_jewellery_product_ad_seed723238620.mp4', //$this->buildVideoUrl($productVideo->filename),
            // Klucze potrzebne przez inne pliki tpl
            'bySize'    => [
                'small_default' => [
                    'url'     => $videoThumb,
                    'width'   => 98,
                    'height'  => 98,
                    'sources' => []
                ],
                'medium_default' => [
                    'url'     => $videoThumb,
                    'width'   => 250,
                    'height'  => 250,
                    'sources' => []
                ],
                'large_default' => [
                    'url'     => $videoThumb,
                    'width'   => 800,
                    'height'  => 800,
                    'sources' => []
                ],
            ],
            'small'  => [
                'url'     => $videoThumb,
                'width'   => 98,
                'height'  => 98,
                'sources' => []
            ],
            'medium' => [
                'url'     => $videoThumb,
                'width'   => 250,
                'height'  => 250,
                'sources' => []
            ],
            'large'  => [
                'url'     => $videoThumb,
                'width'   => 800,
                'height'  => 800,
                'sources' => []
            ],
        ];

        // Pobranie zdjęć do tymczasowej tablicy
        $images = $presentedProduct['images'];
        if (!is_array($images)) {
            $images = [];
        }
        //echo '<pre>';
        //echo '<p>shift</p>';
        //var_dump($images['position']);
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

        // Przypisanie tymczasowej tablicy do tablicy głównej
        $presentedProduct['images'] = $images;
        echo '<pre>';
        echo '<p>shift</p>';
        var_dump($presentedProduct['images']);
        echo '</pre>';
        $presentedProduct['default_image'];
        echo '<pre>';
        var_dump($presentedProduct['default_image']);
        echo '</pre>';
    }

    private function buildVideoUrl(string $filename): string
    {
        // Załóżmy, że uploadujesz do /img/videoloops/
        // Dla pewności użyj linku wygenerowanego przez Link::getBaseLink() itp., 
        // albo stwórz proste:
        // dokończyć budowę tego: $baseLink = Link::getBaseLink();
        return _PS_BASE_URL_.__PS_BASE_URI__.'img/videoloops/'.$filename;
    }
}
