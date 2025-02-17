<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Factory;

use PrestaShop\Module\ProductVideoLoops\Entity\ProductVideo;

class ProductVideoFactory
{
    /**
     * Factory that creates ProductVideo entity based on specify product id
     */
    public function createProductVideo(int $productId): ProductVideo
    {
        return new ProductVideo($productId);
    }    
}